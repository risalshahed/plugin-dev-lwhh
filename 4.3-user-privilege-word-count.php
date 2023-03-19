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

// Load Plugin Text Domain
function wordcount_load_textdomain() {
    load_plugin_textdomain('word-count', false, dirname(__FILE__.'/languages'));
}

add_action('plugins_loaded', 'wordcount_load_textdomain');

// count words
function wordcount_count_words($content) {
    $stripped_content = strip_tags($content);
    $word_num = str_word_count($stripped_content);  
    $label = __('Total number of Words', 'word-count');
    // ---------------------------- 4.3 ----------------------------
    // USER k subidha dte filter_hook dewa
    // user change na krle jei default value thakbe setai 2nd parameter a dewa ("$label" & "h2" tag)
    $label = apply_filters('wordcount_heading', $label);    // user can change label
    $tag = apply_filters('wordcount_tag', 'h2');    // user can change tag
    // BUT user kivabe change krbe ??? "theme file" er "functions.php" theke krte pare, ekhn "Astra" theme er "functions.php" file theke kri

    $content .= sprintf('<%s>%s: %s</%s>', $tag, $label, $word_num, $tag);
    return $content;
}

add_filter('the_content', 'wordcount_count_words');