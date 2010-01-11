<?php
// 
// SYNTAX
// add(str category, str name, int cacheduration, mixed data);
// (cacheduration in Sekunden)
//
global $bottr;
$category = $argumente[0];
$name = $argumente[1];
$duration = $argumente[2];
$data = $argumente[3];

if (!isset($self->thecache[$category])) {
	$self->thecache[$category] = array();
}
if (isset($self->thecache[$category][$name])) {
	$bottr->donotexecutesoon($self->thecache[$category][$name]['token']);
}

$bottr->debug($category.'->'.$name.' wurde gecacht.');

$token = $bottr->executesoon(time()+$duration, array($self, 'del'), array($category, $name));
$self->thecache[$category][$name] = array('token'=>$token,'data'=>$data);

return true;
?>