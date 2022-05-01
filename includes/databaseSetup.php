<?php
global $_db_version;
$aroma_db_version = '1.1';

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
		tag_id mediumint(9) NOT NULL
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
	
	$welcome_name = 'Lavender';
	
	$table_name = $wpdb->prefix . 'bottles';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
		) 
	);
}

