<?php
error_reporting(E_ALL);
include('class.bottr.php');
define('DEBUG', 1);

$bottr = new bottr('modules.xml');
$bottr->connect('nomoketo.de', 5222);
$bottr->auth('rami-test1', 'AS4623874ALSDKHC)Q§$(&)(', 'bot');
$bottr->enterLoop();