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
	
	// converts the data to json and outputs
	function JSONoutput($data) {
		
		$output = json_encode($data);
		header('Content-type: application/json');
		echo $output;
		exit(0);
		
	}
	
	// converts the data to CSV and outputs
	//function CSVoutput($data,$filename='csvexport') {
	//	
	//	$headers = array_keys($data[0]);
	//	
	//	header("Content-type: text/csv");
	//	header("Content-Disposition: attachment; filename=".$filename.".csv");
	//	header("Pragma: no-cache");
	//	header("Expires: 0");
	//	
	//	foreach($headers as $header) {
	//		
	//		echo '"'.str_replace('"', '""', $header).'",';
	//		
	//	}
	//	
	//	echo "\r\n";
	//	
	//	foreach($data as $exportRecord) {
	//		
	//		foreach($exportRecord as $value) {
	//			
	//			echo '"'.str_replace('"', '""', $value).'",';
	//			
	//		}
	//		
	//		echo "\r\n";
	//		
	//	}
	//	
	//	exit(0);
	//	
	//}
	
	function CSVoutput($data,$filename='csvexport') {
		
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=".$filename.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$headers = array();
		$exportData = array();
		
		// find all possibe headers
		foreach($data as $row) { $headers = array_merge($headers,array_keys($row)); }
		$headers = array_unique($headers);
		
		// format export data to match headers
		foreach($data as $row) {
			foreach($headers as $aHeader) {
				$exportRow[$aHeader] = !empty($row[$aHeader])?$row[$aHeader]:'';
			}
			$exportData[] = $exportRow;
		}
		
		// export it
		foreach($headers as $header) {
			
			echo '"'.str_replace('"', '""', $header).'",';
			
		}
		
		echo "\r\n";
		
		foreach($exportData as $exportRecord) {
			
			foreach($exportRecord as $value) {
				
				echo '"'.str_replace('"', '""', $value).'",';
				
			}
			
			echo "\r\n";
			
		}
		
		exit(0);
		
	}
	
	function requireHttps() {
		
		if(empty($_SERVER["HTTPS"])) {
			
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
			
		}
		
	}
	
}
?>
