<?php
global $modules;

$event        = (isset($argumente[0])) ? $argumente[0] : 'message';
$parameters   = (isset($argumente[1])) ? $argumente[1] : array();
$paramtoregex = (isset($argumente[2])) ? $argumente[2] : false;

if(!isset($self->registered_modules[$event])) return true;
foreach($self->registered_modules[$event] as $module){
	if(isset($module['regex']) && $paramtoregex && !preg_match($module['regex'], $paramtoregex)) continue;
	if(isset($module['php'])){
		call_user_func(array($modules, $module['php']['function']), $self, $parameters);
	}
}
?>