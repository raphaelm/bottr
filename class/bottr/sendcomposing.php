<?php
$to = (isset($argumente[0])) ? $argumente[0] : false;
$type = (isset($argumente[0])) ? $argumente[0] : 'composing'; // composing, paused, active, inactive, gone
$self->sendXML("<message from='$self->jid' to='$to' type='chat' id='$self->auth_id'><composing xmlns='http://jabber.org/protocol/chatstates'/></message>");
?>