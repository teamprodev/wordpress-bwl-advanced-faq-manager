<?php

add_shortcode('bwl_tab_sbox', 'bwl_tab_sbox');

function bwl_tab_sbox($atts) {
    
    $unique_faq_container_id = wp_rand();
    $paginate = 0;
    $pag_limit = 0;
    $bwl_tab_sbox_output ='<form id="live-search" action="" class="bwl-faq-search-panel" method="post" data-paginate="' . $paginate . '" data-pag_limit="' . $pag_limit . '">
                        <fieldset>
                            <input type="text" class="search_icon text-input" value="" placeholder="' . esc_html__('Search...', 'bwl-adv-faq') . '"/>
                                <span class="baf-btn-clear baf_dn"></span>
                                <span id="bwl-filter-message-' . $unique_faq_container_id . '" class="bwl-filter-message"></span>
                        </fieldset>
                    </form>';
    
    return $bwl_tab_sbox_output;
}

add_shortcode('bwl_faq_tab', 'bwl_faq_tab');

function bwl_faq_tab($atts, $content = null) {

    extract(shortcode_atts(array(
        'title' => '',
        'link' => '',
        'target' => '',
        'vertical' => 0
                    ), $atts));

    global $single_tab_array;
    
    $single_tab_array[] = array(
        'title' => $title,
        'link' => $link,
        'content' => trim(do_shortcode($content)),
        'vertical' => $vertical
    );
}

add_shortcode('bwl_faq_tabs', 'bwl_faq_tabs');

function bwl_faq_tabs($atts, $content = null) {
    
    global $single_tab_array;
    
    // Vertical FAQ Tab: Introduced in version 1.6.3
    
     $atts = shortcode_atts(array(
                                                'vertical' => 0,
                                                'rtl' => 0
                                            ), $atts);
     
     extract($atts);
     
     $bwl_faq_tabs_ver = "";
     $bwl_faq_content_wrapper_ver = "";
     
     if ( isset($vertical) && $vertical == 1 ) {
         $bwl_faq_tabs_ver = " bwl-faq-tabs-ver";
         $bwl_faq_content_wrapper_ver = " bwl-faq-content-wrapper-ver";
     }
     

    $single_tab_array = array(); // clear the array

    $bwl_faq_tab_navigation = '<div class="bwl-faq-wrapper">';
    $bwl_faq_tab_content = "";
    $bwl_faq_tab_output = "";

    $bwl_faq_tab_navigation .= '<ul class="bwl-faq-tabs'.$bwl_faq_tabs_ver.'">';

    // execute the '[tab]' shortcode first to get the title and content - acts on global $single_tab_array
    do_shortcode($content);
    
    //declare our vars to be super clean here

    foreach ($single_tab_array as $tab => $tab_attr_array) {

        $random_id = wp_rand();

        $default = ( $tab == 0 ) ? ' class="active"' : '';

        if ($tab_attr_array['link'] != "") {

            $bwl_faq_tab_navigation .= '<li' . $default . '><a class="bwl-faq-link" href="' . $tab_attr_array["link"] . '" target="' . $tab_attr_array["target"] . '" rel="tab' . $random_id . '"><span>' . $tab_attr_array['title'] . '</span></a></li>';
        } else {

            $bwl_faq_tab_navigation .= '<li' . $default . '><a href="javascript:void(0)" rel="tab' . $random_id . '"><span>' . $tab_attr_array['title'] . '</span></a></li>';
                    $bwl_faq_tab_content .= '<div class="bwl-faq-tab-content" id="tab' . $random_id . '" ' . ( $tab != 0 ? 'style="display:none"' : '') . '>' . $tab_attr_array['content']. '</div>';
        }
    }

    $bwl_faq_tab_navigation .= '</ul><!-- .bwl-faq-tabs -->';

    $bwl_faq_tab_output = $bwl_faq_tab_navigation . '<div class="bwl-faq-content-wrapper'.$bwl_faq_content_wrapper_ver.'">' . $bwl_faq_tab_content . '</div>';
    $bwl_faq_tab_output .= '</div><!-- .tabs-wrapper -->';

    return $bwl_faq_tab_output;
    
}