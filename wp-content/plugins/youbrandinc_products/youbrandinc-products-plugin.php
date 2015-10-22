<?php 
/*
	Plugin Name: You Brand, Inc. Products
	Plugin URI: https://members.youbrandinc.com/dashboard/getting-started/license-keys/
	Description: Plugin for licensing and using You Brand, Inc. products
	Author: You Brand, Inc.
	Version: 1.56
	Author URI: http://www.YouBrandInc.com
*/

// add the menu items( we create a high level You Brand, Inc menu and a license activation menu)
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
global $ybi_plugin_active;
$is_ioncube_installed = false;

function ybi_isIoncubeInstalled()
{
	// added this as a temp fix as we are most likely removing ioncube restrictions
	//return true;
	if ( is_admin() ) {
	
		if(get_option('ybi_turn_off_ioncube_check') == 'yes')
			return true;
		
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );
		//return 	extension_loaded("IonCube Loader");
		return true;
		
	}
}
function ybi_checkPHPVersionGood()
{
	return (version_compare(phpversion(), '5.3.0', '>='));
}


function ioncube_css_js()
{

	$curPage = $_GET["page"];
	$loadScriptsArr = array('youbrandinc-support-news','youbrandinc-install-ioncube','youbrandinc-adv-setup');

	if(in_array($curPage,$loadScriptsArr))
	{
		//wp_register_style( 'slider_ioncube', plugins_url('css/cupertino/jquery-ui-1.10.2.custom.css',__FILE__ ));
		//wp_enqueue_style( 'slider_ioncube' );
		//wp_register_script( 'jqui-handle', 'http://code.jquery.com/ui/1.10.2/jquery-ui.js','1.10.2');
		//wp_enqueue_script('jqui-handle');
		//wp_enqueue_script( 'ioncube-check-scripts', plugins_url('js/ioncube-page.js', __FILE__ ), false, true );
	}

}
//add_action( 'admin_init','ioncube_css_js');
//add_action( 'admin_enqueue_scripts', 'ioncube_css_js' );
				
if(!function_exists('ybi_product_plugins_activation'))
{
	function ybi_product_plugins_activation()
	{
		update_option('ybi_turn_off_ioncube_check','yes');	
	}
	register_activation_hook( __FILE__, 'ybi_product_plugins_activation' );
}
				

$ybi_plugin_active = true;
define("YBIMULTIPLIER", 1);
function add_youbrandinc_menu_items()
{

	define("YBI_SUPPORT_URL", 'http://members.youbrandinc.com/support/');
	
	$allowed_group = 'manage_options';
	require_once(ABSPATH .'wp-includes/pluggable.php'); // this is here so we can call the user level down below
	$curation_suite_user_level = '';
	$options = '';
	$options = get_option('curation_suite_data');
	$curation_suite_user_level = $options['curation_suite_user_level'];
		
	if($curation_suite_user_level == '')
		$curation_suite_user_level = 'edit_posts';

    $curation_suite_user_level = 'edit_posts';

    add_menu_page('You Brand, Inc.','You Brand, Inc.',$curation_suite_user_level,'youbrandinc','youbrandinc_products_page',plugins_url('youbrandinc_products/i/you-brand-guys-16.png'),82.44);
		// this removes the main menu from being in the submenu
	add_submenu_page('youbrandinc','','',$curation_suite_user_level,'youbrandinc','youbrandinc_products_page');
	// this adds the base products page, but we wanted to rename the submenu item support/news
	add_submenu_page('youbrandinc', 'Support & News', 'Support & News', 'activate_plugins', 'youbrandinc-support-news', 'youbrandinc_products_page');

	if(get_option('ybi_super_admin') == "on")
	{
		add_submenu_page('youbrandinc', 'Advanced Setup', 'Advanced Setup', 'activate_plugins', 'youbrandinc-adv-setup', 'youbrandinc_adv_setup_page');
		add_action('admin_bar_menu', 'ybi_adv_custom_toolbar_links', 999);
	}

	// check to see if we have any plugins that require ioncube
	//$anyPluginRequireIoncube = isAnyYBIPluginThatRequiresIoncubeActive();
	$showAnalytics = true;
	// is ioncube installed and is there any plugins activated that require it

	//ioncube is activated and a plugin requires licensing, so show the page
	add_submenu_page('youbrandinc', 'License Activation', 'License Activation', 'manage_options', 'youbrandinc-license', 'youbrandinc_license_activation_page');


	// do we show the analytics menu item? Only if ioncube is not required, see above. This also will show if they have any of our themes installed
	if($showAnalytics)
		add_submenu_page('youbrandinc', 'Analytics and Shares', 'Analytics and Shares', 'activate_plugins', 'youbrandinc-analytic-share', 'youbrandinc_analytic_share_page');

	//add_submenu_page('youbrandinc', 'Install Ioncube', '<i class="fa fa-exclamation-triangle" style="color: #C80000;"></i>  Install Ioncube', 'administrator', 'youbrandinc-install-ioncube', 'youbrandinc_install_ioncube_page');
}
add_action('admin_menu', 'add_youbrandinc_menu_items', 1);




