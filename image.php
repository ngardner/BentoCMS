<?php

/**
 *  Image Generator
 *
 *  This file automatically generates the correctly sized image,
 *  caches it for future use, and displays it.
 *
 *  Supports jpg, gif, and png
 *
 *  Nathan Gardner <nathan@factory8.com>
 *  September 15th, 2010
 *
 *  Updated: April 1st, 2013 - added multiformat support, and transparent PNGs
 *
 *  ----------------
 *
 *  Usage:
 *  image.php?f=full/path.jpg&w=width&h=height[&effect=(bestfit,crop,stretch)]
 *
 *  f = filename of the image, including directories (relative path to current script, should not begin with /)
 *  w = the width of the image
 *  h = the height of the image
 *  effect = the resize effect. This is optional, defaults to bestfit. Other possible values are crop and stretch
 *
 *  w and h are both required if the effect is crop or stretch, otherwise only one of them is required
 *
 *  Example:
 *  <img src="/image.php?f=images/myimage.jpg&w=180&h=135&effect=crop"/>
 *
 *  Limitations:
 *  The outputted file will be the same format/extension, however Animated GIFs are not supported.
 *  PNGs now support transparency.
 *
 */

// SETTINGS
ini_set('display_errors',0);
$cacheDir = $_SERVER['DOCUMENT_ROOT'].'/cache/';
$imageQuality = 95; // 0 (bad quality, small file) to 100 (high quality, big file) - applies to JPEGS only

##############################################################################
##############################################################################

// INSTALL
if(!is_dir($cacheDir)) { mkdir($cacheDir,0777,true); }

##############################################################################
##############################################################################

// SETUP
$orignalImage = !empty($_GET['f'])?$_GET['f']:0;

if(substr($orignalImage,0,1) == '/') {
  $orignalImage = substr($orignalImage,1);
}

$maxWidth = !empty($_GET['w'])?intval($_GET['w']):0;
$maxHeight = !empty($_GET['h'])?intval($_GET['h']):0;
$effect = !empty($_GET['effect'])?$_GET['effect']:'bestfit';

##############################################################################
##############################################################################

