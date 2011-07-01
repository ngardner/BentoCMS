<?php
/**
 * Form backend controller
 */
class Form extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objForm = new FormModel;
		$formList = $objForm->getForms();
		$this->view->assign('formList',$formList);
		$this->view->assign('content',$this->view->fetch('tpl/form/forms.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionForms($params='') {
		
		$this->actionIndex($params);
		
	}
	
	function actionEditForm($params='') {
		
		$objForm = new FormModel;
		
		$form_id = !empty($params['form_id'])?intval($params['form_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$form_id = $this->saveForm($params);
			$this->messages[] = array('type'=>'success','message'=>'Form has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionForms();
				return;
				
			}
			
		}
		
		if(!empty($form_id)) {
			
			$formInfo = $objForm->loadForm($form_id);
			$this->view->assign('formInfo',$formInfo);
			
		}
		
		$this->view->assign('content',$this->view->fetch('tpl/form/form.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateForm($params='') {
		
		$this->actionEditForm($params);
		
	}
	
	function actionDeleteForm($params='') {
		
		$objForm = new FormModel;
		
		if(!empty($params['form_id'])) {
			
			$deleted = $objForm->deleteForm($params['form_id']);
			
			if($deleted) {
				
				$this->messages[] = array('type'=>'success','message'=>'Form has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Unable to delete form.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown form.');
			
		}
		
		$this->actionIndex();
		return;
		
	}
	
	function actionDeleteFormField($params='') {
		
		$objForm = new FormModel;
		
		if(!empty($params['field_id'])) {
			
			$deleted = $objForm->deleteFormField($params['field_id']);
			
			if($deleted) {
				
				$this->messages[] = array('type'=>'success','message'=>'Field has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Unable to delete field.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown field.');
			
		}
		
		if(!empty($params['form_id'])) {
			
			$this->actionEditForm(array('form_id'=>$params['form_id']));
			
		} else {
			
			$this->actionIndex();
			
		}
		
		return;
		
	}
	
	function actionDeleteSubmission($params='') {
		
		if(!empty($params['submit_id'])) {
			
			$objForm = new FormModel;
			$objForm->deleteSubmission($params['submit_id']);
			
			$this->messages[] = array('type'=>'success','message'=>'Deleted form submission.');
			
		}
		
		if(!empty($params['form_id'])) {
			
			$this->actionViewSubmissions(array('form_id'=>$params['form_id']));
			
		} else {
			
			$this->actionIndex();
			
		}
		
	}
	
	function saveForm($params) {
		
		$objForm = new FormModel;
		$objSettings = Settings::getInstance();
		$defaultEmail = $objSettings->getEntry('admin', 'admin-email');
		
		$saveData = array();
		$saveData['id'] = !empty($params['form_id'])?intval($params['form_id']):false;
		$saveData['name'] = !empty($params['form_name'])?$params['form_name']:'Unnamed';
		$saveData['emailTo'] = !empty($params['form_emailto'])?$params['form_emailto']:$defaultEmail;
		$saveData['captcha'] = !empty($params['form_captcha'])?1:0;
		$saveData['thanksmsg'] = !empty($params['form_thanksmsg'])?$params['form_thanksmsg']:'Thanks';
		$saveData['emailSubject'] = !empty($params['form_emailsubject'])?$params['form_emailsubject']:'Form Submitted';
		$saveData['emailFrom'] = !empty($params['form_emailfrom'])?$params['form_emailfrom']:$defaultEmail;
		$saveData['keyName'] = !empty($params['form_keyname'])?$params['form_keyname']:false;
		
		if(!empty($params['form_new_fields'])) {
			
			foreach($params['form_new_fields']['name'] as $pointer=>$ignore) {
				
				if(!empty($params['form_new_fields']['name'][$pointer])) {
					
					$newField = array();
					$newField['name'] = !empty($params['form_new_fields']['name'][$pointer])?$params['form_new_fields']['name'][$pointer]:false;
					$newField['type'] = !empty($params['form_new_fields']['type'][$pointer])?$params['form_new_fields']['type'][$pointer]:false;
					$newField['field_name'] = !empty($params['form_new_fields']['field_name'][$pointer])?$params['form_new_fields']['field_name'][$pointer]:false;
					$newField['value'] = !empty($params['form_new_fields']['value'][$pointer])?$params['form_new_fields']['value'][$pointer]:false;
					$newField['values'] = !empty($params['form_new_fields']['values'][$pointer])?$params['form_new_fields']['values'][$pointer]:false;
					$newField['width'] = !empty($params['form_new_fields']['width'][$pointer])?$params['form_new_fields']['width'][$pointer]:false;
					$newField['height'] = !empty($params['form_new_fields']['height'][$pointer])?$params['form_new_fields']['height'][$pointer]:false;
					$newField['required'] = !empty($params['form_new_fields']['required'][$pointer])?$params['form_new_fields']['required'][$pointer]:false;
					$newField['validateAs'] = !empty($params['form_new_fields']['validateas'][$pointer])?$params['form_new_fields']['validateas'][$pointer]:false;
					$params['form_fields'][] = $newField;
					
				} else {
					
					// skip
					
				}
				
			}
			
		}
		
		if(!empty($params['form_fields'])) {
			
			foreach($params['form_fields'] as $field) {
				
				$fieldSaveData = array();
				$fieldSaveData['id'] = !empty($field['id'])?intval($field['id']):false;
				$fieldSaveData['name'] = !empty($field['name'])?$field['name']:'Unnamed';
				$fieldSaveData['type'] = !empty($field['type'])?$field['type']:'text';
				$fieldSaveData['values'] = !empty($field['values'])?$field['values']:false;
				$fieldSaveData['width'] = !empty($field['width'])?$field['width']:false;
				$fieldSaveData['height'] = !empty($field['height'])?$field['height']:false;
				$fieldSaveData['required'] = !empty($field['required'])?1:0;
				$fieldSaveData['validateAs'] = !empty($field['validateas'])?$field['validateas']:false;
				$fieldSaveData['value'] = !empty($field['value'])?$field['value']:false;
				$fieldSaveData['field_name'] = !empty($field['field_name'])?$field['field_name']:false;
				$saveData['fields'][] = $fieldSaveData;
				
			}
			
		}
		
		$form_id = $objForm->saveForm($saveData);
		
		return $form_id;
		
	}
	
	function actionSaveFieldsOrder($params='') {
		
		if(!empty($params['order'])) {
			
			$pageOrder = explode(',',$params['order']);
			
			$sortOrder = array();
			
			foreach($pageOrder as $order) {
				
				$sortOrder[] = substr($order,6);
				
			}
			
			$objForm = new FormModel;
			$objForm->saveFieldOrder($sortOrder);
			
		}
		
	}
	
	function actionExportData($params='') {
		
		$exportData = array();
		
		if(!empty($params['form_id']) && !empty($params['doexport']) && !empty($params['startDate']) && !empty($params['endDate'])) {
			
			$startDate = date("Y-m-d",strtotime($params['startDate']));
			$endDate = date("Y-m-d",strtotime($params['endDate']));
			$form_id = intval($params['form_id']);
			
			$objForm = new FormModel;
			
			$formData = $objForm->getSubmissions($form_id,$startDate,$endDate);
			
			if(!empty($formData)) {
				
				$formName = $formData[0]['form_name'];
				
				foreach($formData as $submission) {
					
					$exportDataRecord = array();
					$exportDataRecord['date'] = $submission['cDate'];
					
					if(!empty($submission['fields'])) {
						
						foreach($submission['fields'] as $submitField) {
							
							$exportDataRecord[$submitField['name']] = $submitField['value'];
							
						}
						
					}
					
					$exportData[] = $exportDataRecord;
					
				}
				
			}
			
			if(!empty($exportData)) {
				
				$headers = array_keys($exportData[0]);
				
				header("Content-type: application/CSV");
				header("Content-disposition: attachment; filename=".urlencode($formName)."_export.csv");
				
				foreach($headers as $header) {
					
					echo '"'.$header.'",';
					
				}
				
				echo "\r\n";
				
				foreach($exportData as $exportRecord) {
					
					foreach($exportRecord as $value) {
						
						echo '"'.$value.'",';
						
					}
					
					echo "\r\n";
					
				}
				
			}
			
		}
		
	}
	
}

?>
