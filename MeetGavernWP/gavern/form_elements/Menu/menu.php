<?php 	
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Class of the menu field
 *
 **/
	
class GKFormInputMenu extends GKFormInput {
	/**
	 *
	 * Function used to override the getValue function
	 *
	 * @param default - default value - not used here
	 *
	 * @return null
	 *
	 **/
	
	public function getValue($default) {
		$this->value = '';
	}
	
	/**
	 *
	 * Function used to generate output of the field
	 *
	 * @return HTML output of the field
	 *
	 **/
	
	public function output() {
		// load and parse XML file.
		$json_data = $this->tpl->get_json('config', 'menus');
		//
		$output = '';
		// prepare parser object
		$parser = new GavernWPFormParser($this->tpl);
		// iterate through all menus in the file
		foreach ($json_data as $menu) {			
			$temp_json = '[
				{
					"groupname": "'.($menu->name).'",
					"groupdesc": "'.($menu->description).'",
					"fields": [
						{
							"name": "navigation_menu_state_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Enable', GKTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can enable or disable showing the menu in the template.', GKTPLNAME).'",
							"default": "Y",
							"other": {
								"options": {
									"Y": "'.__('Enabled', GKTPLNAME).'",
									"N": "'.__('Disabled', GKTPLNAME).'",
									"rule": "'.__('Conditional rule', GKTPLNAME).'"
								}
							}
						},
						{
							"name": "navigation_menu_staterule_'.($menu->location).'",
							"type": "Text",
							"label": "'.__('Conditional rule', GKTPLNAME).'",
							"tooltip": "'.__('You can enable showing the menu in the specific pages.', GKTPLNAME).'",
							"default": "",
							"class": "",
							"visibility": "navigation_menu_state_'.($menu->location).'=rule"
						},
						{
							"name": "navigation_menu_depth_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Depth of ', GKTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can specify the menu depth.', GKTPLNAME).'",
							"default": "0",
							"other": {
								"options": {
									"0": "'.__('All levels', GKTPLNAME).'",
									"1": "1",
									"2": "2",
									"3": "3",
									"4": "4",
									"5": "5"
								}
							}
						}
						'.(($menu->main == 'true') ? ',
						{
							"name": "navigation_menu_animation_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Animation for ', GKTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can specify the menu animation.', GKTPLNAME).'",
							"default": "width_height_opacity",
							"other": {
								"options": {
									"width_height_opacity": "'.__('Width, Height and Opacity', GKTPLNAME).'",
									"width_opacity": "'.__('Width and Opacity', GKTPLNAME).'",
									"height_opacity": "'.__('Height and Opacity', GKTPLNAME).'",
									"opacity": "'.__('Opacity', GKTPLNAME).'",
									"none": "'.__('No animation', GKTPLNAME).'"
								}
							}
						},
						{
							"name": "navigation_menu_animationspeed_'.($menu->location).'",
							"type": "Select",
							"label": "'.__('Animation speed for ', GKTPLNAME).' '.($menu->name).'",
							"tooltip": "'.__('You can specify the speed of the menu animation.', GKTPLNAME).'",
							"default": "normal",
							"other": {
								"options": {
									"fast": "'.__('Fast animation (250ms)', GKTPLNAME).'",
									"normal": "'.__('Normal animation (500ms)', GKTPLNAME).'",
									"slow": "'.__('Slow animation (1000ms)', GKTPLNAME).'"
								}
							}
						}': ''). '
					]
				}
			]';	
			// parse the generated JSON
			$output .= $parser->generateForm($temp_json, true);
		}
		
		return $output;
	}
}

// EOF