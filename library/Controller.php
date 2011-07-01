<?php
/**
 * This is the controller, all sub controllers should extend this
 */
class Controller {
  
	public $view;
  public $layout;
	
	function __construct() {
		
		$this->objAuthentication = Authentication::getInstance();
		
	}
	
	public function execute($action, $params) {
		
		if(method_exists($this,$action)) {
			
			call_user_func_array(array($this, $action), array($params));
			
		} else {
			
			throw new Exception('Action "'.$action.'" not defined for this controller.');
			
		}
		
	}
	
	function setPlace($place) {
		
		$this->view = new View($place);
		$this->place = $place;
		
	}
	
	function setLayout() {
		
		$this->layout = 'layout.tpl';
		
	}
	
	function finish($return=false) {
		
		if($this->view) {
			
			if($return) {
				
				$this->view->fetch($this->layout);
				
			} else {
				
				$this->view->display($this->layout);
				
			}
			
		} else {
			
			throw new Exception('No view to render');
			
		}
		
	}
	
	function requireHttps() {
		
		if(empty($_SERVER["HTTPS"])) {
			
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			
		}
		
	}
	
}
?>
