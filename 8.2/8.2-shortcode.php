<?php
/*
Plugin Name: Shortcode
Plugin URI: https://risalshahed.com
Description: Display Shortcode
Version: 1.0
Author: Risal
Author URI: https://risalshahed.com
License: GPLv2 or later
Text Domain: shortcode
Domain Path: /languages/
*/


function astra_button2($attributes) {
    return sprintf( "<a href='%s' target='_blank'>%s</a>", $attributes['url'], $attributes['title'] );
}

add_shortcode('button2', 'astra_button2');

function astra_button3($attributes, $content) {
    return sprintf( "<a href='%s' target='_blank'>%s</a>", $attributes['url'], $content );
}

add_shortcode('button3', 'astra_button3');