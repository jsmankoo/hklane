<?php
///////////////////////////////////////////////////////////////////////////
//
// iHomeFinder Custom Featured Listings System v0.23
// Copyright 2009 iHomefinder Inc. All Rights Reserved.
//
// filename: config.php
// description: Configuration File
//
///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////
// account settings
////////////////////////////////

// user ID for account
$uid = "51244";

// password for account
$sid = "psprings244";

$bid = "19"; // a guess, corresponds to MLS ID?

// idxre.com subdomain to use for all links
$base_idx_url = "http://hklane.idxre.com/";

///////////////////////////////////////////////////////////////////////////
// display settings
////////////////////////////////

// Property Types to Include in Featured Listings Display
// SFR 	= Single Family Residential (House)
// CND 	= Condo
// LL 	= Lots and Land
// RI 	= Multi-Unit Residential
// FRM 	= Farm
// MH 	= Mobile Home
// RNT 	= Rental
$proptype 			= "SFR,CND,LL,COM,RI,FRM,MH,RNT";

// Include Active Listings
$getactive 			= "1";				// Boolean Value, Set to 1 for Yes, 0 for No

// Include Pending/Sold Listings
$getpendingsold 	= "0";				// Boolean Value, Set to 1 for Yes, 0 for No

// URL for 'No Image Available' image
// $default_photo_url	= "http://supportdemo.ihomefinder.com/webservices/featured-ws102/no-photo.jpg";
$default_photo_url	= "http://www.mlsb.com/images/NoPhoto.gif";

?>