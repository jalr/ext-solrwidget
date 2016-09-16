<?php

namespace ApacheSolrForTypo3\Solrwidget\Utility;

abstract class SolrResultFormatter {

	/**
	 * @param \Apache_Solr_Response $response
	 * @return array
	 */
	public static function format(\Apache_Solr_Response $response) {
		$status = $response->getHttpStatus();
		if (100 > $status || 400 <= $status) {
			return array(
				'error' => array(
					'status' => $status,
					'message' => $response->getHttpStatusMessage()
				)
			);
		}
		$results = array();
		if (1 > $response->response->numFound) {
			return $results;
		}
		foreach ($response->response->docs as $result) {
			/** @var Apache_Solr_Document $result */
			$fields = $result->getFieldNames();
			$resultData = array();
			foreach ($fields as $fieldName) {
				$resultData[$fieldName] = $result->$fieldName;
			}
			array_push($results, $resultData);
		}
		return $results;
	}

}
