<?php
$bottr      = &$argumente[0];
$parameters = $argumente[1];

$xml = $parameters['xml'];

if(isset($xml->query)){
	if(strpos($parameters['xmlstring'], 'jabber:iq:version') !== false){
				$bottr->sendXML("<iq from='".$bottr->jid."' to='".$parameters['from']."' type='result' id='".$parameters['id']."'><query xmlns='jabber:iq:version'><name>bottr</name><version>".file_get_contents('version.dat')."</version></query></iq>", false);
	}elseif(strpos($parameters['xmlstring'], 'jabber:iq:last') !== false){
				$bottr->sendXML("<iq from='".$bottr->jid."' to='".$parameters['from']."' type='result' id='".$parameters['id']."'><query xmlns='jabber:iq:last' seconds='0'/></iq>", false);
	}
}elseif($xml->time){
	#if(strpos($parameters['xmlstring'], 'urn:xmpp:time') !== false){
		$bottr->sendXML("<iq from='".$bottr->jid."' to='".$parameters['from']."' type='result' id='".$parameters['id']."'><time xmlns='urn:xmpp:time'><tzo>+01:00</tzo><utc>".gmdate('Y-m-d\TH:i:s\Z')."</utc></time></iq>", false);
	#}
}
