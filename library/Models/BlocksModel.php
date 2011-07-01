<?php

class BlocksModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveBlock($data) {
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['title'],'blocks');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blocks',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blocks');
				
			}
			
		}
		
		return $this->save($data,'blocks');
		
	}
	
	function loadBlock($block_id) {
		
		return $this->load($block_id,'blocks');
		
	}
	
	function cloneBlock($block_id) {
		
		$blockInfo = $this->load($block_id,'blocks');
		unset($blockInfo['keyName']);
		unset($blockInfo['id']);
		$newBlock = $this->saveBlock($blockInfo);
		return $newBlock;
		
	}
	
	function getBlocks() {
		
		$sql = "
		SELECT
			`id`,
			`title`,
			`keyName`,
			`code`
		FROM
			`blocks`
		ORDER BY
			`title`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function deleteBlock($block_id) {
		
		$block_id = intval($block_id);
		$this->db->delete('blocks',$block_id);
		return true;
		
	}
	
	function getBlockId($keyName) {
		
		return $this->db->getOne("SELECT `id` FROM `blocks` WHERE `keyName` = '".$this->db->makeSafe($keyName)."'");
		
	}
	
}
