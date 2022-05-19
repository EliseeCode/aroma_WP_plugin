<?php
/**
 * Template Name: Clean Page
 * This template will only display the content you entered in the page editor
 * This page has to be created in the WP admin dashboard with the correct slug : aroma-answer
 * And the redirection is made in wp_page_creation
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body class="cleanpage">
<div class="wrap container">
  <h1 class="title">Aroma testai</h1>
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'aroma_tests';
$page_uri="./";
$user_id=get_current_user_id();
//CREATE NEW
if (isset($_POST['newsubmitTest'])) {
    $name = $_POST['newname'];
    $comment = $_POST['newcomment'];
    
    $wpdb->query("INSERT INTO $table_name(time, name,comment,creator_id) VALUES(NOW(),'$name','$comment',$user_id)");
    
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//DELETE
if (isset($_GET['delTest'])) {
    $del_id = (int)$_GET['delTest'];
    if(has_user_role('administrator')){
      $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");
    }else{
      $wpdb->query("DELETE FROM $table_name WHERE id='$del_id' AND creator_id='$user_id'");
    }
    echo "<script>location.replace('".$page_uri."');</script>";
  }  
//UPDATE
if (isset($_POST['uptsubmitTest'])) {
    $id = (int)$_POST['uptid'];
    $name = $_POST['uptname'];
    $comment = $_POST['uptcomment'];
    if(has_user_role('administrator')){
      $wpdb->query("UPDATE $table_name SET comment='$comment', name='$name' WHERE id='$id'");
    }else{
      $wpdb->query("UPDATE $table_name SET comment='$comment', name='$name' WHERE id='$id' AND creator_id='$user_id'");
    }
    echo "<script>location.replace('".$page_uri."');</script>";
  }
//UPDATE FORM
if (isset($_GET['uptTest'])) {
    $upt_id = (int)$_GET['uptTest'];
    if(has_user_role('administrator')){
      $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
    }else{
      $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id' AND creator_id='$user_id'");
    }
    foreach($result as $print) {
      $name = $print->name;
    }
    echo "
    
    <div class='card block'>
      <div class='card-header'>
        <div class='card-header-title'>Redaguoti aroma testai</div>
      </div>
      <div class='card-content'>
        <form action='".$page_uri."' method='post'>
          <div class='field'>
              <div class='control'>
                <input type='hidden' id='uptid' name='uptid' value='$print->id'>
                <input class='input' type='text' id='uptname' name='uptname' value='$print->name'>
              </div>
          </div>  
          <div class='field'>
              <div class='control'>
                <textarea class='textarea' id='uptcomment' name='uptcomment'>$print->comment</textarea>
              </div>
          </div>  
          <div class='field has-addons'>  
              <div class='control'>
                <button class='button is-primary' id='uptsubmit' name='uptsubmitTest' type='submit'>
                sutaupyti
                </button>
              </div>
              <div class='control'>
                <a href='".$page_uri."'><button class='button' type='button'>
                atšaukti
                </button></a>
              </div>
          </div>
        </form>
      </div>
    </div>
    ";
  }

//GET TESTS
if(has_user_role('administrator')){
  $result = $wpdb->get_results("SELECT * FROM $table_name WHERE 1 ORDER BY id DESC");
}else{
  $result = $wpdb->get_results("SELECT * FROM $table_name WHERE creator_id='$user_id' ORDER BY id DESC");
}
  
  
?>

    
    <?php if(isset($_GET["newtest"])){?>
      <form action="" method="post">
        <div class="card block">
          <div class="card-header">
            <div class="card-header-title">Naujas testas</div>
          </div>
          <div class='card-content'>
            <div class="field ">
                <div class="control">
                  <input class="input" type="text" id="newname" name="newname" placeholder="kliento vardas">
                </div>
            </div>
            <div class="field ">
                <div class="control">
                  <textarea class="textarea" id="newcomment" name="newcomment" placeholder="komentaras"></textarea>
                </div>
            </div>
            <div class="field has-addons">
                <div class="control">
                  <button class="button is-primary" id="newsubmit" name="newsubmitTest" type="submit">Naujas testas</button>
                </div>
                <div class="control">
                <a href='".$page_uri."'><button class='button' type='button'>atšaukti</button></a>
                </div>
            </div>
          </div>  
        </div>  
      </form>
      <hr>
    <?php } ?>
    <div class="box block">
    <form action="" method="GET">
      <button class="button is-primary m-3 is-pulled-right" name="newtest">Naujas testas</button>
    </form>  
    <table id="testsTable" class="table wp-list-table striped">
        <thead>
            <tr>
                <th >Data</th>
                <th >Kliento vardas</th>
                <th colspan=2>Testą atliko</th>
                <th >Testas</th>
                <th >Veiksmai</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($result as $print) {
          //$creator=get_user_meta( $print->creator_id );
          //print_r($creator);
          $creatorName=get_user_meta( $print->creator_id, 'nickname', true );
          $creatorFirstName=get_user_meta( $print->creator_id, 'first_name', true );
          $creatorLastName=get_user_meta( $print->creator_id, 'last_name', true );
        echo "<tr>
                <td style='box-sizing: inherit;'>$print->time</td>
                <td style='box-sizing: inherit;'>$print->name</td>
                <td style='box-sizing: inherit;'>$creatorFirstName</td>
                <td style='box-sizing: inherit;'>$creatorLastName</td>
                <td style='box-sizing: inherit;'>
                    
                    <a href='/index.php/aroma-answers?test_id=$print->id'>
                      <button class='m-1 button is-primary icon-text' type='button'>
                      <div class='icon'><i class='fas fa-edit'></i></div>
                        <span>Pildyti</span>
                      </button>
                    </a> 

                    <a href='/index.php/aroma-report?test_id=$print->id' class='mr-3'>
                        <button class='m-1 button is-primary icon-text' type='button'>
                          <div class='icon'><i class='fas fa-chart-pie'></i></div>
                          <span>Analizė</span>
                        </button>
                    </a> 
                </td>
                <td style='box-sizing: inherit;'>
                    <a href='".$page_uri."?uptTest=$print->id'>
                      <button class='m-1 button is-warning icon-text' type='button'>
                      <div class='icon'><i class='fas fa-edit'></i></div>
                        <span>Redaguoti</span>
                      </button>
                    </a> 

                    <a href='".$page_uri."?delTest=$print->id'>
                      <button class='m-1 button is-danger icon-text' type='button'>
                        <div class='icon'><i class='fas fa-trash'></i></div>
                        <span>Naikinti</span>
                      </button>
                    </a>

                </td>
            </tr>";
        }
        ?>
        </tbody>
    </table>
    
      </div>
      <script>
        jQuery(document).ready( function () {
      jQuery('#testsTable').DataTable();
      })
      </script>
</div>


