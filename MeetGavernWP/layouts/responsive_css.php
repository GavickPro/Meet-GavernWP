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
	
	@media (min-width: <?php echo get_option($tpl->name . '_tablet_width', '800') + 1; ?>px) {
		#gk-mainmenu-collapse { height: auto!important; }
	}
</style>

<?php

// check the dependicies for the tablet.css file
if(get_option($tpl->name . "_shortcodes3_state", 'Y') == 'Y') {
	wp_enqueue_style('gavern-tablet', gavern_file_uri('css/tablet.css'), array('gavern-shortcodes-template'), false, '(max-width: '. get_option($tpl->name . '_tablet_width', '800') . 'px)');
} elseif(get_option($tpl->name . "_shortcodes2_state", 'Y') == 'Y') {
	wp_enqueue_style('gavern-tablet', gavern_file_uri('css/tablet.css'), array('gavern-shortcodes-elements'), false, '(max-width: '. get_option($tpl->name . '_tablet_width', '800') . 'px)');
} elseif(get_option($tpl->name . "_shortcodes1_state", 'Y') == 'Y') {
	wp_enqueue_style('gavern-tablet', gavern_file_uri('css/tablet.css'), array('gavern-shortcodes-typography'), false, '(max-width: '. get_option($tpl->name . '_tablet_width', '800') . 'px)');
} else {
	wp_enqueue_style('gavern-tablet', gavern_file_uri('css/tablet.css'), array('gavern-extensions'), false, '(max-width: '. get_option($tpl->name . '_tablet_width', '800') . 'px)');
}
// mobile.css is always loaded after the tablet.css file
wp_enqueue_style('gavern-mobile', gavern_file_uri('css/mobile.css'), array('gavern-tablet'), false, '(max-width: '. get_option($tpl->name . '_mobile_width', '800') . 'px)');

// EOF