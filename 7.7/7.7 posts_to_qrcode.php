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

// -------------------------------- 7.6 --------------------------------
$pqrc_countries = array(
    __('Afghanistan', 'posts-to-qrcode'),
    __('Bangladesh', 'posts-to-qrcode'),
    __('Bhutan', 'posts-to-qrcode'),
    __('India', 'posts-to-qrcode'),
    __('Maldives', 'posts-to-qrcode'),
    __('Nepal', 'posts-to-qrcode'),
    __('Pakistan', 'posts-to-qrcode'),
    __('Sri Lanka', 'posts-to-qrcode')
);

function pqrc_init() {
    global $pqrc_countries;
    // 7.6 user chaile theme er "functions.php" theke country add krte parbe
    $pqrc_countries = apply_filters('pqrc_countries', $pqrc_countries);
}

// filter hook 2 bar apply hoye double print hossilo sbkisu, tai eita k 1ta action hook er callback function er mddhe rakhlm
add_action("init", "pqrc_init");


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

    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );

    if( in_array($current_post_type, $excluded_post_types) ) {
        return $content;
    }

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

    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url);

    $content .= sprintf("<div class='qrcode'><img %s src='%s' alt='%s' /></div>", $image_attributes, $image_src, $current_post_title);
    return $content;
}

add_filter('the_content', 'pqrc_display_qrcode');

// --------------------------------- 7.1.2 ---------------------------------
function pqrc_settings_init() {
    // -------------------------------- 7.2 --------------------------------
    // 7.2.1 settings er ALADA_SECTION create krbo
    add_settings_section('pqrc_section', __('Posts to Qr Code', 'posts-to-qrcode'), 'pqrc_section_callback', 'general');
    

    // add_settings_field($id, $title, $callback, $page)
    // 7.2 bind section with this "two" field
    /* add_settings_field( 'pqrc_height', __('QR_Code_Height', 'posts-to-qrcode'), 'pqrc_display_height', 'general', 'pqrc_section' );
    add_settings_field( 'pqrc_width', __('QR_Code_Width', 'posts-to-qrcode'), 'pqrc_display_width', 'general', 'pqrc_section' ); */
    // settings er ei data WP er "Options" table a save hbe
    // End of 7.2.1

    // ------------- 7.3 Generic Callback Function for all fields -------------
    add_settings_field( 'pqrc_height', __('QR_Code_Height', 'posts-to-qrcode'), 'pqrc_display_field', 'general', 'pqrc_section', array('pqrc_height') );
    add_settings_field( 'pqrc_width', __('QR_Code_Width', 'posts-to-qrcode'), 'pqrc_display_field', 'general', 'pqrc_section', array('pqrc_width') );
    
    // ------------------------ 7.4 dropdown add krbo ------------------------
    add_settings_field( 'pqrc_select', __('Dropdown', 'posts-to-qrcode'), 'pqrc_display_select_field', 'general', 'pqrc_section' );
    // 1ta select_field add krbo tai last er array ta lagbe na, onk gula krle lagto
    
    // ---------------------------- 7.5 Checkbox ----------------------------
    add_settings_field( 'pqrc_checkbox', __('Select Countries', 'posts-to-qrcode'), 'pqrc_display_checkboxgroup_field', 'general', 'pqrc_section' );
    // --------------------------- 7.7 jQuery toggle ---------------------------
    add_settings_field( 'pqrc_toggle', __('Toggle Field', 'posts-to-qrcode'), 'pqrc_display_toggle_field', 'general', 'pqrc_section' );


    // Let us "register" this settings (register na krle 'save changes' dleo SAVE hy NAAAAA)
    // register_setting($option_group, $option_name, $args = array())
    register_setting( 'general', 'pqrc_height', array('sanitize_callback' => 'esc_attr') );
    register_setting( 'general', 'pqrc_width', array('sanitize_callback' => 'esc_attr') );
    // --------------------- 7.4 Dropdown register ---------------------
    register_setting( 'general', 'pqrc_select', array('sanitize_callback' => 'esc_attr') );
    // --------------------- 7.5 Checkbox register ---------------------
    register_setting( 'general', 'pqrc_checkbox');
    // jehetu checkbox a multiple save hy (array akare) i.e. amra "esc_attr" USE krbo NAA, krle array ta flat string hye jabe!!!
    // 7.7 toggle register
    register_setting( 'general', 'pqrc_toggle');
}

