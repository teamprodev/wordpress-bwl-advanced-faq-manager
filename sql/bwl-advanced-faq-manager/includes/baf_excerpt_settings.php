<?php

/*-------------------------------------------------------------------------- ---------*/
# Change The Default WordPress Excerpt Length
/*-------------------------------------------------------------------------- ---------*/

function bwl_advanced_faq_excerpt( $sc_excerpt = 0  ){

    $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');    
    
    if( $sc_excerpt == 1 ) {
        $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_status'] = 1;
    }
    
 
    if ( isset($bwl_advanced_faq_options['bwl_advanced_faq_excerpt_status']) && $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_status'] == 1) {

        $content = get_the_content();

        if (str_word_count($content) > $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_length']) {
            
            $baf_excerpt_read_more = apply_filters('baf_excerpt_read_more_text', esc_html__('Read More', 'bwl-adv-faq')  .' &raquo;' );

            $trimmed_content = wp_trim_words($content, $bwl_advanced_faq_options['bwl_advanced_faq_excerpt_length'], '..... <a class="read-more" href="' . esc_url( get_permalink(get_the_ID())  ) . '">' . $baf_excerpt_read_more . ' </a>');
            
        } else {

            $trimmed_content = str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));
        }

        $content = $trimmed_content;
        
    } else {

        $content = str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content()));
    }

    $content = '<div class="baf_content">' . apply_filters('baf_force_content_shortcode',$content ). '</div>';

    return $content;
}

/*-------------------------------------------------------------------------- ---------*/
# Solve apostrophe issue.
/*-------------------------------------------------------------------------- ---------*/

$texturized_text = array(
    'term_name',
    'term_description',
    'the_title',
    'the_content',
    'the_excerpt',
);

foreach($texturized_text as $text ) {
    remove_filter( $text, 'wptexturize' );
}