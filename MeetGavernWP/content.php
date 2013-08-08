<?php

/**
 *
 * The default template for displaying content
 *
 **/

global $tpl; 

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
		
		<?php get_template_part( 'layouts/content.post.featured' ); ?>
		
		<?php if ( is_search() || is_archive() || is_tag() ) : ?>
		<section class="summary">
			<?php the_excerpt(); ?>
		</section>
		<?php else : ?>
		<section class="content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', GKTPLNAME ) ); ?>
			
			<?php gk_post_fields(); ?>
			<?php gk_post_links(); ?>
		</section>
		<?php endif; ?>
		
		<?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>