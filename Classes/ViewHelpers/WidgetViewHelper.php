<?php

namespace ApacheSolrForTypo3\Solrwidget\ViewHelpers;

use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

class WidgetViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @var ObjectManagerInterface
	 */
	protected $objectManagerNative;

	/**
	 * If set to TRUE, it is an AJAX widget.
	 *
	 * @var boolean
	 * @api
	 */
	protected $ajaxWidget = TRUE;

	/**
	 * @var \ApacheSolrForTypo3\Solrwidget\Controller\WidgetController
	 */
	protected $controller;

	/**
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManagerNative(ObjectManagerInterface $objectManager) {
		$this->objectManagerNative = $objectManager;
	}

	/**
	 * Initialize this ViewHelper instance
	 *
	 * @return void
	 */
	public function initialize() {
		$controllerClassReflection = new \ReflectionClass($this);
		$methodReflection = $controllerClassReflection->getProperty('controller');
		$docComment = $methodReflection->getDocComment();
		$matches = array();
		preg_match('/@var[\s]{1,}[\\\\]?([\\\\0-9a-zA-Z_\\^\s]+)/', $docComment, $matches);
		$controllerClassName = trim($matches[1]);
		if (class_exists($controllerClassName) === FALSE) {
			throw new \Exception('Unknown Controller class: ' . $controllerClassName, 1351355695);
		}
		// fallback enabled for Singleton Controllers; however, the initializeContorller method is
		// also enabled for use by classes which have not yet removed their controller inject methods.
		if (method_exists($this, 'injectController') && is_a($controllerClassName, \TYPO3\CMS\Core\SingletonInterface)) {
			$controllerInstance = $this->objectManagerNative->get($controllerClassName);
			$this->injectController($controllerInstance);
		} else {
			$controllerInstance = $this->objectManagerNative->get($controllerClassName);
		}
		$this->controller = $controllerInstance;
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->initiateSubRequest();
	}

}
