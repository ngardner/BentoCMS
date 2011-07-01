<?php
/**
 * This file contains the dispatcher
 */

class Dispatcher {
	
	/**
	 *@var string Directory to load controller from
	 */
	var $directory;
	
	/**
	 *@var string Controller
	 */
	var $controller;
	
	/**
	 *@var string Action to take
	 */
	var $action;
	
	/**
	 *@var string $_GET and $_POST params
	 */
	var $params;
	
	function __construct() {
		
		$this->setController();
		$this->setAction();
		$this->setParams(array());
		
	}
	
	function setAction($action='Index') {
		
		$this->action = 'action'.$action;
		
	}
	
	function setController($controller='Home') {
		
		$this->controller = $controller;
		
	}
	
	function setParams($params) {
		
		$this->params = $params;
		
	}
	
	function setDirectory($dir) {
		
		$this->directory = $dir;
		Autoloader::addClassPath(DIR.$dir.'/');
		
	}
	
	private function runAction() {
		
		try {
			
			if(class_exists($this->controller)) {
				
				$objController = new $this->controller;
				
			} else {
				
				//throw new Exception('Class '.$this->controller.' does not exist');
				// dont throw exceptions here! could be anything, including favicon.ico or robots.txt requests
				header("Location: http://".URL."/Page/404");
				exit(0);
				
				
			}
			
			if ($objController instanceof Controller) {
				
				$objController->setPlace($this->directory);
				$objController->setLayout();
				$objController->execute($this->action,$this->params);
				
			} else {
				
				//bubble up
				throw new Exception('Class '.$this->controller.' does not extend Controller class!');
				
			}
			
		} catch(Exception $e) {
			
			// bubble up
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	function dispatch() {
		
		try {
			
			$this->runAction();
			
		} catch(Exception $e) {
			
			// bubble up
			throw new Exception($e->getMessage());
			
		}
		
	}
	
}

?>
