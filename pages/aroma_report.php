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

</style>  
<?php
global $wpdb;
$pref_table = $wpdb->prefix . 'aroma_test_bottle_preference';
$bottles_table = $wpdb->prefix . 'aroma_bottles';
$test_table = $wpdb->prefix . 'aroma_tests';
$bottle_tag_table = $wpdb->prefix . 'aroma_bottle_tag';
$tags_table = $wpdb->prefix . 'aroma_tags';
$groups_table = $wpdb->prefix . 'aroma_groups';
$group_tag_table = $wpdb->prefix . 'aroma_group_tag';
$page_uri="./";
$user_id=get_current_user_id();
if(isset($_GET["test_id"])){
  $test_id=$_GET["test_id"];
}
else{
  echo "pas de test_id ;(";
  return;
}
echo "<script>test_id=".json_encode($test_id).";</script>";
//Check if user is allowed to see the test
$checkIfExists = $wpdb->get_results("SELECT COUNT(*) FROM $test_table WHERE creator_id = $user_id AND id=$test_id");
if ($checkIfExists == NULL) {
    return "error";
}


//GET Bottles & preferences (pref can be null)
  $bottles = $wpdb->get_results("SELECT $bottles_table.id,
    $bottles_table.name,
    $bottles_table.color,
    $pref_table.preference
    FROM $bottles_table 
    LEFT JOIN $pref_table 
    ON $pref_table.bottle_id=$bottles_table.id AND $pref_table.test_id=$test_id
    WHERE 1
    ORDER BY $bottles_table.id DESC");
  echo "<script>bottles=".json_encode($bottles).";</script>";  
  //GET Groups 
  $groups = $wpdb->get_results("SELECT $groups_table.id,
    $groups_table.name
    FROM $groups_table 
    WHERE 1
    ORDER BY $groups_table.id ASC");
  echo "<script>groups=".json_encode($groups).";</script>";  
//GET tags, group & bottle
  $tags = $wpdb->get_results("SELECT $bottles_table.id, 
      $bottles_table.name as bottle_name,
      $bottles_table.color as bottle_color,
      $tags_table.id as tag_id,
      $tags_table.name as tag_name,
      $groups_table.id as group_id,
      $groups_table.name as group_name,
      $pref_table.preference as pref
    FROM $bottles_table 
    JOIN $bottle_tag_table ON $bottle_tag_table.bottle_id=$bottles_table.id
    JOIN $tags_table ON $bottle_tag_table.tag_id=$tags_table.id
    JOIN $group_tag_table ON $group_tag_table.tag_id=$tags_table.id
    JOIN $groups_table ON $group_tag_table.group_id=$groups_table.id
    JOIN $pref_table ON $bottles_table.id=$pref_table.bottle_id AND $pref_table.test_id=$test_id
    WHERE 1
    ORDER BY $groups_table.id,$tags_table.id DESC");
  echo "<script>tags=".json_encode($tags).";</script>";  

  //GET test
$tests = $wpdb->get_results("SELECT *
FROM $test_table 
WHERE id=$test_id");
if($tests==NULL){return "error";}
foreach ($tests as $t){
  $test=$t;
}

?>
<style>
  .groupItem{
    display: inline-block;
    margin: 10px;
    min-width: 40%;
    vertical-align:top;
    }
  .bottle{
    padding:5px 10px;
    border-radius:5px;
    color:white;
    display:inline-block;
    margin:4px;
  }  
  .saved{border:2px lime solid;}
</style>
<div class="wrap container" style="text-align:center;">
  <div class="level">
    <div class="level-left">
      <a class="level-item" href="/index.php/aroma-answers/?test_id=<?php echo $test_id; ?>">
        <div class="icon">
          <span class="fas fa-arrow-left"></span>
        </div>  
      </a>
    </div>  
    <div class="level-item">
      <div>
      <?php 
      echo "<h1 class='title'>$test->name</h1>
      <p>$test->surname</p>
      <p>$test->time</p>";
      ?>
      </div>
    </div>
    <div class="level-item field">
      <div class='control'>
        <label>Comment</label>
        <textarea id='uptcomment' oninput="updateTestComment();" class="textarea"><?php echo "$test->comment";?></textarea>
      </div>
    </div>
  </div> 

  <!-- TOP Oil -->
  
  <div class="card groupItem is-4 block" style="max-width:400px;">
    <header class='card-header'>
      <p class='card-header-title'>
      TOP pasirinkti aliejai
      </p>
    </header>
    <div class='card-content'>
      <div class='content'>
        <div>
          <?php 
          forEach($bottles as $bottle){
            if($bottle->preference==4)
            echo "<div class='bottle' style='background-color:$bottle->color;'>$bottle->name</div>";
          }
          ?>
        </div>
      </div> 
    </div> 
  </div>     

  <div class="card groupItem is-4 block">
    <header class='card-header'>
      <p class='card-header-title'>
      Ekspres analizė
      </p>
    </header>
    <div class='card-content'>
      <div class='content'>
        <table class="table">
          <thead>
            <tr>
              <th><div class="icon"><i class="fas fa-skull-crossbones"></div></th>
              <th><div class="icon"><i class="fas fa-minus"></div></th>
              <th><div class="icon"><i class="fas fa-meh"></div></th>
              <th><div class="icon"><i class="fas fa-plus"></div></th>
              <th><div class="icon"><i class="fas fa-heart"></div></th>
            </tr>
          </thead> 
          <tbody> 
            <tr>
              <td id="percent_bottle_0">no data</td>
              <td id="percent_bottle_1">no data</td>
              <td id="percent_bottle_2">no data</td>
              <td id="percent_bottle_3">no data</td>
              <td id="percent_bottle_4">no data</td>
            </tr>
          </tbody>    
        </table>
      </div> 
    </div> 
  </div>     
<hr>
  <?php forEach($groups as $group){
    echo "<div class='card is-4 block groupItem groupItem_$group->id'>
    <header class='card-header'>
      <p class='card-header-title'>
        $group->name
      </p>
    </header>
    <div class='card-content'>
      <div class='content'>";
      if($group->id!=6){echo "<div style='max-width:100%;width: 600px;'>";}else{echo "<div style='width: 400px;max-width:100%;'>";}
      echo "
      <canvas id='chartGroup_$group->id'></canvas>
      </div>
      </div>
    </div>
    </div>";
  }?>
  

</div>
<?php
  $wpApiSettings= array(
       'root' => esc_url_raw( rest_url() ),
       'nonce' => wp_create_nonce( 'wp_rest' )
   );
  echo "<script>wpApiSettings=".json_encode($wpApiSettings).";</script>";
?>
  <script>
    console.log(bottles,groups,tags);
    //Construction of the superDataObject
    var data={};
    for(let k in tags)
    {
      row=tags[k];
      group_id=row.group_id;
      group_name=row.group_name;
      tag_id=row.tag_id;
      tag_name=row.tag_name;
      bottle_id=row.bottle_id;
      bottle_name=row.bottle_name;
      pref=row.pref;
      newBottleByPref=data[group_id]?.tagById[tag_id]?.bottleByPref || [
        {total:0,bottles:[]},
        {total:0,bottles:[]},
        {total:0,bottles:[]},
        {total:0,bottles:[]},
        {total:0,bottles:[]}];

      newBottleByPref[pref]={...newBottleByPref[pref],
        total:newBottleByPref[pref]?.total+1 || 1,
        bottles:[...newBottleByPref[pref]?.bottles,{bottle_name,bottle_id}]}

      data={...data,
        [group_id]:{...data[group_id],
          group_id,
          group_name,
          tagById:{...data[group_id]?.tagById,
            [tag_id]:{...data[group_id]?.tagById[tag_id],
              tag_id,
              tag_name,
              bottleByPref:newBottleByPref,
              nberOfBottles:newBottleByPref.reduce((acc,cur)=>{return acc+cur.total},0)
            }
          }}
      }
    }
    console.log(data);

    
      var nbreOfBottleByPref=bottles.reduce((acc,cur)=>{
        acc[cur.preference]++;
        return acc;
      },[0,0,0,0,0]);
      var totalNbreOfBottleByPref=nbreOfBottleByPref.reduce((acc,cur)=>acc+cur,0);
      console.log(nbreOfBottleByPref);
      for(k=0;k<5;k++){
        jQuery('#percent_bottle_'+k).text(`${Math.round(nbreOfBottleByPref[k]*100/totalNbreOfBottleByPref)}% (${nbreOfBottleByPref[k]})`);
      }
      
    
    

    //CHARTS
    const COLORS = [
  '#4dc9f6',
  '#f67019',
  '#f53794',
  '#537bc4',
  '#acc236',
  '#166a8f',
  '#00a950',
  '#58595b',
  '#8549ba'
  ];
    const COLORS_SHAKRA = [
  '#e3424a',
  '#eb7f2d',
  '#f5e42f',
  '#32c241',
  '#3294c2',
  '#3a2dad',
  '#7d35b0'
  ];
    configGroup=[];
    dataSet=[];
    //config group_1
    for(let g in groups)
    {
    var group_id=groups[g].id;
    dataSet[group_id] = {
      labels: Object.values(data[group_id].tagById).map((t)=>{return t.tag_name}),
      datasets: [{
        label: data[group_id].group_name,
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: Object.values(data[group_id].tagById).map((t)=>{
          console.log(t.bottleByPref[3].total,t.bottleByPref[4].total,t.nberOfBottles);
          //To start from somewhere else than origin
          //return [10,(t.bottleByPref[3].total+t.bottleByPref[4].total)*100/t.nberOfBottles];
          return (t.bottleByPref[3].total+t.bottleByPref[4].total)*100/t.nberOfBottles;
        }),
      }]
    };

    configGroup[group_id]={type: 'bar',
      data: dataSet[group_id],
      options: {
        indexAxis: 'y',
        elements: {
          bar: {
            borderWidth: 2,
          }
        },
        responsive: true,
        plugins:{
          legend:{display:false}
        },
        scales: {
        x: {
          min: 0,
          max: 100,
        }
     }
      }
    }
  }
  //OverWrite DataSet and conf
  dataSet[3].datasets[0].backgroundColor= COLORS_SHAKRA;
  dataSet[3].datasets[0].borderColor= 'rgb(255, 255, 255)';
  //Nata
  configGroup[6].type='pie';
  configGroup[6].options.plugins.legend.display=true;
  dataSet[6].datasets[0].backgroundColor= COLORS;
  dataSet[6].datasets[0].borderColor= 'rgb(255, 255, 255)';

  for(let g in groups)
  {
  var group_id=groups[g].id;
  new Chart(
    document.getElementById('chartGroup_'+group_id),
    configGroup[group_id]
  );
  }
  
  </script>  



<script>
  var timer;
  function updateTestComment(){
    if(timer){
      clearTimeout(timer);
    }
    timer=setTimeout(sendAjaxUpdateComment,1000);
  }
  function sendAjaxUpdateComment()
  {
    comment=jQuery('#uptcomment').val();
    jQuery.ajax({
              url: wpApiSettings.root + 'aroma/v1/testComment/',
              method: 'POST',
              data:{test_id:test_id, comment},
              beforeSend: function (xhr) {
                  xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
              }
          }).done(function (response) {
            jQuery('#uptcomment').addClass("saved");
            setTimeout(()=>{jQuery('#uptcomment').removeClass("saved");},500);
          });
  }
  

 


  
</script>
</body>
</html>
      

