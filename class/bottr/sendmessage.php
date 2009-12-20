<?php
$to   = (isset($argumente[0])) ? $argumente[0] : false;
$body = (isset($argumente[1])) ? $argumente[1] : false;

if(!$self->connected){
	trigger_error('Not connected');
	return false;
}
$self->sendXML("<message id='{$self->auth_id}' type='chat' to='$to'><body>".htmlspecialchars($body)."</body></message>", false);
?>