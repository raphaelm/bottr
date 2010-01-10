<?php
$eventid = (isset($argumente[0])) ? $argumente[0] : false;
$module  = (isset($argumente[1])) ? $argumente[1] : false;

$mod = array();
$eventid = (string) $eventid;
if($module->name) $mod['name'] = (string) $module->name;
if($module->regex) $mod['regex'] = (string) $module->regex;
if($module->php) (array) $mod['php'] = (array) $module->php;
$mod['adminonly'] = (isset($module->adminonly) && ((string) $module->adminonly) == 'true');
$self->registered_modules[$eventid][] = $mod;
?>