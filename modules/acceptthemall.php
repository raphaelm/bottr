<?php
function mod_acceptthemall(&$bottr, $parameters){
	$bottr->sendSubscribed($parameters['from']);
	$bottr->sendMessage($parameters['from'], 'Hallo, ich bin bottr! Schön, dich kennen zu lernen ;-)');
}