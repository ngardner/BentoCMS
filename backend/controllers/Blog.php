<?php
/**
 * Blog backend controller
 */
class Blog extends Controller {
	
	var $view;
	var $messages;
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function actionIndex($params='') {
		
		$this->view->assign('content', 'dunno what goes here.');
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCategories($params='') {
		
		$objBlog = new BlogModel;
		$categoryList = $objBlog->getCategories();
		$this->view->assign('categoryList',$categoryList);
		$this->view->assign('content', $this->view->fetch('tpl/blog/categories.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEditCategory($params='') {
		
		$objBlog = new BlogModel;
		$objLayouts = new LayoutModel;
		
		$category_id = !empty($params['category_id'])?intval($params['category_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$saveData = array();
			$saveData['id'] = !empty($params['category_id'])?intval($params['category_id']):false;
			$saveData['title'] = !empty($params['category_title'])?$params['category_title']:'Unnamed';
			$saveData['keyName'] = !empty($params['category_keyName'])?$params['category_keyName']:'';
			$saveData['parent_id'] = !empty($params['category_parent_id'])?intval($params['category_parent_id']):0;
			$saveData['displayOrder'] = isset($params['category_displayOrder'])?intval($params['category_displayOrder']):1000;
			$saveData['layout_id'] = !empty($params['category_layout_id'])?intval($params['category_layout_id']):false;
			$saveData['url'] = !empty($params['category_url'])?$params['category_url']:false;
			$saveData['leftsidebar'] = !empty($params['category_leftsidebar'])?$params['category_leftsidebar']:false;
			$saveData['rightsidebar'] = !empty($params['category_rightsidebar'])?$params['category_rightsidebar']:false;
			
			$category_id = $objBlog->saveCategory($saveData);
			
			if(!empty($params['ajaxsave'])) {
				
				$categoryInfo = $objBlog->loadCategory($category_id);
				echo json_encode($categoryInfo);
				return;
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Blog Category has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionCategories();
				return;
				
			}
			
		}
		
		$categoryList = $objBlog->getCategories();
		$this->view->assign('categoryList',$categoryList);
		
		if(!empty($category_id)) {
			
			$categoryInfo = $objBlog->loadCategory($category_id);
			
		} else {
			
			$categoryInfo['layout_id'] = $objBlog->getDefaultCategoryLayout();
			
		}
		
		$this->view->assign('categoryInfo',$categoryInfo);
		
		$layouts = $objLayouts->getLayouts();
		$this->view->assign('layouts',$layouts);
		
		$this->view->assign('content',$this->view->fetch('tpl/blog/category.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateCategory($params='') {
		
		$this->actionEditCategory($params);
		
	}
	
	function actionDeleteCategory($params='') {
		
		if(!empty($params['category_id'])) {
			
			$objBlog = new BlogModel;
			
			if($objBlog->safeToDeleteCategory($params['category_id'])) {
				
				$objBlog->deleteCategory($params['category_id']);
				
				$this->messages[] = array('type'=>'success','message'=>'Blog Category has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Cannot delete a category that has sub categories.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown category to delete.');
			
		}
		
		$this->actionCategories();
		
	}
	
	function actionSaveCategoryOrder($params='') {
		
		if(!empty($params['order'])) {
			
			$categoryOrder = explode(',',$params['order']);
			
			$sortOrder = array();
			
			foreach($categoryOrder as $order) {
				
				$sortOrder[] = substr($order,9);
				
			}
			
			$objBlog = new BlogModel;
			$objBlog->saveSortOrder($sortOrder);
			
		}
		
	}
	
	function actionArticles($params='') {
		
		$objBlog = new BlogModel;
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('blog','per-page-admin');
		$pageNumb = !empty($params['pageNumb'])?$params['pageNumb']:1;
		$categoryId = !empty($params['category_id'])?$params['category_id']:false;
		
		$articleList = $objBlog->getArticles(array('page'=>$pageNumb,'category_id'=>$categoryId,'howMany'=>$perPage));
		$totalPages = ceil($objBlog->totalArticles/$perPage);
		$categoryList = $objBlog->getCategories();
		
		$this->view->assign('currentCategory',$categoryId);
		$this->view->assign('articleList',$articleList);
		$this->view->assign('totalPages',$totalPages);
		$this->view->assign('pageNumb',$pageNumb);
		$this->view->assign('categoryList',$categoryList);
		$this->view->assign('content', $this->view->fetch('tpl/blog/articles.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionEditArticle($params='') {
		
		$objBlog = new BlogModel;
		
		$article_id = !empty($params['article_id'])?intval($params['article_id']):false;
		
		if(!empty($params['dosave'])) {
			
			$saveData = array();
			$saveData['id'] = !empty($params['article_id'])?intval($params['article_id']):false;
			$saveData['title'] = !empty($params['article_title'])?$params['article_title']:'Unnamed';
			$saveData['keyName'] = !empty($params['article_keyName'])?$params['article_keyName']:'';
			$saveData['article'] = !empty($params['article_article'])?$params['article_article']:'';
			$saveData['author_id'] = !empty($params['article_author_id'])?intval($params['article_author_id']):false;
			$saveData['category_id'] = !empty($params['article_category_id'])?intval($params['article_category_id']):'';
			$saveData['publishDate'] = !empty($params['article_publishDate'])?date("Y-m-d H:i:s",strtotime($params['article_publishDate'])):date("Y-m-d H:i:s");
			$saveData['allow_comments'] = !empty($params['article_allow_comments'])?intval($params['article_allow_comments']):false;
			$saveData['layout_id'] = !empty($params['article_layout_id'])?intval($params['article_layout_id']):false;
			$saveData['status'] = !empty($params['article_status'])?$params['article_status']:'draft';
			$saveData['url'] = !empty($params['article_url'])?$params['article_url']:false;
			$saveData['meta']['title'] = !empty($params['meta_title'])?$params['meta_title']:'';
			$saveData['meta']['description'] = !empty($params['meta_description'])?$params['meta_description']:'';
			$saveData['meta']['keywords'] = !empty($params['meta_keywords'])?$params['meta_keywords']:'';
			
			$article_id = $objBlog->saveArticle($saveData);
			
			if(!empty($params['ajaxsave'])) {
				
				$articleInfo = $objBlog->loadArticle($article_id);
				echo json_encode($articleInfo);
				return;
				
			}
			
			$this->messages[] = array('type'=>'success','message'=>'Blog article has been saved.');
			
			if($params['submit'] == 'Save and Close') {
				
				$this->actionArticles();
				return;
				
			}
			
		}
		
		$categoryList = $objBlog->getCategories();
		$this->view->assign('categoryList',$categoryList);
		
		$objUsers = new UserModel;
		$this->view->assign('userList',$objUsers->getUsers('admin'));
		
		$objLayouts = new LayoutModel;
		$layouts = $objLayouts->getLayouts();
		$this->view->assign('layouts',$layouts);
		
		if(!empty($article_id)) {
			
			$articleInfo = $objBlog->loadArticle($article_id);
			
		} else {
			
			$articleInfo['layout_id'] = $objBlog->getDefaultArticleLayout();
			
		}
		
		$this->view->assign('articleInfo',$articleInfo);
		$this->view->assign('content',$this->view->fetch('tpl/blog/article.tpl'));
		$this->view->assign('messages',$this->messages);
		$this->finish();
		
	}
	
	function actionCreateArticle($params='') {
		
		$this->actionEditArticle($params);
		
	}
	
	function actionDeleteArticle($params='') {
		
		if(!empty($params['article_id'])) {
			
			$objBlog = new BlogModel;
			
			if($objBlog->safeToDeleteArticle($params['article_id'])) {
				
				$objBlog->deleteArticle($params['article_id']);
				
				$this->messages[] = array('type'=>'success','message'=>'Blog article has been deleted.');
				
			} else {
				
				$this->messages[] = array('type'=>'error','message'=>'Cannot delete article.');
				
			}
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown article to delete.');
			
		}
		
		$this->actionArticles();
		
	}
	
	function actionComments($params='') {
		
		$objBlog = new BlogModel();
		$filters = array();
		
		if(!empty($params['status'])) {
			$filters['status'] = $params['status'];
		}
		
		if(!empty($params['article_id'])) {
			$filters['article_id'] = $params['article_id'];
		}
		
		$commentList = $objBlog->getComments($filters);
		$this->view->assign('commentList', $commentList);
		$this->view->assign('content', $this->view->fetch('tpl/blog/comments.tpl'));
		$this->finish();
		
	}
	
	function actionDeleteComment($params='') {
		
		if(!empty($params['comment_id'])) {
			
			$objBlog = new BlogModel;
				
			$objBlog->deleteComment($params['comment_id']);
				
			$this->messages[] = array('type'=>'success','message'=>'Blog comment has been deleted.');
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown comment to delete.');
			
		}
		
		$this->actionComments();
		
	}
	
	function actionUpdateStatus($params = '') {
		
		if(!empty($params['comment_id'])) {
			
			$objBlog = new BlogModel;
			
			$data = array();
			$data['id'] = $params['comment_id'];
			$data['status'] = $params['status'];
			
			$objBlog->saveComment($data);
			
			return;
			
		} else {
			
			$this->messages[] = array('type'=>'error','message'=>'Unknown comment to save.');
			
		}
		
		$this->actionArticles();
		
	}
	
	function actionHelp($params='') {
		
		$this->view->assign('content',$this->view->fetch('tpl/blog/help.tpl'));
		$this->finish();
		
	}
	
}

?>
