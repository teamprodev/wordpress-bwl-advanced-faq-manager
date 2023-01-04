<?php
/**
 * @Description: Shortcode Editor Button
 * @Created At: 08-04-2013
 * @Last Edited AT: 26-06-2013
 * @Created By: Mahbub
 * */
add_action('admin_init', 'baf_tinymce_shortcode_button');

function baf_tinymce_shortcode_button() {

     if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
          return;
      }

      if ( get_user_option( 'rich_editing' ) !== 'true' ) {
          return;
      }

        // WPML FIXING IN VERSION 1.6.6
        $current_lang = 'en';

        if (defined('ICL_SITEPRESS_VERSION') && isset($_GET['lang'])) {
            $current_lang = sanitize_text_field( $_GET['lang'] );
            set_transient('WPML_BAF_SELECTED_LANG', $current_lang);
        }

        add_filter('mce_external_plugins', 'add_baf_shortcode_plugin');
        add_filter('mce_buttons', 'register_baf_shortcode_button');
        
}

function register_baf_shortcode_button($buttons) {

    array_push($buttons, "baf");
    return $buttons;
}

function add_baf_shortcode_plugin($plugin_array) {
 
    $plugin_array['baf'] = plugins_url( BWL_BAFM_PLUGIN_ROOT . '/tinymce/baf_tinymce_button.js');
    return $plugin_array;
}

add_action('wp_ajax_baf_sc_content', 'baf_sc_content');
add_action('wp_ajax_nopriv_baf_sc_content', 'baf_sc_content');

