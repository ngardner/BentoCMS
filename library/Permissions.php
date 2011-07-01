<?php

class Permissions {
	
	private static $instance;
	private $db;
	
	function __construct() {
		
		
		
	}

	function getInstance() {
		
		if (!isset(self::$instance)) {
			
			$c = __CLASS__;
			self::$instance = new $c;
			
		}
		
		return self::$instance;
		
	}
	
	function actionAllowed($controller,$action,$user_id=false) {
		
		$objPermissionModel = new PermissionsModel;
		
		if($objPermissionModel->permissionExist($controller,$action)) {
			
			$allowed = $objPermissionModel->hasPermission($controller,$action,$user_id);
			
			if($allowed) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		} else {
			
			// if permission dosnt exist - its allowed by default!
			return true;
			
		}
		
	}
	
}

?>
