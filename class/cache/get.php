<?php
// 
// SYNTAX
// get(str category, str name);
//
global $bottr;
$category = $argumente[0];
$name = $argumente[1];


if (isset($self->thecache[$category]) && isset($self->thecache[$category][$name])) {
	$bottr->debug($category.'->'.$name.' wurde aus dem Cache geladen.');
	return $self->thecache[$category][$name]['data'];
}
return false;
?>