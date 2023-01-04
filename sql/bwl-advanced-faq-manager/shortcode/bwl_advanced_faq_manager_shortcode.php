<?php

/* ----- 
 * @Description:Generate Suggestion String 
 * @Since: 1.6.9
 * @Last Update: 19-05-2018
 *  ---- */

function get_baf_suggestion_string($atts) {

    extract($atts);

    $baf_suggestion_string = "";

    if (isset($baf_suggestion_status) && $baf_suggestion_status == 1 && $baf_suggestion != "") {

        $baf_suggestion = explode(',', $baf_suggestion);

        $custom_baf_suggestion = "";

        if (sizeof($baf_suggestion) > 0) {

            foreach ($baf_suggestion as $key => $value) {
                $custom_baf_suggestion .= '<a href="#">' . $value . '</a>, ';
            }

            $custom_baf_suggestion = substr($custom_baf_suggestion, 0, strlen($custom_baf_suggestion) - 2);
        }

        $baf_suggestion_string .= '<div class="baf_suggestion"><b class="baf_suggestion_prefix">' . $baf_suggestion_prefix . '</b> ' . $custom_baf_suggestion . '</div>';
    }

    return $baf_suggestion_string;
}

// @Description: FAQ Search Box.
// @Since: 1.0.2
// @Last Update: 24-02-2016

add_shortcode('baf_sbox', 'baf_sbox');

function baf_sbox($atts) {

    $atts = shortcode_atts(array(
        'sbox_id' => wp_rand(),
        'paginate' => 0,
        'search_only_title' => 0,
        'sbox_class' => '',
        'pag_limit' => 0,
        'placeholder' => esc_html__('Search...', 'bwl-adv-faq'),
        'highlight_color' => '#000000',
        'highlight_bg' => '#FDE990',
        'baf_suggestion_status' => 0,
        'baf_suggestion_prefix' => esc_html__('Popular searches:', 'bwl-adv-faq'),
        'baf_suggestion' => '',
        'cont_ext_class' => ''
            ), $atts);

    extract($atts);

    $unique_faq_container_id = $sbox_id;

    $baf_suggestion_string = get_baf_suggestion_string($atts);

    $bwla_search_form_class = (isset($cont_ext_class) && $cont_ext_class != "" ) ? 'bwl-faq-search-panel ' . $cont_ext_class : 'bwl-faq-search-panel';

    // Added in version 1.7.1

    $baf_sbox_output = '<form id="live-search" action="" class="' . $bwla_search_form_class . '" method="post" data-form_id="' . $unique_faq_container_id . '" data-paginate="' . $paginate . '" data-search_only_title="' . $search_only_title . '" data-pag_limit="' . $pag_limit . '" >
                        <fieldset>
                            <input type="text" class="search_icon text-input ' . $sbox_class . '" id="bwl_filter_' . $unique_faq_container_id . '" value="" placeholder="' . $placeholder . '" data-highlight_color="' . $highlight_color . '" data-highlight_bg="' . $highlight_bg . '" />
                                <span class="baf-btn-clear baf_dn"></span>
                                <span id="bwl-filter-message-' . $unique_faq_container_id . '" class="bwl-filter-message"></span>
                        </fieldset>
                        ' . $baf_suggestion_string . '
                    </form>';

    return $baf_sbox_output;
}

// @Description: Main FAQ Shortcode.
// @Since: 1.0.0
// @Last Update: 24-02-108

add_shortcode('bwla_faq', 'bwla_faq');

