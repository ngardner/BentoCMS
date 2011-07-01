<?php

class PermissionsModel extends Model {
	
	public $errorMsg;
	
	function __construct() {
		
		parent::__construct();
		$this->objAuthentication = Authentication::getInstance();
		
	}
	
	function permissionExist($controller,$action) {
		
		$permissionId = $this->getPermissionId($controller,$action);
		
		if($permissionId) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function hasPermission($controller,$action,$user_id) {
		
		$permissionId = $this->getPermissionId($controller,$action);
		
		if($permissionId) {
			
			$hasPermission = $this->db->getOne("SELECT `id` FROM `users_permissions` WHERE `user_id` = ".intval($user_id)." AND `permission_id` = ".intval($permissionId));
			
			if($hasPermission) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getAllPermissions() {
		
		$sql = "SELECT * FROM `permissions`";
		$results = $this->db->getAll($sql);
		
		return $results;
		
	}
	
	function findNewPermissions() {
		
		$newPermissions = array();
		$possiblePermissions = array();
		$classNames = array();
		$controllerDir = DIR.'backend/controllers/';
		
		$fileList = scandir($controllerDir);
		
		if(!empty($fileList)) {
			
			foreach($fileList as $file) {
				
				if(is_file($controllerDir.$file)) {
					
					$classNames[] = substr($file,0,-4);
					
				}
				
			}
			
		}
		
		if(!empty($classNames)) {
			
			foreach($classNames as $className) {
				
				$methodList = get_class_methods($className);
				
				if(!empty($methodList)) {
					
					foreach($methodList as $methodName) {
						
						if(substr($methodName,0,6) == 'action') {
							
							$methodName = substr($methodName,6);
							$possiblePermissions[] = array('controller'=>$className,'action'=>$methodName);
							
						}
						
					}
					
				}
				
			}
			
		}
		
		if(!empty($possiblePermissions)) {
			
			$currentPermissions = $this->getAllPermissions();
			
			if(!empty($currentPermissions)) {
				
				foreach($possiblePermissions as $id=>$possiblePermission) {
					
					$alreadyExists = false;
					
					foreach($currentPermissions as $permission) {
						
						if(strtolower($permission['controller']) == strtolower($possiblePermission['controller']) && strtolower($permission['action']) == strtolower($possiblePermission['action'])) {
							
							$alreadyExists = true;
							break;
							
						}
						
					}
					
					if(!$alreadyExists) {
						
						$newPermissions[] = $possiblePermission;
						
					}
					
				}
				
			}
			
		}
		
		return $newPermissions;
		
	}
	
	function getUserPermissions($user_id) {
		
		if(!empty($user_id)) {
			
			$sql = "
			SELECT
				permissions.`id`,
				permissions.`controller`,
				permissions.`action`
			FROM
				`users_permissions`
			INNER JOIN
				`permissions` ON users_permissions.`permission_id` = permissions.`id`
			WHERE
				`user_id` = ".intval($user_id)."
			";
			
			$result = $this->db->getAll($sql);
			
			return $result;
			
		}
		
	}
	
	function getUserPermissionTable($user_id) {
		
		$sql = "
		SELECT
			p.`id`,
			p.`name`,
			p.`description`,
			if(u.`id`,true,false) as 'hasPermission'
		FROM
			`permissions` as p
		LEFT JOIN
			`users_permissions` as u ON p.`id` = u.`permission_id` AND u.`user_id` = ".intval($user_id)."
		ORDER BY
			p.`controller`,p.`name`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function getPermissionId($controller,$action) {
		
		$sql = "SELECT `id` FROM `permissions` WHERE `controller` = '".$this->db->makeSafe($controller)."' AND `action` = '".$this->db->makeSafe($action)."'";
		
		$id = $this->db->getOne($sql);
		
		if($id) {
			
			return $id;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function grantAllPermissions($user_id) {
		
		$permissions = $this->getAllPermissions();
		
		foreach($permissions as $permission) {
			
			$this->grantPermission($user_id,$permission);
			
		}
		
		return true;
		
	}
	
	function grantPermission($user_id, $permission_id) {
		
		if($permission_id) {
			
			$exists = $this->db->getOne("SELECT `id` FROM `users_permissions` WHERE `user_id` = ".intval($user_id)." AND `permission_id` = ".intval($permission_id));
			
			if(!$exists) {
				
				$this->db->reset();
				$this->db->assign('user_id', intval($user_id));
				$this->db->assign('permission_id', intval($permission_id));
				$this->db->insert('users_permissions');
				
			}
			
			return true;
			
		} else {
			
			return false;
			
		}
		
		
	}
	
	function removeAllPermissions($user_id) {
		
		$sql = "DELETE FROM `users_permissions` WHERE `user_id` = ".intval($user_id);
		$this->db->query($sql);
		
	}
	
	function removePermission($user_id, $permission) {
		
		$permissionId = $this->getPermissionId($permission['controller'],$permission['action']);
		
		$sql = "DELETE FROM `users_permissions` WHERE `user_id` = ".intval($user_id)." AND `permission_id` = ".intval($permission);
		$this->db->query($sql);
		
	}
	
	function createPermission($data) {
		
		$this->save($data,'permissions');
		return true;
		
	}
	
}

?>
