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
    $content .= sprintf('<h2>%s: %s</h2>', $label, $word_num);
    return $content;
}

add_filter('the_content', 'wordcount_count_words');