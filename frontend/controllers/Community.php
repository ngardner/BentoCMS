<?php

class Community extends Controller {

	function __construct() {
		
		parent::__construct();
		$this->objAuthentication = Authentication::getInstance();
		
	}
	
	function actionIndex($params = '') {
		
		$forumModel = new ForumModel();
		$channels = $forumModel->getChannels();
		
		$categories = array();
		foreach($channels as $channel) {
			
			$categories[$channel['id']] = $forumModel->getCategories($channel['id']);
			
		}
		
		$this->view->assign('channels', $channels);
		$this->view->assign('categories', $categories);
		$this->view->assign('content', $this->view->fetch('tpl/community/index.tpl'));
		$this->finish();
		
	}
	
	function actionLatestPosts($params = '') {
		
		$forumModel = new ForumModel();
		
		$posts = $forumModel->latestPosts();
		
		$this->view->assign('posts', $posts);
		$this->view->assign('content', $this->view->fetch('tpl/community/latest.tpl'));
		$this->finish();
		
	}
	
	function actionViewCategory($params = '') {
		
		if(empty($params['categoryId'])) {
			
			$objDispatcher = new Dispatcher;
			$objDispatcher->setController('Forum');
			$objDispatcher->setAction('Index');
			$objDispatcher->setParams($params);
			$objDispatcher->dispatch();
			
		} else {
			
			$objSettings = Settings::getInstance();
			$perPage = $objSettings->getEntry('per_page_posts');
			
			$forumModel = new ForumModel();
			$catid = $params['categoryId'];
			$pageNumb = !empty($params['pageNumb'])?$params['pageNumb']:1;
			$start = ($pageNumb * $perPage) - $perPage;
			
			$category = $forumModel->getCategory($catid);
			$channel = $forumModel->getChannel($category['channelid']);
			$threads = $forumModel->getThreads($catid, $start);
			$totalPages = ceil($forumModel->numbPosts($catid)/$perPage);
			if($totalPages == 0) { $totalPages = 1; }
			
			$this->view->assign('channel', $channel);
			$this->view->assign('category', $category);
			$this->view->assign('threads', $threads);
			$this->view->assign('pageNumb', $pageNumb);
			$this->view->assign('totalPages', $totalPages);
			$this->view->assign('perPage', $perPage);
			$this->view->assign('content', $this->view->fetch('tpl/community/viewcategory.tpl'));
			$this->finish();
			
		}
		
	}
	
	function actionViewThread($params = '') {
		
		if(empty($params['threadId'])) {
			
			$objDispatcher = new Dispatcher;
			$objDispatcher->setController('Forum');
			$objDispatcher->setAction('Index');
			$objDispatcher->setParams($params);
			$objDispatcher->dispatch();
			
		} else {
			
			$objSettings = Settings::getInstance();
			$perPage = $objSettings->getEntry('per_page_posts');
			
			$forumModel = new ForumModel();
			$threadId = $params['threadId'];
			$pageNumb = !empty($params['pageNumb'])?$params['pageNumb']:1;
			$start = ($pageNumb * $perPage) - $perPage;
			$totalPages = ceil($forumModel->numbReplies($threadId)/$perPage);
			if($totalPages == 0) { $totalPages = 1; }
			
			$thread = $forumModel->getThread($threadId, $start);
			$category = $forumModel->getCategory($thread['topic']['catid']);
			$channel = $forumModel->getChannel($category['channelid']);
			
			$this->view->assign('channel', $channel);
			$this->view->assign('category', $category);
			$this->view->assign('thread', $thread);
			$this->view->assign('pageNumb', $pageNumb);
			$this->view->assign('totalPages', $totalPages);
			$this->view->assign('perPage', $perPage);
			$this->view->assign('content', $this->view->fetch('tpl/community/viewthread.tpl'));
			$this->finish();
			
		}
		
	}
	
	function actionNewPost($params = '') {
		
		if($this->objAuthentication->loggedIn()) {
			
			if(!empty($params['newPost']) && $params['newPost'] == 1) {
			
				$forumModel = new ForumModel();
			
				$post = array();
				$post['catid'] = $params['catid'];
				$post['topic'] = $params['topic'];
				$post['message'] = $params['message'];
			
				$newPost = $forumModel->addPost($post);
			
				if($newPost) {
				
					$thread = $forumModel->getThread($newPost);
					$category = $forumModel->getCategory($thread['topic']['catid']);
					$channel = $forumModel->getChannel($category['channelid']);
					
					header("Location: http://".URL."/community/".preg_replace('/[^a-zA-Z]/', '_', $channel['name'])."-".$channel['id']."/".preg_replace('/[^a-zA-Z]/', '_', $category['name'])."-".$category['id']."/".preg_replace('/[^a-zA-Z]/', '_', $thread['topic']['topic'])."-".$thread['topic']['id']."-1.html");

				
				}
			
			} else {
			
				$forumModel = new ForumModel();
				$category = $forumModel->getCategory($params['categoryId']);
			
				$this->view->assign('category', $category);
				$this->view->assign('content', $this->view->fetch('tpl/community/newpost.tpl'));
				$this->finish();
		
			}
			
		} else {
			
			$this->view->assign('errorMsg', 'You must login to make a post');
			$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
			$this->finish();
			
		}
		
	}
	
