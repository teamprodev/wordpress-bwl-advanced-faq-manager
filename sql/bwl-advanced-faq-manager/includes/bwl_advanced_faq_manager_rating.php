<?php

add_action('wp_head', 'bwl_faq_set_ajax_url');

function bwl_faq_set_ajax_url() {
    
?>
<script type="text/javascript">
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>",
  err_faq_category = "<?php esc_html_e('Select FAQ Category!', 'bwl-adv-faq') ?>",
  err_faq_captcha = "<?php esc_html_e(' Incorrect Captcha Value!', 'bwl-adv-faq') ?>",
  string_total = "<?php esc_html_e('Total', 'bwl-adv-faq'); ?>",
  string_singular_page = "<?php echo apply_filters('baf_single_string',esc_html__('Page !', 'bwl-adv-faq')); ?>",
  string_plural_page = "<?php esc_html_e('Pages !', 'bwl-adv-faq'); ?>",
  string_please_wait = "<?php esc_html_e('Please Wait .....', 'bwl-adv-faq'); ?>",
  string_ques_added = "<?php esc_html_e('Question successfully added for review!', 'bwl-adv-faq'); ?>",
  string_ques_unable_add = "<?php esc_html_e('Unable to add faq. Please try again!', 'bwl-adv-faq'); ?>";

var $noting_found_text =
  "<?php echo apply_filters('baf_search_nothing_found_text',esc_html__('Nothing Found!', 'bwl-adv-faq')); ?>",
  $found_text = "<?php echo apply_filters('baf_search_found_text',esc_html__('Found', 'bwl-adv-faq')); ?>",
  $singular_faq = "<?php echo apply_filters('baf_search_singular_faq_text',esc_html__('FAQ !', 'bwl-adv-faq')); ?>",
  $plural_faq = "<?php echo apply_filters('baf_search_plural_faq_text',esc_html__('FAQs !', 'bwl-adv-faq')); ?>";
</script>

<?php

}


function bwl_check_already_voted( $post_id )  {
    
    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');
    $timebeforerevote =  ( isset($bwl_advanced_faq_options['baf_repeat_vote_interval']) && is_numeric($bwl_advanced_faq_options['baf_repeat_vote_interval']) && $bwl_advanced_faq_options['baf_repeat_vote_interval'] > 0 ) ? $bwl_advanced_faq_options['baf_repeat_vote_interval'] : 120; // = 2 hours  
  
    // Retrieve post votes IPs  
    $meta_IP = get_post_meta($post_id, "baf_voted_IP");
    
    $baf_vote_count = get_post_meta($post_id, "baf_votes_count", true);
    
    if( $baf_vote_count == "" || $baf_vote_count == 0 ) {
        return false;
    }
    
    
    if( !empty($meta_IP)) {
        
        $baf_voted_IP = $meta_IP[0];  
        
    } else {
        
         $baf_voted_IP = array();  
         
    } 
          
    // Retrieve current user IP  
    $ip = $_SERVER['REMOTE_ADDR'];  
  
    // If user has already voted  
    if ( in_array($ip, array_keys($baf_voted_IP)) ) {
        
        $time = $baf_voted_IP[$ip];
        
        $now = time();  
        
        // Compare between current time and vote time 
        
        if( round(($now - $time) / 60) > $timebeforerevote ) {
            
            return false;  
            
        }
              
        return true;  
    }  
      
    return false;  
}

function bwl_get_rating_interface($post_id)  {
    
    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

    $baf_vote_count = get_post_meta($post_id, "baf_votes_count", true);
    
    $loading_msg = esc_html__('Please Wait ...', 'bwl-adv-faq'); 
    $thumb_icon_class = ( isset($bwl_advanced_faq_options['baf_like_icon']) && $bwl_advanced_faq_options['baf_like_icon'] != "" ) ? 'fa ' . $bwl_advanced_faq_options['baf_like_icon'] : 'fa fa-thumbs-up';
    $info_icon_class = 'fa fa-info-circle';   
   
    $output = '<hr class="ultra-fancy-hr" />';    
    $output .= '<p class="post-like-container">'; 
    
    $baf_already_voted_status = ( bwl_check_already_voted( $post_id ) == true ) ? 1: 0;
    
    if ( $baf_already_voted_status ) :
        
        $output .= '<h4 class="post-like-status">'.esc_html__("You have already liked it!", 'bwl-adv-faq').'</h4><span title="I like this faq" class="post-like alreadyvoted"><i class="'.$info_icon_class.'"></i></span>';  
    
    else  :
        
        $output .= '<a href="#" data-post_id="'.$post_id.'" data-loading_msg="' . $loading_msg . '" data-info_icon="' . $info_icon_class . '"> 
                            <span class="post-like baf-vote-btn"><i class="'.$thumb_icon_class.'"></i></span> 
                        </a>';
    
    endif;
    
    
    if( ! empty( $baf_vote_count ) ) {
    
        $output .= '<span class="count">' . "$baf_vote_count ". esc_html__("people found this faq useful.", 'bwl-adv-faq') . '</span></p>';
    
    } else {
        
        $output .= '<span class="count">' . esc_html__("Be the first person to like this faq.", 'bwl-adv-faq') . '</span></p>';  
        
    }

    return $output;
    
}

function bwl_advanced_faq_apply_rating() {

     if( isset($_REQUEST['post_like']) ) {
         
         $baf_thanks_msg = esc_html__(' Thank For Your Rating!', 'bwl-adv-faq');
         $baf_already_voted_msg = esc_html__(' You have already give rating!', 'bwl-adv-faq');

        // Retrieve user IP address  
         
        $ip          = $_SERVER['REMOTE_ADDR'];
        
        $post_id  = absint( $_POST['post_id'] );
        
        $meta_IP = get_post_meta($post_id, "baf_voted_IP");  // Get voters'IPs for the current post  
        
        if (!empty($meta_IP)) {
            
            $baf_voted_IP=  $meta_IP[0];
            
        } else {
            
            $baf_voted_IP = array();
            
        }

        $baf_rate_counter = absint( get_post_meta($post_id, "baf_votes_count", true) );  

        if( ! bwl_check_already_voted($post_id) ) {
            
            $baf_voted_IP[$ip] = time();  

            // Save IP and increase votes count
            
            update_post_meta($post_id, "baf_voted_IP", $baf_voted_IP);  
            update_post_meta($post_id, "baf_votes_count", ++$baf_rate_counter);
            
            $data = array (
                'status'           => 1,
                'rate_counter' => $baf_rate_counter,
                'msg'              => $baf_thanks_msg
            );
            
        } else  {
            
             $data = array (
                'status'            => 0,
                'rate_counter'   => $baf_rate_counter,
                'msg'               => $baf_already_voted_msg
            );
             
        }
        
        echo json_encode( $data );
    }
    
    die();
    
}

add_action('wp_ajax_bwl_advanced_faq_apply_rating', 'bwl_advanced_faq_apply_rating');
add_action( 'wp_ajax_nopriv_bwl_advanced_faq_apply_rating', 'bwl_advanced_faq_apply_rating' );