function bwla_faq($atts) {

    $id_prefix = wp_rand();

    $atts = shortcode_atts(array(
        'post_type' => 'bwl_advanced_faq',
        'meta_key' => '',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'limit' => -1,
        'faq_category' => '',
        'faq_topics' => '',
        'sbox' => 1,
        'search_only_title' => 0,
        'sbox_class' => '',
        'placeholder' => esc_html__('Search...', 'bwl-adv-faq'),
        'highlight_color' => '#000000',
        'highlight_bg' => '#FDE990',
        'baf_suggestion_status' => 0,
        'baf_suggestion_prefix' => esc_html__('Popular searches:', 'bwl-adv-faq'),
        'baf_suggestion' => '',
        'taxonomy_info_search' => 0,
        'cont_ext_class' => '',
        'btn_ctrl' => 1,
        'bwl_tabify' => 0,
        'taxonomy_info' => 0, // It will display taxonomy description.
        'list' => 0,
        'multi' => 0,
        'single' => 0,
        'collapse' => 0,
        'fpid' => 0,
        'paginate' => 0,
        'pag_limit' => 5,
        'post_ids' => '',
        'theme_id' => '', // light, red, blue,green,pink,orange
        'first_color' => '',
        'second_color' => '',
        'label_text_color' => '',
        'accordion_arrow' => '',
        'custom_layout' => '', // baf_layout_flat, baf_layout_semi_round, baf_layout_round
        'row_open' => '', // Mention the row number that you would like to open when load the page.
        'sc_excerpt' => 0,
        'schema' => 0,
        'aia' => 0,
            ), $atts);

    extract($atts);

    // Enqueue Scripts Properly In Version 1.6.4

    wp_enqueue_script('baf-custom-scripts');
    wp_enqueue_script('baf_pagination');
    
    // Meta Key Update.

    if ( $meta_key == "votes_count" ) {
        $meta_key = 'baf_votes_count';
    }


    // Show Single FAQ. Introduced Version 1.4.9

    if ($multi == 1) {

        return get_multi_faq_interface($atts);
    }


    if ($single == 1) {

        return get_single_faq_interface($atts);
    }

    // New Feature added in version 1.4.6

    if ($list == 1 && $faq_category != "") {

        return get_list_faq_interface($atts);
    }

    if ($list == 1 && $faq_topics != "") {

        return get_list_faq_interface($atts);
    }

    if ($faq_category != "") {
        $id_prefix = "category-";
        $baf_sort_prefix = PREFIX_BAF_CAT;
        $baf_sort_filter = 'category';
    }

    if ($faq_topics != "") {
        $id_prefix = "topic-";
        $baf_sort_prefix = PREFIX_BAF_TOPIC;
        $baf_sort_filter = 'topics';
    }

    $args = array(
        'post_status' => 'publish',
        'post_type' => $post_type,
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $limit,
        'advanced_faq_category' => $faq_category,
        'advanced_faq_topics' => $faq_topics
    );

    if (isset($post_ids) && $post_ids != "") {
        $args['post__in'] = explode(',', $post_ids);
    }

    if (isset($meta_key) && $meta_key != "") {
        $args['meta_key'] = $meta_key;
    }

    $loop = new WP_Query($args);

    /*     * *********************************************************
     * @Description: Check if tabbify faq and custom order has been setted then we 
     * need to adjust loop.
     * @since: Version 1.6.1
     * ********************************************************* */

    if (isset($bwl_tabify) && $bwl_tabify == 1 && isset($orderby) && $orderby == "menu_order") {

        $baf_cat_all_posts_id = array();
        $baf_term_id = 0;

        if ($loop->have_posts()) :

            while ($loop->have_posts()) :

                $loop->the_post();

                if ($faq_topics != "") {
                    $category_info = get_term_by('slug', $faq_topics, 'advanced_faq_' . $baf_sort_filter);
                } else {
                    $category_info = get_term_by('slug', $faq_category, 'advanced_faq_' . $baf_sort_filter);
                }

                $baf_term_id = $category_info->term_id;
                $baf_cat_all_posts_id[] = get_the_ID();

            endwhile;

        endif;

        wp_reset_query();

        $args = baf_dynamic_sort_args($baf_cat_all_posts_id, $baf_sort_prefix, $baf_sort_filter, $baf_term_id, $faq_category, $limit);

        $loop = new WP_Query($args);
    }

    // Generate FAQ List Output.

    return get_baf_output($atts, $loop);
}

/*------ For List Items  ---------*/

