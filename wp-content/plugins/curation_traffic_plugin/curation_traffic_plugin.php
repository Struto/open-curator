<?php
/*
	Plugin Name: Curation Traffic
	Plugin URI: http://www.CurationTraffic.com
	Description: The Curation Traffic plugin brings you the CurateThis bookmarklet that allows you to easily curate content with the click of a button.
	Author: Curation Suite
	Version: 2.5
	Author URI: http://www.CurationSuite.com
*/

/*if(!function_exists('CurrentPageURL')) {
	require_once('license-check.php');
}*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define( 'MYPLUGINNAME_PATH', plugin_dir_path(__FILE__) );
define('BOOKMARKLET_TYPE', 'plugin');
define('CURATETHIS_VERSION', '2.2');

// this stops the header error coming up when we redirect on the admin page
function ct_plugin_app_output_buffer() {
	ob_start();
} // soi_output_buffer
add_action('init', 'ct_plugin_app_output_buffer');


if(!is_plugin_active('youbrandinc_products/youbrandinc-products-plugin.php')) 
{
	function showCTPluginMessage()
	{
		echo '<div id="message" class="error"><p><strong>To activate the Curation Traffic Plugin you have to have the Licensing Plugin Installed, <a href="https://members.youbrandinc.com/dashboard/getting-started/license-keys/" target="_blank">click here to download and install</a>.</strong></p></div>';
	}
	add_action('admin_notices', 'showCTPluginMessage');  	
}


add_theme_support( 'post-thumbnails', array( 'post' ) );

// fix dawsons site

// verify that ioncube is on, if so great if not then we send to page and deactivate plugin
function curaiton_traffic_plugin_activation_checks() {

	global $wp_version;
	$my_theme = wp_get_theme();
	//	echo $my_theme;
	// Curation Traffic - Toronto
	if ( $my_theme == 'Curation Traffic' ) {
		if( is_plugin_active($plugin) ) 
		{
			deactivate_plugins( $plugin );
			wp_die( "<strong>The ".$plugin_data['Name']." Plugin</strong> and the <strong>".$my_theme." Theme</strong> are not designed to be used together on the same site, the CurateThis & the bookmarklet is built right into the Curation Traffic Theme itself.<br /><br />If you're looking to change themes and use the plugin, first activate the new theme then actaviate the <strong>".$plugin_data['Name']." Plugin</strong>.<br /><br /><br />Back to the WordPress <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
		}
	}
}
// no longer needed now that we check in licensing plugin
add_action( 'admin_init', 'curaiton_traffic_plugin_activation_checks' );

function add_ct_plugin_menu()
{
 	add_submenu_page('youbrandinc', 'Curation Traffic Plugin', 'Curation Traffic Plugin', 'activate_plugins', 'curation_traffic_plugin', 'ct_plugin_admin_page');
}
add_action('admin_menu', 'add_ct_plugin_menu');

function ct_plugin_admin_page()
{

		// make sure we have the needed function to verify the nonce.
		if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
		include dirname(__FILE__).'/curation_traffic_plugin_admin.php';
		return true;

}
	
$un_encrpted_version = '';
	if (get_option('ybi_turn_off_ioncube_check') == 'yes')
		$un_encrpted_version = '_unc';

$ct_plugin_license_key = get_option('ct_plugin_license_key');
//$keyEncode = base64_encode($ct_plugin_license_key);
require  dirname(__FILE__) . '/plugin-updates/plugin-update-checker.php';
	$CTPluginUpdateChecker = PucFactory::buildUpdateChecker(
	'http://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=curation_traffic_plugin'.$un_encrpted_version.'&license_key='.$ct_plugin_license_key, //Metadata URL.
	__FILE__, //Full path to the main plugin file.
	'curation_traffic_plugin'.$un_encrpted_version //Plugin slug. Usually it's the same as the name of the directory.
	);


if (!function_exists('getDomainNameCurationTraffic')) {
	function getDomainNameCurationTraffic($domainb)
	{
		$bits = explode('/', $domainb);
		if ($bits[0]=='http:' || $bits[0]=='https:')
			{
			$domainb= $bits[2];
			} else {
			$domainb= $bits[0];
			}
		unset($bits);
		$bits = explode('.', $domainb);
		$idz=count($bits);
		$idz-=3;
		if (strlen($bits[($idz+2)])==2) {
		$url=$bits[$idz].'.'.$bits[($idz+1)].'.'.$bits[($idz+2)];
		} else if (strlen($bits[($idz+2)])==0) {
		$url=$bits[($idz)].'.'.$bits[($idz+1)];
		} else {
		$url=$bits[($idz+1)].'.'.$bits[($idz+2)];
		}
		return $url;
	}
}

function getTheCurPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
if (!function_exists('count_words')) {
function count_words($string){
   
   $string = trim(preg_replace("/\s+/"," ",$string));
   
   $word_array = explode(" ", $string);
   
   $num = count($word_array);
   
   return $num;
   
}
}

/*if (!function_exists('limit_words')) {

function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}
function limit_words_with_dots($string, $word_limit)
{
	$theRet = '';
	$lenContent = count_words($string);
	if($lenContent > $word_limit)
	{
	    $words = explode(" ",$string);
		$theRet = implode(" ",array_splice($words,0,$word_limit));
		if($lenContent > $word_limit)
			$theRet .= "...";
	    return $theRet;
	}
	else
	{
		return $string;
	}

}
}*/
// here we are adding a custom column to the WP-ADMIN posts page
if (!function_exists('my_columns')) {
add_filter('manage_posts_columns', 'my_columns');
function my_columns($columns) {

    $columns['curation'] = 'Curation';
    return $columns;
}
}

