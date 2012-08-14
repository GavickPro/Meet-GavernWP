<?php 
	
	/**
	 *
	 * Template elements before the page content
	 *
	 **/
	
	// create an access to the template main object
	global $tpl;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>

<?php if(gk_is_active_sidebar('header')) : ?>
	<section id="gk-header">
		<div class="gk-page">
			<?php gk_dynamic_sidebar('header'); ?>
		</div>
	</section>
<?php endif; ?>

<?php if(gk_is_active_sidebar('top')) : ?>
<section id="gk-top">
	<div class="gk-page widget-area">
		<?php gk_dynamic_sidebar('top'); ?>
	</div>
</section>
<?php endif; ?>


<section class="gk-page-wrap">
	<section class="gk-page">
		<section id="gk-mainbody-columns">
			<?php 
			if(
				get_option($tpl->name . '_page_layout', 'right') == 'left' && 
				gk_is_active_sidebar('sidebar') && 
				(
					$args == null || 
					($args != null && $args['sidebar'] == true)
				)
			) : ?>
			<aside id="gk-sidebar">
				<?php gk_dynamic_sidebar('sidebar'); ?>
			</aside>
			<?php endif; ?>
			
			<section>
				<?php if(gk_is_active_sidebar('mainbody_top')) : ?>
				<section id="gk-mainbody-top">
					<?php gk_dynamic_sidebar('mainbody_top'); ?>
				</section>
				<?php endif; ?>
				
				<!-- Mainbody, breadcrumbs -->
				<?php if(gk_show_breadcrumbs()) : ?>
				<section id="gk-breadcrumb-fontsize">
					<?php if(gk_show_breadcrumbs()) gk_breadcrumbs_output(); ?>
				</section>
				<?php endif; ?>