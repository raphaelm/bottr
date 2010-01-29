<?php
if($self->connected){
	$self->sendXML('</stream:stream>', false);
	fclose($self->socket);
	$self->connected = false;
	return true;
}
return false;
?>
