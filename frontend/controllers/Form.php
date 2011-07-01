<?php
/**
 * Form controller
 */
class Form extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionSubmit($params2='') {
		
		// loading $params from global so that the View can load the proper _urlrequest
		global $params;
		$params = array_merge($params,$params2);
		
		$hasError = false;
		$formErrors = array();
		
		if(!empty($params['formSubmit'])) {
			
			$objEmail = new EmailSender();
			$objForm = new FormModel;
			
			if(!empty($params['formSubmit']['id'])) {
				
				$formInfo = $objForm->loadForm($params['formSubmit']['id']);
				
				// validate forms fields
				if(!empty($formInfo['fields'])) {
					
					foreach($formInfo['fields'] as $formField) {
						
						if($formField['required'] == 1) {
							
							if(empty($params['formSubmit']['fields'][$formField['id']])) {
								
								$hasError = true;
								$formError = array('field_id'=>$formField['id'],'errorMsg'=>$formField['name'].' is required.');
								$formErrors[] = $formError;
								
							}
							
						}
						
					}
					
				}
				
				if($hasError) {
					
					// return to page with error message
					if(!empty($params['returnUrlRequest']) && $params['returnUrlRequest'] != 'Form/submit') {
						
						$objDispatcher = new Dispatcher;
						$objFriendlyUrl = new FriendlyurlModel;
						$objFriendlyUrl->parseRequest($params['returnUrlRequest']);
						$controller = $objFriendlyUrl->requestController;
						$action = $objFriendlyUrl->requestAction;
						
						$params = array_merge($params,$objFriendlyUrl->requestParams);
						$params['_urlrequest'] = $params['returnUrlRequest'];
						$params['formErrors'] = $formErrors;
						
						$objDispatcher->setDirectory('frontend');
						$objDispatcher->setController($controller);
						$objDispatcher->setAction($action);
						$objDispatcher->setParams($params);
						$objDispatcher->dispatch();
						exit();
						
						
					} else {
						
						die('Please go back and retry submitting the form. Errors: '.print_r($formErrors));
						
					}
					
				}
				
				
				// save to database
				$submission_id = $this->saveToDb($params);
				
				if($submission_id) {
					
					$params['submission_id'] = $submission_id;
					
				}
				
				// email notification
				$objEmail->sendForm($params);
				
				// return to page with thanks message
				if(!empty($params['returnUrlRequest'])) {
					
					header("Location: http://".URL.'/'.$params['returnUrlRequest'].'?formSubmitted=true');
					
				} else {
					
					header("Location: http://".URL.'/');
					
				}
				
			}
			
		}
		
	}
	
	private function saveToDb($params) {
		
		$objAuth = Authentication::getInstance();
		$user_id = $objAuth->user_id;
		$objForm = new FormModel;
		
		$saveData = array();
		$saveData['form_id'] = $params['formSubmit']['id'];
		$saveData['user_id'] = $user_id;
		$saveData['fromPage'] = !empty($params['returnUrlRequest'])?$params['returnUrlRequest']:false;
		
		if(!empty($params['formSubmit']['fields'])) {
			
			foreach($params['formSubmit']['fields'] as $fieldId=>$value) {
				
				$fieldInfo = array();
				$fieldInfo['field_id'] = intval($fieldId);
				$fieldInfo['value'] = $value;
				$saveData['fields'][] = $fieldInfo;
				
			}
			
		}
		
		if(!empty($params['formSubmit']['userfields'])) {
			
			foreach($params['formSubmit']['userfields'] as $fieldId=>$value) {
				
				$fieldInfo = array();
				$fieldInfo['field_id'] = intval($fieldId);
				$fieldInfo['value'] = $value;
				$saveData['fields'][] = $fieldInfo;
				
			}
			
		}
		
		$submission_id = $objForm->saveSubmission($saveData);
		
		return $submission_id;
		
	}
	
}

?>
