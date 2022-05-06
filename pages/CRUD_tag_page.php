<?php
require(AROMA_PATH.'pages/navbar.php');
global $wpdb;

$tag_table_name = $wpdb->prefix . 'aroma_tags';
$group_table_name = $wpdb->prefix . 'aroma_groups';
$group_tag_table_name = $wpdb->prefix . 'aroma_group_tag';

$page_uri="admin.php?page=aromaTagSetting";
$user_id=get_current_user_id();
//CREATE NEW
if (isset($_POST['newsubmitTag'])) {
    $name = $_POST['newname'];
    $group_id = $_POST['newgroup'];
    $wpdb->query("INSERT INTO $tag_table_name(time, name,creator_id) VALUES(NOW(),'$name',$user_id)");
    $tag_id = $wpdb->insert_id;
    $wpdb->query("INSERT INTO $group_tag_table_name(tag_id, group_id,creator_id) VALUES($tag_id,$group_id,$user_id)");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//DELETE
if (isset($_GET['delTag'])) {
    $del_id = $_GET['delTag'];
    $wpdb->query("DELETE FROM $tag_table_name WHERE id='$del_id' AND creator_id='$user_id'");
    $wpdb->query("DELETE FROM $group_tag_table_name WHERE tag_id='$del_id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }  
//UPDATE
if (isset($_POST['uptsubmitTag'])) {
    $tag_id = $_POST['uptid'];
    $name = $_POST['uptname'];
    $group_id = $_POST['uptgroup'];
    $wpdb->query("UPDATE $tag_table_name SET name='$name' WHERE id='$tag_id' AND creator_id='$user_id'");
    $wpdb->query("UPDATE $group_tag_table_name SET group_id=$group_id WHERE tag_id='$tag_id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//UPDATE FORM
if (isset($_GET['uptTag'])) {
    $upt_id = $_GET['uptTag'];

    $result = $wpdb->get_results("SELECT $group_table_name.name as group_name ,
      $tag_table_name.name as tag_name ,
      $group_table_name.id as group_id ,
      $tag_table_name.id as tag_id
      FROM $tag_table_name 
      LEFT JOIN $group_tag_table_name ON $tag_table_name.id=$group_tag_table_name.tag_id
      LEFT JOIN $group_table_name ON $group_table_name.id=$group_tag_table_name.group_id
      WHERE $tag_table_name.id='$upt_id'");

    foreach($result as $print) {
      $tag_name = $print->tag_name;
      $tag_id = $print->tag_id;
      $group_name = $print->group_name;
      $group_id = $print->group_id;
    }
    $resultGroup = $wpdb->get_results("SELECT * FROM $group_table_name
    WHERE 1 ORDER BY id DESC");
    echo "
    <div class='wrap'>
    <h2>Update Tags</h2>
    <table class='table wp-list-table striped'>
      <thead>
        <tr>
          <th >Tag Id</th>
          <th >TagName</th>
          <th >Group</th>
          <th >Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action='".$page_uri."' method='post'>
          <tr>
            <td >$tag_id<input type='hidden' id='uptid' name='uptid' value='$tag_id'></td>
            <td ><input class='input' type='text' id='uptname' name='uptname' value='$tag_name'></td>
            <td><div class='select'><select name='uptgroup'>";
            foreach ($resultGroup as $print) {
              if($print->id==$group_id){
                echo "<option value=$print->id selected>$print->name</option>";
              }
              else{
              echo "<option value=$print->id>$print->name</option>";
              }
            }
            echo "</select></div></td>
            <td ><button class='button is-primary' id='uptsubmit' name='uptsubmitTag' type='submit'>update</button><a href='".$page_uri."'><button class='button' type='button'>cancel</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }

//GET TagS
  $resultTag = $wpdb->get_results("SELECT $group_table_name.name as group_name ,
    $tag_table_name.name as tag_name ,
    $group_table_name.id as group_id ,
    $tag_table_name.id as tag_id
    FROM $tag_table_name 
    LEFT JOIN $group_tag_table_name ON $tag_table_name.id=$group_tag_table_name.tag_id
    LEFT JOIN $group_table_name ON $group_table_name.id=$group_tag_table_name.group_id
    WHERE 1 ORDER BY $tag_table_name.id DESC");

  $resultGroup = $wpdb->get_results("SELECT * FROM $group_table_name
    WHERE 1 ORDER BY id DESC");
  
?>
<div class="wrap">
    <h1>Tags</h1>
    <h2>New Tag</h2>
    <form action="" method="post">
        <tr>
            <td><input type="text" id="newname" name="newname" placeholder="Tag's name"></td>
            <td><select name="newgroup">
            <?php foreach ($resultGroup as $print) {
              echo "<option value=$print->id>$print->name</option>";
            }?>
            </select></td>
            <td><button id="newsubmit" name="newsubmitTag" type="submit">New</button></td>
        </tr>
    </form>
    <hr>
    <h2>Previous Tags</h2>
    <table class="table wp-list-table striped">
        <thead>
            <tr>
                <th >Tag ID</th>
                <th>Name</th>
                <th >Group</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($resultTag as $print) {
        echo "<tr>
                <td >$print->tag_id</td>
                <td >$print->tag_name</td>
                <td >$print->group_name</td>
                <td >
                    <a href='".$page_uri."&uptTag=$print->tag_id'>
                        <button class='button is-primary' type='button'>Edit</button>
                    </a> 
                    <a href='".$page_uri."&delTag=$print->tag_id'>
                        <button class='button is-danger' type='button'>Delete</button>
                    </a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

