<?php
function mod_setstatus(&$bottr, $parameters){
	preg_match('/^raw ?([^ ]*) (.*)$/i', $parameters['body'], $treffer);
	sendPresence($status, $show = '')
	$raw = $bottr->sendXML($treffer[1], (substr($parameters['body'], 3, 1) == 'w'));
	$bottr->sendMessage($parameters['from'], 'Raw XML sent.'.((substr($parameters['body'], 3, 1) == 'w') ? ' Answer: '.$raw : ''));
	$bottr->sendSubscribed($parameters['from']);
	$bottr->sendMessage($parameters['from'], 'Hallo, ich bin bottr! Schön, dich kennen zu lernen ;-)');
}