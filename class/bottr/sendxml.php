<?php
$xml             = (isset($argumente[0])) ? $argumente[0] : false;
$expect_response = (isset($argumente[1])) ? $argumente[1] : true;
$debug           = (isset($argumente[2])) ? $argumente[2] : DEBUG;

if($debug) echo "Send: $xml\n";
if(!$self->connected){
	trigger_error('Not connected');
	return false;
}
fputs($self->socket, $xml);
if($expect_response){
	$contents = "";
	while($contents == "") {
		$contents .= fgets($self->socket);
	}
	if($debug) echo "Get: $contents\n";
	return $contents;
}
?>