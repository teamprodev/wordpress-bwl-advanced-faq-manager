<?php

//Integrate sorting section in Menu//

add_action('admin_menu', 'bwl_advanced_faq_sort_page_add');

function bwl_advanced_faq_sort_page_add() {
    
    add_submenu_page('edit.php?post_type=bwl_advanced_faq', 
                                BAF_S_FAQ_TEXT . ' ' . esc_html__('Sorting', 'bwl-adv-faq'), 
                                BAF_S_FAQ_TEXT . ' ' . esc_html__('Sorting', 'bwl-adv-faq'), 
                                'edit_posts', 
                                'bwl_advanced_faq_sort', 
                                'bwl_advanced_faq_sort_page');
    
}

function bwl_advanced_faq_sort_page() {
    
    // Enqueue FAQ Sorting Style.
    wp_enqueue_style('bwl-advanced-faq-admin-style');
    
    if(isset($_GET['sort_filter']) && sanitize_text_field($_GET['sort_filter']) == "category") {
        
        $baf_filter_page = "category";
        $baf_filter_page_title = esc_html__('FAQ Sorting By Category', 'bwl-adv-faq');        
        
    } elseif(isset($_GET['sort_filter']) && sanitize_text_field($_GET['sort_filter']) == "topics") {
        
        $baf_filter_page = "topics";
        $baf_filter_page_title = esc_html__('FAQ Sorting By Topics', 'bwl-adv-faq');
        
    } else {
        
        $baf_filter_page = "all";
        $baf_filter_page_title = esc_html__('FAQ Sorting', 'bwl-adv-faq');
        
    }
    
    $baf_filter_page_subtitle = esc_html__('Sorting FAQ by drag-n-drop. Items at the top will be appear in first.' , 'bwl-adv-faq');
    
?>
<div class="wrap" id="baf_faq_sorting_container">

  <div id="icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>

  <h2><?php echo $baf_filter_page_title; ?></h2>

  <?php 
                            require_once ( __DIR__ . '/sorting-pages/part-sort-menu.php');
                        ?>

  <p id="sort-status" data-sort_subtitle="<?php echo $baf_filter_page_subtitle; ?>">
    <?php echo $baf_filter_page_subtitle; ?></p>

  <div class="faq-sort-container">

    <?php 
                            
                                if( $baf_filter_page == 'category' ) : 
                                  
                                    require_once ( __DIR__ . '/sorting-pages/part-categories-faq-sort.php');
                            
                                elseif( $baf_filter_page == 'topics' ) : 
                                    
                                    require_once ( __DIR__ . '/sorting-pages/part-topics-faq-sort.php');
                                
                                else : 
                                    
                                    require_once ( __DIR__ . '/sorting-pages/part-all-faq-sort.php');
                                
                                endif;
                             
                             ?>

    <input type="button" value="Save" class="button button-primary" id="baf_save_sorting" name="baf_save_sorting"
      data-sort_filter="<?php echo $baf_filter_page; ?>">

  </div> <!-- end .faq-sort-container  -->

</div> <!--  end .wrap  -->

<?php 
    
        
}



