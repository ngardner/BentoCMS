<?php

class Settings {
	
	private static $instance;
	private $db;
	private $settings;
	
	function __construct() {
		
		$this->db = Database::getInstance();
		
	}
	
	static function getInstance() {
		
		if (!isset(self::$instance)) {
			
			$c = __CLASS__;
			self::$instance = new $c;
			self::$instance->loadSettings();
			
		}
		
		return self::$instance;
		
	}
	
	function setEntry($group,$name,$value) {
		
		if(empty($this->settings[$group])) {
			
			$this->settings[$group] = array();
			
		}
		
		$this->settings[$group][$name] = $value;
		
	}
	
	function getEntry($group,$name) {
		
		if(isset($this->settings[$group][$name])) {
			
			return $this->settings[$group][$name];
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getEntrys() {
		
		return $this->settings;
		
	}
	
	function getSettings() {
		
		$sql = "
		SELECT
			`id`,
			`name`,
			`description`,
			`key`,
			`value`,
			`group`,
			`values`
		FROM
			`site_settings`
		ORDER BY
			`group`,`name`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function loadSettings() {
		
		$sql = "
		SELECT
			s.`key`,
			s.`value`,
			s.`group`
		FROM
			`site_settings` as s
		";
		
		$records = $this->db->getAll($sql);
		
		if(is_array($records)) {
			
			foreach($records as $record) {
				
				$this->setEntry($record['group'],$record['key'],$record['value']);
				
			}
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function setSetting($id,$value) {
		
		$this->db->reset();
		$this->db->assign_str('value',$value);
		$this->db->update('site_settings',"`id` = ".intval($id));
		return true;
		
	}
	
}

?>
