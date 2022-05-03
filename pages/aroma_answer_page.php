<?php
/**
 * Template Name: Clean Page
 * This template will only display the content you entered in the page editor
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
$page_uri="./";
$user_id=get_current_user_id();
if(isset($_GET["test_id"])){
  $test_id=$_GET["test_id"];
}
else{
  echo "pas de test_id ;(";
  return;
}
//GET Bottles
  $bottles = $wpdb->get_results("SELECT $bottle_table_name.id,
    $bottle_table_name.name,
    $bottle_table_name.color,
    $pref_table_name.preference
    FROM $bottle_table_name 
    LEFT JOIN $pref_table_name 
    ON $pref_table_name.bottle_id=$bottle_table_name.id AND $pref_table_name.test_id=$test_id
    WHERE 1
    ORDER BY $bottle_table_name.id DESC");
  echo "<script>bottles=".json_encode($bottles).";</script>";  
?>

<div class="wrap container">
<nav class="panel">
  <p class="panel-heading">
    Bottles
  </p>
  <div class="panel-block">
    <p class="control has-icons-left">
      <input oninput="filterBottlesByName()" id="bottleNameInput" class="input" type="text" placeholder="Search">
      <span class="icon is-left">
        <i class="fas fa-search" aria-hidden="true"></i>
      </span>
    </p>
  </div>
  <p class="panel-tabs filterContainer">
    <a class="is-active pref_all prefButton" onclick='toogleFilter(-1)'>All</a>
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
      Reset all filters
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


<script>
 fetch('http://localhost:8082/wp-json/aroma/v1/bottle/1/')
    .then(function (response) {
        return response.json();
    }).then(function (data) {
        console.log(data);
    })
</script>  
      </body>
  </html>
      

