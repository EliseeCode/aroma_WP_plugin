<?php

function wppluggin_admin_script(){
    wp_enqueue_script('aroma-admin',
    AROMA_URL.'admin/js/script.js',
    ['jquery'],
    time()
    );
}
add_action('admin_enqueue_scripts','wppluggin_admin_script');

function wppluggin_public_script(){
    wp_enqueue_script('aroma-public',
    AROMA_URL.'public/js/answerScript.js',
    ['jquery'],
    time()
    );
}
add_action('wp_enqueue_scripts','wppluggin_public_script');

function api_test() {
    wp_localize_script( 'aroma-public', 'wpApiSettings', array(
      'root' => esc_url_raw( rest_url() ),
      'nonce' => wp_create_nonce( 'wp_rest' )
  ) );
  wp_enqueue_script('aroma-public');
 }
 //add_action('in_admin_footer', 'api_test');
 add_action('wp_enqueue_scripts', 'api_test', 5);