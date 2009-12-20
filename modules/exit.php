<?php
function mod_exit(&$bottr, $parameters){
	$bottr->sendXML("</stream:stream>");
	$bottr->terminate();
}