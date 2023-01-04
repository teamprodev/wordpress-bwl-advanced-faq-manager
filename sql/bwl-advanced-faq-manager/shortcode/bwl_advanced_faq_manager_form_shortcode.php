<?php

// @Description: External FAQ Submission Form.
// @Since: 1.0.2
// @Last Update: 24-02-108

add_shortcode('bwla_form','bwl_advanced_faq_front_end_form');

function bwl_advanced_faq_front_end_form($atts) {
    
    $atts = shortcode_atts(array(
        'status' => 1,
        'form_heading' => esc_html__('Add A New FAQ Question !', 'bwl-adv-faq'),
        'title_min_length' => '3',
        'title_max_length' => '100',
        'sel_cat' => '',
        'hide_cat_field' => 0,
        'cont_ext_class' => ''
    ), $atts);
    
    extract($atts);
    
    if ($status == 0 ) {
        
       return null;
        
    }
    
    wp_enqueue_script('baf-custom-scripts');    
    
    $err_baf_ext_question = sprintf( esc_html__( " Write your question. Min length %d characters & Max length %d characters !", "bwl-adv-faq"), $title_min_length, $title_max_length );
    
    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
    
    $captcha_status = 1;
    
    if ( isset( $bwl_advanced_faq_options['bwl_advanced_faq_captcha_status'] ) ) { 
        
        $captcha_status = $bwl_advanced_faq_options['bwl_advanced_faq_captcha_status'];
        
    }
 
    $login_required = TRUE; // Default we required logged in to post a new faq.
    
    if( is_user_logged_in() ) {
                
        $login_required = FALSE;

    }
    
    if ( isset( $bwl_advanced_faq_options['bwl_advanced_faq_logged_in_status'] ) ) { 
         
        if ( $bwl_advanced_faq_options['bwl_advanced_faq_logged_in_status'] == 1 ) {
            
            if( is_user_logged_in() ) {
                
                $login_required = FALSE;
                
            }            
            
        } else  {
            
            $login_required = FALSE;
            
        }
        
    }
    
   if ( $login_required == FALSE ) :
    
   $bwl_faq_categories_counter = get_categories('post_type=bwl_advanced_faq&taxonomy=advanced_faq_category&order=DESC');
 
    if( count($bwl_faq_categories_counter) == 0) {
 
        wp_insert_term(
          'General', // the term 
          'advanced_faq_category', // the taxonomy
          array(
            'description'=> 'First FAQ Category.',
            'slug' => 'general',
            'parent'=> 0
          )
        );
 
    }

    $data_string_sel_cat = "";
    $hide_cat_field_class = (isset($hide_cat_field) && $hide_cat_field == 1) ? 'baf_field baf_hidden' : 'baf_field';
    
    $bwl_faq_categories_html = '<div class="'.$hide_cat_field_class.'"><label for="cat">' . esc_html__('Category:', 'bwl-adv-faq') . '</label>';
    
        $bwl_faq_categories_args = array(
            'post_type'=> 'bwl_advanced_faq',
            'show_option_none'=> esc_html__('Category', 'bwl-adv-faq'),
            'tabindex'=> '2',
            'taxonomy'=> 'advanced_faq_category',
            'echo'=> 0,
            'hide_empty'=> 0
        );
    
        
        if( isset($sel_cat) && $sel_cat!="") {
        
            $bwl_faq_categories_args['selected'] = $sel_cat;
            
            $data_string_sel_cat.=' data-sel_cat="'.$sel_cat.'"';
        
        }
        
    
        $bwl_faq_categories_html .= wp_dropdown_categories( $bwl_faq_categories_args );
    
    $bwl_faq_categories_html .= '</div>';
    
    
    $bwl_advanced_faq_form_id = wp_rand();    
    
    if ( $captcha_status == 1 ) :
        
        $bwl_captcha_generator = '<div class="baf_field">
                                                            <label for="captcha">' . esc_html__('Captcha:', 'bwl-adv-faq') . '</label>
                                                            <input id="num1" class="sum" type="text" name="num1" value="' . rand(1,4) . '" readonly="readonly" /> +
                                                            <input id="num2" class="sum" type="text" name="num2" value="' . rand(5,9) . '" readonly="readonly" /> =
                                                            <input id="captcha" class="captcha" type="text" name="captcha" maxlength="2" tabindex="3" />
                                                            <input id="captcha_status" type="hidden" name="captcha_status" value="' . $captcha_status . '" />
                                                            <span id="spambot"> '. esc_html__('Verify Human or Spambot ?', 'bwl-adv-faq') .'</span>
                                                    </div>';    
        
    else:        
        
        $bwl_captcha_generator = '<input id="captcha_status" type="hidden" name="captcha_status" value="' . $captcha_status . '" />';    
        
    endif;
    
    
    $bwla_form_class = (isset($cont_ext_class) && $cont_ext_class !="" ) ? 'bwl-faq-form-container '. $cont_ext_class : 'bwl-faq-form-container';


    $bwla_form_body = '<section class="'.$bwla_form_class.'" id="' . $bwl_advanced_faq_form_id . '">
                    
                                        <h2>' . $form_heading . ' </h2>

                                        <div class="bwl-faq-form-message-box"></div>
                                            
                                        <form id="bwl_advanced_faq_form" class="bwl_advanced_faq_form" name="bwl_advanced_faq_form" method="post" action="#" ' . $data_string_sel_cat . '> 
                                        
                                                <div class="baf_field">
                                                    <label for="title">' . esc_html__('Question Title: ', 'bwl-adv-faq') . '</label>
                                                    <input type="text" id="title" value="" name="title" data-error_msg="' . $err_baf_ext_question . '"  data-min_length="' . $title_min_length . '" data-max_length="' . $title_max_length . '" tabindex=1 />
                                                </div>    
                                               '
                                                . $bwl_faq_categories_html . 

                                                 $bwl_captcha_generator . '

                                                <div class="baf_field">
                                                    <input type="submit" value="' . esc_html__('Submit FAQ', 'bwl-adv-faq') . '" tabindex="4" id="submit" name="submit" bwl_advanced_faq_form_id= "' . $bwl_advanced_faq_form_id . '" />
                                                </div>'

                                                . wp_nonce_field( 'baf_external_form', '_baf_form_nonce', true, false ) .
            
                                           '</form>

                                        </section>';
    else:
        
        $bwl_admin_login_url = ' <a href="' . esc_url( get_home_url() ) . '/wp-admin" target="_blank">' . esc_html__('Click Here', 'bwl-adv-faq') . '</a>';
        $bwla_form_body = '<p><i class="fa fa-info-circle"></i> ' . esc_html__("Log In is required for submitting new FAQ.", 'bwl-adv-faq') . $bwl_admin_login_url . '</p>';

    endif;
        
    return $bwla_form_body;

}

