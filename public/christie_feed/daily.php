<?php  

    $uid = "51244";      
    $sid = "psprings244";
    
    $opts = array(
        'http'=>array(
            'user_agent' => 'PHPSoapClient'
            )
        );

    $context = stream_context_create($opts);

    $client = new SoapClient('http://secure.idxre.com/ihws/AgentWebService.cfc?wsdl');    
    $xml_result = $client->getAllAgents($uid, $sid);  
	$xmlobject = new SimpleXMLElement($xml_result);

	//sort xml results by firstname
	include 'SimpleDOM.php';

	$page = simpledom_load_string($xml_result);

	$nodes = $page->sortedXPath('//agent', './firstname');
	$outstring = '<?xml version="1.0" encoding="UTF-8"?>
	<agentlist name="agents">' . "\n";
	foreach ($nodes as $node)
	{
		$outstring .= $node->asXML() . "\n";
	}
	 $outstring .= '</agentlist>';

	$newfile="/home/hklane3/hklane/public/agents.xml"; 	
	$file = fopen ($newfile, "w"); 
	fwrite($file, $outstring); 
	fclose ($file);
	
	//properties data
	$proptype = "SFR,CND,LL,RI,FRM,MH,RNT";

	$client2 = new SoapClient('http://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	$xml_result2 = $client2->getProps($uid, $sid, $proptype, 1, 1);
	$newfile="/home/hklane3/hklane/public/properties.xml";
	$file = fopen ($newfile, "w");
	fwrite($file, $xml_result2);
	fclose($file);
	
	echo "finished";

	?> 
