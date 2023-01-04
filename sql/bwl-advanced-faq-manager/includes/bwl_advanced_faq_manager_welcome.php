<?php

    /**
    * Render the welcome screen
    */
    function bwl_advanced_faq_welcome() {
 
        include dirname(__FILE__) . '/welcome-page.php';
    }

    /**
     * Add the welcome page to the admin menu
     */
    function bwl_advanced_faq_welcome_submenu() {

        add_submenu_page(
                'edit.php?post_type=bwl_advanced_faq', esc_html__('Thanks for Installing BWL Advanced FAQ Manager.', 'bwl-adv-faq'), esc_html__('About Plugin', 'bwl-adv-faq'), 'administrator', 'bwl-advanced-faq-welcome', 'bwl_advanced_faq_welcome'
        );
    }

    add_action('admin_menu', 'bwl_advanced_faq_welcome_submenu');