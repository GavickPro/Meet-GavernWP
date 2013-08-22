<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

// access to the template object
global $tpl;
// load the form parser
include_once(TEMPLATEPATH . '/gavern/form.parser.php');
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

?>

<div class="gkWrap" id="gkMainWrap" data-theme="<?php echo GKTPLNAME; ?>">	
	<h1>
		<big><?php echo $tpl->full_name; ?></big><small><?php _e('Based on the Gavern WP framework', GKTPLNAME); ?><span><?php echo __('Version: ', GKTPLNAME) . '<strong>' . $tpl->version . '</strong>'; ?></span></small>
	
		<a href="customize.php?theme=<?php echo $tpl->full_name; ?>" title="<?php _e('Customize theme', GKTPLNAME); ?>"><?php _e('Customize theme', GKTPLNAME); ?></a>
	
		<div id="gkSocial">
			<span><?php _e('Follow us on the social media: ', GKTPLNAME); ?></span> 
			
			<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fgavickpro&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;font=arial&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:122px; height:20px;" allowTransparency="true"></iframe> 
			
			<a href="https://twitter.com/gavickpro" class="twitter-follow-button" data-show-count="false">Follow @Dziudek</a>
			
		    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
	</h1>
	
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