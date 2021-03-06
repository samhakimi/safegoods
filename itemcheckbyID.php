<?php
error_reporting(E_ALL);   


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
	    $Category = $item->PrimaryCategoryName;	
        
	    $BidCount = $item->BidCount;
	    $ConvertedCurrentPrice = (float)$item->ConvertedCurrentPrice;
            //$Price = '$' . $ConvertedCurrentPrice  ;
	    setlocale(LC_MONETARY, 'en_US.UTF-8');
	    $Price = money_format('%i', $ConvertedCurrentPrice); 
	    $ListingStatus = $item->ListingStatus;
	    $Title = $item->Title;
	    $ConditionID = $item->ConditionID;
	    $ConditionDisplayName = $item->ConditionDisplayName;
	   
	    $results .= "<div id=\"$ItemID\">";	   
	    $results .= "<h2 class=\"Title\">$Title</h2>";	   
	    $results .= "<h5 class=\"Category\">$Category</h5>";
	    //$results .= "<img class=\"GalleryURL\" src=\"$GalleryURL\">";
	    $results .= "<img style=\"max-width: 100%;\" class=\"PictureURL ui-overlay-shadow ui-corner-all\" src=\"$PictureURL\">";	   
	    $results .= "<div class=\"Price\">$Price</div>";
	    $results .= "<div class=\"Location\">Location : $Location</div>";
	    $results .= "<div>Condition : $ConditionDisplayName</div>";
	    $results .= "<div class=\"BidCount\">Current Bid Count : $BidCount</div>";
	    $results .= "<div><a class=\"ui-btn ui-icon-navigation ui-btn-icon-left ui-corner-all ui-shadow ui-btn-inline\" href=\"$ViewItemURLForNaturalSearch\">see item at ebay</a></div>";
	    //$results .= "<div class=\"EndTime\">$EndTime</div>";
	    
	    $results .= "</div>";
	    $results .= "</div>";
	    
	    
	    
	    //recalls info gathering 

	    
	    
	    	    
	    $recallSearch = strtolower($Category); 	    
	    $recallSearch = preg_replace('/[\$\!\?"\/,\.-\d\(\)]/', '', $recallSearch);   
	    $recallSearch = preg_replace('/\b(ebay|now|old|vintage|brand|collectibles|other|manufactured|factory|modern|new|used|one|two|in|and|with)\b/', '', $recallSearch);  
	    $recallSearch = explode(':', $recallSearch);
	    $recallSearch = array_filter($recallSearch);	     
	    krsort($recallSearch); //start from detailed category first 
	     
	    
	    foreach ($recallSearch as &$searchKeyword) { 
	    
	    // API request variables
	    $recallendpoint = 'https://www.cpsc.gov/cgibin/CPSCUpcWS/CPSCUpcSvc.asmx/getRecallByWord';  // URL to call
	    // Construct the HTTP GET call 
	    $recallendpoint = "$recallendpoint?";
	    $recallendpoint .= "message1=$searchKeyword"; 
	    $recallendpoint .= "&password=safeGoods"; 
	    $recallendpoint .= "&userID=safeGoods"; 
 

	    // Load the call and capture the document returned by eBay API
	    $recallresp = simplexml_load_file($recallendpoint);
	     
	    if($recallresp->attributes()->outcome == "success"){
       
         $totalRecalls = count($recallresp->results->result);
       
	     $results .= '<div class="ui-block-b" style="padding-left: 10px;"><h2>Recalls for "' . $searchKeyword . '" found ' . $totalRecalls . '</h2>' ;
	    
	    if ($totalRecalls > 5) {
	       $results .= '<form><input type="text" data-type="search" id="filterable-input" placeholder="You can filter this list of recalls"></form>'; 	    
	    }
	    
	       $results .= '<div data-role="collapsible-set" data-filter="true" data-input="#filterable-input">';
	    
	       
	    foreach($recallresp->results->result as $recall) { 
	       
	       $recallNo = $recall->attributes()->recallNo;
	       $recDate = $recall->attributes()->recDate;
	       $recallURL = $recall->attributes()->recallURL;
	       $manufacturer = $recall->attributes()->manufacturer;
	       $prname = $recall->attributes()->prname;
	       if ($prname == ""){if ($manufacturer != "") {$prname = $manufacturer;} else {$prname = "No Title";}}
	       $hazard = $recall->attributes()->hazard;
	       $country_mfg = $recall->attributes()->country_mfg;
	       if ($country_mfg == ""){$country_mfg = "Country info not provided";}
	       
	       
	           
	        $results .= "<div data-role=\"collapsible\" id=\"$recallNo\">";
	        $results .= "<p class=\"ui-li-aside\">$recDate</p>"; 	        $results .= "<h3>$prname</h3>"; 
	        $results .= "<p class=\"hazard\">Hazard: $hazard</p>"; 
	        $results .= "<p class=\"manufacturer\">Manufacturer:  $manufacturer <br> Manufactured in: $country_mfg <br><br>";
	        $results .= "<a class=\"ui-btn ui-icon-alert ui-btn-icon-left ui-corner-all ui-shadow ui-btn-inline getMoreInfoLink\" data-transition=\"flip\" href=\"$recallURL\">More Information</a></p>"; 
	        
	        $results .= "</div>";
	        }
	        
	        $results .= "</div>"; //close out the collapsible set
	       	    
	    }
	    
	   $results .= "</div>"; //close out ui block b
	   if ($totalRecalls > 0) break; //no need to go up the category chain since we returned some recalls  
	    }        
	 $results .= "</div>";//closeout the a/b grid
	} else {
	  $results  = "Communication error. Please reload page.";
	}
 
?> 






<div data-role="page">
<div data-role="header">
<a id="backButton" href="?searchField=<?php echo $searchField ?>&pageNumber=<?php echo $pageNumber ?>" data-icon="arrow-l">Back to List</a>
<h1>SafeGoods Check : <?php echo $Title ?></h1> 
</div>
 
<div role="main" class="ui-content"> 
<?php if (!empty($results)){ 
print <<<END
 
    $results 
    
END;
}
?>


<script>
/*$(".getMoreInfoLink").click(function() {
      event.preventDefault(); 

    var url = $(this).attr("href");
    console.log(url);
    
    $.ajax({
           type: "GET",
           url: url, 
           success: function(data)
           {
               $(this).parent().append('Thank you!');
              // console.log(data); // show response from the php script.
           }
         });

    return false; // avoid to execute the actual submit of the form.
});*/
</script>  
<?php include 'disclaimer.php' ?> 
 
</div>
<?php include 'footer.php' ?> 
</div>
