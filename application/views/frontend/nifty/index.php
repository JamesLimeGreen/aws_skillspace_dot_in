<?php 
$currentdata = $this->session->get_userdata(); 

//echo "<pre>"; print_r($currentdata);
?>
<!DOCTYPE html>
<html lang="<?php echo substr($currentdata['language'], 0, 2); ?>">
 <head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M9778VC');</script>
<!-- End Google Tag Manager -->

	<?php if ($page_name == 'course_page'):
		$title = $this->crud_model->get_course_by_id($course_id)->row_array()?>
		<title><?php echo $title['title'].' | '.get_settings('system_name'); ?></title>
	<?php else: ?>
		<title><?php echo ucwords($page_title).' | '.get_settings('system_name'); ?></title>
	<?php endif; ?>


	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="<?php echo get_settings('author') ?>" />

	<?php
	$seo_pages = array('course_page');
	if (in_array($page_name, $seo_pages)):
		$course_details = $this->crud_model->get_course_by_id($course_id)->row_array();?>
		<meta name="keywords" content="<?php echo $course_details['meta_keywords']; ?>"/>
		<meta name="description" content="<?php echo $course_details['meta_description']; ?>" />
	<?php else: ?>
		<meta name="keywords" content="<?php echo get_settings('website_keywords'); ?>"/>
		<meta name="description" content="<?php echo get_settings('website_description'); ?>" />
	<?php endif; ?>

	<!--Social sharing content-->
	<?php if($page_name == "course_page"): ?>
		<meta property="og:title" content="<?php echo $title['title']; ?>" />
		<meta property="og:image" content="<?php echo $this->crud_model->get_course_thumbnail_url($course_id); ?>">
	<?php else: ?>
		<meta property="og:title" content="<?php echo $page_title; ?>" />
		<meta property="og:image" content="<?= base_url("uploads/system/".get_frontend_settings('banner_image')); ?>">
	<?php endif; ?>
	<meta property="og:url" content="<?php echo current_url(); ?>" />
	<meta property="og:description" content="<?php echo get_settings('website_description'); ?>">
	<meta property="og:type" content="Learning management system" />
	<!--Social sharing content-->

	<link name="favicon" type="image/x-icon" href="<?php echo base_url('uploads/system/'.get_frontend_settings('favicon')); ?>" rel="shortcut icon" />
	<?php include 'includes_top.php';?>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M9778VC"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php
		include 'header.php';

		if(get_frontend_settings('cookie_status') == 'active'):
	    	include 'eu-cookie.php';
	  	endif;
	?>
	<main id="content" role="main">
		<?php include $page_name.'.php'; ?>
	</main>
	<?php
		include 'footer.php';
		include 'includes_bottom.php';
		include 'modal.php';
		include 'common_scripts.php';
	?>
</body>
</html>
