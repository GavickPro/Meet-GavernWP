<?php

/**
 *
 * The template fragment to show post header
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $tpl; 

?>

<?php if((!is_page_template('template.fullwidth.php') && ('post' == get_post_type() || 'page' == get_post_type())) && get_the_title() != '') : ?>
	<?php if(!(is_page() && get_option($tpl->name . '_template_show_details_on_pages', 'Y') == 'N')) : ?>
	<?php gk_post_meta(); ?>
	<?php endif; ?>
<?php endif; ?>

<?php if(get_the_title() != '') : ?>
<hgroup>
	<h<?php echo (is_single()) ? '1' : '2'; ?>>
		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		
		<?php if(is_sticky()) : ?>
		<sup>
			<?php _e( 'Featured', GKTPLNAME ); ?>
		</sup>
		<?php endif; ?>
	</h<?php echo (is_single()) ? '1' : '2'; ?>>
</hgroup>
<?php endif; ?>

<?php do_action('gavernwp_before_post_content'); ?>