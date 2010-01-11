<?php
$to   = (isset($argumente[0])) ? $argumente[0] : false;
$body = (isset($argumente[1])) ? $argumente[1] : false;
$enc  = (isset($argumente[2])) ? $argumente[2] : true;

if(!$self->connected){
	trigger_error('Not connected');
	return false;
}

// UTF-8?
if (mb_detect_encoding($body.' ', 'UTF-8, ISO-8859-1') != 'UTF-8') {
	$body = utf8_encode($body);
}

if($enc) $body = htmlspecialchars($body);
$self->sendXML("<message id='{$self->auth_id}' type='chat' to='$to'><body>".$body."</body></message>", false);
?>