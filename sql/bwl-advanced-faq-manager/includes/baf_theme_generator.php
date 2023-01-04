<?php
/**
* @Description: Custom Theme Style integrate for FAQ
* @Created At: 04-07-2013
* @Last Edited AT: 22-03-2018
* @Created By: Mahbub
 * @Note: If $preset value is 1, then we're going to skip $enable_custom_theme value.
**/

function baf_get_theme_color_scheme( $theme_id, $preset=0 ) {
 
    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
    
    $enable_custom_theme = 0;
    
    // Added $preset in version 1.6.7
    // This will allow us to show FAQ preset theme, although user enable_custom_theme option.
    if ( isset($bwl_advanced_faq_options['enable_custom_theme']) && $bwl_advanced_faq_options['enable_custom_theme'] == "on" && $preset==0 ) {         

        $enable_custom_theme = 1;
        
    }    
    
    if( $theme_id == "light" && $enable_custom_theme == 0 ) {

        //LIGHT COLOR SCHEME.
        $gradient_first_color = "#F7F7F7";
        $gradient_second_color = "#FAFAFA";
        $active_background_color = "#F7F7F7";
        $label_text_color = "#555555";
        $hover_background = "#FFFFFF";
        $label_hover_text_color = "#3a3a3a";
        $tab_top_border = "#2C2C2C";

    } else if($theme_id == "red" && $enable_custom_theme == 0 ) {

    //RED COLOR SCHEME.

    $gradient_first_color = "#FF3019";
    $gradient_second_color = "#CF0404";
    $active_background_color = "#CF0404";
    $label_text_color = "#FFFFFF";
    $hover_background = "#FF3019";
    $label_hover_text_color = "#FFFFFF";
    $tab_top_border = $gradient_first_color;
    

    } else if($theme_id == "blue" && $enable_custom_theme == 0 ) {

    //BLUE COLOR SCHEME.

    $gradient_first_color = "#49C0F0";
    $gradient_second_color = "#2CAFE3";
    $active_background_color = "#2CAFE3";
    $label_text_color = "#FFFFFF";
    $hover_background = "#49C0F0";
    $label_hover_text_color = "#FFFFFF";
    $tab_top_border = $gradient_first_color;

    } else if($theme_id == "green" && $enable_custom_theme == 0 ) {

    //GREEN COLOR SCHEME.

    $gradient_first_color = "#0EB53D";
    $gradient_second_color = "#299A0B";
    $active_background_color = "#299A0B";
    $label_text_color = "#FFFFFF";
    $hover_background = "#0EB53D";
    $label_hover_text_color = "#FFFFFF";
    $tab_top_border = $gradient_first_color;

    } else if($theme_id == "pink" && $enable_custom_theme == 0 ) {

    //PINK COLOR SCHEME.

    $gradient_first_color = "#FF5DB1";
    $gradient_second_color = "#EF017C";
    $active_background_color = "#EF017C";
    $label_text_color = "#FFFFFF";
    $hover_background = "#FF5DB1";
    $label_hover_text_color = "#FFFFFF";
    $tab_top_border = $gradient_first_color;

    } else if($theme_id == "orange" && $enable_custom_theme == 0 ) {

        //ORANGE COLOR SCHEME.

        $gradient_first_color = "#FFA84C";
        $gradient_second_color = "#FF7B0D";
        $active_background_color = "#FF7B0D";
        $label_text_color = "#FFFFFF";
        $hover_background = "#FFA84C";
        $label_hover_text_color = "#FFFFFF";
        $tab_top_border = $gradient_first_color;

    } else if( $enable_custom_theme == 1 ) {

        //CUSTOM COLOR SCHEME.

        $hover_background = "#FFA84C";

        /*------ Gradient First Color Settings ---------*/

        $gradient_first_color = "#FFA84C";

        if ( isset($bwl_advanced_faq_options['gradient_first_color']) ) {

        $gradient_first_color = $bwl_advanced_faq_options['gradient_first_color'];

        }

        /*------ Gradient Second Color Settings ---------*/

        $gradient_second_color = "#FF7B0D";

        if ( isset($bwl_advanced_faq_options['gradient_second_color']) ) {

        $gradient_second_color = $bwl_advanced_faq_options['gradient_second_color'];

        }

        /*------ LABEL TEXT COLOR SETTINGS ---------*/

        $label_text_color = "#777777";

        if ( isset($bwl_advanced_faq_options['label_text_color']) ) {

        $label_text_color = $bwl_advanced_faq_options['label_text_color'];

        }

        /*------ LABEL HOVER TEXT COLOR SETTINGS ---------*/

        $label_hover_text_color = "#777777";

        if ( isset($bwl_advanced_faq_options['label_hover_text_color']) ) {

        $label_hover_text_color = $bwl_advanced_faq_options['label_hover_text_color'];

        }

        /*------ Gradient Active Background Color Settings ---------*/

        $active_background_color = "#FF7B0D";

        if ( isset($bwl_advanced_faq_options['active_background_color']) ) {

        $active_background_color = $bwl_advanced_faq_options['active_background_color'];

        }

        $tab_top_border = $gradient_first_color;

        $hover_background = $gradient_first_color;

    } else {

        //DEFAULT COLOR SCHEME.

        $tab_top_border = "#2C2C2C";
        $gradient_first_color = "#FFFFFF";
        $gradient_second_color = "#EAEAEA";
        $active_background_color = "#C6E1EC";
        $label_text_color = "#777777";
        $hover_background = "#FFFFFF";
        $label_hover_text_color = "#777777";

    }        
        
    return array(

        'first_color' => $gradient_first_color,
        'second_color' => $gradient_second_color,
        'label_text_color' => $label_text_color

    );

}

