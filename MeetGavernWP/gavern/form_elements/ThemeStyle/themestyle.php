<?php 	
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');		
	
/**
 *
 * Class of the theme style field
 *
 **/
	
class GKFormInputThemeStyle extends GKFormInput {
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
	 * Function used to create the field output
	 *
	 * @return HTML output of the field
	 *
	 **/
	
	public function output() {
		// load and parse XML file.
		$json_data = $this->tpl->get_json('config', 'styles');
		//
		$output = '<p data-visible="true">';
		// iterate through all menus in the file
		foreach ($json_data as $styles) {
			// get option value
			$theme_style = get_option($this->tpl->name . '_'.($this->name).'_'.($styles->family), '');
			// output the label
			$output .= '<label 
						for="'.($this->tpl->name).'_'.($this->name).'_'.($styles->family).'" 
						title="'.($styles->family_tooltip).'"
					>'.$styles->family_desc.'</label>';
			// output the select
			$output .= '<select 
							id="'.($this->tpl->name).'_'.($this->name).'_'.($styles->family).'" 
							name="'.($this->tpl->name).'_'.($this->name).'_'.($styles->family).'" 
							class="gkInput gkSelect"
							'.($this->required).' 
							'.($this->visibility).' 
							data-name="'.($this->name).'_'.($styles->family).'"
			>';
			// iterator
			$i = 0;
			// iterate through styles
			foreach($styles->styles as $style) {
				$output .= '<option value="'.(string)$style->value.'"'.(($theme_style == (string)$style->value || ($i == 0 && $theme_style == '')) ? ' selected="selected"' : '').'>'. (string)$style->name .'</option>';
				// increment the iterator
				$i++;
			}
			// close the select tag
			$output .= '</select>';
		}
		//
		$output .= '</p>';
		
		return $output;
	}
}		
	
// EOF