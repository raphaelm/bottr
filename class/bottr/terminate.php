<?php
$exit = (isset($argumente[0])) ? $argumente[0] : 0;
if($self->connected){
	$self->disconnect();
}
exit($exit);
?>