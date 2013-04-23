<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Base class for all the back-end fields
 *
 **/

class GKFormInput {
	// access to the template object
	protected $tpl;
	// name of the field (without the template name prefix)
	protected $name;
	// label of the field
	protected $label;
	// tooltip for the field
	protected $tooltip;
	// default value of the field
	protected $value;
	// class attribute for the field
	protected $class;
	// format for validation as regular expression
	protected $format;
	// flag to mark the field as required field
	protected $required;
	// visibility of the field
	protected $visibility;
	
	/**
	 *
	 * Constructor
	 *
	 * @param tpl - handler for the template object
	 * @param name - to fill the name class field
	 * @param label - to fill the label class field
	 * @param tooltip - to fill the tooltip class field
	 * @param default - to fill the value class field
	 * @param class - to fill the class class field
	 * @param format - to fill the format class field
	 * @param required - to fill the required class field
	 * @param visibility - to fill the visibility class field
	 * @param other - additional arguments for the constructor
	 *
	 * @return null
	 *
	 **/
	function __construct($tpl, $name, $label, $tooltip = '', $default = '', $class = '', $format = '', $required = false, $visibility = '', $other = null) {
		// get the Template main object handler
		$this->tpl = $tpl;
		// name for the field used in the storage
		$this->name = $name;
		// label for the input
		$this->label = $label;
		// tooltip content for the label
		$this->tooltip = $tooltip;
		// read the value
		$this->getValue($default);
		// check if it is necessarry to generate the class attribute
		$this->class = $class;
		//
		$this->format = ' data-format="'.$format.'"';
		$this->required = ' data-required="'.$required.'"';
		$this->visibility = ' data-visibility="'.$visibility.'"';
		$this->other = $other;
	} 
	
	/**
	 *
	 * Function to get the field value - it is usually overrided in the more complex fields
	 *
	 * @param default - default value of the field
	 *
	 * @return null
	 *
	 **/
	
	public function getValue($default) {
		// get the option value from database or if it doesn't exist get the default value
		$this->value = get_option($this->tpl->name . "_" . $this->name, $default);
	}
}

/**
 *
 *
 * Standard elements used in the panel
 *
 *
 **/

/**
 *
 * Text block - used as a description
 *
 **/

class GKFormInputTextBlock extends GKFormInput {
	public function output() {
		$output = '<p class="gkTextBlock '.($this->class).'">'.($this->label).'</p>';
		
		return $output;
	}
}

/**
 *
 * Text field - basic input field
 *
 **/

class GKFormInputText extends GKFormInput {
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<input 
					type="text" 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="gkInput gkText '.($this->class).'"
					value="'.($this->value).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				/></p>';
	}
}

/**
 *
 * Raw Text field - basic input field extended with support for removing slashes before apostrophes
 *
 **/

class GKFormInputRawText extends GKFormInput {
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<input 
					type="text" 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="gkInput gkText '.($this->class).'"
					value="'.str_replace('\&#039;', "'", $this->value).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				/></p>';
	}
}

/**
 *
 * Textarea
 *
 **/

class GKFormInputTextarea extends GKFormInput {	
	public function output() {
		return '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'"
				>'.$this->label.'</label>
				<textarea 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="gkInput gkTextarea '.($this->class).'"
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).' 
					data-name="'.($this->name).'"
				>'.(str_replace("\\", "", $this->value)).'</textarea></p>';
	}
}

/**
 *
 * Select - the dropdown list
 *
 **/

class GKFormInputSelect extends GKFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="gkInput gkSelect '.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
				
		foreach($this->other->options as $value => $label) {		
			$output .= '<option value="'.$value.'"'.selected($this->value, $value, false).'>'.$label.'</option>';
		}
		
		$output .= '</select></p>';
		
		return $output;
	}
}

/**
 *
 * Switcher - the Select with only two states - enabled/disabled
 *
 **/

class GKFormInputSwitcher extends GKFormInput {
	public function output() {
		$output = '<p data-visible="true"><label 
					for="'.($this->tpl->name).'_'.($this->name).'" 
					title="'.($this->tooltip).'" 
				>'.$this->label.'</label>
				<select 
					id="'.($this->tpl->name).'_'.($this->name).'" 
					name="'.($this->tpl->name).'_'.($this->name).'" 
					class="gkInput gkSwitcher '.($this->class).'" 
					'.($this->format).' 
					'.($this->required).' 
					'.($this->visibility).'
					data-name="'.($this->name).'"
				>';
		$output .= '<option value="N"'.selected($this->value, 'N', false).'>'.__('Disabled', GKTPLNAME).'</option>';
		$output .= '<option value="Y"'.selected($this->value, 'Y', false).'>'.__('Enabled', GKTPLNAME).'</option>';
		$output .= '</select></p>';
		
		return $output;
	}
}

/**
 *
 * Media - field to select an image
 *
 **/

class GKFormInputMedia extends GKFormInput {
	public function output() {
		
		$output = '<p data-visible="true">
			<label 
			for="'.($this->tpl->name).'_'.($this->name).'"
			title="'.($this->tooltip).'"
			>
				'.$this->label.'
			</label>
			<input 
				id="'.($this->tpl->name).'_'.($this->name).'" 
				type="text" 
				size="36" 
				name="'.($this->tpl->name).'_'.($this->name).'" 
				value="'.($this->value).'" 
 				class="gkInput gkMediaInput" 				
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
				data-name="'.($this->name).'"
			/>
			<input id="'.($this->tpl->name).'_'.($this->name).'_button" class="gkMedia" type="button" value="'.__('Upload Image', GKTPLNAME).'" />
			<small>'.__('Enter an URL or upload an image.', GKTPLNAME).'</small>
			<span class="gkMediaPreview" data-text="'.__('No image selected', GKTPLNAME).'">'.(($this->value != '') ? '<img src="'.$this->value.'" alt="Preview" />' : __('No image selected', GKTPLNAME)).'</span>
			</p>
		';
		
		return $output;
	}
}

/**
 *
 * Width/Height - used to specify size of the rectangle area (i.e. for specify image dimensions)
 *
 **/

class GKFormInputWidthHeight extends GKFormInput {
	public function getValue($default) {
		// get the option value from database or if it doesn't exist get the default value
		$this->value = array(
			"height" => get_option($this->tpl->name . "_" . str_replace('_width', '', $this->name), $default),
			"width" => get_option($this->tpl->name . "_" . str_replace('_height', '', $this->name), $default)
		);
	} 
	
	public function output() {	
		$output = '<p data-visible="true"><label 
			for="'.($this->tpl->name).'_'.($this->name).'"
			title="'.($this->tooltip).'"
			>
				'.$this->label.'
			</label>
			
			<input 
				type="text" 
				size="'.($this->other->size).'" 
				class="gkInput gkWidthHeight"
				id="'.($this->tpl->name . "_" . str_replace('_height', '', $this->name)).'" 
				name="'.($this->tpl->name . "_" . str_replace('_height', '', $this->name)).'" 
				value="'.($this->value['width']).'" 
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
			/>
			 &times; 
			<input 
				type="text" 
				class="gkInput gkWidthHeight"
				size="'.($this->other->size).'" 
				id="'.($this->tpl->name . "_" . str_replace('_width', '', $this->name)).'" 
				name="'.($this->tpl->name . "_" . str_replace('_width', '', $this->name)).'" 
				value="'.($this->value['height']).'" 
				'.($this->format).' 
				'.($this->required).' 
				'.($this->visibility).'
			/> '.($this->other->unit).'</p>
		';
		
		return $output;
	}
}

// EOF