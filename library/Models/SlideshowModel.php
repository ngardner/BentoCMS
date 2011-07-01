<?php

class SlideshowModel extends Model {
	
	var $slides_dir;
	var $slides_fp;
	
	function __construct() {
		
		parent::__construct();
		$this->slides_dir = 'bento/img/slide/';
		$this->slides_fp = DIR.$this->slides_dir;
		
	}
	
	function saveShow($data) {
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['title'],'slideshow_shows');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'slideshow_shows',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'slideshow_shows');
				
			}
			
		}
		
		return $this->save($data,'slideshow_shows');
		
	}
	
	function saveSlide($data) {
		
		return $this->save($data,'slideshow_slides');
		
	}
	
	function loadShow($id) {
		
		return $this->load($id,'slideshow_shows');
		
	}
	
	function getSlideshows() {
		
		$sql = "
		SELECT
			`id`,
			`title`,
			`keyName`
		FROM
			`slideshow_shows`
		ORDER BY
			`title`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function getSlides($show_id) {
		
		$sql = "
		SELECT
			`id`,
			`title`,
			`description`,
			`image`,
			`link`,
			`windowaction`
		FROM
			`slideshow_slides`
		WHERE
			`show_id` = ".intval($show_id)."
		ORDER BY
			`displayOrder`
		";
		
		return $this->db->getAll($sql);
		
	}
	
	function deleteShow($show_id) {
		
		$show_id = intval($show_id);
		$this->db->delete('slideshow_shows',$show_id);
		
		$slides = $this->getSlides($show_id);
		
		if(!empty($slides)) {
			
			foreach($slides as $slide) {
				
				$this->deleteSlide($slide['id']);
				
			}
			
		}
		
		return true;
		
	}
	
	function deleteSlide($slide_id) {
		
		$id = intval($slide_id);
		$this->db->delete('slideshow_slides',$id);
		return true;
		
	}
	
	function safeToDelete($id) {
		
		return true;
		
	}
	
	function uploadSlide($file) {
		
		if(!empty($file['tmp_name'])) {
			
			if(is_uploaded_file($file['tmp_name'])) {
				
				//verify its actually an image
				if(exif_imagetype($file['tmp_name'])) {
					
					$saved = move_uploaded_file($file['tmp_name'],$this->slides_fp.$file['name']);
					
					if($saved) {
						
						$returndata = array();
						$returndata['title'] = $file['name'];
						$returndata['image'] = '/'.$this->slides_dir.$file['name'];
						$returndata['description'] = '';
						$returndata['link'] = '';
						return $returndata;
						
					} else {
						
						return false;
						
					}
					
				} else {
					
					return false;
					
				}
				
			} else {
				
				return false;
				
			}
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getShowId($identifier) {
		
		return $this->db->getOne("SELECT `id` FROM `slideshow_shows` WHERE `keyName` = '".$this->db->makeSafe($identifier)."'");
		
	}
	
	function saveSortOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$slide_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('slideshow_slides',"`id`=".intval($slide_id));
			
		}
		
		return true;
		
	}
	
}
