<?php
$jid = (isset($argumente[0])) ? $argumente[0] : false;
$jid = preg_replace('#^([^@]*)@([^/]*)/?.*$#i', '$1@$2', $jid); // Resource rauslöschen

/* Funktion bottr::isAdmin($jid)
 * Diese Funktion wird aufgerufen um zu überprüfen, ob ein User mit der
 * Jabber-ID $jid Admininstratorrechte hat. Wenn ja, muss sie true
 * zurückgeben, wenn nein, false.
 * Sie wird beispielsweise bei Modulaufrufen wie "terminate" genutzt
 * (in der modules.xml mit <adminonly>true</adminonly> gekennzeichnet).
 * Diese Funktion MUSS angepasst werden, wenn du den Bot auf deinem
 * Server betreibst. Du kannst einfügen, was du willst, beispielsweise:

if($jid == 'rami@jabber.ccc.de') // Wenn es unser Admin ist
{
    return true;
}
else
{
    return false;

}

 * Um nur rami@jabber.ccc.de als Admin zuzulassen. WIR nutzen bei
 * unserer Instanz von bottr eine User-Datenbank.
 *
 *
 * This function will be called to check, whether a user with jabber id
 * $jid is an admin. If yes, it has to return true, otherwise false.
 * The function is called for example by modules like "terminate",
 * marked with <adminonly>true</adminonly> in modules.xml. This function
 * HAS TO BE changed if you want to use bottr on your server. You can
 * use any code you want to, for example

if($jid == 'rami@jabber.ccc.de') // Wenn es unser Admin ist
{
    return true;
}
else
{
    return false;

}

 * if you want to give rami@jabber.ccc.de administrator privileges.
 * WE are using an user database with MySQL on our bottr bot.
 */

global $db;

$query = $db->query('SELECT `user_id` FROM `users` WHERE `user_jid` = \''.mysql_real_escape_string($jid).'\' AND `user_admin` = 1');
if(mysql_num_rows($query) === 1)
	$admin = true;
else
	$admin = false;
$db->free_all_results();
return $admin;