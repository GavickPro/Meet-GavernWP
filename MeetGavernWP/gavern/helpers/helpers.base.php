<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Helper functions
 *
 * Group of additional helper functions used in the framework
 *
 **/
 
if(!function_exists('str_replace_once')) { 
 	/**
 	 *
 	 * Function used to replace one occurence
 	 *
 	 * @param needle - string to replace
 	 * @param replace - string used to replacement
 	 * @param haystack - string where all will be replaced
 	 *
 	 * @return result of the replacement
 	 *
 	 **/
	function str_replace_once($needle , $replace , $haystack){
	    // Looks for the first occurence of $needle in $haystack
	    // and replaces it with $replace.
	    $pos = strpos($haystack, $needle);
	    if ($pos === false) {
	        // Nothing found
	    	return $haystack;
	    }
	    //
	    return substr_replace($haystack, $replace, $pos, strlen($needle));
	}  
	
}

if(!function_exists('get_current_page_url')) {
	/**
	 *
	 * Function used to get the current page URL
	 *
	 * @return current page URL
	 *
	 **/
	function get_current_page_url() {
		// start with the HTTP
		$pageURL = 'http';
		// check for the HTTPS
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		// add rest of the protocol string 
		$pageURL .= "://";
		// check the server port to specify the URL structure - with the port or without
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		// return the result URL
		return preg_replace('@%[0-9A-Fa-f]{1,2}@mi', '', htmlspecialchars($pageURL, ENT_QUOTES, 'UTF-8'));
	}

}

// EOF