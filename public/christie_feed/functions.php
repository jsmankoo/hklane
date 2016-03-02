<?php

function getListing($uid, $sid, $pid, $bid, $type) {
	// Get Featured Listings
	$client = new SoapClient('https://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	$property_xml = $client->getPropDetail($uid, $sid, $pid, $bid, $type);

	// Convert XML to object
	$xml = new SimpleXMLElement($property_xml);
	return $xml;
	//echo "<pre>". print_r($property_xml, 1) ."</pre>\n\n";
	// exit;

}

function getListings($uid, $sid, $proptype, $getactive, $getpendingsold) {
	
	// Get Featured Listings

	//$client = new SoapClient('https://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	//$xml = $client->getProps($uid, $sid, $proptype, $getactive, $getpendingsold);

	//$listings_object = new SimpleXMLElement($xml);

	if ($getactive == "1")
	{
		$listings_object = new SimpleXMLElement('./ihf_integration/properties.xml', NULL, TRUE);
	}
	else if ($getpendingsold == "1")
	{
		$listings_object = new SimpleXMLElement('./ihf_integration/sold_properties.xml', NULL, TRUE);	
	}
	if (isset($listings_object->fail)) {
		return 0;
	}

	$listing_array = buildListings($listings_object);

	return $listing_array;
	
}

function strpos_arr($haystack, $needle) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
        if(($pos = strpos($haystack, $what))!==false) return $pos;
    }
    return false;
}

function getAgents() {
	
	// Get Agent Listings
	/*
	$client = new SoapClient('https://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl');  
	$xml = $client->getProps($uid, $sid, $proptype, $getactive, $getpendingsold);

	$listings_object = new SimpleXMLElement($xml);
	*/
	$agents_object = new SimpleXMLElement('./agents.xml', NULL, TRUE);
	if (isset($agents_object->fail)) {
		return 0;
	}

	$agents_array = buildAgentListings($agents_object);

	return $agents_array;
	
}



function buildAgentListings($xmlobject) {
	$object_array = array();
	foreach ($xmlobject->agent as $p) {

		// create new listing object
		$object = new FeaturedListing;

		$object->agent_id		= 	$p['id'];
		$object->firstname		= 	$p->firstname;
		$object->lastname		= 	$p->lastname;
		$object->photourl		=	$p->photo;
		$object->body			=	$p->body;
		$object->email			=	$p->email;
		$object->hasownsite		=	$p->hasownsite;
		$object->url			=	$p->url;
		$object->officephone	=	$p->officephone;
		$object->officefax		=	$p->officefax;
		$object->homeoffphone	=	$p->homeoffphone;
		$object->homefax		=	$p->homefax;
		$object->cellphone		=	$p->cellphone;
		$object->address		=	$p->address;
		$object->city			=	$p->city;
		$object->state			=	$p->state;
		$object->zip			=	$p->zip;
		
		// add to array	
		$object_array[]				= $object;

	}
	
	// return array
	return $object_array;

}