function get_list_faq_interface($atts) {

    extract($atts);

    wp_enqueue_script('bwl-advanced-faq-filter');

    $unique_faq_container_id = wp_rand();

    $list_output = '<section class="ac-container" container_id="' . $unique_faq_container_id . '" id="' . $unique_faq_container_id . '">';

    $list_inner_output = "";

    // Some Reset: Fix in version 1.5.3

    $orderby = "menu_order";
    $order = "ASC";
    $limit = -1;
    $paginate = 1;

    if (isset($atts['paginate'])) {
        $paginate = $atts['paginate'];
    }

    $pag_limit = 5;

    if (isset($atts['pag_limit'])) {
        $pag_limit = $atts['pag_limit'];
    }

    if (isset($atts['sbox']) && $atts['sbox'] == 1) {

        // Added in version 1.7.1
        // Search Only Title
        $search_only_title = 0;

        if (isset($atts['search_only_title'])) {
            $search_only_title = $atts['search_only_title'];
        }

        // Custom Class For Search Box.

        $sbox_extra_class = "";

        if (isset($atts['sbox_class']) && $atts['sbox_class'] != "") {
            $sbox_extra_class = $atts['sbox_class'];
        }


        $baf_suggestion_string = get_baf_suggestion_string($atts);

        $bwla_search_form_class = (isset($cont_ext_class) && $cont_ext_class != "" ) ? 'bwl-faq-search-panel ' . $cont_ext_class : 'bwl-faq-search-panel';

        $list_inner_output .= '<form id="live-search" action="" class="' . $bwla_search_form_class . '" method="post" data-paginate="' . $paginate . '" data-search_only_title="' . $search_only_title . '" data-pag_limit="' . $pag_limit . '" data-unique_id="' . $unique_faq_container_id . '">
                        <fieldset>
                            <input type="text" class="baf_filter_list search_icon text-input ' . $sbox_extra_class . '" id="bwl_filter_' . $unique_faq_container_id . '" value="" placeholder="' . $placeholder . '" data-highlight_color="' . $highlight_color . '" data-highlight_bg="' . $highlight_bg . '" data-taxonomy_info_search="' . $taxonomy_info_search . '" />
                                <span class="baf-btn-clear baf_dn"></span>
                             <span id="bwl-filter-message-' . $unique_faq_container_id . '" class="bwl-filter-message"></span>
                        </fieldset>
                        ' . $baf_suggestion_string . '
                    </form>';
    }

    if (isset($atts['orderby'])) {

        $orderby = trim($atts['orderby']);
    }

    if (isset($atts['order'])) {

        $order = trim($atts['order']);
    }

    if (isset($atts['limit'])) {

        $limit = trim($atts['limit']);
    }

    if (isset($faq_topics) && $faq_topics != "") {

        $bwl_faq_topics = explode(',', $faq_topics);

        foreach ($bwl_faq_topics as $topics) {

            $topics_info = get_term_by('slug', $topics, 'advanced_faq_topics');

            $list_inner_output .= '<div class="baf_taxonomy_info_container">';

            $list_inner_output .= '<h2>' . $topics_info->name . '</h2>';

            // Display Topics Description.
            // Since: Version 1.6.7

            if (isset($taxonomy_info) && $taxonomy_info == 1 && $topics_info->description != "") {
                $list_inner_output .= '<p class="baf_taxonomy_info">' . $topics_info->description . '</p>';
            }

            $list_inner_output .= '</div>';

            if ($orderby == 'menu_order') {

                $list_inner_output .= do_shortcode('[bwla_dsort_faq baf_sort_filter="topics" faq_topics="' . $topics . '" baf_term_id="' . $topics_info->term_id . '" sbox="0" sbox_class="' . $sbox_class . '" search_only_title="' . $search_only_title . '" btn_ctrl="' . $btn_ctrl . '" order="' . $order . '" orderby="' . $orderby . '" pag_limit="' . $pag_limit . '" paginate="' . $paginate . '" limit="' . $limit . '" theme_id="' . $theme_id . '" first_color="' . $first_color . '" second_color="' . $second_color . '" label_text_color="' . $label_text_color . '" accordion_arrow="' . $accordion_arrow . '" custom_layout="' . $custom_layout . '" sc_excerpt="' . $sc_excerpt . '" schema="' . $schema . '" /]');
            } else {

                $list_inner_output .= do_shortcode('[bwla_faq faq_topics="' . $topics . '" sbox="0" sbox_class="' . $sbox_class . '" search_only_title="' . $search_only_title . '" btn_ctrl="' . $btn_ctrl . '" list="0" order="' . $order . '" orderby="' . $orderby . '" pag_limit="' . $pag_limit . '" paginate="' . $paginate . '" limit="' . $limit . '" theme_id="' . $theme_id . '" first_color="' . $first_color . '" second_color="' . $second_color . '" label_text_color="' . $label_text_color . '" accordion_arrow="' . $accordion_arrow . '" custom_layout="' . $custom_layout . '" sc_excerpt="' . $sc_excerpt . '" schema="' . $schema . '" /]');
            }
        }
    } else {

        $bwl_faq_category = explode(',', $faq_category);

        foreach ($bwl_faq_category as $category) {

            $category_info = get_term_by('slug', $category, 'advanced_faq_category');

            $list_inner_output .= '<div class="baf_taxonomy_info_container">';

            $list_inner_output .= '<h2>' . $category_info->name . '</h2>';

            // Display Category Description.
            // Since: Version 1.6.7

            if (isset($taxonomy_info) && $taxonomy_info == 1 && $category_info->description != "") {
                $list_inner_output .= '<p class="baf_taxonomy_info">' . $category_info->description . '</p>';
            }

            $list_inner_output .= '</div>';

            if ($orderby == 'menu_order') {

                $list_inner_output .= do_shortcode('[bwla_dsort_faq faq_category="' . $category . '" baf_term_id="' . $category_info->term_id . '" sbox="0" sbox_class="' . $sbox_class . '" search_only_title="' . $search_only_title . '" btn_ctrl="' . $btn_ctrl . '" order="' . $order . '" orderby="' . $orderby . '" pag_limit="' . $pag_limit . '" paginate="' . $paginate . '" limit="' . $limit . '" theme_id="' . $theme_id . '" first_color="' . $first_color . '" second_color="' . $second_color . '" label_text_color="' . $label_text_color . '" accordion_arrow="' . $accordion_arrow . '" custom_layout="' . $custom_layout . '" sc_excerpt="' . $sc_excerpt . '" schema="' . $schema . '" /]');
            } else {
//                
                $list_inner_output .= do_shortcode('[bwla_faq faq_category="' . $category . '" sbox="0" sbox_class="' . $sbox_class . '" search_only_title="' . $search_only_title . '" btn_ctrl="' . $btn_ctrl . '" list="0" order="' . $order . '" orderby="' . $orderby . '" pag_limit="' . $pag_limit . '" paginate="' . $paginate . '" limit="' . $limit . '" theme_id="' . $theme_id . '" first_color="' . $first_color . '" second_color="' . $second_color . '" label_text_color="' . $label_text_color . '" accordion_arrow="' . $accordion_arrow . '" custom_layout="' . $custom_layout . '" sc_excerpt="' . $sc_excerpt . '" schema="' . $schema . '" /]');
            }
        }
    }

    $list_output .= $list_inner_output;

    $list_output .= '</section><!-- container -->';

    return $list_output;
}

