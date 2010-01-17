<?php
//
// SYNTAX
// del(str category, str name);
//
global $bottr;
$category = $argumente[0];
$name = $argumente[1];

$bottr->debug($category.'->'.$name.' wurde aus dem cache gelöscht.');

if (isset($self->thecache[$category][$name])) {
	$bottr->donotexecutesoon($self->thecache[$category][$name]['token']);
	unset($self->thecache[$category][$name]);
}

if (isset($self->thecache[$category]) && count($self->thecache[$category]) == 0) {
	unset($self->thecache[$category]);
}

return true;
?>