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
    load_plugin_textdomain('posts-to-qrcode', false, dirname(__FILE__).'/lanuages');
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
    // ------------------------------ 7.1.1 ------------------------------
    $height = get_option('pqrc_height');
    $width = get_option('pqrc_width');
    $height = $height ? $height : 180;
    $width = $width ? $width : 180;
    $dimension = apply_filters('pqrc_qrcode_dimension', "{$width}x{$height}");
    // --------------------------- End of 7.1-1 ---------------------------

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

// --------------------------------- 7.1.2 ---------------------------------
function pqrc_settings_init() {
    // add_settings_field($id, $title, $callback, $page)
    /* add_settings_field( 'pqrc_height', __('QR_Code_Height', 'posts-to-qrcode'), 'pqrc_display_height', 'general' );
    add_settings_field( 'pqrc_width', __('QR_Code_Width', 'posts-to-qrcode'), 'pqrc_display_width', 'general' ); */
    // settings ei data WP er "Options" table a save hbe
    // **** amra jodi, WP admin er dashboard er "reading" page a dekhte chaitm
    add_settings_field( 'pqrc_height', __('QR_Code_Height', 'posts-to-qrcode'), 'pqrc_display_height', 'reading' );
    add_settings_field( 'pqrc_width', __('QR_Code_Width', 'posts-to-qrcode'), 'pqrc_display_width', 'reading' );
    // settings ei data WP er "Options" table a save hbe

    // Let's "register" this settings
    // register_setting($option_group, $option_name, $args = array())
    /* register_setting( 'general', 'pqrc_height', array('sanitize_callback' => 'esc_attr') );
    register_setting( 'general', 'pqrc_width', array('sanitize_callback' => 'esc_attr') ); */
    // **** amra jodi, WP admin er dashboard er "reading" page a dekhte chaitm
    register_setting( 'general', 'pqrc_height', array('sanitize_callback' => 'esc_attr') );
    register_setting( 'general', 'pqrc_width', array('sanitize_callback' => 'esc_attr') );
}

function pqrc_display_height() {
    $height = get_option('pqrc_height');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'pqrc_height', 'pqrc_height', $height);
}

function pqrc_display_width() {
    $width = get_option('pqrc_width');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'pqrc_width', 'pqrc_width', $width);
}

// jehetu amra admin panel kaj krci tai "admin_init" hook initiate krbo
add_action('admin_init', 'pqrc_settings_init');

