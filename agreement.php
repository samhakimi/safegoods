<?php
   $agreementSigned = FALSE;
   if (isset($_COOKIE['cspc_agreement']) AND ($_COOKIE['cspc_agreement'] == "agreed")) {
           $agreementSigned = TRUE;
          }
?>
          


<div data-role="page" id="agreement" data-dialog="true"
<?php if ($agreementSigned == FALSE) {echo "data-close-btn=\"none\" ";} ?>
>
<div data-role="header">
<h1>DISCLAIMER</h1>
</div>
<div role="main" class="ui-content">
THE MATERIAL EMBODIED IN THIS SOFTWARE IS PROVIDED TO YOU “AS-IS” AND WITHOUT WARRANTY OF ANY KIND, EXPRESS, IMPLIED, OR OTHERWISE, INCLUDING WITHOUT LIMITATION, ANY WARRANTY OF FITNESS FOR A PARTICULAR PURPOSE. IN NO EVENT SHALL THE CPSC OR THE UNITED STATES GOVERNMENT BE LIABLE TO YOU OR ANYONE ELSE FOR ANY DIRECT, SPECIAL, INCIDENTAL, INDIRECT, OR CONSEQUENTIAL DAMAGES OF ANY KIND, OR ANY DAMAGES WHATSOEVER, INCLUDING WITHOUT LIMITATION, LOSS OF PROFIT, LOSS OF USE, SAVINGS OR REVENUE, OR THE CLAIMS OF THIRD PARTIES, WHETHER OR NOT DOC OR THE U.S. GOVERNMENT HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH LOSS, HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, ARISING OUT OF OR IN CONNECTION WITH THE POSSESSION, USE, OR PERFORMANCE OF THIS SOFTWARE.</span></p>


<?php if ($agreementSigned == FALSE) {
print <<<END

<form method="GET">
  <input type="submit" id="agreeButton" value="Agree" name="agreed" /> 
  <input type="button" id="notagreeButton"   value="I Do Not Agree" name="notagreed"  /> 
</form> 

<script>
    
        $( "#agreeButton" ).button({ icon: "check" });
        $( "#notagreeButton" ).button({ icon: "delete" });
</script>
END;
} ?>

</div> 
</div> 
