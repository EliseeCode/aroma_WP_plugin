<?php
if(isset($_POST["delete"])){
  tables_delete();
}
if(isset($_POST["install"])){
  tables_install();
}
if(isset($_POST["install_data"])){
  tables_install_data();
}
?>
<div>

  <h1 class="title">DataBase Administration</h1>
  <div class="box m-3">
    <form action="" method="post" >
    <button class="button m-2 is-primary" name="install">DB install</button>
    <button class="button m-2 is-warning" name="install_data">DB import data</button>
    <button class="button m-2 is-danger" name="delete">DB delete</button>
  </div>
</div>  