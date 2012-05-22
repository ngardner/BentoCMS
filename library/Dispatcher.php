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
		
		$cleanParams = array();
		
		// backend controllers are used by trused and authenticated users, allow HTML to be posted
		if($this->directory != 'backend') {
			
			foreach($params as $key=>$value) {
				
				## IF HTML SHOULD BE ALLOWED TO BE POSTED, WHITELIST IT HERE ##
				$whitelistKeys = array(
					'uploads'
				);
				
				if(!in_array($key,$whitelistKeys)) {
					
					if(is_string($value)) {
						
						$value = htmlspecialchars($value);
						
					}
					
				}
				
				$cleanParams[$key] = $value;
				
			}
			
		} else {
			
			$cleanParams = $params;
			
		}
		
		$this->params = $cleanParams;
		
	}
	
	function setDirectory($dir) {
		
		$this->directory = $dir;
		Autoloader::addClassPath(DIR.$dir.'/');
		
	}
	
	private function runAction() {
		
		try {
			
			$className = ucfirst(strtolower($this->controller));
			
			if(class_exists($className)) {
				
				$objController = new $className;
				
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
				throw new Exception('Class '.$className.' does not extend Controller class!');
				
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
