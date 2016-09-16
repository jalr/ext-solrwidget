<?php

namespace ApacheSolrForTypo3\Solrwidget\QueryProvider;

use ApacheSolrForTypo3\Solr\Query;

interface QueryProviderInterface {

	/**
	 * @param string $queryString
	 * @param Query $originalQuery
	 * @return string
	 */
	public function getTitle($queryString, $originalQuery);

	/**
	 * @param string $queryString
	 * @param Query $originalQuery
	 * @return Query
	 */
	public function processQuery($queryString, $originalQuery);

}