// ---------------------------- 7.7 Toggle Function ---------------------------
function pqrc_display_toggle_field() {
    $option = get_option('pqrc_toggle');
    echo "<div id='toggle1'></div>";
    echo "<input type='hidden' id='pqrc_toggle' name='pqrc_toggle' value={$option}/>";
}



// ---------------------------- 7.5 Checkbox Function ---------------------------
function pqrc_display_checkboxgroup_field() {
    global $pqrc_countries;     // 7.6
    $option = get_option('pqrc_checkbox');

    foreach($pqrc_countries as $country) {
        $selected = '';
        // "option" kin2 suru tei array na! so it will return error! so ei check o dte hbe "option" array ki na!
        if(is_array($option) && in_array($country, $option)) {
            $selected = 'checked';
        }

        // <option value='value' selected>value</option> <- note bujhar jnno
        printf( "<input type='checkbox' name='pqrc_checkbox[]' value='%s' %s /> %s <br />", $country, $selected, $country );   // checkbox multiple value save kre tai name array hishebe dte hbe
    }
}

// ---------------------------- 7.4 Dropdown Function ---------------------------
function pqrc_display_select_field() {
    global $pqrc_countries;     // 7.6
    $option = get_option('pqrc_select');
    
    printf("<select id='%s' name='%s'>", 'pqrc_select', 'pqrc_select');
    foreach($pqrc_countries as $country) {
        $selected = '';
        if($option == $country) {
            $selected = 'selected';
        }
        // <option value='value' selected>value</option> <- note bujhar jnno
        printf( "<option value='%s' %s>%s</option>", $country, $selected, $country );
    }
    echo "</select>";
}

// 7.2.2
function pqrc_section_callback() { ?>
    <p>
        <?php _e('Settings for Posts to QR Plugin', 'posts-to-qrcode'); ?>
    </p> <?php
}
// End of 7.2.2

function pqrc_display_field($args) {
    // ------------------------ 7.3 argument er vitor ki asey? "add_settings_field" er vitore thaka last parameter array ta asey ------------------------
    $option = get_option($args[0]); //  krn array te 1ta e element asey
    printf("<input type='text' id='%s' name='%s' value='%s' />", $args[0], $args[0], $option);
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

// ----------------------- 7.7 custom jQuery plugin -----------------------
// ************* amra chai amdr JS (CSS o hoite pare) file sudhu 1ta specific page a e load hbe, dhalao vabe shob page a naa *************
function pqrc_assets($screen) {
    // sudhu "general" settings page a JS load hbe
    if('options-general.php' == $screen) {
        // wp_enqueue_script($handle, $src = '', $deps = array(), $ver = \false, $in_footer = \false)
        wp_enqueue_style( 'pqrc-minitoggle-css', plugin_dir_url(__FILE__)."/assets/css/minitoggle.css" );
        wp_enqueue_script( 'pqrc-main-js', plugin_dir_url(__FILE__)."/assets/js/pqrc-main.js", array('jquery'), time(), true );
        // last_arg TRUE cz JS footer a load krbo
        wp_enqueue_script( 'pqrc-minitoggle-js', plugin_dir_url(__FILE__)."/assets/js/minitoggle.js", array('jquery'), '1.0', true );
        // ei file change krbo na tai version "1.0" instead of time
    }
}

// Admin Panel a JS likhte hoile,
add_action('admin_enqueue_scripts', 'pqrc_assets');