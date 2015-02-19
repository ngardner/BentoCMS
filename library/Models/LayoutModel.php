<?php

class LayoutModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveLayout($data) {
		
		return $this->save($data,'layouts');
		
	}
	
	function loadLayout($layout_id='') {
		
		if(empty($layout_id)) {
			
			$layout_id = $this->db->getOne("SELECT `id` FROM `layouts` WHERE `title` = 'default' LIMIT 1");
			
		}
		
		return $this->load($layout_id,'layouts');
		
	}
	
	function loadLayoutByTitle($title) {
		
		$id = $this->db->getOne("SELECT `id` FROM `layouts` WHERE `title` = '".$this->db->makeSafe($title)."'");
		return $this->loadLayout($id);
		
	}
	
	function getLayouts() {
		
		$sql = "
		SELECT
			`id`,
			`title`
		FROM
			`layouts`
		ORDER BY
			`title`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function safeToDelete($layout_id) {
		
		$layout_id = intval($layout_id);
		
		$sql = "
		SELECT
			`id`
		FROM
			`pages`
		WHERE
			`layout_id` = ".$layout_id."
		LIMIT
			1
		";
		
		$stillAssigned = $this->db->getOne($sql);
		
		if($stillAssigned) {
			
			return false;
			
		} else {
			
			return true;
			
		}
		
	}
	
	function deleteLayout($layout_id) {
		
		$layout_id = intval($layout_id);
		$this->db->delete('layouts',$layout_id);
		return true;
		
	}
	
}

?>
