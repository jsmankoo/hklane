<?php

function getDetailedListingsXML($uid, $sid, $proptype, $getactive, $getpendingsold)
{
	$client = new SoapClient('http://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	$xml = $client->getProps($uid, $sid, $proptype, $getactive, $getpendingsold);
	$listings_object = new SimpleXMLElement($xml);
	
	$detailed_listings_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$detailed_listings_xml .= '<featured_props>';
	foreach ($listings_object->featured_prop as $listing)
	{
		$pid = $listing['listingnumber'];
		$bid = $listing['mlsid'];
		if ($listing->list_price >= 1000000 )
		{
			$new_listing = $client->getPropDetail($uid, $sid, $pid, $bid, $proptype);
			$new_listing = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $new_listing);
			$detailed_listings_xml .= $new_listing;
		}
	}
	$detailed_listings_xml .= '</featured_props>';
	return $detailed_listings_xml;
}

function getDetailedRentalListingsXML($uid, $sid, $proptype, $getactive, $getpendingsold)
{
	$client = new SoapClient('http://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	$xml = $client->getProps($uid, $sid, $proptype, $getactive, $getpendingsold);
	$listings_object = new SimpleXMLElement($xml);
	
	$detailed_listings_xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$detailed_listings_xml .= '<featured_props>';
	foreach ($listings_object->featured_prop as $listing)
	{
		$pid = $listing['listingnumber'];
		$bid = $listing['mlsid'];
		if ($listing->list_price >= 7000 )
		{
			$new_listing = $client->getPropDetail($uid, $sid, $pid, $bid, $proptype);
			$new_listing = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $new_listing);
			$detailed_listings_xml .= $new_listing;
		}
	}
	$detailed_listings_xml .= '</featured_props>';
	return $detailed_listings_xml;
}

function getListings($uid, $sid, $proptype, $getactive, $getpendingsold, $stale = 0) {
	
	// Get Featured Listings

	if ($stale) // pull from file
	{
		if ($getactive == "1")
		{
			$listings_object = new SimpleXMLElement('./properties.xml', NULL, TRUE);
		}
		else if ($getpendingsold == "1")
		{
			$listings_object = new SimpleXMLElement('./sold_properties.xml', NULL, TRUE);	
		}
	}
	else { // pull from web service
		$detailed_listings_xml = getDetailedListingsXML($uid, $sid, $proptype, $getactive, $getpendingsold);
		$listings_object = new SimpleXMLElement($detailed_listings_xml);
	}
	
	$listing_array = buildListings($listings_object);

	return $listing_array;
	
}

function getRentalListings($uid, $sid, $proptype, $getactive, $getpendingsold, $stale = 0) {
	
	// Get Featured Listings

	if ($stale) // pull from file
	{
		if ($getactive == "1")
		{
			$listings_object = new SimpleXMLElement('./properties.xml', NULL, TRUE);
		}
		else if ($getpendingsold == "1")
		{
			$listings_object = new SimpleXMLElement('./sold_properties.xml', NULL, TRUE);	
		}
	}
	else { // pull from web service
		$detailed_listings_xml = getDetailedRentalListingsXML($uid, $sid, $proptype, $getactive, $getpendingsold);
		$listings_object = new SimpleXMLElement($detailed_listings_xml);
	}
	
	$listing_array = buildListings($listings_object);

	return $listing_array;
	
}

function buildListings($xmlobject) {
	$object_array = array();
	foreach ($xmlobject->featured_prop as $p) {

		// create new listing object
		$object = new FeaturedListing($p);		
		// add to array	
		$object_array[]	= $object;

	}
	
	// return array
	return $object_array;

}

function format_christie_phone($in)
{
	// assumed: North American phoen number
	// output phone style: +1 555 555 5555
	$in = preg_replace("/[^0-9]/", " ", $in);
	$in = trim($in);
	if (strlen($in)==12) // '666 666 6666' == 12
	{
		$in = '+1 ' . $in;
	}
	return $in;
}

