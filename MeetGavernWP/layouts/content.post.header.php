<?php

/**
 *
 * The template fragment to show post header
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $tpl; 

$params = get_post_custom();
$params_title = isset($params['gavern-post-params-title']) ? esc_attr( $params['gavern-post-params-title'][0] ) : 'Y';
$param_date = isset($params['gavern-post-params-date']) ? esc_attr( $params['gavern-post-params-date'][0] ) : 'Y';

?>

<?php if($param_date == 'Y') : ?>
<div class="date">
	<?php if(get_post_format() != '') : ?>
	<span class="format gk-format-<?php echo get_post_format(); ?>">
		<?php echo get_post_format(); ?>
	</span>
	<?php endif; ?>
	
	<time class="entry-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
		<?php echo esc_html(get_the_date('d F Y')); ?>	
	</time>
</div>
<?php endif; ?>


<?php if(get_the_title() != '' && $params_title == 'Y') : ?>
<h<?php echo (is_singular()) ? '1' : '2'; ?>>
	<?php if(!is_singular()) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
	<?php endif; ?>
		<?php the_title(); ?>
	<?php if(!is_singular()) : ?>
	</a>
	<?php endif; ?>
	
	<?php if(is_sticky()) : ?>
	<sup>
		<?php _e( 'Featured', GKTPLNAME ); ?>
	</sup>
	<?php endif; ?>
</h<?php echo (is_singular()) ? '1' : '2'; ?>>
<?php endif; ?>

<?php if((!is_page_template('template.fullwidth.php') && ('post' == get_post_type() || 'page' == get_post_type())) && get_the_title() != '') : ?>
	<?php if(!(is_page() && get_option($tpl->name . '_template_show_details_on_pages', 'Y') == 'N')) : ?>
		<?php if(!('post' == get_post_type() && get_option($tpl->name . '_post_aside_state', 'Y') == 'N')) : ?>
			<?php gk_post_meta(); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<?php do_action('gavernwp_before_post_content'); ?>