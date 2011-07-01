<?php

class ForumModel extends Model {
	
	public $totalSearchResults;
	
	function __construct() {
		
		parent::__construct();
		$this->objAuthentication = Authentication::getInstance();
		
	}
	
	function addPost($data) {
		
		
		$this->db->reset();
		$this->db->assign('userid', intval($this->objAuthentication->user_id));
		$this->db->assign('catid', intval($data['catid']));
		$this->db->assign_str('topic', $data['topic']);
		$this->db->assign_str('message', htmlentities($data['message']));
			
		$result = $this->db->insert('mb_posts');
		
		return $result;
		
	}
	
	function addReply($data) {
		
		$this->db->reset();
		$this->db->assign('userid', intval($this->objAuthentication->user_id));
		$this->db->assign('postid', intval($data['postid']));
		$this->db->assign_str('topic', $data['topic']);
		$this->db->assign_str('message', htmlentities($data['message']));
			
		$result = $this->db->insert('mb_replies');
		
		return $result;
		
	}
	
	function toggleSticky($postId) {
		
		$sql = "
		SELECT
			sticky
		FROM
			mb_posts
		WHERE
			id=".$postId."
		";
		
		$isSticky = $this->db->getOne($sql);
		
		
		if($isSticky) {
			
			$sticky = 0;
			
		} else {
			
			$sticky = 1;
			
		}
		
		$this->db->reset();
		$this->db->assign('sticky', intval($sticky));
		$result = $this->db->update('mb_posts', '`id` = '.$postId);
		
		return $sticky;
		
	}
	
	function toggleLock($postId) {
		
		$sql = "SELECT `locked` FROM `mb_posts` WHERE `id` = ".$postId;
		
		$isLocked = $this->db->getOne($sql);
		
		if($isLocked) {
			
			$lock = 0;
			
		} else {
			
			$lock = 1;
			
		}
		
		$this->db->reset();
		$this->db->assign('locked', intval($lock));
		$result = $this->db->update('mb_posts', '`id` = '.$postId);
		
		return $lock;
		
	}
	
	function isThreadLocked($threadId) {
		
		$sql = "SELECT `locked` FROM `mb_posts` WHERE `id` = ".$threadId;
		
		$results = $this->db->getOne($sql);
		
		return $results;
		
	}
	
	function removeThread($threadId) {
		
		$this->db->deactivate('mb_posts', $threadId);
		
		return true;
		
	}
	
	function removePost($postId) {
		
		$this->db->deactivate('mb_replies', $postId);
		
		return true;
		
	}
	
