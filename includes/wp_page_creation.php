<?php
function aroma_answer_content(){
    include(AROMA_PATH."pages/aroma_answer_page.php");
        die();
}
function aroma_test_content(){
    include(AROMA_PATH."pages/CRUD_tests_page.php");
        die();
}
function aroma_test_report_content(){
    include(AROMA_PATH."pages/aroma_report.php");
        die();
}

function page_router_init()
{
    if(is_page('aroma-answers')){   
        add_filter('the_title',function(){return "answer";});
        add_filter('the_content','aroma_answer_content');
    }else if(is_page('aroma-tests')){
        add_filter('the_title',function(){return "tests";});
        add_filter('the_content','aroma_test_content');
    }else if(is_page('aroma-report')){
        add_filter('the_title',function(){return "report";});
        add_filter('the_content','aroma_test_report_content');
        add_action('wp_enqueue_scripts','wp_chart_script');
    }
}
add_action( 'wp', 'page_router_init' );

function wp_chart_script(){
    wp_register_script( 'chartJS', AROMA_URL.'node_modules/chart.js/dist/chart.min.js');
    wp_enqueue_script('chartJS');
}
