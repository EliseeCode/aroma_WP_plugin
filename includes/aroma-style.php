<?php

function wppluggin_admin_style($hook){
    $hook=(string)$hook;
    $adminPagesHook=array("aromapsichologia_page_aromaGroupSetting",
    "toplevel_page_aromaSetting",
    "aromapsichologia_page_aromaTagSetting",
    "aromapsichologia_page_aromaDBSetting"
    );
    if(!in_array((string)$hook,(array)$adminPagesHook)){
        return;
    }
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
    if(is_page('aroma-tests')
    || is_page('aroma-answers')
    || is_page('aroma-report')){
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
}
add_action('wp_enqueue_scripts','wppluggin_public_style');