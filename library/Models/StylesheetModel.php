<?php

class StylesheetModel extends Model {
	
	var $stylesheetDir;
	
	function __construct() {
		
		parent::__construct();
		$this->stylesheetDir = DIR.'bento/css/';
		
	}
	
	function saveStylesheet($data) {
		
		$filename = preg_replace('/\W/','',$data['filename']);
		
		$fp = fopen($this->stylesheetDir.$filename.'.css','w+');
		
		if($fp) {
			
			fwrite($fp,$data['content']);
			fclose($fp);
			return $filename;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function loadStylesheet($filename) {
		
		$filename = preg_replace('/\W/','',$filename);
		
		if(file_exists($this->stylesheetDir.$filename.'.css')) {
			
			$contents = file_get_contents($this->stylesheetDir.$filename.'.css');
			
			if($contents) {
				
				return $contents;
				
			} else {
				
				return false;
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getStylesheets() {
		
		$stylesheets = array();
		$files = scandir($this->stylesheetDir);
		
		if(!empty($files)) {
			
			foreach($files as $file) {
				
				if(is_file($this->stylesheetDir.$file)) {
					
					if(substr($file,-4) == '.css') {
						
						$filename = substr($file,0,-4);
						$stylesheets[] = $filename;
						
					}
					
				}
				
			}
			
		}
		
		return $stylesheets;
		
	}
	
	function deleteStylesheet($filename) {
		
		$filename = preg_replace('/\W/','',$filename);
		
		if(file_exists($this->stylesheetDir.$filename.'.css')) {
			
			if(unlink($this->stylesheetDir.$filename.'.css')) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		}
		
	}
	
}
