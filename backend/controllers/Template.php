<?php
/**
 * Template backend controller
 */
class Template extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionTemplates($params='') {
		
		$group = !empty($params['group'])?$params['group']:false;
		
		$objTemplates = new TemplatesModel;
		$templateList = $objTemplates->getTemplates($group);
		$this->view->assign('group',$group);
		$this->view->assign('templateList',$templateList);
		
		$this->view->assign('content', $this->view->fetch('tpl/templates/templates.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEditTemplate($params='') {
		
		$objTemplates = new TemplatesModel;
		
		$template_id = !empty($params['template_id'])?intval($params['template_id']):false;
		$group = !empty($params['group'])?$params['group']:false;
		
		if(!empty($params['dosave'])) {
			
			$saveData = array();
			
			if(!empty($params['template_id'])) {
				
				$saveData = array();
				$saveData['content'] = !empty($params['template_content'])?$params['template_content']:'';
				$saveData['left_sidebar'] = !empty($params['template_left_sidebar'])?$params['template_left_sidebar']:'';
				$saveData['right_sidebar'] = !empty($params['template_right_sidebar'])?$params['template_right_sidebar']:'';
				$saveData['id'] = intval($params['template_id']);
				
				$template_id = $objTemplates->saveTemplate($saveData);
				
				if(!empty($params['ajaxsave'])) {
					
					$templateInfo = $objTemplates->loadTemplate($template_id);
					echo json_encode($templateInfo);
					return;
					
				}
				
				$this->messages[] = array('type'=>'success','message'=>'Template has been saved.');
				
			} else {
				
				$this->messages[] = array('type'=>'Error','message'=>'Unknown template to save.');
				
			}
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionTemplates(array('group'=>$group));
				return;
				
			}
			
		}
		
		if(!empty($template_id)) {
			
			$templateInfo = $objTemplates->loadTemplate($template_id);
			$this->view->assign('templateInfo',$templateInfo);
			
		}
		
		$this->view->assign('group',$group);
		$this->view->assign('content',$this->view->fetch('tpl/templates/template.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
}

?>
