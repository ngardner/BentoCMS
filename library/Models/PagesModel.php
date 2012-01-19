<?php

class PagesModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function clonePage($page_id) {
		
		$pageInfo = $this->load($page_id,'pages');
		unset($pageInfo['keyName']);
		unset($pageInfo['id']);
		unset($pageInfo['url_id']);
		$newPage = $this->savePage($pageInfo);
		
		return $newPage;		
		
	}
	
	function savePage($data) {
		
		$objSettings = Settings::getInstance();
		$urlPrefix = $objSettings->getEntry('cms','page-url');
		$objUrl = new FriendlyurlModel;
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['title'],'pages');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'pages',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'pages');
				
			}
			
		}
		
		//friendly url
		if(empty($data['url'])) {
			
			$prettyUrl = $urlPrefix.$data['keyName'];
			
		} else {
			
			$prettyUrl = $data['url'];
			
		}
		
		$metaData = !empty($data['meta'])?$data['meta']:array();
		unset($data['url']);
		unset($data['meta']);
		
		$id = $this->save($data,'pages');
		$url_id = $objUrl->saveUrl($prettyUrl,'Page','Index',array('page_id'=>$id), $metaData);
		
		if(!empty($url_id)) {
			
			$saveData['id'] = $id;
			$saveData['url_id'] = $url_id;
			$this->save($saveData,'pages');
			
		}
		
		return $id;
		
	}
	
	function loadPage($page_id) {
		
		$objUrl = new FriendlyurlModel;
		$pageInfo = $this->load($page_id,'pages');
		
		if($pageInfo)  {
			
			$pageInfo['url'] = $objUrl->getUrl($pageInfo['url_id']);
			$pageInfo['meta'] = $objUrl->getMetaData($pageInfo['url_id']);
			
		}
		
		return $pageInfo;
		
	}
	
	function getPages($status='ALL') {
		
		$tree=array();
		
		if($status == 'ALL') {
			
			$whereClause = "1=1";
			
		} else {
			
			$whereClause = "p.`status` = '".$this->db->makeSafe($status)."'";
			
		}
		
		$sql = "
		SELECT
			p.`id`,
			p.`title`,
			p.`keyName`,
			p.`parent_id`,
			p.`displayOrder`,
			p.`status`,
			l.`title` as 'template',
			if(p.`type`='link',p.`content`,'') as 'url',
			p.`type`,
			p.`windowaction`
		FROM
			`pages` as p
		LEFT JOIN
			`layouts` as l ON p.`layout_id` = l.`id`
		WHERE
			".$whereClause."
		ORDER BY
			p.`displayOrder`,p.`title`
		";
		
		$resultsTemp = $this->db->getAll($sql);
		
		// the magic tree code
		if(!empty($resultsTemp)) {
			
			foreach($resultsTemp as $result) {
				
				$results[$result['id']] = $result;
				
			}
			
			foreach($results as $id=>&$page) {
				
				if($page['parent_id'] == 0) {
					
					$tree[$id] = &$page;
					
				} else {
					
					if(!isset($results[$page['parent_id']]['children'])) {
						
						$results[$page['parent_id']]['children'] = array();
						
					}
					
					$results[$page['parent_id']]['children'][$id] = &$page;
					
				}
				
			}
			
		}
		
		return $tree;
		
	}
	
	function getPageSidebars($page_id) {
		
		$page_id = intval($page_id);
		$returnData = array();
		
		$sql = "
		SELECT
			*
		FROM
			`sidebars`
		WHERE
			`page_id` = ".$page_id."
		";
		
		$sidebars = $this->db->getAll($sql);
		
		if(!empty($sidebars)) {
			
			foreach($sidebars as $sidebar) {
				
				$returnData[$sidebar['location']] = $sidebar;
				
			}
			
		}
		
		return $returnData;
		
	}
	
	function deletePage($page_id) {
		
		$page_id = intval($page_id);
		$this->db->delete('pages',$page_id);
		return true;
		
	}
	
	function safeToDelete($page_id) {
		
		$page_id = intval($page_id);
		$hasChilden = $this->db->getOne("SELECT `id` FROM `pages` WHERE `parent_id` = ".$page_id);
		
		if($hasChilden) {
			
			return false;
			
		} else {
			
			return true;
			
		}
		
	}
	
	function saveSortOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$page_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('pages',"`id`=".intval($page_id));
			
		}
		
		return true;
		
	}
	
	function getPageId($page_identifier) {
		
		return $this->db->getOne("SELECT `id` FROM `pages` WHERE `keyName` = '".$this->db->makeSafe($page_identifier)."'");
		
	}
	
}
