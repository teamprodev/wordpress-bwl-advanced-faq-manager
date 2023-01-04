<?php

 /*------------------------------  Custom Column Section ---------*/
        
add_filter('manage_bwl_advanced_faq_posts_columns', 'bwl_advanced_faq_custom_column_header' );

function bwl_advanced_faq_custom_column_header( $columns ) {
                
     $columns['cb'] = 'cb';
     
     $columns['title'] = esc_html__('Title', 'bwl-adv-faq');
     
     $columns['advanced_faq_category'] = BAF_S_FAQ_TEXT . ' ' . esc_html__('Category', 'bwl-adv-faq');
     
     $columns['advanced_faq_topics'] = BAF_S_FAQ_TEXT . ' ' . esc_html__('Topics', 'bwl-adv-faq');
     
     $columns['post_author'] = esc_html__('Author', 'bwl-adv-faq');
     
     $columns['baf_votes_count'] = esc_html__('Votes', 'bwl-adv-faq');
     
     $columns['date'] = esc_html__('Date', 'bwl-adv-faq');
    
     return $columns;
     
 }

add_action('manage_bwl_advanced_faq_posts_custom_column', 'bwl_advanced_faq_display_custom_column');
 
function bwl_advanced_faq_display_custom_column( $column ) {
    
    global $post;
    
    switch ( $column ) {
        
        case 'advanced_faq_category':

            $faq_category = "";

            $get_faq_categories = get_the_terms( $post->ID , 'advanced_faq_category' );

            if ( is_array($get_faq_categories) && count( $get_faq_categories )>0 ) {

                foreach ( $get_faq_categories as $category ) {

                    $faq_category .= $category->name .",";

                }

                echo esc_html( substr( $faq_category, 0, strlen( $faq_category )-1 ) );

            } else {

                esc_html_e('Uncategorized', 'bwl-adv-faq');

            }

           break;
        
        case 'advanced_faq_topics':

            $faq_topics = "";

            $get_faq_topics = get_the_terms( $post->ID , 'advanced_faq_topics' );

            if ( is_array($get_faq_topics) && count( $get_faq_topics )>0 ) {

                foreach ( $get_faq_topics as $topic ) {

                    $faq_topics .= $topic->name .",";

                }

                echo esc_html( substr( $faq_topics, 0, strlen( $faq_topics )-1 ) );

            } else {

                echo esc_html( "—" );

            }

        break;
    
        case 'post_author':
            
                $author_id=$post->post_author;
                
                $bwl_advanced_faq_author = get_the_author_meta( 'display_name' , $author_id );

                $bwl_advanced_faq_author_name = ( $bwl_advanced_faq_author == "" ) ? esc_html__( 'Anonymous', 'bwl-adv-faq') : $bwl_advanced_faq_author;

                echo '<div id="bwl_advanced_faq_author-' . $post->ID . '" data-status_code="' . $author_id . '">
                            ' . $bwl_advanced_faq_author_name . '
                        </div>';

         break;
        
        
        case 'baf_votes_count':
            
            $baf_votes_count = get_post_meta($post->ID, "baf_votes_count", true)  ;
        
            $baf_votes_count = ( $baf_votes_count == "" ) ? 0 : $baf_votes_count ;
            
            echo esc_html( $baf_votes_count );
            
         break;
            
    }
}