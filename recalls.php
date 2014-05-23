<?php

//let's get recall info by keyword
//e.g. https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord?message1=dewalt&password=password&userID=userId
//note the endpoint will display a webpage "under maintenance" if you make a mistake in the GET message.

    if (isset($_GET["word"])){
    $query = $_GET["word"];

	// API request variables
	$endpoint = 'https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord';  // URL to call 
	$safequery = urlencode($query);  // Make the query URL-friendly

	// Construct the HTTP GET call 
	$apicall = "$endpoint?";
	$apicall .= "message1=$safequery"; 
	$apicall .= "&password=safeGoods"; 
	$apicall .= "&userID=safeGoods"; 


	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
    }else{
    $resp = "No word provided";
    }
?>


<pre>
<?php
	print_r($resp);
?>	
</pre>
