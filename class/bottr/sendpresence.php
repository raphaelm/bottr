<?php
$status = (isset($argumente[0])) ? $argumente[0] : false;
$show   = (isset($argumente[1])) ? $argumente[1] : '';

$this->sendXML("<presence from='{$self->jid}'><status>$status</status><show>$show</show></presence>", false);
?>