	function actionNewReply($params = '') {
		
		if($this->objAuthentication->loggedIn()) {
			
			if(!empty($params['newPost']) && $params['newPost'] == 1) {
			
				$forumModel = new ForumModel();
				
				if(!$forumModel->isThreadLocked($params['threadId'])) {
				
					$post = array();
					$post['postid'] = $params['threadId'];
					$post['topic'] = $params['topic'];
					$post['message'] = $params['message'];
			
					$newPost = $forumModel->addReply($post);
			
					if($newPost) {
						
						$objSettings = Settings::getInstance();
						$perPage = $objSettings->getEntry('per_page_posts');
						
						$thread = $forumModel->getThread($post['postid']);
						$category = $forumModel->getCategory($thread['topic']['catid']);
						$channel = $forumModel->getChannel($category['channelid']);
						$totalPages = ceil($forumModel->numbReplies($thread['topic']['id'])/$perPage);
						if($totalPages == 0) { $totalPages = 1; }
					
					
						header("Location: http://".URL."/community/".preg_replace('/[^a-zA-Z]/', '_', $channel['name'])."-".$channel['id']."/".preg_replace('/[^a-zA-Z]/', '_', $category['name'])."-".$category['id']."/".preg_replace('/[^a-zA-Z]/', '_', $thread['topic']['topic'])."-".$thread['topic']['id']."-".$totalPages.".html#".$newPost);

				
					}
				
				} else {
					
					$this->view->assign('content', 'This thread is locked');
					$this->finish();
					
				}
			
			} else {
			
				$objDispatcher = new Dispatcher;
				$objDispatcher->setController('Forum');
				$objDispatcher->setAction('Index');
				$objDispatcher->setParams($params);
				$objDispatcher->dispatch();
		
			}
			
		} else {
			
			$this->view->assign('errorMsg', 'You must login to make a post');
			$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
			$this->finish();
			
		}
		
	}
	
	function actionSticky($params = '') {
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		$forumModel = new ForumModel();
		
		if(!empty($params['threadId'])) {
			
			if($userModel->isAdmin()) {
				
				$sticky = $forumModel->toggleSticky($params['threadId']);
				
				echo $sticky;
				
			} else {
				
				return false;
				
			}
			
		}
		
	}
	
	function actionLock($params = '') {
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		$forumModel = new ForumModel();
		
		if(!empty($params['threadId'])) {
			
			if($userModel->isAdmin()) {
				
				$lock = $forumModel->toggleLock($params['threadId']);
				
				echo $lock;
				
			} else {
				
				return false;
				
			}
			
		}
		
	}
	
	function actionRemoveThread($params = '') {
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		$forumModel = new ForumModel();
		
		if(!empty($params['threadId'])) {
			
			if($userModel->isAdmin()) {
				
				$forumModel->removeThread($params['threadId']);
				
				echo true;
				
			} else {
				
				echo false;
				
			}
			
		}
		
	}
	
	function actionRemovePost($params = '') {
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		$forumModel = new ForumModel();
		
		if(!empty($params['postId'])) {
			
			if($userModel->isAdmin()) {
				
				$forumModel->removePost($params['postId']);
				
				echo true;
				
			} else {
				
				echo false;
				
			}
			
		}
		
	}
	
	function actionAccount($params = '') {
		
		$userModel = new UserModel($this->objAuthentication->user_id);
		
		if(!empty($params['manageAccount']) && $params['manageAccount'] == 1) {
			
			$objValidator = new Validator();
			$objValidator->reset();
			
			if(!empty($params['user_password'])) {
				$objValidator->validatePassword($params['user_password']);
				$objValidator->passwordsMatch($params['user_password'],$params['user_password2']);
			}
		
		
			if($objValidator->hasError) {
			
				$this->view->assign('errorMessages',$objValidator->getError());
			
			} else {
				
				$userInfo = array();
				if(!empty($params['user_password'])) {
					$userInfo['password'] = $params['user_password'];
					$userInfo['email'] = $params['user_email'];
					$userUpdated = $userModel->manageUser($userInfo);
					
					if($userUpdated) {

						$this->view->assign('successMessage','Your account has been updated.');

					} else {

						$this->view->assign('errorMessages',array('Unable to update your account'));

					}
				}
			
			}
		
		}
	
		$this->view->assign('userInfo', $userModel->getInfo());
		$this->view->assign('content', $this->view->fetch('tpl/community/account.tpl'));
		$this->finish();
		
	}
	
