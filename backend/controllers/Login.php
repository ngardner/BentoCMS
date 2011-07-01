<?php
/**
 * Admin Login controller
 */
class Login extends Controller {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function setLayout() {
		
		$this->layout = 'login.tpl';
		
	}
	
	function actionIndex($params='') {
		
		$this->actionLogin($params);
		
	}
	
	function actionLogin($params = '') {
		
		if(!empty($params['email']) && !empty($params['password'])) {
			
			if($this->objAuthentication->login($params['email'],$params['password'])) {
				
				header("Location: http://".URL."/admin/home/index");
				
			} else {
				
				$this->view->assign('errorMsg','Oops! Unable to login. ' . $this->objAuthentication->loginFailReason);
				$this->finish();
				
			}
			
		} else if($this->objAuthentication->loggedIn()){
			
			header("Location: http://".URL."/admin/home/index");
			
		} else {
			
			$this->finish();
			
		}
		
	}
	
	function actionLogout($params = '') {
		
		$this->objAuthentication->logout();
		$this->view->assign('UserInfo',null);
		$this->view->assign('loggedIn',false);
		$this->view->assign('showLogout',1);
		$this->finish();
		
	}
	
}

?>
