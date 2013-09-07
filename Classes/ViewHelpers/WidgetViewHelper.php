<?php

class Tx_Solrwidget_ViewHelpers_WidgetViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * If set to TRUE, it is an AJAX widget.
	 *
	 * @var boolean
	 * @api
	 */
	protected $ajaxWidget = TRUE;

	/**
	 * @var Tx_Solrwidget_Controller_WidgetController
	 */
	protected $controller;

	/**
	 * Initialize this ViewHelper instance
	 *
	 * @return void
	 */
	public function initialize() {
		$controllerClassReflection = new ReflectionClass($this);
		$methodReflection = $controllerClassReflection->getProperty('controller');
		$docComment = $methodReflection->getDocComment();
		$matches = array();
		preg_match('/@var[\s]{1,}([a-zA-Z_\\^\s]+)/', $docComment, $matches);
		$controllerClassName = trim($matches[1]);
		if (class_exists($controllerClassName) === FALSE) {
			throw new Exception('Unknown Controller class: ' . $controllerClassName, 1351355695);
		}
		// fallback enabled for Singleton Controllers; however, the initializeContorller method is
		// also enabled for use by classes which have not yet removed their controller inject methods.
		if (method_exists($this, 'injectController') && is_a($controllerClassName, 't3lib_Singleton')) {
			$controllerInstance = $this->objectManager->get($controllerClassName);
			$this->injectController($controllerInstance);
		} else {
			$controllerInstance = $this->objectManager->create($controllerClassName);
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