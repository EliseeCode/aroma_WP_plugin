<?php
//http://localhost:8082/wp-json/aroma/v1/bottle/1/
function getBottle( WP_REST_Request $request ) {
    global $wpdb;
    $user_id=get_current_user_id();
    $table_name = $wpdb->prefix . 'aroma_bottles';
    $bottleId=$request["id"];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$bottleId");
    foreach($result as $print) {
      $name = $print->name;
    }
    if ( empty( $result ) ) {
        return null;
    }
 
  return ["result"=>$result,"user_id"=>$user_id];
}