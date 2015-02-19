<?php

class FormModel extends Model {
	
	var $totalSubmissions;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveForm($data) {
		
		if(!empty($data['fields'])) {
			
			$form_fields = $data['fields'];
			unset($data['fields']);
			
		}
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['name'],'forms');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'forms',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'forms');
				
			}
			
		}
		
		$form_id = $this->save($data,'forms');
		
		if(!empty($form_id) && !empty($form_fields)) {
			
			$this->saveFormFields($form_fields,$form_id);
			
		}
		
		return $form_id;
		
	}
	
	function saveFormFields($fields,$form_id) {
		
		$form_id = intval($form_id);
		
		if(!empty($fields)) {
			
			foreach($fields as $field) {
				
				$field['form_id'] = $form_id;
				$this->save($field,'forms_fields');
				
			}
			
		}
		
	}
	
	function saveFieldOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$field_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('forms_fields',"`id`=".intval($field_id));
			
		}
		
		return true;
		
	}
	
	function loadForm($id) {
		
		$id = intval($id);
		$formInfo = $this->load($id,'forms');
		$formInfo['fields'] = $this->loadFormFields($id);
		return $formInfo;
		
	}
	
	function getFormId($keyName) {
		
		return $this->db->getOne("SELECT `id` FROM `forms` WHERE `keyName` = '".$this->db->makeSafe($keyName)."'");
		
	}
	
	function getFieldId($keyName) {
		
		return $this->db->getOne("SELECT `id` FROM `forms_fields` WHERE `field_name` = '".$this->db->makeSafe($keyName)."'");
		
	}
	
	function loadFormFields($id) {
		
		$id = intval($id);
		
		$sql = "
		SELECT
			*
		FROM
			`forms_fields`
		WHERE
			`form_id` = ".$id."
		ORDER BY
			`displayOrder`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function getForms() {
		
		$sql = "
		SELECT
			f.*,
			count(s.`id`) as 'numb_submissions'
		FROM
			`forms` as f
		LEFT JOIN
			`forms_submitted` as s ON f.`id` = s.`form_id`
		GROUP BY
			f.`id`
		ORDER BY
			`name`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function getFormIdentifier($id) {
		
		return $this->db->getOne("SELECT `keyName` FROM `forms` WHERE `id` = ".intval($id));
		
	}
	
	function deleteForm($id) {
		
		$id = intval($id);
		
		$sql = "
		SELECT
			`id`
		FROM
			`forms_fields`
		WHERE
			`form_id` = ".$id."
		";
		
		$field_ids = $this->db->getCol($sql);
		
		if(!empty($field_ids)) {
			
			foreach($field_ids as $field_id) {
				
				$this->deleteFormField($field_id);
				
			}
			
		}
		
		$this->db->delete('forms',$id);
		
		return true;
		
	}
	
	function deleteFormField($id) {
		
		$id = intval($id);
		
		$this->db->delete('forms_fields',$id);
		
		return true;
		
	}
	
	function getSubmissions($form_id,$startDate,$endDate) {
		
		$form_id = intval($form_id);
		$startDate = $this->db->makeSafe($startDate);
		$endDate = $this->db->makeSafe($endDate);
		
		$sql = "
		SELECT
			fs.`id`,
			fs.`cDate`,
			f.`name` as 'form_name',
			f.`id` as 'form_id'
		FROM
			`forms_submitted` as fs
		LEFT JOIN
			`forms` as f ON fs.`form_id` = f.`id`
		WHERE
			fs.`form_id` = ".$form_id." AND
			fs.`cDate` BETWEEN '".$startDate."' AND '".$endDate."'
		ORDER BY
			fs.`cDate` DESC
		";
		
		$submissions = $this->db->getAll($sql);
		
		if(!empty($submissions)) {
			
			foreach($submissions as &$submission) {
				
				$submission['fields'] = $this->getSubmission($submission['id']);
				
			}
			
		}
		
		return $submissions;
		
	}
	
	function getSubmission($submit_id) {
		
		$sql = "
		SELECT
			f.`name`,
			f.`field_name`,
			f.`form_id`,
			a.`value`
		FROM
			`forms_submitted_fields` as a
		LEFT JOIN
			`forms_fields` as f ON a.`field_id` = f.`id`
		WHERE
			a.`submit_id` = ".intval($submit_id)."
		ORDER BY
			f.`displayOrder`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function saveSubmission($saveData) {
		
		$fields = $saveData['fields'];
		unset($saveData['fields']);
		
		$submit_id = $this->save($saveData,'forms_submitted');
		
		foreach($fields as $field) {
			
			$field['submit_id'] = $submit_id;
			$this->save($field,'forms_submitted_fields');
			
		}
		
		return $submit_id;
		
	}
	
	function deleteSubmission($submit_id) {
		
		$submit_id = intval($submit_id);
		
		// delete values
		$this->db->query("DELETE FROM `forms_submitted_fields` WHERE `submit_id` = ".$submit_id);
		
		// delete submission
		$this->db->delete('forms_submitted',$submit_id);
		
		return true;
		
	}
	
}
