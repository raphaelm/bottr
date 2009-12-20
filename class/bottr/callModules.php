<?php
$event        = (isset($argumente[0])) ? $argumente[0] : 'message';
$parameters   = (isset($argumente[1])) ? $argumente[1] : array();
$paramtoregex = (isset($argumente[2])) ? $argumente[2] : false;

if(!isset($self->registered_modules[$event])) return true;
foreach($self->registered_modules[$event] as $module){
	if(isset($module['regex']) && $paramtoregex && !preg_match($module['regex'], $paramtoregex)) continue;
	if(isset($module['php'])){
		if(isset($module['php']['file'])){
			include_once($module['php']['file']);
			call_user_func($module['php']['function'], &$self, $parameters);
		}else{
			call_user_func($module['php']['function'], &$self, $parameters);
		}
	}
}
?>