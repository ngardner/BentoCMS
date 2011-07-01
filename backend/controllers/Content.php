<?php
/**
 * Content backend controller
 */
class Content extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionPages($params='') {
		
		$objPages = new PagesModel;
		$pageList = $objPages->getPages();
		$this->view->assign('pageList',$pageList);
		
		$this->view->assign('content', $this->view->fetch('tpl/content/pages.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionClonepage($params = '') {
		
		$objPage = new PagesModel();
		
		if(!empty($params['page_id'])) {
			
			$params['page_id'] = $objPage->clonePage($params['page_id']);
			
			$this->actionEditpage($params);
			
		}
		
	}
	
	function actionEditpage($params='') {
		
		$objPage = new PagesModel;
		$objLayouts = new LayoutModel;
		
		$page_id = !empty($params['page_id'])?intval($params['page_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$page_id = $this->savePage($params);
			
			if(!empty($params['ajaxsave'])) {
				
				$pageInfo = $objPage->loadPage($page_id);
				echo json_encode($pageInfo);
				return;
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Page has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionPages();
				return;
				
			}
			
		}
		
		$pageList = $objPage->getPages();
		$this->view->assign('pageList',$pageList);
		
		$layouts = $objLayouts->getLayouts();
		$this->view->assign('layouts',$layouts);
		
		if(!empty($page_id)) {
			
			$pageInfo = $objPage->loadPage($page_id);
			$pageInfo['sidebars'] = $objPage->getPageSidebars($pageInfo['id']);
			$this->view->assign('pageInfo',$pageInfo);
			
		}
		
		if(!empty($params['type']) && $params['type'] == 'link') {
			$tpl = 'tpl/content/link.tpl';
		} else {
			$tpl = 'tpl/content/page.tpl';
		}
		
		$this->view->assign('content',$this->view->fetch($tpl));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function savePage($params) {
		
		$objSidebars = new SidebarsModel;
		$objPage = new PagesModel;
		
		$saveData = array();
		$saveData['id'] = !empty($params['page_id'])?intval($params['page_id']):false;
		$saveData['title'] = !empty($params['page_title'])?$params['page_title']:'Unnamed';
		$saveData['keyName'] = !empty($params['page_keyname'])?$params['page_keyname']:false;
		$saveData['content'] = !empty($params['page_content'])?$params['page_content']:'';
		$saveData['parent_id'] = !empty($params['page_parent_id'])?intval($params['page_parent_id']):0;
		$saveData['status'] = !empty($params['page_status'])?$params['page_status']:false;
		$saveData['layout_id'] = !empty($params['page_layout_id'])?intval($params['page_layout_id']):false;
		$saveData['displayOrder'] = isset($params['page_displayOrder'])?intval($params['page_displayOrder']):1000;
		$saveData['url'] = !empty($params['page_url'])?$params['page_url']:false;
		$saveData['type'] = !empty($params['page_type'])?$params['page_type']:'page';
		$saveData['windowaction'] = !empty($params['page_windowaction'])?$params['page_windowaction']:false;
		$saveData['meta']['title'] = !empty($params['meta_title'])?$params['meta_title']:'';
		$saveData['meta']['description'] = !empty($params['meta_description'])?$params['meta_description']:'';
		$saveData['meta']['keywords'] = !empty($params['meta_keywords'])?$params['meta_keywords']:'';
		
		$page_id = $objPage->savePage($saveData);
		
		if(!empty($params['page_sidebars'])) {
			
			foreach($params['page_sidebars'] as $location=>$content) {
				
				$saveData = array();
				$saveData['page_id'] = $page_id;
				$saveData['location'] = $location;
				$saveData['content'] = $content;
				$objSidebars->saveSidebar($saveData);
				
			}
			
		}
		
		return $page_id;
		
	}
	
	function actionCreatePage($params='') {
		
		$this->actionEditpage($params);
		
	}
	
	function actionSavePageOrder($params='') {
		
		if(!empty($params['order'])) {
			
			$pageOrder = explode(',',$params['order']);
			
			$sortOrder = array();
			
			foreach($pageOrder as $order) {
				
				$sortOrder[] = substr($order,5);
				
			}
			
			$objPages = new PagesModel;
			$objPages->saveSortOrder($sortOrder);
			
		}
		
	}
	
	function actionDeletePage($params='') {
		
		if(!empty($params['page_id'])) {
			
			$objPages = new PagesModel;
			$objSidebars = new SidebarsModel;
			
			if($objPages->safeToDelete($params['page_id'])) {
				
				$objPages->deletePage($params['page_id']);
				$objSidebars->deletePageSidebars($params['page_id']);
				
				$this->messages[] = array('type'=>'success','message'=>'Page has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Cannot delete a page with children.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown page to delete.');
			
		}
		
		$this->actionPages();
		
	}
	
	function actionBlocks($params='') {
		
		$objBlocks = new BlocksModel;
		$blockList = $objBlocks->getBlocks();
		$this->view->assign('blockList',$blockList);
		
		$this->view->assign('content', $this->view->fetch('tpl/content/blocks.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCloneBlock($params = '') {
		
		$objBlocks = new BlocksModel;
		
		if(!empty($params['block_id'])) {
			
			$params['block_id'] = $objBlocks->cloneBlock($params['block_id']);
			
			$this->actionEditblock($params);
			
		}
		
	}
	
	function actionEditblock($params='') {
		
		$objBlocks = new BlocksModel;
		
		$block_id = !empty($params['block_id'])?intval($params['block_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$block_id = $this->saveBlock($params);
			
			if(!empty($params['ajaxsave'])) {
				
				$blockInfo = $objBlocks->loadBlock($block_id);
				echo json_encode($blockInfo);
				return;
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Block has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionBlocks();
				return;
				
			}
			
		}
		
		if(!empty($block_id)) {
			
			$blockInfo = $objBlocks->loadBlock($block_id);
			$this->view->assign('blockInfo',$blockInfo);
			
		}
		
		$this->view->assign('content',$this->view->fetch('tpl/content/block.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function saveBlock($params) {
		
		$objBlocks = new BlocksModel;
		$saveData = array();
		$saveData['id'] = !empty($params['block_id'])?intval($params['block_id']):false;
		$saveData['title'] = !empty($params['block_title'])?$params['block_title']:'Unnamed';
		$saveData['keyName'] = !empty($params['block_keyname'])?$params['block_keyname']:false;
		$saveData['code'] = !empty($params['block_code'])?$params['block_code']:'';
		
		$block_id = $objBlocks->saveBlock($saveData);
		return $block_id;
		
	}
	
	function actionCreateBlock($params = '') {
		
		$this->actionEditblock($params);
		
	}
	
	function actionDeleteBlock($params='') {
		
		if(!empty($params['block_id'])) {
			
			$objBlocks = new BlocksModel;
			
			$objBlocks->deleteBlock($params['block_id']);
			
			$this->messages[] = array('type'=>'success','message'=>'Block has been deleted.');
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown block to delete.');
			
		}
		
		$this->actionBlocks();
		
	}
	
}

?>
