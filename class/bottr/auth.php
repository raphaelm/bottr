<?php
$username = (isset($argumente[0])) ? $argumente[0] : false;
$password = (isset($argumente[1])) ? $argumente[1] : false;
$resource = (isset($argumente[2])) ? $argumente[2] : false;

if(!$self->connected){
	trigger_error('Not connected');
	return false;
}
if($username == false) $username = $self->username;
if($password == false) $password = $self->password;
if($resource == false) $resource = $self->resource;
$self->username = $username;
$self->password = $password;
$self->resource = $resource;

$self->auth_id	= "auth_" . md5(time() . microtime());

$authentication = $self->sendXML("<iq type='set' id='{$self->auth_id}'><query xmlns='jabber:iq:auth'><username>".htmlspecialchars($username)."</username><resource>".htmlspecialchars($resource)."</resource><password>".htmlspecialchars($password)."</password></query></iq>\n", true);

$xml = simplexml_load_string($authentication);
if(isset($xml->error)){
	trigger_error('Authentication failed!');
	$self->callModules('startup_after_auth_failed');
	return false;
}

if($self->jid == '') $self->jid = $username.'@'.$self->server.'/'.$resource;
if(defined('DEBUG')) echo "JID: ".$self->jid."\n";

if(file_exists('presence.dat')){
	$parts = explode("#", file_get_contents('presence.dat'));
	$self->sendPresence(trim($parts[0]), trim($parts[1]));
}

$self->callModules('startup_after_auth');
return true;