/*------ For List Items  ---------*/

function get_multi_faq_interface($atts) {

    shortcode_atts(array(
        'post_type' => 'bwl_advanced_faq',
        'limit' => -1,
        'meta_key' => '',
        'faq_category' => '',
        'faq_topics' => '',
        'sbox' => 0,
        'bwl_tabify' => 0,
        'list' => 0,
        'fpid' => 0,
        'collapse' => 0,
        'sc_excerpt' => 0,
        'schema' => 0
            ), $atts);
  

    extract($atts);

    $args = array(
        'post_status' => 'publish',
        'post_type' => $post_type,
        'posts_per_page' => -1
    );

    $unique_faq_container_id = wp_rand();

    $fpids = explode(',',$fpid);

    if( is_array($fpids)) {
        $args['post__in'] = $fpids;
    }
    

    if (isset($meta_key) && $meta_key != "") {
        $args['meta_key'] = $meta_key;
    }

    $id_prefix = 'multi-';

    $loop = new WP_Query($args);

    return get_baf_output($atts, $loop, $id_prefix);
}

/*------ For List Items  ---------*/

function get_single_faq_interface($atts) {

    shortcode_atts(array(
        'post_type' => 'bwl_advanced_faq',
        'limit' => -1,
        'meta_key' => '',
        'faq_category' => '',
        'faq_topics' => '',
        'sbox' => 0,
        'bwl_tabify' => 0,
        'list' => 0,
        'single' => 1,
        'fpid' => 0,
        'collapse' => 0,
        'sc_excerpt' => 0,
        'schema' => 0
            ), $atts);

    extract($atts);

    $args = array(
        'post_status' => 'publish',
        'post_type' => $post_type,
        'posts_per_page' => 1,
        'p' => $fpid
    );

    $unique_faq_container_id = wp_rand();    

    if (isset($meta_key) && $meta_key != "") {
        $args['meta_key'] = $meta_key;
    }

    $id_prefix = 'single-';

    $loop = new WP_Query($args);
 
    return get_baf_output($atts, $loop, $id_prefix);
}

