<?php
/**
 * Smarty {menu} function plugin
 *
 * Type:     function<br>
 * Name:     menu<br>
 * Purpose:  Loads in a menu of CMS pages<br>
 * @author Nathan Gardner <nathan@ecrated.com>
 */

$__pageListTemp;

function smarty_function_menu($params, &$smarty) {
    
    $output = '';
    $objPages = new PagesModel;
    $pageList = $objPages->getPages('published');
    global $__pageListTemp;
    
    if(!empty($params['debug']) && $params['debug'] == 'true') {
        echo '<pre>';print_r($pageList);echo '</pre>';
    }
    
    if(!empty($pageList)) {
        
        // limit to a parent
        if(!empty($params['parent'])) {
            
            // find parent
            $parent_id = $objPages->getPageId($params['parent']);
            
            if($parent_id) {
                
                smarty_function_menu_parent($pageList,$parent_id);
                $pageList = $__pageListTemp;
                
            }
            
        }
        
        reset($pageList);
        
        // limit depth
        if(!empty($params['depth'])) {
            
            $depth = intval($params['depth']);
            $pageList = smarty_function_menu_setdepth($pageList,$depth);
            
        }
        
        reset($pageList);
        
        // remove specific pages
        if(!empty($params['remove'])) {
            
            $params['remove'] .= ',404';
            
        } else {
            
            $params['remove'] = '404';
            
        }
        
        $pageList = smarty_function_menu_removepages($pageList,$params['remove']);
        
        reset($pageList);
        
        // set active classes
        if(!empty($smarty->current_page)) {
            
            $pageList = smarty_function_menu_makeactive($pageList,$smarty->current_page);
            
        }
        
        reset($pageList);
        
        if(!empty($params['debug']) && $params['debug'] == 'true') {
            print_r($pageList);
        }
        
        // draw menu
        $output = smarty_function_menu_makemenu($pageList,$output,$smarty);
        
    }
    
    return $output;
    
}

function smarty_function_menu_makemenu($pageList,&$output='',&$smarty) {
    
    $objUrl = new FriendlyurlModel;
    
    $output .= '<ul>';
    
    foreach($pageList as $page) {
        
        if(!empty($page['title'])) {
            
            if(!empty($page['active'])) {
                
                $class = ' class="active"';
                
            } else {
                
                $class = '';
                
            }
            
            $output .= '<li'.$class.'>';
            
            if($page['type'] == 'page') {
                
                $url = $objUrl->findUrl('pages',$page['keyName']);
                
            } else {
                
                $url = $page['url'];
                
            }
            
            $output .= '<a href="'.$url.'" target="'.$page['windowaction'].'">'.$page['title'].'</a>';
            
            if(!empty($page['children'])) {
                
                smarty_function_menu_makemenu($page['children'],$output,$smarty);
                
            }
            
            $output .= '</li>';
            
        }
        
    }
    
    $output .= '</ul>';
    
    return $output;
    
}

function smarty_function_menu_makeactive(&$pageList,$keyName) {
    
    // find active page
    foreach($pageList as $page_id=>&$page) {
        
        if($page['keyName'] == $keyName) {
            
            $page['active'] = true;
            $parent_id = $page_id;
            
            break;
            
        } else {
            
            if(!empty($page['children'])) {
                
                smarty_function_menu_makeactive($page['children'],$keyName);
                
            }
            
        }
        
    }
    
    //bubble it up - HOW??
    
    return $pageList;
    
}

function smarty_function_menu_parent($pageListNew,$parent_id) {
    
    global $__pageListTemp;
    
    foreach($pageListNew as $page_id=>$page) {
        
        if($parent_id == $page_id) {
            
            if(!empty($page['children'])) {
                
                $__pageListTemp = $page['children'];
                
            } else {
                
                return array();
                
            }
            
        } else {
            
            if(!empty($page['children'])) {
                
                smarty_function_menu_parent($page['children'],$parent_id);
                
            }
            
        }
        
    }
    
}

function smarty_function_menu_removepages(&$pageList,$removeList) {
    
    if(is_string($removeList)) {
        
        $removeList = explode(',',$removeList);
        
    }
    
    if(!empty($removeList)) {
        
        foreach($pageList as $page_id=>&$page) {
            
            $key = array_search($page['keyName'],$removeList);
            
            if($key !== false) {
                
                unset($pageList[$page_id]);
                unset($removeList[$key]);
                
            } else {
                
                if(!empty($pageList[$page_id]['children'])) {
                    
                    smarty_function_menu_removepages($pageList[$page_id]['children'],$removeList);
                    
                }
                
            }
            
        }
        
    }
    
    return $pageList;
    
}

function smarty_function_menu_setdepth(&$pageList,$depth,$currentDepth=1) {
    
    $thisDepth = $currentDepth;
    
    foreach($pageList as &$page) {
        
        if($currentDepth >= $depth) {
            
            unset($page['children']);
            
        } else {
            
            if(!empty($page['children'])) {
                
                $currentDepth++;
                smarty_function_menu_setdepth($page['children'],$depth,$currentDepth);
                
            }
            
        }
        
        $currentDepth = $thisDepth;
        
    }
    
    return $pageList;
    
}

?>
