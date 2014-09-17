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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>SourceDonates Admin Login</title>
	<!-- Simple OpenID Selector -->
	<link type="text/css" rel="stylesheet" href="<? echo base_url('css/openid.css')?>" />
	<script type="text/javascript" src="<? echo base_url('js/jquery-1.2.6.min.js')?>"></script>
	<script type="text/javascript" src="<? echo base_url('js/openid-jquery.js')?>"></script>
	<script type="text/javascript" src="<? echo base_url('js/openid-en.js')?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			openid.init('openid_identifier');
			//openid.setDemoMode(true); //Stops form submission for client javascript-only test purposes
		});
	</script>
	<!-- /Simple OpenID Selector -->
	<style type="text/css">
		/* Basic page formatting */
		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
</head>

<body>
	<h2>SourceDonates Admin Login</h2>
	<br/>
	<!-- Simple OpenID Selector -->
	<form action="<? echo base_url('index.php/admin/process_openid')?>" method="post" id="openid_form">
		<input type="hidden" name="action" value="verify" />
		<fieldset>
			<legend>Sign-in or Create New Account</legend>
			<div id="openid_choice">
				<p>Please click your account provider:</p>
				<div id="openid_btns"></div>
			</div>
			<div id="openid_input_area">
				<input id="openid_identifier" name="openid_identifier" type="text" value="http://" />
				<input id="openid_submit" type="submit" value="Sign-In"/>
			</div>
			<noscript>
				<p>OpenID is service that allows you to log-on to many different websites using a single indentity.
				Find out <a href="http://openid.net/what/">more about OpenID</a> and <a href="http://openid.net/get/">how to get an OpenID enabled account</a>.</p>
			</noscript>
		</fieldset>
	</form>
	<!-- /Simple OpenID Selector -->
</body>
</html>