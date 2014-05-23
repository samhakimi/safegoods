<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
?>

<?php

if (!empty($_POST)){




   if(isset($_POST['searchField']) AND (trim($_POST['searchField']) != '')) {
       $query = htmlspecialchars(trim($_POST['searchField']));  
   } else {
       $query = ''; 

}
	// API request variables
	$endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // URL to call
	$version = '1.0.0';  // API version supported by your application
	$appid = 'Codedomi-6e63-454a-baa4-50126bb6f613';  // Replace with your own AppID
	$globalid = 'EBAY-US';  
	$safequery = urlencode($query);  // Make the query URL-friendly

	// Construct the findItemsByKeywords HTTP GET call 
	$apicall = "$endpoint?";
	$apicall .= "OPERATION-NAME=findItemsByKeywords";
	$apicall .= "&SERVICE-VERSION=$version";
	$apicall .= "&SECURITY-APPNAME=$appid";
	$apicall .= "&GLOBAL-ID=$globalid";
	$apicall .= "&keywords=$safequery";
	$apicall .= "&paginationInput.entriesPerPage=1";




	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);


?>
<pre>
<?php
	print_r($resp);
?>	
</pre>

<?php
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
	    $results .= "<div class=\"currentPrice\">".money_format('%i', floatval($currentPrice))."</div>";
	    $results .= "<div class=\"bidCount\">$bidCount</div>";
	    $results .= "<div class=\"link\"><a href=\"$link\">Go to eBAY</a></div>";
	    $results .= "<div class=\"sellingState\">$sellingState $listingType</div>";
	    $results .= "<div class=\"timeLeft\">$timeLeft</div>";
	    
	    $results .= "</div>";
	    
	    
	    
	    //recalls info gathering
	    

	    // API request variables
	    $recallendpoint = 'https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord';  // URL to call 
	    $saferecallquery = urlencode($title);  // Title from the eBay result, make the query URL-friendly

	    // Construct the HTTP GET call 
	    $recallendpoint = "$recallendpoint?";
	    $recallendpoint .= "message1=$safequery"; 
	    $recallendpoint .= "&password=safeGoods"; 
	    $recallendpoint .= "&userID=safeGoods"; 


	    // Load the call and capture the document returned by eBay API
	    $recallresp = simplexml_load_file($recallendpoint);
       
	    foreach($recallresp->results->result as $recall) { 
	       $recallNo = $recall->attributes()->recallNo;
	       $recallURL = $recall->attributes()->recallURL;
	       $manufacturer = $recall->attributes()->manufacturer;
	       $prname = $recall->attributes()->prname;
	       $hazard = $recall->attributes()->hazard;
	       $country_mfg = $recall->attributes()->country_mfg;
	       
	       
	           
	        $results .= "<div class=\"recall\" id=\"$recallNo\">";	   
	        
	        $results .= "<div class=\"hazard\">$hazard</div>"; 
	        $results .= "<div class=\"prname\">$prname</div>"; 
	        $results .= "<div class=\"manufacturer\">$manufacturer</div>"; 
	        $results .= "<div class=\"country_mfg\">$country_mfg</div>"; 
	        $results .= "<div class=\"link\"><a href=\"$recallURL\">more information</a></div>"; 
	        
	        $results .= "</div>";
	    
	       
	       
	    
	    }
	         
	    
	  }
	}
	// If the response does not indicate 'Success,' print an error
	else {
	  $results  = "eBay responded with error code";
	}



   }
?>




<pre>
<?php
	print_r($recallresp);
?>	
</pre>





<form method="post" action="">
 Search for  <input type="text" id="searchField"  name="searchField" />  <input type="submit" value="Find" name="submit"/> 
</form> 









<!-- Build the HTML page with values from the call response -->
 
<?php if (!empty($results)){ 

print <<<END

<h1>eBay Item $query</h1>

<table>
<tr>
  <td>
    $results
  </td>
</tr>
</table> 
END;
} else {

print "<div id=\"howTo\">Enter an eBay auction ID to see any safety recalls on the product.</div>";
}

?>
