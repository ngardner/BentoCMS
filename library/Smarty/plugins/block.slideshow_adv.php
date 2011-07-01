<?php
/**
 * Smarty {slideshow_adv} block plugin
 *
 * Type:     block<br>
 * Name:     slideshow_adv<br>
 * Purpose:  Loads in a slideshow from the database<br>
 * Useage:
 * 
 * 
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_block_slideshow_adv($params, $content, &$smarty, $firstRun) {
    
    if($firstRun) {
        
        if(!empty($params['identifier']) && !empty($params['varname'])) {
            
            $objSlideshow = new SlideshowModel;
            $showId = $objSlideshow->getShowId($params['identifier']);
            
            if(!empty($showId)) {
                
                $showInfo = $objSlideshow->loadShow($showId);
                $showInfo['slides'] = $objSlideshow->getSlides($showId);
                $smarty->assign($params['varname'],$showInfo);
                
            } else {
                
                return 'ERROR: Unknown identifier';
                
            }
            
        } else {
            
            return 'ERROR: Must pass an identifier and a name';
            
        }
        
    } else {
        
        return $content;
        
    }
    
}

?>
