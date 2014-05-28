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
        <style>
          .pic {
            float: right;
            margin: 5px;
           
          }
        .ui-bar-a, .ui-page-theme-a .ui-bar-inherit, html .ui-bar-a .ui-bar-inherit, html .ui-body-a .ui-bar-inherit, html body .ui-group-theme-a .ui-bar-inherit  {
            /* background-color: #e9e9e9; */ 
            background-image: url('./bg.png');
            }
        .Price {
            font-size: 75px;
            float: right;
            }
          .Location {
            font-size: 12px;
            line-height: 50px;
            }
            
            h2 {
                letter-spacing: -2px;
                } 
           .ui-listview .ui-li-has-thumb>.ui-btn>img:first-child {
                position: absolute;
                left: 8px;
                top: 19px;
                max-height: 6em;
                max-width: 6em;
                }
            .ui-listview>li h1, .ui-listview>li h2, .ui-listview>li h3, .ui-listview>li h4, .ui-listview>li h5, .ui-listview>li h6, .ui-listview>li p {
                margin-left: 15px;
                }    
        </style>
    </head>
    <body>
     
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        
	    <?php 
	    
	    //assume not signed in
        $agreementSigned = FALSE;
	    
	    
	    //SET THE agreement cookie
        if (isset($_GET['agreed'])) { 
               setcookie('cspc_agreement','agreed',time()+60*60*24*1);  
               $agreementSigned = TRUE;
        }   
            
           
        if (isset($_COOKIE['cspc_agreement']) AND ($_COOKIE['cspc_agreement'] == "agreed")) {
           $agreementSigned = TRUE;
        }



        if ($agreementSigned == TRUE) {
               if(isset($_GET['itemID'])) { 
                    include 'itemcheckbyID.php';
        }else{   
                    include 'results.php';    
        }
     

         }else{
           include 'agreement.php';
         }
          
                  
        //include 'agreement.php';
        //include 'about.php';
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
