<?php
/**
 * Smarty {form} function plugin
 *
 * Type:     function<br>
 * Name:     form<br>
 * Purpose:  generates form from database<br>
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_function_form($localparams, &$smarty) {
    
    global $params;
    
    if(!empty($localparams['identifier'])) {
        
        $objForm = new FormModel;
        $objAuth = Authentication::getInstance();
        $objTemplate = new TemplatesModel;
				$objUser = new UserModel($objAuth->user_id);
        
				$userInfo = $objUser->getInfo();
        $form_id = $objForm->getFormId($localparams['identifier']);
        
        if($form_id) {
            
            $formInfo = $objForm->loadForm($form_id);
            $templateInfo = $objTemplate->loadTemplateFromKeyname('form');
						
						// assign values if already submitted
						if(!empty($params['formSubmit']['fields']) && !empty($formInfo['fields'])) {
								
								foreach($formInfo['fields'] as &$formField) {
										
										foreach($params['formSubmit']['fields'] as $submittedId => $submittedValue) {
												
												if($formField['id'] == $submittedId) {
														
														if($formField['type'] == 'checkbox' || $formField['type'] == 'radio') {
																$formField['checked'] = 'checked';
														} else {
																$formField['value'] = $submittedValue;
														}
														break;
														
												}
												
										}
										
								}
								
						}
						
						// assign error flag and message if invalid
						if(!empty($params['formErrors']) && !empty($formInfo['fields'])) {
								
								foreach($params['formErrors'] as $formError) {
										
										foreach($formInfo['fields'] as &$formField) {
												
												if($formError['field_id'] == $formField['id']) {
														
														$formField['hasError'] = true;
														$formField['errorMsg'] = $formError['errorMsg'];
														break;
														
												}
												
										}
										
								}
								
						}
						
						// assign var to template
						if(!empty($params['formSubmitted'])) {
								$smarty->assign('formSubmitted',1);
						}
						
						if(!empty($params['formErrors'])) {
								$smarty->assign('formErrors',$params['formErrors']);
						}
						
						$smarty->assign('formInfo',$formInfo);
						$output = $smarty->fetch('fromstring:'.$templateInfo['content']);
            
        } else {
            
            return 'Unknown form identifier';
            
        }
        
    } else {
        
        return 'Must pass an identifier';
        
    }
    
    return $output;
    
}

?>
