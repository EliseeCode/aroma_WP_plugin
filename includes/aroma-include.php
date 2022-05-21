<?php
function has_user_role($check_role){
    $user = wp_get_current_user();
    if(in_array( $check_role, (array) $user->roles )){
        return true;
    }
    return false;
}

function setTagPositions( $position, $tag_id) {
    global $wpdb;
    $pos=(int)$position;
    $tag_id=(int)$tag_id;
    //Update positions
    $table_name = $wpdb->prefix . 'aroma_tags';
    $wpdb->query("UPDATE $table_name SET position=$pos WHERE tag_id=$tag_id");
  return "success";
  }