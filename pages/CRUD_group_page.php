<?php
global $wpdb;
$table_name = $wpdb->prefix . 'aroma_groups';
$page_uri="admin.php?page=aromaGroupSetting";
$user_id=get_current_user_id();
//CREATE NEW
if (isset($_POST['newsubmitGroup'])) {
    $name = $_POST['newname'];
    $wpdb->query("INSERT INTO $table_name(time, name,creator_id) VALUES(NOW(),'$name',$user_id)");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//DELETE
if (isset($_GET['delGroup'])) {
    $del_id = $_GET['delGroup'];
    $wpdb->query("DELETE FROM $table_name WHERE id='$del_id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }  
//UPDATE
if (isset($_POST['uptsubmitGroup'])) {
    $id = $_POST['uptid'];
    $name = $_POST['uptname'];
    $wpdb->query("UPDATE $table_name SET name='$name' WHERE id='$id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//UPDATE FORM
if (isset($_GET['uptGroup'])) {
    $upt_id = $_GET['uptGroup'];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id' AND creator_id='$user_id'");
    foreach($result as $print) {
      $name = $print->name;
    }
    echo "
    <div class='wrap'>
    <h2>Update Groups</h2>
    <table class='wp-list-table widefat striped'>
      <thead>
        <tr>
          <th width='5%'>Group Id</th>
          <th width='25%'>Date</th>
          <th width='25%'>Name</th>
          <th width='25%'>Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action='".$page_uri."' method='post'>
          <tr>
            <td width='5%'>$print->id<input type='hidden' id='uptid' name='uptid' value='$print->id'></td>
            <td width='25%'><input type='text' id='uptname' name='uptname' value='$print->name'></td>
            <td width='25%'><button id='uptsubmit' name='uptsubmitGroup' type='submit'>update</button><a href='".$page_uri."'><button type='button'>cancel</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }

//GET GroupS
  $result = $wpdb->get_results("SELECT * FROM $table_name WHERE creator_id='$user_id' ORDER BY id DESC");
  
?>
<div class="wrap">
    <h1>Groups</h1>
    <h2>New Group</h2>
    <form action="" method="post">
        <tr>
            <td><input type="text" id="newname" name="newname" placeholder="group's name"></td>
            <td><button id="newsubmit" name="newsubmitGroup" type="submit">New</button></td>
        </tr>
    </form>
    <hr>
    <h2>Previous Groups</h2>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th width="5%">Group ID</th>
                <th width="25%">Date</th>
                <th width="25%">Name</th>
                <th width="25%">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $print) {
        echo "<tr>
                <td width='5%'>$print->id</td>
                <td width='25%'>$print->time</td>
                <td width='25%'>$print->name</td>
                <td width='25%'>
                    <a href='".$page_uri."&uptGroup=$print->id'>
                        <button type='button'>update Group informations</button>
                    </a> 
                    <a href='".$page_uri."&delGroup=$print->id'>
                        <button type='button'>delete</button>
                    </a>

                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

