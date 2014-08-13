<?php

class Tx_Solrwidget_QueryProvider_InitialQueryProvider implements Tx_Solrwidget_QueryProvider_QueryProviderInterface {

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * @param string $queryString
	 * @param tx_solr_Query $originalQuery
	 * @return string
	 */
	public function getTitle($queryString, $originalQuery) {
		$label = Tx_Extbase_Utility_Localization::translate('initialQueryGroupTitle', 'Solrwidget');
		return NULL === $label ? 'Default' : $label;
	}

	/**
	 * @param string $queryString
	 * @param tx_solr_Query $originalQuery
	 * @return tx_solr_Query
	 */
	public function processQuery($queryString, $originalQuery) {
		/** @var tx_solr_Query $query */
		$query = $this->objectManager->get('tx_solr_Query', $queryString);
		$query->setFieldList(array('title', 'url', 'teaser', 'score'));
		$query->setUserAccessGroups(t3lib_div::trimExplode(',', $GLOBALS['TSFE']->gr_list));
		return $query;
	}

}
