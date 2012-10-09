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
				<option value="normal"'.(($type == 'normal') ? ' selected="selected"' : '').'>'.__('Standard fonts', GKTPLNAME).'</option>
				<option value="squirrel"'.(($type == 'squirrel') ? ' selected="selected"' : '').'>'.__('Fonts Squirrel', GKTPLNAME).'</option>
				<option value="google"'.(($type == 'google') ? ' selected="selected"' : '').'>'.__('Google Web Fonts', GKTPLNAME).'</option>
				<option value="edgefonts"'.(($type == 'edgefonts') ? ' selected="selected"' : '').'>'.__('Adobe Edge Fonts', GKTPLNAME).'</option>
			</select></p>';
			// normal fonts selector
			$output .= '<p><label>'.__('Font family: ', GKTPLNAME).'</label><select id="'.($this->tpl->name).'_fonts_normal_'.($font_family->short_name).'" class="gkInput gkSelect" data-name="fonts_normal_'.($font_family->short_name).'" data-family="'.($font_family->short_name).'" data-type="normal"
			'.($this->required).' 
			'.($this->visibility).'>
				<option value="Verdana, Geneva, sans-serif"'.(($normal == "Verdana, Geneva, sans-serif") ? ' selected="selected"' : ''). '>Verdana</option>
				<option value="Georgia, Times New Roman, Times, serif"'.(($normal == "Georgia, Times New Roman, Times, serif") ? ' selected="selected"' : '').'>Georgia</option>
				<option value="Arial, Helvetica, sans-serif"'.(($normal == "Arial, Helvetica, sans-serif") ? ' selected="selected"' : '').'>Arial</option>
				<option value="Impact, Arial, Helvetica, sans-serif"'.(($normal == "Impact, Arial, Helvetica, sans-serif") ?  ' selected="selected"' : '' ).'>Impact</option>
				<option value="Tahoma, Geneva, sans-serif"'.(($normal == "Tahoma, Geneva, sans-serif") ? ' selected="selected"' : '').'>Tahoma</option>
				<option value="Trebuchet MS, Arial, Helvetica, sans-serif"'.(($normal == "Trebuchet MS, Arial, Helvetica, sans-serif") ? ' selected="selected"' : '').'>Trebuchet MS</option>
				<option value="Arial Black, Gadget, sans-serif"'.(($normal == "Arial Black, Gadget, sans-serif") ? ' selected="selected"' : ''). '>Arial Black</option>
				<option value="Times, Times New Roman, serif"'.(($normal == "Times, Times New Roman, serif") ? ' selected="selected"' : '').'>Times</option>
				<option value="Palatino Linotype, Book Antiqua, Palatino, serif"'.(($normal == "Palatino Linotype, Book Antiqua, Palatino, serif") ? ' selected="selected"' : '').'>Palatino Linotype</option>
				<option value="Lucida Sans Unicode, Lucida Grande, sans-serif"'.(($normal == "Lucida Sans Unicode, Lucida Grande, sans-serif") ? ' selected="selected"' : '').'>Lucida Sans Unicode</option>
				<option value="MS Serif, New York, serif"'.(($normal == "MS Serif, New York, serif") ? ' selected="selected"' : '').'>MS Serif</option>
				<option value="Comic Sans MS, cursive"'.(($normal == "Comic Sans MS, cursive") ? ' selected="selected"' : '').'>Comic Sans MS</option>
				<option value="Courier New, Courier, monospace"'.(($normal == "Courier New, Courier, monospace") ? ' selected="selected"' : '').'>Courier New</option>
				<option value="Lucida Console, Monaco, monospace"'.(($normal == "Lucida Console, Monaco, monospace") ? ' selected="selected"' : '').'>Lucida Console</option>
			</select></p>';
			// squirrel fonts selector
			$squirrel_fonts = (glob(TEMPLATEPATH . '/fonts/*' , GLOB_ONLYDIR));
			
			$output .= '<p><label>'.__('Fonts Squirrel: ', GKTPLNAME).'</label><select id="'.($this->tpl->name).'_fonts_squirrel_'.($font_family->short_name).'" class="gkInput gkSelect" data-name="fonts_squirrel_'.($font_family->short_name).'" data-family="'.($font_family->short_name).'" data-type="squirrel"
			'.($this->required).' 
			'.($this->visibility).'>';
				if(count($squirrel_fonts) > 0) { 
					for($i = 0; $i < count($squirrel_fonts); $i++) {
						$short_name = str_replace(TEMPLATEPATH . '/fonts/', '', $squirrel_fonts[$i]);
						$output .= '<option value="'.$short_name.'"'.(($squirrel == $short_name) ? ' selected="selected"' : '').'>'.$short_name.'</option>';
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