// PROCESS
if(file_exists($orignalImage) && is_file($orignalImage)) {
  
  $imageInfo = getimagesize($orignalImage);
  $orignalType = intval($imageInfo[2]);
  
  switch($orignalType) {
    
    case 1: $imageType = 'gif'; break;
    case 2: $imageType = 'jpg'; break;
    case 3: $imageType = 'png'; break;
    default: $imageType = 'jpg'; break;
    
  }
  
  $cacheFile = md5($orignalImage.$maxWidth.$maxHeight.$effect).'.'.$imageType;
  
  // if width and height arent set, effect gets set to bestfit
  if(empty($maxWidth) || empty($maxHeight)) {
    
    $effect = 'bestfit';
    
  }
  
  // see if we have a cached version, and that the orignal image has not been updated
  if(!file_exists($cacheDir.$cacheFile) || filemtime($orignalImage) > filemtime($cacheDir.$cacheFile)) {
    
    $orignalWidth = intval($imageInfo[0]);
    $orignalHeight = intval($imageInfo[1]);
    $orignalRatio = $orignalWidth/$orignalHeight;
    
    // determine output width and height
    switch($effect) {
      
      case "crop":
      case "stretch":
        $width = $maxWidth;
        $height = $maxHeight;
      break;
      
      case "bestfit":
      default:
        
        if($maxWidth && $maxHeight) {
          
          if($orignalWidth > $orignalHeight) {
            
            $width = $maxWidth;
            $height = $maxWidth * ($orignalHeight / $orignalWidth);
            
          } else {
            
            $height = $maxHeight;
            $width = $maxHeight * ($orignalWidth / $orignalHeight);
            
          }
          
          if($height > $maxHeight) {
            
            $height = $maxHeight;
            $width = $maxHeight * ($orignalWidth / $orignalHeight);
            
          }
          
        } else {
          
          if($maxWidth) {
            
            $width = $maxWidth;
            $height = $maxWidth * ($orignalHeight / $orignalWidth);
            
          } else {
            
            $height = $maxHeight;
            $width = $maxHeight * ($orignalWidth / $orignalHeight);
            
          }
          
        }
        
      break;
      
    }
    
    $newRatio = $width/$height;
    
    // load in the orignal image
    switch($imageType) {
      
      case 'gif': $loadedImage = imagecreatefromgif($orignalImage); break;
      case 'jpg': $loadedImage = imagecreatefromjpeg($orignalImage); break;
      case 'png': $loadedImage = imagecreatefrompng($orignalImage); break;
      
    }
    
    if($loadedImage) {
      
      // create new image
      $newImage = imagecreatetruecolor($width,$height);
      
      if($imageType == 'png') {
        $trans_layer_overlay = imagecolorallocatealpha($newImage, 220, 220, 220, 127); // force transparency
        imagefill($newImage, 0, 0, $trans_layer_overlay); // force transparency
      }
      
      // put orignal image in new image
      switch($effect) {
        
        case 'bestfit':
        case 'stretch':
          imagecopyresampled($newImage,$loadedImage,0,0,0,0,$width,$height,$orignalWidth,$orignalHeight);
        break;
        
        case 'crop':
          
          if($newRatio > $orignalRatio) {
            
            $start_x = 0;
            $crop_width = $orignalWidth;
            $crop_height = $crop_width * ($height/$width);
            $start_y = ($orignalHeight - $crop_height)/2;
            
          } else {
            
            $start_y = 0;
            $crop_height = $orignalHeight;
            $crop_width = $crop_height * $newRatio;
            $start_x = ($orignalWidth - $crop_width)/2;
            
          }
          
          imagecopyresampled($newImage,$loadedImage,0,0,$start_x,$start_y,$width,$height,$crop_width,$crop_height);
          
        break;
        
      }
      
      // save to cache folder
      switch($imageType) {
        case 'gif': imagegif($newImage,$cacheDir.$cacheFile); break;
        case 'jpg': imagejpeg($newImage,$cacheDir.$cacheFile,$imageQuality); break;
        case 'png':
          imagealphablending($newImage, false); // force transparency
          imagesavealpha($newImage, true); // force transparency
          imagepng($newImage,$cacheDir.$cacheFile,9);
        break;
      }
      
      
      // display it
      outputImage($cacheDir.$cacheFile);
      
    } else {
      
      outputError($maxWidth,$maxHeight,'Image format not supported.');
      
    }
    
  } else {
    
    // have cached version, display it!
    outputImage($cacheDir.$cacheFile);
    
  }
  
} else {
  
  outputError($maxWidth,$maxHeight,'No Image Available');
  
}

##############################################################################
##############################################################################

function outputError($width,$height,$errorMsg) {
  
  if(empty($width)) {
    
    $width = $height;
    
  }
  
  if(empty($height)) {
    
    $height = $width;
    
  }
  
  if(empty($width)) {
    
    $width = 160;
    $height = 53;
    
  }
  
  $errorImage = imagecreate($width,$height);
  $background = imagecolorallocate($errorImage, 230, 230, 230);
  $black = imagecolorallocate($errorImage, 0, 0, 0);
  header('Content-type: image/jpeg');
  imagestring($errorImage,2,10,10,$errorMsg,$black);
  imagejpeg($errorImage);
  imagedestroy($errorImage);
  exit();
  
}

function outputImage($fileName) {
  
  $imageInfo = getimagesize($fileName);
  $orignalType = intval($imageInfo[2]);
  
  switch($orignalType) {
    
    case 'gif';
      header('Content-type: image/gif');
    break;
    case 'jpg';
      header('Content-type: image/jpeg');
    break;
    case 'png';
      header('Content-type: image/png');
    break;
    default:
      header('Content-type: image/jpeg');
    break;
    
  }
  
  header('Content-Disposition: attachment; filename='.$fileName);
  echo file_get_contents($fileName);
  exit();
  
}

?>