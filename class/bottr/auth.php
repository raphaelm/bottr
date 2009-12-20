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
$self->sendXML("<iq type='set' id='{$self->auth_id}'><query xmlns='jabber:iq:auth'><username>".htmlspecialchars($username)."</username><resource>".htmlspecialchars($resource)."</resource><password>".htmlspecialchars($password)."</password></query></iq>\n");

if($self->jid == '') $self->jid = $username.'@'.$self->server;
if(defined('DEBUG')) echo "JID: ".$self->jid."\n";
?>