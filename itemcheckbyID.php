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
	$endpoint = 'http://open.api.ebay.com/shopping';  // URL to call 
	$appid = 'Codedomi-6e63-454a-baa4-50126bb6f613';  // Replace with your own AppID 
	$safequery = urlencode($query);  // Make the query URL-friendly

	// Construct the findItemsByKeywords HTTP GET call 
	$apicall = "$endpoint?";
	$apicall .= "callname=GetSingleItem";
	$apicall .= "&responseencoding=XML";
	$apicall .= "&version=873";
	$apicall .= "&appid=$appid";
	$apicall .= "&siteid=0";
	$apicall .= "&ItemID=$safequery"; 




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
	if ($resp->Ack == "Success") {
	    $results = '';
        $item = $resp->Item;    	  
	    // If the response was loaded, parse it and build links   
	    $ItemID   = $item->ItemID;
	    $EndTime  = $item->EndTime;
	    $ViewItemURLForNaturalSearch = $item->ViewItemURLForNaturalSearch;
	    $Location = $item->Location;
	    $GalleryURL = $item->GalleryURL;
	    $PictureURL = $item->PictureURL;
	    $PrimaryCategoryID = $item->PrimaryCategoryID;
	    $PrimaryCategoryName = $item->PrimaryCategoryName;
	    $BidCount = $item->BidCount;
	    $ConvertedCurrentPrice = $item->ConvertedCurrentPrice;
	    $ListingStatus = $item->ListingStatus;
	    $Title = $item->Title;
	    $ConditionID = $item->ConditionID;
	    $ConditionDisplayName = $item->ConditionDisplayName;
	   
	    $results .= "<div class=\"item\" id=\"$ItemID\">";	   
	    $results .= "<img class=\"GalleryURL\" src=\"$GalleryURL\">";
	    $results .= "<img class=\"PictureURL\" src=\"$PictureURL\">";	   
	    $results .= "<div class=\"Title\">$Title</div>";	   
	    $results .= "<div class=\"PrimaryCategoryName\">$PrimaryCategoryName</div>";
	    $results .= "<div class=\"Location\">$Location</div>";
	    $results .= "<div class=\"ConvertedCurrentPrice\">$ConvertedCurrentPrice</div>";
	    $results .= "<div class=\"BidCount\">$BidCount</div>";
	    $results .= "<div class=\"link\"><a href=\"$ViewItemURLForNaturalSearch\">see item at ebay</a></div>";
	    $results .= "<div class=\"ConditionDisplayName\">$ConditionDisplayName</div>";
	    $results .= "<div class=\"EndTime\">$EndTime</div>";
	    
	    $results .= "</div>";
	    
	    
	    
	    //recalls info gathering
	    

	    // API request variables
	    $recallendpoint = 'https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord';  // URL to call 
	    $saferecallquery = urlencode($Title);  // Title from the eBay result, make the query URL-friendly

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
