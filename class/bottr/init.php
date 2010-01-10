<?php
// Variablen Setzen
$self->server = 'jabber.org';
$self->port = 5222;
$self->username = '';
$self->password = '';
$self->resource = 'bottr';
$self->jid = '';

$self->timeout = 5;
$self->modulesfile = '';

$self->version = '1.0.0';

$self->socket = false;
$self->connected = false;
$self->auth_id = '';
$self->registered_modules = array();
$self->schedule = array();

$modulexml = (isset($argumente[0])) ? $argumente[0] : false;

if($modulexml){
	$self->loadModuleConfig($modulexml);
	$self->modulesfile = $modulexml;
}

?>