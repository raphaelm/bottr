<?php
function mod_ping(&$bottr, $parameters){
	$bottr->sendMessage($parameters['from'], 'PONG!');
}