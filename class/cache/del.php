<?php
// 
// SYNTAX
// del(str category, str name);
//
global $bottr;
$category = $argumente[0];
$name = $argumente[1];

if (isset($self->thecache[$category][$name])) {
	$bottr->donotexecutesoon($self->thecache[$category][$name]['token']);
	unset($self->thecache[$category][$name]);
}

if (count($self->thecache[$category]) == 0) {
	unset($self->thecache[$category]);
}

return true;
?>