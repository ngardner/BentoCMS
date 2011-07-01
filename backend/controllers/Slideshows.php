<?php
/**
 * Slideshows backend controller
 */
class Slideshows extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$objSlideshow = new SlideshowModel;
		$slideshowList = $objSlideshow->getSlideshows();
		
		$this->view->assign('slideshowList',$slideshowList);
		$this->view->assign('content', $this->view->fetch('tpl/slideshows/shows.tpl'));
		$this->view->assign('messages',$this->messages);
		
		$this->finish();
		
	}
	
	function actionEditshow($params='') {
		
		$objSlideshow = new SlideshowModel;
		
		$show_id = !empty($params['show_id'])?intval($params['show_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$saveData = array();
			$saveData['id'] = $show_id;
			$saveData['title'] = !empty($params['show_title'])?$params['show_title']:'Unnamed';
			$saveData['keyName'] = !empty($params['show_keyname'])?$params['show_keyname']:false;
			$saveData['delay'] = !empty($params['show_delay'])?$params['show_delay']:1;
			$saveData['transition'] = !empty($params['show_transition'])?$params['show_transition']:'fade';
			$saveData['width'] = !empty($params['show_width'])?$params['show_width']:320;
			$saveData['height'] = !empty($params['show_height'])?$params['show_height']:240;
			
			$show_id = $objSlideshow->saveShow($saveData);
			
			//upload slides
			if(!empty($params['uploads']['newslide']['name'])) {
				
				foreach($params['uploads']['newslide']['name'] as $pointer=>$ignore) {
					
					if(!empty($params['uploads']['newslide']['tmp_name'][$pointer])) {
						
						$newSlide = array();
						$newSlide['name'] = $params['uploads']['newslide']['name'][$pointer];
						$newSlide['tmp_name'] = $params['uploads']['newslide']['tmp_name'][$pointer];
						
						$uploadedSlide = $objSlideshow->uploadSlide($newSlide);
						
						if($uploadedSlide) {
							
							$params['show_slides'][] = $uploadedSlide;
							
						} else {
							
							$this->messages[] = array('type'=>'error','message'=>'Unable to upload slide.');
							
						}
						
					}
					
				}
				
			}
			
			//save slide to show
			if(!empty($params['show_slides'])) {
				
				foreach($params['show_slides'] as $slide) {
					
					$saveData = array();
					$saveData['id'] = !empty($slide['id'])?$slide['id']:false;
					$saveData['show_id'] = $show_id;
					$saveData['title'] = $slide['title'];
					$saveData['description'] = $slide['description'];
					$saveData['image'] = $slide['image'];
					$saveData['link'] = $slide['link'];
					$saveData['windowaction'] = !empty($slide['windowaction'])?'_blank':'_self';
					$objSlideshow->saveSlide($saveData);
					
				}
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Slideshow has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionIndex();
				return;
				
			}
			
		}
		
		if(!empty($show_id)) {
			
			$showInfo = $objSlideshow->loadShow($show_id);
			$slideList = $objSlideshow->getSlides($show_id);
			$this->view->assign('slideList',$slideList);
			$this->view->assign('showInfo',$showInfo);
			
		}
		
		$this->view->assign('content',$this->view->fetch('tpl/slideshows/show.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateShow($params='') {
		
		$this->actionEditshow($params);
		
	}
	
	function actionDeleteShow($params='') {
		
		if(!empty($params['show_id'])) {
			
			$objSlideshow = new SlideshowModel;
			
			if($objSlideshow->safeToDelete($params['show_id'])) {
				
				$objSlideshow->deleteShow($params['show_id']);
				$this->messages[] = array('type'=>'success','message'=>'Slideshow has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Cannot delete slideshow.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown slideshow to delete.');
			
		}
		
		$this->actionIndex();
		
	}
	
	function actionDeleteSlide($params='') {
		
		if(!empty($params['slide_id'])) {
			
			$objSlideshow = new SlideshowModel;
			$objSlideshow->deleteSlide($params['slide_id']);
			$this->messages[] = array('type'=>'success','message'=>'Slide has been deleted.');
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown slide to delete.');
			
		}
		
		if(!empty($params['show_id'])) {
			
			$this->actionEditShow(array('show_id'=>intval($params['show_id'])));
			
		} else {
			
			$this->actionIndex();
			
		}
		
	}
	
	function actionSaveSlideOrder($params='') {
		
		if(!empty($params['order'])) {
			
			$pageOrder = explode(',',$params['order']);
			
			$sortOrder = array();
			
			foreach($pageOrder as $order) {
				
				$sortOrder[] = substr($order,6);
				
			}
			
			$objSlideshow = new SlideshowModel;
			$objSlideshow->saveSortOrder($sortOrder);
			
		}
		
	}
	
}

?>