function buildListings($xmlobject) {
	$object_array = array();
	foreach ($xmlobject->featured_prop as $p) {

		// create new listing object
		$object = new FeaturedListing;

		$object->mls_listing_num	= $p['listingnumber'];
		$object->mls_id				= $p['mlsid'];
		$object->list_price 		= $p->list_price;
		$object->house_numb			= $p->house_numb;
		$object->street_nam			= $p->street_nam;
		$object->addr_fpn			= $p->addr_fpn;
		$object->city_name			= $p->city_name;
		$object->state				= $p->state;
		$object->zip				= $p->zip;
		$object->prop_sub_t			= $p->prop_sub_t;
		$object->totl_bed			= $p->totl_bed;
		$object->totl_fbath			= $p->totl_fbath;
		$object->totl_hbath			= $p->totl_hbath;
		$object->totl_fl_sq			= $p->totl_fl_sq;
		$object->agt_code			= $p->agt_code;
		$object->unit_desig			= $p->unit_desig;
		$object->office_nam 		= $p->office_nam;
		$object->office_cod 		= $p->office_cod;
		$object->agt_frstnm 		= $p->agt_frstnm;
		$object->agt_lastnm 		= $p->agt_lastnm;
		$object->office_phn 		= $p->office_phn;
		$object->remarks 			= $p->remarks;
		$object->remarks2	 		= $p->remarks2;
		$object->remarks3 			= $p->remarks3;
		$object->year_built 		= $p->year_built;
		$object->address_filtered	= $p->address_filtered;
		$object->propdescyn 		= $p->propdescyn;
		$object->propdescription	= $p->propdescription;
		$object->photoyn 			= $p->photoyn;
		$object->photourl	 		= $p->photourl;
		$object->touryn		 		= $p->touryn; 
		$object->tourlink	 		= $p->tourlink;
		$object->virtualtourlink	= trim($p->virtualtourlink);
		$object->toururl	 		= trim($p->toururl);
		$object->phototouryn 		= $p->phototouryn;
		$object->phototourlink 		= trim($p->phototourlink);
		$object->reportyn	 		= $p->reportyn;
		$object->reporturl	 		= trim($p->reporturl);
		$object->reportlink 		= trim($p->reportlink);
		$object->viewcode	 		= $p->viewcode;
		$object->openyn 			= $p->openyn;
		$object->opendate 			= $p->opendate;
		$object->opentime 			= $p->opentime;
		$object->opendatetext 		= $p->opendatetext;
		$object->openagent	 		= $p->openagent;
		$object->flashtouryn 		= $p->flashtouryn;
		$object->flashtoururl 		= trim($p->flashtoururl);
		$object->flashtourlink 		= trim($p->flashtourlink);
		$object->mapyn 				= $p->mapyn;
		$object->extralinkyn 		= $p->extralinkyn;
		$object->extralinkurl 		= $p->extralinkurl;
		$object->extralinktext 		= $p->extralinktext;
		$object->addressyn	 		= $p->addressyn;
		$object->addresshousenum	= $p->addresshousenum;
		$object->addressstreetnam	= $p->addressstreetnam;
		$object->vprice		 		= $p->vprice;
		$object->sqft2		 		= $p->sqft2;
		$object->dateimported 		= $p->dateimported;
		$object->sharecustom_yn		= $p->sharecustom_yn;
		$object->county		 		= $p->county;
		$object->mlsphotourl 		= $p->mlsphotourl;
		$object->mlsthumbnailurl	= $p->mlsthumbnailurl;
		$object->mlsphotocount 		= $p->mlsphotocount;
		$object->cityid 			= $p->cityid;
		$object->countyid	 		= $p->countyid;
		$object->ihfphotourl 		= $p->ihfphotourl;
		
		// add to array	
		$object_array[]				= $object;

	}
	
	// return array
	return $object_array;

}

class AgentListing
	{
		public $agent_id		= '';
		public $firstname		= '';
		public $lastname		= '';
		public $photourl		= '';
		public $body			= '';
		public $email			= '';
		public $hasownsite		= '';
		public $url				= '';
		public $officephone		= '';
		public $officefax		= '';
		public $homeoffphone	= '';
		public $homefax			= '';
		public $cellphone		= '';
		public $address			= '';
		public $city			= '';
		public $state			= '';
		public $zip				= '';	
		
		public function getBody(){
			$safeText = htmlspecialchars_decode($this->body);
			return $safeText;
		
		}
		
	

	}
	
	
	
	
function convertPropToHuman($s) {
	switch ($s) {
		case 'SFR':
		    return 'House';
		    break;
		case 'CND':
		    return 'Condo';
		    break;
		case 'FRM':
		    return 'Farm';
		    break;
		case 'RI':
		    return 'Residential Income';
		    break;
		case 'MH':
		    return 'Mobile Home';
		    break;
		case 'LL':
		    return 'Lots and Land';
		    break;
		case 'RNT':
		    return 'Rental';
		    break;
		case 'COM':
		    return 'Commercial';
		    break;
		default:
		    return 'Unknown';
	}
}
	



