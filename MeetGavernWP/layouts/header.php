<?php 
	
	/**
	 *
	 * Template header
	 *
	 **/
	
	// create an access to the template main object
	global $tpl;

?>
<?php do_action('gavernwp_doctype'); ?>
<html <?php do_action('gavernwp_html_attributes'); ?>>
<head>
	<title><?php do_action('gavernwp_title'); ?></title>
	<?php do_action('gavernwp_metatags'); ?>
	
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="shortcut icon" href="<?php get_stylesheet_directory_uri(); ?>/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/template.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/wp.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/stuff.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/wp.extensions.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/extensions.css" />
	<!--[if IE 9]>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie9.css" />
	<![endif]-->
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" />
	<![endif]-->
	
	<?php do_action('gavernwp_fonts'); ?>
	<?php gk_head_config(); ?>
	<?php wp_enqueue_script("jquery"); ?>
	
	<?php if(is_singular() && get_option('thread_comments' )) wp_enqueue_script( 'comment-reply' ); ?>
	
	<?php do_action('gavernwp_ie_scripts'); ?>
	
	<?php wp_head(); ?>
	<?php gk_head_shortcodes(); ?>
	<?php gk_head_style_css(); ?>
	<?php gk_head_style_pages(); ?>	
	
	<?php echo $assets_output; ?>
	
	<?php gk_load('responsive_css'); ?>
	
	<?php if(get_option($tpl->name . '_prefixfree_state', 'N') == 'Y') : ?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/prefixfree.js"></script>
	<?php endif; ?>
	
	<?php gk_thickbox_load(); ?>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/gk.scripts.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/gk.menu.js"></script>
	
	<?php if(get_option($tpl->name . "_overridecss_state", 'Y') == 'Y') : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/override.css" />
	<?php endif; ?>
	
	<?php do_action('gavernwp_head'); ?>
	
	<?php 
		echo stripslashes(
			htmlspecialchars_decode(
				str_replace( '&039;', "'", get_option($tpl->name . '_head_code', ''))
			)
		); 
	?>
</head>
<body <?php do_action('gavernwp_body_attributes'); ?>>
	<section class="gk-page">
		<header id="gk-head">
			<?php if(get_option($tpl->name . "_branding_logo_type", 'css') != 'none') : ?>
			<h1>
				<a href="<?php echo home_url(); ?>" class="<?php echo get_option($tpl->name . "_branding_logo_type", 'css'); ?>Logo"><?php gk_blog_logo(); ?></a>
			</h1>
			<?php endif; ?>
			
			<?php if(gk_show_menu('mainmenu')) : ?>
			<a href="#" id="gk-mainmenu-toggle">
				<?php _e('Main menu', GKTPLNAME); ?>
			</a>
			
			<div id="gk-mainmenu-collapse" class="menu-hidden" data-btn="gk-mainmenu-toggle">	
				<?php gavern_menu('mainmenu', 'gk-main-menu', array('walker' => new GKMenuWalker())); ?>
			</div>
			<?php endif; ?>
		</header>
	</section>
