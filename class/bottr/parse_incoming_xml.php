<?php
$contents = (isset($argumente[0])) ? trim($argumente[0]) : $self->incoming_buffer;

// Ist der Stream tot?
if(strpos($contents, '</stream:stream>') !== false || strpos($contents, '</stream:error>') !== false){
	$self->connected = false;
	$self->terminate();
}

// Erstmal überprüfen, wie das End-Element heißen muss
if(substr($contents, 0, 8) == '<message'){ // <message
	$closetag = '</message>';
}elseif(substr($contents, 0, 9) == '<presence'){ // <presence
	$closetag = '</presence';
}elseif(substr($contents, 0, 3) == '<iq'){ // <iq
	$closetag = '</iq>';
}elseif(substr($contents, 0, 7) == '<stream'){ // <stream
	if(substr($contents, 0, 14) == '<stream:stream') // <stream:stream
		$closetag = '</stream:stream>';
	elseif(substr($contents, 0, 13) == '<stream:error') // <stream:error
		$closetag = '</stream:error>';
	else
		$closetag = '</stream';
}elseif(substr($contents, 0, 5) == '<?xml'){ // header
	$closetag = '?>';
}

if((substr($contents, -2) == '/>' || substr($contents, -3) == '/ >') && strpos($contents, '<',1) === false){
	$code = $contents;
}else{
	if($closetag !== ''){
		$pos = strpos($contents, $closetag);
		if($pos === false){
			//$self->debug('Parser: verkrüppeltes XML (kommt noch was?): '.$contents); # i think we don't need it normally
			return false;
		}else{
			$after = substr($contents, ($pos + strlen($closetag)));
		}
	}
	$code = substr($contents, 0, (1+$pos + strlen($closetag)));
}
if(substr($code, -1) == '<'){
	$code = substr($code, 0, strlen($code)-1);
}
// Jetzt geht der Spaß erst los!
if(substr($contents, 0, 8) == '<message'){ // <message
	// Lade String nach SimpleXML
	$xml = simplexml_load_string($code);
	if(!$xml){
		trigger_error('Invalid XML: '.$code);
		return false;
	}
	$attr = $xml->attributes();

	if(isset($xml->body)){
		$module_parameters = array();
		$module_parameters['from'] = isset($attr->from) ? trim($attr->from) : false;
		$module_parameters['to'] = isset($attr->to) ? trim($attr->to) : false;
		$module_parameters['type'] = isset($attr->type) ? trim($attr->type) : false;
		$module_parameters['body'] = isset($xml->body) ? trim($xml->body) : false;
		$module_parameters['subject'] = isset($xml->subject) ? trim($xml->subject) : false;
		$module_parameters['thread'] = isset($xml->thread) ? trim($xml->thread) : false;

		$self->callModules("message", $module_parameters, $module_parameters['body']);
	}elseif(isset($xml->composing) || isset($xml->paused) || isset($xml->active) || isset($xml->inactive) || isset($xml->gone)){
		$module_parameters = array();
		$module_parameters['from'] = isset($attr->from) ? trim($attr->from) : false;
		$module_parameters['to'] = isset($attr->to) ? trim($attr->to) : false;
		$module_parameters['type'] = isset($attr->type) ? trim($attr->type) : false;
		$module_parameters['subject'] = isset($xml->subject) ? trim($xml->subject) : false;
		$module_parameters['thread'] = isset($xml->thread) ? trim($xml->thread) : false;
		$chatstate = isset($xml->composing) ? 'composing' :
				(isset($xml->paused) ? 'paused' : (isset($xml->active) ? 'active' :
				(isset($xml->inactive) ? 'inactive' : (isset($xml->gone) ? 'gone' : 'composing'))));
		$self->callModules("chatstate_$chatstate", $module_parameters, $module_parameters['from']);
	}
}elseif(substr($contents, 0, 9) == '<presence'){ // <presence
	// Lade String nach SimpleXML
	$xml = simplexml_load_string($code);
	if(!$xml){
		trigger_error('Invalid XML: '.$code);
		return false;
	}
	$attr = $xml->attributes();

	$type = isset($attr->type) ? $attr->type : false;
		$module_parameters['from'] = isset($attr->from) ? trim($attr->from) : false;
	$module_parameters['to'] = isset($attr->to) ? trim($attr->to) : false;

	if($type == 'error')
		$module_parameters['error'] = isset($xml->error) ? trim($xml->error) : false;
	if(!$type){
		$module_parameters['show'] = isset($xml->show) ? trim($xml->show) : false;
		$module_parameters['status'] = isset($xml->status) ? trim($xml->status) : false;
		$module_parameters['priority'] = isset($xml->priority) ? trim($xml->priority) : false;
		$module = 'presence';
	}else $module = 'presence_'.$type;
		$self->callModules($module, $module_parameters);

}elseif(substr($contents, 0, 3) == '<iq'){ // <iq <- Achtung, nur type='get/set'
										   // wird geparst! Auf type='result'
										   // und type='error' wartet man ja selber mit sendXML!
	// Lade String nach SimpleXML
	$xml = simplexml_load_string($code);
	if(!$xml){
		trigger_error('Invalid XML: '.$code);
		return false;
	}
	$attr = $xml->attributes();

	$module_parameters['from'] = isset($attr->from) ? trim($attr->from) : false;
	$module_parameters['type'] = isset($attr->type) ? trim($attr->type) : false;
	$module_parameters['id'] = isset($attr->id) ? trim($attr->id) : false;
	$module_parameters['xmlstring'] = $code;
	$module_parameters['xml'] = $xml;

	if($attr->type == 'set')
		$self->callModules("iq_set", $module_parameters, $module_parameters['xmlstring']);
	else
		$self->callModules("iq_get", $module_parameters, $module_parameters['xmlstring']);

}elseif(substr($contents, 0, 7) == '<stream'){ // <stream
	if(substr($contents, 0, 14) == '<stream:stream'){ // <stream:stream
		// neuer Stream. Wir tun mal nix, das hier wird eh wahrscheinlich nie
		// auftreten, weil der Stream mit einem wartenden sendXML gestartet wird
	}elseif(substr($contents, 0, 13) == '<stream:error'){ // <stream:error

		// Lade String nach SimpleXML
		$xml = simplexml_load_string($code);
		if(!$xml){
			trigger_error('Invalid XML: '.$code);
			return false;
		}
		$attr = $xml->attributes();
		$text = isset($xml->text) ? $xml->text : 'no description';
		$childs = $xml->children;
		if(isset($childs['bad-format'])) $type = 'bad-format';
		elseif(isset($childs['bad-namespace-prefix'])) $type = 'bad-namespace-prefix';
		elseif(isset($childs['conflict'])) $type = 'conflict';
		elseif(isset($childs['connection-timeout'])) $type = 'connection-timeout';
		elseif(isset($childs['host-gone'])) $type = 'host-gone';
		elseif(isset($childs['host-unknown'])) $type = 'host-unknown';
		elseif(isset($childs['improper-addressing'])) $type = 'improper-addressing';
		elseif(isset($childs['internal-server-error'])) $type = 'internal-server-error';
		elseif(isset($childs['invalid-from'])) $type = 'invalid-from';
		elseif(isset($childs['invalid-id'])) $type = 'invalid-id';
		elseif(isset($childs['invalid-namespace'])) $type = 'invalid-namespace';
		elseif(isset($childs['invalid-xml'])) $type = 'invalid-xml';
		elseif(isset($childs['not-authorized'])) $type = 'not-authorized';
		elseif(isset($childs['policy-violation'])) $type = 'policy-violation';
		elseif(isset($childs['remote-connection-failed'])) $type = 'remote-connection-failed';
		elseif(isset($childs['resource-constraint'])) $type = 'resource-constraint';
		elseif(isset($childs['restricted-xml'])) $type = 'restricted-xml';
		elseif(isset($childs['see-other-host'])) $type = 'see-other-host';
		elseif(isset($childs['system-shutdown'])) $type = 'system-shutdown';
		elseif(isset($childs['undefined-condition'])) $type = 'undefined-condition';
		elseif(isset($childs['unsupported-encoding'])) $type = 'unsupported-encoding';
		elseif(isset($childs['unsupported-stanza-type'])) $type = 'unsupported-stanza-type';
		elseif(isset($childs['unsupported-version'])) $type = 'unsupported-version';
		elseif(isset($childs['xml-not-well-formed'])) $type = 'xml-not-well-formed';

		$self->debug('Stream-Error: '.$type.': '.$text);

	}else{
		$self->debug('Parser: Wir sind ein anderes STREAM-Miststück');
	}
}elseif(substr($contents, 0, 5) == '<?xml'){ // header
	// KEINE BEWEGUNG!
}

if(isset($after) && strlen($after) > 0){
	$self->incoming_buffer = $after;
	$self->parse_incoming_xml($after);
}

$self->incoming_buffer = '';