function format_christie_proptype($in)
{
	switch ($in) {
		case 'SFR':
		    return 'Single-Family Residence';
		    break;
		case 'CND':
		    return 'Condo/Co-op';
		    break;
		case 'FRM':
		    return 'Ranch';
		    break;
		case 'RI':
		    return 'Other';
		    break;
		case 'MH':
		    return 'Other';
		    break;
		case 'LL':
		    return 'Land Only';
		    break;
		case 'RNT':
		    return 'Rental';
		    break;
		case 'COM':
		    return 'Other';
		    break;
		default:
		    return 'Unknown';
	}
}

class Agent {
	public $id = '';
	public $name = '';
	public $email = '';
	public $officephone = '';
	public $cellphone = '';
	public $fax = '';
	public $streetaddress = '';
	public $city = '';
	public $state = '';
	public $zip = '';
	public $country = '';
	public $photo = '';
	public $logo = '';
	public $url = '';

	public $valid = false;

	function __construct($id)
	{
		$agents = new SimpleXMLElement('./agents.xml', NULL, TRUE);
		foreach ($agents as $agent)
		{
			if ((strcmp($agent->mlsid1, $id) == 0) or (strcmp($agent->agentcompanycode, $id) == 0))
			// if (intval($agent->agentcompanycode) == intval($id))
			{
				$this->id = $agent["id"];
				$this->name = trim($agent->firstname) . ' ' . trim($agent->lastname);
				$this->email = htmlspecialchars(trim($agent->email));
				$this->officephone = format_christie_phone($agent->officephone);
				$this->cellphone = format_christie_phone($agent->cellphone);
				$this->fax = format_christie_phone($agent->officefax);
				$this->streetaddress = $agent->address;
				$this->city = $city;
				$this->state = $state;
				$this->zip = $zip;
				$this->country = "United States of America";
				$this->photo = htmlspecialchars(trim($agent->photo));
				$this->logo = htmlspecialchars($logo);
				$this->url = htmlspecialchars(trim($agent->url));
				$this->valid = true;
			}
		}
	}
}

class FeaturedListing {

	// property declaration
    public $id				= '';
    public $mls_id			= '';
    public $list_price		= '';
    public $unit_number 	= '';
    public $street_number	= '';
    public $street_name		= '';
    public $city_name		= '';
    public $county			= '';
    public $state 			= '';
    public $zip				= '';
    public $cross_street	= '';
    public $subdivision     = '';
    public $property_type 	= '';
    public $year_built		= 'Unknown';
    public $lot_acres		= '';
    public $school_district = '';
    public $elementary_school = '';
    public $highschool = '';
    public $jrhighschool = '';

    public $full_baths = '';
    public $partial_baths = '';
    public $bedrooms;

    public $remarks			= '';
    public $square_feet		= '';

    public $agent_name 		= '';
    public $agent_code		= '';

    public $agents = null;
    public $photos = null;

    public $amenities = '';

    public $video_tour_url = '';
    public $URLDirectLink = '';

