<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Solr Widget: Setup');

Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'Widget', 'Solr Widget', 'EXT:solrwidget/ext_icon.gif');
