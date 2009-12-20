<?php
$to = (isset($argumente[0])) ? $argumente[0] : false;

$this->sendXML("<presence from='{$self->jid}' to='$to' type='subscribe' />", false);
?>