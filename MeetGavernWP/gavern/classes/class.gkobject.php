<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Object class
 *
 * Used to detect store simple datasets as object
 *
 **/

class GKObject {	
	/**
	 *
	 * Function used as an object setter
	 *
	 * @param property - the name of the property to set
	 * @param value - the default value of the property
	 *
	 * @return previous value of the property
	 *
	 **/
	
	public function get($property, $default = null) {
		return isset($this->$property) ? $this->$property : $default;
	}
	
	/**
	 *
	 * Function used as an object setter
	 *
	 * @param property - the name of the property to set
	 * @param value - the default value of the property
	 *
	 * @return previous value of the property
	 *
	 **/

	public function set($property, $value = null) {
		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $previous;
	}
}

// EOF