// below if there is a curated link attached to the post we assume it is a curated post
if (!function_exists('my_show_columns')) {
add_action('manage_posts_custom_column',  'my_show_columns');
function my_show_columns($name) {
    global $post;
    switch ($name) {
        case 'curation':
			$my_meta = get_post_meta($post->ID,'_my_meta',TRUE);

		if (isset($my_meta['curated_link'])) 
		{
			$curated_link = $my_meta['curated_link'];
			if($curated_link <> '')
			{
				// icon-check
				echo '<i class="icon-ok"></i>';
			}
			else
	            echo '';
		}
		else
		{
            echo '';
		}
    }
}
}



if (!function_exists('my_meta_init')) {
add_action('admin_init','my_meta_init');
	function my_meta_init()
	{
		// review the function reference for parameter details
		// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
		// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
		//wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
		wp_enqueue_style('my_meta_css', plugins_url('curation_traffic_plugin/curate_this/css/meta.css'));
		// review the function reference for parameter details
		// http://codex.wordpress.org/Function_Reference/add_meta_box
		foreach (array('post', 'curation') as $type) 
		{
			add_meta_box('my_all_meta', 'Curated Content', 'my_meta_setup', $type, 'normal', 'high');
		}
		add_action('save_post','my_meta_save');
	}
}

if (!function_exists('my_meta_setup')) {
	function my_meta_setup()
	{
		global $post;
		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		$meta = get_post_meta($post->ID,'_my_meta',TRUE);
		// instead of writing HTML here, lets do an include
		include(MYPLUGINNAME_PATH . 'curate_this/meta.php');
		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
}

if (!function_exists('my_meta_setup_curate')) {
function my_meta_setup_curate()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta = get_post_meta($post->ID,'_my_meta',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MYPLUGINNAME_PATH . 'curate_this/meta-curate.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
}
 
 
if (!function_exists('my_meta_save')) {
	function my_meta_save($post_id) 
	{
		// authentication checks
	
		// make sure data came from our meta box
		if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;
	
		// check user permissions
		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else 
		{
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	
		// authentication passed, save data
	
		// var types
		// single: _my_meta[var]
		// array: _my_meta[var][]
		// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]
	
		$current_data = get_post_meta($post_id, '_my_meta', TRUE);	
	 
		$new_data = $_POST['_my_meta'];
	
		my_meta_clean($new_data);
		
		if ($current_data) 
		{
			if (is_null($new_data)) delete_post_meta($post_id,'_my_meta');
			else update_post_meta($post_id,'_my_meta',$new_data);
		}
		elseif (!is_null($new_data))
		{
			add_post_meta($post_id,'_my_meta',$new_data,TRUE);
		}
	
		return $post_id;
	}
}

if (!function_exists('my_meta_clean')) {
function my_meta_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}

		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}
}

function add_style()
{
	add_theme_support('editor_style');
	add_editor_style( plugins_url( 'curation_traffic_plugin/curate_this/editor-style.css') );
}
//add_action( 'init', 'add_style' );

function catch_the_first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "";
  }
  return $first_img;
}

//add_filter( 'the_excerpt', 'fix_image',1 );
function fix_image($content)
{
	  global $post;
			$my_meta = get_post_meta($post->ID,'_my_meta',TRUE);
			$curated_link = $my_meta['curated_link'];
			if($curated_link <> '')
				$content = catch_the_first_image() . $content;	
}
?>