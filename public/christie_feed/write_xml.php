<?php
header("content-type: text/xml");
include_once ("config.php");

include_once ("data_functions.php");

$all_listings = getListings($uid, $sid, $proptype, $getactive, $getpendingsold, 0);

// $christie_property = file_get_contents('./templates/christie_property.xml');

$yesterday = date('m/d/Y', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
$expires = date('m/d/Y', mktime(0, 0, 0, date("m") , date("d") + 2, date("Y"))); // day after tomorrow
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<Properties>';

foreach($all_listings as $listing)
{
    $listing->cross_street = htmlentities($listing->cross_street);

    // HEREDOC:

    $property_xml = <<<EOT
<Property>
	<ListingID><![CDATA[$listing->id]]></ListingID>
	<IdAccount><![CDATA[$listing->mls_id]]></IdAccount>
	<IdListing><![CDATA[$listing->mls_id-$listing->id]]></IdListing>
	<IdListingForeignKey></IdListingForeignKey>
	<ListingStatus>Active</ListingStatus>
	<ListingName>
		<![CDATA[$listing->street_number $listing->street_name $listing->city_name $listing->zip]]>
	</ListingName>
	<URLDirectLink><![CDATA[$listing->URLDirectLink]]></URLDirectLink>
	<StreetAddress><![CDATA[$listing->street_number $listing->street_name]]></StreetAddress>
	<StreetAddressPublishNo>N</StreetAddressPublishNo>
	<AddrCrossStreet><![CDATA[$listing->cross_street]]></AddrCrossStreet>
	<AddrCounty><![CDATA[$listing->county]]></AddrCounty>
	<MapPublishNo>N</MapPublishNo>
	<ZoomLevel>13</ZoomLevel>
	<Latitude></Latitude>
	<Longitude></Longitude>
	<City><![CDATA[$listing->city_name]]></City>
	<Neighborhood></Neighborhood>
	<IdCommunity></IdCommunity>
	<Subdivision><![CDATA[$listing->subdivision]]></Subdivision>
	<SchoolDistrictId><![CDATA[$listing->school_district]]></SchoolDistrictId>
	<SchoolElementary><![CDATA[$listing->elementary_school]]></SchoolElementary>
	<SchoolHigh><![CDATA[$listing->highschool]]></SchoolHigh>
	<SchoolMiddle><![CDATA[$listing->jrhighschool]]></SchoolMiddle>
	<StateOrProvince><![CDATA[$listing->state]]></StateOrProvince>
	<ZipCode><![CDATA[$listing->zip]]></ZipCode>
	<Country>United States of America</Country>
	<StartDateTime><![CDATA[$yesterday]]></StartDateTime>
	<EndDateTime><![CDATA[$expires]]></EndDateTime>
	<DateNewSource>MM/DD/YYYY</DateNewSource>
	<SellerName><![CDATA[$listing->office_nam]]></SellerName>
	<ListingType><![CDATA[$listing->property_type]]></ListingType>
	<CodeListingType><![CDATA[$listing->CodeListingType]]></CodeListingType>
	<NewConstruction>N</NewConstruction>
	<Price></Price>
	<ListPrice><![CDATA[$listing->list_price]]></ListPrice>
	<CurrencyCode>USD</CurrencyCode>
	<ListPricePublishNo>N</ListPricePublishNo>
	<SqFootAveragePrice></SqFootAveragePrice>
	<RealEstateTaxAnnual>11409</RealEstateTaxAnnual>
	<RealEstateTaxYear>2011</RealEstateTaxYear>
	<Remarks>
		<![CDATA[$listing->remarks]]>
	</Remarks>
	<Bedrooms><![CDATA[$listing->bedrooms]]></Bedrooms>
	<Baths><![CDATA[$listing->full_baths]]></Baths>
	<BathsPartial><![CDATA[$listing->partial_baths]]></BathsPartial>
	<RoomsTotal>13</RoomsTotal>
	<Stories>2</Stories>
	<BuildingName></BuildingName>
	<BuildingFloorsTotal></BuildingFloorsTotal>
	<BuildingUnitsTotal></BuildingUnitsTotal>
	<UnitAptId><![CDATA[$listing->unit_number]]></UnitAptId>
	<Guest>2</Guest>
	<YearBuilt><![CDATA[$listing->year_built]]></YearBuilt>
	<LivingArea><![CDATA[$listing->square_feet]]></LivingArea>
	<LivingAreaUnits>Square Feet</LivingAreaUnits>
	<LotSize><![CDATA[$listing->lot_acres]]></LotSize>
	<LotSizeUnits>Acres</LotSizeUnits>
	<lifestyles></lifestyles>
	<propertytypes>
	    <propertytype><![CDATA[$listing->property_type]]></propertytype>
	</propertytypes>
	<Amenities><![CDATA[$listing->amenities]]></Amenities>
	<PropertyStyles></PropertyStyles>
	<ModelName></ModelName>
	<Keywords>Luxury</Keywords>
	<VirtualTour><![CDATA[$listing->video_tour_url]]></VirtualTour>
	<AdvertiserName>HK Lane Real Estate</AdvertiserName>
	<AdvertiserHomepageURL><![CDATA[http://www.hklane.com]]></AdvertiserHomepageURL>
	<AdvertiserLogo><![CDATA[http://www.hklane.com/img/hklane.jpg]]></AdvertiserLogo>
	<SourceName></SourceName>
	<SourceURL></SourceURL>
	<SourceLogo></SourceLogo>
	<CloseDate></CloseDate>
	<CloseBuyer></CloseBuyer>
	<ClosePrice></ClosePrice>
	<ClosePricePublishNo></ClosePricePublishNo>
	<Contacts>
EOT;
    foreach($listing->agents as $agent)
    {
        $property_xml.= <<< EOT
		<Contact>
			<AgentID><![CDATA[$agent->id]]></AgentID>
			<FullName><![CDATA[$agent->name]]></FullName>
			<Email><![CDATA[$agent->email]]></Email>
			<OfficePhone><![CDATA[$agent->officephone]]></OfficePhone>
			<CellPhone><![CDATA[$agent->cellphone]]></CellPhone>
			<Fax><![CDATA[$agent->fax]]></Fax>
			<StreetAddress><![CDATA[$agent->address]]></StreetAddress>
			<City><![CDATA[$agent->city]]></City>
			<StateOrProvince><![CDATA[$agent->state]]></StateOrProvince>
			<ZipCode><![CDATA[$agent->zip]]></ZipCode>
			<Country><![CDATA[$agent->country]]></Country>
			<AgentPhoto><![CDATA[$agent->photo]]></AgentPhoto>
			<AgentLogo><![CDATA[$agent->logo]]></AgentLogo>
			<AgentURL><![CDATA[$agent->url]]></AgentURL>
		</Contact>\n
EOT;
    }

    $property_xml.= <<< EOT
	</Contacts>
	<images>
EOT;
    foreach($listing->photos as $photo)
    {
        $property_xml.= <<< EOT
		<image>
			<ImageURL>
				<![CDATA[$photo]]>
			</ImageURL>
		</image>
EOT;
    }

    $property_xml.= <<< EOT
	</images>
</Property>
EOT;
    echo $property_xml;
}

echo '</Properties>';
?>