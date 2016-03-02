<!DOCTYPE html>
<html lang="en">
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	</head>
	<body>
	
<?php
///////////////////////////////////////////////////////////////////////////
//
// iHomeFinder Custom Featured Agents System v0.23
// Copyright 2009 iHomefinder Inc. All Rights Reserved.
//
// filename: /agents.php
// description: Listing Index Display
// Developed under PHP Version 5.2.4
//
///////////////////////////////////////////////////////////////////////////

// include page header
include('header.php');

// include configuration and functions
include_once('config.php');
include_once('functions.php');
include_once('pagination.class.php');

// include styles
// See http://mis-algoritmos.com/2007/03/16/some-styles-for-your-pagination/ for more style options
echo "<style type=\"text/css\">";
include('fpws.css');
include("./styles/". $stylesheet_file);
echo "</style>";

// get agents
$agents = getAgents();

// if no agents found
if ($agents == 0) {
	// include template for no agents
	include('templates/no-listings.php');
} else {

	///////////////////////////////////////////
	// setup pagination
	
	$p = new pagination;
	$lc = count($agents);
	$p->adjacents(2);
	$p->items($lc);
	$p->limit($max_per_page);
	$p->urlFriendly();

	$p->target = "agents.php?per_page=".$max_per_page;

	if (isset($_GET['page'])) {
		$p->currentPage($_GET['page']);
		$range = getIndexRange($_GET['page'], $lc, $max_per_page);
	} else {
		$range = getIndexRange(1, $lc, $max_per_page);	
	}
		
	// show paginated links before results
	$p->show();
	
	// add a thing to change the # of results of page
	include('pagination_changer.php');	
		
	for ($i = $range['start']; $i <= $range['end']; $i++) {

		$l = $agents[$i];
		//$l->updateState();
		//$l->nophotourl = $default_photo_url;
	
		//$pid 	= $l->displayMLSListingNumber();
		//$bid	= $l->displayBoardID();
		//$ptype	= $l->prop_sub_t;
		
		//$plink 	= $base_idx_url ."idx/searchMap.cfm?cid=$uid&bid=$bid&pid=$pid";	
	
		include('./templates/agents-listing.php');
		
		
	} 

	// show paginated links after results
	$p->show();
}

// include page footer
include('footer.php');
?>

</body>
</html>
