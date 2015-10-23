<?php 
function my_plugin_run_install ($table_name, $table_version, $sql) {
   global $wpdb;
   $wp_table_name = $wpdb->prefix . $table_name;
   if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
        $sql_create_table = "CREATE TABLE " . $wp_table_name . " ( " . $sql . " ) ;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_create_table);

    //create option for table version
        $option_name = $table_name.'_tbl_version';
        $newvalue = $table_version;
          if ( get_option($option_name) ) {
                update_option($option_name, $newvalue);
              } else {
                $deprecated=' ';
                $autoload='no';
                add_option($option_name, $newvalue, $deprecated, $autoload);
          }
    //create option for table name
        $option_name = $table_name.'_tbl';
        $newvalue = $wp_table_name;
          if ( get_option($option_name) ) {
                update_option($option_name, $newvalue);
              } else {
                $deprecated=' ';
                $autoload='no';
                add_option($option_name, $newvalue, $deprecated, $autoload);
          }
}

// Code here with new database upgrade info/table Must change version number to work.
$installed_ver = get_option( $table_name.'_tbl_version' );
 if( $installed_ver != $table_version ) {
  $sql_create_table = "CREATE TABLE " . $wp_table_name . " ( " . $sql . " ) ;";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql_create_table);
  update_option( $table_name.'_tbl_version', $table_version );
  }
}
?>