
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
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
	<?php elseif($page_name == 'blog_details'): ?>
		<meta name="keywords" content="<?php echo $blog_details['keywords']; ?>"/>
		<meta name="description" content="<?php echo ellipsis(strip_tags(htmlspecialchars_decode($blog_details['description'])), 140); ?>" />
	<?php else: ?>
		<meta name="keywords" content="<?php echo get_settings('website_keywords'); ?>"/>
		<meta name="description" content="<?php echo get_settings('website_description'); ?>" />
	<?php endif; ?>

	<!--Social sharing content-->
	<?php if($page_name == "course_page"): ?>
		<meta property="og:title" content="<?php echo $title['title']; ?>" />
		<meta property="og:image" content="<?php echo $this->crud_model->get_course_thumbnail_url($course_id); ?>">
	<?php elseif($page_name == 'blog_details'): ?>
		<meta property="og:title" content="<?php echo $blog_details['title']; ?>" />
		<?php $blog_banner = 'uploads/blog/banner/'.$blog_details['banner']; ?>
        <?php if(!file_exists($blog_banner) || !is_file($blog_banner)): ?>
            <?php $blog_banner = 'uploads/blog/banner/placeholder.png'; ?>
        <?php endif; ?>
		<meta property="og:image" content="<?php echo base_url($blog_banner); ?>">	
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

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-MB7Z6HJ');</script>
	<!-- End Google Tag Manager -->

</head>
<body class="gray-bg">

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MB7Z6HJ"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<?php
	if($this->session->userdata('app_url')):
		include "go_back_to_mobile_app.php";
	endif;

	if ($this->session->userdata('user_login')) {
		include 'logged_in_header.php';
	}else {
		include 'logged_out_header.php';
	}

	if(get_frontend_settings('cookie_status') == 'active'):
    	include 'eu-cookie.php';
  	endif;
  	
  	if($page_name === null){
  		include $path;
  	}else{
		include $page_name.'.php';
	}
	include 'footer.php';
	include 'includes_bottom.php';
	include 'modal.php';
	include 'common_scripts.php';
	?>

	<!--{elapsed_time}-->

</body>
</html>