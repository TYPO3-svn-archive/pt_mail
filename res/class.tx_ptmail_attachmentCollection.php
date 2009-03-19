<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Ursula Klinger (klinger@punkt.de
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
 * attachment collection class for the 'pt_mail' extension
 *
 * $Id: class.tx_ptmail_attachmentCollection.php,v 1.2 2008/12/18 13:54:23 ry44 Exp $
 *
 * @author	Ursula Klinger <klinger@punkt.de
 * @since   2008-10-01
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_attachment.php';// extension specific email object class

/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class


require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php'; // abstract object Collection class



/**
 * Attachment collection class
 *
 * @author	    Ursula Klinger <klinger@punkt.de
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_attachmentCollection extends tx_pttools_objectCollection {
    
    /**
     * Properties
     */
    protected $restrictedClassName = 'tx_ptmail_attachment';    

	

	/**
     * Creates a collection of attachment objects, adds each given parameter of type tx_ptmail_attachment to the collection
     *
     * @param   tx_ptmail_attachment  (optional) attachment object to add to the collection initially
     * @param   tx_ptmail_attachment  (optional) attachment object to add to the collection initially
     * @param   tx_ptmail_attachment  (optional) ...
     * @return  void
     * @author  Fabrizio Branca <branca@punkt.de>
     * @since   2008-12-15
     */
    public function __construct(/* $attachmentObj, $attachmentObj2, $attachmentObj3, ... */) {
    	foreach (func_get_args() as $arg) {
    		$this->addItem($arg); 
    	}
    }

    /**
     * Adds attachments to the collection
     *
     * @example
     * Pass object: 
     * 		$this->addItem($attachmentObj);
     * or create object while passing:
     * 		$this->addItem(new tx_ptmail_attachment('fileadmin/file.pdf'));
     * or pass a string 
     * 		$this->addItem('fileadmin/file.pdf');
     * or pass a comma separeted list of mail addresses only
     * 		$this->addItem('fileadmin/file.pdf<PATH_SEPARATOR>fileadmin/file2.pdf');
     *  		
     * @param 	tx_ptmail_attachment|string 	attachment object or string (with single or comma-separeated-list of attachment)
     * @param 	mixed	(optional) array key
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-12-18
     */
    public function addItem($item, $id=0) {
    	if ($item instanceof tx_ptmail_attachment) {
    		parent::addItem($item, $id);
    	} elseif (is_string($item)) {
   			 foreach (t3lib_div::trimExplode(PATH_SEPARATOR, $item) as $mail) {
   			 	parent::addItem(new tx_ptmail_attachment($mail), $id);
   			 }
   		} else {
   			throw new tx_pttools_exception('Invalid argument!');
   		}
    }
	
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_attachmentCollection.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_attachmentCollection.php']);
}

?>
