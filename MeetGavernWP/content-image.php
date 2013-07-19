<?php

/**
 *
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 **/

global $tpl; 

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'indexed' ); ?>>
		<header class="image">
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>

		<?php get_template_part( 'layouts/content.post.featured' ); ?>

		<?php if ( is_search() || is_archive() || is_tag() ) : ?>
		<section class="summary">
			<?php the_excerpt(); ?>
		</section>
		<?php else : ?>
		<section class="content">
			<?php if(is_single()) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<?php the_content( __( 'Continue reading &rarr;', GKTPLNAME ) ); ?>
			<?php endif; ?>
			
			<?php gk_post_fields(); ?>
			<?php gk_post_links(); ?>
		</section>
		<?php endif; ?>

		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>