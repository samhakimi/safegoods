<div data-role="page" id="about" data-dialog="true">
<div data-role="header">
<h1>ABOUT</h1>
</div>
<div role="main" class="ui-content">
<pre>
This software is GPL v2.0.
Sam Hakimi @codedominion
sam@codedominion.com

</pre>


Designed for the CSPC ChallengePost entry, we are trying to match up eBAY items with potential recalls.

<br>
<br>
Feedback and Suggestions Welcome
<br>
<br>
    <form id="feedbackForm">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="" class="ui-input-text ui-body-c ui-corner-all ui-shadow-inset">

    <label for="textarea">Feedback:</label>
    <textarea cols="40" rows="2" name="feedback" id="feedback"></textarea>
    <button type="button" id="feedbackButton" data-theme="a" class="ui-btn-hidden" aria-disabled="false">Submit</button> 
    </form>



<script>
$("#feedbackButton").click(function() {
     //event.preventDefault();
      
     var url = "./about_savefeedback.php"; 
     $.ajax({
           type: "GET",
           url: url,
           data: $("#feedbackForm").serialize() // serializes the form's elements.
      })
      .done(function( data ) {
           $('#feedbackForm').html('Thank you!');
      }) 
      .fail(function() {
            console.log( "error" );
      })
      .always(function() {
             console.log( "complete" );
      });;
       
});
</script> 



</div> 
</div> 
