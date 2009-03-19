<?php

########################################################################
# Extension Manager/Repository config file for ext: "pt_mail"
#
# Auto generated 19-12-2008 15:14
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Generic Mailer',
	'description' => 'Generic mail extension to be used from within other extensions in all contextes.',
	'category' => 'misc',
	'author' => 'Ursula Klinger, Fabrizio Branca',
	'author_email' => 'klinger@punkt.de',
	'shy' => '',
	'dependencies' => 'pt_tools',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'punkt.de GmbH',
	'version' => '0.0.4',
	'constraints' => array(
		'depends' => array(
			'pt_tools' => '0.4.1-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:20:{s:9:"ChangeLog";s:4:"d129";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"ed69";s:12:"ext_icon.gif";s:4:"4546";s:17:"ext_localconf.php";s:4:"e5a9";s:14:"ext_tables.php";s:4:"d967";s:14:"doc/manual.sxw";s:4:"8df9";s:19:"doc/wizard_form.dat";s:4:"8122";s:20:"doc/wizard_form.html";s:4:"907c";s:40:"res/class.tx_ptmail_additionalHeader.php";s:4:"b6d8";s:50:"res/class.tx_ptmail_additionalHeaderCollection.php";s:4:"14a4";s:31:"res/class.tx_ptmail_address.php";s:4:"be97";s:41:"res/class.tx_ptmail_addressCollection.php";s:4:"7c35";s:34:"res/class.tx_ptmail_attachment.php";s:4:"93f8";s:44:"res/class.tx_ptmail_attachmentCollection.php";s:4:"5c1c";s:31:"res/class.tx_ptmail_iDriver.php";s:4:"e480";s:28:"res/class.tx_ptmail_mail.php";s:4:"22c2";s:45:"res/class.tx_ptmail_t3lib_htmlmailAdapter.php";s:4:"706d";s:39:"res/smarty_tpl/default_mail_default.tpl";s:4:"b414";s:16:"static/setup.txt";s:4:"8dcc";}',
	'suggests' => array(
	),
);

?>