function bwl_advanced_faq_save_post_data() {
    
     if ( ! isset( $_REQUEST['_baf_form_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_baf_form_nonce'], 'baf_external_form' ) ) {
         
        $status = array(
            'bwl_faq_add_status' => 0
        );
         
     } else {
    
        $post = array(
            'post_title'            =>   sanitize_text_field( $_REQUEST['title'] ),
            'post_status'        => 'pending', // Choose: publish, preview, future, etc.
            'post_type'          => 'bwl_advanced_faq'  // Use a custom post type if you want to
        );
      
        $post_id = wp_insert_post($post);         
        
        // Fixed in version 1.6.6
        
        $faq_category = get_term_by('id', sanitize_text_field( $_REQUEST['cat'] ), 'advanced_faq_category');
        wp_set_object_terms($post_id, $faq_category->slug, 'advanced_faq_category',true);
        
        $status = array(
            'bwl_faq_add_status' =>1
        );
        
        //Send Email to administrator.
        
        $baf_options = get_option('bwl_advanced_faq_options');
        
        $bwl_send_email_status = ( isset($baf_options['bwl_advanced_email_notification_status'] ) && $baf_options['bwl_advanced_email_notification_status'] == 0 ) ? FALSE : TRUE;
        
        if ( $bwl_send_email_status == TRUE ) {
            
            $baf_email_send_to = ( isset($baf_options['bwl_advanced_notification_email_id']) && !empty( $baf_options['bwl_advanced_notification_email_id'] ) ) ? sanitize_email( $baf_options['bwl_advanced_notification_email_id'] ) : get_bloginfo( 'admin_email' );
            
            $baf_email_header_title = BAF_A_FS_EMAIL_HEADER_TITLE;
            $baf_email_reply_to = sanitize_email( BAF_A_FS_REPLY_EMAIL );
            $baf_email_subject = BAF_A_FS_EMAIL_SUBJECT;
            
            $baf_faq_url =  get_admin_url() . "post.php?post&#61;$post_id&#38;action&#61;edit";

            $baf_email_body = "<p>". esc_html__("Hello Administrator", 'bwl-adv-faq') . ",<br>" . esc_html__("A new faq has been submitted by a user.", 'bwl-adv-faq') . "</p>";         
            $baf_email_body .= "<h3>" . esc_html__("Submitted FAQ Information", 'bwl-adv-faq') . "</h3><hr />";         
                    $baf_email_body .= "<p><strong>" . esc_html__("Title", 'bwl-adv-faq') . ":</strong><br />" . sanitize_text_field( $_REQUEST['title'] ) . "</p>";            
            $baf_email_body .= "<p><strong>" . esc_html__("FAQ Status", 'bwl-adv-faq') . ":</strong> " . esc_html__("Pending", 'bwl-adv-faq') . "</p>";
                    $baf_email_body .= "<p><strong>" . esc_html__("Review FAQ", 'bwl-adv-faq') . ":</strong> " . esc_url( $baf_faq_url ) . "</p>";
            $baf_email_body .= "<p>" . esc_html__("Thank You!", 'bwl-adv-faq') . "</p>"; 
            
            $baf_email_headers[]= "From: $baf_email_header_title <$baf_email_reply_to>";
            
            add_filter( 'wp_mail_content_type', 'bwl_adv_faq_set_html_content_type' );
            
            wp_mail ( $baf_email_send_to, $baf_email_subject, $baf_email_body, $baf_email_headers );
            
            remove_filter ( 'wp_mail_content_type', 'bwl_adv_faq_set_html_content_type' );
            
        }

    }
    
    echo json_encode($status);
    
    die();
    
}

/**
* @Description: Add A filter for sending HTML email.
* @Created At: 08-04-2013
* @Last Edited AT: 30-06-2013
* @Created By: Mahbub
**/

 function bwl_adv_faq_set_html_content_type() {
   return 'text/html';
}
 
add_action('wp_ajax_bwl_advanced_faq_save_post_data', 'bwl_advanced_faq_save_post_data');

add_action( 'wp_ajax_nopriv_bwl_advanced_faq_save_post_data', 'bwl_advanced_faq_save_post_data' );