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

// 8.3 default value
function astra_button01($attributes) {
    // 8.3.1 assign default value
    $default = [
        'url' => '',
        'name' => __('Risal', 'shortcode'),
        'title' => __('LinkedIn', 'shortcode')
    ];

    // ******* 8.3.2 dashboard a use kra "shortcode" a jodi user kisu na dey, ei default value gula diye dbo, r "user" dle to o ja dbe ta e display kra hbe
    $button_attributes = shortcode_atts($default, $attributes);
    // er mane hoilo, "shortcode_atts" function 1st param a default value r 2nd param a user er dewa attributes nibe, ekhn dashboard a use kra "shortcode" a user kisu NAA dle default value dekhabe, otherwise user er input kra value dekhabe

    return sprintf(
        "<a href='%s' target='_blank'>Hey %s, go to %s</a><br/>",
        $button_attributes['url'],
        $button_attributes['name'],
        $button_attributes['title']
    );
}

add_shortcode('button01', 'astra_button01');


function astra_button2($attributes) {
    return sprintf( "<a href='%s' target='_blank'>%s</a><br/>", $attributes['url'], $attributes['title'] );
}

add_shortcode('button2', 'astra_button2');


function astra_button3($attributes, $content='') {
    $default = [
        'url' => '',
    ];

    $button_attributes = shortcode_atts($default, $attributes);

    return sprintf(
        "<a href='%s' target='_blank'>%s</a><br/>",
        $button_attributes['url'],
        $content
    );
}

add_shortcode('button3', 'astra_button3');