<?php

define('DEVELOPER_CONTACT', 'http://codecanyon.com/user/xenioushk');

$new_version = BWL_BAF_PLUGIN_VERSION; // change version in here.

if (!defined('BWL_ADVANCED_FAQ_VERSION_KEY'))
    
    define('BWL_ADVANCED_FAQ_VERSION_KEY', 'bwl_advanced_faq_version');

if (!defined('BWL_ADVANCED_FAQ_VERSION_NO'))    
    
    define('BWL_ADVANCED_FAQ_VERSION_NO', '1.5.6');

add_option(BWL_ADVANCED_FAQ_VERSION_KEY, BWL_ADVANCED_FAQ_VERSION_NO);

if (get_option(BWL_ADVANCED_FAQ_VERSION_KEY) != $new_version) {
    // Execute your upgrade logic here

    // Then update the version value
    update_option(BWL_ADVANCED_FAQ_VERSION_KEY, $new_version);
}