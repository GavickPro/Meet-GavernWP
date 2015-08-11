<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Function to create import/export options page
 *
 * @return null
 *
 **/
	
ob_start();	
function gavern_importexport_options() {
	// getting access to the template and database global object. 
	global $tpl;
	global $wpdb;
	wp_register_style('gk-import-export-css', gavern_file_uri('css/back-end/importexport.css'));
	wp_enqueue_style('gk-import-export-css');

	// check permissions
	if (!current_user_can('manage_options')) {  
	    wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME));  
	}
	// Import Starts Here
?>
	<div class="gkWrap wrap">
		<h1><big><?php echo $tpl->full_name; ?></big><small><?php _e('Based on the Gavern WP framework', GKTPLNAME); ?></small></h1>
		<div class="gkImport">
			<h2><?php _e('Import Template Settings', GKTPLNAME)?></h2>
			<?php
				if (isset($_FILES['import']) && check_admin_referer('gavern_importexport')) {
					if ($_FILES['import']['error'] > 0) 
						echo "<div class='error'><p><?php _e('No file selected, please make sure to select a file.', GKTPLNAME)?></p></div>";	
					else {
						$file_name = $_FILES['import']['name'];
						$file_ext = strtolower(end(explode(".", $file_name)));
						$file_size = $_FILES['import']['size'];
						if (($file_ext == "json") && ($file_size < 10000)) {
							$encode_options = file_get_contents($_FILES['import']['tmp_name']);
							$options = json_decode($encode_options, true);
							if(is_array($options) && count($options) > 0) {
								foreach($options as $key => $value) {
									update_option($key, esc_attr($value));
								}
							}
							echo "<div class='updated'><p>" .__('All template options are restored successfully.', GKTPLNAME) . "</p></div>";
						}	
						else 
							echo "<div class='error'><p>" .__('Invalid file or file size too big.', GKTPLNAME)."</p></div>";
					}
				}
		?>
			<p><?php _e('1. Click "Browse" button and choose a backup file that you backup before.', GKTPLNAME)?></p>
			<p><?php _e('2. Click "Restore Template Settings" button to restore your template settings.', GKTPLNAME)?></p>
			<form method='post' enctype='multipart/form-data'>
				<p class="submit">
					<?php wp_nonce_field('gavern_importexport'); ?>
					<input type='file' name='import' />
					<input type='submit' name='submit' class="gkMedia" value='<?php _e('Restore Template Settings', GKTPLNAME)?>'/>
				</p>
			</form>
		</div>
<?php
	// Export Starts Here
	if (!isset($_POST['export'])) { 
?>
		<div class="gkExport">
	        <h2><?php _e('Export Template Settings', GKTPLNAME)?></h2>
	        <p><?php _e('When you click "Backup Template Settings" button, system will generate a template backup file for you to save on your computer.', GKTPLNAME)?></p>
	        <p><?php _e('This backup file contains your Gavern template configuration and setting options.', GKTPLNAME)?></p>
	        <p><?php _e('After exporting, you can either use the backup file to restore your template settings on this site again or another Wordpress site when using same Gavern template.', GKTPLNAME)?></p>
            <form method='post'>
	        <p class="submit">
            	<?php wp_nonce_field('gavern_importexport'); ?>
	        	<input type='submit' name='export' class="gkMedia" value='<?php _e('Backup Template Settings', GKTPLNAME)?>'/>
	        </p>
            </form>
	    </div>
<?php 
  	} elseif (check_admin_referer('gavern_importexport')) {
		$option_prefix = $tpl->name;
		$blogname = str_replace(" ", "", get_option('blogname'));
		$date = date("d_m_Y_H_i_s");
		$json_name = $blogname."_".$tpl->name."_".$date; // Generating filename.
		
		// get all rows with options containing specific prefix
		$options = $wpdb->get_results(  
				'SELECT
					option_value,
					option_name
				FROM 
				'.$wpdb->options.'
				WHERE 
					option_name LIKE \''.$option_prefix.'%\';' 
		);
		$value = array();
		if ($options) {
			foreach ($options as $key) {
				if(
					$key->option_name != $tpl->name . '_widget_responsive' &&
					$key->option_name != $tpl->name . '_widget_rules' &&
					$key->option_name != $tpl->name . '_widget_rules_type' &&
					$key->option_name != $tpl->name . '_widget_style' && 
					$key->option_name != $tpl->name . '_widget_style_css' && 
					$key->option_name != $tpl->name . '_widget_users'
				) {
					$value[$key->option_name] = $key->option_value;
				}
			}
		}
		$json_file = json_encode($value); // Encode data into json data
		
		ob_clean();
		echo $json_file;
		header("Content-Type: text/json; charset=" . get_option( 'blog_charset'));
		header("Content-Disposition: attachment; filename=$json_name.json");
		exit();
	}
	?>
	</div>
<?php 
	// Export Ends Here
}
// EOF