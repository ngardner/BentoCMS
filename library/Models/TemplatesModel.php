<?php

class TemplatesModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveTemplate($data) {
		
		return $this->save($data,'templates');
		
	}
	
	function loadTemplate($id) {
		
		return $this->load($id,'templates');
		
	}
	
	function loadTemplateFromKeyname($keyname) {
		
		$id = $this->db->getOne("SELECT `id` FROM `templates` WHERE `keyName` = '".$this->db->makeSafe($keyname)."'");
		return $this->loadTemplate($id);
		
	}
	
	function getTemplates($group) {
		
		$sql = "
		SELECT
			*
		FROM
			`templates`
		WHERE
			`group` = '".$this->db->makeSafe($group)."'
		ORDER BY
			`name`
		";
		
		return $this->db->getAll($sql);
		
	}
	
}
