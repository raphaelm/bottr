<?php
while(true){
	$contents = "";
	while($contents == ""){
		usleep(50);
		$contents .= fgets($self->socket); 
	}
	if(defined('DEBUG')) echo "Rec: ".$contents."\n";
	$self->parse_incoming_xml($contents);
}
?>