<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

$bottr->sendMessage($parameters['from'], 'PONG!');