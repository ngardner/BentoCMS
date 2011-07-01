<?php
/**
 * Search controller
 */
class Search extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function setLayout() {
		
		$this->layout = 'search.tpl';
		
	}
	
	function actionIndex($params='') {
		
		$objSearch = new SearchModel;
		$searchQuery = !empty($params['searchQuery'])?$params['searchQuery']:'';
		$searchOptions = array();
		$searchResults = false;
		
		// load templates
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		$layoutInfo = $objLayout->loadLayout();
		$template = $objTemplate->loadTemplateFromKeyname('search');
		
		// doing search?
		if(!empty($searchQuery)) {
			
			// add search options
			if(!empty($params['searchForType'])) {
				
				foreach($params['searchForType'] as $typeSearch) {
					
					$searchOptions[] = array('type'=>'type','value'=>$typeSearch);
					
				}
				
			}
			
			// do search
			$searchResults = $objSearch->performSearch($searchQuery,$searchOptions);
			
		}
		
		// assign to template
		$this->view->assign('searchQuery',$searchQuery);
		$this->view->assign('searchOptions',$searchOptions);
		$this->view->assign('searchResults',$searchResults);
		
		// render template
		$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
		$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar']));
		$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar']));
		$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
		$this->finish();
		
	}
	
	function actionAutocomplete($params='') {
		
		$returnResults = array();
		
		if(!empty($params['term'])) {
			
			$objSearch = new SearchModel;
			$searchResults =  $objSearch->performSearch($params['term'],array('dontsave'=>true));
			
			if(!empty($searchResults)) {
				
				foreach($searchResults as $result) {
					
					$record = new stdClass;
					$record->title = $result['title'];
					$record->url = $result['url'];
					$record->desc = $result['description'];
					$returnResults[] = $record;
					
				}
				
			}
			
		}
		
		echo json_encode($returnResults);
		
	}
	
}

?>

