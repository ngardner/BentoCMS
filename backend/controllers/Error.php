<?php
/**
 * Error backend controller
 */
class Error extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionPermission($params='') {
		
		$this->view->assign('content', $this->view->fetch('tpl/error/nopermission.tpl'));
		$this->finish();
		
	}
	
}

?>