	function actionLogin($params = '') {
		
		if(!empty($params['email']) && !empty($params['password'])) {
			
			if($this->objAuthentication->login($params['email'],$params['password'])) {
				
				//no errors, continue to home
				/*$objDispatcher = new Dispatcher;
				$objDispatcher->setController('Forum');
				$objDispatcher->setAction('Index');
				$objDispatcher->setParams($params);
				$objDispatcher->dispatch();*/
				$this->actionIndex($params);
				
			} else {
				
				$this->view->assign('errorMsg', 'Unable to login, try again.');
				$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
				$this->finish();
				
			}
			
		} else if(!empty($params['createAccount']) && $params['createAccount'] == 1) {
			
			$errorMessages = array();
			$objValidator = new Validator;
			$objValidator->reset();
			$objValidator->validateEmail($params['create_email']);
			$objValidator->validatePassword($params['create_password']);
			$objValidator->passwordsMatch($params['create_password'], $params['create_password2']);
			$objValidator->validateName($params['create_displayName']);
			
			if($objValidator->hasError || empty($params['create_terms'])) {
				
				$error = $objValidator->getError();
				if(empty($params['create_terms'])) {
					$error []= 'You must agree to the Terms of Use to make an account';
				}
				$this->view->assign('errorMessages', $error);
				$this->view->assign('created', false);
				
			} else {
				
				$userData = array();
				$userData['email'] = $params['create_email'];
				$userData['password'] = $params['create_password'];
				$userData['displayName'] = $params['create_displayName'];

				$userModel = new UserModel();

				$created = $userModel->createUser($userData);

				if(!empty($created)) {
					
					$this->view->assign('created', true);
					
					$objEmail = new Emailer;
					$objEmail->setFrom(CONTACT_EMAIL);
					$objEmail->setSubject('Retail Roar Community account created');
					$objEmail->addTO($userData['email']);
					$objEmail->setBody($this->view->fetch('emails/communitysignup.tpl'), true);
					$sent = $objEmail->sendMail();

				} else {
					
					$this->view->assign('created', false);
					$this->view->assign('errorMessages', $userModel->errorMsg);
					
				}
				
				$this->view->assign('submitted', true);
				
			}
			
			$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
			$this->finish();
			
		} else if($this->objAuthentication->loggedIn()){
			
			$this->view->assign('content', 'Already logged in.');
			$this->finish();
			
		} else {
			
			$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
			$this->finish();
			
		}
		
	}
	
	function actionLogout($params = '') {
		
		$this->objAuthentication->logout();
		$this->view->assign('UserInfo', null);
		$this->view->assign('loggedIn', false);
		$this->view->assign('loggedOut', true);
		$this->view->assign('content', $this->view->fetch('tpl/community/login.tpl'));
		$this->finish();
		
	}
	
	function actionSearch($params = '') {
		
		$forumModel = new ForumModel();
		
		if(!empty($params['submit']) && $params['submit'] == 1 || !empty($params['pageNumb'])) {
			
			$pageNumb = !empty($params['pageNumb'])?$params['pageNumb']:1;
			
			$results = $forumModel->search($params['search'], $pageNumb);
			
			$objSettings = Settings::getInstance();
			$perPage = $objSettings->getEntry('per_page_posts');
			
			$this->view->assign('search', $params['search']);
			$this->view->assign('pageNumb', $pageNumb);
			$this->view->assign('results', $results);
			$this->view->assign('totalPages', ceil($forumModel->totalSearchResults/$perPage));
			$this->view->assign('content', $this->view->fetch('tpl/community/search.tpl'));
			$this->finish();
			
		}
		
	}
	
	/*function actionNewUser($params = '') {
		
		if(!empty($params['createAccount']) && $params['createAccount'] == 1) {
			
			$errorMessages = array();
			$objValidator = new Validator;
			$objValidator->reset();
			$objValidator->validateEmail($params['create_email']);
			$objValidator->validatePassword($params['create_password']);
			$objValidator->passwordsMatch($params['create_password'], $params['create_password2']);
			$objValidator->validateName($params['create_displayName']);
			
			if($objValidator->hasError || empty($params['create_terms'])) {
				
				$this->view->assign('errorMessages', $objValidator->getError());
				$this->view->assign('created', false);
				
			} else {
				
				$userData = array();
				$userData['email'] = $params['create_email'];
				$userData['password'] = $params['create_password'];
				$userData['displayName'] = $params['create_displayName'];

				$userModel = new UserModel();

				$created = $userModel->createUser($userData);

				if(!empty($created)) {
					
					$this->view->assign('created', true);
					
					$objEmail = new Emailer;
					$objEmail->setFrom(CONTACT_EMAIL);
					$objEmail->setSubject('Retail Roar Community account created');
					$objEmail->addTO($userData['email']);
					$objEmail->setBody($this->view->fetch('emails/communitysignup.tpl'), true);
					$sent = $objEmail->sendMail();
					var_dump($sent);

				} else {
					
					$this->view->assign('created', false);
					$this->view->assign('errorMessages', $userModel->errorMsg);
					
				}
				
				$this->view->assign('submitted', true);
				
			}
			
		}
		
		$this->view->assign('content', $this->view->fetch('tpl/community/newuser.tpl'));
		$this->finish();
		
	}*/
	
}

?>
