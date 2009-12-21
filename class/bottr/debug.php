<?php
$txt = $argumente[0];
$do_anyway = (isset($argumente[1]) && $argumente[1] === true);

global $debug_recipients;
foreach($debug_recipients as $jid => $activated) {
	if ($activated || $do_anyway) $self->sendMessage($jid, 'Debug: '.$txt);
}
?>