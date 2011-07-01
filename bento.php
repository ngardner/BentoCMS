<?php
/**
 * BentoCMS Backend
 * 
 * @author Nathan Gardner <nathan@factory8.com>
 * @version 1.0
 */

// start timer
$startTime = microtime(true);

// start session
session_start();

// load initalization file
require('config/init.php');

// connect to database
try {
	
	$objDatabase = Database::getInstance();
	$objDatabase->connect(DB_SERVER,DB_PORT,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	
} catch(Exception $e) {
	
	$objEmail = new Emailer;
	$objEmail->addTO(ERROR_EMAIL);
	$objEmail->setFrom(ERROR_EMAIL);
	$objEmail->setSubject('FATAL ERROR | Database connection problem on '.URL);
	$objEmail->setBody('Database connection problem!!' . $objDatabase->getError());
	$objEmail->sendMail();
	die('Unable to connect to database.');
	
}

$objAuthentication = Authentication::getInstance();
$objSettings = Settings::getInstance();
$objDispatcher = new Dispatcher;

// login check
if($objAuthentication->loggedIn()) {
	
	// admin user check
	if($objAuthentication->isAdmin()) {
		
		// set what were doing (set controller and action)
		$controller = ucfirst(!empty($params['_controller'])?$params['_controller']:'Home');
		$action = ucfirst(!empty($params['_action'])?$params['_action']:'Index');
		
	} else {
		
		// take to frontend!
		$objAuthentication->logout();
		header("Location: http://".URL);
		
	}
	
} else {
	
	$controller = 'Login';
	
	if(!empty($params['_controller']) && $params['_controller'] != 'Login') {
		
		$action = 'Index';
		
	} else {
		
		$action = ucfirst(!empty($params['_action'])?$params['_action']:'Index');
		
	}
	
}

// permission check
$objPermissions = Permissions::getInstance();
$hasPermission = $objPermissions->actionAllowed($controller,$action,$objAuthentication->user_id);

if(!$hasPermission) {
	
	$controller = 'Error';
	$action = 'Permission';
	
}

// start up
try {
	
	$objSettings->loadSettings();
	$objDispatcher->setDirectory('backend');
	$objDispatcher->setController($controller);
	$objDispatcher->setAction($action);
	$objDispatcher->setParams($params);
	$objDispatcher->dispatch();
	
} catch(Exception $e) {
	
	$objEmail = new Emailer;
	$objEmail->addTO(ERROR_EMAIL);
	$objEmail->setFrom(ERROR_EMAIL);
	$objEmail->setSubject('FATAL ERROR | Exception thrown on '.URL);
	$objEmail->setBody('Fatal Exception! '.$e->getMessage());
	$objEmail->sendMail();
	die('Error<br/>'.$e->getMessage().'<br/><a href="http://'.URL.'">'.PRODUCT_NAME.'</a>');
	
}

// time to clean up
$dbQueries = $objDatabase->getNumbQueries();
$objDatabase->disconnect();

//end timer
$endTime = microtime(true);

// calc render details
$totalSeconds = number_format($endTime-$startTime,3);
$totalMemory = number_format((memory_get_peak_usage()/1024),2);

// speed / queries / memory report
// if you uncomment this ajax requests / rss / xml responses will be corrupt
//echo "\r\n".'<!--'."\r\n".'Took '.$totalSeconds.' seconds, '.$dbQueries.' database queries, and '.$totalMemory.'KB of memory'."\r\n".'-->';

?>
