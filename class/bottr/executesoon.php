<?php
$timestamp = (isset($argumente[0])) ? $argumente[0] : false;
$callback = (isset($argumente[1])) ? $argumente[1] : false;
$param_arr = (isset($argumente[2])) ? $argumente[2] : false;

$uniqueid = md5($timestamp.$callback.$param_arr);
$self->schedule[$uniqueid] = array('timestamp' => $timestamp, 'callback' => $callback, 'param_arr' => $param_arr);
return $uniqueid;
