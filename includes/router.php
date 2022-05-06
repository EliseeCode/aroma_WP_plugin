<?php
//test API
include(AROMA_PATH.'API/bottles.php');
//To record the pref wihen a test is made. Write in the DB
add_action( 'rest_api_init', function () {
  register_rest_route( 'aroma/v1', '/bottle/(?P<id>[\d]+)', array(
    'methods' => 'GET',
    'callback' => 'getBottle',
  ) );
  register_rest_route( 'aroma/v1', '/pref', array(
    'methods' => 'POST',
    'callback' => 'setPref',
  ) );
  register_rest_route( 'aroma/v1', '/testComment', array(
    'methods' => 'POST',
    'callback' => 'setComment',
  ) );
} );