function bwl_advanced_faq_manager_custom_theme() {
        
    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
    
    $theme_id = 'default';
    
    $bwl_advanced_faq_collapsible_accordion_status = 1; // Introduced in version 1.4.4
    
    $enable_custom_theme = 0; // Introduced in version 1.4.4
    
    $enable_rtl_support =  0; // Introduced in version 1.5.3
    
    if ( isset($bwl_advanced_faq_options['enable_custom_theme']) && $bwl_advanced_faq_options['enable_custom_theme'] == "on" ) {         

        $enable_custom_theme = 1;
        
    }
        
    if ( is_rtl() ) {

        $enable_rtl_support = 1;

    }
    
    if ( isset($bwl_advanced_faq_options['bwl_advanced_faq_theme']) ) {
         
        $theme_id =   $bwl_advanced_faq_options['bwl_advanced_faq_theme'];
        
    }
    
    if ( isset($bwl_advanced_faq_options['bwl_advanced_faq_collapsible_accordion_status']) && $bwl_advanced_faq_options['bwl_advanced_faq_collapsible_accordion_status'] != 0 ) {
         
        $bwl_advanced_faq_collapsible_accordion_status =   $bwl_advanced_faq_options['bwl_advanced_faq_collapsible_accordion_status'];
        
    }    
    
    if( $theme_id == "light" && $enable_custom_theme == 0 ) {
        
        //LIGHT COLOR SCHEME.
        $gradient_first_color = "#F7F7F7";
        $gradient_second_color = "#FAFAFA";
        $active_background_color = "#F7F7F7";
        $label_text_color = "#555555";
        $hover_background = "#FFFFFF";
        $label_hover_text_color = "#3a3a3a";        
        $tab_top_border =  "#2C2C2C";
        
    } else if($theme_id == "red" && $enable_custom_theme == 0 ) {
        
        //RED COLOR SCHEME.
        
        $gradient_first_color = "#FF3019";
        $gradient_second_color = "#CF0404";
        $active_background_color = "#CF0404";        
        $label_text_color = "#FFFFFF";
        $hover_background = "#FF3019";
        $label_hover_text_color = "#FFFFFF";
        $tab_top_border = $gradient_first_color;
        
    } else if($theme_id == "blue" && $enable_custom_theme == 0 ) {
        
        //BLUE COLOR SCHEME.
        
        $gradient_first_color = "#49C0F0";
        $gradient_second_color = "#2CAFE3";
        $active_background_color = "#2CAFE3";        
        $label_text_color = "#FFFFFF";
        $hover_background = "#49C0F0";
        $label_hover_text_color = "#FFFFFF";	
        $tab_top_border = $gradient_first_color;
        
    } else if($theme_id == "green" && $enable_custom_theme == 0 ) {
        
        //GREEN COLOR SCHEME.
        
        $gradient_first_color = "#0EB53D";
        $gradient_second_color = "#299A0B";
        $active_background_color = "#299A0B";        
        $label_text_color = "#FFFFFF";
        $hover_background = "#0EB53D";
        $label_hover_text_color = "#FFFFFF";	
        $tab_top_border = $gradient_first_color;
        
    } else if($theme_id == "pink" && $enable_custom_theme == 0 ) {
        
        //GREEN COLOR SCHEME.
        
        $gradient_first_color = "#FF5DB1";
        $gradient_second_color = "#EF017C";
        $active_background_color = "#EF017C";        
        $label_text_color = "#FFFFFF";
        $hover_background = "#FF5DB1";
        $label_hover_text_color = "#FFFFFF";	
        $tab_top_border = $gradient_first_color;
        
    } else if($theme_id == "orange" && $enable_custom_theme == 0 ) {
        
        //GREEN COLOR SCHEME.
        
        $gradient_first_color = "#FFA84C";
        $gradient_second_color = "#FF7B0D";
        $active_background_color = "#FF7B0D";
        $label_text_color = "#FFFFFF";
        $hover_background = "#FFA84C";
        $label_hover_text_color = "#FFFFFF";	
        $tab_top_border = $gradient_first_color;
        
    } else if( $enable_custom_theme == 1 ) {
        
        //CUSTOM COLOR SCHEME.
        
        $hover_background = "#FFA84C";
        
        /*------------------------------ Gradient First Color Settings ---------*/
        
        $gradient_first_color = "#FFA84C";
        
        if ( isset($bwl_advanced_faq_options['gradient_first_color']) ) {
         
            $gradient_first_color =   $bwl_advanced_faq_options['gradient_first_color'];

        }
        
        /*------------------------------ Gradient Second Color Settings ---------*/
        
        $gradient_second_color = "#FF7B0D";
        
        if ( isset($bwl_advanced_faq_options['gradient_second_color']) ) {
         
            $gradient_second_color =   $bwl_advanced_faq_options['gradient_second_color'];

        }
        
        /*------------------------------ LABEL TEXT COLOR SETTINGS ---------*/
        
        $label_text_color = "#777777";
        
         if ( isset($bwl_advanced_faq_options['label_text_color']) ) {
         
            $label_text_color =   $bwl_advanced_faq_options['label_text_color'];

        }
        
        /*------------------------------ LABEL HOVER TEXT COLOR SETTINGS ---------*/
        
        $label_hover_text_color = "#777777";
        
         if ( isset($bwl_advanced_faq_options['label_hover_text_color']) ) {
         
            $label_hover_text_color =   $bwl_advanced_faq_options['label_hover_text_color'];

        }
        
        /*------------------------------ Gradient Active Background Color Settings ---------*/
        
        $active_background_color = "#FF7B0D";
        
        if ( isset($bwl_advanced_faq_options['active_background_color']) ) {
         
            $active_background_color =   $bwl_advanced_faq_options['active_background_color'];

        }
        
        $tab_top_border = $gradient_first_color;
        
        $hover_background = $gradient_first_color;
        
    } else {
        
        //DEFAULT COLOR SCHEME.
        
        $tab_top_border =  "#2C2C2C";
        $gradient_first_color = "#FFFFFF";
        $gradient_second_color = "#EAEAEA";
        $active_background_color = "#C6E1EC";
        $label_text_color = "#777777";
        $hover_background = "#FFFFFF";
        $label_hover_text_color = "#777777";
        
    }
    
    
    if ( strtoupper( $gradient_first_color ) == "#FFFFFF") {
        
        $tab_top_border =  "#2C2C2C";
        
    }
    
    /*------------------------------Font Settings (Version : 1.4.5)  ---------*/
    
    $label_font_size = "";
    
    if ( isset( $bwl_advanced_faq_options['bwl_advanced_label_font_size'] ) && $bwl_advanced_faq_options['bwl_advanced_label_font_size'] !="" ) {
        
        $label_font_size = "font-size: " . $bwl_advanced_faq_options['bwl_advanced_label_font_size'] ."px;";
        
    }
    
    $output = "";
    
    $output .= ".ac-container label{";
    $output .= " color: " . $label_text_color .";
                       " . $label_font_size . "
                       background: " . $gradient_first_color . ";
                       background: linear-gradient(" . $gradient_first_color . " ," . $gradient_second_color . ");";    
    $output .= "}";
    
    $output .=".ac-container label:hover{
                            background: " . $gradient_first_color .";
                            color: " . $label_hover_text_color .";
                    }";
    
    
    $output .=".ac-container input:checked + label,
                   .ac-container input:checked + label:hover{
                            background: " . $gradient_first_color . ";
                            color: " . $label_text_color . ";
                      }"; 
    $output .=".ac-container input:checked + label{
                      }";
    
    $output .=".ac-container label:before, .ac-container label:after{
                        color: " . $label_text_color . ";
                      }";
    
    $output .="#baf_page_navigation .active_page{
                            background: " . $gradient_first_color .";
                            color: " . $label_text_color ." !important;
                    }";
    
    $output .="div.baf-ctrl-btn span.baf-expand-all, div.baf-ctrl-btn span.baf-collapsible-all{
                            background: " . $gradient_first_color .";
                            color: " . $label_text_color .";
                    }";
    
    $output .="div.baf-ctrl-btn span.baf-expand-all:hover, div.baf-ctrl-btn span.baf-collapsible-all:hover{
                            background: " . $gradient_second_color . ";
                            color: " . $label_text_color . ";
                    }";
    
    if ( isset( $bwl_advanced_faq_options['bwl_advanced_content_font_size'] ) && $bwl_advanced_faq_options['bwl_advanced_content_font_size'] !="" ) {
        
    // Change Font Settings: Introduced in Version: 1.4.5
    $output .=".ac-container .bwl-faq-container article div,
                    .ac-container .bwl-faq-container article p {
                            font-size: " . $bwl_advanced_faq_options['bwl_advanced_content_font_size'] . "px;
                   }";
    
    }
    
    // Change Font Settings: Introduced in Version: 1.4.5
    
    $output .=".bwl-faq-wrapper ul.bwl-faq-tabs li.active{                            
                            border-color: " . $tab_top_border .";
                   }";
    
    // Add Custom CSS CODE : Introduced in Version: 1.5.3
    
    if ( isset( $bwl_advanced_faq_options['bwl_advanced_faq_custom_css'] ) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_css'] !="" ) {
         
        $output .=  esc_html($bwl_advanced_faq_options['bwl_advanced_faq_custom_css']);
    
    }
    
    // Voting Icon.
    
    $baf_like_icon_color = '#228AFF';
    
    if ( isset($bwl_advanced_faq_options['baf_like_icon_color']) && $bwl_advanced_faq_options['baf_like_icon_color'] !="#228AFF" ) {
        
        $output .=".post-like{ color: " . $bwl_advanced_faq_options['baf_like_icon_color'] . "; }";

    }
    
    $baf_like_icon_hover_color = '#333333';
    
    if ( isset($bwl_advanced_faq_options['baf_like_icon_hover_color']) && $bwl_advanced_faq_options['baf_like_icon_hover_color'] !="#333333" ) {
        
        $output .=".post-like-container a:hover .post-like i{ color: " . $bwl_advanced_faq_options['baf_like_icon_hover_color'] . "; }";

    }
    
    
    
    // RTL STATUS.
    $baf_rtl_status = 0;
    
    if ( is_rtl() ) {

               $baf_rtl_status = 1;
               
               $output.='.ac-container .bwl-faq-search-panel span.baf-btn-clear{
                    left: 5px;
               }';

    } else {
        
              $output.='.ac-container .bwl-faq-search-panel span.baf-btn-clear{
                    right: 3px;
              }';
        
    }
 
    echo "<style type='text/css'>$output</style>";
    
    $color_scheme_output = "var baf_rtl_status = " . $baf_rtl_status . ",
                                               first_color = '" . $gradient_first_color . "',   
                                               checked_background = '" . $active_background_color . "',
                                               hover_background = '" . $hover_background . "',
                                               bwl_advanced_faq_collapsible_accordion_status = '" . $bwl_advanced_faq_collapsible_accordion_status . "',
                                               text_nothing_found = '" . esc_html__('Nothing Found !', 'bwl-adv-faq'). "',
                                               text_faqs = '" . esc_html__('FAQs', 'bwl-adv-faq'). "',
                                               text_faq = '" . esc_html__('FAQ', 'bwl-adv-faq'). "',                                               
                                               second_color = '" . $gradient_second_color. "'";
    
    echo '<script type="text/javascript">' . $color_scheme_output . '</script>';
    
}

add_action( 'wp_head', 'bwl_advanced_faq_manager_custom_theme' );