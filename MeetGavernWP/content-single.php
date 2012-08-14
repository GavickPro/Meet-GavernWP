<?php

/**
 *
 * The template for displaying content in the single.php template
 *
 **/
 
global $tpl;
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<?php include('layouts/content.post.header.php'); ?>
	</header>

	<?php include('layouts/content.post.featured.php'); ?>

	<section class="content">
		<?php the_content(); ?>
		<?php gk_post_links(); ?>
	</section>

	<?php include('layouts/content.post.footer.php'); ?>
</article>
