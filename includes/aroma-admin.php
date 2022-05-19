<?php

/*
*Create menu button
*/
function addAdminPageContent(){
    	        add_menu_page(
                    'AromaPsichologia',
                    'AromaPsichologia',
                    'manage_options',
                    'aromaSetting',
                    'aromaAdminPage',
                    'dashicons-chart-line',
                    100
                );
}
add_action('admin_menu', 'addAdminPageContent');

function addAdminSubPageGroupContent(){
    add_submenu_page(
        'aromaSetting',
        'Groups',
        'Groups',
        'manage_options',
        'aromaGroupSetting',
        'aromaGroupPage',
        100
    );
    add_submenu_page(
        'aromaSetting',
        'Tags',
        'Tags',
        'manage_options',
        'aromaTagSetting',
        'aromaTagPage',
        100
    );
    add_submenu_page(
        'aromaSetting',
        'DBAdmin',
        'DBAdmin',
        'manage_options',
        'aromaDBSetting',
        'aromaDBPage',
        50
    );
}
add_action('admin_menu', 'addAdminSubPageGroupContent');


/*
*add admin content page
*/
function aromaDBPage(){
    if(!current_user_can('manage_options')){
        return;
    }
    include(AROMA_PATH.'pages/DBadmin.php');
}
function aromaAdminPage(){
    if(!current_user_can('manage_options')){
        return;
    }
    include(AROMA_PATH.'pages/CRUD_bottle_page.php');
}
function aromaGroupPage(){
    if(!current_user_can('manage_options')){
        return;
    }
    include(AROMA_PATH.'pages/CRUD_group_page.php');
}
function aromaTagPage(){
    if(!current_user_can('manage_options')){
        return;
    }
    include(AROMA_PATH.'pages/CRUD_tag_page.php');
}


/*
*Add link in plugin menu to redirect admin to settings from plugin page
*/
function aroma_add_setting_link($links)
{
    $setting_link='<a href="admin.php?page=aromaSetting">'.__('Settings','aroma').'</a>';
    array_push($links,$setting_link);
    return $links;
}

$filter_name='plugin_action_links_'.AROMA_BASENAME;
add_filter($filter_name,'aroma_add_setting_link');

/*
*Add Database Table (bottles) during activation
*/
include(AROMA_PATH.'includes/databaseSetup.php');

register_activation_hook( AROMA_FILE, 'tables_install' );
register_activation_hook( AROMA_FILE, 'tables_install_data' );

//Uninstall
//register_deactivation_hook( AROMA_FILE, 'tables_delete' );
//ON delete
 if( defined( 'WP_UNINSTALL_PLUGIN' ) ){
     tables_delete();
 }