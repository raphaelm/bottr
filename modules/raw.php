<?php
function mod_raw(&$bottr, $parameters){
	preg_match('/^raww? (.*)$/i', $parameters['body'], $treffer);
	$raw = $bottr->sendXML($treffer[1], (substr($parameters['body'], 3, 1) == 'w'));
	$bottr->sendMessage($parameters['from'], 'Raw XML sent.'.((substr($parameters['body'], 3, 1) == 'w') ? ' Answer: '.$raw : ''));
}