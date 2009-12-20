<?php
$contents = (isset($argumente[0])) ? trim($argumente[0]) : false;

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
}elseif(substr($contents, 0, 8) == '<message' && isset($xml->composing)){
	$from = trim($attr->from);
	$self->callModules("chatstate_composing", array("from" => $from), $from);
}elseif(substr($contents, 0, 8) == '<message' && isset($xml->paused)){
	$from = trim($attr->from);
	$self->callModules("chatstate_paused", array("from" => $from), $from);
}elseif(substr($contents, 0, 8) == '<message' && isset($xml->active)){
	$from = trim($attr->from);
	$self->callModules("chatstate_active", array("from" => $from), $from);
}elseif(substr($contents, 0, 8) == '<message' && isset($xml->inactive)){
	$from = trim($attr->from);
	$self->callModules("chatstate_inactive", array("from" => $from), $from);
}elseif(substr($contents, 0, 8) == '<message' && isset($xml->gone)){
	$from = trim($attr->from);
	$self->callModules("chatstate_gone", array("from" => $from), $from);
}elseif(substr($contents, 0, 8) == '<message'){ // andere messages außer denen, die wir schon geprüft haben

}elseif($attr->type == 'subscribe'){
	$self->callModules("subscribe", array("from" => trim($attr->from), "to" => trim($attr->to)), $from);
}elseif(substr($contents, 0, 10) == "<presence "){ // ne andere presence außer denen, die wir geprüft haben
	// tue nichts
}else{
	var_dump($xml);
	file_put_contents('new.log', $contents."\n\n", FILE_APPEND);
}
?>