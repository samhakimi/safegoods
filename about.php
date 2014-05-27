<div data-role="page" id="about" data-dialog="true"
<?php if ($agreementSigned == FALSE) {echo "data-close-btn=\"none\" ";} ?>
>
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
    <textarea cols="40" rows="8" name="feedback" id="feedback"></textarea>
    <button type="submit" data-theme="a" class="ui-btn-hidden" aria-disabled="false">Submit</button> 
    </form>
<script>
$("#feedbackForm").submit(function() {
      event.preventDefault();
     // console.log( $( this ).serialize() );

    var url = "./about_savefeedback.php"; 

    $.ajax({
           type: "GET",
           url: url,
           data: $("#feedbackForm").serialize(), // serializes the form's elements.
           success: function(data)
           {
               $('#feedbackForm').html('Thank you!');
               console.log(data); // show response from the php script.
           }
         });

    return false; // avoid to execute the actual submit of the form.
});
</script> 
</div> 
</div> 
