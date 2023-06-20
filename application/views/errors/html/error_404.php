<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo STORE_NAME; ?> | 404 Page Not Found</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="shortcut icon" href="<?php echo config_item('base_url');?>/assets/images/favi-icon.png" type="image/x-icon"/>
	<link rel="stylesheet" type="text/css" href="<?php echo config_item('base_url');?>/assets/css/style-404.css">
	<script type="text/javascript" src="<?php echo config_item('base_url');?>/assets/js/jquery-1.11.3.min.js"></script>
</head>
<body>
	<div class="wrap">
		<header class="header-404">
			<a href="javascript:;"><img src="<?php echo config_item('base_url');?>/assets/images/ahr_logo_138X80_wt.png"></a>
		</header>
		<!--start-content-->
		<div class="content">
			<!--start-logo-->
			<div class="logo-404">

				<span><img src="<?php echo config_item('base_url');?>/assets/images/exclamation-mark-sign.png">Page Not Found!</span>
				<div>
					<br/>
					<h6>We couldn't find the page you requested. </h6>
				</div>
			</div>
			<!--end-logo-->
			<!--start-search-bar-section-->
			<div class="buttom">
				<div class="seach_bar">
					<p>Go to <span><a href="<?php echo config_item('base_url');?>">home</a></span> page</p>
				</div>
			</div>
			<!--end-sear-bar-->
			<!--copy-right-->
			<p class="copy_right">&copy; <?php echo date('Y'); ?> <?php echo STORE_NAME; ?>. All Rights Reserved. </p>
		</div>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
		

		$(document).ready(function(){
			    resizeContent();

			    $(window).resize(function() {
			        resizeContent();
			    });
			});

			function resizeContent() {
			    $height = $(window).height() - 85;
			    $('.wrp').height($height);
			}
	</script>
</body>
</html>