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
				
				throw new Exception('Class '.$this->controller.' does not exist');
				
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
		
		$this->browserCheck();
		$this->replicatedSiteCheck(); // changes control and action
		
		try {
			
			$this->runAction();
			
		} catch(Exception $e) {
			
			// bubble up
			$this->error404($e->getMessage());
			
		}
		
	}
	
	function replicatedSiteCheck()  {
		
		// only do this check if class doesnt exist
		$className = ucfirst(strtolower($this->controller));
		if(class_exists($className)) { return false; }
		
		// parse out petpros name
		$petproname = urlencode($this->controller);
		
		$objPetPro = new PetProModel;
		$replicatedSite = $objPetPro->GetReplicatedSiteInfo($petproname);
		
		if($replicatedSite) {
			
			$objPetPro->rememberPetPro($replicatedSite);
			
			// take them to petpro page
			//header("Location: ".URL."petpro");
			//exit(0);
			
			// take them to homepage
			header("Location: ".URL);
			exit(0);
			
		} else {
			
			return false;
			
		}
		
		
	}
	
	function browserCheck() {
		
		// if you want to check for IE6 or whatever, do it here
		return true;
		
	}
	
	static function error404($error='') {
		
		header('HTTP/1.0 404 Not Found');
		//echo '
		//<!DOCTYPE html>
		//<html lang="en">
		//<head>
		//	<meta charset="utf-8">
		//	<title>'.PRODUCT_NAME.'</title>
		//	<link href="'.URL.'/bento/css/reset.css" rel="stylesheet">
		//	<link href="'.URL.'/bento/css/style.css" rel="stylesheet">
		//</head> 
		//<body>
		//
		//<div id="container">
		//	<div id="header"><h1>'.PRODUCT_NAME.'</h1></div>
		//	<div id="content">
		//		<h2>404</h2>
		//		<p>Sorry, but the page wasn\'t found</p>
		//		<hr/>
		//		<strong>'.htmlentities($error).'</strong><br/>
		//		<ul>
		//			<li>Directory: '.$this->directory.'</li>
		//			<li>Class: '.$this->controller.'</li>
		//			<li>Function: '.$this->action.'</li>
		//			<li>Params: '.print_r($this->params,true).'</li>
		//		</ul>
		//	</div>
		//	<footer>'.PRODUCT_NAME.'</footer>
		//</div>
		//';
		
		echo '
		<!DOCTYPE html>
		<html>
		<head>
			<title>Paw Tree</title>
			<meta charset="utf-8">
			<meta name="description" content="">
			<meta name="keywords" content="">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
			<link rel="stylesheet" href="/bento/css/reset.css">
			<link rel="stylesheet" href="/bento/css/typography.css">
			<link rel="stylesheet" href="/bento/css/style.css">
			<link rel="stylesheet" href="/bento/css/style2.css">
			<link rel="stylesheet" href="/bento/css/responsive.css">
		</head>
		
		<body class="page">
			<div id="container">
				
				<div id="header" class="clearfix">
					<div id="logo">
						<a href="http://pawtree.com/"><img src="/bento/img/logo.png" alt="Paw Tree" title="Paw Tree"/></a>
					</div>
					<ul class="nav">
						<li><a href="/about" target="_self">About</a></li>
						<li><a href="/product-details" target="_self">Learn</a></li>
						<li><a href="/shop" target="_self">Shop</a></li>
						<li><a href="/paw-club" target="_self">Opportunity</a></li>
					</ul>
				</div>
				
				<div id="content" class="content-page">
					<div class="content-header"><h1>Error</h1></div>
					<h2>An error has occured</h2>
					<hr/>
					<strong>'.htmlentities($error).'</strong><br/>
					'.
					/*
					<ul>
						<li>Directory: '.$this->directory.'</li>
						<li>Class: '.$this->controller.'</li>
						<li>Function: '.$this->action.'</li>
						<li>Params: '.print_r($this->params,true).'</li>
					</ul>
					*/
					'
				</div>
				
				<footer><p>&copy; 2013 PAWTREE, LLC. ALL RIGHTS RESERVED.</p></footer>
				
			</div>
		</body>
		</html>
		';
		exit(0);
		
	}
	
}

?>
