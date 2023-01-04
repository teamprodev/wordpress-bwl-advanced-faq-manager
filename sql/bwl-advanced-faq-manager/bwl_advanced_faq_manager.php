<?php
/**
* Plugin Name: BWL Advanced FAQ Manager
* Plugin URI: https://1.envato.market/baf-wp
* Description: BWL Advanced FAQ Manager is a cool frequently asked question management plugin for WordPress. This plugin allows you to create unlimited number of FAQ items and display in front end very easily.
* Author: Md Mahbub Alam Khan
* Version: 1.8.6
* Author URI: https://1.envato.market/baf-wp
* WP Requires at least: 4.6+
* Text Domain: bwl-adv-faq
*/

if (!class_exists('BWL_Advanced_Faq_Manager')) {

    class BWL_Advanced_Faq_Manager {

        function __construct() {
            
            require_once ( __DIR__ . '/includes/baf_constant.php');
            
            $this->baf_update_meta_data();
            $this->include_files();
            $this->register_post_type();
            $this->taxonomies();
            $this->baf_cau();
            $this->baf_flash_rules(); // Added in version 1.6.4
            //Proper Way To Enqueue Scripts For Front End and Admin.

            add_action('wp_enqueue_scripts', array(&$this, 'baf_enqueue_scripts'));
            add_action('admin_enqueue_scripts', array(&$this, 'baf_admin_enqueue_scripts'));

            $this->baf_update_plugins_data();
            add_filter('plugin_row_meta', array($this, 'baf_metalinks'), null, 2);
            add_action('plugins_loaded', 'bwl_adv_faq_load_textdomain');
        }
        
        function baf_update_meta_data() {
            
            $baf_update_meta_data_status = get_option('baf_update_meta_data_status');            

            if ( empty( $baf_update_meta_data_status ) ) {
                global $wpdb;
                $baf_old_votes_count_key = 'votes_count';
                $baf_new_votes_count_key = 'baf_votes_count';
                $query = "UPDATE " . $wpdb->prefix . "postmeta SET meta_key = '" . $baf_new_votes_count_key . "' WHERE meta_key = '" . $baf_old_votes_count_key . "'";
                $results = $wpdb->get_results($query, ARRAY_A);
                wp_reset_query();

                $baf_old_votes_id_key = 'voted_IP';
                $baf_new_votes_id_key = 'baf_voted_IP';
                $query = "UPDATE " . $wpdb->prefix . "postmeta SET meta_key = '" . $baf_new_votes_id_key . "' WHERE meta_key = '" . $baf_old_votes_id_key . "'";
                $results = $wpdb->get_results($query, ARRAY_A);
                wp_reset_query();

                update_option('baf_update_meta_data_status', 1); // Updated=1, 0/empty=need to update.
                
            }
            
        }
        
        public function baf_metalinks($links, $file) {
            if (strpos($file, 'bwl_advanced_faq_manager.php') !== false && is_plugin_active($file)) {

                $new_links = array(
                    '<a href="' . esc_url('https://projects.bluewindlab.net/wpplugin/baf/doc') . '" target="_blank" class="baf_doc_link">' . __('Documentation', 'bwl-adv-faq') . '</a>',
                    '<a href="' . esc_url('https://codecanyon.net/item/bwl-advanced-faq-manager/5007135/support/contact') . '" target="_blank" class="baf_support_link">' . __('Premium Support', 'bwl-adv-faq') . '</a>'
                );

                $links = array_merge($links, $new_links);
            }

            return $links;
        }

        function baf_update_plugins_data() {

            if (BWL_BAF_PLUGIN_VERSION == '1.6.4') {

                $baf_1_6_4_update_status = get_option('baf_1_6_4_update_status');

                if ($baf_1_6_4_update_status != 1) {

                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'bwl_advanced_faq',
                        'posts_per_page' => '-1'
                    );

                    $loop = new WP_Query($args);

                    if ($loop->have_posts()) :

                        while ($loop->have_posts()) :

                            $loop->the_post();

                            $post_ID = get_the_ID();

                            $bwl_advanced_faq_author = get_post_meta($post_ID, "bwl_advanced_faq_author", true);

                            $bwl_author_id = ( $bwl_advanced_faq_author == "" ) ? 1 : $bwl_advanced_faq_author;

                            $arg = array(
                                'ID' => $post_ID,
                                'post_type' => 'bwl_advanced_faq',
                                'post_author' => $bwl_author_id,
                            );

                            wp_update_post($arg);

                        endwhile;

                    endif;

                    wp_reset_query();

                    update_option('baf_1_6_4_update_status', 1);
                }
            }
        }

        function baf_enqueue_scripts() {

            $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

            // Load front end styles & scripts.

            wp_register_style('bwl-advanced-faq-theme', plugins_url( 'css/faq-style.css', __FILE__ ), array(), BWL_BAF_PLUGIN_VERSION);
            wp_enqueue_style('bwl-advanced-faq-theme');

            /*-- RTL MODE --*/

            if (is_rtl()) {

                wp_register_style('bwl-advanced-faq-rtl-style', plugins_url( 'css/rtl-faq-style.css', __FILE__ ) , array(), BWL_BAF_PLUGIN_VERSION);
                wp_enqueue_style('bwl-advanced-faq-rtl-style');
            }

            /*-- Introduce Font-Awesome In Version 1.4.9 --*/

            if (isset($bwl_advanced_faq_options['bwl_advanced_fa_status']) && $bwl_advanced_faq_options['bwl_advanced_fa_status'] == "on") {

                wp_register_style('bwl-advanced-faq-font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), BWL_BAF_PLUGIN_VERSION);
                wp_enqueue_style('bwl-advanced-faq-font-awesome');
            }

            wp_register_script('baf-text-highlight-scripts', plugins_url( 'js/baf_text_highlight.js', __FILE__), array('jquery'), BWL_BAF_PLUGIN_VERSION, TRUE);
            wp_register_script('baf-custom-scripts', plugins_url( 'js/baf-custom-scripts.js', __FILE__ ), array('jquery'), BWL_BAF_PLUGIN_VERSION, TRUE);
            wp_register_script('baf_pagination', plugins_url( 'js/baf_pagination.js', __FILE__ ), array('jquery', 'baf-text-highlight-scripts'), BWL_BAF_PLUGIN_VERSION, TRUE);
            wp_register_script('bwl-advanced-faq-filter', plugins_url( 'js/bwl_faq_filter.js', __FILE__ ), array('jquery', 'baf-text-highlight-scripts'), BWL_BAF_PLUGIN_VERSION, TRUE);

            wp_register_script('baf-schema-script', plugins_url('js/baf_schema.js', __FILE__), array('jquery'), BWL_BAF_PLUGIN_VERSION, TRUE);
            wp_enqueue_script('baf-schema-script');
        }

        function baf_admin_enqueue_scripts() {

            // Load admin styles & scripts.

            wp_register_style('bwl-advanced-faq-admin-style', plugins_url( 'css/faq-admin-style.css', __FILE__) , array('wp-color-picker'), BWL_BAF_PLUGIN_VERSION);
            wp_register_style('bwl-advanced-faq-rtl-admin-faq-style', plugins_url( 'css/rtl-admin-faq-style.css', __FILE__ ), array('wp-color-picker'), BWL_BAF_PLUGIN_VERSION);

            // TinyMCE Editor Style.

            wp_register_style('bwl-advanced-faq-editor-style', plugins_url( 'tinymce/css/bwl-advanced-faq-editor.css', __FILE__) , array(), BWL_BAF_PLUGIN_VERSION);
            wp_register_style('bwl-advanced-faq-multiple-select', plugins_url( 'tinymce/css/multiple-select.css', __FILE__ ) , array(), BWL_BAF_PLUGIN_VERSION);
            wp_register_script('bwl-advanced-faq-multiple-select', plugins_url( 'tinymce/js/jquery.multiple.select.js', __FILE__) , array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable'), BWL_BAF_PLUGIN_VERSION, TRUE);

            //Plugin FAQ Sorting Page.
            wp_register_script('baf-admin-custom-scripts', plugins_url( 'js/baf-admin-custom-scripts.js', __FILE__ ) , array('jquery', 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable'), BWL_BAF_PLUGIN_VERSION, TRUE);

            //Enqueue FAQ Admin Script & Style.

            wp_enqueue_style('bwl-advanced-faq-editor-style'); // TinyMCE Editor Overlay.
            wp_enqueue_style('bwl-advanced-faq-multiple-select'); // Enqueue Multiselect Style.
            wp_enqueue_script('bwl-advanced-faq-multiple-select'); // Enqueue Multiselect Script.

            wp_enqueue_style('bwl-advanced-faq-admin-style');
            wp_enqueue_script('baf-admin-custom-scripts');
        }

        function baf_flash_rules() {

            $baf_flash_rules_status = get_option('baf_flash_rules_status');

            if ($baf_flash_rules_status != 1) {

                flush_rewrite_rules();
                update_option('baf_flash_rules_status', 1);
            }


            // Matching Old Slug & New Slug Value.
            // First we get data from plugin option panel.

            $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

            $baf_custom_slug = "bwl-advanced-faq";

            if (isset($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_slug'] != "") {

                $baf_custom_slug = trim($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']);
            }

            $baf_old_custom_slug = get_option('baf_old_custom_slug');

            if ($baf_old_custom_slug == "") {

                update_option('baf_old_custom_slug', $baf_custom_slug);
            }


            if ($baf_custom_slug != $baf_old_custom_slug) {
                flush_rewrite_rules();
                update_option('baf_old_custom_slug', $baf_custom_slug);
            }
        }

        public function baf_cau() {          

            if (is_admin()) {

                $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

                require_once ( __DIR__ . '/includes/baf_update_notifier.php');
            }
        }

        public function include_files() {

            // Commen Functions.

            require_once ( __DIR__ . '/includes/baf_excerpt_settings.php');

            /*-- Load Required Files --*/

            if (is_admin()) {

                // Load Only Admin panel required files.
                require_once ( __DIR__ . '/includes/admin/bwl_advanced_faq_manager_sorting.php'); // INTEGRATE FAQ SORTING
                require_once ( __DIR__ . '/includes/settings/bwl_advanced_faq_manager_settings.php'); // Load plugins option panel.
                require_once ( __DIR__ . '/includes/version-manager.php'); // Load plugin versioning informations.
                require_once ( __DIR__ . '/includes/bwl_advanced_faq_manager_welcome.php'); // Load Welcome page.
                require_once ( __DIR__ . '/includes/admin/bwl_advanced_faq_manager_custom_column.php'); // Load plugin custom columns.
                require_once ( __DIR__ . '/includes/admin/bwl_advanced_faq_manager_quick_edit.php'); // Load plugin quick and bulk edit settings.
                require_once ( __DIR__ . '/includes/admin/bwl_advanced_faq_custom_filter.php'); // Load plugin custom filter by category and tags options
                require_once ( __DIR__ . '/tinymce/bwl_advanced_faq_manager_tiny_mce_config.php'); // Load Custom shrotcode editor panel.
                
            } else {

                // Load only Frontend files.

                require_once ( __DIR__ . '/includes/baf_theme_generator.php'); // Generate and Load plugin custom themes.

                /*-- INTEGRATE SHORTCODES --*/

                require_once ( __DIR__ . '/shortcode/bwl_advanced_faq_manager_shortcode.php'); // Load plugin faq shortcodes.

                require_once ( __DIR__ . '/shortcode/bwl-faq-tabify-shortcode.php');
            }

            require_once ( __DIR__ . '/shortcode/bwl_advanced_faq_manager_form_shortcode.php'); // Load plugin External Faq insertion form shortcodes.

            require_once ( __DIR__ . '/includes/bwl_advanced_faq_manager_rating.php'); // Count FAQ rating.
        }

        public function register_post_type() {

            /*
             * Custom Slug Section.
             */

            $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

            $bwl_advanced_faq_custom_slug = "bwl-advanced-faq";

            if (isset($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_slug'] != "") {

                $bwl_advanced_faq_custom_slug = trim($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']);
            }

            $labels = array(
                'name' => esc_html__('All', 'bwl-adv-faq') .' ' . BAF_P_FAQ_TEXT,
                'singular_name' => BAF_S_FAQ_TEXT,
                'add_new' => esc_html__('Add New', 'bwl-adv-faq') .' ' . BAF_S_FAQ_TEXT, 
                'add_new_item' => esc_html__('Add New', 'bwl-adv-faq') .' ' . BAF_S_FAQ_TEXT,
                'edit_item' => esc_html__('Edit', 'bwl-adv-faq') .' ' . BAF_S_FAQ_TEXT,
                'new_item' => esc_html__('New', 'bwl-adv-faq') .' ' . BAF_S_FAQ_TEXT,
                'all_items' => esc_html__('All', 'bwl-adv-faq') .' ' . BAF_P_FAQ_TEXT,
                'view_item' => esc_html__('View', 'bwl-adv-faq') .' ' . BAF_S_FAQ_TEXT,
                'search_items' => esc_html__('Search', 'bwl-adv-faq')  .' ' . BAF_S_FAQ_TEXT,
                'not_found' => esc_html__('Not found', 'bwl-adv-faq'),
                'not_found_in_trash' => esc_html__('Not found in Trash', 'bwl-adv-faq'),
                'parent_item_colon' => '',
                'menu_name' => BAF_MENU_TEXT
            );

            // Default form items supports for FAQ plugin.
            // Revisions introduced in version 1.8.2

            $baf_supports = array('title', 'editor','revisions', 'author');


            // Comment section added in version 1.8.2
            // Admin can show/hide comment permssion from plugin option panel.

            if (isset($bwl_advanced_faq_options['baf_comment_status']) && $bwl_advanced_faq_options['baf_comment_status'] == "on") {

                $baf_supports[] = 'comments';

            }
            
            // Disable Single Page Generate introduced version 1.8.2
            
            $baf_publicly_queryable = true;
            
            if (isset($bwl_advanced_faq_options['baf_disable_single_faq_status']) && $bwl_advanced_faq_options['baf_disable_single_faq_status'] == "on") {

                $baf_publicly_queryable = false;

            }
            
            
            $args = array(
                'labels' => $labels,
                'query_var' => 'advanced_faq',  
                'show_in_nav_menus' => true,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'rewrite' => array(
                    'slug' => $bwl_advanced_faq_custom_slug,
                    'with_front' => true //before it was true
                ),
                'publicly_queryable' => $baf_publicly_queryable,
                'capability_type' => 'post',
                'has_archive' => FALSE,
                'hierarchical' => true,
                'show_in_admin_bar' => true,
                'supports' => $baf_supports,
                'menu_icon' => plugins_url( 'images/faq_icon.png', __FILE__ )
            );

            register_post_type('bwl_advanced_faq', $args);
        }

        public function taxonomies() {

            /*
             * Custom Slug Section.
             */

            $bwl_advanced_faq_options = get_option('bwl_advanced_faq_options');

            $bwl_advanced_faq_custom_slug = "bwl-advanced-faq";

            if (isset($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']) && $bwl_advanced_faq_options['bwl_advanced_faq_custom_slug'] != "") {

                $bwl_advanced_faq_custom_slug = trim($bwl_advanced_faq_options['bwl_advanced_faq_custom_slug']);
            }

            $taxonomies = array();

            $taxonomies['advanced_faq_category'] = array(
                'hierarchical' => true,
                'query_var' => 'advanced_faq_category', // changed in version 1.5.9
                'rewrite' => array(
                    'slug' => $bwl_advanced_faq_custom_slug . '-category'
                ),
                'labels' => array(
                    'name' => BAF_S_FAQ_TEXT . ' ' . esc_html__(' Category', 'bwl-adv-faq'),
                    'singular_name' => esc_html__('Category', 'bwl-adv-faq'),
                    'edit_item' => esc_html__('Edit Category', 'bwl-adv-faq'),
                    'update_item' => esc_html__('Update category', 'bwl-adv-faq'),
                    'add_new_item' => esc_html__('Add Category', 'bwl-adv-faq'),
                    'new_item_name' => esc_html__('Add New category', 'bwl-adv-faq'),
                    'all_items' => esc_html__('All categories', 'bwl-adv-faq'),
                    'search_items' => esc_html__('Search categories', 'bwl-adv-faq'),
                    'popular_items' => esc_html__('Popular categories', 'bwl-adv-faq'),
                    'separate_items_with_comments' => esc_html__('Separate categories with commas', 'bwl-adv-faq'),
                    'add_or_remove_items' => esc_html__('Add or remove category', 'bwl-adv-faq'),
                    'choose_from_most_used' => esc_html__('Choose from most used categories', 'bwl-adv-faq')
                )
            );

            //  INTRODUCED CATEGORY FILTERING IN ADMIN PANEL FROM VESTION 1.4.8 VERSION

            if (is_admin()) {
                $taxonomies['advanced_faq_category']['query_var'] = TRUE;
            }

            $taxonomies['advanced_faq_topics'] = array(
                'hierarchical' => true,
                'query_var' => 'advanced_faq_topics',
                'rewrite' => array(
                    'slug' => $bwl_advanced_faq_custom_slug . '-topics'
                ),
                'labels' => array(
                    'name' => BAF_S_FAQ_TEXT . ' ' . esc_html__('Topics', 'bwl-adv-faq'),
                    'singular_name' => esc_html__('Topics', 'bwl-adv-faq'),
                    'edit_item' => esc_html__('Edit Topics', 'bwl-adv-faq'),
                    'update_item' => esc_html__('Update Topics', 'bwl-adv-faq'),
                    'add_new_item' => esc_html__('Add Topic', 'bwl-adv-faq'),
                    'new_item_name' => esc_html__('Add New Topics', 'bwl-adv-faq'),
                    'all_items' => esc_html__('All Topics', 'bwl-adv-faq'),
                    'search_items' => esc_html__('Search Topics', 'bwl-adv-faq'),
                    'popular_items' => esc_html__('Popular Topics', 'bwl-adv-faq'),
                    'separate_items_with_comments' => esc_html__('Separate Topics with commas', 'bwl-adv-faq'),
                    'add_or_remove_items' => esc_html__('Add or remove Topics', 'bwl-adv-faq'),
                    'choose_from_most_used' => esc_html__('Choose from most used Topics', 'bwl-adv-faq')
                )
            );

            //  INTRODUCED TOPICS FILTERING IN ADMIN PANEL FROM VESTION 1.4.8 VERSION

            if (is_admin()) {
                $taxonomies['advanced_faq_topics']['query_var'] = TRUE;
            }

            $this->register_all_taxonomies($taxonomies);
        }

        public function register_all_taxonomies($taxonomies) {

            foreach ($taxonomies as $name => $arr) {
                register_taxonomy($name, array('bwl_advanced_faq'), $arr);
            }
        }

    }

    /*-- INTEGRATE WIDGET --*/

    /*
     * @Integrate All Widgets.
     * @since: 1.0.0
     * @update: 1.5.7  * 
     */

    $baf_widgets = array('bwl_advanced_faq_manager_widget', 'bwl_advanced_faq_categories_widget', 'bwl_advanced_faq_topics_widget');

    foreach ($baf_widgets as $widget_key => $widget_page):
        require_once ( __DIR__ . '/widget/' . $widget_page . '.php');
    endforeach;
    
    /*-- Guternberg Support --*/
    
    require_once ( __DIR__ . '/includes/baf_gutenberg_support.php');
    
    /*-- TRANSLATION FILE --*/

    function bwl_adv_faq_load_textdomain() {

        load_plugin_textdomain('bwl-adv-faq', FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');
    }

    /*--INITIALIZATION --*/

    function bwl_advanced_faq_manager_init() {

        new BWL_Advanced_Faq_Manager();
    }

    add_action('init', 'bwl_advanced_faq_manager_init');
}