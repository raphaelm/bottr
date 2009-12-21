<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

preg_match('/^reload_func ([^ ]*) ([^ ]*)$/i', $parameters['body'], $treffer);
if (!in_array($treffer[1], array('modules', 'bottr', 'db'))) {
	$bottr->sendMessage($parameters['from'], 'Diese Klasse kann nicht verändert werden.');
	return;
}

eval('$return = $'.$treffer[1].'->add_function(\''.$treffer[2].'\');');
if ($return) {
	$bottr->sendMessage($parameters['from'], 'Funktion neu geladen.');
} else {
	$bottr->sendMessage($parameters['from'], 'Funktion nicht neu geladen. Syntax-Error? Für Details siehe im Error-Log nach.');
}