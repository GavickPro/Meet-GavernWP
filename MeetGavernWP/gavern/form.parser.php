<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Forms parser class
 *
 **/

include_once(gavern_file('gavern/form_elements/standard.php'));

/**
 *
 * Class used to parse the JSON files with the forms structure
 *
 **/

class GavernWPFormParser {
	// template object
	private $tpl;
	// loaded JSON data
	private $loaded_data;
	// generated elements
	private $elements;
	
	/**
	 *
	 * Class constructor
	 *
	 * @param tpl - template object
	 *
	 **/
	function __construct($tpl) {
		// get the Template main object handler
		$this->tpl = $tpl;
	} 
	
	/**
	 *
	 * Function to generate the form
	 *
	 * @param filename - the name of the JSON file to parse
	 * @param string - is the input a string or file?
	 *
	 * @return output from the output() method - HTML output  
	 *
	 **/
	public function generateForm($filename, $string = false) {
		if(!$string) {
			// load file
			$this->loaded_data = $this->tpl->get_json('options', $filename);
			// check the loaded JSON data
			if($this->loaded_data != null && count($this->loaded_data != 0)) {
				// generate output
				return $this->output();
			} else {
				// if there is no JSON data
				return '';
			}
		} else {
			// load file
			$this->loaded_data = json_decode($filename);
			// check the loaded JSON data
			if($this->loaded_data != null && count($this->loaded_data != 0)) {
				// generate output
				return $this->output();
			} else {
				// if there is no JSON data
				return '';
			}
		}
	}
	
	/**
	 *
	 * Function used to parse the JSON data
	 * 
	 * @retunr HTML output
	 *
	 **/
	private function output() {
		// prepare empty string for the output
		$prepared_data = '';
		$standard_fields = array('Text', 'RawText', 'Select', 'Switcher', 'Textarea', 'Media', 'WidthHeight', 'TextBlock');
		// parse groups
		foreach($this->loaded_data as $group) {
			// 
			$prepared_data .= '<fieldset><legend>'.($group->groupname).'</legend>'; 
			$prepared_data .= '<p><small>'.($group->groupdesc).'</small></p>';
			
			foreach($group->fields as $field) {
				if(in_array($field->type, $standard_fields)) {
					$className = 'GKFormInput' . $field->type;
					$output = new $className(
						$this->tpl, 
						isset($field->name) ? $field->name : null, 
						isset($field->label) ? $field->label : null, 
						isset($field->tooltip) ? $field->tooltip : null, 
						isset($field->default) ? $field->default : null, 
						isset($field->class) ? $field->class : null, 
						isset($field->format) ? $field->format : null, 
						isset($field->required) ? $field->required : null, 
						isset($field->visibility) ? $field->visibility : null, 
						isset($field->other) ? $field->other : null
					);
					$prepared_data .= $output->output();
				} else {
					// load field config
					$file_config = $this->tpl->get_json('form_elements/'.$field->type, 'config', false);
					// check if the file is correct
					if((is_array($file_config) && count($file_config) > 0) || is_object($file_config)) {
						// load these files only once time
						if(!class_exists($file_config->class)) {
							// load the main PHP class
							include_once(gavern_file('gavern/form_elements/').($field->type).'/'.($file_config->php));
						}
						// create the object
						if(class_exists($file_config->class)) {
							$className = $file_config->class;
							$output = new $className(
								$this->tpl, 
								isset($field->name) ? $field->name : null, 
								isset($field->label) ? $field->label : null, 
								isset($field->tooltip) ? $field->tooltip : null, 
								isset($field->default) ? $field->default : null, 
								isset($field->class) ? $field->class : null, 
								isset($field->format) ? $field->format : null, 
								isset($field->required) ? $field->required : null, 
								isset($field->visibility) ? $field->visibility : null, 
								isset($field->other) ? $field->other : null
							);
							$prepared_data .= $output->output();
						}
					} else {
						array_push($this->tpl->problems, 'JSON ERROR: config file for the element '.($field->type).' doesn\'t exist or is incorrect');
					}
				}
			}
			
			$prepared_data .= '</fieldset>';
		}
		// return the created output
		return $prepared_data;
	}
}
 
// EOF