/*------ Return Post/FAQ Author Information  ---------*/

add_shortcode('bwla_author', 'bwla_author');

function bwla_author($atts) {

    global $post;

    extract(shortcode_atts(array(
        'post_id' => $post->ID
                    ), $atts));

    $bwl_advanced_faq_author = get_post_meta($post_id, "bwl_advanced_faq_author", true);

    $bwl_advanced_faq_author_name = ( $bwl_advanced_faq_author == "" ) ? esc_html__('Anonymous', 'bwl-adv-faq') : get_the_author_meta('display_name', $bwl_advanced_faq_author);

    return $bwl_advanced_faq_author_name;
}

/*------ Dynamic Sorting For Categories ---------*/

add_shortcode('bwla_dsort_faq', 'bwla_dsort_faq');

function bwla_dsort_faq($atts) {

    extract(shortcode_atts(array(
        'post_type' => 'bwl_advanced_faq',
        'meta_key' => '',
        'orderby' => 'ID',
        'order' => 'ASC',
        'limit' => -1,
        'faq_category' => '',
        'faq_topics' => '',
        'baf_sort_filter' => 'category',
        'baf_term_id' => 0,
        'paginate' => 0,
        'pag_limit' => 5,
        'sbox' => 0,
        'sbox_class' => 0,
        'search_only_title' => 0,
        'sc_excerpt' => 0,
        'schema' => 0
                    ), $atts));

    $args = array(
        'post_status' => 'publish',
        'post_type' => $post_type,
        'posts_per_page' => -1
    );

    if ($baf_sort_filter == "topics") {

        $baf_sort_prefix = PREFIX_BAF_TOPIC;
        $args['advanced_faq_topics'] = $faq_topics;
    } else {

        $baf_sort_prefix = PREFIX_BAF_CAT;
        $args['advanced_faq_category'] = $faq_category;
    }

    $loop = new WP_Query($args);
    $baf_cat_all_posts_id = array();

    if ($loop->have_posts()) :

        while ($loop->have_posts()) :

            $loop->the_post();

            $baf_cat_all_posts_id[] = get_the_ID();

        endwhile;

    endif;

    wp_reset_query();

    $args = baf_dynamic_sort_args($baf_cat_all_posts_id, $baf_sort_prefix, $baf_sort_filter, $baf_term_id, $faq_category, $limit);

    $loop = new WP_Query($args);

    return get_baf_output($atts, $loop);
}

