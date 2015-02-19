<?php

class Model {
	
	protected $db;
	protected $log;
	protected $datasource;
	
	function __construct() {
		
		$this->db = Database::getInstance();
		
	}
	
	function save($data,$table) {
		
		if(is_array($data)) {
			
			$this->db->reset();
			
			foreach($data as $field=>$value) {
				
				$this->db->assign_str($field,$value);
				
			}
			
			if(!empty($data['id'])) {
				
				$id = intval($data['id']);
				$this->db->update($table,"`id` = ".$id);
				
			} else {
				
				$id = $this->db->insert($table);
				
			}
			
			return $id;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function load($id,$table) {
		
		$id = intval($id);
		
		$sql = "
		SELECT
			*
		FROM
			`".$table."`
		WHERE
			`id` = ".$id."
		LIMIT
			1
		";
		
		return $this->db->getRow($sql);
		
	}
	
	function loadAll($table,$whereClauseCustom='') {
		
		$table = $this->db->makeSafe($table);
		
		$whereClause = ($whereClauseCustom)?$whereClauseCustom:"1 = 1";
		
		$sql = "
		SELECT
			*
		FROM
			`".$table."`
		WHERE
			".$whereClause."
		";
		
		return $this->db->getAll($sql);
		
		
	}
	
	function delete($id,$table) {
		
		return $this->db->delete($table,$id);
		
	}
	
	function generateKeyName($name,$table,$current_page_id=false) {
		
		$keyName = trim(strtolower(preg_replace('/\W/','-',$name)));
		$current_page_id = intval($current_page_id);
		$whereClause = "`keyName` = '".$keyName."'";
		
		if($current_page_id) {
			
			$whereClause .= " AND `id` != ".$current_page_id;
			
		}
		
		// make sure its unique
		$sql = "SELECT `keyName` FROM `".$table."` WHERE ".$whereClause." LIMIT 1";
		$exists = $this->db->getOne($sql);
		
		if($exists) {
			
			$newName = $name.rand(1,9);
			return $this->generateKeyName($newName,$table);
			
		} else {
			
			return $keyName;
			
		}
		
	}
	
	function isFileSafe($file) {
		
		$allowed_extensions = array(
			'doc',
			'xls',
			'pdf',
			'txt',
			'jpg',
			'jpeg',
			'gif',
			'png',
			'zip',
			'rtf',
			'html'
		);
		
		$file_extension = substr($file,strrpos($file,'.')+1);
		
		if(in_array($file_extension,$allowed_extensions)) {
			
			return true;
			
		} else {
			
			throw new Exception('File extension is not allowed to be uploaded! ( '.$file_extension.')');
			
		}
		
	}
	
}

?>
