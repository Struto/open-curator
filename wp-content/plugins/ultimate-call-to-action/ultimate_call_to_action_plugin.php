<?php
/*
	Plugin Name: Ultimate Call to Action
	Plugin URI: http://curationtraffic.com/products/ultimate-call-to-action/
	Description: The Ultimate Call to Action allows you to create niched and category specific call to actions.
	Author: You Brand, Inc.
	Version: 1.3
	Author URI: http://www.YouBrandInc.com
*/

// we check to see if the products plugin is installed, if it's not then we show the message to install.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'MYPLUGINNAME_PATH', plugin_dir_path(__FILE__) );
// define the custom table names because the prefix might not be 'wp_'
define( 'ULTIMATE_CTA_CATEGORY_TABLE', $wpdb->prefix . 'ultimate_cta_category');
define( 'ULTIMATE_CTA_TABLE', $wpdb->prefix . 'ultimate_cta');

// here we create the database tables
define("MY_PLUGIN_VERSION", "1.1" ); //Declare the plugin version. This way we know the tables are always up to date. I usually declare this in my main plugin file.
require_once("includes/functions.php");
require_once("includes/database_install.php");
register_activation_hook(__FILE__,'my_plugin_data_tables_install');

// this stops the header error coming up when we redirect on the admin page
function ucta_plugin_app_output_buffer() {
	ob_start();
} // soi_output_buffer
add_action('init', 'ucta_plugin_app_output_buffer');

if(!is_plugin_active('youbrandinc_products/youbrandinc-products-plugin.php')) 
{
	function showUCTAMessage()
	{
		// <div id="message" class="updated fade">
		echo '<div id="message" class="error"><p><strong>To activate the Ultimate Call to Action Plugin you have to have the Licensing Plugin Installed, <a href="https://members.youbrandinc.com/dashboard/getting-started/license-keys/" target="_blank">click here to download and install</a>.</strong></p></div>';
	}
	add_action('admin_notices', 'showUCTAMessage');  	
}

	
if( !is_admin()){
	//  well some themes don't include the latest jquery and we need jquery to do the feature_box so let's register it
// taken out
//   wp_deregister_script('jquery');
//   wp_register_script('jquery', ("http://code.jquery.com/jquery-latest.min.js"), false);
//   wp_enqueue_script('jquery');
}


function add_ucta_menu()
{
	add_submenu_page('youbrandinc', 'Ultimate CTA', 'Ultimate CTA', 'administrator', 'admin.php?page=ucta_plugin', 'ucta_admin_page');
}
add_action('admin_menu', 'add_ucta_menu');
function ucta_admin_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }

	include dirname(__FILE__).'/ultimate_call_to_action_plugin_admin.php';
	return true;
}

$ucta_license_key = get_option('ucta_license_key');
//$keyEncode = base64_encode($ucta_license_key);
require  dirname(__FILE__) . '/plugin-updates/plugin-update-checker.php';
$UCTAPluginUpdateChecker = PucFactory::buildUpdateChecker(
'http://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=ultimate-call-to-action&license_key='.$ucta_license_key, //Metadata URL.
__FILE__, //Full path to the main plugin file.
'ultimate-call-to-action' //Plugin slug. Usually it's the same as the name of the directory.
);
	