// the products page that is an overview of products and support messages
function youbrandinc_products_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	
	include dirname(__FILE__).'/youbrandinc-products-overview.php';
	return true;
}
function youbrandinc_analytic_share_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	
	include dirname(__FILE__).'/youbrandinc-analytic-share.php';
	return true;
}
function youbrandinc_install_ioncube_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	
	include dirname(__FILE__).'/youbrandinc-install-ioncube.php';
	return true;
}
function youbrandinc_adv_setup_page()
{
	// make sure we have the needed function to verify the nonce.
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	
	include dirname(__FILE__).'/server-setup/advanced-ybi-setup.php';
	return true;
}

function yb_products_cs_js()
{
	
	$curPage = $_GET["page"];
	$loadScriptsArr = array('youbrandinc','youbrandinc-support-news','youbrandinc-install-ioncube','youbrandinc-adv-setup','youbrandinc-analytic-share','curation_suite_display_settings');

	if(in_array($curPage,$loadScriptsArr))
	{
		wp_register_style('yb_products_cs_js', plugins_url('css/ybi-products-style.css',__FILE__ ));
		wp_enqueue_style('yb_products_cs_js');
	}
}
add_action( 'admin_init','yb_products_cs_js');

//if(is_admin())
//{
//	wp_register_style('yb_products_cs_js', plugins_url('css/ybi-products-style.css',__FILE__ ));
//	wp_enqueue_style('yb_products_cs_js');
	wp_register_style('fontawesome', plugins_url('font-awesome/css/font-awesome.min.css',__FILE__,'4.3' ));
	wp_enqueue_style('fontawesome');
//}

  
// include the license check code

//if (ybi_checkPHPVersionGood())
require_once('license-check.php');



$ybi_license_key = 'nokeyrequired';
require  dirname(__FILE__) . '/plugin-updates/plugin-update-checker.php';
	$YBIProductUpdateChecker = PucFactory::buildUpdateChecker(
	'http://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=youbrandinc_products&license_key='.$ybi_license_key, //Metadata URL.
	__FILE__, //Full path to the main plugin file.
	'youbrandinc_products' //Plugin slug. Usually it's the same as the name of the directory.
	);

// checks to see if any plugin is activae that requires ioncube
function isAnyYBIPluginThatRequiresIoncubeActive()
{
	$pluginActive = false;
	// these products require ioncube
	$PluginArr = array("Curation Traffic Plugin","Ultimate Call to Action", "Social Quote Traffic","WP RoundUp","Curation Suite");
	foreach($PluginArr as $val) 
	{
		// is the plugin activated
		if(isYBIPluginActive($val))
		{
			// if so return true and break as we found one that requires ioncube
			$pluginActive = true;
			break;	
		}
	}
	return $pluginActive;
}

// checks to see if any of the YBI plugins are active
function isYBIPluginActive($inPluginName)
{
	$pluginCheckURL = '';
	switch($inPluginName) {
		case 'Curation Traffic Plugin';
		$pluginCheckURL = 'curation_traffic_plugin/curation_traffic_plugin.php';
		break;
		case 'Ultimate Call to Action';
		$pluginCheckURL = 'ultimate-call-to-action/ultimate_call_to_action_plugin.php';
		break;
		case 'Social Quote Traffic';
		$pluginCheckURL = 'social-quote-traffic/social_quote_traffic.php';
		break;
		case 'WP RoundUp';
		$pluginCheckURL = 'wp-roundup/wp-roundup.php';
		break;
		case 'Curation Suite';
		$pluginCheckURL = 'curation-suite/curation-suite.php';
		break;
	}
	
	return is_plugin_active($pluginCheckURL);
}
// we apply some minor styling to the customizer page
function ybi_theme_customize_style() {
	//we probably should check here to ensure they are only running our themes
    //wp_enqueue_style('ybi_custom_customizer', plugins_url('css/admin-style.css',__FILE__ ));
}
add_action( 'customize_controls_enqueue_scripts', 'ybi_theme_customize_style' );

