<?php
/**
 * Home page backend controller
 */
class Home extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objUsers = new UserModel;
		$latestLogins = $objUsers->getRecentLogins(true);
		$this->view->assign('latestLogins',$latestLogins);
		$this->view->assign('content', $this->view->fetch('tpl/home/index.tpl'));
		$this->finish();
		
	}
	
}

?>
