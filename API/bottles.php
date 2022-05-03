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
function setPref( WP_REST_Request $request ) {
    global $wpdb;
    $user_id=get_current_user_id();
    $bottle_id=$request["bottle_id"];
    $prefValue=$request["prefValue"];
    $test_id=$request["test_id"];
    //Allowed?
    $test_table = $wpdb->prefix . 'aroma_tests';
    $checkIfExists = $wpdb->get_results("SELECT COUNT(*) FROM $test_table WHERE creator_id = $user_id AND id=$test_id");
    if ($checkIfExists == NULL) {
        return "error";
    }


    $table_name = $wpdb->prefix . 'aroma_test_bottle_preference';
    
    $where=[
      'test_id' => $test_id,
      'bottle_id'=>$bottle_id,
    ];
    $wpdb->delete( $table_name, $where, null );
    $wpdb->insert( 
      $table_name, 
      array( 
        'time' => current_time( 'mysql' ), 
        'test_id' => $test_id,
        'preference'=> $prefValue,
        'bottle_id'=>$bottle_id
      ) 
    );

    //$result = $wpdb->get_results("SELECT * FROM $table_name WHERE id=$bottleId");
    // foreach($result as $print) {
    //   $name = $print->name;
    // }
    // if ( empty( $result ) ) {
    //     return null;
    // }
 
  return ["user_id"=>$user_id,"request"=>$request,"bottle_id"=>$bottle_id,"test_id"=>$test_id,"pref"=>$prefValue];
}