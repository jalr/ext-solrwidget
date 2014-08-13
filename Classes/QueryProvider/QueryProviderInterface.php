<?php

interface Tx_Solrwidget_QueryProvider_QueryProviderInterface {

	/**
	 * @param string $queryString
	 * @param tx_solr_Query $originalQuery
	 * @return string
	 */
	public function getTitle($queryString, $originalQuery);

	/**
	 * @param string $queryString
	 * @param tx_solr_Query $originalQuery
	 * @return tx_solr_Query
	 */
	public function processQuery($queryString, $originalQuery);

}
