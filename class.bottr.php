<?php
class bottr {
	public $server = 'jabber.org';
	public $port = 5222;
	public $username = '';
	public $password = '';
	public $resource = 'bottr';
	public $jid = '';

	public $timeout = 5;
	public $modulesfile = '';

	public $version = '1.0.0';

	private $socket;
	private $connected = false;
	private $auth_id = '';
	private $registered_modules = array();

	public function __destruct(){
		if($this->connected){
			$this->disconnect();
		}
	}

	public function __construct($modulexml = false){
		if($modulexml){
			$this->loadModuleConfig($modulexml);
			$this->modulesfile = $modulexml;
		}
	}

	public function terminate(){
		if($this->connected){
			$this->disconnect();
		}
		exit;
	}

	public function connect($server = false, $port = false){
		if($server == false) $server = $this->server;
		if($port == false) $port = $this->port;
		$this->server = $server;
		$this->port = $port;

		if (function_exists("dns_get_record"))
		{
			$record = dns_get_record("_xmpp-client._tcp.$server", DNS_SRV);
			if (!empty($record))
			{
				$server = $record[0]["target"];
				$port = $record[0]["port"];
			}
		}

		$this->socket = fsockopen($server, $port, $errno, $errstr, 5);

		socket_set_blocking($this->socket, 0);
		stream_set_blocking($this->socket, false);
		socket_set_timeout($this->socket, 31536000);

		if(!$this->socket){
			return false;
		}
		$this->connected = true;
		$this->sendXML("<?xml version='1.0' encoding='UTF-8' ?" . "><stream:stream to='{$this->server}' xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>\n");
	}

	public function auth($username = false, $password = false, $resource = false){
		if(!$this->connected){
			trigger_error('Not connected');
			return false;
		}
		if($username == false) $username = $this->username;
		if($password == false) $password = $this->password;
		if($resource == false) $resource = $this->resource;
		$this->username = $username;
		$this->password = $password;
		$this->resource = $resource;

		$this->auth_id	= "auth_" . md5(time() . microtime());
		$this->sendXML("<iq type='set' id='{$this->auth_id}'><query xmlns='jabber:iq:auth'><username>".htmlspecialchars($username)."</username><resource>".htmlspecialchars($resource)."</resource><password>".htmlspecialchars($password)."</password></query></iq>\n");
		
		if($this->jid == '') $this->jid = $username.'@'.$this->server;
		if(defined('DEBUG')) echo "JID: ".$this->jid."\n";
	}

	public function disconnect(){
		if($this->connected){
			fclose($this->socket);
		}
		$this->connected = false;
	}

	public function sendMessage($to, $body){
		if(!$this->connected){
			trigger_error('Not connected');
			return false;
		}
		$this->sendXML("<message id='{$this->auth_id}' type='chat' to='$to'><body>".htmlspecialchars($body)."</body></message>", false);
	}

	public function enterLoop(){
		while(true){
			$contents = "";
			while($contents == ""){
				$contents .= fgets($this->socket); 
			}
			if(defined('DEBUG')) echo "Rec: ".$contents."\n";
			$this->parse_incoming_xml($contents);

		}
	}

	public function loadModuleConfig($file = false){
		if(!$file) $file = $this->modulesfile;
		$this->registered_modules = array();
		$xml = simplexml_load_file($file);
		foreach($xml->module as $module){
			$attr = $module->attributes();
			$this->registerModule($attr->event, $module);
		}
	}

	private function registerModule($eventid, $module){
		$mod = array();
		$eventid = (string) $eventid;
		if($module->name) $mod['name'] = (string) $module->name;
		if($module->regex) $mod['regex'] = (string) $module->regex;
		if($module->php) (array) $mod['php'] = (array) $module->php;
		$this->registered_modules[$eventid][] = $mod;
	}

	private function parse_incoming_xml($contents){
		$xml = simplexml_load_string($contents);
		if(!$xml){
			trigger_error('Invalid XML: '.$contents);
			return false;
		}
		$attr = $xml->attributes();
		if(substr($contents, 0, 8) == '<message' && isset($xml->body)){
			$from = trim($attr->from);
			$body = trim($xml->body);
			$this->callModules("message", array("from" => $from, "body" => $body), $body);
		}elseif(substr($contents, 0, 8) == '<message' && !isset($xml->body)){
			$from = trim($attr->from);
			$body = false;
			$this->callModules("typing", array("from" => $from), $from);
		}elseif($attr->type == 'subscribe'){
			$this->callModules("subscribe", array("from" => trim($attr->from), "to" => trim($attr->to)), $from);
		}else{
			var_dump($xml);
			file_put_contents('new.log', $contents."\n\n", FILE_APPEND);
		}
	}

	private function callModules($event = "message", $parameters = array(), $paramtoregex = false){
		if(!isset($this->registered_modules[$event])) return true;
		foreach($this->registered_modules[$event] as $module){
			if(isset($module['regex']) && $paramtoregex && !preg_match($module['regex'], $paramtoregex)) continue;
			if(isset($module['php'])){
				if(isset($module['php']['file'])){
					include_once($module['php']['file']);
					call_user_func($module['php']['function'], &$this, $parameters);
				}else{
					call_user_func($module['php']['function'], &$this, $parameters);
				}
			}
		}
	}

	public function sendXML($xml, $expect_response = true, $debug = DEBUG){
		if($debug) echo "Send: $xml\n";
		if(!$this->connected){
			trigger_error('Not connected');
			return false;
		}
		fputs($this->socket, $xml);
		if($expect_response){
			$contents = "";
			while($contents == "") {
				$contents .= fgets($this->socket);
			}
			if($debug) echo "Get: $contents\n";
			return $contents;
		}
	}

	public function sendPresence($status, $show = ''){ // show can be: chat, dnd, away, xa
		$this->sendXML("<presence from='{$this->jid}'><status>$status</status><show>$show</show></presence>", false);
	}

	public function sendSubscribed($to){
		$this->sendXML("<presence from='{$this->jid}' to='$to' type='subscribed' />", false);
	}


	public function sendSubscribe($to){
		$this->sendXML("<presence from='{$this->jid}' to='$to' type='subscribe' />", false);
	}
}