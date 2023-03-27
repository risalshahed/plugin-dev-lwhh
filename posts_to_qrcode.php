<?php
/*
Plugin Name: Posts To QR Code
Plugin URI: https://learnwith.hasinhayder.com
Description: Display QR Code under ever posts
Version: 1.0
Author: LWHH
Author URI: https://hasin.me
License: GPLv2 or later
Text Domain: posts-to-qrcode
Domain Path: /languages/
*/

/*function wordcount_activation_hook(){}
register_activation_hook(__FILE__,"wordcount_activation_hook");

function wordcount_deactivation_hook(){}
register_deactivation_hook(__FILE__,"wordcount_deactivation_hook");*/

$pqrc_countries = array(
  __( 'Afghanistan', 'posts-to-qrcode' ),
  __( 'Bangladesh', 'posts-to-qrcode' ),
  __( 'Bhutan', 'posts-to-qrcode' ),
  __( 'India', 'posts-to-qrcode' ),
  __( 'Maldives', 'posts-to-qrcode' ),
  __( 'Nepal', 'posts-to-qrcode' ),
  __( 'Pakistan', 'posts-to-qrcode' ),
  __( 'Sri Lanka', 'posts-to-qrcode' ),
);

// apply_filters barbar add krle, country akbr dle 2bar add hoy! tai pqrc_init
// function call kore er mddhe apply_filters akbare declare kra holo
function pqrc_init() {

  global $pqrc_countries;

  // apply_filters($tag, $value)
  // improving $pqrc_countries applying filter hook
  $pqrc_countries = apply_filters('pqrc_countries', $pqrc_countries);
}

add_action( "init", 'pqrc_init' );


function wordcount_load_textdomain() {
  load_plugin_textdomain( 'posts-to-qrcode', false, dirname( __FILE__ ) . "/languages" );
}

function pqrc_display_qr_code( $content ) {
  $current_post_id    = get_the_ID();
  $current_post_title = get_the_title( $current_post_id );
  $current_post_url   = urlencode( get_the_permalink( $current_post_id ) );
  $current_post_type  = get_post_type( $current_post_id );

  // Post Type Check

  $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );
  if ( in_array( $current_post_type, $excluded_post_types ) ) {
      return $content;
  }

  // Dimension Hook
  $height    = get_option( 'pqrc_height' ); // egulo function er mddhe local variable
  $width     = get_option( 'pqrc_width' );  // so prefix kra mandatory na, krle vlo
  $height    = $height ? $height : 180; // specific value na thakle, default value nibo
  $width     = $width ? $width : 180; // here, default value is 180
  $dimension = apply_filters( 'pqrc_qrcode_dimension', "{$width}x{$height}" );
  // apply_filters( $tag, $value )

  //Image Attributes
  $image_attributes = apply_filters( 'pqrc_image_attributes', null );

  $image_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url );
  $content   .= sprintf( "<div class='qrcode'><img %s  src='%s' alt='%s' /></div>", $image_attributes, $image_src, $current_post_title );

  return $content;
}

add_filter( 'the_content', 'pqrc_display_qr_code' );

function pqrc_settings_init() { // general settings a kisu settings nibo

  // add_settings_section( $id, $title, $callback, $page )
  add_settings_section( 'pqrc_section', __( 'Posts to QR Code', 'posts-to-qrcode' ), 'pqrc_section_callback', 'general' );

  // settings field toiri krar akta trick holo, prottek field er jnno alada alada callback function (nichey asey, function pqrc_display_height(), function pqrc_display_width() ) lekha khub tedious work; akhn ei process ta simple krte, kisu change kra lagbe, jmn ...***

  // add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array)

  // add_settings_field( 'pqrc_height', __( 'QR Code Height', 'posts-to-qrcode' ), 'pqrc_display_height', 'general', 'pqrc_section')
  // ...*** eikhane, last a array add krte hbe, callback function er name pqrc_display_height, pqrc_display_width, pqrc_display_extra etc. er poriborte pqrc_display_field kra hoilo

  add_settings_field( 'pqrc_height', __( 'QR Code Height', 'posts-to-qrcode' ), 'pqrc_display_field', 'general', 'pqrc_section', array( 'pqrc_height' ) );

  add_settings_field( 'pqrc_width', __( 'QR Code Width', 'posts-to-qrcode' ), 'pqrc_display_field', 'general', 'pqrc_section', array( 'pqrc_width' ) );
  
  // add_settings_field( 'pqrc_extra', __( 'Extra Field', 'posts-to-qrcode' ), 'pqrc_display_field', 'general', 'pqrc_section', array('pqrc_extra') );


  // Add dropdown field in general settings
  // array baad dewa hoice eikhane; akta function diye jokhn onk gula select field draw kra lage, tokhn array lagey!
  add_settings_field( 'pqrc_select', __( 'Dropdown', 'posts-to-qrcode' ), 'pqrc_display_select_field', 'general', 'pqrc_section' );
  add_settings_field( 'pqrc_checkbox', __( 'Select Countries', 'posts-to-qrcode' ), 'pqrc_display_checkboxgroup_field', 'general', 'pqrc_section' );
  add_settings_field( 'pqrc_toggle', __( 'Toggle Field', 'posts-to-qrcode' ), 'pqrc_display_toggle_field', 'general', 'pqrc_section' );

  // register_setting( $option_group(kon page a save hbe), $option_name, $args = array)
  register_setting( 'general', 'pqrc_height', array( 'sanitize_callback' => 'esc_attr' ) );
  register_setting( 'general', 'pqrc_width', array( 'sanitize_callback' => 'esc_attr' ) );
  //register_setting( 'general', 'pqrc_extra', array( 'sanitize_callback' => 'esc_attr' ) );
  register_setting( 'general', 'pqrc_select', array( 'sanitize_callback' => 'esc_attr' ) );
  register_setting( 'general', 'pqrc_checkbox' ); // multiple country select hbe checkbox a
  register_setting( 'general', 'pqrc_toggle' ); // tai eikhane array dbo na!
}