function baf_dynamic_sort_args($baf_cat_all_posts_id, $baf_sort_prefix, $baf_sort_filter, $baf_term_id, $faq_category, $limit) {

    $baf_cat_sorted_posts_id = explode(',', get_option($baf_sort_prefix . $baf_term_id)); // call db for post meta.

    $baf_cat_final_sorted_posts_id = array_values(array_unique(array_merge($baf_cat_sorted_posts_id, $baf_cat_all_posts_id)));

    $args = array(
        'post_type' => 'bwl_advanced_faq',
        'post_status' => 'publish',
        'post__in' => $baf_cat_final_sorted_posts_id,
        'orderby' => 'post__in',
        'posts_per_page' => $limit
    );

    if ($baf_sort_filter == "topics") {

        $id_prefix = "topic-";
        $args['advanced_faq_topics'] = $faq_category;
    } else {

        $id_prefix = "category-";
        $args['advanced_faq_category'] = $faq_category;
    }

    return $args;
}

/* * *********************************************************
 * @Description: Get FAQ List Output String
 * @Since: 1.6.1
 * @Created At: 30-10-2015
 * @Last Edited AT: 30-10-2015
 * @Created By: Mahbub
 * ********************************************************* */

function get_baf_output($atts, $loop, $id_prefix = "") {

    global $post;

    extract($atts); // Fixed in version 1.6.6

    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
    $preset = 0;

    if (isset($theme_id) && $theme_id != "") {

        $theme_id = $theme_id;
        $preset = 1;
    } else if (isset($bwl_advanced_faq_options['bwl_advanced_faq_theme'])) {

        $theme_id = $bwl_advanced_faq_options['bwl_advanced_faq_theme'];
    } else {

        $theme_id = 'default';
    }

    $baf_predefined_theme_color_scheme = baf_get_theme_color_scheme($theme_id, $preset);

    // Initialize Data.

    $unique_faq_container_id = wp_rand();
    $sbox = isset($sbox) ? $sbox : 1;
    $search_only_title = isset($search_only_title) ? $search_only_title : 0;
    $sbox_class = isset($sbox_class) ? $sbox_class : '';
    $pag_limit = isset($pag_limit) ? $pag_limit : 5;
    $paginate = isset($paginate) ? $paginate : 0;
    $baf_section_class = ( isset($custom_layout) && $custom_layout != "" ) ? "ac-container" . ' ' . $custom_layout : "ac-container";

    // Color BackUp.
    $default_gradient_first_color = ( isset($baf_predefined_theme_color_scheme['first_color'])) ? trim($baf_predefined_theme_color_scheme['first_color']) : '#F7F7F7'; // Default Light BG.
    $default_gradient_second_color = ( isset($baf_predefined_theme_color_scheme['second_color'])) ? trim($baf_predefined_theme_color_scheme['second_color']) : '#FAFAFA'; // Default Light Bg.
    $default_label_text_color = ( isset($baf_predefined_theme_color_scheme['label_text_color'])) ? trim($baf_predefined_theme_color_scheme['label_text_color']) : '#777777'; // Default Light Bg.
    // For Custom Theme Color.
    $first_color = ( isset($first_color) && $first_color != "" ) ? $first_color : $default_gradient_first_color; // Deafult Gradient First Color.
    $second_color = ( isset($second_color) && $second_color != "" ) ? $second_color : $default_gradient_second_color; // Deafult Gradient Second Color.
    $label_text_color = ( isset($label_text_color) && $label_text_color != "" ) ? $label_text_color : $default_label_text_color; // Deafult Gradient Second Color.
    // Accordion Arrow.
    $default_accordion_arrow = ( isset($bwl_advanced_faq_options['bwl_advanced_fa_arrow_up']) && $bwl_advanced_faq_options['bwl_advanced_fa_arrow_up'] != "" ) ? substr($bwl_advanced_faq_options['bwl_advanced_fa_arrow_up'], 1, strlen($bwl_advanced_faq_options['bwl_advanced_fa_arrow_up'])) : 'f106';  // From Settings Page.
    // From Shortcode.
    $baf_accordion_arrow = ( isset($accordion_arrow) && $accordion_arrow != "" ) ? $accordion_arrow : $default_accordion_arrow; // Deafult Gradient Second Color.

    if (isset($faq_category) && $faq_category != "") {
        $id_prefix = "category-";
    } else if (isset($faq_topics) && $faq_topics != "") {
        $id_prefix = "topic-";
    } else if ($id_prefix == "single-") {
        $id_prefix = "single-";
        $collapse_status = ( isset($collapse) && $collapse == 1 ) ? ' baf-single-collapse' : '';
        $baf_section_class .= " single-faq-post" . $collapse_status;
        $sbox = 0;
    } else {
        $id_prefix = wp_rand();
    }

    // Start Generating FAQ Output.

    $section_faq_unique_class = ' section_baf_' . $unique_faq_container_id;
    $tag_row_open = '';

    if (isset($row_open) && $row_open != "") {
        $section_faq_unique_class .= ' baf_row_open ';
        $tag_row_open .= 'data-row_open="' . $row_open . '"';
    }

    // AIA.

    $baf_section_class .= ( isset($aia) && $aia == 1 ) ? ' aia_container' : '';

    $output = '<section class="baf_custom_style ' . $section_faq_unique_class . ' ' . $baf_section_class . '" container_id="' . $unique_faq_container_id . '" data-first_color="' . $first_color . '" data-second_color="' . $second_color . '" data-label_text_color="' . $label_text_color . '" data-accordion_arrow="' . $baf_accordion_arrow . '" ' . $tag_row_open . '  data-schema="'.$schema.'">'; //Open the container

    /*------ Get Options For Search Settings  ---------*/

    $bwl_advanced_faq_search_status = 1;

    if (isset($bwl_advanced_faq_options['bwl_advanced_faq_search_status'])) {

        $bwl_advanced_faq_search_status = $bwl_advanced_faq_options['bwl_advanced_faq_search_status'];
    }

    $output .= '<input type="hidden" id="current_page" /><input type="hidden" id="show_per_page" />  ';

    if ($bwl_advanced_faq_search_status && $sbox != 0) {

        $output .= do_shortcode('[baf_sbox sbox_id="' . $unique_faq_container_id . '" placeholder="' . $placeholder . '" paginate="' . $paginate . '" sbox_class="' . $sbox_class . '" search_only_title="' . $search_only_title . '" pag_limit="' . $pag_limit . '" baf_suggestion_status="' . $baf_suggestion_status . '" baf_suggestion_prefix="' . $baf_suggestion_prefix . '" baf_suggestion="' . $baf_suggestion . '" highlight_color="' . $highlight_color . '"  highlight_bg="' . $highlight_bg . '"  cont_ext_class="' . $cont_ext_class . '" ]');
    }

    if (isset($bwl_advanced_faq_options['bwl_collapsible_btn_status']) && $bwl_advanced_faq_options['bwl_collapsible_btn_status'] == 1) {

        if (isset($btn_ctrl) && $btn_ctrl == 1) {

            $output .= '<div class="baf-ctrl-btn"><span class="baf-expand-all"><i class="fa fa-plus"></i></span><span class="baf-collapsible-all"><i class="fa fa-minus"></i></span></div>';
        }
    }

    /*------ FAQ Post Date/Time Information ---------*/

    $bwl_advanced_faq_meta_info_status = 0;

    if (isset($bwl_advanced_faq_options['bwl_advanced_faq_meta_info_status'])) {

        $bwl_advanced_faq_meta_info_status = $bwl_advanced_faq_options['bwl_advanced_faq_meta_info_status'];
    }

    $bwl_advanced_faq_meta_info = "";
    $bwl_advanced_faq_show_date_time_interface = "";

    /*------ FAQ Author Information ---------*/

    $bwl_advanced_faq_author_info_interface = "";

    /*------Direct Post Edit Permission ---------*/

    $bwl_advanced_faq_edit_status = 0;
    $bwl_advanced_faq_edit_interface = "";

    if (is_user_logged_in()) :
        $bwl_advanced_faq_edit_status = 1;
    endif;

    /*------ Like Button Status ---------*/

    $bwl_advanced_faq_like_button_interface = "";
    $bwl_advanced_faq_like_button_status = 1;

    if (isset($bwl_advanced_faq_options['bwl_advanced_faq_like_button_status'])) {

        $bwl_advanced_faq_like_button_status = $bwl_advanced_faq_options['bwl_advanced_faq_like_button_status'];
    }

    /* ----- Logged In Requirement For Voting Status ---- */

    if (isset($bwl_advanced_faq_options['baf_logged_in_voting_status']) && $bwl_advanced_faq_options['baf_logged_in_voting_status'] == 1) {

        if (!is_user_logged_in()) {

            $bwl_advanced_faq_like_button_status = 0;
        }
    }


    if ($loop->have_posts()) :

        $counter = 1;

        while ($loop->have_posts()) :

            $loop->the_post();

            $post_id = get_the_ID();

            if ($bwl_advanced_faq_like_button_status == 1) {

                $bwl_advanced_faq_like_button_interface = bwl_get_rating_interface( $post_id );
            }

            // Get Author FAQ Author Information

            $author_id = $post->post_author;

            $bwl_advanced_faq_author = get_the_author_meta('display_name', $author_id);

            $bwl_advanced_faq_author_name = ( $bwl_advanced_faq_author == "" ) ? esc_html__('Anonymous', 'bwl-adv-faq') : $bwl_advanced_faq_author;

            $bwl_advanced_faq_author_info_interface = "<span class='fa fa-user'></span> " . $bwl_advanced_faq_author_name . " &nbsp;";

            // Get FAQ Date and Time

            $bwl_advanced_faq_show_date_time_interface = "<span class='fa fa-calendar'></span> " . get_the_date() . " &nbsp;";

            // Get FAQ Edit Link

            if ($bwl_advanced_faq_edit_status == 1 && current_user_can('edit_post', $post_id)) {
                $bwl_advanced_faq_edit_url = get_edit_post_link();
                $bwl_advanced_faq_edit_interface = '<span class="fa fa-edit"></span> <a href="' . $bwl_advanced_faq_edit_url . '" target="_blank" title="' . get_the_title() . '">' . esc_html__('Edit', 'bwl-adv-faq') . '</a>';
            }

            //Initialize ingredient customizer addon option.

            $baf_ic_info = "";
            
            if ( shortcode_exists( 'baf_ic_products' ) ) {
                global $post;
                $backup = $post; 
                $bwl_advanced_faq_meta_info = "";
                $baf_ic_info .= do_shortcode('[baf_ic_products f_id="' . $post_id . '"]');
                $post = $backup;
            }

            if ($bwl_advanced_faq_meta_info_status == 1) {

                $bwl_advanced_faq_meta_info = "<p class='bwl_meta_info'>" . $bwl_advanced_faq_author_info_interface . $bwl_advanced_faq_show_date_time_interface . $bwl_advanced_faq_edit_interface . "</p>";

            }

            $bwl_advanced_faq_meta_info.=$baf_ic_info;

            $output .= '<div class="bwl-faq-container bwl-faq-container-' . $unique_faq_container_id . '" id="faq-' . $post_id . '">' .
                    '<label class="baf_schema" for="ac-' . $id_prefix . $unique_faq_container_id . $post_id . '" label_id="ac-' . $id_prefix . $post_id . '" parent_container_id="' . $unique_faq_container_id . '">' . get_the_title() . '</label>' .
                    '<input id="ac-' . $id_prefix . $unique_faq_container_id . $post_id . '" name="accordion-1" type="checkbox" />' .
                    '<article class="ac-medium" article_id="ac-' . $id_prefix . $post_id . '">' . bwl_advanced_faq_excerpt($sc_excerpt) . $bwl_advanced_faq_meta_info . $bwl_advanced_faq_like_button_interface . '</article>' .
                    '</div>';

            $counter++;

        endwhile;

        $output .= '<div id="baf_page_navigation" class="baf_page_navigation" data-paginate="' . $paginate . '" data-pag_limit="' . $pag_limit . '"></div>';

    else :

        $output .= "<p>" . esc_html__('Sorry, No FAQ Available!', 'bwl-adv-faq') . "</p>";

    endif;

    $output .= '</section>'; //Close the container

    wp_reset_query();

    return $output;
    
}