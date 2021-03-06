<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $page_title.' | '.get_settings('system_name'); ?></title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="<?php echo get_settings('author') ?>" />

	<meta name="keywords" content="<?php echo get_settings('website_keywords'); ?>"/>
	<meta name="description" content="<?php echo get_settings('website_description'); ?>" />

	<link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/'.get_frontend_settings('favicon')); ?>" rel="shortcut icon" />
	<?php include 'includes_top.php';?>
</head>
<body>
	<?php include 'payment_gateway.php'; ?>
	<?php
	include 'includes_bottom.php';
	if(get_frontend_settings('cookie_status') == 'active'):
    	include 'eu-cookie.php';
  	endif;
	?>
	<script type="text/javascript">
		"use strict";
		$(document).ready(function () {
		    if (localStorage.getItem("accept_cookie_academy")) {
		    }else{
		    	$('#cookieConsentContainer').fadeIn(1000);
		    }
		});

		function cookieAccept() {
		    if (typeof(Storage) !== "undefined") {
				localStorage.setItem("accept_cookie_academy", true);
				localStorage.setItem("accept_cookie_time", "<?php echo date('m/d/Y'); ?>");
				$('#cookieConsentContainer').fadeOut(1200);
		    }
		}
		function selectedPaymentGateway(gateway) {
			if (gateway == 'paypal') {

				$(".payment-gateway").css("border", "2px solid #D3DCDD");
				$('.tick-icon').hide();
				$('.form').hide();

				$(".paypal").css("border", "2px solid #00D04F");
				$('.paypal-icon').show();
				$('.paypal-form').show();
			} else if (gateway == 'stripe') {

				$(".payment-gateway").css("border", "2px solid #D3DCDD");
				$('.tick-icon').hide();
				$('.form').hide();

				$(".stripe").css("border", "2px solid #00D04F");
				$('.stripe-icon').show();
				$('.stripe-form').show();
			}
			else if (gateway == 'peach_payment') {

				$(".payment-gateway").css("border", "2px solid #D3DCDD");
				$('.tick-icon').hide();
				$('.form').hide();

				$(".peach_payment").css("border", "2px solid #00D04F");
				$('.peach_payment-icon').show();
				$('.peach_payment-form').show();
			}
		}
	</script>
</body>
</html>
