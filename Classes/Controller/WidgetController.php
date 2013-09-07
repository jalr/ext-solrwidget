<?php


class Tx_Solrwidget_Controller_WidgetController extends Tx_Fluid_Core_Widget_AbstractWidgetController {

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
		$results = array('q' => $query);
		$json = json_encode($results, JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
		header('Content-type: text/json');
		header('Content-length: ' . strlen($json));
		echo $json;
		exit();
	}

}
