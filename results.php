<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
?>

<?php
 
if (!empty($_GET)){

   if(isset($_GET['searchField']) AND (trim($_GET['searchField']) != '')) {
       $query = htmlspecialchars(trim($_GET['searchField']));  
   } else {
       $query = ''; 

   }
	// API request variables
	$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Codedomi-6e63-454a-baa4-50126bb6f613'; 
	$globalid = 'EBAY-US';  
	$safequery = urlencode($query);  // Make the query URL-friendly

	// Construct the findItemsByKeywords HTTP GET call 
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=findItemsByKeywords";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&SECURITY-APPNAME=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&keywords=$safequery";
	$apicall .= "&paginationInput.entriesPerPage=10";




	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
 
	// Check to see if the request was successful, else print an error
	if ($resp->ack == "Success") {
	  $results = '';
	  // If the response was loaded, parse it and build links  
	  foreach($resp->searchResult->item as $item) {
	    $pic   = $item->galleryURL;
	    $link  = $item->viewItemURL;
	    $title = $item->title;
	    $itemId = $item->itemId;
	    $categoryName = $item->primaryCategory->categoryName;
	    $productId = $item->productId;
	    $location = $item->location;
	    $country = $item->country;
	    $listingType = $item->listingInfo->listingType;
	    $currentPrice = $item->sellingStatus->currentPrice;
	    $bidCount = $item->sellingStatus->bidCount;
	    $sellingState = $item->sellingStatus->sellingState;
	    $timeLeft = $item->sellingStatus->timeLeft;
	   
	   
	   
	    $results .= "<div class=\"itemId\" id=\"$itemId\">";	   
	    $results .= "<img class=\"pic\" src=\"$pic\">";	   
	    $results .= "<div class=\"title\">$title</div>";	   
	    $results .= "<div class=\"categoryName\">$categoryName</div>";
	    $results .= "<div class=\"location\">$location</div>";
	    $results .= "<div class=\"currentPrice\">\$ $currentPrice</div>";
	    $results .= "<div class=\"bidCount\">$bidCount</div>";
	    $results .= "<div class=\"link\"><a href=\"?itemID=$itemId&searchField=$safequery\">Product Recall Check</a></div>";
	    //$results .= "<div class=\"sellingState\">$sellingState $listingType</div>"; 	    
	    $results .= "</div>"; 
	    
	  }
	} else {
	  $results  = "Something went wrong. Please try your search again."; 
	}
   }
?>




<div data-role="page">
<div data-role="header">
<h1>SafeGoods: EBAY Item Recall Checks</h1>
</div>

<div class="ui-field-contain">
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
 
<label for="searchField">Search EBAY:</label>
<input type="search" name="searchField" id="searchField" value=""> 
 
</form> 
</div>
 
<?php 
if (!empty($results)){ 
print <<<END
 
    $results 
    
END;
}

include 'disclaimer.php';
?>

</div>


