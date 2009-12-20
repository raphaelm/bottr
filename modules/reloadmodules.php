<?php
function mod_reloadmodules(&$bottr, $parameters){
	$bottr->loadModuleConfig(false);
	$bottr->sendMessage($parameters['from'], 'Modules reloaded');
}