// register our CSS and JS for admin functions
function ucta_css_js()
{
	wp_register_style('ucta_css_js', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('ucta_css_js');
	wp_register_script( 'ucta_functions', plugins_url('functions.js',__FILE__ ));
	wp_enqueue_script('ucta_functions');
}
add_action( 'admin_init','ucta_css_js');

require("ultimate_call_to_action_widget.php");

function front_end_init() {
	if(!is_admin())
	{
		wp_register_script('feature_box_script', plugins_url('feature_box.js', __FILE__), false, true);
		wp_enqueue_script("feature_box_script");
	}
}
add_action('init', 'front_end_init');

// this function will return the top Category ID no matter how many levels you are down.
function pa_category_top_parent_id ($catid) 
{
	while ($catid) 
	{
		$cat = get_category($catid); // get the object for the catid
		$catid = $cat->category_parent; // assign parent ID (if exists) to $catid
		// the while loop will continue whilst there is a $catid
		// when there is no longer a parent $catid will be NULL so we can assign our $catParent
		$catParent = $cat->cat_ID;
	 }
	return $catParent;
}


// NOTE: this function is within the above function getCTAsForPost()
function buildSQL($inType, $inCTA_ID)
{
	// we go up three levels in the category tree (if exitsts) to get all relevant CTA's
	// the logic here is if the current category doesn't have one maybe the parent might, or the grandparent
	// we assume that this is the silo of the site and it would make sense
	$categories = get_the_category();
	$current_cat_id = $categories[0]->cat_ID;
	$parent_cat_id = $categories[0]->category_parent;
	$super_parent_id = pa_category_top_parent_id ($current_cat_id);

	// let's build the SQL to get all entries that don't have an is_all set, it it's set to is_all we get that down below as a fall back
	$theReturnSQL = "";
	$theReturnSQL = "SELECT * FROM ".ULTIMATE_CTA_TABLE." CTA LEFT JOIN ".ULTIMATE_CTA_CATEGORY_TABLE." CTAC ON CTA.id = CTAC.ultimate_cta_id WHERE (CTA.type = '".$inType."' AND (CTAC.category_id = ".$current_cat_id;
	if($parent_cat_id > 0)
		$theReturnSQL .= " OR CTAC.category_id = ". $parent_cat_id ;
	if($super_parent_id > 0)
		$theReturnSQL .= " OR CTAC.category_id = ". $super_parent_id ;
	$theReturnSQL .= ") and is_all = 0 and is_active=1";
	if($inCTA_ID > 0)
		$theReturnSQL .= " and CTA.id = " . $inCTA_ID;

	$theReturnSQL .= ") GROUP BY CTA.id";

	//echo '<p>' . $theReturnSQL . '</p>';
	return $theReturnSQL;
}

// NOTE: this function is within the above function getCTAsForPost()
function getFinalCTA($inAllCTAs, $inType, $inCTA_ID)
{
	global $wpdb;
	// we go up three levels in the category to get a CAll to ACtion
	$categories = get_the_category();
	$current_cat_id = $categories[0]->cat_ID;
	$parent_cat_id = $categories[0]->category_parent;
	$super_parent_id = pa_category_top_parent_id ($current_cat_id);
	
	// set the arrays that will hold the sorts we do below
	$currentArray = array();
	$parentArray = array();
	$superparentArray = array();
	// we are sorting the passed in inAllCTAs which is really just the returned rows from the SQL query we built above
	// we do this so we can seperate each category call to action into it's own array
	// NOTE: we are putting in the returned OBJECT in the array
	foreach( $inAllCTAs as $result )
	{
		if($current_cat_id == $result->category_id)
		{
			$currentArray[] = $result;
			continue;
		}
		if($parent_cat_id == $result->category_id)
		{
			$parentArray[] = $result;
			continue;
		}
		if($super_parent_id == $result->category_id)
		{
			$superparentArray[] = $result;
			continue;
		}
	}

	// now we check seperated call to actions, if it has more than one then we randomize which one we return
	// Note: we return a single OBJECT that is a ROW from the QUERY above.
	// we do this for all three levels of categories
	$curCount = count($currentArray);
	if($curCount > 0)
	{
		if($curCount >1)
		{
			$random = rand(0, $curCount);
			return $currentArray[$random];
		}
		else
			return $currentArray[0];
	}
	
	$curCount = count($parentArray);
	if($curCount > 0)
	{
		if($curCount >1)
		{
			$random = rand(0, $curCount);
			return $parentArray[$random];
		}
		else
			return $parentArray[0];
	}
	
	$curCount = count($superparentArray);
	if($curCount > 0)
	{
		if($curCount >1)
		{
			$random = rand(0, $curCount);
			return $superparentArray[$random];
		}
		else
			return $superparentArray[0];
	}
	// if it got here then it didn't return, so it must have not found a CTA, so now we will query the DB and get all the call to actions that are set to is_all
	// if there is more than one then we will randomize the display and return the object
	$theSQL = "SELECT * FROM ".ULTIMATE_CTA_TABLE." CTA WHERE CTA.type = '".$inType."' AND is_all = 1 and is_active = 1";
	if($inCTA_ID > 0)
		$theSQL .= " and CTA.id = " . $inCTA_ID;
	
	$allCTAs = $wpdb->get_results($theSQL);
	$curCount = count($allCTAs);
	// echo '<p>inType: ' . $inType . '</p>';
	// echo '<p>curCount: ' . $curCount . '</p>';
	if($curCount > 0)
	{
		if($curCount >1)
		{
			// since there is more than one let's get a random CTA, also don't forget the logic error below, we subtract one from the current count
			$random = rand(0, $curCount-1);
			return $allCTAs[$random];
			//do nothing		
		}
		else
		{
			// since there is only one then we return the single CTA
			return $allCTAs[0];
		}
	} //if($curCount > 0)
} // end function getFinalCTA




function getCTAsForPost() {
	global $post,$wpdb;
	
	$isAllCTAs = array();
	//find all feature boxes for category, parent, and grandparent
	$allCTAs = $wpdb->get_results(buildSQL('inline_below', 0));
	$theCTA =  getFinalCTA($allCTAs, 'inline_below',0);
	$returnCTAs['inline_below'] = $theCTA;
	$allCTAs = $wpdb->get_results(buildSQL('custom', 0));
	$theCTA =  getFinalCTA($allCTAs, 'custom',0);
	$returnCTAs['custom'] = $theCTA;
	$allCTAs = $wpdb->get_results(buildSQL('inline_above', 0));
	$theCTA =  getFinalCTA($allCTAs, 'inline_above',0);
	$returnCTAs['inline_above'] = $theCTA;
	//$data[$key] = $value;
	return $returnCTAs;
} // function getCTAsForPost()

// main functionality for this plugin
function addCTAContent($content = '') {
	global $post,$wpdb;
	// here is the we check to see if this is a single page, we only show CTA's on single pages
	if(is_single())
	{
		// this one function does the work, calling other functions, etc, it also returns an array of CTA objects
		$CTAs = getCTAsForPost();
		/* FOR debugging
			echo '< inline_above' . var_dump($get_cta['inline_above']);
			echo '< feature_box:' . var_dump($get_cta['custom']);
			echo '< inline_below' . var_dump($get_cta['inline_below']);
		*/	
		// get the inline_above CTA if it exists and we put it before the content	
		if (array_key_exists('inline_above', $CTAs)) 
		{
			if($CTAs['inline_above']->width > 0)
				$addStyle = ' style="width:' . $CTAs['inline_above']->width . 'px;"';
			else
				$addStyle = '';
			$content = '<div id="cta_inline_above"'.$addStyle.'>' . stripslashes($CTAs['inline_above']->content) . '</div>' . $content;
		}
		// get the inline_below CTA if it exists and we put it after the content
		if (array_key_exists('inline_below', $CTAs)) 
		{
			if($CTAs['inline_below']->width > 0)
				$addStyle = ' style="width:' . $CTAs['inline_below']->width . 'px;"';
			else
				$addStyle = '';
			$content .= '<div id="cta_inline_below"'.$addStyle.'>' . stripslashes($CTAs['inline_below']->content) . '</div>';
		}
		// get the feature-box and add it last
		// this is different because we are using a jQuery call to move the feature box to where the user has specified it should show up within their site
		if (array_key_exists('custom', $CTAs)) 
		{
			if($CTAs['custom']->width > 0)
				$addStyle = ' style="width:' . $CTAs['custom']->width . 'px;"';
			else
				$addStyle = '';
			$content .= '<div id="cta_custom"'.$addStyle.'>' . stripslashes($CTAs['custom']->content) . '</div>';
			$content .= '<script>featureBoxVar = "'.$CTAs['custom']->insert_before.'";</script>';
	
		}
		else
		{
			$content .= '<script>var featureBoxVar = "";</script>';
		}
	}
	else
	{
		$content .= '<script>var featureBoxVar = "";</script>';
	}
	return $content;
}
add_filter('the_content', 'addCTAContent');
/*
if(!function_exists('youbrandinc_license_activation_page')) {
	require_once('license-check.php');
}

*/
?>