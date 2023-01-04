<?php 

// Added in version 1.6..5

$bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

if ( is_rtl() ) {
    
    wp_enqueue_style( 'bwl-advanced-faq-rtl-admin-faq-style' );
    
}

?>

<ul class="baf-sort-menu">
    <li><a href="edit.php?post_type=bwl_advanced_faq&page=bwl_advanced_faq_sort"<?php if($baf_filter_page == "all"){ echo ' class="sort-selected"'; }?>><?php esc_html_e('All FAQs Sorting', 'bwl-adv-faq'); ?></a></li>
    <li><a href="edit.php?post_type=bwl_advanced_faq&page=bwl_advanced_faq_sort&sort_filter=category"<?php if($baf_filter_page == "category"){ echo ' class="sort-selected"'; }?>><?php esc_html_e('Category Wise FAQ Sorting', 'bwl-adv-faq'); ?></a></li>
    <li><a href="edit.php?post_type=bwl_advanced_faq&page=bwl_advanced_faq_sort&sort_filter=topics"<?php if($baf_filter_page == "topics"){ echo ' class="sort-selected"'; }?>><?php esc_html_e('Topics Wise FAQ Sorting', 'bwl-adv-faq'); ?></a></li>
</ul>