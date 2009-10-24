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
 * Interface for mail driver
 *
 * $Id: class.tx_ptmail_iDriver.php,v 1.7 2009/01/07 16:49:39 ry44 Exp $
 *
 * @author      Ursula Klinger <klinger@punkt.de>
 * @since       2008-01-01
 */

/**
 * Interface for the mail driver 
 *
 * @author      Ursula Klinger <klinger@punkt.de>
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
interface tx_ptmail_iDriver {

    /**
     * set the email recipcients
     *
     * @param   tx_ptmail_addressCollection address collection of the recipcients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setTo(tx_ptmail_addressCollection $to);
    
    /**
     * set the email sender address
     *
     * @param   tx_ptmail_address  the sender address
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setFrom(tx_ptmail_address $from);
    
    /**
     * set the email cc recipcients
     *
     * @param   tx_ptmail_addressCollection address collection of the cc recipcients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setCc(tx_ptmail_addressCollection $cc);
    
    /**
     * set the email bcc recipcients
     *
     * @param   tx_ptmail_addressCollection address collection of the bcc recipcients
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setBcc(tx_ptmail_addressCollection $bcc);
    
    /**
     * set the email reply address
     *
     * @param   tx_ptmail_address  the reply address
     * @return  void 
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setReply(tx_ptmail_address $reply);
    
    /**
     * set the attachment files
     *
     * @param   tx_ptmail_attachmentCollection the attachments
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setAttachment(tx_ptmail_attachmentCollection $attachment);
    
    /**
     * set the charset of the mail
     *
     * @param   string	charset of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setCharset($charset);
    
	/**
     * set the charset of the template
     *
     * @param   string	charset of the template
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setTemplateCharset($templateCharset);
    
    /**
     * Set the return path of the mail
     *
     * @param 	string	return path
     * @return 	void
     * @author 	Fabrizio Branca <branca@punkt.de>
     * @since	2008-12-29 
     */
    public function setReturnPath($returnPath);
    
    /**
     * set the subject of the mail
     *
     * @param   string	subject of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setSubject($subject);
    
    
    /**
     * set the body of the mail
     *
     * @param   string	body of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setBody($body);
    
    /**
     * set the html body of the mail
     *
     * @param   string	HTML body of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setHtmlBody($body);
    
    /**
     * set the additinal headers of the mail
     *
     * @param   tx_ptmail_additionalHeaderCollection	additional headers of the mail
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function setAdditionalHeaders(tx_ptmail_additionalHeaderCollection $headers);
    
    /**
     * prepare the mail and send it
     *
     * @param   void
     * @return  bool	true if the mail was sent, false otherwise
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-23
     */
    public function send();
}



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_iDriver.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_iDriver.php']);
}

?>