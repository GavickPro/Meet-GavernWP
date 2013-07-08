<?php

/**
 *
 * Archive page
 *
 **/

global $tpl;

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody">
	<h1 class="page-title">
		<?php if ( is_day() ) : ?>
			<?php printf( __( 'Daily Archives: %s', GKTPLNAME ), '<span>' . get_the_date() . '</span>' ); ?>
		<?php elseif ( is_month() ) : ?>
			<?php printf( __( 'Monthly Archives: %s', GKTPLNAME ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
		<?php elseif ( is_year() ) : ?>
			<?php printf( __( 'Yearly Archives: %s', GKTPLNAME ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
		<?php else : ?>
			<?php _e( 'Blog Archives', GKTPLNAME ); ?>
		<?php endif; ?>
	</h1>

	<?php if ( have_posts() ) : ?>
		<?php do_action('gavernwp_before_loop'); ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
	
		<?php gk_content_nav(); ?>
		
		<?php do_action('gavernwp_after_loop'); ?>
	<?php else : ?>
	
		<h1 class="entry-title"><?php _e( 'Nothing Found', GKTPLNAME ); ?></h1>
						
		<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', GKTPLNAME ); ?></p>
		
		<?php get_search_form(); ?>
	
	<?php endif; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF