<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- integrate shopzilla and the recall api -->
        
	    <?php
        if (!empty($_POST)){

           if(isset($_POST['agreed'])) { 
               setcookie('cspc_agreement','agreed',time()+60*60*24*1);
           } 
        } 
        ?>

 

        
        <?php if (isset($_COOKIE['cspc_agreement']) AND ($_COOKIE['cspc_agreement'] == "agreed") OR isset($_POST['agreed'])) {


               if(isset($_GET['itemID'])) { 
                            include 'itemcheckbyID.php';
               } else {   
                            include 'results.php';   
               }
     
               include 'disclaimer.php'; 

             }else{
               
                include 'agreement.php'; 
             }
         ?> 




       <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-51282511-1', 'unclesams.us');
          ga('send', 'pageview');

        </script>
    </body>
</html>
