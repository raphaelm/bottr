<?php
global $debug_recipients;
$bottr      = &$argumente[0];
$parameters = $argumente[1];

preg_match("|^([^/]+)|", $parameters['from'], $userescaped);
$userescaped = strtolower($userescaped[1]);
if (isset($debug_recipients[$userescaped])) {
	$debug_recipients[$userescaped] = !$debug_recipients[$userescaped];
	if ($debug_recipients[$userescaped]) {
		$bottr->sendMessage($parameters['from'], 'Debug ist fuer dich nun AN.');	
	} else {
		$bottr->sendMessage($parameters['from'], 'Debug ist fuer dich nun AUS.');	
	}
} else {
	$bottr->sendMessage($parameters['from'], 'Was geht dich das an? Du darfst eh nicht!');	
}