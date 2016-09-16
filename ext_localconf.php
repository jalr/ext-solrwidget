<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'ApacheSolrForTypo3.' . $_EXTKEY,
    'Widget',
	array('Plugin' => 'plugin'),
	array('Plugin' => 'plugin')
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['solrwidget'] = array(
	'queryProviders' => array(
		'ApacheSolrForTypo3\\Solrwidget\\QueryProvider\\InitialQueryProvider'
	)
);
