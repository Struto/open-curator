<?php 
function my_plugin_data_tables_install () {
$table_version = MY_PLUGIN_VERSION; //Call the plugin version.
//Install the first table
$table_name = "ultimate_cta";
$sql = "id mediumint(9) NOT NULL AUTO_INCREMENT,
	  create_ts timestamp NOT NULL,
	  name tinytext NOT NULL,
	  type VARCHAR(20) DEFAULT NULL,
	  content text NOT NULL,
	  width smallint NOT NULL,
	  insert_before VARCHAR(100) DEFAULT NULL,
	  is_all boolean,
	  is_active boolean,
	  UNIQUE KEY id (id)";
my_plugin_run_install  ($table_name, $table_version, $sql);
 
//Install the second table
$table_name = "ultimate_cta_category";
$sql = "id mediumint(9) NOT NULL AUTO_INCREMENT,
	   category_id bigint(20) NOT NULL,
	   ultimate_cta_id mediumint(9) NOT NULL,
	   UNIQUE KEY id (id)";
my_plugin_run_install  ($table_name, $table_version, $sql);
}
?>