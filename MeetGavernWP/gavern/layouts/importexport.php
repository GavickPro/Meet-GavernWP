<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

// access to the template object
global $tpl;
// get settings output
$importexport_export = print_r(gavern_get_option_backup($tpl->name . '_'), true);

?>

<div class="gkWrap wrap">	
	<h2><?php _e('Import / Export theme settings', GKTPLNAME); ?></h2>
	
	<dl>
		<dt>
			<h3><?php _e('Export', GKTPLNAME); ?></h2>
		</dt>
		<dd>
			<textarea id="importexport_export" name="importexport_export"><?php echo $importexport_export; ?></textarea>
		</dd>
		
		<dt>
			<h3><?php _e('Import (used if the textarea was filled)', GKTPLNAME); ?></h3>
		</dt>
		<dd>
			<textarea id="importexport_import" name="importexport_import"></textarea>
		</dd>
	</dl>
	
	<div class="gksave">
		<p>    
		    <div class="gkSaveSettings">
		    	<img src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif" class="gkAjaxLoading" alt="Loading">
		    	<button class="button-primary gkSave" id="importexport_save" data-loading="<?php _e('Saving&hellip;', GKTPLNAME); ?>" data-loaded="<?php _e('Save settings', GKTPLNAME); ?>" data-wrong="<?php _e('Please check the form!', GKTPLNAME); ?>"><?php _e('Save settings', GKTPLNAME); ?></button>
		    </div>
		</p>
	</div>
</div>