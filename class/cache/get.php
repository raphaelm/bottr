<?php
// 
// SYNTAX
// get(str category, str name);
//
global $bottr;
$category = $argumente[0];
$name = $argumente[1];

if (isset($self->thecache[$category]) && isset($self->thecache[$category][$name])) {
	return $self->thecache[$category][$name]['data'];
}
return false;
?>