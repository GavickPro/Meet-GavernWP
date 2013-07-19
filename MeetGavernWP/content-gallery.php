<?php

/**
 *
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 **/

global $tpl; 

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="gallery">
		<?php get_template_part( 'layouts/content.post.header' ); ?>
	</header>

	<?php get_template_part( 'layouts/content.post.featured' ); ?>

	<?php if ( is_search() || is_archive() || is_tag() ) : ?>
	<section class="summary">
		<?php the_excerpt(); ?>
	</section>
	<?php else : ?>
		<section class="content">
			<?php if ( post_password_required() ) : ?>
				<?php if(is_single()) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<?php the_content( __( 'Continue reading &rarr;', GKTPLNAME ) ); ?>
				<?php endif; ?>
			<?php else : ?>
				<?php
					$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>

				<figure class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</figure>

				<p>
					<em>
						<?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, GKTPLNAME ),
						'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', GKTPLNAME ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?>
					</em>
				</p>
			<?php endif; ?>
			
			<?php if(is_single()) : ?>
				<?php the_content(); ?>
			<?php else : ?>
				<?php the_content( __( 'Continue reading &rarr;', GKTPLNAME ) ); ?>
			<?php endif; ?>
			
		<?php endif; ?>
		
		<?php gk_post_fields(); ?>
		<?php gk_post_links(); ?>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'layouts/content.post.footer' ); ?>
</article>
