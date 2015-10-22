<?php
function optionsframework_option_name() {
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

function optionsframework_options() {
	$options = array();

	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		 'teeny'  => 'true',
		'tinymce' => array( 'plugins' => 'wordpress')
	);

/* General Settings  */

$options[] = array( "name" => __('Basic','curationnews'),
			"type" => "heading");

$options[] = array( "name" => __('Header Logo','curationnews'),
             'desc' => __('Logo height should be 40px. Width is flexible. Leave blank to use site title text.', 'curationnews'),
			"id" => "curationnews_logo",
			"std" => "",
			"type" => "upload");

/*$options[] = array( "name" => __('Large Logo','curationnews'),
			"desc" => __('Select a file to appear as the large logo for your site if you chose to have the logo below the navigation. The recommended maximum dimensions (if you plan on using a 728x90 ad) for this logo are 222x90.','curationnews'),
			"id" => "curationnews_logo_large",
			"std" => "",
			"type" => "upload");
*/
$options[] = array( "name" => __('Custom Favicon','curationnews'),
			"desc" => __('Upload a 16x16px PNG/GIF image that will represent your website\'s favicon.','curationnews'),
			"id" => "curationnews_favicon",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => __('Custom CSS','curationnews'),
			"desc" =>  __('Enter your custom CSS here. You will not lose any of the CSS you enter here if you update the theme to a new version.','curationnews'),
			"id" => "curationnews_customcss",
			"std" => "",
			"type" => "textarea");

/* Social Media Settings */
$options[] = array( "name" => __('Social Media Settings','curationnews'),
			"type" => "heading");
$options[] = array( "name" => __('Search','curationnews'),
			"desc" =>  __('Display search box.','curationnews'),
			"id" => "curationnews_search",
			 "std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('RSS','curationnews'),
			"desc" =>  __('Display RSS.','curationnews'),
			"id" => "curationnews_rss",
			 "std" => "true",
			"type" => "checkbox");

$options[] = array( "name" => __('Facebook','curationnews'),
			"desc" =>  __('Enter your full Facebook Page URL here.','curationnews'),
			"id" => "curationnews_facebook",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Twitter','curationnews'),
			"desc" =>  __('Enter your full Twitter URL here.','curationnews'),
			"id" => "curationnews_twitter",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Pinterest','curationnews'),
			"desc" =>  __('Enter your full Pinterest URL here.','curationnews'),
			"id" => "curationnews_pinterest",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Google Plus','curationnews'),
			"desc" =>  __('Enter your full Google Plus URL here.','curationnews'),
			"id" => "curationnews_google",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => __('Linkedin','curationnews'),
			"desc" =>  __('Enter your full Linkedin URL here.','curationnews'),
			"id" => "curationnews_linkedin",
			"std" => "",
			"type" => "text");

/* Ad Management Settings */
$options[] = array( "name" => __('Ad Management','curationnews'),
			"type" => "heading");

$options[] = array( "name" => __('Home Sidebar','curationnews'),
			"desc" =>  __('You can also place a Call to Action in a Home Page Sidebar visit your Widgets section.','curationnews'),
			"type" => "info");
 
$options[] = array( "name" => __('Top Banner Ad','curationnews'),
			"desc" =>  __('(Insert a standard sized: 728x90 - 750x100 Ad Code (Eg. Google Adsense). You could also insert a banner image as wide as 940px).','curationnews'),
			"id" => "curationnews_leader_ad",
         	"std" => "",
             'type' => 'editor',
		     'settings' => $wp_editor_settings );

/*$options[] = array( "name" => __('222x90 - 200x100 Ad Code','curationnews'),
			"desc" =>  __('Enter your ad code (Eg. Google Adsense).','curationnews'),
			"id" => "curationnews_small_ad",
        	"std" => "",
			'type' => 'editor',
	    	'settings' => $wp_editor_settings );*/

/* Fonts */
 $typography_mixed_fonts = array_merge( options_typography_get_os_fonts());

$options[] = array( "name" => __('Fonts','curationnews'),
			"type" => "heading");
$options[] = array( "name" => __('Fonts Selection','curationnews'),
			"desc" =>  __('You do not need to select font for each element. For example. Body, paragraph and heading define the general fonts used.','curationnews'),
	    	"type" => "info");
$options[] = array( 'name' => 'Body / Paragraph',
	'desc' => '',
	'id' => 'google_font_body',
    'std' => array( 'size' => '14px', 'face' => 'Cambria, Georgia, serif', 'color' => '#333333'),
	'type' => 'typography',
	'options' => array(
	'faces' => $typography_mixed_fonts,
		'styles' => false )
	);
$options[] = array( 'name' => 'Site Title',
	'desc' => '',
	'id' => 'google_font_brand',
    'std' => array( 'size' => '16px', 'face' => 'Lobster, cursive', 'color' => '#333333'),
	'type' => 'typography',
	'options' => array(
	'faces' => $typography_mixed_fonts,
		'styles' => false )
	);
$options[] = array( 'name' => 'Heading (H1 - H6)',
	'desc' => '',
	'id' => 'google_font_h',
    'std' => array('face' => 'Lobster, cursive', 'color' => '#333333'),
	'type' => 'typography',
	'options' => array(
	'faces' => $typography_mixed_fonts,
	'sizes' => false,
	'styles' => false )
	);
$options[] = array( 'name' => 'Post/Page Title',
	'desc' => '',
	'id' => 'google_font_ptitle',
    'std' => array( 'size' => '16px', 'face' => 'Lobster, cursive', 'color' => '#333333'),
	'type' => 'typography',
	'options' => array(
	'faces' => $typography_mixed_fonts,
		'styles' => false )
	);
$options[] = array( 'name' => 'Widget Title',
	'desc' => '',
	'id' => 'google_font_widget_title',
    'std' => array( 'size' => '16px', 'face' => 'Lobster, cursive', 'color' => '#333333'),
	'type' => 'typography',
	'options' => array(
	'faces' => $typography_mixed_fonts,
	'styles' => false	)
	);
/*$options[] = array( "name" => __('Additional Google Fonts','curationnews'),
			"desc" =>  __('Choose a font from the <a href="http://google.com/webfonts" target="_blank">Google WebFont Directory</a> and type its name in the text field.<br> Supported only for PR News Pro version','curationnews'),
	    	"type" => "info");*/
return $options;
}

add_filter( 'admin_footer', 'curationnews_removes_editor_visual_tab', 99 );
function curationnews_removes_editor_visual_tab()
{
    ?>
    <style type="text/css">
    a.switch-tmce, a.switch-tmce:hover {
        display:none;
    }
    </style>';
    <script type="text/javascript">
    jQuery(document).ready(function() {
        document.getElementsByClassName("switch-tmce").onclick = 'none';
    });
    </script>'
    <?php
}

?>