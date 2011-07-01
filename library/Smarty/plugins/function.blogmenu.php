<?php
/**
 * Smarty {blogmenu} function plugin
 *
 * Type:     function<br>
 * Name:     blogmenu<br>
 * Purpose:  Loads in the blogs menu<br>
 * @author Nathan Gardner <nathan@factory8.com>
 */

$__catListTemp = array();

function smarty_function_blogmenu($params, &$smarty) {
    
    $output = '';
    $objBlog = new BlogModel;
    $categoryList = $objBlog->getCategories();
    
    global $__catListTemp;
    
    if(!empty($categoryList)) {
        
        // limit to a parent
        if(!empty($params['parent'])) {
            
            // find parent
            $parent_id = $objBlog->getCategoryId($params['parent']);
            
            if($parent_id) {
                
                smarty_function_blogmenu_parent($categoryList,$parent_id);
                $categoryList = $__catListTemp;
                
            }
            
        }
        
        reset($categoryList);
        
        // draw menu
        $output = smarty_function_blogmenu_makemenu($categoryList,$output,$smarty);
        
    }
    
    return $output;
    
}

function smarty_function_blogmenu_makemenu($categoryList,&$output='',&$smarty) {
    
    $output .= '<ul class="blogcat">';
    
    foreach($categoryList as $category) {
        
        $output .= '<li>';
        
        $output .= '<a href="'.$category['url'].'">'.$category['title'].'</a>';
        
        if(!empty($category['children'])) {
            
            smarty_function_blogmenu_makemenu($category['children'],$output,$smarty);
            
        }
        
        $output .= '</li>';
        
    }
    
    $output .= '</ul>';
    
    return $output;
    
}

function smarty_function_blogmenu_findtopparent($categoryList,$parent_id) {
    
    
    
}

function smarty_function_blogmenu_parent($categoryListNew,$parent_id) {
    
    global $__catListTemp;
    
    foreach($categoryListNew as $cat_id=>$category) {
        
        if($parent_id == $cat_id) {
            
            $__catListTemp = array($categoryListNew[$parent_id]);
            return;
            
        }
        
        if(!empty($category['children'])) {
            
            if(array_key_exists($parent_id,$category['children'])) {
                
                $__catListTemp = array($category);
                return;
                
            } else {
                
                smarty_function_blogmenu_parent($category['children'],$parent_id);
                
            }
            
        }
        
    }
    
}


//function smarty_function_blogmenu_parent($categoryListNew,$parent_id) {
//    
//    global $__catListTemp;
//    
//    foreach($categoryListNew as $cat_id=>$category) {
//        
//        if($parent_id == $cat_id) {
//            
//            if(!empty($category['children'])) {
//                
//                $__catListTemp = $category['children'];
//                
//            } else {
//                
//                $__catListTemp = array();
//                
//            }
//            
//        } else {
//            
//            if(!empty($category['children'])) {
//                
//                smarty_function_blogmenu_parent($category['children'],$parent_id);
//                
//            }
//            
//        }
//        
//    }
//    
//}

?>
