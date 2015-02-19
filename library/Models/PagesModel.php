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
	
	/**
	 * Returns the keyName of the pages oldest (root) parent
	 */
	function getPageRootKeyname($page_id) {
		
		$page_id = intval($page_id);
		
		while(($parent_id = $this->db->getOne("SELECT `parent_id` FROM `pages` WHERE `id` = ".$page_id)) != 0) {
			$page_id = $parent_id;
		}
		
		$rootsKeyname = $this->db->getOne("SELECT `keyName` FROM `pages` WHERE `id` = ".$page_id);
		
		return $rootsKeyname;
		
	}

	//begin homepage functions

	function loadHomepage() {
		
		$homepageInfo = array();

		// get first mini-profile slide
		$mp_slide = "
		SELECT
			*
		FROM
			`homepage_info`
		WHERE
			`key` = 'mp_slide'
		";
		$homepageInfo['mp_slide'] = $this->db->getAll($mp_slide);

		// get mini-profile welcome message
		$mp_welcome = "
		SELECT
			*
		FROM
			`homepage_info`
		WHERE
			`key` = 'mp_welcome'
		";
		$homepageInfo['mp_welcome'] = $this->db->getAll($mp_welcome);
		
		// get other slides
		$slides = "
		SELECT
			*
		FROM
			`homepage_slides`
		ORDER BY
			`displayOrder`
		";
		$homepageSlides = $this->db->getAll($slides);
		
		foreach($homepageSlides as $homepageSlide) {
			$homepageInfo['slides'][] = $homepageSlide;
		}
		// get tagline
		$tag = "
		SELECT
			`value`
		FROM
			`homepage_info`
		WHERE
			`key` = 'tag'
		";
		$homepageInfo['tagline'] = $this->db->getAll($tag);

		// get icons
		$icons = "
		SELECT
			*
		FROM
			`homepage_icons`
		ORDER BY
			`displayOrder`
		";
		$homepageIcons = $this->db->getAll($icons);
		foreach($homepageIcons as $icon){
			$homepageInfo['icons'][] = $icon;
		}

		// get features
		$features = "
		SELECT
			*
		FROM
			`homepage_features`
		ORDER BY
			`displayOrder`
		";
		$homepageFeatures = $this->db->getAll($features);
		foreach($homepageFeatures as $feature){
			$homepageInfo['features'][] = $feature;
		}
			
		return $homepageInfo;
		
	}
	/**
	 * This does not upload (see Files->upload) it saves the results to DB
	 */
	function saveHomepageSlide($slide) {
		
		return $this->save($slide,'homepage_slides');
		
	}

	/**
	 * This does not upload (see Files->upload) it saves the results to DB
	 */
	function updateSlides($slide) {
		
		return $this->save($slide,'homepage_slides');
		
	}
	
	/**
	 * This does not upload (see Files->upload) it saves the results to DB
	 */
	function saveHomepageBlock($block) {
		
		return $this->save($block,'homepage_blocks');
		
	}

	function saveHomeInfo($info) {
		
		return $this->save($info, 'homepage_info');
		
	}

	/**
	 * This does not upload (see Files->upload) it saves the results to DB
	 */
	function saveHomepageIcon($icons) {
		
		return $this->save($icons, 'homepage_icons');
		
	}

	/**
	 * This does not upload (see Files->upload) it saves the results to DB
	 */
	function saveHomepageFeature($features) {

		return $this->save($features, 'homepage_features');
		
	}
	
	function saveHomepageBlockSortOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$block_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('homepage_blocks',"`id`=".intval($block_id));
			
		}
		
		return true;
		
	}
	
	function deleteHomepageBlock($id) {
		
		$id = intval($id);
		
		// remove from filesystem
		$blockInfo = $this->load($id,'homepage_blocks');
		
		if($blockInfo) {
			
			@unlink(DIR.$blockInfo['image']);
			
			// remove from db
			$this->db->delete('homepage_blocks',$id);
			
		}
		
		return true;
		
	}

	function saveHomepageIconSortOrder($sortOrder){

		foreach($sortOrder as $order=>$slide_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('homepage_icons',"`id`=".intval($slide_id));
			
		}
		
		return true;

	}

	function saveHomepageFeatureSortOrder($sortOrder){

		foreach($sortOrder as $order=>$slide_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('homepage_features',"`id`=".intval($slide_id));
			
		}
		
		return true;

	}
	
	function saveHomepageSlideSortOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$slide_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('homepage_slides',"`id`=".intval($slide_id));
			
		}
		
		return true;
		
	}
	
	function deleteHomepageSlide($id) {
		
		$id = intval($id);
		
		// remove from filesystem
		$slideInfo = $this->load($id,'homepage_slides');
		
		if($slideInfo) {
			
			@unlink(DIR.$slideInfo['image']);
			
			// remove from db
			$this->db->delete('homepage_slides',$id);
			
		}
		
		return true;
		
	}

	function deleteMiniprofileSlide($id) {
		
		$id = intval($id);
		
		// remove from filesystem
		$slideInfo = $this->load($id,'homepage_info');
		
		if($slideInfo) {
			
			@unlink(DIR.$slideInfo['value']);
			
			// remove from db
			$this->db->delete('homepage_info',$id);
			
		}
		
		return true;
		
	}

	function deleteHomepageIcon($id) {
		
		$id = intval($id);
		
		// remove from filesystem
		$iconInfo = $this->load($id,'homepage_icons');
		
		if($iconInfo) {
			
			@unlink(DIR.$iconInfo['image']);
			
			// remove from db
			$this->db->delete('homepage_icons',$id);
			
		}
		
		return true;
		
	}

	function deleteHomepageFeature($id) {
		
		$id = intval($id);
		
		// remove from filesystem
		$featureInfo = $this->load($id,'homepage_features');
		
		if($featureInfo) {
			
			@unlink(DIR.$featureInfo['image']);
			
			// remove from db
			$this->db->delete('homepage_features',$id);
			
		}
		
		return true;
		
	}

	
}
