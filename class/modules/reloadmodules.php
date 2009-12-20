<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

$bottr->loadModuleConfig(false);
$bottr->sendMessage($parameters['from'], 'Modules reloaded');