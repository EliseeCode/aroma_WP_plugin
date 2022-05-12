<?php
global $_db_version;
$aroma_db_version = '1.3';

function tables_delete() {
	// Delete table when deactivate
	global $wpdb;
	$tableNames=['aroma_bottles',
	'aroma_tests',
	'aroma_tags',
	'aroma_groups',
	'aroma_group_tag',
	'aroma_bottle_tag',
	'aroma_test_bottle_preference'];
	forEach($tableNames as $tableNameEnding){
		$table_name = $wpdb->prefix . $tableNameEnding;
		$sql = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query($sql);
	}
	delete_option("aroma_db_version");
}
function tables_empty() {
	// Delete table when deactivate
	global $wpdb;
	$tableNames=['aroma_bottles',
	'aroma_tests',
	'aroma_tags',
	'aroma_groups',
	'aroma_group_tag',
	'aroma_bottle_tag',
	'aroma_test_bottle_preference'];
	forEach($tableNames as $tableNameEnding){
		$table_name = $wpdb->prefix . $tableNameEnding;
		$sql = "IF EXISTS TRUNCATE TABLE $table_name;";
		$wpdb->query($sql);
	}
	delete_option("aroma_db_version");
}
function tables_install() {
	global $wpdb;
	global $aroma_db_version;
	
	$charset_collate = $wpdb->get_charset_collate();
	//TABLE BOTTLES
	$table_name = $wpdb->prefix . 'aroma_bottles';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		color tinytext DEFAULT NULL,
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	//TABLE ACCOUNTS
	$table_name = $wpdb->prefix . 'aroma_tests';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		surname tinytext NOT NULL,
		comment varchar(1000) DEFAULT '',
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	//TABLE BOTTLE_PROPERTY
	$table_name = $wpdb->prefix . 'aroma_bottle_tag';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		bottle_id mediumint(9) NOT NULL,
		tag_id mediumint(9) NOT NULL,
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//TABLE GROUP_TAG
	$table_name = $wpdb->prefix . 'aroma_group_tag';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		group_id mediumint(9) NOT NULL,
		tag_id mediumint(9) NOT NULL,
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//TABLE GROUPS
	$table_name = $wpdb->prefix . 'aroma_groups';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//TABLE TAGS
	$table_name = $wpdb->prefix . 'aroma_tags';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//TABLE ACCOUNTS
	$table_name = $wpdb->prefix . 'aroma_test_bottle_preference';
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		bottle_id mediumint(9) NOT NULL,
		test_id mediumint(9) NOT NULL,
		preference TINYINT NOT NULL,
		position TINYINT DEFAULT '-1',
		creator_id mediumint(9),
		PRIMARY KEY  (id)
	) $charset_collate;";

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	add_option( 'aroma_db_version', $aroma_db_version );
}

function tables_install_data() {
	global $wpdb;

	//BOTTLES
	$table_name = $wpdb->prefix . 'aroma_bottles';
	$wpdb->query("TRUNCATE TABLE $table_name");
	$handle = fopen(AROMA_PATH.'DBTable_init/Bottles Aroma - bottles.csv', "r");
        $c = 0;
        while(($row = fgetcsv($handle, 500, ",")) !== false){
          $id = $row[0];
          $name = $row[1];
          $color="#533d75";
		  $user_id=get_current_user_id();
          $data = array(
            'id' => $id,
            'name' => $name,
            'color' => $color,
			'time' => current_time( 'mysql' ),
			'creator_id'=> $user_id
          );
          $wpdb->replace( $table_name , $data );
        }

	//TAGS
	$table_name = $wpdb->prefix . 'aroma_tags';
	$wpdb->query("TRUNCATE TABLE $table_name");
	$handle = fopen(AROMA_PATH.'DBTable_init/Bottles Aroma - tags.csv', "r");
        $c = 0;
        while(($row = fgetcsv($handle, 500, ",")) !== false){
          $id = $row[0];
          $name = $row[1];
		  $user_id=get_current_user_id();
          $data = array(
            'id' => $id,
            'name' => $name,
			'time' => current_time( 'mysql' ),
			'creator_id'=> $user_id
          );
          $wpdb->replace( $table_name , $data );
        }
		
	//GROUPS
	$table_name = $wpdb->prefix . 'aroma_groups';
	$wpdb->query("TRUNCATE TABLE $table_name");
	$handle = fopen(AROMA_PATH.'DBTable_init/Bottles Aroma - groups.csv', "r");
        $c = 0;
        while(($row = fgetcsv($handle, 500, ",")) !== false){
          $id = $row[0];
          $name = $row[1];
		  $user_id=get_current_user_id();
          $data = array(
            'id' => $id,
            'name' => $name,
			'time' => current_time( 'mysql' ),
			'creator_id'=> $user_id
          );
          $wpdb->replace( $table_name , $data );
        }
		
	//GROUP_TAG
	$table_name = $wpdb->prefix . 'aroma_group_tag';
	$wpdb->query("TRUNCATE TABLE $table_name");
	$handle = fopen(AROMA_PATH.'DBTable_init/Bottles Aroma - group_tag.csv', "r");
        $c = 0;
        while(($row = fgetcsv($handle, 500, ",")) !== false){
          $group_id = $row[1];
          $tag_id = $row[0];
		  $user_id=get_current_user_id();
          $data = array(
            'group_id' => $group_id,
            'tag_id' => $tag_id,
			'time' => current_time( 'mysql' ),
			'creator_id'=> $user_id
          );
          $wpdb->replace( $table_name , $data );
        }
		
	//BOTTLE_TAG
	$table_name = $wpdb->prefix . 'aroma_bottle_tag';
	$wpdb->query("TRUNCATE TABLE $table_name");
	$handle = fopen(AROMA_PATH.'DBTable_init/Bottles Aroma - bottle_tag.csv', "r");
        $c = 0;
        while(($row = fgetcsv($handle, 500, ",")) !== false){
          $bottle_id = $row[0];
          $tag_id = $row[1];
		  $user_id=get_current_user_id();
          $data = array(
            'bottle_id' => $bottle_id,
            'tag_id' => $tag_id,
			'time' => current_time( 'mysql' ),
			'creator_id'=> $user_id
          );
          $wpdb->replace( $table_name , $data );
        }	
	
}


