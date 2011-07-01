<?php
/**
 * Smarty {blog_archive} block plugin
 *
 * Type:     block<br>
 * Name:     blog_archive<br>
 * Purpose:  Loads in a blog articles from the database<br>
 * Useage:
 * {blog_archive varname='archive' category='test' year='2010'}
 *  ...
 * {/blog_articles}
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_block_blog_archive(&$params, $content, &$smarty, &$repeat) {
    
    if($repeat) {
        
        $objBlog = new BlogModel;
        $months = array();
        
        $filters = array();
        $filters['nolimit'] = true;
        if(!empty($params['year'])) { $filters['year'] = $params['year']; }
        if(!empty($params['category'])) {
            $filters['category_id'] = $objBlog->getCategoryId($params['category']);
        }
        
        $allArticles = $objBlog->getArticles($filters);
        
        if(!empty($allArticles)) {
            
            foreach($allArticles as $article) {
                
                $year = substr($article['publishDate'],0,4);
                $month = substr($article['publishDate'],5,2);
                
                if($params['yearly']) {
                    
                    $index = $year;
                    
                } else {
                    
                    $index = $year.$month;
                    
                }
                
                if(empty($months[$index])) {
                    
                    $months[$index] = array('month'=>$month,'year'=>$year,'count'=>1);
                    
                } else {
                    
                    $months[$index]['count'] = $months[$index]['count']+1;
                    
                }
                
            }
            
        }
        
        $params['data'] = $months;
        $repeat = count($months);
        
    }
    
    
    if(is_array($params['data'])) {
        
        if($month = array_shift($params['data'])) {
            
            $smarty->assign($params['varname'],$month);
            
        }
        
    }
    
    if(empty($month)) {
        $repeat = false;
    } else {
        $repeat = true;
    }
    
    return $content;
    
}

?>
