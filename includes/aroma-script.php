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

    wp_localize_script( 'aroma-public', 'wpApiSettings', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ) );
    wp_enqueue_script('aroma-public');

    wp_register_script( 'Font_Awesome', 'https://kit.fontawesome.com/713e64bb36.js' );
    wp_enqueue_script('Font_Awesome');
}
add_action('wp_enqueue_scripts','wppluggin_public_script');
