<?php

class Status {

	private $data = null;
	private $conn = null;
	
	public function Status($server) {
		if (empty($server) || !$this->valideIP($server)) {
			throw new StatusException("Address format is not valid.", 1);
		}

		$this->data = array(
			'online' => false,
			'host' => $server,
			'num_players' => 0,
			'max_players' => 0,
		);

		$this->process();
	}

	private function init() {
		$server = explode(":", $this->host);
		$ip = $server[0];
		$port = $server[1];
		$this->conn = fsockopen("udp://" . $ip, $port, $grbr, $grsl, 4);


		if ($this->conn) {
			fwrite($this->conn, "\xFF\xFF\xFF\xFF\x69");
			fread($this->conn, 4);
			$sstatus = socket_get_status($this->conn);
			$this->data['online'] = ($sstatus["unread_bytes"] == 0) ? false : true;
		}
	}

	private function valideIP($server) {
		$server = explode(":", $server);
		$ip = $server[0];
		$port = $server[1];

		return (filter_var($ip, FILTER_VALIDATE_IP) && is_numeric($port)) ? true : false;
	}

	private function process() {
		$this->init();

		if ($this->online) {
			fwrite($this->conn, "\xFF\xFF\xFF\xFFTSource Engine Query\x00");
			fread($this->conn, 6);
			
			
			$type = fread($this->conn,1);
						
			if(!in_array($type, array("\x49", "\x44", "\x6d")))
			{
				return null;
			}
			
			// 6d -> cs1.6
			if (bin2hex($type) == '6d'){
				$this->getString();
			}else {
				$this->data['protocol'] = $this->getInt8();
			}
			$this->data['name'] = $this->getString();
			$this->data['map'] = $this->getString();
			$this->data['dir'] = $this->getString();
			$this->data['desc'] = $this->getString();
			
			if (bin2hex($type) != '6d'){
				 fread($this->conn,2);
			}
			
			$this->data['num_players'] = $this->getInt8();
			$this->data['max_players'] = $this->getInt8();
			
			
		}
	}

	private function getString(){
		$text = "";
		while (($content = fread($this->conn, 1)) != "\x00") {
				$text .= $content;
			}
		return $text;
	}
	
	private function getInt8(){
		$val = fread($this->conn, 1);
		return ord($val);
	}
	
	private function getInt16(){
		$val = fread($this->conn, 2);
		return bindec($val);
	}
	
	public function __get($name) {
		if ($this->data !== null && isset($this->data[$name])) {
			return $this->data[$name];
		}
		return null;
	}

	
	function __destruct(){
		fclose($this->conn);		
	}
}

class StatusException extends Exception {
	
}
