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
		
		$this->view->assign('pageTitle','Permission Denied');
		$this->view->assign('content',$this->view->fetch('fromstring:To view this page, you must have an account. <a href="/user/login">Login or Register</a> to continue.'));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
}

?>
