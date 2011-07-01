<?php
/**
 * Users backend controller
 */
class Users extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objUsers = new UserModel;
		$adminUsers = $objUsers->getUsers('admin');
		
		$this->view->assign('userList',$adminUsers);
		$this->view->assign('userType','Admin');
		$this->view->assign('content',$this->view->fetch('tpl/administration/users.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionNormalUsers($params='') {
		
		$objUsers = new UserModel;
		$users = $objUsers->getUsers('user');
		
		$this->view->assign('userList',$users);
		$this->view->assign('userType','Normal');
		$this->view->assign('content',$this->view->fetch('tpl/administration/users.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEdituser($params='') {
		
		$objUser = new UserModel;
		$user_id = !empty($params['user_id'])?intval($params['user_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$saveData = array();
			$saveData['id'] = !empty($params['user_id'])?$params['user_id']:false;
			$saveData['email'] = !empty($params['user_email'])?$params['user_email']:false;
			$saveData['password'] = !empty($params['user_password'])?$params['user_password']:false;
			$saveData['fName'] = !empty($params['user_fname'])?$params['user_fname']:false;
			$saveData['lName'] = !empty($params['user_lname'])?$params['user_lname']:false;
			$saveData['title'] = !empty($params['user_title'])?$params['user_title']:false;
			$saveData['type'] = !empty($params['user_type'])?$params['user_type']:'user';
			$saveData['permissions'] = !empty($params['user_permissions'])?$params['user_permissions']:false;
			$user_id = $objUser->saveUser($saveData);
			
			if($user_id) {
				
				$this->messages[] = array('type'=>'success','message'=>'User has been saved.');
				
				if($params['submit'] == 'Save and Close') {
					
					$this->actionIndex();
					return;
					
				}
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>$objUser->errorMsg);
				
			}
			
		}
		
		if($user_id) {
			
			$objUser->setUserId($user_id);
			
		}
		
		$userInfo = $objUser->getInfo();
		$this->view->assign('userInfo',$userInfo);
		
		$permissions = $objUser->permissions->getUserPermissionTable($user_id);
		$this->view->assign('permissions',$permissions);
		
		$this->view->assign('content',$this->view->fetch('tpl/administration/user.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateUser($params='') {
		
		$this->actionEdituser($params);
		
	}
	
	function actionDeleteUser($params='') {
		
		$user_id = !empty($params['user_id'])?intval($params['user_id']):false;
		
		$objUser = new UserModel;
		$objUser->deleteUser($user_id);
		$this->messages[] = array('type'=>'success','message'=>'User has been deleted.');
		$this->actionIndex();
		
	}
	
}

?>