	function __construct($x)
	{
		$this->id					= $x['listingnumber'];
    	$this->mls_id 				= $x['mlsid'];
		$this->list_price 			= intval($x->ListPrice);
		$this->unit_number			= $x->UnitNumber;
		$this->street_number 		= $x->StreetNumber;
		$this->street_name			= $x->StreetName;
		$this->city_name 			= trim($x->City);
		$this->state 				= trim($x->State);
		$this->subdivision			= $x->Subdivision;
		$this->county 				= $x->County;
		$this->cross_street			= $x->StreetName . ' and ' . $x->CrossStreet;
		$this->property_type 		= format_christie_proptype($x->PropertyType);
		$this->CodeListingType		= ($x->PropertyType=='RNT') ? 'R' : 'S';
		$this->lot_acres			= number_format((double)$x->LotAcres, 2, '.', '');
		$this->square_feet			= $x->SquareFeet;
		$this->school_district		= $x->SchoolDistrict;
		$this->elementary_school 	= $x->ElementarySchool;
		$this->highschool 			= $x->HighSchool;
		$this->jrhighschool 		= $x->JrHighSchool;
		$this->zip 					= trim($x->Zip);

		$title_string = strtoupper($this->street_number) . ' ' . strtoupper($this->street_name) . ' ' . strtoupper($this->city_name) . ' ' . strtoupper($this->zip);
		$this->URLDirectLink		= 'http://hklane.idxre.com/homes/' . $this->mls_id . '/51244/' . $title_string . '/' . $this->id;
		$this->URLDirectLink		= str_replace(' ','-',$this->URLDirectLink);
		$this->URLDirectLink		= htmlspecialchars($this->URLDirectLink);

		$this->remarks				= $x->Remarks;
		$this->year_built 			= $x->YearBuilt;

		$this->agent_name			= $x->AgentName;
		$this->agent_code			= $x->AgentCode;

		$this->full_baths			= $x->FullBaths;
		$this->partial_baths		= $x->HalfBaths;
		$this->bedrooms				= $x->Bedrooms;

		$this->video_tour_url 		= htmlspecialchars($x->VirtualTourLink);

		$this->agents = array();

		$main_agent = new Agent($x->AgentCode);
		if ($main_agent->valid) { $this->agents[] = $main_agent; }

		$number_of_photos = $x->MLSPhotoCount;
		$this->photos = array();
		$base = "photo";
		for ($i = 1; $i <= $number_of_photos; $i++)
		{
			$index = str_pad($i, 2, "0", STR_PAD_LEFT); // 01, 02, ... 10, 11, and so forth.
			$element = $base . $index; // the name of the element... ihf doesn't believe in nesting
			array_push($this->photos, htmlspecialchars($x->$element));
		}

		// special amenities using Christie's code
		switch ($x->GarageSpaces)
		{
			case 3:
				$this->amenities .= '1014,';
				break;
			case 4:
				$this->amenities .= '1015,';
				break;
			case 5:
				$this->amenities .= '1016,';
				break;
		}

		$remarks = strtolower($x->Remarks);
		$community = strtolower($x->Community);

		if (strstr($community, 'gated community')!=false) // Gated Community
		{
			$this->amenities .= '339,';
		}
		if (strstr($remarks, 'granite counter')!= false) // Granite Counters
		{
			$this->amenities .= '340,';
		}
		if (strstr(strtolower($x->Rooms), 'guest house')!=false) // Guest House
		{
			$this->amenities .= '343,';
		}
		if (intval($x->HorsePropertyYN)) { // Horse Facilities
			$this->amenities .= '350,';
		}
		if (strstr($remarks, 'library')!=false) // Library
		{
			$this->amenities .= '363,';
		}
		if ($x->SecuritySystem == "Yes") // Security System
		{
			$this->amenities .= '396,';
		}
		if ($x->Spa != '') // Spas
		{
			$this->amenities .= '400,';
		}
		if (strstr($remarks, 'tennis')!= false) // tennis courts
		{
			$this->amenities .= '409,';
		}
		// if (intval($x->PoolYN)) // indoor pool
		// {
		// 	$this->amenities .= '1032,';	
		// }
		if (strstr($remarks, 'media room')!=false) // media room
		{
			$this->amenities .= '1037,';
		}
		if (strstr($remarks, 'outdoor kitchen')!=false) // outdoor kitchen
		{
			$this->amenities .= '1038,';
		}
		if (strstr($remarks, 'projector screen')!=false) // screen room
		{
			$this->amenities .= '1045,';
		}
		if (strstr($remarks, 'terrace')!=false) // terraces
		{
			$this->amenities .= '1050,';
		}
		if (strstr(strtolower($x->Rooms), 'wine cellar')!=false) // wine cellar
		{
			$this->amenities .= '1052,';
		}
		if (strstr($community, "guard")!= false) // guard gated
		{
			$this->amenities .= '1057,';
		}
		if (strstr($community, "golf")!= false) // golf community
		{
			$this->amenities .= '1063,';
		}

		if (intval($x->NewConstructionYN)) // new construction
		{
			$this->amenities .= '636,';	
		}
		// views:
		$view_desc = strtolower($x->MiscString2);
		if (strstr($view_desc, 'lake')!=false) // lake
		{
			$this->amenities .= '581,';
		}
		if (strstr($view_desc, 'water')!=false) // water view
		{
			$this->amenities .= '626,';
		}
		if (strstr($view_desc, 'mountain')!=false) // mountain view
		{
			$this->amenities .= '589,';
		}

		// remove trailing comma:
		if ($this->amenities != '')
		{
			$this->amenities = substr($this->amenities, 0, strlen($this->amenities)-1);
		}
	}

}

?>