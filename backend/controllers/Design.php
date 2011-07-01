<?php
/**
 * Design backend controller
 */
class Design extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionLayouts($params='') {
		
		$objLayouts = new LayoutModel;
		$layoutList = $objLayouts->getLayouts();
		$this->view->assign('layoutList',$layoutList);
		
		$this->view->assign('content', $this->view->fetch('tpl/design/layouts.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEditlayout($params='') {
		
		$objLayouts = new LayoutModel;
		
		$layout_id = !empty($params['layout_id'])?intval($params['layout_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$layout_id = $this->saveLayout($params);
			
			if(!empty($params['ajaxsave'])) {
				
				$layoutInfo = $objLayouts->loadLayout($layout_id);
				echo json_encode($layoutInfo);
				return;
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Layout has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionLayouts();
				return;
				
			}
			
		}
		
		if(!empty($layout_id)) {
			
			$layoutInfo = $objLayouts->loadLayout($layout_id);
			$this->view->assign('layoutInfo',$layoutInfo);
			
		}
		
		$this->view->assign('content',$this->view->fetch('tpl/design/layout.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function saveLayout($params) {
		
		$objLayouts = new LayoutModel;
		$saveData = array();
		$saveData['id'] = !empty($params['layout_id'])?intval($params['layout_id']):false;
		$saveData['title'] = !empty($params['layout_title'])?$params['layout_title']:'Unnamed';
		$saveData['code'] = !empty($params['layout_code'])?$params['layout_code']:'';
		
		$layout_id = $objLayouts->saveLayout($saveData);
		return $layout_id;
		
	}
	
	function actionCreatelayout($params='') {
		
		$this->actionEditlayout($params);
		
	}
	
	function actionDeletelayout($params='') {
		
		if(!empty($params['layout_id'])) {
			
			$objLayouts = new LayoutModel;
			
			if($objLayouts->safeToDelete($params['layout_id'])) {
				
				$objLayouts->deleteLayout($params['layout_id']);
				
				$this->messages[] = array('type'=>'success','message'=>'Layout has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Cannot delete. There are still pages assigned to this layout.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown layout to delete.');
			
		}
		
		$this->actionLayouts();
		
	}
	
	function actionStylesheets($params='') {
		
		$objCSS = new StylesheetModel;
		$cssList = $objCSS->getStylesheets();
		$this->view->assign('cssList',$cssList);
		
		$this->view->assign('content', $this->view->fetch('tpl/design/stylesheets.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEditstylesheet($params='') {
		
		$objCSS = new StylesheetModel;
		
		$filename = !empty($params['filename'])?$params['filename']:false;
		
		if(!empty($params['dosave'])) {
			
			if(!empty($params['css_filename'])) {
				
				$saveData = array();
				$saveData['filename'] = $params['css_filename'];
				$saveData['content'] = !empty($params['css_content'])?$params['css_content']:'';
				
				$filename = $objCSS->saveStylesheet($saveData);
				
				if(!empty($params['ajaxsave'])) {
					
					echo 1;
					return;
					
				}
				
				$this->messages[] = array('type'=>'success','message'=>'Stylesheet has been saved.');
				
				if($params['submit'] == 'Save and Close') {
					
					$this->actionStylesheets();
					return;
					
				}
				
			} else {
				
				$this->messages[] = array('type'=>'warning','message'=>'Must specifty a file name.');
				
			}
			
		}
		
		if(!empty($filename)) {
			
			$stylesheet = $objCSS->loadStylesheet($filename);
			$this->view->assign('stylesheet',$stylesheet);
			$this->view->assign('filename',$filename);
			
		}
		
		$this->view->assign('content',$this->view->fetch('tpl/design/stylesheet.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateStylesheet($params='') {
		
		$this->actionEditstylesheet($params);
		
	}
	
	function actionDeletestylesheet($params='') {
		
		if(!empty($params['filename'])) {
			
			$objCSS = new StylesheetModel;
			$objCSS->deleteStylesheet($params['filename']);
			$this->messages[] = array('type'=>'success','message'=>'Stylesheet has been deleted.');
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown stylesheet to delete.');
			
		}
		
		$this->actionStylesheets();
		
	}
	
}

?>
