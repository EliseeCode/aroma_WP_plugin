<?php

function wppluggin_admin_style(){
    wp_enqueue_style('wpplugin-admin',
    AROMA_URL.'admin/css/wpplugin-admin-style.css',
    [],
    time()
    );
    wp_enqueue_style('bulma',
    AROMA_URL.'admin/css/bulma.min.css',
    [],
    time()
    );
    wp_enqueue_style('table',
    AROMA_URL.'admin/css/table.min.css',
    [],
    time()
    );
}
add_action('admin_enqueue_scripts','wppluggin_admin_style');

function wppluggin_public_style(){
    wp_enqueue_style('wpplugin-admin',
    AROMA_URL.'public/css/wpplugin-style.css',
    [],
    time()
    );
    wp_enqueue_style('bulma',
    AROMA_URL.'public/css/bulma.min.css',
    [],
    time()
    );
    wp_enqueue_style('table',
    AROMA_URL.'admin/css/table.min.css',
    [],
    time()
    );
    
}
add_action('wp_enqueue_scripts','wppluggin_public_style');