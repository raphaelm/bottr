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

$plainbody = preg_replace("#<a[^>]*href=['|\"]([^'\">]*)['|\"][^>]*>(.*)</a>#iU", '\\2 (\\1)', $body);
$plainbody = strip_tags($plainbody);

if($plainbody === $body)
	$xml = "<message type='chat' to='$to'><body>".$body."</body></message>";
else
	$xml = "<message type='chat' to='$to'><html xmlns='http://jabber.org/protocol/xhtml-im'><body xmlns='http://www.w3.org/1999/xhtml'>".$body."</body></html><body>".$plainbody."</body></message>";
	
$self->sendXML("<message id='{$self->auth_id}' type='chat' to='$to'><body>".$body."</body></message>", false);
?>
