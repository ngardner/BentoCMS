<?php
/**
 * Smarty {url} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * Purpose:  returns the friendlyurl<br>
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_function_url($params, &$smarty) {
    
    $objUrl = new FriendlyurlModel;
    
    if(!empty($params['identifier'])) {
        
        if(!empty($params['type'])) {
            
            switch($params['type']) {
                
                case 'page':
                    $table = 'pages';
                break;
                
                case 'blog-category':
                    $table = 'blog_categories';
                break;
                
                case 'blog-article':
                    $table = 'blog_articles';
                break;
                
                default:
                    $table = 'pages';
                break;
                
            }
            
        } else {
            
            $table = 'pages';
            
        }
        
        $url = $objUrl->findUrl($table,$params['identifier']);
        return $url;
        
    } else {
        
        return 'Must pass an identifier';
        
    }
    
}

?>
