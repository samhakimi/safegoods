<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging
?>

<?php
 


   if(isset($_GET['itemID'])) { 
   
    $ItemID = $_GET['itemID'];
	// API request variables
	$endpoint = 'http://open.api.ebay.com/shopping';  // URL to call 
	$appid = 'Codedomi-6e63-454a-baa4-50126bb6f613';   
	// Construct the findItemsByKeywords HTTP GET call 
	$apicall = "$endpoint?";
	$apicall .= "callname=GetSingleItem";
	$apicall .= "&responseencoding=XML";
	$apicall .= "&version=873";
	$apicall .= "&appid=$appid";
	$apicall .= "&siteid=0";
	$apicall .= "&ItemID=$ItemID";




	// Load the call and capture the document returned by eBay API
	$resp = simplexml_load_file($apicall);
    }
 
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
	    $categories = explode(":", $PrimaryCategoryName);//get the last string;	    
	    $CategoryName = end($categories);
	    $BidCount = $item->BidCount;
	    $ConvertedCurrentPrice = $item->ConvertedCurrentPrice;
	    $ListingStatus = $item->ListingStatus;
	    $Title = $item->Title;
	    $ConditionID = $item->ConditionID;
	    $ConditionDisplayName = $item->ConditionDisplayName;
	   
	    $results .= "<div class=\"item\" id=\"$ItemID\">";	   
	    //$results .= "<img class=\"GalleryURL\" src=\"$GalleryURL\">";
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
	    // maybe split title, get rid of all word less than 5 letters long
	     
	    
	    //$stringForRecalls = preg_replace("([0-9]+)","", $stringForRecalls);
	    //$stringForRecalls = preg_replace("(with.*)","", $stringForRecalls);
	    //$stringForRecalls = preg_replace("(\s.*)","", $stringForRecalls);

	    // API request variables
	    $recallendpoint = 'https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord';  // URL to call
	    // Construct the HTTP GET call 
	    $recallendpoint = "$recallendpoint?";
	    $recallendpoint .= "message1=$CategoryName"; 
	    $recallendpoint .= "&password=safeGoods"; 
	    $recallendpoint .= "&userID=safeGoods"; 

        //print_r($recallendpoint);

	    // Load the call and capture the document returned by eBay API
	    $recallresp = simplexml_load_file($recallendpoint);
	    
	    if($recallresp->attributes->outcome == "success"){
       
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
	          
	} else {
	  $results  = "eBay responded with error code";
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

 
    $results 
    
END;
} else {

print "<div id=\"howTo\">Enter an eBay item ID to see any safety recalls on the product.</div>";
}

?>
