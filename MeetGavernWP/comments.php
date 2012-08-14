<?php

/**
 *
 * Comments part
 *
 **/

?>

<?php if ( post_password_required() ) : ?>
<section id="comments">
	<p class="no-password"><?php _e( 'This post is password protected. Enter the password to view any comments.', GKTPLNAME ); ?></p>
</section>
<?php
	return;/* Stop the rest of comments.php from being processed */	
	endif;
?>

<?php if ( have_comments() ) : ?>
<section id="comments">
	<h2>
		<?php
			printf( 
				_n( 
					'One thought on &ldquo;%2$s&rdquo;', 
					'%1$s thoughts on &ldquo;%2$s&rdquo;', 
					get_comments_number(), 
					GKTPLNAME
				),
				number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' 
			);
		?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option('page_comments' )) : ?>
	<nav>
		<div class="nav-prev">
			<?php previous_comments_link( __( '&larr; Older Comments', GKTPLNAME ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', GKTPLNAME ) ); ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<ol>
		<?php wp_list_comments(array( 'callback' => 'gavern_comment_template' )); ?>	
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option('page_comments' )) : ?>
	<nav>
		<div class="nav-prev">
			<?php previous_comments_link( __( '&larr; Older Comments', GKTPLNAME ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', GKTPLNAME ) ); ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<?php comment_form(); ?>
</section>
<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<section id="comments" class="nocomments">	
	<p class="no-comments"><?php _e( 'Comments are closed.', GKTPLNAME ); ?></p>
</section>
<?php else : ?>
<section id="comments" class="nocomments">
	<?php comment_form(); ?>
</section>
<?php endif; ?>