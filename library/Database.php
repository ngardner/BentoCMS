<?php
/**
 * This file contains the database class
 */

class Database {
	
	private static $instance;
	private $dbConn;
	private $selectMode;
	private $resultSet;
	private $assignedData;
	private $numbQueries;
	public $col;
	public $lastSql;
	
	function __construct() {
		
		$this->dbConn = false;
		$this->resultSet = false;
		$this->assignedData = false;
		$this->selectMode = MYSQL_ASSOC;
		$this->numbQueries = 0;
		
	}
	
	static function getInstance() {
		
		if (!isset(self::$instance)) {
			
			$c = __CLASS__;
			self::$instance = new $c;
			
		}
		
		return self::$instance;
		
	}
	
	function setMode($mode) {
		
		$this->selectMode = $mode;
		
	}
	
	function connect($server,$port,$username,$password,$database) {
		
		$this->dbConn = mysql_connect($server.':'.$port,$username,$password,true);
		
		if($this->dbConn) {
			
			//force UTF-8 encoding
			mysql_set_charset('utf8',$this->dbConn);
			
			if(mysql_select_db($database,$this->dbConn)) {
				
				return true;
				
			} else {
				
				throw new Exception('Unable to select database.');
				
			}
			
		} else {
			
			throw new Exception('Unable to connect to database.');
			
		}
		
	}
	
	function disconnect() {
		
		mysql_close($this->dbConn);
		return true;
		
	}
	
	function getRow($sql) {
		
		try {
			
			$this->query($sql);
			
			if($this->resultSet) {
				
				$result = mysql_fetch_array($this->resultSet,$this->selectMode);
				return $result;
				
			} else {
				
				return false;
				
			}
			
		} catch(Exception $e) {
			
			throw new Exception('Unable to select data: '.$e->getMessage());
			
		}
		
	}
	
	function getOne($sql) {
		
		try {
			
			$this->query($sql);
			
			if($this->resultSet) {
				
				$result = mysql_fetch_array($this->resultSet,MYSQL_NUM);
				
				if(!empty($result)) {
					
					return $result[0];
					
				}
				
			}
			
			return false;
			
		} catch(Exception $e) {
			
			throw new Exception('Unable to select data: '.$e->getMessage());
			
		}
		
	}
	
	function getAll($sql) {
		
		$results = array();
		
		try {
			
			$this->query($sql);
			
			if($this->resultSet) {
				
				while($result = mysql_fetch_array($this->resultSet,$this->selectMode)) {
					
					$results[] = $result;
					
				}
				
			}
			
			return $results;
			
		} catch(Exception $e) {
			
			throw new Exception('Unable to select data: '.$e->getMessage());
			
		}
		
	}
	
	function getCol($sql) {
		
		$results = array();
		
		try {
			
			$this->query($sql);
			
			if($this->resultSet) {
				
				while($result = mysql_fetch_array($this->resultSet,MYSQL_NUM)) {
					
					$results[] = $result[0];
					
				}
				
			}
			
			return $results;
			
		} catch(Exception $e) {
			
			throw new Exception('Unable to select data: '.$e->getMessage());
			
		}
		
	}
	
	function reset() {
		
		$this->assignedData = false;
		$this->resultSet = false;
		
	}
	
	function assign($col,$val) {
		
		$this->assignedData[$col] = $val;
		
	}
	
	function assign_str($col,$val) {
		
		$this->assignedData[$col] = $this->makeSafe($val);
		
	}
	
	function update($table,$whereClause) {
		
		if(!empty($this->assignedData)) {
			
			$sql = "UPDATE `".$this->makeSafe($table)."` SET ";
			
			foreach($this->assignedData as $column=>$value) {
				
				$sql .= "`".$this->makeSafe($column)."` = '".$value."', ";
				
			}
			
			$sql = substr($sql,0,-2); // strip off last comma
			
			if(!empty($whereClause)) {
				
				$sql .= " WHERE ".$whereClause;
				
			}
			
			$this->query($sql);
			
			return true;
			
		} else {
			
			throw new Exception('No data assigned to update.');
			
		}
		
	}
	
	function insert($table) {
		
		if(!empty($this->assignedData)) {
			
			$sql = "INSERT INTO `".$this->makeSafe($table)."` SET ";
			
			foreach($this->assignedData as $column=>$value) {
				
				$sql .= "`".$this->makeSafe($column)."` = '".$value."', ";
				
			}
			
			$sql = substr($sql,0,-2); // strip off last comma
			$this->query($sql);
			
			return $this->insertId();
			
			
		} else {
			
			throw new Exception('No data assigned to update.');
			
		}
		
	}
	
	function insertId() {
		
		return mysql_insert_id($this->dbConn);
		
	}
	
	function delete($table,$id) {
		
		$sql = "DELETE FROM `".$this->makeSafe($table)."` WHERE `id` = ".$this->makeSafe($id);
		$this->query($sql);
		
		return true;
		
	}
	
	function deactivate($table,$id) {
		
		$this->reset();
		$this->assign('active',false);
		$this->update($table,"`id`=".intval($id));
		
		return true;
		
	}
	
	function query($sql) {
		
		if($this->dbConn) {
			
			$this->resultSet = mysql_query($sql,$this->dbConn);
			$this->lastSql = $sql;
			$this->numbQueries++;
			
			$haserror = mysql_errno($this->dbConn);
			
			if($haserror) {
				
				throw new Exception('Database error'."\r\n".'SQL: '.$sql."\r\n".'Error: '.mysql_error($this->dbConn));
				
			}
			
		} else {
			
			throw new Exception('Not connected to database');
			
		}
		
	}
	
	function makeSafe($string) {
		
		if(get_magic_quotes_gpc()) {
			
			//uughhh
			$safeData = $string;
			
		} else {
			
			$safeData = mysql_real_escape_string($string,$this->dbConn);
			
		}
		
		return $safeData;
		
	}
	
	function getNumbQueries() {
		
		return $this->numbQueries;
		
	}
	
	function getError() {
		
		if($this->dbConn) {
			$errorMsg = mysql_error($this->dbConn);
			return $errorMsg;
		} else {
			return 'No database connection.';
		}
		
	}
	
	function movenext(){
		
		return $this->col = mysql_fetch_array($this->resultSet);
		
	}
	
}

?>
