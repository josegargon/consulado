<?php
// this file contains the contents of the popup window

$curr_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$curr_url = explode('wp-content', $curr_url);
$sp_ajaxurl = 'http://'.$curr_url[0].'wp-admin/admin-ajax.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> Click on a shortcode image to insert sample into post </title>
<meta http-equiv="Expires" content="Sat, 1 Jan 2000 08:00:00 GMT" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="includes/js/script_tinymce.js"></script>
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" />
<style type="text/css"> 

    .scrollPanel a {
        display:block;
        width:99%;
        padding:3px;
        
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #f1f1f1;
        
        margin-bottom: 5px;
        
        cursor: pointer;
    }
    .scrollPanel a:hover {
        border: 1px solid #b5b5b5;
        color: #0a6fb6;
    }
    .scrollPanel h2 {
        color:#1a1a1a;
    }

</style>
</head>
<body class="">
    <form action="/" method="get" accept-charset="utf-8">   
    <div id="ScrollContainer">
        <div id="Scroller">
            <!-- Pick file -->
            <div id="PickingShortcode" class="scrollPanel">
                <input type="hidden" class="shortcode_input" value="" />

                <h2> Headings </h2><br/>
                <a href="#" id="h1" class="shortcode"><img src="includes/images/shortcode_h1.png" /></a>
                <a href="#" id="h2" class="shortcode"><img src="includes/images/shortcode_h2.png" /></a>
                <a href="#" id="h3" class="shortcode"><img src="includes/images/shortcode_h3.png" /></a>
                <a href="#" id="h4" class="shortcode"><img src="includes/images/shortcode_h4.png" /></a>
                <a href="#" id="h5" class="shortcode"><img src="includes/images/shortcode_h5.png" /></a>

                <h2> Blockquote </h2><br/>
                <a href="#" id="bq" class="shortcode"><img src="includes/images/shortcode_bq.png" /></a>

                <h2> Lists </h2><br/>
                <a href="#" id="list1" class="shortcode"><img src="includes/images/shortcode_list1.png" /></a>
                <a href="#" id="list2" class="shortcode"><img src="includes/images/shortcode_list2.png" /></a>
                <a href="#" id="list3" class="shortcode"><img src="includes/images/shortcode_list3.png" /></a>

                <h2> Message boxes </h2><br/>
                <a href="#" id="msg1" class="shortcode"><img src="includes/images/shortcode_message1.png" /></a>
                <a href="#" id="msg2" class="shortcode"><img src="includes/images/shortcode_message2.png" /></a>
                <a href="#" id="msg3" class="shortcode"><img src="includes/images/shortcode_message3.png" /></a>
                <a href="#" id="msg4" class="shortcode"><img src="includes/images/shortcode_message4.png" /></a>

                <h2> Tab </h2><br/>
                <a href="#" id="tab" class="shortcode"><img src="includes/images/shortcode_tab.png" /></a>

                <h2> Accordion </h2><br/>
                <a href="#" id="acc" class="shortcode"><img src="includes/images/shortcode_accordion.png" /></a>
            </div>
        </div><!--EOF:Scroller-->
    </div>
    
    </div>
</body>
</html>