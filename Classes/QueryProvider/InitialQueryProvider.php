<?php

namespace ApacheSolrForTypo3\Solrwidget\QueryProvider;

use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use ApacheSolrForTypo3\Solr\Query;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class InitialQueryProvider implements QueryProviderInterface {

	/**
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * @param string $queryString
	 * @param Query $originalQuery
	 * @return string
	 */
	public function getTitle($queryString, $originalQuery) {
		$label = LocalizationUtility::translate('initialQueryGroupTitle', 'Solrwidget');
		return NULL === $label ? 'Default' : $label;
	}

	/**
	 * @param string $queryString
	 * @param Query $originalQuery
	 * @return Query
	 */
	public function processQuery($queryString, $originalQuery) {
		/** @var Query $query */
		$query = $this->objectManager->get(Query::class, $queryString);
		$query->setFieldList(array('title', 'url', 'teaser', 'score'));
		$query->setUserAccessGroups(GeneralUtility::trimExplode(',', $GLOBALS['TSFE']->gr_list));
		return $query;
	}

}
