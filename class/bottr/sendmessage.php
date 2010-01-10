<?php
$to   = (isset($argumente[0])) ? $argumente[0] : false;
$body = (isset($argumente[1])) ? $argumente[1] : false;
$enc  = (isset($argumente[2])) ? $argumente[2] : true;

if(!$self->connected){
	trigger_error('Not connected');
	return false;
}
if($enc) $body = htmlspecialchars($body);
$self->sendXML("<message id='{$self->auth_id}' type='chat' to='$to'><body>".$body."</body></message>", false);
?>