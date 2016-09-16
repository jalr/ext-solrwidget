<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "solrwidget".
 *
 * Auto generated 13-08-2014 07:56
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Solr Fluid Widget',
	'description' => 'A Fluid Widget for use with EXT:solr - can be used as ViewHelper or as standalone plugin. Uses AJAX for searching and JS-filling of a Fluid-based HTML skeleton for displaying results.',
	'category' => 'misc',
	'shy' => 0,
	'version' => '0.9.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Solr Team, Olivier Doberkau, Claus Due',
	'author_email' => 'opensource@dkd.de',
	'author_company' => 'dkd Internet Service GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.1.99-7.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:12:"ext_icon.gif";s:4:"68b4";s:17:"ext_localconf.php";s:4:"4ba7";s:14:"ext_tables.php";s:4:"9ef1";s:9:"README.md";s:4:"d42a";s:39:"Classes/Controller/PluginController.php";s:4:"ac55";s:39:"Classes/Controller/WidgetController.php";s:4:"2b61";s:46:"Classes/QueryProvider/InitialQueryProvider.php";s:4:"1ad7";s:48:"Classes/QueryProvider/QueryProviderInterface.php";s:4:"6633";s:39:"Classes/Utility/SolrResultFormatter.php";s:4:"ddba";s:40:"Classes/ViewHelpers/WidgetViewHelper.php";s:4:"1f06";s:34:"Configuration/TypoScript/setup.txt";s:4:"a9c4";s:40:"Resources/Private/Language/locallang.xml";s:4:"8bbd";s:38:"Resources/Private/Layouts/Default.html";s:4:"09bc";s:38:"Resources/Private/Partials/Widget.html";s:4:"56a1";s:46:"Resources/Private/Templates/Plugin/Plugin.html";s:4:"0a27";s:45:"Resources/Private/Templates/Widget/Index.html";s:4:"762e";s:45:"Resources/Public/Javascript/Initialisation.js";s:4:"a6cb";}',
);

?>
