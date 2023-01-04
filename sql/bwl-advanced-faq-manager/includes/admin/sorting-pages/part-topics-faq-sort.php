<?php
$faq_topics_args = array(
    'taxonomy' => 'advanced_faq_topics',
    'hide_empty' => 0,
    'orderby' => 'ID',
    'order' => 'ASC'
);

$faq_topics = get_categories($faq_topics_args);
wp_reset_query();
?>

<label for="baf_sort_faq_category"><?php esc_html_e('FAQ Topics', 'bwl-adv-faq'); ?></label>

<select id="baf_sort_faq_category" name="baf_sort_faq_category">
    <option value=""><?php esc_html_e('Select A Topic', 'bwl-adv-faq'); ?></option>
    <?php 

        foreach ($faq_topics as $category):
            
    ?>        
        <option value="<?php echo esc_html( $category->slug ); ?>" data-term_id="<?php echo $category->term_id?>"><?php echo esc_html( ucfirst( $category->name ) ); ?></option>

    <?php
    
        endforeach;
        
    ?>
</select>

<ul id="bwl_faq_items"></ul>