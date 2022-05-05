<?php
require(AROMA_PATH.'pages/navbar.php');

global $wpdb;

$table_name = $wpdb->prefix . 'aroma_bottles';
?>

<div class="wrap container" style="text-align:center;">
<?php
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
    <table class='wp-list-table table striped' style='display:inline-block;'>
      <thead>
        <tr>
          <th>Bottle Id</th>
          <th width='40px'>color</th>
          <th >Name</th>
          <th >Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action='' method='post'>
          <tr>
            <td >$print->id
              <input type='hidden' id='uptid' name='uptid' value='$print->id'>
            </td>
            <td >
              <input type='color' id='uptcolor' name='uptcolor' value='$print->color'>
            </td>
            <td ><input type='text' id='uptname' name='uptname' value='$print->name'></td>
            <td ><button class='button is-primary' id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=aromaSetting'><button type='button' class='button'>CANCEL</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }


  $result = $wpdb->get_results("SELECT * FROM $table_name");
  
?>


    <h1>Aroma bottle</h1>
<?php if(isset($_POST['newBottle'])){?>
  <div>
    <h2>Create new bottle</h2>
    <form action="" method="post">
        <table class="table" style="display:inline-block;">
          <tr>
              <td><input type="color" id="newcolor" name="newcolor"></td>
              <td><input type="text" id="newname" name="newname"></td>
              <td>
                <button class="button is-primary" id="newsubmit" name="newsubmit" type="submit">Create</button>
                <button class="button ">Cancel</button>
              </td>
          </tr>
      </table>
    </form>
</div>  
<?php } ?>    
    <hr>
    <h2>Existing bottles</h2>
    <div class="block">
      <form action="" method="POST">
        <button class="button is-primary block" name="newBottle">New bottle</button>
      </form>
    </div>
    <table class="wp-list-table striped table" style="display:inline-block;">
        <thead>
            <tr>
                <th >Bottle ID</th>
                <th >Color</th>
                <th >Name</th>
                <th >Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $print) {
        echo "<tr>
                <td width='30px'>$print->id</td>
                <td width='40px'>
                <div style='padding:20px;background-color:$print->color'></div>
                </td>
                <td>$print->name</td>
                <td>
                    <a href='admin.php?page=aromaSetting&upt=$print->id'>
                        <button class='button is-primary' type='button'>Update</button>
                    </a> 
                    <a href='admin.php?page=aromaSetting&del=$print->id'>
                        <button class='button is-danger' type='button'>Delete</button>
                    </a>
                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>