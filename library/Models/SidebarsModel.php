<?php

class SidebarsModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveSidebar($data) {
		
		if(empty($data['id'])) {
			
			// try to find based on page and location
			$sidebar_id = $this->db->getOne("SELECT `id` FROM `sidebars` WHERE `page_id` = ".intval($data['page_id'])." and `location` = '".$this->db->makeSafe($data['location'])."'");
			
			if($sidebar_id) {
				
				$data['id'] = $sidebar_id;
				
			}
			
		}
		
		return $this->save($data,'sidebars');
		
	}
	
	function loadSidebar($sidebar_id) {
		
		return $this->load($sidebar_id,'sidebars');
		
	}
	
	function deletePageSidebars($page_id) {
		
		$page_id = intval($page_id);
		
		$sidebar_ids = $this->db->getCol("SELECT `id` FROM `sidebars` WHERE `page_id` = ".$page_id);
		
		if(!empty($sidebar_ids)) {
			
			foreach($sidebar_ids as $id) {
				
				$this->db->delete('sidebars',$id);
				
			}
			
		}
		
		return true;
		
	}
	
}