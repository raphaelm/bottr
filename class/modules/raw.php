<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

preg_match('/^raww? (.*)$/i', $parameters['body'], $treffer);
$raw = $bottr->sendXML(htmlspecialchars_decode($treffer[1]), (substr($parameters['body'], 3, 1) == 'w'));
$bottr->sendMessage($parameters['from'], 'Raw XML sent.'.((substr($parameters['body'], 3, 1) == 'w') ? ' Answer: '.$raw : ''));