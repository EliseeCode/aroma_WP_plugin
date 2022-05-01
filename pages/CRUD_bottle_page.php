<?php

global $wpdb;
$table_name = $wpdb->prefix . 'aroma_bottles';
//CREATE NEW
if (isset($_POST['newsubmit'])) {
    $name = $_POST['newname'];
    $color = $_POST['newcolor'];
    $user_id=get_current_user_id();
    // $email = $_POST['newemail'];
    // $wpdb->query("INSERT INTO $table_name(name,email) VALUES('$name','$email')");
     $wpdb->query("INSERT INTO $table_name(name,color,creator_id) VALUES('$name','$color',$user_id)");
    
    echo "<script>location.replace('admin.php?page=aromaSetting');</script>";
  }
//DELETE
if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");
    
    echo "<script>location.replace('admin.php?page=aromaSetting');</script>";
  }  
//UPDATE
if (isset($_POST['uptsubmit'])) {
    $id = $_POST['uptid'];
    $name = $_POST['uptname'];
    $color = $_POST['uptcolor'];
    $wpdb->query("UPDATE $table_name SET name='$name',color='$color' WHERE id='$id'");
    
    echo "<script>location.replace('admin.php?page=aromaSetting');</script>";
  }
//UPDATE FORM
if (isset($_GET['upt'])) {
    $upt_id = $_GET['upt'];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
    foreach($result as $print) {
      $name = $print->name;
    }
    echo "
    <div class='wrap'>
    <h2>Update Bottles</h2>
    <table class='wp-list-table widefat striped'>
      <thead>
        <tr>
          <th width='25%'>Bottle Id</th>
          <th width='25%'>color</th>
          <th width='25%'>Name</th>
          <th width='25%'>Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action='' method='post'>
          <tr>
            <td width='25%'>$print->id</td>
            <td width='25%'>
              <input type='color' id='uptcolor' name='uptcolor' value='$print->color'>
            </td>
            <td width='25%'><input type='text' id='uptname' name='uptname' value='$print->name'></td>
            <td width='25%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=aromaSetting'><button type='button'>CANCEL</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }


  $result = $wpdb->get_results("SELECT * FROM $table_name");
  
?>
<div class="wrap">
    <h1>Aroma bottle</h1>
    <h2>Create new bottle</h2>
    <form action="" method="post">
        <tr>
            <td><input type="text" value="AUTO_GENERATED" disabled></td>
            <td><input type="color" id="newcolor" name="newcolor"></td>
            <td><input type="text" id="newname" name="newname"></td>
            <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
        </tr>
    </form>
    <hr>
    <h2>Existing bottles</h2>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th width="25%">Bottle ID</th>
                <th width="25%">Color</th>
                <th width="25%">Name</th>
                <th width="25%">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $print) {
        echo "<tr>
                <td width='25%'>$print->id</td>
                <td width='25%'>
                <div style='padding:20px;background-color:$print->color'></div>
                </td>
                <td width='25%'>$print->name</td>
                <td width='25%'>
                    <a href='admin.php?page=aromaSetting&upt=$print->id'>
                        <button type='button'>UPDATE</button>
                    </a> 
                    <a href='admin.php?page=aromaSetting&del=$print->id'>
                        <button type='button'>DELETE</button>
                    </a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
    
   
<a href="/index.php/aroma-tests/">Tests page</a>
</div>