<?php

class User extends Controller {
	
	var $errorMsg;
	
	function __construct() {
		
		parent::__construct();
		$this->PermissionsModel = new PermissionsModel;
		$this->objAuthentication = Authentication::getInstance();
		
	}
	
	function setLayout() {
		
		$this->layout = 'user.tpl';
		
	}
	
	function actionLogin($params = '') {
		
		$loginError=false;
		$returnUrl = !empty($params['returnUrlRequest'])?$params['returnUrlRequest']:false;
		
		if(!empty($params['dologin'])) {
			
			if(!empty($params['email']) && !empty($params['password'])) {
				
				if($this->objAuthentication->login($params['email'],$params['password'])) {
					
					$objUser = new UserModel($this->objAuthentication->user_id);
					
					// where do we take them after login?
					if(!empty($returnUrl) && $returnUrl != 'User/login') {
						
						// if returnUrl, take them there
						header("Location: http://".URL.'/'.$returnUrl);
						
					} else {
						
						// otherwise take them to profile
						header("Location: http://".URL."/User/profile");
						
					}
					
				} else {
					
					$loginError = $this->objAuthentication->loginFailReason;
					
				}
				
			}
			
		}
		
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$layoutInfo = $objLayout->loadLayout();
		$template = $objTemplate->loadTemplateFromKeyname('user-login');
		
		$this->view->assign('urlrequest',$returnUrl);
		$this->view->assign('errorMsg',$loginError);
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
	function actionRegister($params='') {
		
		$errorMsg = false;
		$returnUrl = !empty($params['returnUrlRequest'])?$params['returnUrlRequest']:false;
		
		if(!empty($params['doregister'])) {
			
			$params['active'] = 1; // by default user is active
			$params['activateString'] = md5(time());
			
			$user_id = $this->saveUser($params);
			
			if(!$user_id) {
				
				$errorMsg = $this->errorMsg;
				
			} else {
				
				// success
				$this->objAuthentication->login($params['user_email'],$params['user_password']);
				
				// send new user registration email
				$objEmailer = new EmailSender;
				$objEmailer->sendUserRegisterAdmin($user_id);
				
				if(!empty($returnUrl) && $returnUrl != 'User/register') {
					
					header("Location: http://".URL.'/'.$returnUrl);
					
				} else {
					
					header("Location: http://".URL.'/User/profile');
					
				}
				
				return;
				
			}
			
		}
		
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$layoutInfo = $objLayout->loadLayout();
		$template = $objTemplate->loadTemplateFromKeyname('user-register');
		
		$this->view->assign('urlrequest',$returnUrl);
		$this->view->assign('errorMsg',$errorMsg);
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
	function saveUser($data) {
		
		// dont trust posted user_id
		if(!empty($data['user_id'])) {
			if($data['user_id'] != $this->objAuthentication->user_id) {
				// editing someone elses profile
				$this->errorMsg = 'Unauthorized';
				return false;
			}
		}
		
		$user_id = false;
		$this->errorMsg = null;
		
		$saveData = array();
		$saveData['id'] = !empty($data['user_id'])?intval($data['user_id']):false;
		$saveData['email'] = !empty($data['user_email'])?$data['user_email']:false;
		$saveData['company'] = !empty($data['user_company'])?$data['user_company']:false;
		$saveData['title'] = !empty($data['user_title'])?$data['user_title']:false;
		$saveData['fName'] = !empty($data['user_fName'])?$data['user_fName']:false;
		$saveData['lName'] = !empty($data['user_lName'])?$data['user_lName']:false;
		$saveData['phone'] = !empty($data['user_phone'])?$data['user_phone']:false;
		$saveData['address'] = !empty($data['user_address'])?$data['user_address']:false;
		$saveData['address2'] = !empty($data['user_address2'])?$data['user_address2']:false;
		$saveData['city'] = !empty($data['user_city'])?$data['user_city']:false;
		$saveData['province'] = !empty($data['user_province'])?$data['user_province']:false;
		$saveData['country'] = !empty($data['user_country'])?$data['user_country']:false;
		$saveData['zip'] = !empty($data['user_zip'])?$data['user_zip']:false;
		
		if(!empty($data['user_password'])) {
			
			$saveData['password'] = !empty($data['user_password'])?$data['user_password']:false;
			
		}
		
		if(isset($data['active'])) {
			
			$saveData['active'] = intval($data['active']);
			
		}
		
		if(!empty($data['activateString'])) {
			
			$saveData['activateString'] = $data['activateString'];
			
		}
		
		// validate it all
		$objValidator = new Validator;
		$objValidator->validateEmail($saveData['email']);
		$objValidator->validateName($saveData['fName']);
		$objValidator->validateName($saveData['lName']);
		$objValidator->validatePhone($saveData['phone']);
		$objValidator->validateNotEmpty($saveData['title'],'title');
		$objValidator->validateNotEmpty($saveData['company'],'company');
		$objValidator->validateNotEmpty($saveData['address'],'address');
		$objValidator->validateNotEmpty($saveData['city'],'city');
		$objValidator->validateNotEmpty($saveData['province'],'province');
		$objValidator->validateNotEmpty($saveData['country'],'country');
		$objValidator->validateNotEmpty($saveData['zip'],'zip');
		
		if(!empty($data['password'])) {
			
			$objValidator->validatePassword($saveData['password']);
			@$objValidator->passwordsMatch($saveData['password'],$data['password2']);
			
		}
		
		if($objValidator->hasError) {
			
			$this->errorMsg = $objValidator->getError();
			
		} else {
			
			$objUser = new UserModel;
			$user_id = $objUser->saveUser($saveData);
			
		}
		
		return $user_id;
		
	}
	
	
	function actionForgotPassword($params = '') {
		
		$errorMsg = '';
		$message = '';
		
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$layoutInfo = $objLayout->loadLayout();
		
		//$template = $objTemplate->loadTemplateFromKeyname('user-forgotpassword');
		$template = array(
			'left_sidebad'=>'',
			'right_sidebar'=>'',
			'content'=>'
				<form method="post" action="/user/forgotpassword">
				<input type="hidden" name="resetpassword" value="1"/>
				<label>Email:</label><input type="text" name="email">
				</form>
			'
		);
		
		if(!empty($params['resetpassword']) && !empty($params['email'])) {
			
			$objUser = new UserModel;
			$userInfo = $objUser->forgotPassword($params['email']);
			
			if($userInfo) {
				
				$objEmailer = new EmailSender;
				$objEmailer->sendUserForgotPassword($userInfo);
				$message = 'Your password has been reset, please check your email for the new password.';
				
			} else {
				
				$errorMsg = 'Unable to find a user with that email address.';
				
			}
			
		}
		
		$this->view->assign('errorMsg',$errorMsg);
		$this->view->assign('message',$message);
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
	function actionLogout($params = '') {
		
		$this->objAuthentication->logout();
		header("Location: http://".URL);
		
	}
	
	function actionProfile($params='') {
		
		$message = !empty($params['message'])?$params['message']:false;
		
		$this->objAuthentication->requiresAccount();
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		$userInfo = $userModel->getInfo();
		
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$layoutInfo = $objLayout->loadLayout();
		$template = $objTemplate->loadTemplateFromKeyname('user-profile');
		
		$this->view->assign('message',$message);
		$this->view->assign('userInfo',$userInfo);
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
	function actionSaveProfile($params='') {
		
		$this->objAuthentication->requiresAccount();
		
		if(!empty($params['user_id']) && $params['user_id'] == $this->objAuthentication->user_id) {
			
			$saved = $this->saveUser($params);
			
			if($saved) {
				
				$this->actionProfile(array('message'=>'Your profile has been saved.'));
				return;
				
			} else {
				
				$returnError = 'Unable to save profile. ';
				
				if(!empty($this->errorMsg)) {
					
					if(is_array($this->errorMsg)) {
						
						foreach($this->errorMsg as $errorMsg) {
							
							$returnError .= $errorMsg.' ';
							
						}
						
					} else {
						
						$returnError .= $this->errorMsg;
						
					}
					
				}
				
				$this->actionProfile(array('message'=>$returnError));
				return;
				
			}
			
		} else {
			
			// no user id passed, or incorrect id passed
			$this->actionProfile(array('message'=>'Profile not saved.'));
			return;
			
		}
		
	}
	
	function actionChangePassword($params='') {
		
		$this->objAuthentication->requiresAccount();
		
		$errorMsg = false;
		$changedpassword = false;
		
		if(!empty($params['changepassword'])) {
			
			$objUser = new UserModel;
			
			if(!empty($params['orignal_pw']) && !empty($params['password']) && !empty($params['password2'])) {
				
				// verify old password
				$passwordMatch = $objUser->testPassword($this->objAuthentication->user_id,$params['orignal_pw']);
				
				if($passwordMatch) {
					
					$objValidation = new Validator;
					$objValidation->validatePassword($params['password']);
					$objValidation->passwordsMatch($params['password'],$params['password2']);
					
					if($objValidation->hasError) {
						
						$errorMsg = $objValidation->getError();
						
						if(is_array($errorMsg)) {
							
							$errorMsg = implode(', ',$errorMsg);
							
						}
						
					} else {
						
						$saveData = array();
						$saveData['id'] = $this->objAuthentication->user_id;
						$saveData['password'] = $this->objAuthentication->encryptPassword($params['password']);
						$changedpassword = $objUser->save($saveData,'users');
						
						if($changedpassword) {
							
							$objEmailer = new EmailSender;
							$objEmailer->sendUserChangePassword($this->objAuthentication->user_id);
							
						} else {
							
							$errorMsg = 'Unable to change password.';
							
						}
						
					}
					
				} else {
					
					$errorMsg = 'Current password incorrect.';
					
				}
				
			} else {
				
				$errorMsg = 'Current password and new password are required.';
				
			}
			
		}
		
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$layoutInfo = $objLayout->loadLayout();
		$template = $objTemplate->loadTemplateFromKeyname('user-changepassword');
		
		$this->view->assign('errorMsg',$errorMsg);
		$this->view->assign('changedpassword',$changedpassword);
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
}

?>
