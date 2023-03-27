<?php
/*
Plugin Name: TinySlider
Plugin URI: https://risalshahed.com
Description: Display Slider
Version: 1.0
Author: Risal
Author URI: https://risalshahed.com
License: GPLv2 or later
Text Domain: tinyslider
Domain Path: /languages/
*/

// Very first -> load plugin textdomain
function tinys_load_textdomain() {
    load_plugin_textdomain('tinys_load_textdomain', false, dirname(__FILE__),'/lanuages');
}

add_action('plugins_loaded', 'tinys_load_textdomain');


// ------------------ 9.2 Add a function to MANAGE ASSET ------------------
function tinys_assets() {
    wp_enqueue_style( 'tinyslider-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/tiny-slider.css', null, '1.0' );
    wp_enqueue_script( 'tinyslider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.4/tiny-slider.js', null, '1.0', true );

    wp_enqueue_script( 'tinyslider-main-js', plugin_dir_url(__FILE__).'/assets/js/main.js', array('jquery'), '1.0', true );
}

add_action('wp_enqueue_scripts', 'tinys_assets');

// ------------------ 9.3 REGENERATE Thumbnails ------------------
// "Onet Regenerate Thumbnail" Plugin Install & Activate krte hbe, jehetu amdr images regenerate krte hbe image er resize krte hoile
// activate kore, dashboard er tools a giye, regen thumbnail a giye regen krte hbe, taholei amdr add kra new size effectibe hbe
function tinys_init() {
    add_image_size('tiny-slider', 800, 500, true);
}

add_action('init', 'tinys_init');

// --------------------------- 9.1 add shortcode ---------------------------
// ***** children thakle "parameter" a "content" asbe, r self closing hoile NO_CONTENT
function tinys_shortcode_tslider($arguments, $content) {
    $defaults = array(
        'width'=>800,
        'height'=>500,
        'id'=>''
    );

    $attributes = shortcode_atts($defaults, $arguments);
    // **** jehetu content ase, eita k MUST shortcode kra lagbe
    $content = do_shortcode($content);

    // **** Array, "" er vitore dewa jaay na, tai agei ekta variable a declare krlm
    $id = $attributes['id'];
    $width = $attributes['width'];
    $height = $attributes['height'];

    /* $shorcode_output = <<<EOD
<div id="{$attributes['id']}" style="width:{$attributes['width']}; height:{$attributes['height']}">
    <div class='slider'>
    {$content}
    </div>
</div>
EOD; */

    $shorcode_output =
        "<div id={$id} style='width:{$width}; height:{$height}'>
            <div class='slider'>
                {$content}
            </div>
        </div>";

    // ALWAYS "return" the_shortcode, NEVER "echo"
    return $shorcode_output;
}


add_shortcode('tslider', 'tinys_shortcode_tslider');


function tinys_shortcode_tslide($arguments) {
    $defaults = array(
        'id'=>'',
        'caption'=>'',
        'size'=>'large'
    );
    
    $attributes = shortcode_atts($defaults, $arguments);

    $image_src = wp_get_attachment_image_src($attributes['id'], $attributes['size']);

    // var_dump($image_src[0]);
    $single_img = $image_src[0];
    // echo $single_img;
    // echo '<br/>';
    $caption = $attributes['caption'];

    /* $shortcode_output = <<<EOD
<div class='slide'>
    <p><img src="{$image_src[0]}" alt="{$attributes['caption']}" /></p>
    <p>{$attributes['caption']}</p>
</div>
EOD; */
    $shortcode_output =
        "<div class='slide'>
            <p><img src='{$single_img}' alt='{$caption}' /></p>
            <p>{$caption}</p>
        </div>";

    return $shortcode_output;
}

add_shortcode('tslide', 'tinys_shortcode_tslide');