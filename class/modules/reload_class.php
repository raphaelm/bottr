<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

preg_match('/^reload_class ([^ ]*)$/i', $parameters['body'], $treffer);
if (!in_array($treffer[1], array('modules', 'bottr', 'db', 'user', 'cache', 'say'))) {
	$bottr->sendMessage($parameters['from'], 'Diese Klasse kann nicht verändert werden.');
	return;
}
$success = $fail = 0;

eval('global $'.$treffer[1].';');

/*foreach(glob('class/'.$treffer[1].'/*.php') as $file){
	$file = preg_match('#^class/'.$treffer[1].'/(.*).php$#', $file, $treffer2);
	eval('$return = $'.$treffer[1].'->add_function(\''.$treffer2[1].'\');');
	if ($return) {
		$success++;
	} else {
		$fail++;
	}
}

*/
eval('$funcs = array_keys($'.$treffer[1].'->function_builds);');
foreach($funcs as $func){
	eval('$return = $'.$treffer[1].'->add_function(\''.$func.'\');');
	if ($return) {
		$success++;
	} else {
		$fail++;
	}
}

	$bottr->sendMessage($parameters['from'], $success.' Funktionen neu geladen, '.$fail.' fehlgeschlagen.');