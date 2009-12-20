<?php
$file   = (isset($argumente[0])) ? $argumente[0] : $self->modulesfile;

$self->registered_modules = array();
$xml = simplexml_load_file($file);
foreach($xml->module as $module){
	$attr = $module->attributes();
	$self->registerModule($attr->event, $module);
}
?>