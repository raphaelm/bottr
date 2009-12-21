<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('classloader.php');
define('DEBUG', 1);

// Datenbank
$db = new pseudoclass('database');
$db->init();

$debug_recipients = array(
	// Empfängername			// Derzeit aktiviert
	'rami@jabber.ccc.de' => true);
	// PHP-FEHLERMELDUNGEN WERDEN SO ODER SO GESENDET!!

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	global $bottr;
    switch ($errno) {
    case E_USER_ERROR:
        $bottr->debug("Fatal Error: $errstr in $errfile (line $errline)", true);
        break;

    case E_USER_WARNING:
        $bottr->debug("Warning: $errstr in $errfile (line $errline)", true);
        break;

    case E_USER_NOTICE:
        $bottr->debug("Notice: $errstr in $errfile (line $errline)", true);
        break;

    default:
		$bottr->debug("Unknown: $errstr in $errfile (line $errline)", true);
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
$old_error_handler = set_error_handler("myErrorHandler");

// Init
$bottr = new pseudoclass('bottr');
$modules = new pseudoclass('modules');
$bottr->init('modules.xml');
$bottr->connect('nomoketo.de');
$bottr->auth('rami-test1', trim(file_get_contents('passwd')), 'bot');
$bottr->sendPresence(';-)');
$bottr->enterLoop();
