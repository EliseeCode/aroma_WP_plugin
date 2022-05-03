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

//add_action('init', 'add_rewrites');
// function add_rewrites() 
// {
//   global $wp_rewrite;
//   $wp_rewrite->add_external_rule('aromaTest/$', AROMA_PATH.'pages/CRUD_tests_page.php');

// }




//API
// function api_test() {
//     wp_localize_script( 'wp-api', 'wpApiSettings', array(
//       'root' => esc_url_raw( rest_url() ),
//       'nonce' => wp_create_nonce( 'wp_rest' )
//   ) );
//   wp_enqueue_script('wp-api');
//  }
//  add_action('wp_footer', 'api_test');
 //add_action('wp_footer', 'api_test', 5);
 
// function script_that_requires_jquery() {
//     wp_register_script( 'ajaxAroma', AROMA_PATH.'/public/js/answerScript.js', array( 'jquery' ), '1.0.0', true );
//     wp_enqueue_script( 'ajaxAroma' );
// }
// add_action( 'wp_enqueue_scripts', 'script_that_requires_jquery' );


function aroma_answer_content(){
    include(AROMA_PATH."pages/aroma_answer_page.php");
        die();
}

function answerPage_init()
{
    if(is_page('answer')){   
        add_filter('the_title',function(){return "answer";});
        add_filter('the_content','aroma_answer_content');
        
    }
}

add_action( 'wp', 'answerPage_init' );
 