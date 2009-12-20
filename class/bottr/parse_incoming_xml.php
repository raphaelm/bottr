<?php
$contents = (isset($argumente[0])) ? $argumente[0] : false;

$xml = simplexml_load_string($contents);
if(!$xml){
	trigger_error('Invalid XML: '.$contents);
	return false;
}
$attr = $xml->attributes();
if(substr($contents, 0, 8) == '<message' && isset($xml->body)){
	$from = trim($attr->from);
	$body = trim($xml->body);
	$self->callModules("message", array("from" => $from, "body" => $body), $body);
}elseif(substr($contents, 0, 8) == '<message' && !isset($xml->body)){
	$from = trim($attr->from);
	$body = false;
	$self->callModules("typing", array("from" => $from), $from);
}elseif($attr->type == 'subscribe'){
	$self->callModules("subscribe", array("from" => trim($attr->from), "to" => trim($attr->to)), $from);
}else{
	var_dump($xml);
	file_put_contents('new.log', $contents."\n\n", FILE_APPEND);
}
?>