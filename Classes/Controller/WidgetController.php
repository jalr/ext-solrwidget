<?php


class Tx_Solrwidget_Controller_WidgetController extends Tx_Fluid_Core_Widget_AbstractWidgetController {

	/**
	 * @var tx_solr_pi_results
	 */
	protected $searcher;

	/**
	 * @return void
	 */
	public function initializeAction() {
		/** @var tx_solr_pi_results $searcher */
		$searcher = $this->objectManager->get('tx_solr_pi_results');
		$searcher->cObj = $this->configurationManager->getContentObject();
		$searcher->main('', array());
		$this->searcher = $searcher;
	}

	/**
	 * @return void
	 */
	public function indexAction() {

	}

	/**
	 * @param string $query
	 * @return void
	 */
	public function searchAction($query) {
		$providers = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['solrwidget']['queryProviders'];
		$results = array();
		foreach ($providers as $providerClassName) {
			/** @var Tx_Solrwidget_QueryProvider_QueryProviderInterface $provider */
			$provider = $this->objectManager->get($providerClassName);
			$providedQueryOrNull = $provider->processQuery($query, TRUE === isset($results[0]) ? $results[0] : NULL);
			if (NULL === $providedQueryOrNull) {
				continue;
			}
			array_push($results, array(
				'title' => $provider->getTitle($query, TRUE === isset($queries[0]) ? $queries[0] : NULL),
				'query' => $providedQueryOrNull,
				'results' => $this->querySolrServer($providedQueryOrNull)
			));
		}
		if (1 === count($results)) {
			$candidate = $results[0]['results'];
		} else {
			$candidate = $results;
		}
		$json = json_encode($candidate, JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
		header('Content-type: text/json');
		header('Content-length: ' . strlen($json));
		echo $json;
		exit();
	}

	/**
	 * @param tx_solr_Query $query
	 * @return mixed
	 */
	protected function querySolrServer(tx_solr_Query $query) {
		$queryString = $query->getQueryString();
		$this->searcher->getSearch()->search($query);
		$response = $this->searcher->getSearch()->getResponse();
		if (1 > $response->response->numFound && 0 < $response->spellcheck->suggestions->{$queryString}->numFound) {
			$firstSuggestedQueryString = $response->spellcheck->suggestions->{$queryString}->suggestion[0];
			$query->setQueryString($firstSuggestedQueryString);
			$query->useRawQueryString(TRUE);
			$response = $this->searcher->getSearch()->search($query);
		}
		return Tx_Solrwidget_Utility_SolrResultFormatter::format($response);
	}

}
