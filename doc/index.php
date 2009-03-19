<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Ursula Klinger <klinger@punkt.de>
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
 * Module 'not changed content elemnts of the 'pt_langworkflow' extension.
 *
 * $Id: index.php,v 1.1 2008/11/05 13:40:00 ry26 Exp $
 *
 * @author  Ursula Klinger <klinger@punkt.de>
 * @since   2008-08-11
 */ 





/**
 * Debugging config for development
 */
#$trace     = 1; // (int) trace options @see tx_pttools_debug::trace() [for local temporary debugging use only, please COMMENT OUT this line if finished with debugging!]
#$errStrict = 1; // (bool) set strict error reporting level for development (requires $trace to be set to 1)  [for local temporary debugging use only, please COMMENT OUT this line if finished with debugging!]


    // DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require_once('conf.php');
require_once($BACK_PATH.'init.php');
require_once($BACK_PATH.'template.php');

$LANG->includeLLFile('EXT:pt_mailtest/mod1/locallang.xml');
require_once(PATH_t3lib.'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);    // This checks permissions and exits if the users has no permission for entry.
    // DEFAULT initialization of a module [END]
require_once t3lib_extMgm::extPath('pt_mail').'res/class.tx_ptmail_mail.php';
/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class

// Defining PATH_thisScript here: Must be the ABSOLUTE path of this script in the right context:^M
// This will work as long as the script is called by it's absolute path!^M
define('PATH_thisScript', $_SERVER['argv'][0]); ### Original call: define(PATH_thisScript, $HTTP_ENV_VARS['_']);^M
// Include configuration file:^M
require(dirname(PATH_thisScript).'/conf.php');
define('PATH_tslib',dirname(PATH_thisScript).'/'.$BACK_PATH.'sysext/cms/tslib/');
define('PATH_site',dirname(PATH_thisScript).'/'.$BACK_PATH.'..');
require_once t3lib_extMgm::extPath('pt_tools').'res/inc/faketsfe.inc.php';


/**
 * Module 'mailtest' for the 'pt_mailtest' extension.
 *
 * @author     <>
 * @package    TYPO3
 * @subpackage    tx_ptmailtest
 */
class  tx_ptmailtest_module1 extends t3lib_SCbase {
                var $pageinfo;

                
                
                /**
                 * Initializes the Module
                 * @return    void
                 */
                function init()    {
                	 global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
                	 
                	$extConf = tx_pttools_div::returnExtConfArray('pt_mail');
                	$rootLine = $GLOBALS['TSFE']->sys_page->getRootLine($extConf['tsConfigurationPid']); 
                	$GLOBALS['TSFE']->tmpl = t3lib_div::makeInstance('t3lib_tsparser_ext');  
        			$GLOBALS['TSFE']->tmpl->tt_track = 0;  
        			$GLOBALS['TSFE']->tmpl->init();
        			$GLOBALS['TSFE']->tmpl->runThroughTemplates($rootLine);  
        			$GLOBALS['TSFE']->tmpl->generateConfig(); 
        			$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
                	//$GLOBALS['TSFE']->newCObj();
                   

                    parent::init();

                    /*
                    if (t3lib_div::_GP('clear_all_cache'))    {
                        $this->include_once[] = PATH_t3lib.'class.t3lib_tcemain.php';
                    }
                    */
                }

                /**
                 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
                 *
                 * @return    void
                 */
                function menuConfig()    {
                    global $LANG;
                    $this->MOD_MENU = Array (
                        'function' => Array (
                            '1' => $LANG->getLL('function1'),
                            '2' => $LANG->getLL('function2'),
                            '3' => $LANG->getLL('function3'),
                        )
                    );
                    parent::menuConfig();
                }

                /**
                 * Main function of the module. Write the content to $this->content
                 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
                 *
                 * @return    [type]        ...
                 */
                function main()    {
                    global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

                    // Access check!
                    // The page will show only if there is a valid page and if this page may be viewed by the user
                    $this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
                    $access = is_array($this->pageinfo) ? 1 : 0;

                    if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))    {

                            // Draw the header.
                        $this->doc = t3lib_div::makeInstance('mediumDoc');
                        $this->doc->backPath = $BACK_PATH;
                        $this->doc->form='<form action="" method="POST">';

                            // JavaScript
                        $this->doc->JScode = '
                            <script language="javascript" type="text/javascript">
                                script_ended = 0;
                                function jumpToUrl(URL)    {
                                    document.location = URL;
                                }
                            </script>
                        ';
                        $this->doc->postCode='
                            <script language="javascript" type="text/javascript">
                                script_ended = 1;
                                if (top.fsMod) top.fsMod.recentIds["web"] = 0;
                            </script>
                        ';

                        $headerSection = $this->doc->getHeader('pages',$this->pageinfo,$this->pageinfo['_thePath']).'<br />'.$LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.path').': '.t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'],50);

                        $this->content.=$this->doc->startPage($LANG->getLL('title'));
                        $this->content.=$this->doc->header($LANG->getLL('title'));
                        $this->content.=$this->doc->spacer(5);
                        $this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
                        $this->content.=$this->doc->divider(5);


                        // Render content:
                        $this->moduleContent();


                        // ShortCut
                        if ($BE_USER->mayMakeShortcut())    {
                            $this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
                        }

                        $this->content.=$this->doc->spacer(10);
                    } else {
                            // If no access or if ID == zero

                        $this->doc = t3lib_div::makeInstance('mediumDoc');
                        $this->doc->backPath = $BACK_PATH;

                        $this->content.=$this->doc->startPage($LANG->getLL('title'));
                        $this->content.=$this->doc->header($LANG->getLL('title'));
                        $this->content.=$this->doc->spacer(5);
                        $this->content.=$this->doc->spacer(10);
                    }
                }

                /**
                 * Prints out the module HTML
                 *
                 * @return    void
                 */
                function printContent()    {
					try {
                    	$this->content.=$this->doc->endPage();
                    	
                    	$mail = new tx_ptmail_mail();
                    	$mail->set_to(new tx_ptmail_addressCollection( new tx_ptmail_address('klinger@punkt.de','Ursula Klinger')));
                    
                    	$mail->sendMail();
                    	$this->content .= "Sende E-Mail";
                    	echo $this->content;
                	} catch ( tx_pttools_exception $excObj ) {
						$GLOBALS['trace']=1;
						$excObj->handleException();
						$GLOBALS['trace']=0;
			// die($excObj->__toString());^M
					}
                }

                /**
                 * Generates the module content
                 *
                 * @return    void
                 */
                function moduleContent()    {
                    switch((string)$this->MOD_SETTINGS['function'])    {
                        case 1:
                            $content='<div align="center"><strong>Hello World!</strong></div><br />
                                The "Kickstarter" has made this module automatically, it contains a default framework for a backend module but apart from that it does nothing useful until you open the script '.substr(t3lib_extMgm::extPath('pt_mailtest'),strlen(PATH_site)).$pathSuffix.'index.php and edit it!
                                <hr />
                                <br />This is the GET/POST vars sent to the script:<br />'.
                                'GET:'.t3lib_div::view_array($_GET).'<br />'.
                                'POST:'.t3lib_div::view_array($_POST).'<br />'.
                                '';
                            $this->content.=$this->doc->section('Message #1:',$content,0,1);
                        break;
                        case 2:
                            $content='<div align=center><strong>Menu item #2...</strong></div>';
                            $this->content.=$this->doc->section('Message #2:',$content,0,1);
                        break;
                        case 3:
                            $content='<div align=center><strong>Menu item #3...</strong></div>';
                            $this->content.=$this->doc->section('Message #3:',$content,0,1);
                        break;
                    }
                }
            }



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mailtest/mod1/index.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_mailtest/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_ptmailtest_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)    include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>
