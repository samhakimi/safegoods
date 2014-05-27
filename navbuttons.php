<div class="ui-grid-a">
  <div class="ui-block-a">
 
  <a   <?php if ($pageNumber == 1) {echo "style=\"display:none;\"" ;} ?>  class="ui-btn ui-icon-arrow-l ui-btn-icon-left ui-corner-all ui-shadow" href="?searchField=<?php echo $safequery ?>&pageNumber=<?php echo $pageNumber-1; ?>">Back</a>
  
  
  </div>
  <div class="ui-block-b">
    <a class="ui-btn ui-icon-arrow-r ui-btn-icon-right ui-corner-all ui-shadow" href="?searchField=<?php echo $safequery ?>&pageNumber=<?php echo $pageNumber+1; ?>">Next</a>
  </div>
</div>
