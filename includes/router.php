<?php
include(AROMA_PATH.'API/bottles.php');
add_action( 'rest_api_init', function () {
  register_rest_route( 'aroma/v1', '/bottle/(?P<id>[\d]+)', array(
    'methods' => 'GET',
    'callback' => 'getBottle',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( 'aroma/v1', '/pref', array(
    'methods' => 'POST',
    'callback' => 'setPref',
  ) );
} );