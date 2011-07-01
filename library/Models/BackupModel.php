<?php

class BackupModel extends Model {
	
	var $errorMsg;
	var $backupDir;
	var $backupURL;
	
	function __construct() {
		
		parent::__construct();
		$this->backupDir = DIR.'backups/';
		$this->backupURL = URL.'/backups/';
		
	}
	
	function createBackup() {
		
		$backupFileName = $this->backupDir.'BACKUP_'.DB_DATABASE.'_'.date('YmhHis').'.gz';
		$backupCommand = "mysqldump --opt -h ".DB_SERVER." -u ".DB_USERNAME." -p".DB_PASSWORD." ".DB_DATABASE." | gzip > ".$backupFileName;
		system($backupCommand);
		
		// lets see if it made it
		if(file_exists($backupFileName) && filesize($backupFileName) > 0) {
			
			return true;
			
		} else {
			
			$this->errorMsg = 'tried: '.$backupCommand;
			return false;
			
		}
		
	}
	
	function getBackups() {
		
		$backups = array();
		$files = scandir($this->backupDir);
		
		if(!empty($files)) {
			
			foreach($files as $file) {
				
				if(is_file($this->backupDir.$file)) {
					
					if(substr($file,-3) == '.gz') {
						
						$backup = array();
						$backup['filepath'] = 'http://'.$this->backupURL;
						$backup['file'] = $file;
						$backup['timestamp'] = date("Y-m-d H:i:s",filemtime($this->backupDir.$file));
						$backup['size'] = round(filesize($this->backupDir.$file)/1024,2).' KB';
						$backups[] = $backup;
						
					}
					
				}
				
			}
			
		}
		
		return $backups;
		
	}
	
}
