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
		
		$objBlog = new BlogModel;
		$latestComments = $objBlog->getComments(array('status'=>'pending','limit'=>'5'));
		
		$objSearch = new SearchModel;
		$popularSearches = $objSearch->getPopular(array('startDate'=>date("Y-m-d",strtotime('-1 Month')),'endDate'=>date("Y-m-d"),'howMany'=>5));
		
		$this->view->assign('latestLogins',$latestLogins);
		$this->view->assign('latestComments', $latestComments);
		$this->view->assign('popularSearches', $popularSearches);
		$this->view->assign('content', $this->view->fetch('tpl/home/index.tpl'));
		$this->finish();
		
	}
	
}

?>
