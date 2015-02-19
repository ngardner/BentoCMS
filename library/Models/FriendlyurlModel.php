<?php

class FriendlyurlModel extends Model {
	
	public $requestController;
	public $requestAction;
	public $requestParams;
	public $url_id;
	
	function __construct() {
		
		parent::__construct();
		$this->requestController='Page';
		$this->requestAction='Index';
		$this->requestParams=array();
		$this->url_id = 0;
		
	}
	
	function saveUrl($url,$controller,$action,$params,$metaData = '') {
		
		//see if its already saved
		$existsId = $this->entryExist($url,$controller,$action,$params);
		
		if($existsId) {
			
			if(!empty($metaData) && is_array($metaData)) {
				$saveData = array();
				$saveData['id'] = $existsId;
				$saveData['title'] = $metaData['title'];
				$saveData['description'] = $metaData['description'];
				$saveData['keywords'] = $metaData['keywords'];
				$this->save($saveData,'friendlyurls');
			}
			
			return $existsId;
			
		} else {
			
			//see if url is unique
			if($this->isUnique($url)) {
				
				$saveData = array();
				$saveData['url'] = $url;
				$saveData['controller'] = $controller;
				$saveData['action'] = $action;
				$saveData['params'] = serialize($params);
				if(!empty($metaData) && is_array($metaData)) {
					$saveData['title'] = $metaData['title'];
					$saveData['description'] = $metaData['description'];
					$saveData['keywords'] = $metaData['keywords'];
				}
				$id = $this->save($saveData,'friendlyurls');
				return $id;
				
			} else {
				
				$url = $url.rand(1,9);
				return $this->saveUrl($url,$controller,$action,$params);
				
			}
			
		}
		
	}
	
	function saveShortUrl($controller,$action,$params) {
		
		$shortUrl = '/'.microtime(true);
		return $this->saveUrl($shortUrl,$controller,$action,$params);
		
	}
	
	function entryExist($url,$controller,$action,$params) {
		
		$sql = "
		SELECT
			`id`
		FROM
			`friendlyurls`
		WHERE
			`url` = '".$this->db->makeSafe($url)."' AND
			`controller` = '".$this->db->makeSafe($controller)."' AND
			`action` = '".$this->db->makeSafe($action)."' AND
			`params` = '".$this->db->makeSafe(serialize($params))."'
		LIMIT
			1
		";
		
		return $this->db->getOne($sql);
		
	}
	
	function isUnique($url) {
		
		$exist = $this->db->getOne("SELECT `id` FROM `friendlyurls` WHERE `url` = '".$this->db->makeSafe($url)."'");
		
		if($exist) {
			
			return false;
			
		} else {
			
			return true;
			
		}
		
	}
	
	function parseRequest($urlString) {
		
		if(empty($urlString)) {
			
			// nothing passed, default to homepage
			$this->requestController='Page';
			$this->requestAction='Index';
			return true;
			
		}
		
		$sql = "
		SELECT
			`id`,
			`controller`,
			`action`,
			`params`
		FROM
			`friendlyurls`
		WHERE
			`url` = '/".$this->db->makeSafe($urlString)."'
		LIMIT
			1
		";
		
		$foundAction = $this->db->getRow($sql);
		
		if(!empty($foundAction)) {
			
			$this->requestController=$foundAction['controller'];
			$this->requestAction=$foundAction['action'];
			$this->url_id = $foundAction['id'];
			@$this->requestParams=unserialize($foundAction['params']);
			
			if(!empty($this->requestParams['_linkback'])) {
				
				$linkback = substr($this->requestParams['_linkback'],1);
				$this->parseRequest($linkback);
				
			}
			
		} else {
			
			// url not found, lets try controller/action logic
			$urlparts = explode('/',$urlString,2);
			$controller = !empty($urlparts[0])?$urlparts[0]:false;
			$action = !empty($urlparts[1])?$urlparts[1]:'Index';
			$this->requestController=$controller;
			$this->requestAction=$action;
			$this->requestParams=array();
			
		}
		
		return true;
		
	}
	
	function getMetaData($id) {
		
		$meta = $this->db->getRow("SELECT `title`, `description`, `keywords` FROM `friendlyurls` WHERE `id` = ".intval($id));
		return $meta;
		
	}
	
	function getUrl($id) {
		
		$url = $this->db->getOne("SELECT `url` FROM `friendlyurls` WHERE `id` = ".intval($id));
		return $url;
		
	}
	
	function findUrl($table,$keyName) {
		
		$sql = "
		SELECT
			`url_id`
		FROM
			`".$this->db->makeSafe($table)."`
		WHERE
			`keyName` = '".$this->db->makeSafe($keyName)."'
		LIMIT
			1
		";
		
		$url_id = $this->db->getOne($sql);
		
		return $this->getUrl($url_id);
		
	}
	
}

?>
