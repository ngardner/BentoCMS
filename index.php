<?php
/**
 * BentoCMS Frontend
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
	//$objEmail->sendMail();
	die('Unable to connect to database.');
	
}

$objAuthentication = Authentication::getInstance();
$objSettings = Settings::getInstance();
$objDispatcher = new Dispatcher;

// custom url rewriting
$objUrls = new FriendlyurlModel;
$objUrls->parseRequest($params['_urlrequest']);
if(!empty($objUrls->requestParams)) {
	$params = array_merge($params,$objUrls->requestParams);
}

// start up
try {
	
	$objSettings->loadSettings();
	$objDispatcher->setDirectory('frontend');
	$objDispatcher->setController($objUrls->requestController);
	$objDispatcher->setAction($objUrls->requestAction);
	$objDispatcher->setParams($params);
	$objDispatcher->dispatch();
	
} catch(Exception $e) {
	
	$objEmail = new Emailer;
	$objEmail->addTO(ERROR_EMAIL);
	$objEmail->setFrom(ERROR_EMAIL);
	$objEmail->setSubject('FATAL ERROR | Exception thrown on '.URL);
	$objEmail->setBody('Fatal Exception! '.$e->getMessage().print_r($params,true));
	//$objEmail->sendMail();
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
//echo "\r\n".'<!--'."\r\n".'Took '.$totalSeconds.' seconds, '.$dbQueries.' database queries, and '.$totalMemory.'KB of memory'."\r\n".'-->';

?>
