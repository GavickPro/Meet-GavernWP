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
			<?php printf( __( 'Author Archives: %s', GKTPLNAME ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
		</h1>
	
		<?php rewind_posts(); ?>
	
		<?php if ( get_the_author_meta( 'description' ) ) : ?>
		<section class="author-info">
			<aside class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 48 ); ?>
			</aside>
			<div class="author-desc">
				<h2><?php printf( __( 'About %s', GKTPLNAME ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
			</div>
		</section>
		<?php endif; ?>
	
		<?php gk_content_nav(); ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
		
		<?php gk_content_nav(); ?>
	
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