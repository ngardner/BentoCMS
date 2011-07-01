<?php

class EmailSender extends Model {
	
	public $errorMsg;
	
	function __construct() {
		
		parent::__construct();
		
		$this->objAuthentication = Authentication::getInstance();
		
		$this->view = new View('frontend');
		
	}
	
	function sendForm($form) {
		
		$objForms = new FormModel();
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		
		$formInfo = $objForms->loadForm($form['formSubmit']['id']);
		$formFields = $objForms->getSubmission($form['submission_id']);
		$this->view->assign('formFields', $formFields);
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-sendform');
		
		// modify email subject
		if(!empty($form['pageTitle'])) {
			
			$subject = $formInfo['emailSubject'].' - '.$form['pageTitle'];
			
		} else if(!empty($form['returnUrlRequest'])) {
			
			$subject = $formInfo['emailSubject'].' - '.$form['returnUrlRequest'];
			
		} else {
			
			$subject = $formInfo['emailSubject'];
			
		}
		
		$objEmailer->setFrom($formInfo['emailFrom'],PRODUCT_NAME);
		$objEmailer->addTo($formInfo['emailTo']);
		$objEmailer->setSubject($subject);
		
		// assign vars to template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		// render template
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		
		// send email
		$objEmailer->sendMail();
		
		return true;
		
	} 
	
	function sendUserRegisterAdmin($user_id) {
		
		$objEmailer = new Emailer();
		$objSettings = Settings::getInstance();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objUser = new UserModel($user_id);
		$adminEmail = $objSettings->getEntry('admin', 'admin-email');
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-register');
		$userInfo = $objUser->getInfo();
		
		// assign vars to template
		$this->view->assign('userInfo', $userInfo);
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($adminEmail);
		$objEmailer->setSubject('NEW USER / User Account Created '.$userInfo['company']);
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
	function sendUserLogin($user_id) {
		
		$objEmailer = new Emailer();
		$objSettings = Settings::getInstance();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objUser = new UserModel($user_id);
		$adminEmail = $objSettings->getEntry('admin', 'admin-email');
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-login');
		
		$this->view->assign('userInfo', $objUser->getInfo());
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($adminEmail);
		$objEmailer->setSubject('CS PRIMARY CONTACT LOGIN');
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
	function sendUserChangePassword($user_id) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objUser = new UserModel($user_id);
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-changepassword');
		
		$userInfo = $objUser->getInfo();
		$this->view->assign('userInfo', $userInfo);
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($userInfo['email'],$userInfo['fName'].' '.$userInfo['lName']);
		$objEmailer->setSubject('Change to your Parks Associates account');
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
	function sendUserForgotPassword($userInfo) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-forgotpassword');
		
		$this->view->assign('userInfo', $userInfo);
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($userInfo['email'],$userInfo['fName'].' '.$userInfo['lName']);
		$objEmailer->setSubject('Parks Associates Password Reset');
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
	function sendOrderClient($order_id) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objOrder = new OrderModel;
		$objWebcasts = new WebcastsModel;
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-order');
		
		// load order
		$orderInfo = $objOrder->getOrder($order_id);
		
		// assign to template
		$this->view->assign('adminView',false);
		$this->view->assign('orderInfo',$orderInfo);
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		// send email
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($orderInfo['bill_email'],$orderInfo['bill_fname'].' '.$orderInfo['bill_lname']);
		$objEmailer->setSubject('Thank you for your order');
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		// see if we also need to send webcast registration email
		if(!empty($orderInfo['webcasts'])) {
			
			foreach($orderInfo['webcasts'] as $webcast) {
				
				// see if its in the future
				if(strtotime($webcast['eventDate']) > time()) {
					
					// send them email
					$this->sendWebcastRegistrationClient($orderInfo);
					break; // only need to send once
					
				}
				
			}
			
		}
		
		return true;
		
	}
	
	function sendOrderAdmin($order_id) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objOrder = new OrderModel;
		$objUser = new UserModel;
		$objSettings = Settings::getInstance();
		$adminEmail = $objSettings->getEntry('admin','admin-email');
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-order');
		
		// load order
		$orderInfo = $objOrder->getOrder($order_id);
		$objUser->setUserId($orderInfo['user_id']);
		$userInfo = $objUser->getInfo();
		
		// assign to template
		$this->view->assign('adminView',true);
		$this->view->assign('orderInfo',$orderInfo);
		$this->view->assign('userInfo',$userInfo);
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		// send email
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($adminEmail,"Parks Associates");
		$objEmailer->setSubject('NEW PURCHASE');
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		// see if we also need to send webcast registration email
		if(!empty($orderInfo['webcasts'])) {
			
			foreach($orderInfo['webcasts'] as $webcast) {
				
				// see if its in the future
				if(strtotime($webcast['eventDate']) > time()) {
					
					// send them email
					$this->sendWebcastRegistrationAdmin($orderInfo);
					break; // only need to send once
					
				}
				
			}
			
		}
		
		return true;
		
	}
	
	function sendWebcastRegistrationClient($orderInfo) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-webcastregistration');
		
		$this->view->assign('orderInfo', $orderInfo);
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($orderInfo['bill_email'],$userInfo['bill_fname'].' '.$userInfo['bill_lname']);
		$objEmailer->setSubject('Webcast Registration details');
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
	function sendWebcastRegistrationAdmin($orderInfo) {
		
		$objEmailer = new Emailer();
		$objLayout = new LayoutModel();
		$objTemplate = new TemplatesModel();
		$objSettings = Settings::getInstance();
		$adminEmail = $objSettings->getEntry('admin','admin-email');
		
		$layoutInfo = $objLayout->loadLayout(28);
		$template = $objTemplate->loadTemplateFromKeyname('email-webcastregistration-admin');
		
		$this->view->assign('orderInfo', $orderInfo);
		
		$objEmailer->setFrom('noreply@parksassociates.com');
		$objEmailer->addTo($adminEmail);
		$objEmailer->setSubject('WEBCAST PURCHASE');
		
		// render template
		$this->view->assign('content', $this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left', $this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right', $this->view->fetch('fromstring:'.$template['right_sidebar']));
		
		$objEmailer->setBody($this->view->fetch('fromstring:'.$layoutInfo['code']), true);
		$objEmailer->sendMail();
		
		return true;
		
	}
	
}


?>
