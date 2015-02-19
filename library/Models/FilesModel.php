<?php

class FilesModel extends Model {
    
    var $files_dir;
    var $files_fp;
    var $errors;
    
    function __construct() {
        
        parent::__construct();
        $this->files_dir = 'bento/uploads/';
	$this->files_fp = DIR.$this->files_dir;
        $this->errors = array();
        
    }
    
    function upload($file,$subFolder='') {
        
        // reset errors
        $this->errors = array();
        
        if(!empty($file['tmp_name'])) {
            
            if(is_uploaded_file($file['tmp_name'])) {
                
		// add YYYYMM subfolders
		$yyyymm = date("Ym");
		if($subFolder) { $subFolder .= '/'.$yyyymm; } else { $subFolder = $yyyymm; }
		
		if(!is_dir($this->files_fp.$subFolder)) {
                    mkdir($this->files_fp.$subFolder,0777,true);
                    chmod($this->files_fp.$subFolder,0777);
                }
                
                $file['name'] = $this->uniqueFilename($file['name'],$subFolder);
                
                $saved = move_uploaded_file($file['tmp_name'],$this->files_fp.$subFolder.'/'.$file['name']);
                
                if($saved) {
                    
                    $returndata = array();
                    $returndata['title'] = $file['name'];
                    $returndata['file'] = $this->files_dir.$subFolder.'/'.$file['name'];
                    $returndata['filesize'] = filesize($returndata['file']);
		    
                    return $returndata;
                    
                } else {
                    
                    $this->errors[] = 'Unable to move uploaded file to final location';
                    return false;
                    
                }
                
            } else {
                
                $this->errors[] = 'File is not an uploaded file';
                return false;
                
            }
            
        } else {
            
            $this->errors[] = 'Unknown uploaded file';
            return false;
            
        }
        
    }
    
    function uniqueFilename($fileName,$subFolder='') {
        
	$fileName = $this->cleanFilename($fileName);
	
        if(file_exists($this->files_fp.$subFolder.'/'.$fileName)) {
            
            // add timestamp to filename to make it unique
            $fileNameRev = strrev($fileName);
            $fileParts = explode('.',$fileNameRev,2);
            $newFileName = strrev($fileParts[1]).'_'.time().'.'.strrev($fileParts[0]);
            return $this->uniqueFilename($newFileName,$subFolder);
            
        } else {
            
            return $fileName;
            
        }
        
    }
    
    function store($file,$subFolder='') {
        
        $filenameLength = strpos(strrev($file),'/');
        if(!$filenameLength) {
            return false;
        } else {
            $fileName = substr($file,-$filenameLength);
        }
        
	// add YYYYMM subfolders
	$yyyymm = date("Ym");
	if($subFolder) { $subFolder .= '/'.$yyyymm; } else { $subFolder = $yyyymm; }
	
        if(!is_dir($this->files_fp.$subFolder)) {
            @mkdir($this->files_fp.$subFolder, 0777, true);
            @chmod($this->files_fp.$subFolder,0777);
        }
        
        $fileName = $this->uniqueFilename($fileName,$subFolder);
        
        $saved = copy($file,$this->files_fp.$subFolder.'/'.$fileName);
        
        if($saved) {
            
            return $this->files_dir.$subFolder.'/'.$fileName;
            
        } else {
            
            return false;
            
        }
        
    }
    
    function save($data,$filename,$location='') {
	
	// add YYYYMM subfolders
	$yyyymm = date("Ym");
	if($location) { $location .= '/'.$yyyymm; } else { $location = $yyyymm; }
	
	if(!is_dir($this->files_fp.$location)) {
            @mkdir($this->files_fp.$location, 0777, true);
            @chmod($this->files_fp.$location,0777);
        }
	
	$fileName = $this->uniqueFilename($filename,$location);
	
	$saved = file_put_contents($this->files_fp.$location.'/'.$fileName,$data);
	
	if($saved) {
	    return $this->files_fp.$location.'/'.$fileName;
	} else {
	    return false;
	}
	
    }
    
    function cleanFilename($fileName) {
	
	$fileNameRev = strrev($fileName);
	$fileParts = explode('.',$fileNameRev,2);
	$cleanPart = strrev($fileParts[1]);
	$extension = strrev($fileParts[0]);
	
	$cleanPart = preg_replace('/[^a-z\_A-Z0-9]/','',$cleanPart);
	
	$newFileName = $cleanPart.'.'.$extension;
	
	return $newFileName;
	
    }
    
}

?>