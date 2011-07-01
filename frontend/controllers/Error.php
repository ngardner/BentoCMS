<?php
/**
 * Error controller
 */
class Error extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function setLayout() {
		
		$this->layout = 'cms.tpl';
		
	}
	
	function actionRequiresaccount($params='') {
		
		$objLayout = new LayoutModel;
		$layoutInfo = $objLayout->loadLayout();
		$this->view->assign('content','To view this page, you must have an account. Please login or register.');
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
}

?>
