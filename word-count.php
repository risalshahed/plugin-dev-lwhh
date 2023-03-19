<?php
/*
Plugin Name: Word Count
Plugin URI: 
Description: Count Words from any WordPress Post
Version: 1.0
Author: LWHH
Author URI: https://hasin.me
License: GPLv2 or later
Text Domain: word-count
Domain Path: /languages/
*/

// Plugin jodi "Database_Table" er upor depend kre tokhn eikhan theke toiri kra hy
/* function wordcount_activation_hook() {
    // *** Activation Hook (1ta WP Plugin er "Active" Button a Click krle RUN kre)
    register_activation_hook(__FILE__, 'wordcount_activation_hook');
}

function wordcount_deactivation_hook() {
    // *** Deactivation Hook (1ta WP Plugin er "Deactive" Button a Click krle RUN kre)
    register_deactivation_hook(__FILE__, 'wordcount_deactivation_hook');
} */

// Load Plugin Text Domain
function wordcount_load_textdomain() {
    // load_plugin_textdomain(text_domain, ?deprecated, "languages" FILE er PATH);
    load_plugin_textdomain('word-count', false, dirname(__FILE__.'/languages'));
    // ********** FILE er PATH dekhaite amra eikhane "dirname" function use krci
}

add_action('plugins_loaded', 'wordcount_load_textdomain');

// count words
function wordcount_count_words($content) {
    // Strip HTML and PHP tags from a string
    $stripped_content = strip_tags($content);
    $word_num = str_word_count($stripped_content);  
    $label = __('Total number of Words', 'word-count');

    // USER k subidha dte filter_hook dewa
    // user change na krle jei default value thakbe setai 2nd parameter a dewa ("$label" & "h2" tag)
    $label = apply_filters('wordcount_heading', $label);    // user can change label
    $tag = apply_filters('wordcount_tag', 'h2');    // user can change tag
    // BUT user kivabe change krbe ??? "theme file" er "functions.php" theke krte pare, ekhn "Astra" theme er "functions.php" file theke kri

    $content .= sprintf('<%s>%s: %s</%s>', $tag, $label, $word_num, $tag);
    return $content;
}

add_filter('the_content', 'wordcount_count_words');


function wordcount_reading_time($content) {
    $stripped_content = strip_tags($content);
    $word_num = str_word_count($stripped_content);
    $reading_min = floor( $word_num / 200 );
    $reading_sec = ceil( ( $word_num % 200 ) / ( 200 / 60 ) );

    // let's check amdr plugin a "USER" asholei "readingtime" dekhaite chaay ki na
    $is_visible = apply_filters('wordcount_display_readingtime', 1);
    if($is_visible) {
        $label = __('Total Reading Time', 'word-count');
        $label = apply_filters('wordcount_readingtime_heading', $label);
        $tag = apply_filters('wordcount_readingtime_tag', 'h4');
        $content .= sprintf('<%s>%s: %s minutes %s seconds</%s>', $tag, $label, $reading_min, $reading_sec, $tag);
    }
    return $content;
}

add_filter('the_content', 'wordcount_reading_time');