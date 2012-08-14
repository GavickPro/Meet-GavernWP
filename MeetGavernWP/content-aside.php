<?php

/**
 *
 * The template for displaying posts in the Aside Post Format on index and archive pages
 *
 **/
 
 global $tpl;
 
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="aside">
			<?php include('layouts/content.post.header.php'); ?>
		</header>

		<?php include('layouts/content.post.featured.php'); ?>

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
			
			<?php gk_post_links(); ?>
		</section>
		<?php endif; ?>

		<?php include('layouts/content.post.footer.php'); ?>
	</article>
