<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

// access to the template object
global $tpl;
// load the form parser
require_once(TEMPLATEPATH . '/gavern/form.parser.php');
// create a new instance of the form parser
$parser = new GavernWPFormParser($tpl);
// get the tabs list from the JSON file
$tabs = $tpl->get_json('options','tabs');
// iterators
$tabsIterator = 0;
$contentIterator = 0;
// active tab
$activeTab = 0;

if(isset($_COOKIE[GKTPLNAME . '_active_tab']) && is_numeric($_COOKIE[GKTPLNAME . '_active_tab'])) {
	$activeTab = floor($_COOKIE[GKTPLNAME . '_active_tab']);
}

$widget_settings = 'active';

if(get_option($tpl->name . '_widget_settings_loaded', 'N') == 'Y') {
	$widget_settings = 'inactive';
} else if(isset($_GET['task']) && $_GET['task'] == 'load_widgets') {
	$widget_settings = $tpl->loadWidgetSettings();
} else if(isset($_GET['task']) && $_GET['task'] == 'notload_widgets') {
	update_option($this->name . '_widget_settings_loaded', 'Y');
}

?>

<div class="gkWrap" id="gkMainWrap" data-theme="<?php echo GKTPLNAME; ?>">	
	<h1>
		<big><?php echo $tpl->full_name; ?></big><small><?php _e('Based on the Gavern WP framework', GKTPLNAME); ?></small>
	
		<a href="customize.php?theme=<?php echo $tpl->full_name; ?>" title="<?php _e('Customize theme', GKTPLNAME); ?>"><?php _e('Customize theme', GKTPLNAME); ?></a>
	</h1>
	
	<?php if($widget_settings == 'active' || $widget_settings == 'loaded') : ?>
	<div id="gk-message">
		<?php if($widget_settings == 'active') : ?>
		<h2><?php _e('Do you want to load demo widget settings?', GKTPLNAME); ?> <button id="gk-cancel-settings"><?php _e('Close', GKTPLNAME); ?></button> <button id="gk-load-settings"><?php _e('Load demo settings', GKTPLNAME); ?></button></h2>
		<?php endif; ?>
		
		<?php if($widget_settings == 'loaded') : ?>
		<h2 class="center"><?php _e('Demo widget settings has been loaded successfully', GKTPLNAME); ?></h2>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	
	<div>
		<ul id="gkTabs">
		<?php foreach($tabs as $tab) : ?>
			<?php if($tab[2] == 'enabled') : ?>
			<li<?php echo ($tabsIterator == $activeTab) ? ' class="'.str_replace(' ', '', strtolower($tab[0])).' active"' : ' class="'.str_replace(' ', '', strtolower($tab[0])).'"'; ?> title="<?php echo $tab[0]; ?>"><?php echo $tab[0]; ?></li>
			<?php 
				$tabsIterator++;
				endif; 
			?>
		<?php endforeach; ?>
		</ul>
		
		<div id="gkTabsContent">
		<?php foreach($tabs as $tab) : ?>
			<?php if($tab[2] == 'enabled') : ?>
			<div<?php if($contentIterator == $activeTab) echo ' class="active"'; ?>>
				<?php echo $parser->generateForm($tab[1]); ?>
				
				<div class="gkSaveSettings">
					<img src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif" class="gkAjaxLoading" alt="Loading">
					<button class="button-primary gkSave" data-loading="<?php _e('Saving&hellip;', GKTPLNAME); ?>" data-loaded="<?php _e('Save settings', GKTPLNAME); ?>" data-wrong="<?php _e('Please check the form!', GKTPLNAME); ?>"><?php _e('Save settings', GKTPLNAME); ?></button>
				</div>
			</div>
			<?php 
				$contentIterator++;
				endif; 
			?>
		<?php endforeach; ?>
		</div>
	</div>
</div>