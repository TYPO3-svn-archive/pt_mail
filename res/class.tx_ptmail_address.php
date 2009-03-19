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
 * $Id: class.tx_ptmail_address.php,v 1.8 2009/01/07 16:49:39 ry44 Exp $
 *
 * @author    Ursula Klinger <klinger@punkt.dee>
 * @since   2008-10-01
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of extension specific resources
 */

/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class



/**
 * Address class
 *
 * @author      Ursula Klinger <klinger@punkt.dee>
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_address {
    
    /**
     * Properties
     */
    protected $title;
    protected $email;
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: creates an address object
     *
     * @param   string 				(optional) email address or "Title <email@address.net>"
     * @param   string  			(optional) email title
     * @param 	tx_pttools_address	(optional) tx_pttools_address object
     * @param 	bool				(optional) init from current logged user (is in frontend context), default is false
     * @return  void
     * @author	Ursula Klinger <klinger@punkt.de>
     * @since   2008-10-01
     */
    public function __construct($email='', $title='', tx_pttools_address $address = NULL, $fromCurrentFeuser = false) { 
    
        if (!empty($email)) {
        	
       		$matches = array();
       		if (preg_match('/^(.*)\s<(.*)>$/', $email, $matches)) {
       			$email = $matches[2];
       			$title = $matches[1];
       		}
           	$this->set_email($email);
           	$this->set_title($title);
           	
        } elseif (!is_null($address)) {
        	
            $this->setFromAddressObject($address);
            
        } elseif ($fromCurrentFeuser) {
        	
            $this->setFromCurrentFeuser();
            
        } else {
        	
             throw new tx_pttools_exception('Empty email address');
             
        }
    }   
    

    /***************************************************************************
     *   Business METHODS
     **************************************************************************/
    
    /**
     * Set title and mail address from currently logged in user
     *
     * @param 	void
     * @return 	void
     * @throws	tx_pttools_assert		if not in TSFE context or if no fe_user was found
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-12-16
     */
    public function setFromCurrentFeuser() {
    	tx_pttools_assert::isInstanceOf($GLOBALS['TSFE'], 'tslib_fe', array('message' => 'No TSFE found!'));
    	tx_pttools_assert::isEqual($GLOBALS['TSFE']->loginUser, true, array('message' => 'No frontend user found!'));
    	tx_pttools_assert::isInstanceOf($GLOBALS['TSFE']->fe_user, 'tslib_feUserAuth', array('message' => 'No frontend user found!'));

    	$this->set_email($GLOBALS['TSFE']->fe_user->user['email']);    
        $this->set_title($GLOBALS['TSFE']->fe_user->user['name']);
    }
    
	/**
	 * Set from tx_pttools_address object
	 *
	 * @param 	tx_pttools_address 	address object
	 * @return	false
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-12-16
	 */
    public function setFromAddressObject(tx_pttools_address $address) {
        tx_pttools_assert::isObject($address);
        $this->set_email($address->get_email1());
        $this->set_title($address->get_firstname() . ' ' . $address->get_lastname());
    }
 
    
    /***************************************************************************
     *   PROPERTY GETTER/SETTER METHODS
     **************************************************************************/
     
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2008-10-01
     */
    public function get_email() {
        return $this->email;
    }

    /**
     * Set the property value
     *
     * @param   string        
     * @return  void
     * @since   2008-10-01
     */
    public function set_email($email) {
        tx_pttools_assert::isNotEmptyString($email);
        tx_pttools_assert::isValidEmail($email);
        $this->email = $email;
    }

    
    /**
     * Returns the property value
     *
     * @param   void        
     * @return  string      property value
     * @since   2008-10-01
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Set the property value
     *
     * @param   int        
     * @return  void
     * @since   2006-10-23
     */
    public function set_title($title) {
        $this->title = $title;
    }
    
	/**
	 * Returns a string representation of this mail object
	 * 
	 * @param	void
	 * @return 	string	string representation
	 * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
	 * @since 	2008-12-28
	 */
    public function __toString() {
    	if (!empty($this->title)) {
    		return $this->title . ' <' . $this->email . '>';
    	} else {
    		return $this->email;
    	}
    }
        
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_address.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_address.php']);
}

?>