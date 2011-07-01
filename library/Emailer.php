<?php

class Emailer {

	private $boundary;
	private $headers;
	private $to;
	private $cc;
	private $bcc;
	private $from;
	private $subject;
	private $message;
	
	/**
  * sets a default boundary
  */
	function __construct() {
		
		$this->setBoundary("--==Multipart_Boundary_x".md5(time())."x==--");
		
	}
	
	public function getParams() {
		
		$p['to'] = $this->to;
		$p['cc'] = $this->cc;
		$p['bcc'] = $this->bcc;
		$p['from'] = $this->from;
		$p['subject'] = $this->subject;
		$p['message'] = $this->message;
		
		return $p;
		
	}
	
	/**
  * sets who the email comes from
  * @param string $email The email address
  * @param string $name The name of who is sending the email
  */
	public function setFrom($email,$name='') {
		
		if($this->validEmail($email)) {
			
			if(!empty($name)) {
				
				$this->addHeader("From: ".$name." <".$email.">\n");
				$this->from = $email;
				
			} else {
				
				$this->addHeader("From: ".$email."\n");
				$this->from = $email;
				
			}
			
		} else {
			
			throw new Exception('Invalid email for setFROM ['.$email.']');
			
		}
		
	}
	
	/**
	* add a person to the list of receipients
  * @param string $email Email address of receipient
  */
	public function addTO($email,$name='') {
		
		if($this->validEmail($email)) {
			
			if(!empty($name)) {
				
				if(!empty($this->to)) {
					$this->to .= ", ".$name." <".$email.">";
				} else {
					$this->to = $name." <".$email.">";
				}
				
			} else {
				
				if(!empty($this->to)) {
					$this->to .= ", ".$email;
				} else {
					$this->to = $email;
				}
				
			}
			
		} else {
			
			throw new Exception('Invalid email for addTO ['.$email.']');
			
		}
		
	}
	
	/**
	 * adds a person to the CC list of receipients
	 * @param string $email Email address of receipient
	 */
	public function addCC($email,$name='') {
		
		if($this->validEmail($email)) {
			
			if(!empty($name)) {
				
				if(!empty($this->cc)) {
					$this->cc .= ", ".$name." <".$email.">";
				} else {
					$this->cc = "Cc: ".$name." <".$email.">";
				}
				
			} else {
				
				if(!empty($this->cc)) {
					$this->cc .= ", ".$email;
				} else {
					$this->cc = "Cc: ".$email;
				}
				
			}
			
		} else {
			
			throw new Exception('Invalid email for addCC ['.$email.']');
			
		}
		
	}
	
	/**
	 * adds a person to the BCC list of receipients
	 * @param string $email Email address of receipient
	 */
	public function addBCC($email,$name='') {
		
		if($this->validEmail($email)) {
			
			if(!empty($name)) {
				
				if(!empty($this->bcc)) {
					$this->bcc .= ", ".$name." <".$email.">";
				} else {
					$this->bcc = "Bcc: ".$name." <".$email.">";
				}
				
			} else {
				
				if(!empty($this->bcc)) {
					$this->bcc .= ", ".$email;
				} else {
					$this->bcc = "Bcc: ".$email;
				}
				
			}
			
		} else {
			
			throw new Exception('Invalid email for addBCC ['.$email.']');
			
		}
		
	}
	
	/**
  * sets the subject of the email
  * @param string $subject The subject of the email
	*/
	public function setSubject($subject) {
		
		if(!empty($subject)) {
			
			$this->subject = $subject;
			
		}
		
	}
	
	/**
  * Adds the text/html portion of the email - which is the "body"
  * @param string $message The body of the email
  * @param boolean $asHTML If true sends as text/html, if false sends as text/plain
  */
	public function setBody($message,$asHTML=false) {
		
		if(!empty($message)) {
			
			if($asHTML) {
				$contenttype = 'text/html';
			} else {
				$contenttype = 'text/plain';
			}
			
			$body = "This is a multi-part message in MIME format.\n\n" . 
			"--".$this->boundary."\n" . 
			"Content-Type:".$contenttype."; charset=\"iso-8859-1\"\n" . 
			"Content-Transfer-Encoding: 7bit\n\n" . 
			$message . "\n\n";
			
			$this->addMessage($body);
			
		}
		
	}
	
