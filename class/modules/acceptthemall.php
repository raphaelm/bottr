<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

$bottr->sendSubscribed($parameters['from']);
$bottr->sendMessage($parameters['from'], 'Hallo, ich bin bottr! Schön, dich kennen zu lernen ;-)');