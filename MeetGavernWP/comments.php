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
	<?php do_action('gavernwp_before_comments_count'); ?>
	<h2>
		<?php if(get_comments_number() == 1) : ?>
		<?php printf(__( 'One thought on &ldquo;%1$s&rdquo;', GKTPLNAME), '<span>' . get_the_title() . '</span>'); ?>
		<?php elseif(get_comments_number() == 2) : ?>
		<?php printf(__( 'Two thoughts on &ldquo;%1$s&rdquo;', GKTPLNAME), '<span>' . get_the_title() . '</span>'); ?>
		<?php elseif(get_comments_number() > 2) : ?>
		<?php printf(__( '%1$s thoughts on &ldquo;%2$s&rdquo;', GKTPLNAME), number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>'); ?>
		<?php endif; ?>
	</h2>
	<?php do_action('gavernwp_after_comments_count'); ?>

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
	
	<?php do_action('gavernwp_before_comments_list'); ?>
	<ol>
		<?php wp_list_comments(array( 'callback' => 'gavern_comment_template', 'style' => 'ol')); ?>	
	</ol>
	<?php do_action('gavernwp_after_comments_list'); ?>

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
	<?php do_action('gavernwp_before_comments_form'); ?>
	<?php comment_form(); ?>
	<?php do_action('gavernwp_after_comments_form'); ?>
</section>
<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<?php if( get_comment_pages_count() > 0) : ?>
	<section id="comments" class="nocomments">	
		<p class="no-comments"><?php _e( 'Comments are closed.', GKTPLNAME ); ?></p>
	</section>
	<?php endif; ?>
<?php else : ?>
<section id="comments" class="nocomments">
	<?php do_action('gavernwp_before_comments_form'); ?>
	<?php comment_form(); ?>
	<?php do_action('gavernwp_after_comments_form'); ?>
</section>
<?php endif; ?>