	/**
  * Attaches a file to the email.
  * Does not handle file uploads.
  * @param file $file path to a file
  */
	public function attachFile($file) {
		
		$fileinfo = $this->getFileInfo($file);
		$filedata = $this->readFile($file);
		
		if($filedata) {
			// attach it
			$mailmsg = "--".$this->boundary."\n" . 
                "Content-Type: ".$fileinfo['type'].";\n" . 
                " name=\"".$fileinfo['name']."\";\n" .
				" filename=\"".$fileinfo['name']."\";\n" .
				"Content-Description: ".$fileinfo['name']."\n".
				"Content-Disposition: attachment;\n".
                "Content-Transfer-Encoding: base64\n\n" . 
                $filedata . "\n\n";
			
			$this->addMessage($mailmsg);
			
		}
		
	}
	
	/**
  * Sets the boundary for the email message
  * Does not have to be set
  * @param string $boundary Should be a unique string that splits up the message into parts.
  */
	public function setBoundary($boundary) {
		
		$this->boundary = $boundary;
		
	}
	
	/**
  * Gets the file name and type if available.
  * @param file $file file to get info from
  */
	private function getFileInfo($file) {
		$filename = str_replace("\\","/",$file);
		$filename = explode("/",$file);
		$filename = $filename[count($filename)-1];
		
		if(function_exists("mime_content_type")) {
			$filetype = mime_content_type($file);
		} else {
			$filetype = "application/octet-stream";
		}
		
		return array("name"=>$filename,"type"=>$filetype);
	}
	
	/**
  * Reads the contents out of the file so it can attach it.
  * @param file $attachment File to get contents from
  */
	private function readFile($attachment) {
		// get file contents
		$file = @fopen($attachment,'rb');
		if($file) {
			$data = @fread($file,filesize($attachment)); 
			fclose($file);
			$data = chunk_split(base64_encode($data));
			if(!empty($data)) {
				return $data;
			} else {
				throw new Exception('Unable to read file ['.$attachment.']');
			}
		} else {
			throw new Exception('Unable to open file ['.$attachment.']');
		}
		
	}
	
	/**
  * Uses a regular expression to validate the email address
  * @param string $emailaddress Email address to validate
  */
	private function validEmail($emailaddress='') {
		
		if(preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/",$emailaddress)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	/**
  * Adds something to the header
  * @param string $whattoadd Header to add
  */
	private function addHeader($whattoadd) {
		
		$this->headers .= $whattoadd;
		
	}
	
	/**
  * Adds a message to the email. This could be attachments, or the body, ect.
  * @param string $whattoadd What to add to the message
  */
	private function addMessage($whattoadd) {
		
		$this->message .= $whattoadd;
		
	}
	
	/**
  * Sends the email
  */
	public function sendMail() {
		
		if(!empty($this->from)) {
			
			if(!empty($this->to)) {
				
				$this->setHeaders();
				
				
				ini_set('track_errors','On');
				
				$mailsent = @mail($this->to,$this->subject,$this->message,$this->headers);
				if($mailsent) {
					return true;
				} else {
					throw new Exception('Unable to send email (error:'.$php_errormsg.')');
				}
				
			} else {
				
				throw new Exception('Must set TO before sending email: '.print_r($this->getParams(),true));
				
			}
			
		} else {
			
			throw new Exception('Must set FROM before sending email');
			
		}
		
	}
	
	/**
  * Adds the headers to the email
  */
	private function setHeaders() {
		
		$this->addHeader("MIME-Version: 1.0\n");
		$this->addHeader("Content-Type: multipart/mixed;\n");
		$this->addHeader(" boundary=\"".$this->boundary."\"\n");
		if(!empty($this->cc)) { $this->addHeader($this->cc."\n"); }
		if(!empty($this->bcc)) { $this->addHeader($this->bcc."\n"); }
		
	}
	
	/**
	 * sets the smtp server with ini_set
	 * @param string $server The name or IP address of the SMTP server you wish to use
	 */
	public function setSMTP($server) {
		
		if(!empty($server)) {
			
			ini_set("SMTP",$server);
			
		}
		
	}

}

?>