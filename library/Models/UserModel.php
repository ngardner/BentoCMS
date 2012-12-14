<?php

class UserModel extends Model {
	
	protected $user_id;
	public $errorMsg;
	
	function __construct($user_id = '') {
		
		parent::__construct();
		
		$this->permissions = new PermissionsModel;
		$this->objAuthentication = Authentication::getInstance();
		
		if(!empty($user_id)) {
			
			$this->setUserId($user_id);
			
		}
		
	}
	
	function getUsers($type='all') {
		
		$whereClause = "1=1";
		
		if($type != 'all') {
			
			$whereClause .= " AND `type` = '".$this->db->makeSafe($type)."'";
			
		}
		
		$sql = "
		SELECT
			*
		FROM
			`users`
		WHERE
			".$whereClause."
		ORDER BY
			`lName`,`fName`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function getRecentLogins($withAdmins=false) {
		
		if($withAdmins) {
			
			$whereClause = '1=1';
			
		} else {
			
			$whereClause = 'type != "admin"';
			
		}
		
		$sql = "
		SELECT
			*
		FROM
			`users`
		WHERE
			".$whereClause."
		ORDER BY
			`lastLogin` DESC
		LIMIT
			5
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function activateUser($email,$code) {
		
		$sql = "
		SELECT
			`id`
		FROM
			`users`
		WHERE
			`email` = '".$this->db->makeSafe($email)."' AND
			`activateString` = '".$this->db->makeSafe($code)."'
		LIMIT
			1
		";
		
		$found_user = $this->db->getOne($sql);
		
		if($found_user) {
			
			$this->db->reset();
			$this->db->assign('active',1);
			$this->db->update('users',"`id` = ".$found_user);
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function saveUser($data) {
		
		if(isset($data['permissions'])) {
			
			$setPermissions = true;
			$permissions = $data['permissions'];
			unset($data['permissions']);
			
		} else {
			
			$setPermissions = false;
			
		}
		
		if(!empty($data['password'])) {
			
			$data['password'] = $this->encryptPassword($data['password']);
			
		} else {
			
			unset($data['password']);
			
		}
		
		// make sure email is unique
		if(!empty($data['email'])) {
			
			$emailUsedBy = $this->emailUsed($data['email']);
			$data['id'] = !empty($data['id'])?intval($data['id']):0;
			
			if($emailUsedBy != false && $emailUsedBy != $data['id']) {
				
				$this->errorMsg = 'Email already in use. '.$emailUsedBy.' - '.$data['id'];
				return false;
				
			}
			
		}
		
		//save user
		$this->user_id = $this->save($data,'users');
		
		if($setPermissions) {
			
			//remove permissions
			$this->permissions->removeAllPermissions($this->user_id);
			
			if($this->user_id && !empty($permissions) && $data['type'] == 'admin') {
				
				//save permissions
				foreach($permissions as $permission_id) {
					
					$this->permissions->grantPermission($this->user_id,$permission_id);
					
				}
				
			}
			
		}
		
		return $this->user_id;
		
	}
	
	function emailUsed($email) {
		
		$sql = "
		SELECT
			`id`
		FROM
			`users`
		WHERE
			`email` = '".$this->db->makeSafe($email)."'
		LIMIT
			1
		";
		
		$exists = $this->db->getOne($sql);
		
		if($exists) {
			
			return $exists;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function forgotPassword($email) {
		
		$updated = false;
		
		$sql = "
		SELECT
			*
		FROM
			`users`
		WHERE
			`email` = '".$this->db->makeSafe($email)."'
		";
		
		$userInfo = $this->db->getRow($sql);
		
		if(!empty($userInfo['id'])) {
			
			$userInfo['newPassword'] = $this->generatePassword(6);
			
			$this->db->reset();
			$this->db->assign_str('password',$this->encryptPassword($userInfo['newPassword']));
			$updated = $this->db->update('users',"`id` = ".$userInfo['id']);
			
		}
		
		if($updated) {
			
			return $userInfo;
			
		} else {
			
			return false;
			
		}
		
	}
	
	private function generatePassword($length=6) {
		
		if(intval($length) < 6) {
			
			$length = 6;
			
		}
		
		$newPassword = '';
		$availableChars = range('a','z');
		$availableNumbs = range(0,9);
		$chars = array_merge($availableChars,$availableNumbs);
		$i=0;
		
		while($i<$length) {
			
			$newPassword .= $chars[rand(0,sizeof($chars))];
			$i++;
			
		}
		
		return $newPassword;
		
	}
	
	function getInfo() {
		
		$sql = "
		SELECT
			*
		FROM
			`users`
		WHERE
			`id` = ".intval($this->user_id)."
		";
		
		$userInfo = $this->db->getRow($sql);
		$userInfo['permissions'] = $this->permissions->getUserPermissions($this->user_id);
		unset($userInfo['password']);
		
		return $userInfo;
		
	}
	
	private function encryptPassword($password) {
		
		$password = md5($password);
		return $password;
		
	}
	
	function user_id() {
		
		return $this->user_id;
		
	}
	
	function setUserId($user_id) {
		
		$this->user_id = $user_id;
		
	}
	
	function testPassword($user_id,$password) {
		
		$sql = "SELECT `id` FROM `users` WHERE `id` = ".intval($user_id)." AND `password` = '" . $this->db->makeSafe($this->encryptPassword($password))."' LIMIT 1";
		
		$is_correct = $this->db->getOne($sql);
		
		if($is_correct == $user_id) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function deleteUser($user_id) {
		
		$user_id = intval($user_id);
		
		$this->db->delete('users',$user_id);
		
		$permission_ids = $this->db->getCol("SELECT `id` FROM `users_permissions` WHERE `user_id` = ".$user_id);
		
		if(!empty($permission_ids)) {
			
			foreach($permission_ids as $id) {
				
				$this->db->delete('users_permissions',$id);
				
			}
			
		}
		
		return true;
		
	}
	
	function findUser($searchString) {
		
		$sql = "
		SELECT
			u.`id`,
			u.`fName`,
			u.`lName`,
			u.`company`,
			u.`title`,
			u.`email`,
			u.`phone`
		FROM
			`users` as u
		WHERE
			concat(u.`fName`,' ',u.`lName`,' ',u.`company`,' ',u.`email`) LIKE '%".$this->db->makeSafe($searchString)."%'
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function isCSuser() {
		
		$sql = "
		SELECT
			`id`
		FROM
			`shop_user_subscriptions`
		WHERE
			`user_id` = ".$this->user_id."
		LIMIT
			1
		";
		
		$result = $this->db->getOne($sql);
		
		if(!empty($result)) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function isPrimaryCSuser() {
		
		$sql = "
		SELECT
			`is_primary`
		FROM
			`shop_user_subscriptions`
		WHERE
			`user_id` = ".$this->user_id."
		LIMIT
			1
		";
		
		$result = $this->db->getOne($sql);
		
		if(!empty($result) && $result['is_primary'] == 1) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
}


?>
