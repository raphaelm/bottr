<?php
global $modules, $current_user;

$event        = (isset($argumente[0])) ? $argumente[0] : 'message';
$parameters   = (isset($argumente[1])) ? $argumente[1] : array();
$paramtoregex = (isset($argumente[2])) ? $argumente[2] : false;

if(isset($parameters['from'])) $current_user = $parameters['from'];

if(!isset($self->registered_modules[$event])) return true;
foreach($self->registered_modules[$event] as $module){
	if(isset($module['regex']) && $paramtoregex && !preg_match($module['regex'], $paramtoregex)) continue;
	if(isset($module['adminonly']) && $module['adminonly'] == true && isset($parameters['from']) && !$self->isAdmin($parameters['from'])){
		$self->debug($parameters['from'].' isAdmin FAILED.');
		continue;
	}elseif(isset($module['php'])){
		if(defined('DEBUG')) echo "Call Module: modules->".$module['php']['function']."\n";
		call_user_func(array($modules, $module['php']['function']), $self, $parameters);
	}
}
?>