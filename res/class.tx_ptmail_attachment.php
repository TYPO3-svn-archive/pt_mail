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
 * Attachment class for the 'pt_mail' extension
 *
 * $Id: class.tx_ptmail_attachment.php,v 1.3 2008/12/18 13:54:23 ry44 Exp $
 *
 * @author	Ursula Klinger <klinger@punkt.dee>
 * @since   2008-10-01
 */ 


require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class



/**
 * Attachment class
 *
 * @author	    Ursula Klinger <klinger@punkt.dee>
 * @since       2008-10-01
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_attachment {
    
    /**
     * Properties
     */
    protected $file;
    
	/***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: creates an attachment object
     *
     * @param   string		path to the attachment file
     * @return  void
 	 * @author	Ursula Klinger <klinger@punkt.de>
 	 * @since   2008-10-01
     */
    public function __construct($file) { 
    
        $this->set_file($file);
    }   
    

    /***************************************************************************
     *   Business METHODS
     **************************************************************************/
    
	
 
    
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
    public function get_file() {
        return $this->file;
    }

    /**
     * Set the property value
     *
     * @param   string        
     * @return  void
     * @since   2008-10-01
     */
    public function set_file($file) {
    	tx_pttools_assert::isFilePath($file);
        $this->file = $file;
    }
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_attachment.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_attachment.php']);
}

?>