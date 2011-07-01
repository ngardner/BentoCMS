<?php
/**
 * Administration backend controller
 */
class Administration extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		
		
	}
	
	function actionCreatePermissions($params='') {
		
		$objPermissions = new PermissionsModel;
		
		if(!empty($params['dosubmit'])) {
			
			if(!empty($params['permission']) && is_array($params['permission'])) {
				
				foreach($params['permission'] as $newPermission) {
					
					if(!empty($newPermission['enable'])) {
						
						if(!empty($newPermission['name']) && !empty($newPermission['description'])) {
							
							unset($newPermission['enable']);
							$objPermissions->createPermission($newPermission);
							
						}
						
					}
					
				}
				
			}
			
		}
		
		$possiblePermissions = $objPermissions->findNewPermissions();
		
		$this->view->assign('messages',$this->messages);
		$this->view->assign('possiblePermissions',$possiblePermissions);
		$this->view->assign('content', $this->view->fetch('tpl/administration/createpermissions.tpl'));
		$this->finish();
		
	}
	
}

?>
