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
if ($checkIfExists == NULL && !has_user_role('administrator')) {
    return "error";
}


//GET Bottles & preferences (pref can be null)
  $bottles = $wpdb->get_results("SELECT $bottles_table.id,
    $bottles_table.name,
    $bottles_table.color,
    $pref_table.preference,
    $pref_table.position
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
      $tags_table.position as tag_position,
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
    ORDER BY $groups_table.id ASC, $tags_table.position ASC");
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
        <label>Užklausa</label>
        <textarea id='uptcomment' oninput="updateTestComment();" class="textarea"><?php echo "$test->comment";?></textarea>
      </div>
    </div>
  </div> 

  <!-- TOP Oil -->
  <style>
    .bottle:nth-child(1):before{content:"1";}
    .bottle:nth-child(2):before{content:"2";}
    .bottle:nth-child(3):before{content:"3";}
    .bottle:nth-child(4):before{content:"4";}
    .bottle{opacity:0.7;}
    .bottle:nth-child(1), .bottle:nth-child(2),.bottle:nth-child(3), .bottle:nth-child(4){opacity:1;}
    
  </style>  
  <div class="card groupItem is-4 block" style="max-width:400px;">
    <header class='card-header'>
      <p class='card-header-title'>
      TOP pasirinkti aliejai
      </p>
    </header>
    <div class='card-content'>
      <div class='content'>
        <div id='sortableTOP' style="overflow: auto;">
          <?php 
          forEach($bottles as $bottle){
            if($bottle->preference==4){
            echo "<div class='bottle bottle_item_$bottle->id' data-position='$bottle->position' data-id='$bottle->id' style='background-color:$bottle->color;'><div class='icon'><i class='fas fa-heart'></i></div>$bottle->name</div>";
            }
          }
          forEach($bottles as $bottle){
          if($bottle->preference!=null && $bottle->preference==0){
            echo "<div class='bottle bottle_item_$bottle->id' data-position='$bottle->position' data-id='$bottle->id' style='background-color:$bottle->color;'><div class='icon'><i class='fas fa-skull-crossbones'></i></div>$bottle->name</div>";
          }
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
              <!-- <th><div class="icon"><i class="fas fa-skull-crossbones"></div></th> -->
              <th><div class="icon"><i class="fas fa-minus"></div></th>
              <th><div class="icon"><i class="fas fa-meh"></div></th>
              <th><div class="icon"><i class="fas fa-plus"></div></th>
              <!-- <th><div class="icon"><i class="fas fa-heart"></div></th> -->
            </tr>
          </thead> 
          <tbody> 
            <tr>
              <!-- <td id="percent_bottle_0">no data</td> -->
              <td id="percent_bottle_1">no data</td>
              <td id="percent_bottle_2">no data</td>
              <td id="percent_bottle_3">no data</td>
              <!-- <td id="percent_bottle_4">no data</td> -->
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
    //Construction of the superDataObject
    var data={};
    console.log(tags);
    for(let k in tags)
    {
      row=tags[k];
      group_id=row.group_id;
      group_name=row.group_name;
      tag_id=row.tag_id;
      tag_name=row.tag_name;
      tag_position=row.tag_position;
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
        total:newBottleByPref[pref]?.total+1,
        bottles:[...newBottleByPref[pref]?.bottles,{bottle_name,bottle_id}]}

      data={...data,
        [group_id]:{...data[group_id],
          group_id,
          group_name,
          tagById:{...data[group_id]?.tagById,
            [tag_id]:{...data[group_id]?.tagById[tag_id],
              tag_id,
              tag_name,
              tag_position,
              bottleByPref:newBottleByPref,
              nberOfBottles:newBottleByPref.reduce((acc,cur)=>{return acc+cur.total},0)
            }
          }}
      }
      
console.log("afterData",data)
    }
    console.log(data);

    
      var nbreOfBottleByPref=bottles.reduce((acc,cur)=>{
        acc[cur.preference]++;
        return acc;
      },[0,0,0,0,0]);
      var totalNbreOfBottleByPref=nbreOfBottleByPref.reduce((acc,cur)=>acc+cur,0);
      console.log(nbreOfBottleByPref);
      jQuery('#percent_bottle_1').text(`${Math.round((nbreOfBottleByPref[0]+nbreOfBottleByPref[1])*100/totalNbreOfBottleByPref)}% (${(nbreOfBottleByPref[0]+nbreOfBottleByPref[1])})`);
      jQuery('#percent_bottle_2').text(`${Math.round(nbreOfBottleByPref[2]*100/totalNbreOfBottleByPref)}% (${nbreOfBottleByPref[2]})`);
      jQuery('#percent_bottle_3').text(`${Math.round((nbreOfBottleByPref[3]+nbreOfBottleByPref[4])*100/totalNbreOfBottleByPref)}% (${(nbreOfBottleByPref[3]+nbreOfBottleByPref[4])})`);

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
  '#7d35b0',
  '#3a2dad',
  '#3294c2',
  '#32c241',
  '#f5e42f',
  '#eb7f2d',
  '#e3424a',
  ];

    configGroup=[];
    dataSet=[];
    //config group_1
    for(let g in groups)
    {

    var group_id=groups[g].id;
    
    dataSet[group_id] = {
      labels: Object.values(data[group_id].tagById)
        .sort((a,b)=>{return a.tag_position-b.tag_position;})
        .map((t)=>{return t.tag_name}),
      datasets: [{
        label: data[group_id].group_name,
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: Object.values(data[group_id].tagById)
          .sort((a,b)=>{return a.tag_position-b.tag_position;})
          .map((t)=>{
          posValue=Math.floor((t.bottleByPref[3].total+t.bottleByPref[4].total)*100/t.nberOfBottles);
          return posValue;
        }),
        datalabels: {
          align: 'start',
          anchor: 'end'
        }
      }
      ]
    };

    if(group_id==2)
    {
      dataSet[group_id].datasets.push(
      {
          label: data[group_id].group_name,
          backgroundColor: 'rgb(80, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
          data: Object.values(data[group_id].tagById).map((t)=>{
            negValue=Math.floor(-(t.bottleByPref[0].total+t.bottleByPref[1].total)*100/t.nberOfBottles);
            return negValue;
          }),
          datalabels: {
            align: 'end',
            anchor: 'start'
          }
      });
    }


    var pluginConfig={
          legend:{display:false},
          datalabels: {
            color: 'white',
            display: function(context) {
              return Math.abs(context.dataset.data[context.dataIndex]) > 15;
            },
            font: {
              weight: 'bold'
            },
            formatter: function(value, context) {
              if(value.length>1){
              return Math.round(value[1]) + '%';
              }
              else{
              return Math.round(value) + '%';
              }
            }
          }
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
        plugins:
          {...pluginConfig}
        ,
        scales: {
          x: {
          stacked:true,  
          min: -100,
          max: 100,
          },
          y:{
            stacked:true,
          }
        }
      }
    }
  }
  
  configGroup[1].options.scales.x.min=0;
  configGroup[3].options.scales.x.min=0;
  configGroup[4].options.scales.x.min=0;
  configGroup[5].options.scales.x.min=0;
  
  
  //OverWrite DataSet and conf
  dataSet[3].datasets[0].backgroundColor= COLORS_SHAKRA;
  dataSet[3].datasets[0].borderColor= 'rgb(255, 255, 255)';
  dataSet[3].datasets[0].data=dataSet[3].datasets[0].data.reduce((acc,cur)=>{acc.unshift(cur);return acc;},[]);
  dataSet[3].labels=dataSet[3].labels.reduce((acc,cur)=>{acc.unshift(cur);return acc;},[]);

  //Nata
  configGroup[6].type='pie';
  configGroup[6].options.plugins.legend.display=true;
  dataSet[6].datasets[0].backgroundColor= COLORS;
  dataSet[6].datasets[0].borderColor= 'rgb(255, 255, 255)';
  //Group

  Chart.register(ChartDataLabels);
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
  


 
  function getListOrder(tObj) {
    var list = jQuery(tObj).sortable("toArray");
    return list.toString();
  }
  
  
  jQuery( function() {
    jQuery( "#sortableTOP > .bottle" ).sort(function(a, b) {
      //console.log(a,a.getAttribute('data-position'));
        return parseInt(a.getAttribute('data-position')) - parseInt(b.getAttribute('data-position'));
    }).appendTo(jQuery( "#sortableTOP" ));

    jQuery( "#sortableTOP" ).sortable({ 
    axis: "y",
    update: function(event, ui) {
      var positions = jQuery("#sortableTOP").sortable('toArray',{attribute: 'data-id'}).toString();

      jQuery.ajax({
              url: wpApiSettings.root + 'aroma/v1/setPositions/',
              method: 'POST',
              data:{test_id:test_id, positions: positions},
              beforeSend: function (xhr) {
                  xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
              }
          }).done(function (response) {
              console.log(response);
          });
    }
  });
  
  });
 


  
</script>
</body>
</html>
      

