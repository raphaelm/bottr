<?php
$start = microtime(1);
$_s_next = $start+1;
$_m_next = $start+60;
$_h_next = $start+3600;
while($self->connected){
	$contents = "";
	while($contents == ""){
		usleep(50);
		$new = fgets($self->socket, 16384);
		$contents .= $new;

		// Minute-Triggers
		$now = microtime(1);
		if($now > $_s_next){
			$_s_next = $now+1;
			$self->callModules("everysecond", array());
		}
		if($now > $_m_next){
			$_m_next = $now+60;
			$self->callModules("everyminute", array());
		}
		if($now > $_h_next){
			$_h_next = $now+3600;
			$self->callModules("everyhour", array());
		}
		foreach($self->schedule as $key => $schedule){
			if($now > $schedule['timestamp']){
				call_user_func_array($schedule['callback'], $schedule['param_arr']);
				unset($self->schedule[$key]);
			}
		}
	}
	if(defined('DEBUG')) echo "Rec: ".$contents."\n";
	$self->incoming_buffer .= $contents;
	$self->parse_incoming_xml();
}
?>
