<?php

class Authentication {
	
	private static $instance;
	private $db;
	public $user_id;
	public $user_type;
	public $user_activated;
	private $registered;
	public $loginFailReason;
	
	function __construct() {
		
		$this->db = Database::getInstance();
		
		if(!empty($_SESSION[SESSION_NAME.'user_id'])) {
			
			$this->setLogin($_SESSION[SESSION_NAME.'user_id']);
			
		}
		
	}
	
	function getInstance() {
		
		if (!isset(self::$instance)) {
			
			$c = __CLASS__;
			self::$instance = new $c;
			
		}
		
		return self::$instance;
		
	}
	
	function login($email,$password) {
		
		$sql = "
		SELECT
			`lockout`
		FROM
			`users`
		WHERE
			`email` = '".$this->db->makeSafe($email)."'
		LIMIT
			1
		";
		
		$lockouttime = strtotime($this->db->getOne($sql));
		
		if(!empty($lockouttime) && $lockouttime > time()) {
			
			// locked out
			$this->loginFailReason = 'Account locked out! Please contact support.';
			return false;
			
		}
		
		$sql = "
		SELECT
			`id`
		FROM
			`users`
		WHERE
			`email` = '".$this->db->makeSafe($email)."' AND
			`password` = '".$this->encryptPassword($password)."'
		LIMIT
			1
		";
		
		$result = $this->db->getRow($sql);
		$user_id = $result['id'];
		
		if($result) {
			
			$this->setLogin($user_id);
			return true;
			
		} else {
			
			$this->failedLoginAttempt($email);
			$this->loginFailReason = 'Invalid email / password.';
			return false;
			
		}
		
	}
  
	function requiresAccount() {
		
		if($this->loggedIn() && $this->user_activated) {
			
			return true;
			
		} else {
			
			header("Location: http://".URL."/Error/requiresaccount");
			
		}
		
	}
	
	private function failedLoginAttempt($email) {
		
		$attempts = $this->db->getOne("SELECT `loginattempts` FROM `users` WHERE `email` = '".$this->db->makeSafe($email)."'");
		
		$this->db->reset();
		$this->db->assign('loginattempts',$attempts+1);
		
		if($attempts >= 5) {
			
			$this->db->assign('lockout',date("Y-m-d H:i:s",time()+3600));
			
		}
		
		$this->db->update('users',"`email` = '".$this->db->makeSafe($email)."'");
		
	}
	
	private function setLogin($user_id) {
		
		$this->registered = true;
		$this->user_id = $user_id;
		
		$info = $this->db->getRow("SELECT `type`,`active` FROM `users` WHERE `id` = ".intval($user_id));
		$type = $info['type'];
		$active = $info['active'];
		
		$this->user_type = $type;
		$this->user_activated = $active;
		
		$this->db->reset();
		$this->db->assign('loginattempts',0);
		$this->db->assign('lastLogin',date("Y-m-d H:i:s"));
		$this->db->update('users',"`id` = ".$user_id);
		
		$_SESSION[SESSION_NAME.'user_id'] = $user_id;
		$_SESSION[SESSION_NAME.'user_type'] = $type;
		$_SESSION[SESSION_NAME.'user_activated'] = $active;
		
	}
	
	function logout() {
		
		$this->registered = false;
		$this->user_id = false;
		$this->user_activated = false;
		session_unset();
		
		return true;
		
	}
	
	function loggedIn() {
		
		return $this->registered;
		
	}
	
	function isAdmin() {
		
		if($this->user_type == 'admin') {
			
			return true;
			
		} else {
			
			return false;
			
		}
		
	}
	
	function encryptPassword($password) {
		
		$password = md5($password);
		return $password;
		
	}
	
}

?>