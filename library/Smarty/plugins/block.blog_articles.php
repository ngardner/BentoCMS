<?php
/**
 * Smarty {blog_articles} block plugin
 *
 * Type:     block<br>
 * Name:     blog_articles<br>
 * Purpose:  Loads in a blog articles from the database<br>
 * Useage:
 * {blog_articles varname='myblog' category='test'}
 *  {$myblog.title}<br/>
 *  {$myblog.article}<br/>
 * {/blog_articles}
 * @author Nathan Gardner <nathan@factory8.com>
 */

function smarty_block_blog_articles(&$params, $content, &$smarty, &$repeat) {
    
    if($repeat) {
        
        $objBlog = new BlogModel;
        
        if(!empty($params['identifier'])) {
            
            // pull just one article
            $filters = array();
            $filters['id'] = $objBlog->getArticleId($params['identifier']);
            
        } else {
            
            $filters = array();
            $filters['status'] = 'published';
            
            // limit to category
            if(!empty($params['category'])) {
                
                $filters['category_id'] = $objBlog->getCategoryId($params['category']);
                
            }
            
            
            // limit amount returned
            if(!empty($params['limit'])) {
                
                $filters['limit'] = intval($params['limit']);
                
            }
            
            // preview flag
            if(!empty($params['preview'])) {
                
                $filters['preview'] = true;
                
            }
            
        }
        
        $params['articles'] = $objBlog->getArticles($filters);
        $repeat = $params['articles'];
        
    }
    
    
    if(is_array($params['articles'])) {
        
        if($article = array_shift($params['articles'])) {
            
            $smarty->assign($params['varname'],$article);
            
        }
        
    }
    
    if(empty($article)) {
        $repeat = false;
    } else {
        $repeat = true;
    }
    
    return $content;
    
}

?>
