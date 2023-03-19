<?php
/*
Plugin Name: Posts To QR Code
Plugin URI: https://risalshahed.com
Description: Display QR Code under ever posts
Version: 1.0
Author: Risal
Author URI: https://risalshahed.com
License: GPLv2 or later
Text Domain: posts-to-qrcode
Domain Path: /languages/
*/

function pqrc_wordcount_load_textdomain() {
    load_plugin_textdomain('posts-to-qrcode', false, dirname(__FILE__.'/lanuages'));
}

function pqrc_display_qrcode($content) {
    // get_the_ID() -> Retrieve the ID of the current item in the WordPress Loop
    $current_post_id = get_the_ID();
    // get title
    $current_post_title = get_the_title($current_post_id);
    // url a pass krbo so "url encode" kore nei
    $current_post_url = urlencode( get_the_permalink( $current_post_id ) );

    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=185x185&ecc=L&qzone=1&data=%s', $current_post_url);

    $content .= sprintf("<div class='qrcode'><img src='%s' alt='%s' /></div>", $image_src, $current_post_title);    // img er alt tag -> "current_post_title"
    return $content;
}

add_filter('the_content', 'pqrc_display_qrcode');