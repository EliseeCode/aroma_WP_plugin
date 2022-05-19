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
<style>
  .prefButton{color:grey;}
  .prefButton.is-active{
    color:black;
  }
</style>  
<?php
global $wpdb;
$pref_table_name = $wpdb->prefix . 'aroma_test_bottle_preference';
$bottle_table_name = $wpdb->prefix . 'aroma_bottles';
$test_table = $wpdb->prefix . 'aroma_tests';
$page_uri="./";
$user_id=get_current_user_id();
if(isset($_GET["test_id"])){
  $test_id=$_GET["test_id"];
}
else{
  echo "no test_id ;(";
  return;
}
//Check if user is allowed to see the test
    $checkIfExists = $wpdb->get_results("SELECT COUNT(*) FROM $test_table WHERE creator_id = $user_id AND id=$test_id");
    if ($checkIfExists == NULL && !has_user_role('administrator')) {
        return "You are not allowed to see this test";
    }
//GET test
$tests = $wpdb->get_results("SELECT *
    FROM $test_table 
    WHERE id=$test_id");
  
//GET Bottles
  $bottles = $wpdb->get_results("SELECT $bottle_table_name.id,
    $bottle_table_name.name,
    $bottle_table_name.color,
    $pref_table_name.preference
    FROM $bottle_table_name 
    LEFT JOIN $pref_table_name 
    ON $pref_table_name.bottle_id=$bottle_table_name.id AND $pref_table_name.test_id=$test_id
    WHERE 1
    ORDER BY $bottle_table_name.name ASC");
  echo "<script>bottles=".json_encode($bottles).";</script>";  
?>

<div class="wrap container">

  <div class="level" style="width:500px;">
    <a class="level-item" href="/index.php/aroma-tests">
    <div class="icon">
      <span class="fas fa-arrow-left"></span>
    </div>  
  </a>
    <div class="level-item">
      <div>
      <?php foreach ($tests as $test) {
      echo "<h1 class='title'>$test->name</h1>
      <p>$test->surname</p>
      <p>$test->time</p>";
      }?>
      </div>
    </div>
  </div>
<nav class="panel">

  <div class="panel-heading">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
      Eteriniai aliejai
      </div>
      </div>
      <div class="level-right">
        <div class="level-item">
          <a href="/index.php/aroma-report?test_id=<?php echo $test_id;?>" class="icon-text button is-primary">
            <span class="icon"><i class="fas fa-chart-pie"></i></span>
            <span>Analizė</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="panel-block">
    <p class="control has-icons-left">
      <input oninput="filterBottlesByName()" id="bottleNameInput" class="input" type="text" placeholder="Paieška">
      <span class="icon is-left">
        <i class="fas fa-search" aria-hidden="true"></i>
      </span>
    </p>
  </div>
  <p class="panel-tabs filterContainer">
    <a class="is-active pref_all prefButton" onclick='toogleFilter(-1)'>Visi</a>
        <a class='pref_0 prefButton' data-val=0 onclick='toogleFilter(0)'><span class='icon'><span class='fas fa-skull-crossbones'></span></span></a>
        <a class='pref_1 prefButton' data-val=1 onclick='toogleFilter(1)'><span class='icon'><span class='fas fa-minus'></span></span></a>
        <a class='pref_2 prefButton' data-val=2 onclick='toogleFilter(2)'><span class='icon'><span class='fas fa-meh'></span></span></a>
        <a class='pref_3 prefButton' data-val=3 onclick='toogleFilter(3)'><span class='icon'><span class='fas fa-plus'></span></span></a>
        <a class='pref_4 prefButton' data-val=4 onclick='toogleFilter(4)'><span class='icon'><span class='fas fa-heart'></span></span></a>
  </p>
  <?php foreach ($bottles as $bottle) {
          $prefClass = array_fill(0,5, '');
          if(isset($bottle->preference))
          {$prefClass[$bottle->preference]='is-danger';}
        echo "<a class='aromaItem' style='display:flex;justify-content: space-evenly;' id='aromaItem_$bottle->id'>
                <div class='level-item'>
                  <div style='background-color:$bottle->color; color:white;padding:5px 10px; border-radius:8px; width:250px; text-align:center;' >$bottle->name</div>
                </div> 
                <div class='level-item'>
                  <div class='prefContainer level-item level'>
                      <button class='button is-inverted pref_0 prefButton ml-1 $prefClass[0]' onclick='tooglePreference($bottle->id,$test_id,0)'><span class='icon'><span class='fas fa-skull-crossbones'></span></span></button>
                      <button class='button is-inverted pref_1 prefButton ml-1 $prefClass[1]' onclick='tooglePreference($bottle->id,$test_id,1)'><span class='icon'><span class='fas fa-minus'></span></span></button>
                      <button class='button is-inverted pref_2 prefButton ml-1 $prefClass[2]' onclick='tooglePreference($bottle->id,$test_id,2)'><span class='icon'><span class='fas fa-meh'></span></span></button>
                      <button class='button is-inverted pref_3 prefButton ml-1 $prefClass[3]' onclick='tooglePreference($bottle->id,$test_id,3)'><span class='icon'><span class='fas fa-plus'></span></span></button>
                      <button class='button is-inverted pref_4 prefButton ml-1 $prefClass[4]' onclick='tooglePreference($bottle->id,$test_id,4)'><span class='icon'><span class='fas fa-heart'></span></span></button>
                  </div>
                </div>  
            </a>";
        }
        ?>
 

  <div class="panel-block">
    <button onclick='toogleFilter(-1)' class="button is-outlined is-fullwidth">
    Pašalinti pasirinkimus
    </button>
  </div>
</nav>
</div>

<?php
  $wpApiSettings= array(
       'root' => esc_url_raw( rest_url() ),
       'nonce' => wp_create_nonce( 'wp_rest' )
   );
  echo "<script>wpApiSettings=".json_encode($wpApiSettings).";</script>";
?>   
<script>
function toogleFilter(val){
  jQuery('#bottleNameInput').val("");
  var noFilter=false;
  var selection=jQuery('.filterContainer').find('.pref_'+val);
  if(selection.hasClass("is-active") || val==-1){
    selection.removeClass('is-active');
    jQuery('.filterContainer').find('.prefButton').removeClass('is-active');
    jQuery(".pref_all").addClass("is-active");
    noFilter=true;
  }
  else{
  jQuery('.filterContainer').find('.prefButton').removeClass('is-active');
  selection.addClass('is-active');
  }
  //Hide and show bottle by pref
  
    filteredBottle=bottles.filter((bottle)=>{
      return bottle.preference==val || noFilter;
    });
    jQuery('.aromaItem').hide();
    for(let k in filteredBottle){
      jQuery('#aromaItem_'+filteredBottle[k].id).fadeIn(500);
    }
  
}      
function filterBottlesByName(){
  jQuery('.filterContainer').find('.prefButton').removeClass('is-active');
  bottleNameInput=jQuery('#bottleNameInput').val().toLowerCase();
  filteredBottle=bottles.filter((bottle)=>{
    return bottle.name.toLowerCase().includes(bottleNameInput)
  });
  jQuery('.aromaItem').hide();
  for(let k in filteredBottle){
    jQuery('#aromaItem_'+filteredBottle[k].id).fadeIn(500);
  }
}

function tooglePreference(bottle_id, test_id, prefValue) {
  
  var selectionPref=jQuery("#aromaItem_"+bottle_id).find('.pref_'+prefValue);
  if(selectionPref.hasClass("is-danger")){
    selectionPref.removeClass("is-danger");
    prefValue=-1;
  }else{
    jQuery("#aromaItem_"+bottle_id).find('.prefButton').removeClass("is-danger");
    selectionPref.addClass("is-danger");
  }

  jQuery.ajax({
              url: wpApiSettings.root + 'aroma/v1/pref/',
              method: 'POST',
              data:{test_id:test_id, bottle_id:bottle_id, prefValue:prefValue},
              beforeSend: function (xhr) {
                  xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
              }
          }).done(function (response) {
            bottle_ids=bottles.map((b)=>{return b.id});
            bottles[bottle_ids.indexOf(bottle_id.toString())].preference=prefValue;
            console.log(bottles);
              console.log(response);
              jQuery('#ajaxAnswer').html(JSON.stringify(response, null, 2));
          });

}
    </script>  
      </body>
  </html>
      

