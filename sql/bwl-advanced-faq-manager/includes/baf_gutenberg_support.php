<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function baf_post_type_gutenberg_support($args, $post_type) {
    if ($post_type == "bwl_advanced_faq") {
        $args['show_in_rest'] = true;
    }

    return $args;
}

function baf_taxonomy_gutenberg_support($args, $taxonomy) {
    if ($taxonomy == 'advanced_faq_category' || $taxonomy == 'advanced_faq_topics') {
        $args['show_in_rest'] = true;
    }

    return $args;
}

$bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

if (isset($bwl_advanced_faq_options['baf_gutenberg_status']) && $bwl_advanced_faq_options['baf_gutenberg_status'] == "on") {

add_filter('register_post_type_args', 'baf_post_type_gutenberg_support', 20, 2);
add_filter('register_taxonomy_args', 'baf_taxonomy_gutenberg_support', 10, 2);

}