function baf_get_sorting_data() {
    
    $baf_sort_filter = sanitize_text_field( $_POST['baf_sort_filter'] ); // all/category/topics
    $faq_category = sanitize_text_field( $_POST['baf_category_slug'] ); // get category slug.
    $baf_term_id = sanitize_text_field( $_POST['baf_term_id'] ); // get category id
    $post_type = 'bwl_advanced_faq';
    
    $args = array(
        'post_status'       => 'publish',
        'post_type'         => $post_type,
        'posts_per_page' => -1
    );
    
    if( $baf_sort_filter == "topics" ) {
        
        $baf_sort_prefix = PREFIX_BAF_TOPIC;
        $args['advanced_faq_topics'] = $faq_category;
        
    } else {
        
        $baf_sort_prefix = PREFIX_BAF_CAT;
        $args['advanced_faq_category'] = $faq_category;
        
    }
    
    $loop = new WP_Query($args);
    $baf_cat_all_posts_id = array();
    
    $output = "";
    
    if ( $loop->have_posts() ) :
        
        while ( $loop->have_posts() ) :
        
            $loop->the_post();
            
            $baf_cat_all_posts_id[] = get_the_ID();
            
        endwhile;
        
    endif;
    
    wp_reset_query();
    
    $baf_cat_sorted_posts_id = explode(',' , get_option( $baf_sort_prefix.$baf_term_id ) ); // call db for post meta.
    
    
    if(sizeof($baf_cat_sorted_posts_id) == 0 ) {
        $baf_cat_sorted_posts_id = array();
    }
    
    $baf_cat_final_sorted_posts_id = array_values ( array_unique(array_merge($baf_cat_sorted_posts_id, $baf_cat_all_posts_id) ) );
    
    $args = array(
        'post_status'       => 'publish',
        'post__in'            => $baf_cat_final_sorted_posts_id,
        'post_type'         => $post_type,
        'orderby'             => 'post__in',
        'posts_per_page' => -1   
    );
    
    if( $baf_sort_filter == "topics" ) {
        
        $args['advanced_faq_topics'] = $faq_category;
        
    } else {
        
        $args['advanced_faq_category'] = $faq_category;
        
    }
    
    $query = new WP_Query($args);
    
    $post_data = array();
    
     if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
     
        $post_data['status'] = 1;
        $post_data['data'][] = array('post_id'=> get_the_ID(),
                                               'post_title'=> get_the_title()
                                        );
     
      endwhile; 
      
      else :
           $post_data['status'] = 0;
    endif;
    
    wp_reset_query();
    
    echo json_encode($post_data);
    
    die();
    
}

add_action('wp_ajax_baf_get_sorting_data', 'baf_get_sorting_data');
add_action('wp_ajax_nopriv_baf_get_sorting_data', 'baf_get_sorting_data');


function bwl_advanced_faq_apply_sort() {

    global $wpdb;

    $baf_sort_filter_type = sanitize_text_field( $_POST['baf_sort_filter_type'] );
    $baf_term_id = sanitize_text_field( $_POST['baf_term_id'] );
    $baf_sort_data = sanitize_text_field( $_POST['baf_sort_data'] );
    
    if ( $baf_sort_filter_type == "category") {
   
        update_option( PREFIX_BAF_CAT.$baf_term_id, $baf_sort_data );
        
    } else  if ( $baf_sort_filter_type == "topics") {
        
        $baf_topics_unique_option_id = 'baf_topics_'.$baf_term_id;
        update_option( $baf_topics_unique_option_id, $baf_sort_data );
        
    } else if ( $baf_sort_filter_type == "all") {
        
        // Save All Sorting Data (menu_order)
        
        $order = explode(',', $baf_sort_data);
        $counter = 0;

        foreach ($order as $bwl_faq_id) {

            $wpdb->update($wpdb->posts, array('menu_order' => $counter), array('ID' => $bwl_faq_id));
            $counter++;
        }
    
    } else{
         // Do nothing.
    }

    $post_data['status'] = 1;
    
    wp_reset_query();
    
    echo json_encode($post_data);
    die();
}

add_action('wp_ajax_bwl_advanced_faq_apply_sort', 'bwl_advanced_faq_apply_sort');
add_action('wp_ajax_nopriv_bwl_advanced_faq_apply_sort', 'bwl_advanced_faq_apply_sort');


function baf_custom_admin_js() {
    
    $output = '<script type="text/javascript">';
        $output .= ' var baf_text_loading = "'.esc_html__('Loading .....', 'bwl-adv-faq').'";';
        $output .= ' var baf_text_saved = "'.esc_html__('Saved', 'bwl-adv-faq').'";';
    $output .= '</script>';
    
    echo $output;
    
}

add_action('admin_head', 'baf_custom_admin_js');