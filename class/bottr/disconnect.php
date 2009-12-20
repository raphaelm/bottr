<?php
if($self->connected){
	fclose($self->socket);
}
$self->connected = false;
?>