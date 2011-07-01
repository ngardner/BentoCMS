<?php

class Validator {
	
	private $errorMsg;
	public $hasError;
	
	function reset() {
		
		$this->hasError = false;
		$this->errorMsg = false;
		
	}
	
	private function setError($msg) {
		
		$this->hasError = true;
		$this->errorMsg[] = $msg;
		
	}
	
	function getError() {
		
		return $this->errorMsg;
		
	}
	
	function validateEmail($emailAddress,$field='') {
		
		if(empty($field)) {
			$field = 'email address';
		}
		
		if(preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/", $emailAddress)) {
			
			return true;
			
		} else {
			
			$this->setError('Invalid'.$field);
			return false;
			
		}
		
	}
	
	function validatePhone($phoneNumber,$fieldname='phone number') {
		
		$phoneNumber = str_replace('-', '', $phoneNumber);
		if(is_numeric($phoneNumber)) {
			
			return true;
			
		} else {
			
			$this->setError('Invalid '.$fieldname);
			return false;
			
		}
		
	}
	
	function validateCheckbox($value) {
		
		if($value == 'on') {
			
			return true;
			
		} else {
			
			$this->setError('Checkbox must be checked.');
			return false;
			
		}
		
	}
	
	function validateName($name,$fieldname='name') {
		
		if(!empty($name)) {
			
			return true;
			
		} else {
			
			$this->setError('Invalid '.$fieldname);
			return false;
			
		}
		
	}
	
	function validatePassword($password) {
		
		if(strlen($password) >= 6) {
			
			return true;
			
		} else {
			
			$this->setError('Invalid password, must be 6 characters');
			return false;
			
		}
		
	}
	
	function validateNotEmpty($data='',$field='') {
		
		if(empty($field)) {
			$field = 'Field';
		}
		
		if(!empty($data)) {
			
			return true;
			
		} else {
			
			$this->setError($field.' cannot be empty');
			return false;
			
		}
		
	}
	
	function validateCardNumb($str='',$field='') {
		
		if (strspn($str, "0123456789") != strlen($str)) {
			$this->setError($field.' is invalid.');
			return false; // non-digit found
		}
		$map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
		$sum = 0;
		$last = strlen($str) - 1;
		for ($i = 0; $i <= $last; $i++) {
			$sum += $map[$str[$last - $i] + ($i & 1) * 10];
		}
		
		if($sum % 10 == 0) {
			
			return true;
			
		} else {
			
			$this->setError($field.' is invalid.');
			return false; // non-digit found
			
		}
		
	}
	
	function passwordsMatch($password1,$password2) {
		
		if($password1 == $password2) {
			
			return true;
			
		} else {
			
			$this->setError('Passwords do not match.');
			return false;
			
		}
		
	}
	
}

?>
