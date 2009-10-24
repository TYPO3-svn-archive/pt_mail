<?php
/***************************************************************
 *  Copyright notice
 *  
 *  (c) 2008 Ursula Klinger (klinger@punkt.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is 
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 * 
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 * 
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/** 
 * email class for the 'pt_mail' extension
 *
 * $Id: class.tx_ptmail_mail.php,v 1.18 2009/01/07 16:49:39 ry44 Exp $
 *
 * @author	Ursula Klinger <klinger@punkt.dee>
 * @since   2008-10-01
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */

/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_address.php';
require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_addressCollection.php'; // extension specific collection class
require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_attachment.php';
require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_attachmentCollection.php'; // extension specific collection class
require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_t3lib_htmlmailAdapter.php';

/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools') . 'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools') . 'res/staticlib/class.tx_pttools_assert.php';
require_once t3lib_extMgm::extPath('pt_tools') . 'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools') . 'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools') . 'res/objects/class.tx_pttools_smartyAdapter.php';
require_once t3lib_extMgm::extPath('cms') . 'tslib/class.tslib_content.php';
require_once t3lib_extMgm::extPath('cms') . 'tslib/class.tslib_fe.php';
require_once (PATH_t3lib . 'class.t3lib_timetrack.php');
require_once (PATH_t3lib . 'class.t3lib_tstemplate.php');



