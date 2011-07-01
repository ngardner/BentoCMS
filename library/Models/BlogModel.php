<?php

class BlogModel extends Model {
	
	var $totalArticles; // count of total articles based on last select (including filters) - for pagination
	
	function __construct() {
		
		parent::__construct();
		
	}
	
	function saveCategory($data) {
		
		$objSettings = Settings::getInstance();
		$urlPrefix = $objSettings->getEntry('blog','category-url');
		$objUrl = new FriendlyurlModel;
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['title'],'blog_categories');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blog_categories',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blog_categories');
				
			}
			
		}
		
		//friendly url
		if(empty($data['url'])) {
			
			$prettyUrl = $urlPrefix.$data['keyName'];
			
		} else {
			
			$prettyUrl = $data['url'];
			
		}
		
		unset($data['url']);
		
		$id = $this->save($data,'blog_categories');
		$url_id = $objUrl->saveUrl($prettyUrl,'Blog','Category',array('category_id'=>$id));
		
		if(!empty($url_id)) {
			
			$saveData['id'] = $id;
			$saveData['url_id'] = $url_id;
			$this->save($saveData,'blog_categories');
			
		}
		
		return $id;
		
	}
	
	function loadCategory($category_id) {
		
		$objUrl = new FriendlyurlModel;
		$category =  $this->load($category_id,'blog_categories');
		
		if($category) {
			
			$category['url'] = $objUrl->getUrl($category['url_id']);
			
		}
		
		return $category;
		
	}
	
	function getCategories() {
		
		$objUrl = new FriendlyurlModel;
		
		$tree=array();
		
		$sql = "
		SELECT
			c.`id`,
			c.`title`,
			c.`keyName`,
			c.`parent_id`,
			c.`displayOrder`,
			count(a.`id`) as 'article_count',
			c.`url_id`
		FROM
			`blog_categories` as c
		LEFT JOIN
			`blog_articles` as a ON c.`id` = a.`category_id` AND
			a.`status` = 'published'
		GROUP BY
			c.`id`
		ORDER BY
			c.`displayOrder`,c.`title`
		";
		
		$resultsTemp = $this->db->getAll($sql);
		
		if(!empty($resultsTemp)) {
			
			foreach($resultsTemp as $result) {
				
				
				$result['url'] = $objUrl->getUrl($result['url_id']);
				
				$results[$result['id']] = $result;
				
			}
			
			foreach($results as $id=>&$category) {
				
				if($category['parent_id'] == 0) {
					
					$tree[$id] = &$category;
					
				} else {
					
					if(!isset($results[$category['parent_id']]['children'])) {
						
						$results[$category['parent_id']]['children'] = array();
						
					}
					
					$results[$category['parent_id']]['children'][$id] = &$category;
					
				}
				
			}
			
		}
		
		return $tree;
		
	}
	
	function deleteCategory($category_id) {
		
		$category_id = intval($category_id);
		$this->db->delete('blog_categories',$category_id);
		return true;
		
	}
	
	function getCategoryId($keyName) {
		
		return $this->db->getOne("SELECT `id` FROM `blog_categories` WHERE `keyName` = '".$this->db->makeSafe($keyName)."'");
		
	}
	
	function saveSortOrder($sortOrder) {
		
		foreach($sortOrder as $order=>$category_id) {
			
			$this->db->reset();
			$this->db->assign('displayOrder',intval($order));
			$this->db->update('blog_categories',"`id`=".intval($category_id));
			
		}
		
		return true;
		
	}
	
	function saveArticle($data) {
		
		$objSettings = Settings::getInstance();
		$urlPrefix = $objSettings->getEntry('blog','article-url');
		$objUrl = new FriendlyurlModel;
		
		//identifier
		if(empty($data['keyName'])) {
			
			// generate new one
			$data['keyName'] = $this->generateKeyName($data['title'],'blog_articles');
			
		} else {
			
			if(!empty($data['id'])) {
				
				// make sure entered keyname is valid and unique
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blog_articles',$data['id']);
				
			} else {
				
				// generate new one
				$data['keyName'] = $this->generateKeyName($data['keyName'],'blog_articles');
				
			}
			
		}
		
		//friendly url
		if(empty($data['url'])) {
			
			$prettyUrl = $urlPrefix.$data['keyName'];
			
		} else {
			
			$prettyUrl = $data['url'];
			
		}
		
		$metaData = $data['meta'];
		unset($data['url']);
		unset($data['meta']);
		
		$id = $this->save($data,'blog_articles');
		$url_id = $objUrl->saveUrl($prettyUrl,'Blog','Article',array('article_id'=>$id), $metaData);
		
		if(!empty($url_id)) {
			
			$saveData['id'] = $id;
			$saveData['url_id'] = $url_id;
			$this->save($saveData,'blog_articles');
			
		}
		
		return $id;
		
	}
	
	function loadArticle($article_id) {
		
		$article = $this->load($article_id,'blog_articles');
		$objUrl = new FriendlyurlModel;
		
		if(!empty($article)) {
			
			$article['tags'] = $this->getArticleTags($article['id']);
			$article['url'] = $objUrl->getUrl($article['url_id']);
			$article['category'] = $this->db->getOne("SELECT `title` FROM `blog_categories` WHERE `id` = ".intval($article['category_id']));
			$article['meta'] = $objUrl->getMetaData($article['url_id']);
			
		}
		
		return $article;
		
	}
	
	function getArticles($filters='') {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('blog','per-page');
		$objUrl = new FriendlyurlModel;
		
		$start = 0;
		$howMany = $perPage;
		$articleSelect = 'b.`article`';
		$whereClause = '';
		
		if(is_array($filters)) {
			
			if(!empty($filters['howMany'])) {
				
				$howMany = intval($filter['howMany']);
				$perPage = $howMany;
				
			}
			
			if(!empty($filters['status'])) {
				
				$whereClause .= "`status` = '".$this->db->makeSafe($filters['status'])."' AND ";
				
			}
			
			if(!empty($filters['category_id'])) {
				
				$whereClause .= "(`category_id` = '".$this->db->makeSafe($filters['category_id'])."' OR `parent_id` = '".$this->db->makeSafe($filters['category_id'])."') AND ";
				
			}
			
			if(!empty($filters['id'])) {
				
				$whereClause .= "`id` = '".$this->db->makeSafe($filters['id'])."' AND ";
				
			}
			
			if(!empty($filters['page'])) {
				
				$start = intval($perPage*intval($filters['page'])-$perPage);
				
			} else {
				
				$start = 0;
				
			}
			
			// if limit is set, override page filter
			if(!empty($filters['limit'])) {
				
				$start=0;
				$howMany = intval($filters['limit']);
				
			}
			
			if(!empty($filters['preview'])) {
				
				$articleSelect = "LEFT(b.`article`,100) as 'article'";
				
			}
			
			if(!empty($filters['year'])) {
				
				$year = intval($filters['year']);
				$whereClause .= "YEAR(b.`publishDate`) = ".$year." AND ";
				
			}
			
			if(!empty($filters['month'])) {
				
				$month = intval($filters['month']);
				$whereClause .= "MONTH(b.`publishDate`) = ".$month." AND ";
				
			}
			
			if(!empty($filters['nolimit'])) {
				
				$start = 0;
				$howMany = 0;
				
			}
			
		}
		
		if(empty($whereClause)) {
			
			$whereClause  = '1=1 AND ';
			
		}
		
		$whereClause = substr($whereClause,0,-5);
		
		if($howMany) {
			$limitClause = $start.','.$howMany;
		} else {
			$limitClause = '1000000';
		}
		
		$sql = "
		SELECT
			SQL_CALC_FOUND_ROWS
			b.`id`,
			b.`title`,
			b.`keyName` as 'identifier',
			b.`keyName`,
			".$articleSelect.",
			b.`author_id`,
			b.`category_id`,
			b.`publishDate`,
			b.`status`,
			c.`title` as 'category',
			concat(a.`fName`,' ',a.`lName`) as 'author',
			b.`url_id`
		FROM
			`blog_articles` as b
		LEFT JOIN
			`blog_categories` as c ON b.`category_id` = c.`id`
		LEFT JOIN
			`staff` as a ON b.`author_id` = a.`id`
		WHERE
			".$whereClause."
		ORDER BY
			`publishDate` DESC
		LIMIT
			".$limitClause."
		";
		
		$articles = $this->db->getAll($sql);
		$this->totalArticles = $this->db->getOne("SELECT FOUND_ROWS()");
		
		foreach($articles as &$article) {
			
			$article['url'] = $objUrl->getUrl($article['url_id']);
			$article['author'] = $this->getAuthor($article['author_id']);
			
		}
		
		return $articles;
		
	}
	
	function deleteArticle($article_id) {
		
		$article_id = intval($article_id);
		$this->db->delete('blog_articles',$article_id);
		return true;
		
	}
	
	function getArticleId($keyName) {
		
		return $this->db->getOne("SELECT `id` FROM `blog_articles` WHERE `keyName` = '".$this->db->makeSafe($keyName)."'");
		
	}
	
	function safeToDeleteCategory($id) {
		
		$hasChildren = $this->db->getOne("SELECT `id` FROM `blog_categories` WHERE `parent_id` = ".intval($id));
		
		if($hasChildren) {
			
			return false;
			
		} else {
			
			return true;
			
		}
		
	}
	
	function safeToDeleteArticle($id) {
		
		return true;
		
	}
	
	function getAuthor($author_id) {
		
		$sql = "
		SELECT
			`email`,
			`fName`,
			`lName`,
			`title`,
			concat(`fname`,' ',`lname`) as 'fullName'
		FROM
			`users`
		WHERE
			`id` = ".intval($author_id)."
		";
		
		return $this->db->getRow($sql);
		
	}
	
	function getNextArticle($category_id,$timestamp) {
		
		$sql = "
		SELECT
			`id`,
			`title`,
			`url_id`
		FROM
			`blog_articles`
		WHERE
			`publishDate` > '".$this->db->makeSafe($timestamp)."' AND
			`category_id` = ".intval($category_id)."
		ORDER BY
			`publishDate`
		LIMIT
			1
		";
		
		$nextArticle = $this->db->getRow($sql);
		
		if(!empty($nextArticle)) {
			
			$objUrl = new FriendlyurlModel;
			$nextArticle['url'] = $objUrl->getUrl($nextArticle['url_id']);
			
		}
		
		return $nextArticle;
		
	}
	
	function getPrevArticle($category_id,$timestamp) {
		
		$sql = "
		SELECT
			`id`,
			`title`,
			`url_id`
		FROM
			`blog_articles`
		WHERE
			`publishDate` < '".$this->db->makeSafe($timestamp)."' AND
			`category_id` = ".intval($category_id)."
		ORDER BY
			`publishDate` DESC
		LIMIT
			1
		";
		
		$prevArticle = $this->db->getRow($sql);
		
		if(!empty($prevArticle)) {
			
			$objUrl = new FriendlyurlModel;
			$prevArticle['url'] = $objUrl->getUrl($prevArticle['url_id']);
			
		}
		
		return $prevArticle;
		
	}
	
	function getDefaultCategoryLayout() {
		
		$objSettings = Settings::getInstance();
		$defaultLayout = $objSettings->getEntry('blog','category-layout');
		
		if(!empty($defaultLayout)) {
			
			return $this->db->getOne("SELECT `id` FROM `layouts` WHERE `title` = '".$this->db->makeSafe($defaultLayout)."'");
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getDefaultArticleLayout() {
		
		$objSettings = Settings::getInstance();
		$defaultLayout = $objSettings->getEntry('blog','article-layout');
		
		if(!empty($defaultLayout)) {
			
			return $this->db->getOne("SELECT `id` FROM `layouts` WHERE `title` = '".$this->db->makeSafe($defaultLayout)."'");
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getComments($filters = '') {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('blog','per-page');
		
		$start = 0;
		$howMany = $perPage;
		
		if(!empty($filters)) {
			
			$whereClause = '';
			$limitClause = '';
			
			if(!empty($filters['article_id'])) {
				
				$whereClause .= "blog_comments.article_id = ".intval($filters['article_id'])." AND ";
				
			}
			
			if(!empty($filters['status'])) {
				
				$whereClause .= "blog_comments.status = '".$this->db->makeSafe($filters['status'])."' AND ";
				
			}
			
			if(!empty($filters['page'])) {
				
				$start = intval($perPage*intval($filters['page'])-$perPage);
				
			} else {
				
				$start = 0;
				
			}
			
			// if limit is set, override page filter
			if(!empty($filters['limit'])) {
				
				$start=0;
				$howMany = intval($filters['limit']);
				
			}
			
		} else {
			
			$whereClause  = '1=1 AND ';
			
		}
		
		$whereClause = substr($whereClause,0,-5);
		$limitClause = $start.','.$howMany;
		
		$sql = "
		SELECT 
			blog_comments.*, 
			users.fName, 
			users.lName,
			users.email,
			blog_articles.title
		FROM 
			`blog_comments` 
		INNER JOIN 
			`users` ON blog_comments.user_id = users.id 
		INNER JOIN
			`blog_articles` ON blog_articles.id = blog_comments.article_id
		WHERE 
			".$whereClause."
		ORDER BY
			`cDate` DESC
		LIMIT
			".$limitClause."
		";
		
		$comments = $this->db->getAll($sql);
		
		return $comments;		
		
	}
	
	function saveComment($data) {
		
		$id = $this->save($data,'blog_comments');
		
		return $id;
		
	}
	
	function deleteComment($comment_id) {
		
		$comment_id = intval($comment_id);
		$this->db->delete('blog_comments',$comment_id);
		return true;
		
	}
	
}
