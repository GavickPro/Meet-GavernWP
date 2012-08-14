<?php
/**
 *
 * The template for displaying search form
 *
 **/
 
global $tpl;
 
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s"><?php _e( 'Search', GKTPLNAME ); ?></label>
	<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', GKTPLNAME ); ?>" value="<?php echo wp_kses(get_search_query(), null); ?>" />
	
	<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', GKTPLNAME ); ?>" />
</form>