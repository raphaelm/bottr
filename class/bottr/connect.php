<?php
$server = (isset($argumente[0])) ? $argumente[0] : false;
$port   = (isset($argumente[1])) ? $argumente[1] : false;

if($server == false) $server = $self->server;
if($port == false) $port = $self->port;
$self->server = $server;
$self->port = $port;

if (function_exists("dns_get_record"))
{
	$record = dns_get_record("_xmpp-client._tcp.$server", DNS_SRV);
	if (!empty($record))
	{
		$server = $record[0]["target"];
		$port = $record[0]["port"];
	}
}

$self->socket = fsockopen($server, $port, $errno, $errstr, 5);

socket_set_blocking($self->socket, 0);
stream_set_blocking($self->socket, false);
socket_set_timeout($self->socket, 31536000);

if(!$self->socket){
	return false;
}
$self->connected = true;
$self->sendXML("<?xml version='1.0' encoding='UTF-8' ?" . "><stream:stream to='{$self->server}' xmlns='jabber:client' xmlns:stream='http://etherx.jabber.org/streams'>\n");
return true;
?>