<?php
error_reporting(E_ALL);  // Turn on all errors, warnings and notices for easier debugging 


   if(isset($_GET['itemID']) AND isset($_GET['searchField'])) { 
   
    $ItemID = $_GET['itemID'];
    $searchField = $_GET['searchField'];
    $pageNumber = $_GET['pageNumber'];
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
	    $results = '<div class="ui-grid-a" style="padding: 10px;"><div class="ui-block-a">';
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
	    $PrimaryCategoryName = preg_replace('/[,\.&-\d\(\)]/', ' ', $PrimaryCategoryName); ;	    
	    $categories = explode(":", $PrimaryCategoryName);//get the last string;	    
	    $CategoryName = end($categories);//last in the category list	    
	    $uselessCategories = array("Other", "Factory Manufactured"); 
        if (in_array($CategoryName, $uselessCategories, true)) {
           $CategoryName = array_pop($categories);
           $CategoryName = end($categories);
        }
	    $BidCount = $item->BidCount;
	    $ConvertedCurrentPrice = $item->ConvertedCurrentPrice;
	    $ListingStatus = $item->ListingStatus;
	    $Title = $item->Title;
	    $ConditionID = $item->ConditionID;
	    $ConditionDisplayName = $item->ConditionDisplayName;
	   
	    $results .= "<div id=\"$ItemID\">";	   
	    $results .= "<h2 class=\"Title\">$Title</h2>";	   
	    $results .= "<h5 class=\"PrimaryCategoryName\">$PrimaryCategoryName</h5>";
	    //$results .= "<img class=\"GalleryURL\" src=\"$GalleryURL\">";
	    $results .= "<img style=\"max-width: 100%;\" class=\"PictureURL ui-overlay-shadow ui-corner-all\" src=\"$PictureURL\">";	   
	    //$results .= "<div class=\"Location\">$Location</div>";
	    $results .= "<div class=\"ConvertedCurrentPrice\">\$$ConvertedCurrentPrice  | Condition: $ConditionDisplayName</div>";
	    //$results .= "<div class=\"BidCount\">$BidCount</div>";
	    $results .= "<div><a class=\"ui-btn ui-icon-navigation ui-btn-icon-left ui-corner-all ui-shadow ui-btn-inline\" href=\"$ViewItemURLForNaturalSearch\">see item at ebay</a></div>";
	    //$results .= "<div class=\"EndTime\">$EndTime</div>";
	    
	    $results .= "</div>";
	    $results .= "</div>";
	    
	    
	    
	    //recalls info gathering 

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
	    //print_r($recallresp);exit;
	    if($recallresp->attributes()->outcome == "success"){
       
       $totalRecalls = count($recallresp->results->result);
       
	    $results .= '<div class="ui-block-b" style="padding-left: 10px;"><h2>Recall Check:</h2>Found '.$totalRecalls . ' potential matches.';
	    
	    if ($totalRecalls > 0) {
	       $results .= ' <p>Please go through this list carefully. Even if you don\'t see an exact match, a high number of recalls may be indicative of problems with the underlying technology.</p>'; 
	    
	    }
	    
	       $results .= "<div data-role=\"collapsible-set\">";
	       
	       
	    foreach($recallresp->results->result as $recall) { 
	       $recallNo = $recall->attributes()->recallNo;
	       $recDate = $recall->attributes()->recDate;
	       $recallURL = $recall->attributes()->recallURL;
	       $manufacturer = $recall->attributes()->manufacturer;
	       $prname = $recall->attributes()->prname;
	       if ($prname == ""){$prname = "No Title";}
	       $hazard = $recall->attributes()->hazard;
	       $country_mfg = $recall->attributes()->country_mfg;
	       
	       
	           
	        $results .= "<div data-role=\"collapsible\" id=\"$recallNo\">";
	        $results .= "<p class=\"ui-li-aside\">$recDate</p>"; 	        $results .= "<h3>$prname</h3>"; 
	        $results .= "<p class=\"hazard\">Hazard: $hazard</p>"; 
	        $results .= "<p class=\"manufacturer\">Manufacturer:  $manufacturer <br> Manufactured in: $country_mfg <br><br>";
	        $results .= "<a class=\"ui-btn ui-icon-alert ui-btn-icon-left ui-corner-all ui-shadow ui-btn-inline\" href=\"$recallURL\">More Information</a></p>"; 
	        
	        $results .= "</div>";
	        }
	        
	        $results .= "</div>"; //close out the collapsible set
	        $results .= "</div>"; //close out ui block b
	       	    
	    }
	          
	        $results .= "</div>";//closeout the a/b grid
	} else {
	  $results  = "Communication error. Please reload page.";
	}
 
?>
<div data-role="page">
<div data-role="header">
<a id="backButton" href="?searchField=<?php echo $searchField ?>&pageNumber=<?php echo $pageNumber ?>" data-icon="arrow-l">Go Back</a>
<h1><?php echo $Title ?></h1> 
</div>
 
<div role="main" class="ui-content"> 
<?php if (!empty($results)){ 
print <<<END
 
    $results 
    
END;
}
?>
<?php include 'disclaimer.php' ?> 
<?php include 'footer.php' ?> 
 
</div>
</div>