	function latestPosts() {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('latest_posts');
		
		$sql = "
		SELECT
			p.id,
			p.topic,
			CONCAT(SUBSTRING(p.message,1,30),'...') as message,
			p.sticky,
			p.locked,
			p.cdate,
			u.displayName as author,
			COUNT(r.id) as replies,
			ca.id as categoryId,
			ca.name as categoryName,
			ch.name as channelName,
			ch.id as channelId,
			IFNULL(r.cdate, p.cdate) as replydate
		FROM
			mb_posts p
		JOIN
			mb_users u ON p.userid = u.id
		LEFT JOIN
			mb_replies r ON p.id = r.postid
		LEFT JOIN
			mb_categories ca ON ca.id = p.catid
		LEFT JOIN
			mb_channels ch ON ch.id = ca.channelid
		WHERE
			p.isactive = 1 AND
			u.isactive = 1 AND
			p.locked = 0
		GROUP BY
			p.id
		ORDER BY
			replydate DESC
		LIMIT 
			".$perPage;
		
		$results = $this->db->getAll($sql);
		
		if(is_array($results)) {
			
			foreach($results as $result) {
				$sql = "
				SELECT
					r.cdate,
					u.displayName as author
				FROM
					mb_replies r
				JOIN
					mb_users u ON r.userid = u.id
				WHERE
					r.isactive = 1 AND
					u.isactive = 1 AND
					r.postid=".$result['id']."
				ORDER BY
					r.cdate DESC
				LIMIT
					1
				";
				
				$result['lastreply'] = $this->db->getRow($sql);
				
				if(empty($result['lastreply'])) {
					
					$result['lastreply']['cdate'] = $result['cdate'];
					$result['lastreply']['author'] = $result['author'];
					
				}
				
				$threads[] = $result;
				
			}
			
		}
		
		if(!empty($threads)) {

			return $threads;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function search($search, $pageNumber) {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('per_page_posts');
		
		##pagination
		if($pageNumber > 1) {

			$start = ($perPage * $pageNumber) - $perPage;

		} else {

			$start = 0;

		}
		
		$sql = "
		SELECT
			mb_posts.*,
			mb_posts.id AS 'postid',
			mb_categories.id AS 'categoryId',
			mb_categories.name AS 'categoryName',
			mb_channels.id AS 'channelId',
			mb_channels.name AS 'channelName',
			mb_users.displayName as author
		FROM
			`mb_posts`
		INNER JOIN
			`mb_categories` ON mb_categories.id = mb_posts.catid
		INNER JOIN
			`mb_channels` ON mb_channels.id = mb_categories.channelid
		INNER JOIN
				mb_users ON mb_posts.userid = mb_users.id
		WHERE
			mb_posts.isactive = 1 AND
			concat(mb_posts.topic, mb_posts.message) LIKE '%".$this->db->makeSafe($search)."%'";
		
		$posts = $this->db->getAll($sql);
		
		$sql = "
		SELECT
			mb_replies.*,
			mb_replies.id AS 'replyId',
			mb_posts.catid AS 'categoryId',
			mb_categories.name AS 'categoryName',
			mb_channels.id AS 'channelId',
			mb_channels.name AS 'channelName',
			mb_users.displayName as author
		FROM
			`mb_replies`
		INNER JOIN
			`mb_posts` ON mb_posts.id = mb_replies.postid
		INNER JOIN
			`mb_categories` ON mb_categories.id = mb_posts.catid
		INNER JOIN
			`mb_channels` ON mb_channels.id = mb_categories.channelid
		INNER JOIN
			mb_users ON mb_replies.userid = mb_users.id
		WHERE
			mb_replies.isactive = 1 AND
			concat(mb_replies.topic, mb_replies.message) LIKE '%".$this->db->makeSafe($search)."%'";
			
		$replies = $this->db->getAll($sql);
		
		$threads = array();
		if(!empty($posts) || !empty($replies)) {
			
			$results = array_merge($posts, $replies);
			
			$this->totalSearchResults = count($results);
			$results = array_slice($results, $start, $perPage);
			
			foreach($results as $result) {
				$sql = "
				SELECT
					r.cdate,
					u.displayName as author
				FROM
					mb_replies r
				JOIN
					mb_users u ON r.userid = u.id
				WHERE
					r.isactive = 1 AND
					u.isactive = 1 AND
					r.postid=".$result['postid']."
				ORDER BY
					r.cdate DESC
				LIMIT
					1
				";
				
				$result['lastreply'] = $this->db->getRow($sql);
				
				if(empty($result['lastreply'])) {
					
					$result['lastreply']['cdate'] = $result['cdate'];
					$result['lastreply']['author'] = $result['author'];
					
				}
				
				$threads[] = $result;
				
			}
			
		}
		
		return $threads;
		
	}
	
	function getThread($threadId,$start=0) {
		
		$sql = "
		SELECT
			p.id,
			p.topic,
			p.message,
			p.sticky,
			p.locked,
			p.cdate,
			p.catid,
			p.isactive,
			u.displayName as author,
			u.isadmin as admin
		FROM
			mb_posts p
		JOIN
			mb_users u ON p.userid = u.id
		WHERE
			p.id = ".$threadId."
		";
		
		$post = $this->db->getRow($sql);
		$replies = $this->getReplies($threadId,$start);
		
		$result = array('topic'=>$post,'replies'=>$replies);
		
		return $result;
		
	}
	
	private function getReplies($postId,$start=0) {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('per_page_posts');
		
		$sql = "
		SELECT
			r.id,
			r.topic,
			r.message,
			r.cdate,
			u.displayName as author
		FROM
			mb_replies r
		JOIN
			mb_users u on r.userid = u.id
		WHERE
			r.isactive = 1 AND
			u.isactive = 1 AND
			r.postid = ".$postId."
		ORDER BY
			cdate
		LIMIT
			".$start.",".$perPage."
		";
		
		$replies = $this->db->getAll($sql);
		
		return $replies;
		
	}
	
	function getThreads($catId,$start=0) {
		
		$objSettings = Settings::getInstance();
		$perPage = $objSettings->getEntry('per_page_posts');
		
		$sql = "
		SELECT
			p.id,
			p.topic,
			CONCAT(SUBSTRING(p.message,1,30),'...') as message,
			p.sticky,
			p.locked,
			p.cdate,
			u.displayName as author,
			COUNT(r.id) as replies,
			ca.id as categoryId,
			ca.name as categoryName,
			ch.name as channelName,
			ch.id as channelId
		FROM
			mb_posts p
		JOIN
			mb_users u ON p.userid = u.id
		LEFT JOIN
			mb_replies r ON p.id = r.postid
		LEFT JOIN
			mb_categories ca ON ca.id = p.catid
		LEFT JOIN
			mb_channels ch ON ch.id = ca.channelid
		WHERE
			p.isactive = 1 AND
			u.isactive = 1 AND
			p.catid = ".$catId."
		GROUP BY
			p.id
		ORDER BY
			p.sticky DESC,
			r.cdate DESC,
			p.cdate DESC
		LIMIT
			".$start.",".$perPage."
		";
		
		$results = $this->db->getAll($sql);
		
		if(is_array($results)) {
			
			foreach($results as $result) {
				$sql = "
				SELECT
					r.cdate,
					u.displayName as author
				FROM
					mb_replies r
				JOIN
					mb_users u ON r.userid = u.id
				WHERE
					r.isactive = 1 AND
					u.isactive = 1 AND
					r.postid=".$result['id']."
				ORDER BY
					r.cdate DESC
				LIMIT
					1
				";
				
				$result['lastreply'] = $this->db->getRow($sql);
				
				if(empty($result['lastreply'])) {
					
					$result['lastreply']['cdate'] = $result['cdate'];
					$result['lastreply']['author'] = $result['author'];
					
				}
				
				$threads[] = $result;
				
			}
			
		}
		
		if(!empty($threads)) {

			return $threads;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getChannels() {
		
		$sql = "
		SELECT
			*
		FROM
			mb_channels c
		WHERE
			c.isactive = 1
		ORDER BY
			c.displayorder
		";
		
		$result = $this->db->getAll($sql);
		
		return $result;
		
	}
	
	function getChannel($channelId) {
		
		$sql = "
		SELECT
			*
		FROM
			mb_channels c
		WHERE
			c.isactive = 1 AND
			c.id = ".$channelId."
		LIMIT
			1
		";
		
		$result = $this->db->getRow($sql);
		
		return $result;
		
	}
	
	function getCategories($channelId) {
		
		$sql = "
		SELECT
			c.*,
			count(p.id) as threads
		FROM
			mb_categories c
		LEFT JOIN
			mb_posts p ON c.id = p.catid AND p.isactive = 1
		WHERE
			c.isactive = 1 AND
			c.channelid = ".$channelId."
		GROUP BY
			c.id
		ORDER BY
			c.displayorder
		";
		
		$result = $this->db->getAll($sql);
		
		return $result;
		
	}
	
	function getCategory($categoryId) {
		
		$sql = "
		SELECT
			*
		FROM
			mb_categories c
		WHERE
			c.isactive = 1 AND
			c.id = ".$categoryId."
		LIMIT
			1
		";
		
		$result = $this->db->getRow($sql);
		
		return $result;
		
	}
	
	function numbReplies($threadId) {
		
		$sql = "
		SELECT
			COUNT(id)
		FROM
			mb_replies
		WHERE
			isactive = 1 AND
			postid = ".$threadId."
		GROUP BY
			postid
		";
		
		$count = $this->db->getOne($sql);
		
		return $count;
		
	}
	
	function numbPosts($catId) {
		
		$sql = "
		SELECT
			COUNT(id)
		FROM
			mb_posts
		WHERE
			isactive = 1 AND
			catid = ".$catId."
		GROUP BY
			catid
		";
		
		$count = $this->db->getOne($sql);
		
		return $count;
		
	}
	
}


?>
