<?php
require(AROMA_PATH.'pages/navbar.php');

global $wpdb;
$user_id=get_current_user_id();
$table_name = $wpdb->prefix . 'aroma_bottles';
$bottle_tag_table = $wpdb->prefix . 'aroma_bottle_tag';
$tags_table = $wpdb->prefix . 'aroma_tags';
$group_tag_table = $wpdb->prefix . 'aroma_group_tag';
$groups_table = $wpdb->prefix . 'aroma_groups';
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
    $tags = $_POST['upttags'];
    $wpdb->query("DELETE FROM $bottle_tag_table WHERE bottle_id=$id");
    $valuesArray=[];
    forEach($tags as $tag){
      $tag=(int)$tag;
      array_push($valuesArray,"($id,$tag,$user_id)");
    }
    $values=join(',',$valuesArray);
    $wpdb->query("INSERT INTO $bottle_tag_table(bottle_id,tag_id,creator_id) VALUES $values");
    
    echo "<script>location.replace('admin.php?page=aromaSetting');</script>";
  }
//UPDATE FORM
if (isset($_GET['upt'])) {
    $upt_id = $_GET['upt'];
    $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
    foreach($result as $print) {
      $name = $print->name;
      $id = $print->id;
      $color = $print->color;
    }
    $allGroupTag = $wpdb->get_results("SELECT $tags_table.id as tag_id,
      $tags_table.name as tag_name, 
      $groups_table.id as group_id, 
      $groups_table.name as group_name
      FROM $tags_table 
      JOIN $group_tag_table ON $group_tag_table.tag_id=$tags_table.id
      JOIN $groups_table ON $group_tag_table.group_id=$groups_table.id");
    $allBottleTag = $wpdb->get_results("SELECT * FROM $bottle_tag_table WHERE bottle_id=$upt_id");
    $allGroups = $wpdb->get_results("SELECT * FROM $groups_table");
    
    echo "<div class='wrap'>
    <h2>Update Bottles</h2>
    <table class='wp-list-table table striped' style='display:inline-block;'>
      <thead>
        <tr>
          <th width='40px'>color</th>
          <th>Name</th>";
          forEach($allGroups as $group){
            echo "<th>$group->name</th>";
          }
         echo "<th >Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action='' method='post'>
          <tr>
            <td>
              <input type='color' id='uptcolor' name='uptcolor' value='$color'>
              <input type='hidden' id='uptid' name='uptid' value='$id'>
            </td>
            <td ><input type='text' style='width:100px;' id='uptname' name='uptname' value='$name'></td>";
            forEach($allGroups as $group){
              echo "<td style='text-align:left;'>";
              
              forEach($allGroupTag as $groupTag){
                if($groupTag->group_id==$group->id){
                  $checked="";
                  forEach($allBottleTag as $bottleTag){
                    if($bottleTag->tag_id==$groupTag->tag_id){$checked="checked";}
                  }
                echo "<div><label><input type='checkbox' $checked name=upttags[] value=$groupTag->tag_id>$groupTag->tag_name</label></div>";
                }
              }
              
              echo"</td>";
            }
            echo "<td ><button class='button is-primary' id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=aromaSetting'><button type='button' class='button'>CANCEL</button></a></td>
          </tr>
        </form>
      </tbody>
    </table>
    </div>";
  }

  $result = $wpdb->get_results("SELECT $table_name.id as id, $table_name.name as name, $table_name.color as color, GROUP_CONCAT(CONCAT($groups_table.name,' : ',$tags_table.name)) as tags FROM $table_name 
  JOIN $bottle_tag_table ON $table_name.id=$bottle_tag_table.bottle_id
  JOIN $tags_table ON $bottle_tag_table.tag_id=$tags_table.id
  JOIN $group_tag_table ON $group_tag_table.tag_id=$tags_table.id
  JOIN $groups_table ON $groups_table.id=$group_tag_table.group_id
  GROUP BY $table_name.id");
  echo "<script>bottles=".json_encode($result).";</script>";  
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
    <table id="bottleTable" class="wp-list-table striped table" style="">
        <thead>
            <tr>
                <th >Bottle ID</th>
                <th >Color</th>
                <th >Name</th>
                <th width="40%">tags</th>
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
                <td id='bottleItemTag_$print->id'></td>
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
   <script>
     jQuery(document).ready( function () {
      
      for(let k in bottles)
      {
        bottle=bottles[k];
        bottle_id=bottle.id;
        tagsRow=bottle.tags;
        groupTagArray=tagsRow.split(',');
        tagsHTML="";
        dataTag={};
        for(let t in groupTagArray)
        {
          
          [groupName,tagName]=groupTagArray[t].split(':');
          //console.log(groupName,tagName);
          dataTag={...dataTag,
            [groupName]:[...dataTag[groupName]||[],tagName]};
          
        }
        GroupNames=Object.keys(dataTag);
        //console.log('group&tag for bottle:'+bottle_id,dataTag);
        for(g in GroupNames){
          groupName=GroupNames[g];
          tagsFromGroup=dataTag[groupName].map((tagName)=>{return `<span class="tag">${tagName}</span>`}).join('')
          jQuery('#bottleItemTag_'+bottle_id).append(`<div><span style="font-size:0.7em;">${groupName} : </span>${tagsFromGroup}</div>`);
        }
        tagsHTML
        
      }
      
      jQuery('#bottleTable').DataTable();
} );
   </script>  
</div>