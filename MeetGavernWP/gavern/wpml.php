<?php

add_filter('gavern-get-json', 'gavern_wpml_get_json', 10, 2);

function gavern_wpml_get_json($dir, $lang) {
    global $wpdb;
        if ($dir == 'options') {
            $code = $wpdb->get_var("SELECT default_locale FROM {$wpdb->prefix}icl_languages WHERE code='" . ICL_LANGUAGE_CODE . "'");
            $lang = $code . '/';
        }
    return $lang;
}

// EOF