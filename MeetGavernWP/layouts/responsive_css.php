<?php 
	
	/**
	 *
	 * Template part loading the responsive CSS code
	 *
	 **/
	
	// create an access to the template main object
	global $tpl;
	global $fullwidth;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
	$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	
?>

<style type="text/css">
	.gk-page { max-width: <?php echo get_option($tpl->name . '_template_width', 980); ?>px; }
	<?php if(
		get_option($tpl->name . '_page_layout', 'right') != 'none' && 
		gk_is_active_sidebar('sidebar') && 
		($fullwidth != true)
	) : ?>
	#gk-mainbody-columns > aside { width: <?php echo get_option($tpl->name . '_sidebar_width', '30'); ?>%;}
	#gk-mainbody-columns > section { width: <?php echo 100 - get_option($tpl->name . '_sidebar_width', '30'); ?>%; }
	<?php else : ?>
	#gk-mainbody-columns > section { width: 100%; }
	<?php endif; ?>
</style>

<?php if($iPod || $iPhone || $iPad) : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/tablet.css" media="screen and (max-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px)" />
<?php else : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/tablet.css" media="screen and (max-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-moz-max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-o-max-device-pixel-ratio: 1.5/1), only screen  and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-webkit-max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-moz-min-device-pixel-ratio: 2), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-o-min-device-pixel-ratio: 2/1), only screen and (max-device-width: <?php echo get_option($tpl->name . '_tablet_width', '800'); ?>px) and (-webkit-min-device-pixel-ratio: 2),only screen and (max-device-width: <?php echo 2* get_option($tpl->name . '_tablet_width', '800'); ?>px) and (min-device-pixel-ratio: 2)" />
<?php endif; ?>

<?php if($iPod || $iPhone || $iPad) : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/mobile.css" media="screen and (max-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px)" />
<?php else : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/mobile.css" media="screen and (max-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-moz-max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-o-max-device-pixel-ratio: 1.5/1), only screen  and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-webkit-max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (max-device-pixel-ratio: 1.5), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-moz-min-device-pixel-ratio: 2), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-o-min-device-pixel-ratio: 2/1), only screen and (max-device-width: <?php echo get_option($tpl->name . '_mobile_width', '800'); ?>px) and (-webkit-min-device-pixel-ratio: 2),only screen and (max-device-width: <?php echo 2* get_option($tpl->name . '_mobile_width', '800'); ?>px) and (min-device-pixel-ratio: 2)" />
<?php 
endif;
// EOF