function pqrc_display_toggle_field() {
  $option = get_option('pqrc_toggle');
  echo '<div id="toggle1"></div>';
  echo "<input type='hidden' name='pqrc_toggle' id='pqrc_toggle' value='".$option."'/>";
}

function pqrc_display_checkboxgroup_field() {
  global $pqrc_countries; // pqrc_countries function er baire defined, tai vitore global must
  // anytime option table thk option retrieve krte get_option used hy
  $option = get_option('pqrc_checkbox');

  foreach ( $pqrc_countries as $country ) {
    $selected = '';

    // is_array(var)-> var array kina check kra; in_array('value check', array/ var er mddhe)
    if (is_array( $option ) && in_array($country, $option) ) {
      $selected = 'checked';
    }
    printf( '<input type="checkbox" name="pqrc_checkbox[]" value="%s" %s /> %s <br/>', $country, $selected, $country ); // multiple selectable checkbox tai name="pqrc_checkbox[]"
  }
}

function pqrc_display_select_field() {
  global $pqrc_countries;
  $option = get_option( 'pqrc_select' );

  printf( '<select id="%s" name="%s">', 'pqrc_select', 'pqrc_select' );
  foreach ( $pqrc_countries as $country ) {
    $selected = '';
    if ( $option == $country ) {
      $selected = 'selected';
    }
    printf( '<option value="%s" %s>%s</option>', $country, $selected, $country );
  }
  echo "</select>";
}


function pqrc_section_callback() {
  echo "<p>" . __( 'Settings for Posts To QR Plugin', 'posts-to-qrcode' ) . "</p>";
}

// ei single callback diye issa mto field use kra jabe
function pqrc_display_field( $args ) {
  $option = get_option($args[0]); // single element dewa hoice, tai $args[0] dewa hoice
  printf( "<input type='text' id='%s' name='%s' value='%s'/>", $args[0], $args[0], $option );
}

function pqrc_display_height() {
  $height = get_option( 'pqrc_height' );
  printf( "<input type='text' id='%s' name='%s' value='%s'/>", 'pqrc_height', 'pqrc_height', $height );
}

function pqrc_display_width() {
  $width = get_option( 'pqrc_width');
  printf( "<input type='text' id='%s' name='%s' value='%s'/>", 'pqrc_width', 'pqrc_width', $width);
}

add_action( "admin_init", 'pqrc_settings_init' );

function pqrc_assets( $screen ) {
  if ( 'options-general.php' == $screen ) { // current (general) page execute hbe only
    wp_enqueue_style( 'pqrc-minitoggle-css', plugin_dir_url( __FILE__ ) . "/assets/css/minitoggle.css" );

    // wp_enqueue_script($handle, $src = '', $deps = array, $ver = false, $in_footer = false)
    wp_enqueue_script( 'pqrc-minitoggle-js', plugin_dir_url( __FILE__ ) . "/assets/js/minitoggle.js", array( 'jquery' ), "1.0", true );
    wp_enqueue_script( 'pqrc-main-js', plugin_dir_url( __FILE__ ) . "/assets/js/pqrc-main.js", array( 'jquery' ), time(), true );
  }
}

// any WP plugin a CSS or JS file enqueue krte chaile, kisu rules niye aware hoite hbe
add_action( 'admin_enqueue_scripts', 'pqrc_assets' );

