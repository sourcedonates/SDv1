<?php
/*
 *  SourceDonates - A donator interface and management system for Source game servers and various Forum Systems.
 *  Copyright (C) 2012 Werner "Arrow768" Maisl
 *
 *  This Software may only be hosted by the copyright holder
 *  You are not allowed to copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software
 *  You are not allowed to host this Software on your own
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 *  INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 *  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 *  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 *  If you have any questions about this Software, fell free to send me a email:
 *  arrow768 AT sourcedonates DOT com
 */
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?=$community_name?> - Source Donates</title>
  <meta name="description" content="Source donates is an open source donation system that allows communities to set up an easy way to accept donates and give the user something in return.">
  <meta name="author" content="Arrow & Roger">

  <meta name="viewport" content="width=device-width">
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-35092359-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  <link href="<? echo base_url('css/style.css')?>" rel="stylesheet" />
  
  <!-- all scripts -->
  <script src="<? echo base_url('js/libs/jquery-1.7.1.min.js');?>" type="text/javascript"></script>
  <script src="<? echo base_url('js/plugins.js');?>" type="text/javascript"></script>
  <script src="<? echo base_url('js/script.js');?>" type="text/javascript"></script>
  <script src="<? echo base_url('js/libs/modernizr-2.5.3.min.js');?>"> </script> 
   <!-- end all scripts -->
  
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
<body id="home" style="margin-top: -5px; opacity: 0;">

  <div id="wrapper">
      
    <?php if($forum_login === 0):?>
    <div class="modal error center">
      <p class="container">
        You are not <a href="#">logged in</a>. Therefore you will have to enter a your forum-email. 
        <a href="#">more info</a>
      </p>
      <a href="#" class="close clean">x</a>
    </div>
    <?php endif; ?>
    
    <header>
      <div class="container clearfix">
        <h1 class="nomargin float-left">
          <a class="clean clearfix" href="<? echo base_url('index.php/donate');?>">
            <img class="float-left" src="<? echo base_url($community_logo);?>" alt="<?=$community_name?>">
            <span class="float-left"><?=$community_name?></span>
          </a>
        </h1>
        
        <nav class="">
          <ul class="nomargin clean-list float-left">
            <li><a href="<?php echo base_url('index.php/hof');?>">Hall Of Fame</a></li>
            <li class="last"><a href="<? echo base_url('index.php/faq');?>">FAQ</a></li>
          </ul>
        </nav>
    
      </div>
    </header>