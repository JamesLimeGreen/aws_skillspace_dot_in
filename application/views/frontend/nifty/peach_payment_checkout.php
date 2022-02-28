<!DOCTYPE html>
<html lang="en">
<head>
  <title>Peach Payments | <?php echo get_settings('system_name');?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	if($payment_mode == 'TEST'):
?>
 <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkout_info->id; ?>"></script>

 <?php else: ?>
<script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkout_info->id; ?>"></script>

 <?php endif; ?>

  <link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/'.get_frontend_settings('favicon'));?>" rel="shortcut icon" />
</head>
<body>
  <form action="<?= base_url();?>/home/peach_payment" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
</body>
</html>
