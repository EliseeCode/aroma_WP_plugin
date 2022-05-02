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
  $result = $wpdb->get_results("SELECT * FROM $bottle_table_name WHERE 1 ORDER BY id DESC");
?>
<div class="wrap">
    <h1>How do you feel?</h1>
    <input type="text" placeholder="Aroma name...">
    <hr>
    <h2>Aromas</h2>
    <div class="aromaContainer">
        <?php foreach ($result as $print) {
        echo "<div class='aromaItem' id='aromaItem_$print->id'>
                <span width='5%'>$print->id</span>
                <div width='25%' style='background-color:$print->color;' >$print->name</div>
                <div class='prefContainer' width='25%'>
                    <button class='disgustPref prefButton' onclick='tooglePreference($print->id,$test_id,0)'>Beurk</button>
                    <button class='negativePref prefButton' onclick='tooglePreference($print->id,$test_id,1)'>-</button>
                    <button class='neutralPref prefButton' onclick='tooglePreference($print->id,$test_id,2)'>0</button>
                    <button class='positivePref prefButton' onclick='tooglePreference($print->id,$test_id,3)'>+</button>
                    <button class='lovePref prefButton' onclick='tooglePreference($print->id,$test_id,4)'>Love</button>
                </div>
            </div>";
        }
        ?>
</div>

<?php
  $wpApiSettings= array(
       'root' => esc_url_raw( rest_url() ),
       'nonce' => wp_create_nonce( 'wp_rest' )
   );
  echo "<script>wpApiSettings=".json_encode($wpApiSettings).";</script>";
?>
    
    <button onclick="sendAjaxRequest()">Send Ajax</button>
    <pre id="ajaxAnswer"></pre>
    
    <script>
     
      console.log("Script public",wpApiSettings);
      function sendAjaxRequest() {
        console.log(jQuery);
          jQuery.ajax({
              url: wpApiSettings.root + 'aroma/v1/bottle/1/',
              method: 'GET',
              beforeSend: function (xhr) {
                  xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
              },
              data: {
                  'id': '1'
              }
          }).done(function (response) {
              console.log(response);
              jQuery('#ajaxAnswer').html(JSON.stringify(response, null, 2));
          });
      }

function tooglePreference(bottle_id, test_id, prefValue) {

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
      

