<?php
$to = (isset($argumente[0])) ? $argumente[0] : false;

$self->sendXML("<presence from='{$self->jid}' to='$to' type='unsubscribed' />", false);
?>