<?php 	
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	
	
/**
 *
 * Fonts field class
 *
 **/	
	
class GKFormInputFonts extends GKFormInput {
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
	 * Function used to generate input field output
     *
	 * @return HTML code of the field
	 *
	 **/
	
	public function output() {
		// load and parse XML file.
		$json_data = $this->tpl->get_json('config', 'fonts');
		//
		$output = '';
		// iterate through all menus in the file
		foreach ($json_data as $font_family) {
			// get the values
			$selectors = get_option($this->tpl->name . '_fonts_selectors_' . ($font_family->short_name), '');
			$type = get_option($this->tpl->name . '_fonts_type_' . ($font_family->short_name), 'normal');
			$normal = get_option($this->tpl->name . '_fonts_normal_' . ($font_family->short_name), '');
			$squirrel = get_option($this->tpl->name . '_fonts_squirrel_' . ($font_family->short_name), '');
			$google = get_option($this->tpl->name . '_fonts_google_' . ($font_family->short_name), '');
			$edgefonts = get_option($this->tpl->name . '_fonts_edgefonts_' . ($font_family->short_name), '');
			// generate the text block with description
			$output .= '<p class="gkTextBlock">'.($font_family->description).'</p>';
			// generate the label
			$output .= '<p><label>'.($font_family->full_name).'</label>';
			// generate the select lists - first main selector
			$output .= '<select id="'.($this->tpl->name).'_fonts_type_'.($font_family->short_name).'" class="gkInput gkSelect" data-name="fonts_type_'.($font_family->short_name).'" data-family="'.($font_family->short_name).'" data-type="type"
			'.($this->required).' 
			'.($this->visibility).'>
				<option value="normal"'.selected($type, 'normal', false).'>'.__('Standard fonts', GKTPLNAME).'</option>
				<option value="squirrel"'.selected($type, 'squirrel', false).'>'.__('Fonts Squirrel', GKTPLNAME).'</option>
				<option value="google"'.selected($type, 'google', false).'>'.__('Google Web Fonts', GKTPLNAME).'</option>
				<option value="edgefonts"'.selected($type, 'edgefonts', false).'>'.__('Adobe Edge Fonts', GKTPLNAME).'</option>
			</select></p>';
			// normal fonts selector
			$output .= '<p><label>'.__('Font family: ', GKTPLNAME).'</label><select id="'.($this->tpl->name).'_fonts_normal_'.($font_family->short_name).'" class="gkInput gkSelect" data-name="fonts_normal_'.($font_family->short_name).'" data-family="'.($font_family->short_name).'" data-type="normal"
			'.($this->required).' 
			'.($this->visibility).'>
				<option value="Verdana, Geneva, sans-serif"'.selected($normal, "Verdana, Geneva, sans-serif", false). '>Verdana</option>
				<option value="Georgia, Times New Roman, Times, serif"'.selected($normal, "Georgia, Times New Roman, Times, serif", false).'>Georgia</option>
				<option value="Arial, Helvetica, sans-serif"'.selected($normal, "Arial, Helvetica, sans-serif", false).'>Arial</option>
				<option value="Impact, Arial, Helvetica, sans-serif"'.selected($normal, "Impact, Arial, Helvetica, sans-serif", false).'>Impact</option>
				<option value="Tahoma, Geneva, sans-serif"'.selected($normal, "Tahoma, Geneva, sans-serif", false).'>Tahoma</option>
				<option value="Trebuchet MS, Arial, Helvetica, sans-serif"'.selected($normal, "Trebuchet MS, Arial, Helvetica, sans-serif", false).'>Trebuchet MS</option>
				<option value="Arial Black, Gadget, sans-serif"'.selected($normal, "Arial Black, Gadget, sans-serif", false). '>Arial Black</option>
				<option value="Times, Times New Roman, serif"'.selected($normal, "Times, Times New Roman, serif", false).'>Times</option>
				<option value="Palatino Linotype, Book Antiqua, Palatino, serif"'.selected($normal, "Palatino Linotype, Book Antiqua, Palatino, serif", false).'>Palatino Linotype</option>
				<option value="Lucida Sans Unicode, Lucida Grande, sans-serif"'.selected($normal, "Lucida Sans Unicode, Lucida Grande, sans-serif", false).'>Lucida Sans Unicode</option>
				<option value="MS Serif, New York, serif"'.selected($normal, "MS Serif, New York, serif", false).'>MS Serif</option>
				<option value="Comic Sans MS, cursive"'.selected($normal, "Comic Sans MS, cursive", false).'>Comic Sans MS</option>
				<option value="Courier New, Courier, monospace"'.selected($normal, "Courier New, Courier, monospace", false).'>Courier New</option>
				<option value="Lucida Console, Monaco, monospace"'.selected($normal, "Lucida Console, Monaco, monospace", false).'>Lucida Console</option>
			</select></p>';
			// squirrel fonts selector
			$squirrel_fonts = (glob(TEMPLATEPATH . '/fonts/*' , GLOB_ONLYDIR));
			$squirrel_fonts_len = count($squirrel_fonts);
			
			for($i = 0; $i < $squirrel_fonts_len; $i++) {
				
				if(
					substr($squirrel_fonts[$i], -7) === 'gk-font' || 
					substr($squirrel_fonts[$i], -12) === 'font-awesome' ||
					substr($squirrel_fonts[$i], -11) === 'woocommerce'
				) {
					$squirrel_fonts[$i] = false;
				}
			}
			
			$squirrel_fonts = array_filter($squirrel_fonts);
			$squirrel_fonts = array_values($squirrel_fonts);
			
			$output .= '<p><label>'.__('Fonts Squirrel: ', GKTPLNAME).'</label><select id="'.($this->tpl->name).'_fonts_squirrel_'.($font_family->short_name).'" class="gkInput gkSelect" data-name="fonts_squirrel_'.($font_family->short_name).'" data-family="'.($font_family->short_name).'" data-type="squirrel"
			'.($this->required).' 
			'.($this->visibility).'>';
				if(count($squirrel_fonts) > 0) { 
					for($i = 0; $i < count($squirrel_fonts); $i++) {
						$short_name = str_replace(TEMPLATEPATH . '/fonts/', '', $squirrel_fonts[$i]);
						$output .= '<option value="'.$short_name.'"'.selected($squirrel, $short_name, false).'>'.$short_name.'</option>';
					}
				} else {
					$output .= '<option value="-1" selected="selected">'.__('You have no fonts in fonts/ directory', GKTPLNAME).'</option>';
				}
			$output .= '</select></p>';
			// google fonts selector
			$output .= '<p>
			<label for="'.($this->tpl->name).'_fonts_google_'.($font_family->short_name).'">
				'.__('Google Web Fonts URL: ', GKTPLNAME).'
			</label>
			<input 
			id="'.($this->tpl->name).'_fonts_google_'.($font_family->short_name).'" 
			value="'.$google.'" 
			class="gkInput" 
			data-name="fonts_google_'.($font_family->short_name).'" 
			data-family="'.($font_family->short_name).'" 
			data-type="google" 
			'.($this->required).' 
			'.($this->visibility).'/></p>';
			// Adobe Edge Fonts selector
			$output .= '<p>
			<label for="'.($this->tpl->name).'_fonts_edgefonts_'.($font_family->short_name).'">
				'.__('Adobe Edge Fonts URL: ', GKTPLNAME).'
			</label>
			<input 
			id="'.($this->tpl->name).'_fonts_edgefonts_'.($font_family->short_name).'" 
			value="'.$edgefonts.'" 
			class="gkInput" 
			data-name="fonts_edgefonts_'.($font_family->short_name).'" 
			data-family="'.($font_family->short_name).'" 
			data-type="edgefonts" 
			'.($this->required).' 
			'.($this->visibility).'/></p>';
			// selectors
			$output .= '<p><label for="'.($this->tpl->name).'_fonts_selectors_'.($font_family->short_name).'">
				'.__('Selectors:', GKTPLNAME).'
				</label>
				<textarea 
				id="'.($this->tpl->name).'_fonts_selectors_'.($font_family->short_name).'" 
				class="gkInput" data-name="fonts_selectors_'.($font_family->short_name).'"
				'.($this->required).' 
				'.($this->visibility).'>'.str_replace("\\", "", $selectors).'</textarea>
			</p>';
		}
		
		return $output;
	}
}

// EOF