<?php
/**
 * @package eliseeAromaPlugin
 */
/*
Plugin Name: eliseeAroma plugin
Description: plugin to make Aroma test
Version: 1.0.0
*/

if(! defined('ABSPATH')){
    die;
}
define('AROMA_URL',plugin_dir_url(__FILE__));
define('AROMA_PATH',plugin_dir_path(__FILE__));
define('AROMA_BASENAME',plugin_basename(__FILE__));
define('AROMA_FILE',__FILE__);
define('AROMA_DIR',__DIR__);

include(AROMA_PATH.'includes/aroma-include.php');

include(AROMA_PATH.'includes/aroma-style.php');
include(AROMA_PATH.'includes/aroma-script.php');

//Page de création des bottles
include(AROMA_PATH.'includes/aroma-admin.php');

//Create Routers:
include(AROMA_PATH.'includes/router.php');

//Create pages
include(AROMA_PATH.'includes/wp_page_creation.php');

 