<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008  <>
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

require_once(PATH_tslib.'class.tslib_pibase.php');

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_mail.php';


/**
 * Plugin 'test mail' for the 'pt_mailtest' extension.
 *
 * @author	 <>
 * @package	TYPO3
 * @subpackage	tx_ptmailtest
 */
class tx_ptmailtest_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_ptmailtest_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_ptmailtest_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'pt_mailtest';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj=1;
		
		try {
			$mail = new tx_ptmail_mail($this);
			
			$attachments = new tx_ptmail_attachmentCollection();
			$attachment = new tx_ptmail_attachment(t3lib_div::getFileAbsFileName('fileadmin/img/btn_thema.gif'));
			
			$attachments->addItem($attachment);
			$mail->set_attachments($attachments);
			$mail->set_mailCharset('iso-8859-15');
			$mail->set_organisation('klinger');
			$mail->set_templateCharset('utf-8');
			$mail->sendMail();
			$content='
			<strong>Send an email</strong>
		';
		} catch ( tx_pttools_exception $excObj ) {
			$GLOBALS['trace']=1;
			$excObj->handleException();
			$GLOBALS['trace']=0;
			// die($excObj->__toString());^M
		}
		
		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mailtest/pi1/class.tx_ptmailtest_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mailtest/pi1/class.tx_ptmailtest_pi1.php']);
}

?>