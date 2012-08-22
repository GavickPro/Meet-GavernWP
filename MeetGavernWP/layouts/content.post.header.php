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

<?php if('post' == get_post_type() || 'page' == get_post_type()) : ?>
<?php gk_post_meta(); ?>
<?php endif; ?>

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