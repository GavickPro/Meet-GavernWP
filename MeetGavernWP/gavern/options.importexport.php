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
	wp_register_style('gk-import-export-css', get_template_directory_uri().'/css/back-end/importexport.css');
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
			<h2>Import Template Settings</h2>
			<?php
				if (isset($_FILES['import']) && check_admin_referer('gavern_importexport')) {
					if ($_FILES['import']['error'] > 0) 
						echo "<div class='error'><p>No file selected, please make sure to select a file.</p></div>";	
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
							echo "<div class='updated'><p>All template options are restored successfully.</p></div>";
						}	
						else 
							echo "<div class='error'><p>Invalid file or file size too big.</p></div>";
					}
				}
		?>
			<p>1. Click "Browse" button and choose a backup file that you backup before.</p>
			<p>2. Click "Restore Template Settings" button to restore your template settings.</p>
			<form method='post' enctype='multipart/form-data'>
				<p class="submit">
					<?php wp_nonce_field('gavern_importexport'); ?>
					<input type='file' name='import' />
					<input type='submit' name='submit' class="gkMedia" value='Restore Template Settings'/>
				</p>
			</form>
		</div>
<?php
	// Export Starts Here
	if (!isset($_POST['export'])) { 
?>
		<div class="gkExport">
	        <h2>Export Template Settings</h2>
	        <p>When you click "Backup Template Settings" button, system will generate a template backup file for you to save on your computer.</p>
	        <p>This backup file contains your Gavern template configuration and setting options.</p>
	        <p>After exporting, you can either use the backup file to restore your template settings on this site again or another Wordpress site when using same Gavern template.</p>
            <form method='post'>
	        <p class="submit">
            	<?php wp_nonce_field('gavern_importexport'); ?>
	        	<input type='submit' name='export' class="gkMedia" value='Backup Template Settings'/>
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