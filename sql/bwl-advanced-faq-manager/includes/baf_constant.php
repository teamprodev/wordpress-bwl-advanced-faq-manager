<?php

/*-- PLUGIN COMMON CONSTANTS --*/

define("BWL_BAF_PLUGIN_TITLE", 'BWL Advanced FAQ Manager');
define("BWL_BAFM_PLUGIN_ROOT", 'bwl-advanced-faq-manager');
define("BWL_BAFM_PLUGIN_DIR", plugins_url() . '/bwl-advanced-faq-manager/');
define("BWL_BAF_PLUGIN_VERSION", '1.8.6');
define("BWL_BAF_PLUGIN_PRODUCTION_STATUS", 0); // Change this value in to 0 in Devloper mode :)
define("PREFIX_BAF_CAT", 'baf_cat_'); // Change this value in to 0 in Devloper mode :)
define("PREFIX_BAF_TOPIC", 'baf_topics_'); // Change this value in to 0 in Devloper mode :)

/*-- EMAIL CONSTANTS --*/  
 
define('BAF_A_FS_EMAIL_HEADER_TITLE', apply_filters('baf_a_fs_email_header_title',esc_html__('New FAQ Question', 'bwl-adv-faq')));
define('BAF_A_FS_EMAIL_SUBJECT', apply_filters('baf_a_fs_email_subject',esc_html__('New FAQ submited!', 'bwl-adv-faq')));
define('BAF_A_FS_REPLY_EMAIL', apply_filters('baf_a_fs_email_subject',"no-reply@email.com"));

/*-- PLUGIN DEFAULT TEXTS --*/  
define('BAF_MENU_TEXT', apply_filters('baf_menu_text', esc_html__('Advanced FAQ', 'bwl-adv-faq') ));
define('BAF_S_FAQ_TEXT', apply_filters('baf_s_faq_text', esc_html__('FAQ', 'bwl-adv-faq') ));
define('BAF_P_FAQ_TEXT', apply_filters('baf_p_faq_text', esc_html__('FAQs', 'bwl-adv-faq') ));