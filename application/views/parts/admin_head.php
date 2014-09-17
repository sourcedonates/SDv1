<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Dashboard I Admin Panel</title>

    <link rel="stylesheet" href="<?php echo base_url('css/layout.css')?>" type="text/css" media="screen" />
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="<?php echo base_url('css/ie.css')?>" type="text/css" media="screen" />
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('js/jquery-1.5.2.min.js')?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/hideshow.js')?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/jquery.tablesorter.min.js')?>" type="text/javascript"></script>
    <script src="<?php echo base_url('js/jquery.equalHeight.js')?>" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        { 
          $(".tablesorter").tablesorter(); 
         } 
        );
        $(document).ready(function() {

        //When page loads...
        $(".tab_content").hide(); //Hide all content
        $("ul.tabs li:first").addClass("active").show(); //Activate first tab
        $(".tab_content:first").show(); //Show first tab content

        //On Click Event
        $("ul.tabs li").click(function() {

                $("ul.tabs li").removeClass("active"); //Remove any "active" class
                $(this).addClass("active"); //Add "active" class to selected tab
                $(".tab_content").hide(); //Hide all tab content

                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                $(activeTab).fadeIn(); //Fade in the active ID content
                return false;
        });

        });
    </script>
    <script type="text/javascript">
        $(function(){
            $('.column').equalHeight();
        });
    </script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-35092359-1']);
        _gaq.push(['_setDomainName', 'sourcedonates.com']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>
    </head>