<?php
/**
 * Smarty {slideshow} function plugin
 *
 * Type:     function<br>
 * Name:     slideshow<br>
 * Purpose:  Loads in a slideshow from the database<br>
 * @author Nathan Gardner <nathan@factory8.com>
 * @param array
 * @param Smarty
 */

function smarty_function_slideshow($params, &$smarty) {
    
    if(!empty($params['identifier'])) {
        
        $objSlideshow = new SlideshowModel;
        $showId = $objSlideshow->getShowId($params['identifier']);
        
        if(!empty($showId)) {
            
            $showInfo = $objSlideshow->loadShow($showId);
            $showInfo['slides'] = $objSlideshow->getSlides($showId);
            
            $jsOutput = '
            <script type="text/javascript">
            $(document).ready(function() {
                $(\'#slideshow_'.$showId.'\').cycle({
                   fx: \''.$showInfo['transition'].'\',
                   timeout: '.($showInfo['delay']*1000).',
                   pager: \'#navi_'.$showId.'\',
                   pagerAnchorBuilder: function(idx, slide) { 
                   		return \'<a href="#"><img src="/bento/img/x.gif"/></a>\'; 
					} 
                });
            });
            </script>
            ';
            
            $output = $jsOutput."\r\n";
            
            $output .= '
            <div class="slideshow" style="width:'.$showInfo['width'].'px; height:'.$showInfo['height'].'px;">
                
                <div class="navi" id="navi_'.$showId.'"></div>
                
                <div class="slideshow-slides" id="slideshow_'.$showId.'">
                
            ';
            foreach($showInfo['slides'] as $slide) {
                
                $output .= '
                <div class="slide">
                    <div class="slide-box">
                        <p class="slide-title">'.$slide['title'].'</p>
                        <p class="slide-info">'.$slide['description'].'</p>
                    </div>
                    ';
                
                if(!empty($slide['link'])) {
                    
                    $output .= '<a href="'.$slide['link'].'" target="'.$slide['windowaction'].'">';
                    
                }
                
                $output .= '<img src="/image.php?f='.$slide['image'].'&amp;w='.$showInfo['width'].'&amp;h='.$showInfo['height'].'&amp;" alt="'.$slide['title'].'"/>';
                
                if(!empty($slide['link'])) {
                    
                    $output .= '</a>';
                    
                }
                
                $output .= '
                </div>
                ';
                
            }
            
            $output .= '
                
                </div>
                
            </div>
            ';
            
            return $output;
            
        } else {
            
            return 'ERROR: Unknown slideshow';
            
        }
        
    } else {
        
        return 'ERROR: Must pass identifier';
        
    }
    
}

?>
