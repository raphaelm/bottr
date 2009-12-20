<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('classloader.php');
define('DEBUG', 1);

$bottr = new pseudoclass('bottr');
$bottr->init('modules.xml');
$bottr->connect('nomoketo.de', 5222);
$bottr->auth('rami-test1', 'AS4623874ALSDKHC)Q§$(&)(', 'bot');
$bottr->enterLoop();