function getFeedYBI($feed_url, $total_posts) {  
    $content = file_get_contents($feed_url);  
    $x = new SimpleXmlElement($content);  
    echo "<ul>";  
	$i = 1;
    foreach($x->channel->item as $entry) {  
        echo "<li><a href='$entry->link?source=ybi_product_tab' title='$entry->title' target='_blank'>" . $entry->title . "</a></li>";
		
		if($i == $total_posts)
			break;
		$i++;
    }  
    echo "</ul>";  
}  

// function to display number of post views.
// for the plugin of CT this is located in the CT plugin file
// for the theme the tracking is located in the single.php
function getPostViewsYBI($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Views";
    }
	if($count == 1)
		return $count.' View';
	else
    	return $count.' Views';
}
 
// function to count views.
function setPostViewsYBI($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
add_filter( 'the_content', 'recordYBIPageView' );
function recordYBIPageView($content)
{
	if ( is_single() )
		if (function_exists('setPostViewsYBI'))
			setPostViewsYBI(get_the_ID());
	return $content;
}
 
// Add the post views column in WP-Admin
add_filter('manage_posts_columns', 'posts_YBI_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views_YBI',5,2);
function posts_YBI_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views_YBI($column_name, $id){
    if($column_name === 'post_views'){
        echo number_format(getPostViewsYBI(get_the_ID())*YBIMULTIPLIER);
    }
}

// these are customizations for the cusomizer for the themes we built
add_action('customize_register', 'ybiproducts_customize_register');
function ybiproducts_customize_register( $wp_customize )
{
	// we should probably check against themes here
	//	$my_theme = wp_get_theme();
	//echo $my_theme->get( 'Name' );

	$wp_customize->add_section(
		'ybi_post_options',
		array(
			'title'		=> __('Advanced Options', 'ybi_advanced_options'),
			'priority'	=> 33,
		)
	);
	
	// display tags
	$wp_customize->add_setting(
		'ybi_no_display_date',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'ybi_no_display_date',
		array(
			'label' => __('Don\'t Show Post Dates', 'ybi_advanced_options'),
			'section' => 'ybi_post_options',
			'settings' => 'ybi_no_display_date',
			'type' => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'ybi_no_display_author',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'ybi_no_display_author',
		array(
			'label' => __('Don\'t Show Author', 'ybi_advanced_options'),
			'section' => 'ybi_post_options',
			'settings' => 'ybi_no_display_author',
			'type' => 'checkbox',
		)
	);
}
/**
* Creates a .txt file backup located in the /youbrandinc_products/server-setup/backups/ directory of the file passed
* the file is also appended with a timestamp
* the final file would be: $newFileBase_timestamp
*
* @param string $file - the file where file_get_contents is called
* @param string $newFileBase - the filename, is appended with timestamp
*
* @return string $newFilename - returns the new filename that was created ($newFileBase_timestamp)
*/
function ybi_createFileTextDownloadBackup($file, $newFileBase)
{
	$scriptFileName = $_SERVER['SCRIPT_FILENAME'];
	$scriptFileName = str_replace("wp-admin/admin.php","",$scriptFileName);
	$backupDir = $scriptFileName . 'wp-content/plugins/youbrandinc_products/server-setup/backups/';
	$fileContents = file_get_contents($file);
	$ts = date('m-d-Y_H-i-s');
	$newFilename = $newFileBase . '_' . $ts . '.txt';
	$fullPathtoFile = $backupDir . $newFilename;
	file_put_contents($fullPathtoFile, $fileContents);
	return $newFilename;
}
/**
* This function will take a file name and the contents of a file and create a backup in the same directory
* it does this by replacing appending the file name with _ybi_backup_ and a timestamp, it also replaces any dots (.) in the filename for good measure
*
* @param string $file the file that is to be backed up
* @param string $fileContents the contents of the file to be backed up
*
* @return string $newFilename - returns the new filename that was created
*/
function ybi_createFileLocationBackup($file, $fileContents)
{
	$ts = date('m-d-Y_H-i-s');
	// replace dot with underscore
	$file = str_replace(".","_",$file);
	$newFilename = $file.'_ybi_backup_'.$ts.'.txt';
	file_put_contents($newFilename, $fileContents);
	return $newFilename;
}


/**
* If any plugin requires ioncube and if ioncube is not installed then...
* we add the admin_init action that will copy the check-server.php to the wp-admin folder
*/
//if(isAnyYBIPluginThatRequiresIoncubeActive() && !ybi_isIoncubeInstalled())
//	add_action( 'admin_init', 'ybi_copy_server_check_wpadmin' );
/**
* copies the check-server file to the wp-admin folder (used in iframes for checking the status of ioncube & php)
* we found this is the best place to test if ioncube is activated as wordpress runs from this directory
*
* @return void
*/
function ybi_copy_server_check_wpadmin()
{
	$copy_to_directory = str_replace("wp-content","wp-admin/",WP_CONTENT_DIR);
	$path = plugin_dir_path( __FILE__ );
	$file = $path . 'server-setup/check-server.php';
	if(!file_exists($copy_to_directory . 'check-server.php'))
	{
		$newfile = $copy_to_directory . 'check-server.php';
		if ( copy($file, $newfile) ) {

		}
	}

}

// * this function is no longer used adn will be taken out in a later version
//add_action( 'admin_init', 'ybi_products_before_activate_checks' );
function ybi_products_before_activate_checks() 
{
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );
	if(!ybi_isIoncubeInstalled())
	{
			deactivate_plugins( $plugin );
			wp_die( '<strong>The '.$plugin_data['Name'].' Plugin</strong> requires <strong>Ioncube</strong> to be activated on your server. Activating Ioncube is pretty easy and you can find how to do that by visiting <a href="https://youbrandinc.zendesk.com/entries/22130398-How-to-Get-ionCube-Activated-on-Your-Server" target="_blank">How to Get Ioncube Activated on Your Server.</a><br /><br /><br />Back to the WordPress <a href="'.get_admin_url(null, 'plugins.php').'">Plugins page</a>.');
	}
	else
	{
		$is_ioncube_installed = true;
	}

}
function setYBISuperAdmin($inSet)
{
	// this will turn it on or off, send it on or off
	if($inSet != '')
		update_option('ybi_super_admin', $inSet);	
}
if (isset($_GET['YBIAdmin']))
	setYBISuperAdmin($_GET["YBIAdmin"]);


// add a link to the WP Toolbar
function ybi_adv_custom_toolbar_links($wp_admin_bar) {
	if(!current_user_can( 'administrator' ))
		return;
	
	$args = array(
		'id' => 'ybi_plugin_shortcut',
		'title' => 'Upload Plugin', 
		'href' => admin_url('plugin-install.php?tab=upload'), 
		'meta' => array(
			'class' => 'ybi_plugin_shortcut', 
			'title' => 'Upload Plugin'
			)
	);
	$wp_admin_bar->add_node($args);
	
	$args = array(
		'id' => 'ybi_theme_shortcut',
		'title' => 'Upload Theme', 
		'href' => admin_url('theme-install.php?upload'), 
		'meta' => array(
			'class' => 'ybi_theme_shortcut', 
			'title' => 'Upload Theme'
			)
	);
	$wp_admin_bar->add_node($args);
	
	$args = array(
		'id' => 'ybi_turn_off_admin',
		'title' => 'Turn Off YBI Admin', 
		'href' => admin_url('?YBIAdmin=off'), 
		'meta' => array(
			'class' => 'ybi_turn_off_admin', 
			'title' => 'Turn Off YBI Admin'
			)
	);
	$wp_admin_bar->add_node($args);
}



function getServerCheckIframe($inName, $inCheckType)
{
	/*
		<p class="advanced_server_check"><a href="<?php echo get_bloginfo('url'); ?>/wp-admin/check-server.php?checkType=<?php echo $inCheckType; ?>" target="_blank">Advanced Server Information <i class="fa fa-mail-forward fa-lg"></i></a></p>
	*/
?>
	<iframe style="clear: both;" class="<?php echo $inName ?>_check_iframe <?php echo $inCheckType ?>_iframe" id="<?php echo $inName ?>_iframe" src="<?php echo get_bloginfo('url'); ?>/wp-admin/check-server.php?checkType=<?php echo $inCheckType; ?>"></iframe>
 <?php
}
?>