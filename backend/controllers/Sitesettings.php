<?php
/**
 * Sitesettings backend controller
 */
class Sitesettings extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objSettings = Settings::getInstance();
		
		if(!empty($params['dosave'])) {
			
			if(!empty($params['sitesettings'])) {
				
				foreach($params['sitesettings'] as $setting_id=>$setting_value) {
					
					$objSettings->setSetting($setting_id,$setting_value);
					
				}
				
				$this->messages[] = array('type'=>'success','message'=>'Site settings have been saved.');
				
			}
			
		}
		
		$allSettings = $objSettings->getSettings();
		$this->view->assign('siteSettings',$allSettings);
		$this->view->assign('messages',$this->messages);
		$this->view->assign('content',$this->view->fetch('tpl/administration/sitesettings.tpl'));
		$this->finish();
		
	}
	
}

?>
