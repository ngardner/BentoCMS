<?php

class SearchModel extends Model {
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function performSearch($query,$options='') {
		
		// log it
		if(empty($options['dontsave'])) {
			$this->saveSearch($query);
		} else {
			unset($options['dontsave']);
		}
		
		// parse it
		$searchKeywords = $this->getKeywords($query);
		
		// search it
		if($searchKeywords) {
			
			$whereClause = '(';
			$pointSelect = '';
			$orderBy = 'SUM(';
			$counter = 1;
			
			foreach($searchKeywords as $keyword) {
				
				$whereClause .= "f.`title` LIKE '%".$this->db->makeSafe($keyword)."%' OR ";
				$whereClause .= "f.`keywords` LIKE '%".$this->db->makeSafe($keyword)."%' OR ";
				
				$pointSelect .= "if(f.`title` LIKE '%".$this->db->makeSafe($keyword)."%','5','0') as 'title_points".$counter."', ";
				$pointSelect .= "if(f.`keywords` LIKE '%".$this->db->makeSafe($keyword)."%','2','0') as 'keyword_points".$counter."', ";
				
				$orderBy .= '`title_points'.$counter.'` + `keyword_points'.$counter.'` + ';
				
				$counter++;
				
			}
			
			$whereClause = substr($whereClause,0,-4).') ';
			$pointSelect = substr($pointSelect,0,-2);
			$orderBy = substr($orderBy,0,-3).') DESC' ;
			
			if(!empty($options)) {
				
				$whereClause .= ' AND (';
				
				foreach($options as $option) {
					
					switch($option['type']) {
						
						case 'type':
							
							$whereClause .= "concat(f.`controller`,'-',f.`action`) = '".$this->db->makeSafe($option['value'])."' OR ";
							
						break;
						
					}
					
				}
				
				$whereClause = substr($whereClause,0,-4).') ';
				
			}
			
			$sql = "
			SELECT
				f.`title`,
				f.`description`,
				f.`keywords`,
				f.`url`,
				concat(f.`controller`,'-',f.`action`) as 'type',
				".$pointSelect."
			FROM
				`friendlyurls` as f
			WHERE
				".$whereClause."
			GROUP BY
				f.`id`
			ORDER BY
				".$orderBy."
			-- LIMIT
			--	20
			";
			
			$results = $this->db->getAll($sql);
			
			if(!empty($results)) {
				
				foreach($results as &$result) {
					
					switch($result['type']) {
						
						case 'Blog-Article': $result['type'] = 'Blog Article'; break;
						case 'Blog-Category': $result['type'] = 'Blog'; break;
						case 'Page-Index': $result['type'] = 'Webpage'; break;
						default: $result['type'] = 'Unknown'; break;
						
					}
					
				}
				
			}
			
			return $results;
			
		} else {
			
			return false;
			
		}
		
		#########
		#########
		#########
		
		return false;
		
	}
	
	function getPopular($options) {
		
		$startDate = !empty($options['startDate'])?$options['startDate']:date("Y-m-d",strtotime('-1 Month'));
		$endDate = !empty($options['endDate'])?$options['endDate']:date("Y-m-d");
		$howMany = !empty($options['howMany'])?$options['howMany']:20;
		
		$sql = "
		SELECT
			`searchQuery`,
			count(`id`) as 'count'
		FROM
			`searches`
		WHERE
			`cdate` BETWEEN '".$this->db->makeSafe($startDate)."' AND '".$this->db->makeSafe($endDate)."'
		GROUP BY
			`searchQuery`
		ORDER BY
			count(`id`) DESC
		LIMIT
			".intval($howMany)."
		";
		
		return $this->db->getAll($sql);
		
	}
	
	private function saveSearch($query) {
		
		$objAuth = Authentication::getInstance();
		
		if($objAuth->loggedIn()) {
			
			$user_id = $objAuth->user_id;
			
		} else {
			
			$user_id = 0;
			
		}
		
		$this->db->reset();
		$this->db->assign_str('searchQuery',$query);
		$this->db->assign('user_id',$user_id);
		$this->db->insert('searches');
		
		return true;
		
	}
	
	private function getKeywords($query) {
		
		$keywords = array();
		
		//group words together if quote enclosed
		$keywordsTemp = str_getcsv($query,' ','"');
		
		// remove common words
		if(!empty($keywordsTemp)) {
			
			foreach($keywordsTemp as &$testKeyword) {
				
				$testKeyword = trim(strtolower($testKeyword));
				
				if($this->keywordToCommon($testKeyword)) {
					
					// not going to use it
					
				} else {
					
					$keywords[] = $testKeyword;
					
				}
				
			}
			
		}
		
		if(!empty($keywords)) {
			
			return $keywords;
			
		} else {
			
			return false;
			
		}
		
	}
	
	private function keywordToCommon($keyword) {
		
		// cheap logic, might need to enhance in future
		
		if(strlen($keyword) <= 2) {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
}
