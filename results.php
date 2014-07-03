<?php
error_reporting(0); 
//error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
?>

<?php
  

   if(isset($_GET['searchField'])) {
       $query = htmlspecialchars(trim($_GET['searchField']));  
   } else {
       $query = ''; 

   }
   
   	if (isset($_GET["pageNumber"])) {
	   $pageNumber = $_GET["pageNumber"];
    } else {       
       $pageNumber = '1'; 
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
	$apicall .= "&paginationInput.entriesPerPage=5";
    $apicall .= "&paginationInput.pageNumber=" . $pageNumber ;


	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
 
	// Check to see if the request was successful, else print an error
	if ($resp->ack == "Success") {
	  $results = '<ul class="ui-listview" data-role="listview">';
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
	   
	   
	   
	    $results .= "<li class=\"ui-li-has-thumb\" id=\"$itemId\">";
	    $results .= "<a class=\"ui-btn ui-btn-icon-right ui-icon-carat-r\" href=\"?itemID=$itemId&searchField=$safequery&pageNumber=$pageNumber\">";   	   
	    $results .= "<img class=\"ui-shadow ui-corner-all\" src=\"$pic\">";	   
	    $results .= "<h4 class=\"title\">$title</h4>";	
	    $results .= "<h5 class=\"categoryName\">$categoryName</h5>";
	    $results .= "<p class=\"location\">$location</p>";
	    $results .= "<p class=\"currentPrice\">Price: $$currentPrice</p>"; 	    
	    $results .= "<p class=\"sellingState\">Sale: $sellingState | Type:$listingType | Active Bids: $bidCount</p>"; 	    
	    $results .= "</a>"; 
	    $results .= "</li>"; 
	    
	  }
	  $results .= "</ul>"; 
	} else {
	  $results  = "<br>Use the search feature to look for EBAY items, we'll check the safety incident reports for the products you're interested in.<br><br><br><br>"; 
	} 
?>




<div data-role="page">
<div data-role="header">
<h1>SafeGoods EBAY Item Safety Check</h1>
</div> 


<div data-role="content">

<div class="ui-field-contain">
<form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
<label for="searchField">Search EBAY:</label>
<input type="search" name="searchField" id="searchField" value="<?php echo $safequery ?>"> 
 </form> 
</div>

 
 
 
<?php 
if (!empty($results)){ 
print <<<END
 
    $results 
    
END;
}
?>
</div> 
<?php if($query != ''){ include 'navbuttons.php'; } ?>
<?php include 'footer.php' ?> 

</div>
