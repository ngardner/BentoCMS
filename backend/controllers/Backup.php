<?php
/**
 * Backup backend controller
 */
class Backup extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objBackup = new BackupModel;
		$currentBackups = $objBackup->getBackups();
		
		$this->view->assign('messages',$this->messages);
		$this->view->assign('currentBackups',$currentBackups);
		$this->view->assign('content', $this->view->fetch('tpl/administration/backup.tpl'));
		$this->finish();
		
	}
	
	function actionCreate($params='') {
		
		if(!empty($params['dosubmit'])) {
			
			$objBackup = new BackupModel;
			
			// backup database
			$created = $objBackup->createBackup();
			
			if($created) {
				
				$this->messages[] = array('type'=>'success','message'=>'Database Backup Completed');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Error during backup!');
				$this->messages[] = array('type'=>'error','message'=>$objBackup->errorMsg);
				
			}
			
		}
		
		$this->actionIndex();
		
	}
	
	function actionDownload($params='') {
		
		if(!empty($params['file'])) {
			
			$objBackup = new BackupModel;
			
			header('Content-type: application/x-tar');
			header('Content-Disposition: attachment; filename='.$params['file']);
			echo file_get_contents($objBackup->backupDir.$params['file']);
			exit();
			
		}
		
	}
	
}

?>
