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
 * $Id: class.tx_ptmail_t3lib_htmlmailAdapter.php,v 1.10 2009/01/07 16:49:39 ry44 Exp $
 *
 * @author	Ursula Klinger <klinger@punkt.de>
 * @since   2008-10-01
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_iDriver.php';// extension specific interface class

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_address.php';
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_addressCollection.php';

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_attachment.php';
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_attachmentCollection.php';

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_additionalHeader.php';
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_additionalHeaderCollection.php';

require_once(PATH_t3lib.'class.t3lib_htmlmail.php');
/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php'; // general static library class



/**
 * attachment class
 *
 * @author	    Ursula Klinger <klinger@punkt.dee>
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_t3lib_htmlmailAdapter extends t3lib_htmlmail implements tx_ptmail_iDriver {
    
    /**
     * Properties
     */
 
	/**
	 * @var tx_ptmail_addressCollection all to emails
	 */
	protected $to = NULL;
	
	/**
	 * @var tx_ptmail_addressCollection all cc emails
	 */
	protected $cc = NULL;
	
	/**
	 * @var tx_ptmail_addressCollection all bcc emails
	 */
	protected $bcc = NULL;
	
    /**
	 * @var tx_ptmail_addressCollection all bcc emails
	 */
	protected $additonalHeaders = NULL;
	
	/**
	 * @var string  charset of the template and to,from etc
	 */
	protected $templateCharset = '';
	
	
	/***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
      

    /***************************************************************************
     *   Business METHODS
     **************************************************************************/
   
    /**
     * set the email recipients
     *
     * @param   tx_ptmail_addressCollection address collection of the recipients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setTo(tx_ptmail_addressCollection $to) {
		tx_pttools_assert::isObject($to);
		$this->set_to($to);
    }
    
    
     /**
     * set the email cc recipients
     *
     * @param   tx_ptmail_addressCollection address collection of the cc recipients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setCc(tx_ptmail_addressCollection $cc) {
    	tx_pttools_assert::isObject($cc);
    	$this->set_cc($cc);
    }
    
   	/**
     * set the email bcc recipients
     *
     * @param   tx_ptmail_addressCollection address collection of the bcc recipients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */    
    public function setBcc(tx_ptmail_addressCollection $bcc)  {
    	tx_pttools_assert::isObject($bcc);
    	$this->set_bcc($bcc);
    }
    
     /**
     * set the email reply address
     *
     * @param   tx_ptmail_address  the reply address
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setReply(tx_ptmail_address $reply) {
    	tx_pttools_assert::isObject($reply);
    	$this->replyto_name 	= $reply->get_title();
    	$this->replyto_email 	= $reply->get_email();
    }
    
    /**
     * set the email sender address
     *
     * @param   tx_ptmail_address  the sender address
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setFrom(tx_ptmail_address $from) {
    	tx_pttools_assert::isObject($from);
    	$this->from_name 	= $from->get_title();
    	$this->from_email 	= $from->get_email();
    }
    
    
    /**
     * set the attachment files
     *
     * @param   tx_ptmail_attachmentCollection the attachments
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setAttachment(tx_ptmail_attachmentCollection $attachments) {
    	foreach ($attachments AS $attachment) { /* @var $attachment tx_ptmail_attachment */
    		$this->addAttachment($attachment->get_file());
    	}
    }
    
    /**
     * set the charset of the mail
     *
     * @param   string	charset of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setCharset($charset) {
    	$this->charset = $charset;
    }
    
    /**
     * set the charset of the template
     *
     * @param   string	charset of the template
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setTemplateCharset($templateCharset) {
    	$this->templateCharset = $templateCharset;
    }
    
    /**
     * Set a return path
     *
     * @param 	string 	return path
     * @author	Fabrizio Branca <mail@fabrizio-branca.de> 
     * @since 	2008-12-29
     */
    public function setReturnPath($returnPath) {
    	$this->returnPath = $returnPath;
		$this->forceReturnPath = !empty($returnPath);
    }
    
    /**
     * set the subject of the mail
     *
     * @param   string	subject of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setSubject($subject) {
    	$this->subject = $subject;
    	
    }
    
   
    /**
     * set the body of the mail
     *
     * @param   string	body of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setBody($body) {
    	$this->setPlain($this->encodeMsg($body));
    }
    
    /**
     * set the body HTML of the mail
     *
     * @param   string	body of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setHtmlBody($body) {
    	$this->setHtml($this->encodeMsg($body));
    }
    
    /**
     * set the additinal headers of the mail
     *
     * @param   tx_ptmail_additionalHeaderCollection	additional headers of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    
	public function setAdditionalHeaders(tx_ptmail_additionalHeaderCollection $additionalHeaders) {
    	$this->set_additionalHeaders($additionalHeaders);
    	
    }
    
    /**
     * prepare the mail and send it
     *
     * @param   void
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function send() {
    	$this->start();
    	$this->setHeaders();
    	$this->setContent();
    	return $this->sendTheMail();
    }
	
   
    public function setHeaders() {
    	// init the X-Mailer;
    	$this->mailer = '';
    	
    	parent::setHeaders();
    	
    	if (!$this->dontEncodeHeader) {
         	$enc = $this->alt_base64 ? 'base64' : 'quoted_printable'; // Header must be ASCII, therefore only base64 or quoted_pr
		}
    	
		if ($this->get_to() != NULL)  {
			$toArr = array();
    		foreach($this->to as $to) {
        		if (trim($to->get_title()) != '') {
        			$title = t3lib_div::encodeHeader(iconv($this->get_templateCharset(), $this->charset,$to->get_title()),$enc,$this->charset);
    				$toArr[] = $title.' <'.$to->get_email().'>';
        		} else {
        			$toArr[] = $to->get_email();
        		}
        	}
        	$this->recipient = implode(',', $toArr);
		}
		
    	
    	if ($this->get_bcc() != NULL)  {
    		$bccArr = array();
    		foreach($this->bcc as $bcc) {
        		if (trim($bcc->get_title()) != '') {
        			$title = t3lib_div::encodeHeader(iconv($this->get_templateCharset(), $this->charset,$bcc->get_title()),$enc,$this->charset);
    				$bccArr[] = $title.' <'.$bcc->get_email().'>';
        		} else {
        			$bccArr[] = $bcc->get_email();
        		}
        	}
        	$this->add_header('BCC:' .implode(',', $bccArr));
		}
		
    	if ($this->get_cc() != NULL)  {
    		$ccArr = array();
    		foreach($this->cc as $cc) {
        		if (trim($cc->get_title()) != '') {
        			$title = t3lib_div::encodeHeader(iconv($this->get_templateCharset(), $this->charset,$cc->get_title()),$enc,$this->charset);
    				$ccArr[] = $title.' <'.$cc->get_email().'>';
        		} else {
        			$ccArr[] = $cc->get_email();
        		}
        	}
        	$this->add_header('CC:' . implode(',', $ccArr));
		}
		
		if ($this->get_additionalHeaders() != NULL) {
			foreach ($this->get_additionalHeaders() AS $additionalHeader ) { /* @var $additionalHeader tx_ptmail_additionalHeader */
				$this->add_header($additionalHeader->get_header());
			}
		}
	}

	/**
	 * use the parent class, only for a html mail there is oanother
	 *
	 * @param   void
	 * @return  void
	 * @author	Ursula Klinger <klinger@punkt.de>
	 * @since   2008-10-23
	 */
	public function setContent() {
		if (!empty($this->theParts['html']['content']) && empty($this->theParts['plain']['content'])){
			$this->add_header($this->html_text_header);
			$this->add_message($this->getContent('html'));
		} else {
			parent::setContent();
		}
	}

	/***************************************************************************
	 *   PROPERTY GETTER/SETTER METHODS
	 **************************************************************************/
	
	/**
 	 * Set the property value
	 *
	 * @param   tx_ptmail_addressCollection all to address          
	 * @return  void
	 * @since   2008-10-24
	 */
    public function set_to(tx_ptmail_addressCollection $to) {
    	$this->to = $to;
    	
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  tx_ptmail_addressCollection all to addresses
     * @since   2008-10-24 
     */
	public function get_to() {
		return $this->to;
    }
    
    
	/**
 	 * Set the property value
	 *
	 * @param   tx_ptmail_addressCollection all cc address          
	 * @return  void
	 * @since   2008-10-24
	 */
    public function set_cc(tx_ptmail_addressCollection $cc) {
    	$this->cc = $cc;
    	
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  tx_ptmail_addressCollection all cc addresses
     * @since   2008-10-24 
     */
	public function get_cc() {
		return $this->cc;
    }
    
	/**
 	 * Set the property value
	 *
	 * @param   tx_ptmail_addressCollection all bcc address          
	 * @return  void
	 * @since   2008-10-24
	 */
    public function set_bcc(tx_ptmail_addressCollection $bcc) {
    	$this->bcc = $bcc;
    	
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  tx_ptmail_addressCollection all bcc addresses
     * @since   2008-10-24 
     */
	public function get_bcc() {
		return $this->bcc;
    }
    
    /**
 	 * Set the property value
	 *
	 * @param   tx_ptmail_additionalHeaderCollection all additional header information          
	 * @return  void
	 * @since   2008-10-24
	 */
	public function set_additionalHeaders(tx_ptmail_additionalHeaderCollection $additionalHeaders) {
    	$this->additionalHeaders = $additionalHeaders;
    	
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  tx_ptmail_additionalHeaderCollection all additional header information
     * @since   2008-10-24 
     */
    public function get_additionalHeaders() {
		return $this->additionalHeaders;
    }
    
	/**
 	 * Set the property value
	 *
	 * @param   string  charset of the template and to,from etc          
	 * @return  void
	 * @since   2008-10-24
	 */
	public function set_templateCharset($templateCharset) {
    	$this->templateCharset = $templateCharset;
    	
    }
    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return 	string  charset of the template and to,from etc 
     * @since   2008-10-24 
     */
    public function get_templateCharset() {
		return $this->templateCharset;
    }
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_t3lib_htmlmailAdapter.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_t3lib_htmlmailAdapter.php']);
}

?>