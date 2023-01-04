<?php

    /**
    * Render the settings screen
    */

    function bwl_advanced_faq_settings() {
        
        wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
    
    ?>

<div class="wrap faq-wrapper baf-option-panel">

  <h2><?php _e('BWL Advanced FAQ Manager Settings', 'bwl-adv-faq'); ?></h2>

  <?php if (isset($_GET['settings-updated']) && sanitize_text_field($_GET['settings-updated']) == 'true' ) { ?>
  <div id="message" class="updated">
    <p><strong><?php esc_html_e('Settings saved.', 'bwl-adv-faq') ?></strong></p>
  </div>
  <?php } ?>

  <form action="options.php" method="post">
    <?php settings_fields('bwl_advanced_faq_options')?>
    <?php do_settings_sections(__FILE__);?>

    <p class="submit">
      <input name="submit" type="submit" class="button-primary"
        value="<?php esc_html_e('Save Settings', 'bwl-adv-faq'); ?>" />
    </p>
  </form>

</div>

<?php
        
    }

    
    function register_settings_and_fields() {
        
        // First Parameter option group.
        // Second Parameter contain keyword. use in get_options() function.
        
        register_setting('bwl_advanced_faq_options', 'bwl_advanced_faq_options');
        
        // General Settings.        
        add_settings_section('bwl_advanced_faq_general_section', esc_html__("General Settings: ", 'bwl-adv-faq'), "bwl_advanced_faq_general_section_cb", __FILE__);        
        
        add_settings_field('bwl_advanced_faq_search_status',  esc_html__("Display Search Box: ", 'bwl-adv-faq'), "bwl_search_box_settings", __FILE__ , 'bwl_advanced_faq_general_section');
        add_settings_field('bwl_advanced_faq_meta_info_status',  esc_html__("Display Meta Info: ", 'bwl-adv-faq'), "bwl_meta_info_settings", __FILE__ , 'bwl_advanced_faq_general_section');
        
        // External FAQ Form Settings
        add_settings_section('bwl_advanced_faq_display_section', esc_html__("External FAQ Form Settings: ", 'bwl-adv-faq'), "bwl_advanced_faq_display_section_cb", __FILE__);        
        
        add_settings_field('bwl_advanced_faq_logged_in_status',  esc_html__("Require Log In To Submit FAQ: ", 'bwl-adv-faq'), "bwl_faq_logged_in_settings", __FILE__ , 'bwl_advanced_faq_display_section');
        add_settings_field('bwl_advanced_faq_captcha_status',  esc_html__("Captcha In FAQ Submit Form : ", 'bwl-adv-faq'), "bwl_faq_captcha_settings", __FILE__ , 'bwl_advanced_faq_display_section');
        add_settings_field('bwl_advanced_email_notification_status',  esc_html__("New FAQ Email Notification: ", 'bwl-adv-faq'), "bwl_faq_email_notification_settings", __FILE__ , 'bwl_advanced_faq_display_section');        
        add_settings_field('bwl_advanced_notification_email_id',  esc_html__("Notification Email ID: ", 'bwl-adv-faq'), "bwl_faq_notification_email_id_settings", __FILE__ , 'bwl_advanced_faq_display_section');        
        
        // Vote Settings.        
        add_settings_section('baf_vote_settings_section', esc_html__("Vote Settings", 'bwl-adv-faq'), "baf_vote_settings_cb", __FILE__);       
        add_settings_field('baf_logged_in_voting_status',  esc_html__("Allow Votes Only For Users: ", 'bwl-adv-faq'), "baf_logged_in_voting_status_settings", __FILE__ , 'baf_vote_settings_section');
        add_settings_field('bwl_advanced_faq_like_button_status',  esc_html__("Display Like Button: ", 'bwl-adv-faq'), "bwl_like_button_settings", __FILE__ , 'baf_vote_settings_section');
        add_settings_field('baf_repeat_vote_interval',  esc_html__("Repeat Vote Interval: ", 'bwl-adv-faq'), "baf_repeat_vote_interval_settings", __FILE__ , 'baf_vote_settings_section');  
        add_settings_field('baf_like_icon',  esc_html__("Like Icon: ", 'bwl-adv-faq'), "baf_like_icon_settings", __FILE__ , 'baf_vote_settings_section');  
        add_settings_field('baf_like_icon_color',  esc_html__("Like Icon Color: ", 'bwl-adv-faq'), "baf_like_icon_color_setting", __FILE__ , 'baf_vote_settings_section');
        add_settings_field('baf_like_icon_hover_color',  esc_html__("Like Icon Hover Color: ", 'bwl-adv-faq'), "baf_like_icon_hover_color_setting", __FILE__ , 'baf_vote_settings_section');
        
        
        // Font Settings.        
        add_settings_section('bwl_advanced_faq_custom_font_section', esc_html__("Font Settings", 'bwl-adv-faq'), "bwl_advanced_faq_custom_font_cb", __FILE__);        
        add_settings_field('bwl_advanced_label_font_size',  esc_html__("FAQ Label Font Size: ", 'bwl-adv-faq'), "bwl_faq_label_font_size_settings", __FILE__ , 'bwl_advanced_faq_custom_font_section');        
        add_settings_field('bwl_advanced_content_font_size',  esc_html__("Content Font Size: ", 'bwl-adv-faq'), "bwl_faq_content_font_size_settings", __FILE__ , 'bwl_advanced_faq_custom_font_section');        
        add_settings_field('bwl_advanced_fa_status',  esc_html__("Font Awesome: ", 'bwl-adv-faq'), "bwl_enable_fa_settings", __FILE__ , 'bwl_advanced_faq_custom_font_section');        
        add_settings_field('bwl_advanced_fa_arrow_up',  esc_html__("Label Arrow: ", 'bwl-adv-faq'), "bwl_fa_arrow_up_settings", __FILE__ , 'bwl_advanced_faq_custom_font_section');  

        // Theme Settings.
        add_settings_section('bwl_advanced_faq_custom_theme_section', esc_html__("Custom Theme Settings", 'bwl-adv-faq'), "bwl_advanced_faq_custom_theme_cb", __FILE__);
        add_settings_field('bwl_advanced_faq_theme',  esc_html__("FAQ Theme: ", 'bwl-adv-faq'), "bwl_advanced_faq_theme_settings", __FILE__ , 'bwl_advanced_faq_custom_theme_section');        
        add_settings_field('enable_custom_theme',  esc_html__("Enable Custom Theme: ", 'bwl-adv-faq'), "enable_custom_theme_setting", __FILE__ , 'bwl_advanced_faq_custom_theme_section');
        add_settings_field('gradient_first_color',  esc_html__("Label Gradient First Color: ", 'bwl-adv-faq'), "gradient_first_color_setting", __FILE__ , 'bwl_advanced_faq_custom_theme_section');
        add_settings_field('gradient_second_color',  esc_html__("Label Gradient Second Color: ", 'bwl-adv-faq'), "gradient_second_color_setting", __FILE__ , 'bwl_advanced_faq_custom_theme_section');
        add_settings_field('label_text_color',  esc_html__("Label Text Color: ", 'bwl-adv-faq'), "label_text_color_setting", __FILE__ , 'bwl_advanced_faq_custom_theme_section');
        
        // Reading Settings.        
        add_settings_section('bwl_advanced_faq_reading_section', esc_html__("Reading Settings", 'bwl-adv-faq'), "bwl_advanced_faq_excerpt_section_cb", __FILE__);        
        add_settings_field('bwl_advanced_faq_collapsible_accordion_status',  esc_html__("Collapsible Accordion: ", 'bwl-adv-faq'), "bwl_faq_collapsible_accordion_settings", __FILE__ , 'bwl_advanced_faq_reading_section');
        add_settings_field('bwl_collapsible_btn_status',  esc_html__("Expand/Collapse Button Status: ", 'bwl-adv-faq'), "bwl_expand_collapse_button_settings", __FILE__ , 'bwl_advanced_faq_reading_section');
        add_settings_field('bwl_advanced_faq_excerpt_status',  esc_html__("Excerpt Status: ", 'bwl-adv-faq'), "bwl_faq_excerpt_settings", __FILE__ , 'bwl_advanced_faq_reading_section');
        add_settings_field('bwl_advanced_faq_excerpt_length',  esc_html__("Excerpt Length: ", 'bwl-adv-faq'), "bwl_faq_excerpt_length", __FILE__ , 'bwl_advanced_faq_reading_section');
        add_settings_field('baf_disable_single_faq_status',  esc_html__("Disable Single FAQ Page? ", 'bwl-adv-faq'), "baf_disable_single_faq_status_settings", __FILE__ , 'bwl_advanced_faq_reading_section');
        
        // Advanced Settings.
        add_settings_section('bwl_advanced_faq_advanced_section', esc_html__("Advanced Settings", 'bwl-adv-faq'), "bwl_advanced_faq_advanced_section_cb", __FILE__);           
        add_settings_field('bwl_advanced_faq_custom_slug',  esc_html__("Custom Slug: ", 'bwl-adv-faq'), "bwl_faq_custom_slug_settings", __FILE__ , 'bwl_advanced_faq_advanced_section');
        add_settings_field('baf_gutenberg_status',  esc_html__("Enable Gutenberg Support? ", 'bwl-adv-faq'), "baf_gutenberg_status_settings", __FILE__ , 'bwl_advanced_faq_advanced_section');
        add_settings_field('baf_comment_status',  esc_html__("Enable Comment Support? ", 'bwl-adv-faq'), "baf_comment_status_settings", __FILE__ , 'bwl_advanced_faq_advanced_section');
        add_settings_field('bwl_advanced_faq_custom_css',  esc_html__("Custom CSS: ", 'bwl-adv-faq'), "bwl_faq_custom_css_settings", __FILE__ , 'bwl_advanced_faq_advanced_section');

    }

    function baf_vote_settings_cb() {
        // Add option Later.        
    }

    function bwl_advanced_faq_custom_font_cb() {
        // Add option Later.        
    }
    
    /**
    * @Description: FAQ Label Font Size.
    **/
    
    function bwl_faq_label_font_size_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_label_font_size  = ""; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_label_font_size'])) { 
            
            $bwl_advanced_label_font_size = $bwl_advanced_faq_options['bwl_advanced_label_font_size'];
            
        }        

        
        $bwl_advanced_content_font_size_string =  '<select name="bwl_advanced_faq_options[bwl_advanced_label_font_size]">';
        
        $bwl_advanced_content_font_size_string .='<option value="" "selected=selected"> '. esc_html__('Use Theme Font Size', 'bwl-adv-faq') . ' </option>';
        
        for( $i = 15; $i <= 72; $i++ ) {
                     
            if( $bwl_advanced_label_font_size == $i ) {

                $selected_status = "selected=selected";

            } else {

                $selected_status = "";

            }
            
            
            $bwl_advanced_content_font_size_string .='<option value="'.$i.'" ' . $selected_status . '>' . $i . ' Px</option>';
            
        }
        
        $bwl_advanced_content_font_size_string .="</select>";

        echo $bwl_advanced_content_font_size_string; 
        
        
    }
    
    /**
    * @Description: FAQ Content Font Size.
    **/
    
    function bwl_faq_content_font_size_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_content_font_size  = ""; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_content_font_size'])) { 
            
            $bwl_advanced_content_font_size = $bwl_advanced_faq_options['bwl_advanced_content_font_size'];
            
        }        

        
        $bwl_advanced_content_font_size_string =  '<select name="bwl_advanced_faq_options[bwl_advanced_content_font_size]">';
        
        $bwl_advanced_content_font_size_string .='<option value="" "selected=selected"> '. esc_html__('Use Theme Font Size', 'bwl-adv-faq') . ' </option>';
        
        for( $i = 11; $i <= 62; $i++ ) {
                     
            if( $bwl_advanced_content_font_size == $i ) {

                $selected_status = "selected=selected";

            } else {

                $selected_status = "";

            }
            
            
            $bwl_advanced_content_font_size_string .='<option value="'.$i.'" ' . $selected_status . '>' . $i . ' Px</option>';
            
        }
        
        $bwl_advanced_content_font_size_string .="</select>";

        echo $bwl_advanced_content_font_size_string; 
        
        
    }
    
    /**
    * @Description: Font Awesome Settings
    **/
    
    function bwl_enable_fa_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_fa_status  = ""; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_fa_status'])) {
            
            $bwl_advanced_fa_status = $bwl_advanced_faq_options['bwl_advanced_fa_status'];
            
        }        
        
        if( $bwl_advanced_fa_status == "on" ) {
            
            $enable_status = "checked=checked";
            
        } else {
            
            $enable_status = "";
            
        }
        
        echo '<input type="checkbox" name="bwl_advanced_faq_options[bwl_advanced_fa_status]" ' . $enable_status . '>';
        
        
    }
    
    /**
    * @Description: FAQ Label Arrow Up Settings.
    **/
    
    function bwl_fa_arrow_up_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_fa_arrow_up  = ""; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_fa_arrow_up'])) { 
            
            $bwl_advanced_fa_arrow_up = $bwl_advanced_faq_options['bwl_advanced_fa_arrow_up'];
            
        }        

        
        $bwl_advanced_fa_arrow_up_string =  '<select name="bwl_advanced_faq_options[bwl_advanced_fa_arrow_up]">';
        
        $bwl_advanced_fa_arrow_up_string .='<option value="" "selected=selected"> '. esc_html__('Select', 'bwl-adv-faq') . ' </option>';
                
        $baf_up_arrows = array(
                                        '\f062'=>'Arrow Up',
                                        '\f106'=>'Angle up',
                                        '\f102'=>'Double Angle Up',
                                        '\f0aa'=>'Circle Arrow Up',
                                        '\f0d8'=>'Caret Arrow Up',
                                        '\f077'=>'Chevron Arrow Down',
            );

        
        foreach($baf_up_arrows as $up_arrow_key=> $up_arrow_value ) :
            
            if( $bwl_advanced_fa_arrow_up == $up_arrow_key ) {

                $selected_status = "selected=selected";

            } else {

                $selected_status = "";

            }
            
            $bwl_advanced_fa_arrow_up_string .='<option value="'.$up_arrow_key.'" ' . $selected_status . '>' . $up_arrow_value . '</option>';
            
        endforeach;
        
        $bwl_advanced_fa_arrow_up_string .="</select>";

        echo $bwl_advanced_fa_arrow_up_string; 
        
    }
    
    
    function bwl_advanced_faq_custom_theme_cb() {
        // Add option Later.        
    }
    
    
    /**
    * @Description: FAQ Collapsible Accordion.
    **/
    
    function enable_custom_theme_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $enable_custom_theme  = ""; 
        
        if( isset($bwl_advanced_faq_options['enable_custom_theme'])) {
            
            $enable_custom_theme = $bwl_advanced_faq_options['enable_custom_theme'];
            
        }        
        
        if( $enable_custom_theme == "on" ) {
            
            $enable_status = "checked=checked";
            
        } else {
            
            $enable_status = "";
            
        }
        
        echo '<input type="checkbox" id="baf_enable_custom_theme" name="bwl_advanced_faq_options[enable_custom_theme]" ' . $enable_status . '>';
        
    }
    
    
    /**
    * @Description: FAQ Color Picker settings.
    **/
    
    /*----- First Color Settings -----*/
    
    function gradient_first_color_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $gradient_first_color  = "#FFFFFF"; 
        
        if( isset($bwl_advanced_faq_options['gradient_first_color'])) { 
            
                    $gradient_first_color = strtoupper( $bwl_advanced_faq_options['gradient_first_color'] );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[gradient_first_color]" id="gradient_first_color" class="medium-text ltr" value="' . sanitize_hex_color( $gradient_first_color ). '" />';        
        
    }
    
    /*----- Second Color Settings -----*/
    
    function gradient_second_color_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $gradient_second_color  = "#EAEAEA"; 
        
        if( isset($bwl_advanced_faq_options['gradient_second_color'])) { 
            
                    $gradient_second_color = strtoupper( $bwl_advanced_faq_options['gradient_second_color'] );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[gradient_second_color]" id="gradient_second_color" class="medium-text ltr" value="' . sanitize_hex_color( $gradient_second_color ). '" />';        
        
    }
    
    
    /*----- Label Text Color Setting Settings -----*/
    
    function label_text_color_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $label_text_color  = "#777777"; 
        
        if( isset($bwl_advanced_faq_options['label_text_color'])) { 
            
            $label_text_color = strtoupper( $bwl_advanced_faq_options['label_text_color'] );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[label_text_color]" id="label_text_color" class="medium-text ltr" value="' . sanitize_hex_color( $label_text_color ). '" />';        
        
    }
    
 
    
    function bwl_advanced_faq_advanced_section_cb() {
        // Add option Later.        
    }
    
  
     /**
    * @Description: FAQ Custom Slug settings.
    **/
    
    function bwl_faq_custom_slug_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_custom_slug  = "bwl-advanced-faq"; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_slug'] != "" ) {
            
            $bwl_advanced_faq_custom_slug = sanitize_text_field(trim( $bwl_advanced_faq_options['bwl_advanced_faq_custom_slug'] ) );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[bwl_advanced_faq_custom_slug]" class="regular-text all-options" value="' . strtolower( $bwl_advanced_faq_custom_slug ) . '" /> <strong>Example:</strong> http://yourdomain.com/custom-slug/faq-4/ ';        
        echo '<br /><strong>Note:</strong> You may face 404 issue after changing slug value. To solve that, Go to Settings>Permalinks. Select "Default" from Common Settings, click save. Then again select "Post name" radio button and click save. Issue will be solved.';
        
    }
    
    /**
    * @Description: Gutenberg Support
    **/
    
    function baf_gutenberg_status_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_gutenberg_status  = ""; 
        
        if( isset($bwl_advanced_faq_options['baf_gutenberg_status'])) {
            
            $baf_gutenberg_status = $bwl_advanced_faq_options['baf_gutenberg_status'];
            
        }        
        
        if( $baf_gutenberg_status == "on" ) {
            
            $enable_status = "checked=checked";
            
        } else {
            
            $enable_status = "";
            
        }
        
        echo '<input type="checkbox" name="bwl_advanced_faq_options[baf_gutenberg_status]" ' . $enable_status . '>';
        
    }
    
    /**
    * @Description: Comment Support
    **/
    
    function baf_comment_status_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_comment_status  = ""; 
        
        if( isset($bwl_advanced_faq_options['baf_comment_status'])) {
            
            $baf_comment_status = $bwl_advanced_faq_options['baf_comment_status'];
            
        }        
        
        if( $baf_comment_status == "on" ) {
            
            $enable_status = "checked=checked";
            
        } else {
            
            $enable_status = "";
            
        }
        
        echo '<input type="checkbox" name="bwl_advanced_faq_options[baf_comment_status]" ' . $enable_status . '>';
        
    }
    
    /**
    * @Description: FAQ Custom CSS settings.
    **/
    
    function bwl_faq_custom_css_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_custom_css  = ""; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_custom_css']) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_css'] != "" ) {
            
                $bwl_advanced_faq_custom_css = sanitize_textarea_field( $bwl_advanced_faq_options['bwl_advanced_faq_custom_css'] );
            
        }
        echo '<textarea  id="bwl_advanced_faq_custom_css" name="bwl_advanced_faq_options[bwl_advanced_faq_custom_css]" class="regular-text all-options">'.$bwl_advanced_faq_custom_css .'</textarea>'; 
        
    }
    
    
    function bwl_advanced_faq_excerpt_section_cb() {
        // Add option Later.        
    }
    
     /**
    * @Description: FAQ Collapsible Accordion.
    **/
    
    function bwl_faq_collapsible_accordion_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_collapsible_accordion_status  = 0; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_collapsible_accordion_status'])) {
            
            $bwl_advanced_faq_collapsible_accordion_status = $bwl_advanced_faq_options['bwl_advanced_faq_collapsible_accordion_status'];
            
        }        
        
        
        if( $bwl_advanced_faq_collapsible_accordion_status == 1 ) {
            
            $show_status = "selected=selected";
            $hide_status = "";
            $all_faq_open_status = "";
            
        }else if( $bwl_advanced_faq_collapsible_accordion_status == 2 ) {
            
            $show_status = "";
            $hide_status = "";
            $all_faq_open_status = "selected=selected";
            
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            $all_faq_open_status = "";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_collapsible_accordion_status]">	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('Select', 'bwl-adv-faq').'</option>
                    <option value="1" ' . $show_status . '>'.esc_html__('Show All FAQ Answer Closed', 'bwl-adv-faq').'</option>	 
                    <option value="2" ' . $all_faq_open_status . '>'.esc_html__('Show All FAQ Answer Opened', 'bwl-adv-faq').'</option>                    
                 </select>';
        
    }
    
    
    /**
    * @Description: FAQ Expand/Collapsible Button For Accordion.
    **/
    
    function bwl_expand_collapse_button_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_collapsible_btn_status  = 0; 
        
        if( isset($bwl_advanced_faq_options['bwl_collapsible_btn_status'])) {
            
            $bwl_collapsible_btn_status = $bwl_advanced_faq_options['bwl_collapsible_btn_status'];
            
        }
        
        if( $bwl_collapsible_btn_status == 1 ) {
            
            $show_status = "selected=selected";
            $hide_status = "";
            $all_faq_open_status = "";
            
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            $all_faq_open_status = "";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_collapsible_btn_status]">	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('Hide', 'bwl-adv-faq').'</option>
                    <option value="1" ' . $show_status . '>'.esc_html__('Show', 'bwl-adv-faq').'</option>                  
                 </select>';
        
    }
    
    
    /**
    * @Description: FAQ Excerpt settings.
    **/
    
    function bwl_faq_excerpt_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_excerpt_status  = 0; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_excerpt_status'])) {
            
            $bwl_advanced_faq_excerpt_status = $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_status'];
            
        }
        
        if($bwl_advanced_faq_excerpt_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_excerpt_status]">	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('Inactive', 'bwl-adv-faq').'</option>
                    <option value="1" ' . $show_status . '>'.esc_html__('Active', 'bwl-adv-faq').'</option>	 
                    
                 </select>';
        
    }
    
    
    /**
    * @Description: Disable Single FAQ Page Support
    **/
    
    function baf_disable_single_faq_status_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_disable_single_faq_status  = ""; 
        
        if( isset($bwl_advanced_faq_options['baf_disable_single_faq_status'])) {
            
            $baf_disable_single_faq_status = $bwl_advanced_faq_options['baf_disable_single_faq_status'];
            
        }        
        
        if( $baf_disable_single_faq_status == "on" ) {
            
            $enable_status = "checked=checked";
            
        } else {
            
            $enable_status = "";
            
        }
        
        echo '<input type="checkbox" name="bwl_advanced_faq_options[baf_disable_single_faq_status]" ' . $enable_status . '>';
        
    }
    
    
    /**
    * @Description: FAQ Repeat Vote Interval
    **/
    
    function baf_repeat_vote_interval_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_repeat_vote_interval  = 120; 
        
        if( isset($bwl_advanced_faq_options['baf_repeat_vote_interval'])) { 
            
            $baf_repeat_vote_interval = ( is_numeric($bwl_advanced_faq_options['baf_repeat_vote_interval']) && $bwl_advanced_faq_options['baf_repeat_vote_interval'] > 0 ) ? $bwl_advanced_faq_options['baf_repeat_vote_interval'] : 120; // = 2 hours  
            
        }
        
        echo '<input type="number" name="bwl_advanced_faq_options[baf_repeat_vote_interval]" class="small-text baf_numeric_field" value="' . absint( $baf_repeat_vote_interval ). '" /> <em>i.e: Default 120 (2 hours).</em>';        
        
    }
    
    
    
    
    /*----- Form Input -----*/
    
    function bwl_advanced_faq_display_section_cb() {
        // Add option Later.        
    }
    
    function bwl_advanced_faq_theme_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_theme  = 'default'; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_theme'])) { 
            
            $bwl_advanced_faq_theme = $bwl_advanced_faq_options['bwl_advanced_faq_theme'];
            
        }       
        
        $theme_scheme = array('default', 'light', 'red', 'blue', 'green', 'pink', 'orange');        
        
        $theme_output = '<select name="bwl_advanced_faq_options[bwl_advanced_faq_theme]">';
        
        foreach ($theme_scheme as $theme_key=>$theme_value) {            
            
            if($bwl_advanced_faq_theme == $theme_value) {
                
                $show_status = "selected=selected";                
                
            } else {

                $show_status = "";                

            }
            
            $theme_output .='<option value="' . $theme_value . '" ' . $show_status . '>' . ucfirst($theme_value) . ' Theme</option>';
            
        }
        
        $theme_output .= '</select>';
        
        echo $theme_output;
        
    }
    
    
    
    function bwl_advanced_faq_general_section_cb() {
        // Add option Later. 
    }
    
    /**
    * @Description: Search BOx Settings
    **/
    
    function bwl_search_box_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_search_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_search_status'])) { 
            
            $bwl_advanced_faq_search_status = $bwl_advanced_faq_options['bwl_advanced_faq_search_status'];
            
        }        
        
        
        if($bwl_advanced_faq_search_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_search_status]">	 
	<option value="1" ' . $show_status . '>'.esc_html__('Display Search Box', 'bwl-adv-faq').'</option>	 
	<option value="0" ' . $hide_status . '>'.esc_html__('Hide Search Box', 'bwl-adv-faq').'</option>
        </select>';
        
    }    
    
    /**
    * @Description: Meta Info Settings
    **/
    
    function bwl_meta_info_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_meta_info_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_meta_info_status'])) { 
            
            $bwl_advanced_faq_meta_info_status = $bwl_advanced_faq_options['bwl_advanced_faq_meta_info_status'];
            
        }
        
        
        if($bwl_advanced_faq_meta_info_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_meta_info_status]">	 
	<option value="1" ' . $show_status . '>'.esc_html__('Show', 'bwl-adv-faq').'</option>	 
	<option value="0" ' . $hide_status . '>'.esc_html__('Hide', 'bwl-adv-faq').'</option>
        </select>';
        
    }    
    
    /**
    * @Description: Like Button Enable/Disable
    **/
    
    function bwl_like_button_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_like_button_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_like_button_status'])) { 
            
            $bwl_advanced_faq_like_button_status = $bwl_advanced_faq_options['bwl_advanced_faq_like_button_status'];
            
        }
        
        
        if($bwl_advanced_faq_like_button_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_like_button_status]">	 
	<option value="1" ' . $show_status . '>'.esc_html__('Display Like Button', 'bwl-adv-faq').'</option>	 
	<option value="0" ' . $hide_status . '>'.esc_html__('Hide Like Button', 'bwl-adv-faq').'</option>
        </select>';
        
        
    }    
    
    /**
    * @Description: Like Button Enable/Disable
    **/
    
    function baf_logged_in_voting_status_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_logged_in_voting_status  = 0; 
        
        if( isset($bwl_advanced_faq_options['baf_logged_in_voting_status'])) { 
            
            $baf_logged_in_voting_status = $bwl_advanced_faq_options['baf_logged_in_voting_status'];
            
        }
        
        
        if($baf_logged_in_voting_status == 1 ) {
            $true_status = "selected=selected";
            $false_status = "";
        } else {
            
            $true_status = "";
            $false_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[baf_logged_in_voting_status]">	 
                <option value="0" ' . $false_status . '>'.esc_html__('No', 'bwl-adv-faq').'</option>
	<option value="1" ' . $true_status . '>'.esc_html__('Yes', 'bwl-adv-faq').'</option>	 
        </select>';
        
        
    }   
    
    /**
    * @Description: FAQ Excerpt Length settings.
    **/
    
    function bwl_faq_excerpt_length() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_excerpt_length  = 60; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_excerpt_length'])) { 
            
                    $bwl_advanced_faq_excerpt_length = is_numeric($bwl_advanced_faq_options['bwl_advanced_faq_excerpt_length']) ? $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_length'] : 60;
            
        }
        
        echo '<input type="number" name="bwl_advanced_faq_options[bwl_advanced_faq_excerpt_length]" class="small-text baf_numeric_field" value="' . absint( $bwl_advanced_faq_excerpt_length ). '" />';        
        
    }
    
    /**
    * @Description: FAQ Like Icon Settings
    **/
    
    function baf_like_icon_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_like_icon  = ""; 
        
        if( isset($bwl_advanced_faq_options['baf_like_icon'])) { 
            
            $baf_like_icon = $bwl_advanced_faq_options['baf_like_icon'];
            
        }        

        
        $baf_like_icon_string =  '<select name="bwl_advanced_faq_options[baf_like_icon]">';
        
        $baf_like_icon_string .='<option value="" "selected=selected"> '. esc_html__('Select', 'bwl-adv-faq') . ' </option>';
                
        $baf_up_arrows = array('fa-thumbs-o-up'=>'Transparent Thumbs Up', 
                                                                             'fa-thumbs-up'=>'Filled Thumbs Up',
                                                                             'fa-heart-o'=>'Transparent Heart',
                                                                             'fa-heart'=>'Filled Heart',
                                                                             'fa-smile-o'=>'Smile Face',
                                                                             'fa-level-up'=>'Level up',
                                                                             'fa-arrow-circle-up'=>'Circle up',
                                                                             'fa-arrow-up'=>'Arrow up',
                                                                             'fa-angle-up'=>'Angle up',
                                                                             'fa-angle-double-up'=>'Double Angle up');

        
        foreach($baf_up_arrows as $up_arrow_key=> $up_arrow_value ) :
            
            if( $baf_like_icon == $up_arrow_key ) {

                $selected_status = "selected=selected";

            } else {

                $selected_status = "";

            }
            
            $baf_like_icon_string .='<option value="'.$up_arrow_key.'" ' . $selected_status . '>' . $up_arrow_value . '</option>';
            
        endforeach;
        
        $baf_like_icon_string .="</select>";

        echo $baf_like_icon_string; 
        
    }
    
    /**
    * @Description: FAQ Like Icon Color Settings
    **/
    
    function baf_like_icon_color_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_like_icon_color  = "#228AFF"; 
        
        if( isset($bwl_advanced_faq_options['baf_like_icon_color'])) { 
            
            $baf_like_icon_color = strtoupper( $bwl_advanced_faq_options['baf_like_icon_color'] );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[baf_like_icon_color]" id="baf_like_icon_color" class="medium-text ltr" value="' . sanitize_hex_color( $baf_like_icon_color ). '" />';          
        
    }
    
    /**
    * @Description: FAQ Like Icon Hover Color Settings
    **/
    
    function baf_like_icon_hover_color_setting() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $baf_like_icon_hover_color  = "#333333"; 
        
        if( isset($bwl_advanced_faq_options['baf_like_icon_hover_color'])) { 
            
                    $baf_like_icon_hover_color = strtoupper( $bwl_advanced_faq_options['baf_like_icon_hover_color'] );
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[baf_like_icon_hover_color]" id="baf_like_icon_hover_color" class="medium-text ltr" value="' . sanitize_hex_color( $baf_like_icon_hover_color ). '" />';  
        
    }
    
    /**
    * @Description: FAQ Login required settings.
    **/
    
    function bwl_faq_logged_in_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_logged_in_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_logged_in_status'])) { 
            
            $bwl_advanced_faq_logged_in_status = $bwl_advanced_faq_options['bwl_advanced_faq_logged_in_status'];
            
        }        
        
        
        if($bwl_advanced_faq_logged_in_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_logged_in_status]">	 
                    <option value="1" ' . $show_status . '>'.esc_html__('Required (Recommended)', 'bwl-adv-faq').'</option>	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('Not Required', 'bwl-adv-faq').'</option>
                 </select>';
        
        
    }
    
    /**
    * @Description: FAQ Captcha settings.
    **/
    
    function bwl_faq_captcha_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_captcha_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_faq_captcha_status'])) { 
            
            $bwl_advanced_faq_captcha_status = $bwl_advanced_faq_options['bwl_advanced_faq_captcha_status'];
            
        }        
        
        
        if($bwl_advanced_faq_captcha_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_faq_captcha_status]">	 
                    <option value="1" ' . $show_status . '>'.esc_html__('Enable (Recommended)', 'bwl-adv-faq').'</option>	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('Disable', 'bwl-adv-faq').'</option>
                 </select>';
        
        
    }
    
    /**
    * @Description: FAQ Email Notification Settings.
    **/
    
    function bwl_faq_email_notification_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_faq_email_notification_status  = 1; 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_email_notification_status'])) { 
            
            $bwl_advanced_faq_email_notification_status = $bwl_advanced_faq_options['bwl_advanced_email_notification_status'];
            
        }        
        
        
        if($bwl_advanced_faq_email_notification_status == 1) {
            $show_status = "selected=selected";
            $hide_status = "";
        } else {
            
            $show_status = "";
            $hide_status = "selected=selected";
            
        }
        
        echo '<select name="bwl_advanced_faq_options[bwl_advanced_email_notification_status]">	 
                    <option value="1" ' . $show_status . '>'.esc_html__('Yes (Recommended)', 'bwl-adv-faq').'</option>	 
                    <option value="0" ' . $hide_status . '>'.esc_html__('No', 'bwl-adv-faq').'</option>
                 </select>';
        
        
    }
    
    /**
    * @Description: FAQ Notification Email ID Settings.
    **/
    
    function bwl_faq_notification_email_id_settings() {
        
        $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
        
        $bwl_advanced_notification_email_id  = get_bloginfo( 'admin_email' ); 
        
        if( isset($bwl_advanced_faq_options['bwl_advanced_notification_email_id']) && $bwl_advanced_faq_options['bwl_advanced_notification_email_id'] != "" ) {
            
            $bwl_advanced_notification_email_id = $bwl_advanced_faq_options['bwl_advanced_notification_email_id'];
            
        }
        
        echo '<input type="text" name="bwl_advanced_faq_options[bwl_advanced_notification_email_id]" class="regular-text all-options" value="' . sanitize_email( $bwl_advanced_notification_email_id ) . '" /> <em>i.e: Add a valid Email address !</em>' ;        
        
    }
    
    /**
     * Add the settings page to the admin menu
     */
    
    function bwl_advanced_faq_settings_submenu() {
        
        add_submenu_page(
                'edit.php?post_type=bwl_advanced_faq', esc_html__('BWL Advanced FAQ Manager Settings.', 'bwl-adv-faq'),  BAF_S_FAQ_TEXT . ' ' . esc_html__('Settings', 'bwl-adv-faq'), 'administrator', 'bwl-advanced-faq-settings', 'bwl_advanced_faq_settings'
        );        
        
    }

    add_action('admin_menu', 'bwl_advanced_faq_settings_submenu');
    
    
    add_action('admin_init', 'register_settings_and_fields');