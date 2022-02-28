<!DOCTYPE html>
<html lang="en">
<head>
  <title>Peach Payment | <?php echo get_settings('system_name');?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?= $checkout_info->id; ?>"></script>

  <link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/'.get_frontend_settings('favicon'));?>" rel="shortcut icon" />
</head>
<body>
  <form action="<?= base_url();?>/admin/peach_payment" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
</body>
</html>
