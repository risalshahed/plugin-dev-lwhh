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
    // url a pass krbo so url ta "encode" kore nei
    $current_post_url = urlencode( get_the_permalink( $current_post_id ) );
    // ----------------------- 5.3 -----------------------
    // ************** POST_TYPE **************
    $current_post_type = get_post_type($current_post_id);

    // jodi kono post a USER "qrcode" NAA dte chay, seta jno user pare setar independence user k dbo
    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );

    // check if current post excluded er mddhe asey ki na
    if( in_array($current_post_type, $excluded_post_types) ) {
        // ***** say, amra "page" a asi i.e. $current_post_type = 'page', SO jokhn amra "PAGE" a thakbo, tokhn amra default content return kore dbo i.e. NO MODIFICATION will be there
        return $content;
    }

    // ************** SIZE **************
    $dimension = apply_filters('pqrc_qrcode_dimension', '185x185');

    // ----------------------- Image Attributes -----------------------
    // initial value nai tai null
    $image_attributes = apply_filters('pqrc_image_attributes', null);

    // ----------------------- 5.2 -----------------------
    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url);

    $content .= sprintf("<div class='qrcode'><img %s src='%s' alt='%s' /></div>", $image_attributes, $image_src, $current_post_title);
    // <img %s src='%s' alt='%s' />;
    // <img $image_attributes src='$image_src' alt='alt_tag' />
    // REMINDER, <a href='...'></a>, here, "a" is element & "href" is attribute
    return $content;
}

add_filter('the_content', 'pqrc_display_qrcode');