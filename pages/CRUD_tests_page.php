<?php
global $wpdb;
$table_name = $wpdb->prefix . 'aroma_tests';
$page_uri="./";
$user_id=get_current_user_id();
//CREATE NEW
if (isset($_POST['newsubmitTest'])) {
    $name = $_POST['newname'];
    $wpdb->query("INSERT INTO $table_name(time, name,creator_id) VALUES(NOW(),'$name',$user_id)");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//DELETE
if (isset($_GET['delTest'])) {
    $del_id = $_GET['delTest'];
    $wpdb->query("DELETE FROM $table_name WHERE id='$del_id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }  
//UPDATE
if (isset($_POST['uptsubmitTest'])) {
    $id = $_POST['uptid'];
    $name = $_POST['uptname'];
    $wpdb->query("UPDATE $table_name SET name='$name' WHERE id='$id' AND creator_id='$user_id'");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//UPDATE FORM
if (isset($_GET['uptTest'])) {
    $upt_id = $_GET['uptTest'];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id' AND creator_id='$user_id'");
    foreach($result as $print) {
      $name = $print->name;
    }
    echo "
    <div class='wrap'>
    <h2>Update Tests</h2>
    <table class='wp-list-table widefat striped'>
      <thead>
        <tr>
          <th width='5%'>test Id</th>
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
            <td width='25%'><button id='uptsubmit' name='uptsubmitTest' type='submit'>update</button><a href='".$page_uri."'><button type='button'>cancel</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }

//GET TESTS
  $result = $wpdb->get_results("SELECT * FROM $table_name WHERE creator_id='$user_id' ORDER BY id DESC");
  
?>
<div class="wrap">
    <h1>Tests</h1>
    <h2>New test</h2>
    <form action="" method="post">
        <tr>
            <td><input type="text" id="newname" name="newname" placeholder="person's name"></td>
            <td><button id="newsubmit" name="newsubmitTest" type="submit">New</button></td>
        </tr>
    </form>
    <hr>
    <h2>Previous tests</h2>
    <table class="wp-list-table widefat striped">
        <thead>
            <tr>
                <th width="5%">test ID</th>
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
                    <a href='".$page_uri."?uptTest=$print->id'>
                        <button type='button'>update test informations</button>
                    </a> 
                    <a href='/index.php/aroma-answers?test_id=$print->id'>
                        <button type='button'>answer the test</button>
                    </a> 

                    <a href='".$page_uri."?report=$print->id'>
                        <button type='button'>see report</button>
                    </a> 

                    <a href='".$page_uri."?delTest=$print->id'>
                        <button type='button'>delete</button>
                    </a>

                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

