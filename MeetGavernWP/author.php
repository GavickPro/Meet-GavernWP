<?php

/**
 *
 * Author page
 *
 **/

global $tpl;

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody">
	<?php if ( have_posts() ) : ?>
	
		<?php the_post(); ?>
	
		<h1 class="page-title author">
			<?php printf( __( 'Author archives: %s %s', GKTPLNAME ), get_the_author_meta('first_name', get_the_author_meta( 'ID' )), get_the_author_meta('last_name', get_the_author_meta( 'ID' )) ); ?>
		</h1>
	
		<?php rewind_posts(); ?>
	
		<?php gk_author(true); ?>
	
		<?php do_action('gavernwp_before_loop'); ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
		
		<?php gk_content_nav(); ?>
		
		<?php do_action('gavernwp_after_loop'); ?>
	
	<?php else : ?>
		<h1 class="page-title">
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h1>
	
		<section class="intro">
			<?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', GKTPLNAME ); ?>
		</section>
		
		<?php get_search_form(); ?>
	<?php endif; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF