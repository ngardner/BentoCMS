<?php
/**
 * This file loads the Smarty class
 * its just here so the autoload works
 */

include('Smarty/Smarty.class.php');

class View extends Smarty {
	
	private $location;
	
	function __construct($location) {
		
		$this->Smarty();
		$this->location = $location;
		$this->template_dir = DIR.$location.'/views/';
		$this->compile_dir = DIR.$location.'/views/compiled/';
		$this->force_compile = true;
		$this->assignCommons();
		$this->registerPlugins();
		
	}
	
	function assignCommons() {
		
		global $params;
		
		##prod info
		$this->assign('ProductTitle',PRODUCT_NAME);
		
		##skin dir
		$this->assign('skin',URL.'/'.$this->location.'/views/');
		$this->assign('bento',URL.'/bento/');
		
		##global url http
		if(defined('INSTALLDIR') && INSTALLDIR != ''){
			$this->assign('httpUrl',URL.'/');
		}else{
			$this->assign('httpUrl',URL);
		}
		
		##site settings
		$objSettings = Settings::getInstance();
		$settings = $objSettings->getEntrys();
		$this->assign('Settings', $settings);
		
		$metaTitle = $settings['meta']['default-meta-title'];
		$metaDescription = $settings['meta']['default-meta-description'];
		$metaKeywords = $settings['meta']['default-meta-keywords'];
		
		if(!empty($params['_urlrequest'])) {
			
			$objUrls = new FriendlyurlModel;
			$objUrls->parseRequest($params['_urlrequest']);
			$urlMeta = $objUrls->getMetaData($objUrls->url_id);
			
			if(!empty($urlMeta['title'])) { $metaTitle = $urlMeta['title']; }
			if(!empty($urlMeta['description'])) { $metaDescription = $urlMeta['description']; }
			if(!empty($urlMeta['keywords'])) { $metaKeywords = $urlMeta['keywords']; }
			
			$this->assign('urlrequest',$params['_urlrequest']);
			
		}
		
		##meta deta
		$this->assign('metaTitle', $metaTitle);
		$this->assign('metaDescription', $metaDescription);
		$this->assign('metaKeywords', $metaKeywords);
		
		##global filesystem path
		$this->assign('fsPath',DIR);
		
		##user vars
		$objAuthentication = Authentication::getInstance();
		
		if($objAuthentication->loggedIn()) {
			
			$objUser = new UserModel($objAuthentication->user_id);
			$this->assign('loggedIn',true);
			$this->assign('UserInfo',$objUser->getInfo());
			
		} else {
			
			$this->assign('loggedIn',false);
			
		}
		
	}
	
	function registerPlugins() {
		
		// fromstring plugin
		include_once(DIR.'library/Smarty/plugins/resource.fromstring.php');
		$this->register_resource("fromstring",
			array(
				"smarty_resource_fromstring_source",
				"smarty_resource_fromstring_timestamp",
				"smarty_resource_fromstring_secure",
				"smarty_resource_fromstring_trusted"
			)
		);
		
	}
	
}

?>
