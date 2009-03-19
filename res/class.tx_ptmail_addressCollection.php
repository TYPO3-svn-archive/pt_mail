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
 * Address collection class for the 'pt_mail' extension
 *
 * $Id: class.tx_ptmail_addressCollection.php,v 1.5 2009/01/07 16:49:39 ry44 Exp $
 *
 * @author	Ursula Klinger <klinger@punkt.de
 * @since   2008-10-01
 */ 


require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php'; // abstract object Collection class

require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_address.php';



/**
 * Address collection class
 *
 * @author	    Ursula Klinger <klinger@punkt.de
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_addressCollection extends tx_pttools_objectCollection {
    
    /**
     * Properties
     */
    protected $restrictedClassName = 'tx_ptmail_address';
	/***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    
    /**
     * Creates a collection of email adresses, adds each given parameter of type tx_ptmail_address or string to the collection
     * 
     * @param   tx_ptmail_address|string  (optional) email address object or string (with single or comma-separeated-list of addresses) to add to the collection initially
     * @param   tx_ptmail_address|string  (optional) email address object or string (with single or comma-separeated-list of addresses) to add to the collection initially
     * @param   tx_ptmail_address|string  (optional) ...
     * @return  void
     * @author  Fabrizio Branca <branca@punkt.de>
     * @since   2008-10-01
     */
    public function __construct(/* $emailAddressObj1, $emailAddressObj2, $emailAddressObj3, ... */) {
    	foreach (func_get_args() as $arg) {
    		$this->addItem($arg);
    	}
    }

    /**
     * Adds e-mail addresses to the collection
     *
     * @example
     * Pass object: 
     * 		$this->addItem($emailAddressObj);
     * or create object while passing:
     * 		$this->addItem(new tx_ptmail_address('john@doe.org', 'John Doe'));
     * or create object with condensed address
     * 		$this->addItem(new tx_ptmail_address('John Doe <john@doe.org>'));
     * or pass a string (mail address only)
     * 		$this->addItem('john@doe.org');
     * or pass a condensed mail address as string
     * 		$this->addItem('John Doe <john@doe.org>');
     * or pass a comma separeted list of mail addresses only
     * 		$this->addItem('john@doe.org,jane@doe.org');
     * or pass a comma separeted list of condensed mail addresses
     * 		$this->addItem('John Doe <john@doe.org>, Jane Doe <jane@doe.org>');
     *  		
     * @param 	tx_ptmail_address|string 	email address object or string (with single or comma-separeated-list of addresses)
     * @param 	mixed	(optional) array key
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-12-17
     */
    public function addItem($item, $id=0) {
    	if ($item instanceof tx_ptmail_address) {
    		parent::addItem($item, $id);
    	} elseif (is_string($item)) {
   			 foreach (t3lib_div::trimExplode(',', $item) as $mail) {
   			 	parent::addItem(new tx_ptmail_address($mail), $id);
   			 }
   		} else {
   			throw new tx_pttools_exception('Invalid argument!');
   		}
    }
    
    /**
     * Return a string representation of this collection
     *
     * @param 	void
     * @return 	string	string representation
     * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-12-28
     */
    public function __toString() {
    	$addresses = array();
    	foreach ($this as $address) { /* @var $address tx_ptmail_address */
    		$addresses[] = $address->__toString();
    	}
    	return implode (', ', $addresses);
    }
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_addressCollection.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_addressCollection.php']);
}

?>