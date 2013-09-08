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
		$solrQuery = $this->buildSolrQuery($query);
		$results = $this->querySolrServer($solrQuery);
		$json = json_encode($results, JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
		header('Content-type: text/json');
		header('Content-length: ' . strlen($json));
		echo $json;
		exit();
	}

	/**
	 * @param string $queryString
	 * @return tx_solr_Query
	 */
	protected function buildSolrQuery($queryString) {
		/** @var tx_solr_Query $query */
		$query = $this->objectManager->get('tx_solr_Query', $queryString);
		$query->setQueryField('title', 1.0);
		$query->addReturnField('title');
		$query->setFieldList(array('title', 'url', 'teaser', 'score'));
		return $query;
	}

	/**
	 * @param tx_solr_Query $query
	 * @return mixed
	 */
	protected function querySolrServer(tx_solr_Query $query) {
		$this->searcher->renderCommand('search', array());
		$this->searcher->getSearch()->search($query);
		$response = $this->searcher->getSearch()->getResponse();
		return Tx_Solrwidget_Utility_SolrResultFormatter::format($response);
	}

}
