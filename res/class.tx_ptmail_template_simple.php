<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Fabrizio Branca (mail@fabrizio-branca.de)
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
 * Simple template
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2009-09-21
 */

require_once t3lib_extMgm::extPath('pt_mail') . 'res/class.tx_ptmail_iTemplate.php';
require_once t3lib_extMgm::extPath('pt_tools') . 'res/objects/class.tx_pttools_smartyAdapter.php';

/**
 * Simple template 
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2009-09-21
 * @package     TYPO3
 * @subpackage  tx_ptmail
 */
class tx_ptmail_template_simple implements tx_ptmail_iTemplate {
	
	/**
	 * @var string content
	 */
	protected $content;
	
	/**
	 * @var array marker array
	 */
	protected $markerArray;
	
	

    /**
     * Set the content
     *
     * @param   string content
     * @return  void 
 	 * @author  Fabrizio Branca <mail@fabrizio-branca.de>
 	 * @since   2009-09-21
     */
    public function setContent($content) {
    	$this->content = $content;
    }
        
    /**
     * Set the markers
     *
     * @param   array	marker array
     * @return  void 
 	 * @author  Fabrizio Branca <mail@fabrizio-branca.de>
 	 * @since   2009-09-21
     */
    public function setMarkerArray(array $markerArray) {
    	$this->markerArray = $markerArray;
    }
    
    /**
     * Return the rendered content
     *
     * @param   void
     * @return  void 
 	 * @author  Fabrizio Branca <mail@fabrizio-branca.de>
 	 * @since   2009-09-21
     */
    public function render() {
    	return str_replace(array_keys($this->markerArray), array_values($this->markerArray), $this->content);
    }
    
}



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_template_simple.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mail/res/class.tx_ptmail_template_simple.php']);
}

?>