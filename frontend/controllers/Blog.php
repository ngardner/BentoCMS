<?php
/**
 * Blog controller
 */
class Blog extends Controller {
	
	var $view;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function setLayout() {
		
		$this->layout = 'blog.tpl';
		
	}
	
	function actionIndex($params='') {
		
		$this->view->assign('content','No "index" for blog, please visit a category or article.');
		$this->finish();
		
	}
	
	function actionCategory($params='') {
		
		$objBlog = new BlogModel;
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		$objSettings = Settings::getInstance();
		
		$category_id = !empty($params['category_id'])?intval($params['category_id']):0;
		$page_numb = !empty($params['page_numb'])?intval($params['page_numb']):1;
		$listView = !empty($params['listView'])?true:false;
		$yearFilter = !empty($params['year'])?$params['year']:false;
		$monthFilter = !empty($params['year'])?$params['year']:false;
		
		$categoryInfo = $objBlog->loadCategory($category_id);
		
		if(!empty($categoryInfo)) {
			
			// load additional info
			$layoutInfo = $objLayout->loadLayout($categoryInfo['layout_id']);
			$per_page = $objSettings->getEntry('blog','per-page');
			
			if($listView) {
				
				$template = $objTemplate->loadTemplateFromKeyname('blog-articles-list');
				
			} else {
				
				$template = $objTemplate->loadTemplateFromKeyname('blog-articles');
				
			}
			
			// get categories articles
			$filters = array();
			$filters['status'] = 'published';
			$filters['category_id'] = $category_id;
			$filters['page'] = $page_numb;
			$filters['year'] = intval($yearFilter);
			$filters['month'] = intval($monthFilter);
			
			$blogArticles = $objBlog->getArticles($filters);
			$totalArticles = $objBlog->totalArticles;
			
			if(isset($params['rss'])) {
				
				header('Content-Type: text/xml');
				
				$rss = new SimpleXMLElement('<rss></rss>');
				$rss->addAttribute('version', '0.92');
				$channel = $rss->addChild('channel');
				$channel->addChild('title', $categoryInfo['title']);
				$channel->addChild('link', 'http://'.URL.$categoryInfo['url']);
				$channel->addChild('description', $categoryInfo['keyName']);
				foreach($blogArticles as $article) {
					
					$item = $channel->addChild('item');
					$item->addChild('title', htmlspecialchars($article['title']));
					$item->addChild('description', $article['article']);
					$item->addChild('link', 'http://'.URL.$article['url']);
					$item->addChild('pubDate', date('r', strtotime($article['publishDate'])));
					
				}
				echo $rss->asXml();
				return;
				
			}
			
			// used to set active state in menu
			$this->view->current_blog_category = $categoryInfo['keyName'];
			
			//assign vars for template
			$this->view->assign('categoryInfo', $categoryInfo);
			$this->view->assign('pageTitle',$categoryInfo['title']);
			$this->view->assign('articles',$blogArticles);
			$this->view->assign('totalArticles',$totalArticles);
			$this->view->assign('page_numb',$page_numb);
			$this->view->assign('per_page',$per_page);
			$this->view->assign('totalPages',ceil($totalArticles/$per_page));
			$this->view->assign('blogCategories',$objBlog->getCategories());
			$this->view->assign('currentCategoryIdentifier',$categoryInfo['keyName']);
			$this->view->assign('year',$yearFilter);
			$this->view->assign('month',$monthFilter);
			
			// render template
			$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
			$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar'] . ' ' . $categoryInfo['leftsidebar']));
			$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar'] . ' ' . $categoryInfo['rightsidebar']));
			
			// render layout
			$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
			
		} else {
			
			// page not found
			$this->view->assign('layout','404');
			
		}
		
		$this->finish();
		
	}
	
	function actionArticle($params='') {
		
		$objBlog = new BlogModel;
		$objLayout = new LayoutModel;
		$objTemplate = new TemplatesModel;
		
		$article_id = !empty($params['article_id'])?intval($params['article_id']):0;
		
		$articleInfo = $objBlog->loadArticle($article_id);
		$articleAuthor = $objBlog->getAuthor($articleInfo['author_id']);
		$articleTags = $objBlog->getArticleTags($article_id);
		
		$filters = array();
		$filters['article_id'] = intval($params['article_id']);
		if(empty($params['preview'])) {
			$filters['status'] = 'approved';
		}
		
		$articleComments = $objBlog->getComments($filters);
		
		$categoryInfo = $objBlog->loadCategory($articleInfo['category_id']);
		
		$nextArticle = $objBlog->getNextArticle($articleInfo['category_id'],$articleInfo['publishDate']);
		$prevArticle = $objBlog->getPrevArticle($articleInfo['category_id'],$articleInfo['publishDate']);
		
		if(!empty($articleInfo)) {
			
			// load additional info
			$layoutInfo = $objLayout->loadLayout($articleInfo['layout_id']);
			$template = $objTemplate->loadTemplateFromKeyname('blog-article');
			
			// used to set active state in menu
			$this->view->current_blog_article = $articleInfo['keyName'];
			
			//assign template vars
			$this->view->assign('pageTitle',$articleInfo['title']);
			$this->view->assign('articleInfo',$articleInfo);
			$this->view->assign('articleTags',$articleTags);
			$this->view->assign('articleAuthor',$articleAuthor);
			$this->view->assign('articleComments',$articleComments);
			$this->view->assign('blogCategories',$objBlog->getCategories());
			$this->view->assign('currentCategoryIdentifier',$categoryInfo['keyName']);
			$this->view->assign('nextArticle',$nextArticle);
			$this->view->assign('prevArticle',$prevArticle);
			
			if(!empty($params['commentSubmitted'])) {
				$this->view->assign('commentSubmitted', $params['commentSubmitted']);
			}
			// render template
			$this->view->assign('content',$this->view->fetch('fromstring:'.$template['content']));
			$this->view->assign('sidebar_left',$this->view->fetch('fromstring:'.$template['left_sidebar'] . ' ' . $categoryInfo['leftsidebar']));
			$this->view->assign('sidebar_right',$this->view->fetch('fromstring:'.$template['right_sidebar'] . ' ' . $categoryInfo['rightsidebar']));
			
			// render layout
			$this->view->assign('layout',$this->view->fetch('fromstring:'.$layoutInfo['code']));
			
		} else {
			
			// page not found
			$this->view->assign('layout','404');
			
		}
		
		$this->finish();
		
	}
	
	function actionComment($params = '') {
		
		if(!empty($params['article_id'])) {
		
			$objBlog = new BlogModel();
			$saveData = array();
			$saveData['article_id'] = $params['article_id'];
			$saveData['user_id'] = $params['user_id'];
			$saveData['comment'] = $params['comment'];
			if(!empty($params['anonymous'])) {
				
				$saveData['name'] = 'Anonymous';
				
			} else {
				
				$saveData['name'] = $params['name'];
				
			}
			$saveData['status'] = 'pending';
			
			$comment = $objBlog->saveComment($saveData);
			
			if($comment) {
				
				$articleInfo = $objBlog->loadArticle($params['article_id']);
				header("Location: http://".URL.'/'.$articleInfo['url'].'?commentSubmitted=true#comment');
				
			} else {
				
				header("Location: http://".URL.'/');
				
			}
			
		}
		
	}
	
}

?>
