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
    AROMA_URL.'public/js/script.js',
    [],
    time()
    );
}
add_action('wp_enqueue_scripts','wppluggin_public_script');