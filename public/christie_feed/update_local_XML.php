<?php  


	include 'config.php';
	include 'data_functions.php';
	
	$errors = 0;
    $client = new SoapClient('http://secure.idxre.com/ihws/AgentWebService.cfc?wsdl');    
    try
    {
    	$xml_result = $client->getAllAgents($uid, $sid);
    }
    catch (Exception $e)
    {
    	$errors++;
    	echo "Error in SOAP client request: " . $e . "\n";	
    }
    
    try
    {
		$xmlobject = new SimpleXMLElement($xml_result);
    }
    catch (Exception $e)
    {
    	$errors++;
    	echo "Error in creating an XML object: " . $e . "\n";
    }	

	//sort xml results by firstname
		
	include 'SimpleDOM.php';

	try 
	{
		$page = simpledom_load_string($xml_result);

		$nodes = $page->sortedXPath('//agent', './firstname');
		$outstring = '<?xml version="1.0" encoding="UTF-8"?>
		<agentlist name="agents">' . "\n";
		foreach ($nodes as $node)
		{
			$outstring .= $node->asXML() . "\n";
		}
		 $outstring .= '</agentlist>';		
	}
	catch (Exception $e)
	{
		$errors++;
		echo "Error creating XML: " . $e . "\n";
	}


	try 
	{
		$newfile="/home/hklane3/hklane/public/christie_feed/agents.xml"; 	
		$file = fopen ($newfile, "w"); 
		fwrite($file, $outstring); 
		fclose ($file);
	}
	catch (Exception $e)
	{
		$errors++;
		echo "Error writing local agents.xml: " . $e . "\n";
	}


	try 
	{
		//properties data
		$proptype = "SFR,CND,LL,RI,FRM,MH,RNT";
		$xml_active = getDetailedListingsXML($uid, $sid, $proptype, 1, 0);

		$newfile="/home/hklane3/hklane/public/christie_feed/properties.xml";
		$file = fopen ($newfile, "w");
		fwrite($file, $xml_active);
		fclose($file);
	}
	catch (Exception $e)
	{
		$errors++;
		echo "Error writing or sorting properties.xml: " . $e . "\n";
	}

	try 
	{
		//properties data
		$proptype = "RNT";
		$xml_active = getDetailedRentalListingsXML($uid, $sid, $proptype, 1, 0);

		$newfile="/home/hklane3/hklane/public/christie_feed/rental_properties.xml";
		$file = fopen ($newfile, "w");
		fwrite($file, $xml_active);
		fclose($file);
	}
	catch (Exception $e)
	{
		$errors++;
		echo "Error writing or sorting rental_properties.xml: " . $e . "\n";
	}
	

	if ($errors)
	{
		echo "finished with ERRORS\n";
		echo "Please manually update via http://www.hklane.com/christie_feed/update_local_XML.php";
	}
	else {
		echo "finished with no visible exceptions or errors";
	}	
	
	
	?> 