function baf_sc_content() {
    ?>

    <h3>
        <?php esc_html_e('BWL Advanced FAQ Manager Shortcode Editor', 'bwl-adv-faq'); ?>
        <span class="btn_baf_editor_close">X</span>
    </h3>

    <div id="baf_editor_popup_content">

        <?php
        $faq_items_args = array(
            'post_status' => 'publish',
            'post_type' => 'bwl_advanced_faq',
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1
        );

        $faq_items = get_posts($faq_items_args);

        // WPML FIXING IN VERSION 1.6.6

        if (defined('ICL_SITEPRESS_VERSION')) {

            global $sitepress;
            $current_lang = $sitepress->get_current_language(); //save current language
            $new_lang = ( get_transient('WPML_BAF_SELECTED_LANG') != "" ) ? get_transient('WPML_BAF_SELECTED_LANG') : 'en';
            $sitepress->switch_lang($new_lang);
        }

        $faq_category_args = array(
            'taxonomy' => 'advanced_faq_category',
            'hide_empty' => 0,
            'orderby' => 'ID',
            'order' => 'ASC'
        );

        $faq_categories = get_categories($faq_category_args);

        $faq_topics_args = array(
            'taxonomy' => 'advanced_faq_topics',
            'hide_empty' => 0,
            'orderby' => 'ID',
            'order' => 'ASC'
        );

        $faq_topics = get_categories($faq_topics_args);

        if (defined('ICL_SITEPRESS_VERSION')) {

            $sitepress->switch_lang($current_lang); //restore previous language
        }
        ?>


        <div class="row">

            <label for="custom_faq_type"><?php _e('FAQ Type', 'bwl-adv-faq'); ?></label>

            <input type="radio" name="custom_faq_type" class="custom_faq_type" value="1" checked="checked"/>All&nbsp;
            <input type="radio" name="custom_faq_type" class="custom_faq_type" value="2"/>Category&nbsp;
            <input type="radio" name="custom_faq_type" class="custom_faq_type" value="3"/>Topics
            <input type="radio" name="custom_faq_type" class="custom_faq_type" value="4"/>Single FAQ

        </div>

        <hr class="bafm-shortcode-seperator"/>


        <div class="row bafm_dn" id="faq_item_container">

            <label for="faq_items"><?php _e('FAQs', 'bwl-adv-faq'); ?></label>

            <select id="faq_items" name="faq_items">

                <?php
                foreach ($faq_items as $faqs):
                    ?>        
                    <option value="<?php echo $faqs->ID ?>"><?php echo $faqs->post_title; ?></option>

                    <?php
                endforeach;

                wp_reset_query();
                ?>            

            </select>

        </div>


        <div class="row bafm_dn" id="faq_category_container">

            <label for="faq_category"><?php _e('FAQ Category', 'bwl-adv-faq'); ?></label>

            <select id="faq_category" name="faq_category">

                <?php
                foreach ($faq_categories as $category):
                    ?>        
                    <option value="<?php echo $category->slug ?>"><?php echo $category->name; ?></option>

                    <?php
                endforeach;

                wp_reset_query();
                ?>            

            </select>

        </div>

        <div class="row bafm_dn" id="faq_topics_container">

            <label for="faq_topics"><?php _e('FAQ Topics', 'bwl-adv-faq'); ?></label>

            <select id="faq_topics" name="faq_topics">

                <?php
                foreach ($faq_topics as $topics):
                    ?>        

                    <option value="<?php echo $topics->slug ?>"><?php echo $topics->name; ?></option>

                    <?php
                endforeach;

                wp_reset_query();
                ?>            

            </select>

        </div>

        <div class="row bafm_dn" id="faq_tab_container">

            <div class="faq-two-col">
                <label for="bwl_tabify"><?php _e('Show In Tab', 'bwl-adv-faq') ?></label>
                <input type="checkbox" id="bwl_tabify" name="bwl_tabify" value="1" class="bafm_checkbox" />
            </div>

            <div class="faq-two-col">

                <label for="bwl_tabify_ver" class="text-right"><?php _e('Show Items in Vertical Tab', 'bwl-adv-faq') ?></label>
                <input type="checkbox" id="bwl_tabify_ver" name="bwl_tabify_ver" value="1" class="bafm_checkbox"/>
            </div>

        </div> <!-- end row  -->

        <div class="row baf_sc_settings">
            <label for="no_of_faqs"><?php _e('Number of FAQs', 'bwl-adv-faq'); ?></label>
            <input type="text" id="no_of_faqs" name="no_of_faqs" value="" class="baf_input_small"/> <small><?php _e('e.g: Any number like 1,2,3 ', 'bwl-adv-faq'); ?></small>
        </div>

        <div class="row baf_sc_settings">
            <label for="orderby"><?php _e('Order By', 'bwl-adv-faq'); ?></label>
            <select id="orderby" name="orderby">
                <option value="" selected>- <?php _e('Select', 'bwl-adv-faq'); ?> -</option>
                <option value="ID"><?php _e('ID', 'bwl-adv-faq'); ?></option>
                <option value="title"><?php _e('Title', 'bwl-adv-faq'); ?></option>
                <option value="menu_order"><?php _e('Custom Sorting', 'bwl-adv-faq'); ?></option>
                <option value="date"><?php _e('Date', 'bwl-adv-faq'); ?></option>            
                <option value="rand"><?php _e('Random Order', 'bwl-adv-faq'); ?></option>
            </select>
        </div>

        <div class="row baf_sc_settings">
            <label for="order"><?php _e('Order Type', 'bwl-adv-faq'); ?></label>
            <select id="order" name="order">
                <option value="" selected>- <?php _e('Select', 'bwl-adv-faq'); ?> -</option>
                <option value="ASC"><?php _e('Ascending', 'bwl-adv-faq'); ?></option>
                <option value="DESC"><?php _e('Descending', 'bwl-adv-faq'); ?></option>            
            </select>
        </div>

        <div class="row baf_sc_settings">

            <label for="sbox"><?php _e('Show Search Box', 'bwl-adv-faq') ?></label>
            <input type="checkbox" id="sbox" name="sbox" value="1" class="bafm_checkbox" checked="checked"/>

        </div> <!-- end row  -->

        <div class="row baf_sc_settings">

            <label for="bwla_form"><?php _e('Add External FAQ Form', 'bwl-adv-faq') ?></label>
            <input type="checkbox" id="bwla_form" name="bwla_form" value="1" class="bafm_checkbox"/>

        </div> <!-- end row  -->

        <div class="row baf_sc_settings">
            <label for="bwla_pagination"><?php _e('Pagination', 'bwl-adv-faq') ?></label>
            <input type="checkbox" id="bwla_pagination" name="bwla_pagination" value="1" class="bafm_checkbox" checked="checked"/>
        </div> <!-- end row  -->

        <div class="row bafm_dn" id="faq_description_container">
            <label for="bwla_taxonomy_info"><?php _e('Show Description?', 'bwl-adv-faq') ?></label>
            <input type="checkbox" id="bwla_taxonomy_info" name="bwla_taxonomy_info" value="1" class="bafm_checkbox"/>
        </div> <!-- end row  -->

        <div class="row baf_sc_settings">
            <label for="bwla_item_per_page"><?php _e('Items Per Page', 'bwl-adv-faq'); ?></label>
            <input type="text" id="bwla_item_per_page" name="bwla_item_per_page"  value="5" class="baf_input_small"/> <small><?php _e('e.g: Any number like 1,2,3 ', 'bwl-adv-faq'); ?></small>
        </div>
        
        <?php if (class_exists('BAF_sba') ) : ?>
        
        <div class="row baf_sc_settings">
            <label for="bwla_schema"><?php _e('Enable FAQ Schema', 'bwl-adv-faq') ?></label>
            <input type="checkbox" id="bwla_schema" name="bwla_schema" value="1" class="bafm_checkbox"/>
        </div> <!-- end row  -->
        
        <?php endif; ?>


        <div id="baf_editor_popup_buttons">
            <input id="addShortCodebtn" name="addShortCodebtn" class="button-primary" type="button" value="Insert" />
            <input id="closeShortCodebtn" name="closeShortCodebtn" class="button" type="button" value="Close" />
        </div>

    </div>

    <?php
    die();
}