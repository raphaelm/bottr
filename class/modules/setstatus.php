<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];
if(trim(substr($parameters['body'], 9)) == '')
	$args = array();
else
	$args = explode(' ', trim(substr($parameters['body'], 9)));

if(count($args) == 0){
	$bottr->sendMessage($parameters['from'], "Syntax (3 Möglicgkeiten):\nsetstatus <type> <status>\nsetstatus <status>\nsetstatus <type>\n<type> kann sein: chat (\"online\"; alias: online), dnd (\"beschäftigt\"; alias: busy), away (\"abwesend\"; alias: afk) oder xa (eine Art \"erweitertes\" away)\n<status> kann auch Leerzeichen enthalten, du musst den Parameter nicht in Anführungszeichen einschließen!");
	return true;
}

$types = array( // some aliases
'chat' => 'chat',
'online' => 'chat',
'dnd' => 'dnd',
'busy' => 'dnd',
'away' => 'away',
'afk' => 'away',
'xa' => 'xa');

if(isset($types[$args[0]])){
	$type = $types[$args[0]];
	unset($args[0]);
	$status = join(' ', $args);
}else{
	$status = trim(substr($parameters['body'], 9));
	$type = 'chat';
}

$bottr->sendPresence($status, $type);