class FeaturedListing
	{
    // property declaration
    public $mls_listing_num		= '';
    public $mls_id				= '';
    public $list_price 			= '';
    public $house_numb 			= '';
    public $street_nam 			= '';
    public $addr_fpn 			= '';
    public $city_name 			= '';
    public $state 				= '';
    public $zip 				= '';
    public $prop_sub_t 			= '';
    public $totl_bed 			= '';
    public $totl_fbath 			= '';
    public $totl_hbath 			= '';
    public $totl_fl_sq 			= '';
    public $agt_code 			= '';
    public $unit_desig 			= '';
    public $office_nam 			= '';
    public $office_cod 			= '';
    public $agt_frstnm 			= '';
    public $agt_lastnm 			= '';
    public $office_phn 			= '';
    public $remarks 			= '';
    public $remarks2			= '';
    public $remarks3			= '';
    public $year_built 			= '';
    public $address_filtered 	= '';
    public $propdescyn 			= '';
    public $propdescription		= '';
    public $photoyn 			= '';
    public $photourl 			= '';
    public $touryn 				= '';    
    public $tourlink 			= '';
    public $virtualtourlink 	= '';
    public $toururl 			= '';
    public $phototouryn 		= '';
    public $phototourlink 		= '';
    public $reportyn 			= '';
    public $reporturl			= '';
    public $reportlink 			= '';
    public $viewcode 			= '';
    public $openyn 				= '';
    public $opendate 			= '';
    public $opentime 			= '';
    public $opendatetext 		= '';
    public $openagent 			= '';
    public $flashtouryn 		= '';
    public $flashtoururl 		= '';
    public $flashtourlink 		= '';
    public $mapyn 				= '';
    public $extralinkyn 		= '';
    public $extralinkurl 		= '';
    public $extralinktext 		= '';
    public $addressyn 			= '';
    public $addresshousenum 	= '';
    public $addressstreetnam 	= '';
    public $vprice 				= '';
    public $sqft2 				= '';
    public $dateimported 		= '';
    public $sharecustom_yn 		= '';
    public $county 				= '';
    public $mlsphotourl 		= '';
    public $mlsthumbnailurl 	= '';
    public $mlsphotocount 		= '';
    public $cityid 				= '';
    public $countyid 			= '';
    public $ihfphotourl 		= '';
    public $nophotourl			= '';
	public $links				= 0;

	public function updateState() {
		if ($this->extralinkyn == 1) { $this->links = 1; }
		if ($this->flashtouryn == 1) { $this->links = 1; }
		if ($this->reportyn == 1) { $this->links = 1; }
		if ($this->touryn > 0) { $this->links = 1; }
		if (!empty($this->virtualtourlink)) { $this->links = 1; }
		if ($this->phototouryn == 1) { $this->links = 1; }
	}

	// converts property type code to display string
	public function displayPropertyType() {
		switch ($this->prop_sub_t) {
			case 'SFR':
			    return 'House';
			    break;
			case 'CND':
			    return 'Condo';
			    break;
			case 'FRM':
			    return 'Farm';
			    break;
			case 'RI':
			    return 'Residential Income';
			    break;
			case 'MH':
			    return 'Mobile Home';
			    break;
			case 'LL':
			    return 'Lots and Land';
			    break;
			case 'RNT':
			    return 'Rental';
			    break;
			case 'COM':
			    return 'Commercial';
			    break;
			default:
			    return 'Unknown';
		}
	}

	public function displayPrice() {
		return '<span class="list-price">$ '. number_format((double)$this->list_price) .'</span>';
	}


	public function displayMLSListingNumber() {
    	return $this->mls_listing_num;
    }

	public function displayBoardID() {
    	return $this->mls_id;
    }
    
    public function getStreetAddress($no_apt = FALSE) {
    	$address_string = '';
    	if (!empty($this->house_numb)) {
    		$address_string .= $this->house_numb . ' ';
    	}
    	if (!empty($this->street_nam)) {
    		$address_string .= $this->street_nam . ' ';
    	}
    	
		return trim($address_string);
    }

    public function getCity() {
    	$city_string = '';
    	if (!empty($this->city_name)) {
    		$city_string .= trim($this->city_name);
    	}
    	return $city_string;
    }
    public function getCityStateZip() {
    	$citystatezip_string = '';
    	if (!empty($this->city_name)) {
    		$citystatezip_string .= trim($this->city_name) . ' ';
    	}
    	if (!empty($this->state)) {
    		$citystatezip_string .= trim($this->state) .' ';
    	}
    	if (!empty($this->zip)) {
    		$citystatezip_string .= trim($this->zip);
    	}
		return $citystatezip_string;
    }
    
    public function displayAddress() {
    	return '<span class="street-address">'. $this->getStreetAddress() .'</span>';
    	// $this->addr_fpn
    }
    
    public function displayCityStateZip() {
	    return '<span class="city-state-zip">'. $this->getCityStateZip() .'</span>';
    }

	public function displayFlashTourLink() {
		if ($this->flashtouryn == 0) {
			return '';
		} else {
			return "<li><a href=\"". $this->flashtoururl ."\">". $this->flashtourlink ."</a></li>";
		}
	}

	public function displayExtraLink() {
		if ($this->extralinkyn != 0) {
			if ($this->extralinktext == '') {
				$extralinktext = $this->extralinktext;
			} else {
				$extralinktext = 'Extra Link';
			}
			return "<li><a href=\"". $this->extralinkurl ."\">". $extralinktext ."</a><li>";
		} else {
			return '';
		}
	}

	public function displayReport() {
		if ($this->reportyn != 0) {
			if ($this->reportlink == '') {
				$reportlink = $this->reportlink;
			} else {
				$reportlink = "Property Report";
			}
			return "<li><a href=\"". $this->reporturl ."\">". $reportlink ."</a></li>";
		} else {
			return '';
		}
	}

	public function displayVirtualTourLink() {
		// if touryn is 'yes', use link provided by client
		if ($this->touryn != 0) {
			return "<li><a href=\"#\" onClick=\"window.open('". $this->toururl ."', 'phototour', 'menubar=1,resizable=1,width=750,height=440')\">". $this->tourlink ."</a></li>";
		} else {
			// if link provided by MLS
			if (!empty($this->virtualtourlink)) {
				return "<li><a href=\"#\" onClick=\"window.open('". $this->virtualtourlink ."', 'phototour', 'menubar=1,resizable=1,width=750,height=440')\">Virtual Tour</a></li>";
			// else nothing to return
			} else {
				return '';
			}
			
//			window.open ("http://www.javascript-coder.com","mywindow","menubar=1,resizable=1,width=350,height=250"); 
			
			// javascript:VTour=window.open('http://www.visualtour.com/shownp.asp?sk=13&t=1645231',
			// 'VTour','scrollbars=yes,status=no,menubar=yes,resizable=yes,height=440,width=750,screenx=10,left=10,screeny=10,top=10')
			// ;%20VTour.focus();
		}
	}

	public function displayPhotoTourLink() {
		if ($this->phototouryn != 0 && $this->phototourlink != '') {
			return "<li><a href=\"". $this->phototourlink ."\">Photo Tour</a></li>";
		} else {
			return '';
		}
	}

	public function getPhotoURL() {

		if ($this->ihfphotourl != '') {
			return $this->ihfphotourl;
		} elseif ($this->photourl != '') {
			return $this->photourl;
		} elseif ($this->mlsphotourl != '') {
			return $this->mlsphotourl;
		} else {
			return $this->nophotourl;
		}
	}


	public function displayOpenHouse() {
		if ($this->openyn != 0) {
			$openhouse = "<span class=\"openhouse\">Open House on ". $this->opendatetext .", ". $this->opentime;
			if ($this->openagent != '') {
				$openhouse .= "</span> - <span class=\"openhouse-agent\">Contact: ". $this->openagent;	
			}
			$openhouse .= "</span>";
			return $openhouse;
		} else {
			return '';
		}
	}
	

	public function getLastOpenHouseTimestamp() {
		if ($this->openyn != 0) {
			$datetime_array				= explode(' ', $this->opendate);
			$date_array 				= explode('-', $datetime_array[0]);

			$time 						= $this->opentime;

			$timestamp					= mktime ( 0,0,0, $date_array[1], $date_array[2], $date_array[0]);
			
			// date('F j, Y', $timestamp);
			return $timestamp;
		}
	}

	public function displayTotalBedrooms() {
		return '<span class="total-bedrooms">'. $this->totl_bed .'</span>';
	}

	public function displayTotalFullBaths() {
		return '<span class="total-bathrooms">'. $this->totl_fbath .'</span>';
	}

	public function displayTotalHalfBaths() {
		return '<span class="total-half-bathrooms">'. $this->totl_hbath .'</span>';
	}

	public function displayRemarks() {
		return '<span class="property-description">'. $this->propdescription .'</span>';
	    // $this->propdescyn
    	// $this->remarks
    	// $this->remarks2
    	// $this->remarks3
	}

	public function displayCounty() {
		if ($this->county != '') {
			return '<li>County: <span class="county">'. $this->county .'</span></li>';
		} else {
			return '';
		}
	}

	public function displayTotalSquareFootage() {
		if ($this->totl_fl_sq != 0 && $this->totl_fl_sq != '') {
			return '<li>Square Feet: <span class="square-feet">'. $this->totl_fl_sq .'</span>';
		} else {
			return '';
		}
	}
	
	public function displayYearBuilt() {
		if ($this->year_built != '') {
			return '<li>Year Built: <span class="year-built">'. $this->year_built .'</span></li>';
		} else {
			return '';
		}
	}

	public function getAgentName() {
		$agent_name = '';
		if ($this->agt_frstnm != '') {
			$agent_name = $this->agt_frstnm;
		} elseif ($this->agt_lastnm != '') {
			$agent_name = $this->agt_lastnm;
		}
		if ($agent_name != '') {
			return $agent_name;
		} else {
			return '';
		}
	}
	public function getAgentOffice() {
		if ($this->office_nam != '') {
			return $this->office_nam;
		} else {
			return '';
		}
	}
	public function displayAgentNameAndOffice() {
		$name = $this->getAgentName();
		$office = $this->getAgentOffice();
		$str = '';
		if (($name != '') && ($office != ''))
		{
			$str .= $name . ", " . $office;
		}
		else if ($name != ''){
			$str .= $name;
		}
		else if ($office != ''){
			$str .= $office;
		}
		return $str;
	}
	public function displayAgentName() {
		$agent_name = '';
		if ($this->agt_frstnm != '') {
			$agent_name = $this->agt_frstnm;
		} elseif ($this->agt_lastnm != '') {
			$agent_name = $this->agt_lastnm;
		}
		if ($agent_name != '') {
			return "<li>Agent: <span class=\"agent-name\">". $agent_name .'</span></li>';
		} else {
			return '';
		}
	}

	public function displayOfficeName() {
		if ($this->office_nam != '') {
			return "<li>Office: <span class=\"office-name\">". $this->office_nam .'</span></li>';
		} else {
			return '';
		}
	}

	public function displayOfficePhone() {
		if ($this->office_phn != '') {
			return "<li>Office Phone: <span class=\"office-phone\">". $this->office_phn .'</span></li>';
		} else {
			return '';
		}
	}

	public function displayGoogleMapLink($zoom_level = 15) {
		$address = trim($this->getStreetAddress(TRUE));
		$address .= ', ' . $this->getCityStateZip();
		
		if ($address != '') {
			$address = str_replace (' ', '+', $address);
			return "<a href=\"http://maps.google.com/maps?q=". $address ."&z=$zoom_level&num=1&t=m\" target=\"_blank\">Map Link</a>";
		} else {
			return '';
		}
	}

/*    
	These values are typically blank, and thus not used by this class    

    $this->agt_code
    $this->office_cod
    $this->address_filtered
    $this->photoyn
    $this->viewcode
    $this->mapyn
    $this->addressyn
    $this->addresshousenum
    $this->addressstreetnam
    $this->sqft2
    $this->dateimported
    $this->sharecustom_yn
    $this->county
    $this->mlsthumbnailurl
    $this->mlsphotocount
    $this->cityid
    $this->countyid
*/

}

?>