/**
 * attachment class
 *
 * @author	    Ursula Klinger <klinger@punkt.dee>
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_mail {
	/**
	 * Properties
	 */
	
	/**
	 * @var array  cobfiguration of the mail
	 */
	protected $conf;
	
	/**
	 * @var string  language of the mail
	 */
	protected $language;
	
	/**
	 * @var string charset of the template string for the mail
	 */
	protected $templateCharset;
	
	/**
	 * @var string charset of the mail
	 */
	protected $mailCharset;
	
	/**
	 * @var tx_ptmail_addressCollection  all bcc addresses
	 */
	protected $bcc = NULL;
	
	/**
	 * @var tx_ptmail_addressCollection  all cc addresses
	 */
	protected $cc = NULL;
	
	/**
	 * @var tx_ptmail_addressCollection  all to addresses
	 */
	protected $to = NULL;
	
	/**
	 * @var tx_ptmail_address reply address
	 */
	protected $reply = NULL;
	
	/**
	 * @var tx_ptmail_address from address
	 */
	protected $from = NULL;
	
	/**
	 * @var tx_ptmail_attachmentCollection  all attachments
	 */
	protected $attachments;
	
	/**
	 * @var tslib_cObj object needed for the TS
	 */
	protected $cObj;
	
	/**
	 * @var string   subject of the mail
	 */
	protected $subject;
	
	/**
	 * @var string   header of the body of the mail
	 */
	protected $bodyHeader;
	
	/**
	 * @var string   body of the mail
	 */
	protected $body;
	
	/**
	 * @var string   footer of the body of the mail
	 */
	protected $bodyFooter;
	
	/**
	 * @var string   header of the body of the html mail
	 */
	protected $htmlBodyHeader;
	
	/**
	 * @var string   body of the html mail
	 */
	protected $htmlBody;
	
	/**
	 * @var string   footer of the body of the html mail
	 */
	protected $htmlBodyFooter;
	
	
	/**
	 * @var tx_ptmail_iDriver if not set tx_ptmail_t3lib_htmlmailAdapter 
	 */
	protected $driver;
	
	/**
	 * @var tx_ptmail_additionalHeaderCollection  all additional header entries 
	 */
	protected $additionalHeaders;
	
	/**
	 * @var string organisation write in the header of the mail
	 */
	protected $organisation;
	
	/**
	 * @var bool	if true, this class will not attempt to load typoscript configuration if it is called in a non TSFE context
	 */
	protected $ignoreTSinNonTSFEContext = false;
	
	/**
	 * @var string  mail identifier (optional)
	 */
	protected $maildId = '';
	
	/**
	 * Class Constants
	 */
	const EXT_KEY = 'pt_mail';



	/***************************************************************************
	 *   CONSTRUCTOR
	 **************************************************************************/
	
	/**
	 * Class constructor: creates an email object
	 *
	 * @param   object  (optional) parent object (if extKey and/or prefixId properties available, extension/prefixId specific configuration will be loaded)
	 * @param   string  (optional) mail id
	 * @param   array   (optional) local configuration 
	 * @param   string	(optional) language
	 * @param   bool    (optional) ignore TSFE config if not in TSFE context (will be loaded otherwise)
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-01
	 */
	public function __construct($pObj = NULL, $mailId = '', array $conf = array(), $language = '', $ignoreTSinNonTSFEContext = false) {
		
		if (!empty($mailId)) {
			$this->maildId = $mailId;
		}
		
		if (empty($language) && ($GLOBALS['TSFE'] instanceof tslib_fe)) {
			$language = $GLOBALS['TSFE']->lang;
		}
		$this->language = $language;
		
		$this->ignoreTSinNonTSFEContext = $ignoreTSinNonTSFEContext;
		
		$this->loadAndSetConfiguration($pObj, $conf);
	}



	/***************************************************************************
	 *   Business METHODS
	 **************************************************************************/
	
	/**
	 * prepare the mail and send it
	 *
	 * @param   array   (optional) array of the placeholder and values
	 * @return  bool	true if mail was sent, false otherwise 
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	public function send(array $markerArray = array()) {
		
		tx_pttools_assert::isInstanceOf($this->get_to(), 'tx_ptmail_addressCollection', array('message' => 'No "to" address defined'));
		tx_pttools_assert::isInstanceOf($this->get_from(), 'tx_ptmail_address', array('message' => 'No "from" address defined'));
		
		if (is_null($this->driver)) {
			$this->driver = new tx_ptmail_t3lib_htmlmailAdapter();
		}
		
		$this->driver->setFrom($this->get_from());
		
		if ($this->get_reply() instanceof tx_ptmail_address) {
			$this->driver->setReply($this->get_reply());
		} else {
			$this->driver->setReply($this->get_from());
		}
		
		if (!$this->conf['developmentMode']) {
			$this->driver->setTo($this->get_to());
		} elseif (!empty($this->conf['developmentModeReceiver'])) {
			if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Sending mail in development mode only to "%s"', $this->conf['developmentModeReceiver']), 'pt_mail');
			$developmentModeReceiver = new tx_ptmail_addressCollection($this->conf['developmentModeReceiver']); 
			$this->driver->setTo($developmentModeReceiver);
		} else {
			if (TYPO3_DLOG) t3lib_div::devLog('No "developmentModeReceiver" set, so no mail will be send', 'pt_mail');
			// if in developmentMode and no developmentModeReceiver address is set no mail will be sent
			return true;
		}
		
		if (!$this->conf['developmentMode'] && ($this->get_cc() instanceof tx_ptmail_addressCollection)) {
			$this->driver->setCc($this->get_cc());
		}
		
		if (!$this->conf['developmentMode'] && ($this->get_bcc() instanceof tx_ptmail_addressCollection)) {
			$this->driver->setBcc($this->get_bcc());
		}
		
		if ($this->get_attachments() instanceof tx_ptmail_attachmentCollection) {
			$this->driver->setAttachment($this->get_attachments());
		}
		
		if ($this->get_additionalHeaders() != '') {
			$this->driver->setAdditionalHeaders($this->get_additionalHeaders());
		}
		
		if (strlen(trim($this->get_mailCharset())) > 0) {
			$this->driver->charset = $this->get_mailCharset();
		}
		
		$this->driver->set_templateCharset($this->get_templateCharset());
		
		if (strlen(trim($this->get_organisation())) > 0) {
			$this->driver->organisation = $this->get_organisation();
		}
		
		$subject = $this->replaceMarker($this->get_subject(), $markerArray);
		
		// prepend prefix if set
		if (!empty($this->conf['mailPrefix'])) {
			$subject = $this->conf['mailPrefix'] . ' ' . $subject;
		}
		
		$body = $this->getBodyOutput($markerArray);
		$htmlBody = $this->getHtmlBodyOutput($markerArray);
		
		if ($this->conf['developmentMode']) {
			$subject = '[pt_mail development mode] ' . $subject;
			$originalBody = $body;
			$body = '==== pt_mail development mode ====';
			
			$body .= chr(10).'Original "to" receivers:'.chr(10);
			foreach ($this->get_to() as $address) { /* @var $address tx_ptmail_address */
				$body .= '   '. $address->__toString() . chr(10);	
			}
			
			if ($this->get_cc() instanceof tx_ptmail_addressCollection) {
				$body .= chr(10).'Original "cc" receivers:'.chr(10);
				foreach ($this->get_cc() as $address) { /* @var $address tx_ptmail_address */
					$body .= '   '. $address->__toString() . chr(10);	
				}
			}
			
			if ($this->get_bcc() instanceof tx_ptmail_addressCollection) {
				$body .= chr(10).'Original "bcc" receivers:'.chr(10);
				foreach ($this->get_bcc() as $address) { /* @var $address tx_ptmail_address */
					$body .= '   '. $address->__toString() . chr(10);	
				}
			}
			
			$body .= chr(10).'==== Original body ==============='.chr(10);
			$body .= $originalBody;
			$body .= chr(10).'=================================='.chr(10);
		}
		
		$this->driver->setSubject($subject);
		$this->driver->setBody($body);
		$this->driver->setHtmlBody($htmlBody);
		
		if (!empty($this->conf['returnPath'])) {
			if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Setting returnPath to "%s"', $this->conf['returnPath']), 'pt_mail');
			$this->driver->setReturnPath($this->conf['returnPath']);
		}
		
		if (TYPO3_DLOG) t3lib_div::devLog(
			'Sending the mail!' . ($this->conf['developmentMode'] ? ' (in development mode)' : ''), 
			'pt_mail', 
			0, 
			array(
				'to' => $this->conf['developmentMode'] ? $developmentModeReceiver->__toString() : $this->get_to()->__toString(),
				'cc' => (!$this->conf['developmentMode'] && ($this->get_cc() instanceof tx_ptmail_addressCollection)) ? $this->get_cc()->__toString() : '',
				'bcc' => (!$this->conf['developmentMode'] && ($this->get_bcc() instanceof tx_ptmail_addressCollection)) ? $this->get_bcc()->__toString() : '',
				'subject' => $subject,
				'body' => $body
			)
		);
		
		return $this->driver->send();
	}
	
	/**
	 * Send mail function (deprecated!)
	 *
	 * @deprecated 	use $this->send() instead!
	 * @param 		array   (optional) array of the placeholder and values
	 * @return 		bool	true if mail was sent, false otherwise 
	 * @author		Fabrizio Branca <branca@punkt.de>
	 * @since		2008-12-17
	 */
	public function sendMail(array $markerArray = array()) {
		return $this->send($markerArray);
	}

	/**
	 * Load and set configuration
	 *
	 * @param   object  (optional) parent object 
	 * @param   array   (optional) configuration array
	 * @return  void
	 * @author  Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-17
	 */
	protected function loadAndSetConfiguration($pObj = NULL, array $localConf = array()) {
		
		/***********************************************************************
		 * Load configuration
		 **********************************************************************/
		// get configuration from ext_localconf.php (unserialized config) of pt_mail
		$t3_confVars['pt_mail_extconf'] = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pt_mail'];
		
		// get 'from email' and 'from title' from pt_mail extension configuration
		$extConf = tx_pttools_div::returnExtConfArray(self::EXT_KEY);
		if (isset($extConf['fromEmail'])) {
			$t3_confVars['pt_mail_ext']['from.']['email'] = $extConf['fromEmail'];
		}
		if (isset($extConf['fromTitle'])) {
			$t3_confVars['pt_mail_ext']['from.']['title'] = $extConf['fromTitle'];
		}
		if (isset($extConf['templateCharset'])) {
			$t3_confVars['pt_mail_ext']['templateCharset'] = $extConf['templateCharset'];
		}
		if (isset($extConf['mailCharset'])) {
			$t3_confVars['pt_mail_ext']['mailCharset'] = $extConf['mailCharset'];
		}
		if (isset($extConf['developmentMode'])) {
			$t3_confVars['pt_mail_ext']['developmentMode'] = $extConf['developmentMode'];
		}
		if (isset($extConf['developmentModeReceiver'])) {
			$t3_confVars['pt_mail_ext']['developmentModeReceiver'] = $extConf['developmentModeReceiver'];
		}
		
		// get configuration from ext_localconf.php (unserialized config) of parent extension
		if (is_object($pObj) && property_exists($pObj, 'extKey')) {
			$t3_confVars[$pObj->extKey . '_extconf'] = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$pObj->extKey]['pt_mail'];
		}
		
		// get configuration from typoscript (global and plugin specific) if available
		if ($GLOBALS['TSFE'] instanceof tslib_fe) {
			
			// get global configuration from typoscript
			$t3_confVars['mail'] = tx_pttools_div::typoscriptRegistry('config.pt_mail.');
			
			// get extension specific configuration from typoscript (config.<extKey>.mail{})
			if (is_object($pObj) && property_exists($pObj, 'extKey')) {
				$t3_confVars[$pObj->extKey] = tx_pttools_div::typoscriptRegistry('config.' . $pObj->extKey . '.mail.');
			}
			
			// get plugin specific configuration from typoscript (plugin.<prefixId>.mail{})
			if (is_object($pObj) && property_exists($pObj, 'prefixId')) {
				$t3_confVars[$pObj->prefixId] = tx_pttools_div::typoscriptRegistry('plugin.' . $pObj->prefixId . '.mail.');
			}
			
			// mail-id specific configuration
			if (!empty($this->maildId)) {
				$t3_confVars[$this->maildId] = tx_pttools_div::typoscriptRegistry('config.pt_mail.mailId.' . $this->maildId . '.');
			}
		
		} elseif (!$this->ignoreTSinNonTSFEContext) {
			
			// get global configuration from typoscript
			$t3_confVars['mail'] = tx_pttools_div::typoscriptRegistry('config.pt_mail.', NULL, 'pt_mail', 'tsConfigurationPid');
			
			// get extension specific configuration from typoscript (config.<extKey>.mail{})
			if (is_object($pObj) && property_exists($pObj, 'extKey')) {
				$t3_confVars[$pObj->extKey] = tx_pttools_div::typoscriptRegistry('config.' . $pObj->extKey . '.mail.', NULL, 'pt_mail', 'tsConfigurationPid');
			}
			
			// get plugin specific configuration from typoscript (plugin.<prefixId>.mail{})
			if (is_object($pObj) && property_exists($pObj, 'prefixId')) {
				$t3_confVars[$pObj->prefixId] = tx_pttools_div::typoscriptRegistry('plugin.' . $pObj->prefixId . '.mail.', NULL, 'pt_mail', 'tsConfigurationPid');
			}
			
			// mail-id specific configuration
			if (!empty($this->maildId)) {
				$t3_confVars[$this->maildId] = tx_pttools_div::typoscriptRegistry('config.pt_mail.mailId.' . $this->maildId . '.', NULL, 'pt_mail', 'tsConfigurationPid');
			}
		
		} else {
			
			// not in TSFE context, but we don't want to look in the typoscript configuration
			// only configuration from (ext_)localconf.php files and passed local configuration will be used

		}
		
		// get local configuration from constructor
		$t3_confVars['local'] = $localConf;
		
		/***********************************************************************
		 * Set configuration
		 **********************************************************************/
		$this->conf = array();
		foreach ($t3_confVars as $key => $configuration) {
			if (is_array($configuration)) {
				if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Merging "%s" configuration to existing configuration.', $key), 'pt_mail');
				$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf, $configuration);
			}
		}
		if (TYPO3_DLOG) t3lib_div::devLog('Email configuration', 'pt_mail', 0, array('parts' => $t3_confVars, 'merged' => $this->conf));
		$fakeTsfe = false;
		// we need a cObj for the configuration cObjGetSingle
		if (($GLOBALS['TSFE'] instanceof tslib_fe) && ($GLOBALS['TSFE']->cObj instanceof tslib_cObj)) {
			$this->cObj = $GLOBALS['TSFE']->cObj;
		} else {
			$fakeTsfe = true;
			// backup current TSFE
			$currTsfe = $GLOBALS['TSFE'];
			if (!$GLOBALS['TSFE'] instanceof tslib_fe) {
				$TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');
				$GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], 0, '0', 1, '', '', '', '');
				$GLOBALS['TSFE']->cObjectDepthCounter = 100;
				$GLOBALS['TSFE']->initTemplate();
			}
			if (!$GLOBALS['TT'] instanceof t3lib_timeTrack) {
				$GLOBALS['TT'] = new t3lib_timeTrack();
			}
			$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		}

		foreach (array('to', 'cc', 'bcc') as $type) {
			$this->initRecipcients($type);
		}
		
		foreach (array('from', 'reply') as $type) {
			$this->initSender($type);
		}
		$this->initAdditionalHeader();
		
		foreach (array('subject', 'bodyHeader', 'body', 'bodyFooter') as $type) {
			if (isset($this->conf[$type]) && is_array($this->conf[$type . '.'])) {
				$template = $this->initSubpart($this->conf[$type], $this->conf[$type . '.']);
				$setter = 'set_' . $type;
				if (method_exists($this, $setter)) {
					$this->$setter($template);
				} else {
					throw new tx_pttools_exception('Method ' . $setter . ' does not exist');
				}
			}
		}
		
		if ($fakeTsfe) {
			// restore original TSFE
			unset($GLOBALS['TSFE']);
			$GLOBALS['TSFE'] = $currTsfe;
		}
		
		$this->set_mailCharset($this->conf['mailCharset']);
		if (isset($this->conf['templateCharset']) && strlen(trim($this->conf['templateCharset'])) > 0) {
			$this->set_templateCharset($this->conf['templateCharset']);
		} else {
			$this->set_templateCharset(tx_pttools_div::getSiteCharsetEncoding());
		}
		if (isset($this->conf['organisation'])) {
			$this->set_organisation($this->conf['organisation']);
		}
	
	}



	/**
	 * read additional header information from the configuration and if it exists set it to the additionalHeaderCollection 
	 *
	 * @param   void
	 * @return  void
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function initAdditionalHeader() {
		if (isset($this->conf['additionalHeaders.'])) {
			$additionalHeaders = new tx_ptmail_additionalHeaderCollection();
			foreach ($this->conf['additionalHeaders.'] as $header) {
				if (strlen(trim($header))) {
					$additionalHeaders->addItem(new tx_ptmail_additionalHeader($header));
				}
			}
			$this->set_additionalHeaders($additionalHeaders);
		}
	}



	/**
	 * read the recipcients information from the configuration and if it exists set it to addressCollection
	 *
	 * @param   string	type of recipcient (to,cc,bcc) 
	 * @return  void
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function initRecipcients($type) {
		if (isset($this->conf[$type . '.']) && is_array($this->conf[$type . '.'])) {
			$addresses = NULL;
			foreach ($this->conf[$type . '.'] as $address) {
				if (!empty($address['email'])) {
					if ($addresses == NULL) {
						$addresses = new tx_ptmail_addressCollection();
					}
					if (is_array($address) && !empty($address['email'])) {
						$addresses->addItem(new tx_ptmail_address($address['email'], $address['title']));
					} else {
						$addresses->addItem($address);
					}
						
				}
			}
			if ($addresses != NULL) {
				$setter = 'set_' . $type;
				if (method_exists($this, $setter)) {
					$this->$setter($addresses);
				} else {
					throw new tx_pttools_exception('Method ' . $setter . ' does not exist');
				}
			}
		}
	}



	/**
	 * read the from (reply) information from the configuration and if it exists set it to address
	 *
	 * @param   
	 * @return   
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function initSender($type) {
		if (isset($this->conf[$type . '.']) && is_array($this->conf[$type . '.'])) {
			if (!empty($this->conf[$type . '.']['email'])) {
				$address = new tx_ptmail_address($this->conf[$type . '.']['email'], $this->conf[$type . '.']['title']);
				$setter = 'set_' . $type;
				if (method_exists($this, $setter)) {
					$this->$setter($address);
				} else {
					throw new tx_pttools_exception('Method ' . $setter . ' does not exist');
				}
			}
		}
	}



	/**
	 * Returns the complete body and replaces markers
	 *
	 * @param   array	subpart marker array
	 * @return  string	mail body
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function getBodyOutput(array $subpartMarkerArray) {
		
		$bodyHeader = $this->replaceMarker($this->get_bodyHeader(), $subpartMarkerArray);
		$body = $this->replaceMarker($this->get_body(), $subpartMarkerArray);
		$bodyFooter = $this->replaceMarker($this->get_bodyFooter(), $subpartMarkerArray);
		$mailBody = '';
		$mailBody .= !empty($bodyHeader) ? $bodyHeader . "\n" : '';
		$mailBody .= !empty($body) ? $body . "\n" : '';
		$mailBody .= !empty($bodyFooter) ? $bodyFooter . "\n" : '';
		//$mailBody = $this->replaceMarker($this->initSubpart($this->conf['mail'], $this->conf['mail.']), $markerArray);
		if (strlen(trim($this->get_templateCharset())) > 0 && strlen(trim($this->get_mailCharset())) > 0 && $this->get_templateCharset() != $this->get_mailCharset()) {
			$mailBody = iconv($this->get_templateCharset(), $this->get_mailCharset() . '//IGNORE', $mailBody);
		}
		return $mailBody;
	}

	/**
	 * Returns the complete body and replaces markers
	 *
	 * @param   array	subpart marker array
	 * @return  string	mail body
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 * @since   2009-09-21
	 */
	protected function getHtmlBodyOutput(array $subpartMarkerArray) {
		
		$htmlBodyHeader = $this->replaceMarker($this->get_htmlBodyHeader(), $subpartMarkerArray);
		$htmlBody = $this->replaceMarker($this->get_htmlBody(), $subpartMarkerArray);
		$htmlBodyFooter = $this->replaceMarker($this->get_htmlBodyFooter(), $subpartMarkerArray);
		$mailHtmlBody = '';
		$mailHtmlBody .= !empty($htmlBodyHeader) ? $htmlBodyHeader . "\n" : '';
		$mailHtmlBody .= !empty($htmlBody) ? $htmlBody . "\n" : '';
		$mailHtmlBody .= !empty($htmlBodyFooter) ? $htmlBodyFooter . "\n" : '';
		//$mailHtmlBody = $this->replaceMarker($this->initSubpart($this->conf['mail'], $this->conf['mail.']), $markerArray);
		if (strlen(trim($this->get_templateCharset())) > 0 && strlen(trim($this->get_mailCharset())) > 0 && $this->get_templateCharset() != $this->get_mailCharset()) {
			$mailHtmlBody = iconv($this->get_templateCharset(), $this->get_mailCharset() . '//IGNORE', $mailHtmlBody);
		}
		return $mailHtmlBody;
	}



	

	/**
	 * replace the marker in a smarty template string and return it as string
	 *
	 * @param   	string	smarty template string
	 * @param 	array	marker and values to replace    
	 * @return   string  the string with the replaced markers
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function replaceMarker($text, array $markerArray) {
		
		$smarty = new tx_pttools_smartyAdapter(self::EXT_KEY);
		foreach ($markerArray as $markerKey => $markerValue) {
			$smarty->assign($markerKey, $markerValue);
		}
		return $smarty->fetch('string:' . $text);
	
	}



	/**
	 * get the subpart of an email, defined in the config
	 *
	 * @param   string   type of the subpart
	 * @param	array    subpart configuration
	 * @return  string   result string of the subpart configuration 
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	protected function initSubpart($name, array $subpart) {
		
		// check if there is a template
		if (isset($subpart['template.']['file'])) {
			$file = sprintf($subpart['template.']['file'], $this->language);
			if (!file_exists(t3lib_div::getFileAbsFileName($file))) {
				$file = sprintf($subpart['template.']['file'], 'default');
				tx_pttools_assert::isFilePath(t3lib_div::getFileAbsFileName($file));
			}
			$subpart['template.']['file'] = $file;
		}
		$template = '';
		
		// this is needed when not running in a frontend context
		if (is_array($subpart)) {
			// change directory to PATH_site
			$dir = getcwd();
			if ($dir != PATH_site) {
				chdir(PATH_site);
			}
			$template = $this->cObj->cObjGetSingle($name, $subpart);
			// switch to where we were before
			if ($dir != PATH_site) {
				chdir($dir);
			}
		}
		
		return $template;
	}



	/**************************************************************************
	 * SETTER/GETTER METHOD
	 ***************************************************************************/
	
	/**
	 * Set the property value
	 *
	 * @param	tx_ptmail_addressCollection   address collection
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_to(tx_ptmail_addressCollection $to) {
		$this->to = $to;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_addressCollection
	 * @since   2008-10-23 
	 */
	public function get_to() {
		return $this->to;
	}



	/**
	 * Set the property value
	 *
	 * @param	tx_ptmail_addressCollection
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_cc(tx_ptmail_addressCollection $cc) {
		$this->cc = $cc;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_addressCollection
	 * @since   2008-10-23 
	 */
	public function get_cc() {
		return $this->cc;
	}



	/**
	 * Set the property value
	 *
	 * @param	tx_ptmail_addressCollection
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_bcc(tx_ptmail_addressCollection $bcc) {
		$this->bcc = $bcc;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_addressCollection
	 * @since   2008-10-23 
	 */
	public function get_bcc() {
		return $this->bcc;
	}



	/**
	 * Set the property value
	 *
	 * @param   tx_ptmail_address        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_from(tx_ptmail_address $from) {
		$this->from = $from;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_address
	 * @since   2008-10-23 
	 */
	public function get_from() {
		if ($this->from == NULL) {
			throw new tx_pttools_exception('No from address');
		}
		return $this->from;
	}



	/**
	 * Set the property value
	 *
	 * @param   tx_ptmail_address        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_reply(tx_ptmail_address $reply) {
		$this->reply = $reply;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_address
	 * @since   2008-10-23 
	 */
	public function get_reply() {
		return $this->reply;
	}



	/**
	 * Set the property value
	 *
	 * @param   tx_ptmail_attachmentCollection
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_attachments(tx_ptmail_attachmentCollection $attachments) {
		$this->attachments = $attachments;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_attachmentCollection
	 * @since   2008-10-23 
	 */
	public function get_attachments() {
		return $this->attachments;
	}



	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_subject($subject) {
		$this->subject = $subject;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  
	 * @since   2008-10-23 
	 */
	public function get_subject() {
		return $this->subject;
	}



	/**
	 * Set the property value
	 *
	 * @param   string        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_bodyHeader($bodyHeader) {
		$this->bodyHeader = $bodyHeader;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  
	 * @since   2008-10-23 
	 */
	public function get_bodyHeader() {
		return $this->bodyHeader;
	}



	/**
	 * Set the property value
	 *
	 * @param	string
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_body($body) {
		$this->body = $body;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2008-10-23 
	 */
	public function get_body() {
		return $this->body;
	}



	/**
	 * Set the property value
	 *
	 * @param   string        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	
	public function set_bodyFooter($bodyFooter) {
		$this->bodyFooter = $bodyFooter;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2008-10-23 
	 */
	public function get_bodyFooter() {
		return $this->bodyFooter;
	}

/**
	 * Set the property value
	 *
	 * @param   string        
	 * @return  tx_ptmail_mail
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	public function set_htmlBodyHeader($htmlBodyHeader) {
		$this->htmlBodyHeader = $htmlBodyHeader;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	public function get_htmlBodyHeader() {
		return $this->htmlBodyHeader;
	}



	/**
	 * Set the property value
	 *
	 * @param	string
	 * @return  tx_ptmail_mail
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	public function set_htmlBody($htmlBody) {
		$this->htmlBody = $htmlBody;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	public function get_htmlBody() {
		return $this->htmlBody;
	}



	/**
	 * Set the property value
	 *
	 * @param   string        
	 * @return  tx_ptmail_mail
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	
	public function set_htmlBodyFooter($htmlBodyFooter) {
		$this->htmlBodyFooter = $htmlBodyFooter;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2009-09-21
	 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
	 */
	public function get_htmlBodyFooter() {
		return $this->htmlBodyFooter;
	}

	

	/**
	 * Set the property value
	 *
	 * @param   string	additional headers       
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_additionalHeaders($additionalHeaders) {
		$this->additionalHeaders = $additionalHeaders;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  tx_ptmail_additionalHeadersCollection
	 * @since   2008-10-23 
	 */
	public function get_additionalHeaders() {
		return $this->additionalHeaders;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2008-10-23
	 */
	public function get_templateCharset() {
		return $this->templateCharset;
	}



	/**
	 * Set the property value
	 *
	 * @param   string        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-23
	 */
	public function set_templateCharset($templateCharset) {
		$this->templateCharset = $templateCharset;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2008-10-23
	 */
	public function get_mailCharset() {
		return $this->mailCharset;
	}



	/**
	 * Set the property value
	 *
	 * @param   string 	mail character set      
	 * @return  tx_ptmail_mail
	 * @since   2008-10-01
	 */
	public function set_mailCharset($mailCharset) {
		$this->mailCharset = $mailCharset;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   void        
	 * @return  string
	 * @since   2008-10-23
	 */
	public function get_organisation() {
		return $this->organisation;
	}



	/**
	 * Set the property value
	 *
	 * @param   string	organisation      
	 * @return  tx_ptmail_mail
	 * @since   2008-10-01
	 */
	public function set_organisation($organisation) {
		$this->organisation = $organisation;
		return $this;
	}



	/**
	 * Returns the property value
	 *
	 * @param   tx_ptmail_iDriver        
	 * @return  tx_ptmail_mail
	 * @since   2008-10-01
	 */
	public function set_driver(tx_ptmail_iDriver $driver) {
		$this->driver = $driver;
		return $this;
	}

} // end class


/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_mail.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_mail.php']);
}

?>