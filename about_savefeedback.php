<?php //save feedback submission and say thanks. no layout info this is ajax'ed into about.php
  

 
$file = 'feedback.txt'; 
$theFeedback = date('l \t\h\e jS')."\n";
//$theFeedback .= $_GET['email']."\n";
//$theFeedback .= htmlspecialchars($_GET['feedback'])."\n\n\n";
// Write the contents to the file, 
// using the FILE_APPEND flag to append the content to the end of the file
// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
file_put_contents(./feedback/$file, $theFeedback, FILE_APPEND | LOCK_EX);
?>
