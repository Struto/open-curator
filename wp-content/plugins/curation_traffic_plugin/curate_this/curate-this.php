<?php
/**
 * Curate This Display and Handler.
 *
 * @package WordPress
 * @subpackage Press_This
 */
// v1.70 - Added featured image options and upload, clipped API checks, CurateThis sizing (in admin panel), clean headline, image cycle at the top
// v1.66 09/18/2013 - fixed the clean up of sentance problem as it was removing the characters br instead of the <br>, see function getSentancesCombined below
// v1.65 04/19/2013 - fixed ClippedData from bombing out on youtube videos, verifyed data came back.
// v1.64 02/28/2013 - added auto summary insertion, h2-h4 and bold text finding and buttons, also added the ability to not use blockquote
// v1.63 01/18/2012 - Added clipped api, fixed categories not working with WP3.5 upgrade
// v1.62 11/23/2012 - removed add all button, updated member link
// v1.61 10/23/2012 - added namespace so it doesn't conflict with plugins that use HTMLSIMPLEDOM.
// v1.60 07/23/2012 - post box additions, removed meta content on post, now using standard posts no more custom post types
// v1.59 04/30/2012 - added domain to image attribution, fixed youtube issue, added plugin and theme changes
// v1.58 04/04/2012 - Added image attribution
// v1.57 03/10/2012 - added  auto redirect
// v1.56 03/02/2012 - added META Description, ability to remove and add all twitter users, improved debug capability, plus few image inclusions
// v1.55 - finished commenting and released for final version
// v1.54 - added tons of commenting, fixed a few things, and fixed the new lines when using add to for paragraphs
// v1.53 - added pinterest share, some minor code fixes, also if image doesn't have http we add that
// v1.52 - added images, added paragraph check, and user update image url changes image
// v1.51 first version from version 1.5
namespace curationtraffic;
global $TwitterUsersFound,$dont_find_twitter,$curate_bm_image_align,$image_handling_option,$version_number,$auto_image_attribution,$check_image_sizes;

// setting some rules
$findIframe = false;
$verifyImageExtension = true;
$checkForBadImageURLS = true;
$totalParagraphsToCheck = 18;
$dont_find_twitter = false;
$auto_summary_insert = false;
$dont_use_blockquote = false;
$remove_allow_url_errors = true;
$check_image_sizes = 0;

// use the showActions var below for debugging finding Twitter Accounts
// 0 = off, 1 = basic generally it got far in finding a user, 2 = here we show steps of getting the twitter username, 3 = the full bang, you get it all baby 
$showActions = 0;
$ItFound = '';

define('IFRAME_REQUEST' , true);

/** WordPress Administration Bootstrap */
//require_once('./admin.php');
$dir = dirname(__FILE__);
$admin_dir = str_replace('wp-content\plugins\curation_traffic_plugin\curate_this','wp-admin', $dir);
$admin_dir = str_replace('wp-content/plugins/curation_traffic_plugin/curate_this','wp-admin', $admin_dir);
//echo $admin_dir;
require_once($admin_dir . '/admin.php');
//header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

// include the hTML DOM reader
//$theHTMLDomFile = get_template_directory() . '/custom/simple_html_dom.php';
//$theHTMLDomFile = MYPLUGINNAME_PATH . 'simple_html_dom.php';
$SUPPORT_FILE_DIR = '';
$SUPPORT_FILE_URL = '';

if(BOOKMARKLET_TYPE == 'plugin')
{
	$close_curate_window = get_option('close_curate_window');
	$auto_ = get_option('auto_');
	$auto_summary_insert = get_option('auto_summary_insert');
	$dont_use_blockquote = get_option('dont_use_blockquote');
	$dont_find_twitter = get_option('dont_find_twitter');
	$max_twitter_users = get_option('max_twitter_users');
	$curate_bm_image_align = get_option('curate_bm_image_align');
	$image_handling_option = get_option('image_handling_option');
	$bit_ly_username = get_option('bit_ly_username');
	$bit_ly_api_key = get_option('bit_ly_api_key');
	$check_image_sizes = get_option('check_image_sizes');
	if($check_image_sizes == '')
		$check_image_sizes = 0;
	$curation_link_text = get_option('curation_link_text');
	$SUPPORT_FILE_DIR = MYPLUGINNAME_PATH . '/curate_this/';
	$theHTMLDomFile = $SUPPORT_FILE_DIR . 'simple_html_dom.php';
	$SUPPORT_FILE_URL = plugins_url() . '/curation_traffic_plugin/curate_this/';
	$remove_allow_url_errors = get_option('remove_allow_url_errors');
	$turn_off_clipped_api = get_option('turn_off_clipped_api');
	$auto_image_attribution = get_option('auto_image_attribution');


	
	if (function_exists('ct_plugin_validate_license'))
	{
		
		if (ct_plugin_validate_license()!==true) 
		{ 
			die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
		}
	}
	else
	{
		die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
	}
}
else
{
	$data = get_option(OPTIONS); 
	$close_curate_window = $data['close_curate_window'];
	$auto_ = $data['auto_'];
	$auto_summary_insert = $data['auto_summary_insert'];
	$dont_use_blockquote = $data['dont_use_blockquote'];
	$dont_find_twitter = $data['dont_find_twitter'];
	$max_twitter_users = $data['max_twitter_users'];
	$curate_bm_image_align = $data['curate_bm_image_align'];
	$bit_ly_username = $data['bit_ly_username'];
	$bit_ly_api_key = $data['bit_ly_api_key'];
	$check_image_sizes = $data['check_image_sizes'];
	$SUPPORT_FILE_DIR = get_template_directory() . '/curate_this/';
	$theHTMLDomFile = $SUPPORT_FILE_DIR . 'simple_html_dom.php';
	$SUPPORT_FILE_URL = get_bloginfo('template_directory') . '/curate_this/';
	$curation_link_text = $data['curation_link_text'];
	$remove_allow_url_errors = $data['remove_allow_url_errors'];
	$turn_off_clipped_api = $data['turn_off_clipped_api'];	
	
	if (function_exists('curation_traffic_validate_license'))
	{
		if (curation_traffic_validate_license()!==true) 
		{ 
			die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
		}
	}
	else
	{
		die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
	}
	$dbVersion = get_option('curate-this-version');
	//echo $SUPPORT_FILE_DIR;
	//echo '<br>' . $SUPPORT_FILE_URL;
}


$version_number = $dbVersion;

require_once($theHTMLDomFile);
//include($theHTMLDomFile);
//get the data options from the admin panel


// getting if this is a repull from the URL
$isRepull = $_GET["repull"];
if($isRepull)
{
	$totalParagraphsToCheck = isset($_GET['totalParagraphsToCheck']) ? $_GET['totalParagraphsToCheck'] : 18;
	//$verifyImageExtension = isset($_GET['verifyImageExtension']) ? $_GET['verifyImageExtension'] : true;
	//$checkForBadImageURLS = isset($_GET['checkForBadImageURLS']) ? $_GET['checkForBadImageURLS'] : true;
	$verifyImageExtension = false;
	$checkForBadImageURLS = false;
}



if ( ! current_user_can('edit_posts') )
	wp_die( __( 'Cheatin&#8217; uh?' ) );

/**
 * Press It form handler.
 *
 * @package WordPress
 * @subpackage Press_This
 * @since 2.6.0
 *
 * @return int Post ID
 */

function press_it($image_handling_option) {
global $curation_link_text;
//	if(BOOKMARKLET_TYPE == 'theme')
//		$post = get_default_post_to_edit($post_type = 'curation');
//	else	
	$post = get_default_post_to_edit();
	$post = get_object_vars($post);
	$post_ID = $post['ID'] = (int) $_POST['post_id'];

//	$twitterUserName = $_POST['twitter_username'];
//	$FoundTwitterUserName = $_POST['twitter_username'];
//	my_meta_init();

	if ( !current_user_can('edit_post', $post_ID) )
		wp_die(__('You are not allowed to edit this post.'));

	$post['post_category'] = isset($_POST['post_category']) ? $_POST['post_category'] : '';
	$post['tax_input'] = isset($_POST['tax_input']) ? $_POST['tax_input'] : '';
	$post['post_title'] = isset($_POST['title']) ? $_POST['title'] : '';
	
		$aa = $_POST['aa'];
		$mm = $_POST['mm'];
		$jj = $_POST['jj'];
		$hh = $_POST['hh'];
		$mn = $_POST['mn'];
		$ss = $_POST['ss'];
		$aa = ($aa <= 0 ) ? date('Y') : $aa;
		$mm = ($mm <= 0 ) ? date('n') : $mm;
		$jj = ($jj > 31 ) ? 31 : $jj;
		$jj = ($jj <= 0 ) ? date('j') : $jj;
		$hh = ($hh > 23 ) ? $hh -24 : $hh;
		$mn = ($mn > 59 ) ? $mn -60 : $mn;
		$ss = ($ss > 59 ) ? $ss -60 : $ss;
		$post['post_date'] = sprintf( "%04d-%02d-%02d %02d:%02d:%02d", $aa, $mm, $jj, $hh, $mn, $ss );
		$post['post_date_gmt'] = get_gmt_from_date($post['post_date']);
		// we will use these down below for scheduling if necessary
		$post_date = $post['post_date'];
		$post_date_gmt = $post['post_date_gmt'];
		$content = isset($_POST['content']) ? $_POST['content'] : '';
//var_dump($_POST);
		// getting the form data
		$savedMeta = $_POST['_my_meta'];
		$theLink = $savedMeta['curated_link'];
		$theCuratedDomain = getDomainNameCurationTraffic($theLink);

		// if the link text is not set we then set it to the default
		if($curation_link_text == '')
			$curation_link_text = "See full story on";

		// adding the curation attribution link.
		$theAttributionLink = '<p class="curated_link">'.$curation_link_text.' <a href="'.$theLink.'" target="_blank">'.$theCuratedDomain.'</a></p>';

		// adding the image credit links
		$imageCreditText = $savedMeta['image_credit_text'];
		$imageCreditLink = $savedMeta['image_credit_link'];
		$imageCreditBlock = '';
		
		// here we check to see if there is a link, if so then we wrap the text around the links.
		if($imageCreditText <> '')
		{
			$imageCreditBlock .= '<p class="image_credit">';
			if($imageCreditLink <> '')
				$imageCreditBlock .= '<a href="'.$imageCreditLink.'" target="_blank">';
				
			$imageCreditBlock .= $imageCreditText;
	
			if($imageCreditLink <> '')
				$imageCreditBlock .= '</a>';
			$imageCreditBlock .= '</p>';
		}
		// now we add all the above to the main content before it gets submitted
		$content .= $theAttributionLink . $imageCreditBlock;

		// right now we are saving all this data to metaboxes, in the future we might want to just save the link.
		my_meta_save($post_ID); //scott

	$upload = false;
	if ( !empty($_POST['photo_src']) && current_user_can('upload_files') ) {
		foreach( (array) $_POST['photo_src'] as $key => $image) {
			// see if files exist in content - we don't want to upload non-used selected files.
			if ( strpos($_POST['content'], htmlspecialchars($image)) !== false ) {
				$desc = isset($_POST['photo_description'][$key]) ? $_POST['photo_description'][$key] : '';
				$upload = media_sideload_image($image, $post_ID, $desc);

				// Replace the POSTED content <img> with correct uploaded ones. Regex contains fix for Magic Quotes
				if ( !is_wp_error($upload) )
					$content = preg_replace('/<img ([^>]*)src=\\\?(\"|\')'.preg_quote(htmlspecialchars($image), '/').'\\\?(\2)([^>\/]*)\/*>/is', $upload, $content);
			}

		}
	}

	
	// ImageHandling - keep_image_in_postbox, upload_to_feature_delete_postbox, upload_to_feature_keep_postbox, remove the iamge from post

	$image_url = $savedMeta['curated_image_url'];
	if($image_url <> '')
	{

		if($image_handling_option == 'upload_to_feature_delete_postbox')
			$content = preg_replace('/<img[^>]+./','', $content);
	
		// set the post_content and status
		$post['post_content'] = $content;

		// upload the image selected and set it to a featured image
		if($image_handling_option == 'upload_to_feature_delete_postbox' || $image_handling_option == 'upload_to_feature_keep_postbox')	
		{
			//echo '$image_url : ' . $image_url;
			$image_url = strtok($image_url,'?');
			//echo '<br>$strtok : ' . $image_url;
			$upload_dir = wp_upload_dir();
			//var_dump($upload_dir);
			
			$image_data = file_get_contents($image_url);
			$filename = basename($image_url);
			if(wp_mkdir_p($upload_dir['path']))
				$file = $upload_dir['path'] . '/' . $filename;
			else
				$file = $upload_dir['basedir'] . '/' . $filename;
			file_put_contents($file, $image_data);
			
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			/* for debug
			console($wp_filetype);
			console($image_data);
			console($filename);
			console($attachment);
			console($file); */
			$attach_id = wp_insert_attachment( $attachment, $file, $post_ID );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			
			set_post_thumbnail( $post_ID, $attach_id );
		} // check to upload featured image block
	} // if($image_url <> '')

	// UpdatePostSection
	if ( isset( $_POST['publish'] ) && current_user_can( 'publish_posts' ) )
		$post['post_status'] = 'publish';
	elseif ( isset( $_POST['review'] ) )
		$post['post_status'] = 'pending';
	else
		$post['post_status'] = 'draft';

	// error handling for media_sideload
	if ( is_wp_error($upload) ) {
		wp_delete_post($post_ID);
		wp_die($upload);
	} else {
		// Post formats
		set_post_format( $post_ID, false );
		$post_ID = wp_update_post($post);

		// next we handle the schedule...
		// when they change the date the button value automatically changes to schedule
		// we check for that
		// For some reason we couldn't update the post status to future above... it's probably something to do with the post not submitted and drafts exists, it's a wordpress protection thing
		// not allowing a post to be updated with previous versions or something like that., this works for now.
		if($_POST['publish'] == 'Schedule')
		{
			// create a new array for the post
			$UpdatePost = array();
			// get the post in a name array
			$UpdatePost = get_post($post_ID, ARRAY_A);
			// set the post status to future... we could probably check but let's trust them for now... ha ha
			$UpdatePost['post_status'] = 'future';
			// these two variables are above and gotten from the form
			$UpdatePost['post_date'] = $post_date;
			$UpdatePost['post_date_gmt'] = $post_date_gmt;
			// finally we update the post
			wp_update_post($UpdatePost);
		}

	}

	return $post_ID;
} // end press_it()

// For submitted posts.
if ( isset($_REQUEST['action']) && 'post' == $_REQUEST['action'] ) {
	check_admin_referer('press-this');
	//added by scott
	$TwitterUsersFound = $_POST['twitter_username'];
	$posted = $post_ID = press_it($image_handling_option);
} else {
	$post = get_default_post_to_edit('post', true);
	$post_ID = $post->ID;
}

// Set Variables
$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';

$selection = '';
if ( !empty($_GET['s']) ) {
	$selection = str_replace('&apos;', "'", stripslashes($_GET['s']));
	$selection = trim( htmlspecialchars( html_entity_decode($selection, ENT_QUOTES) ) );
}

if ( ! empty($selection) ) {
	$selection = preg_replace('/(\r?\n|\r)/', '</p><p>', $selection);
	//remove by scott and modified right below it
//	$selection = '<p>' . str_replace('<p></p>', '', $selection) . '</p>';
	$selection = str_replace('<p></p>', '\n\n', $selection);
}

$url = isset($_GET['u']) ? esc_url($_GET['u']) : '';
//$url = isset($_GET['u']) ? $_GET['u'] : '';
$image = isset($_GET['i']) ? $_GET['i'] : '';


	wp_enqueue_style( 'colors' );
	wp_enqueue_script( 'post' );
	_wp_admin_html_begin();
?>

<title><?php _e('Curate This') ?></title>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {'url':'<?php echo SITECOOKIEPATH; ?>','uid':'<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>','time':'<?php echo time() ?>'};
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>', pagenow = 'press-this', isRtl = <?php echo (int) is_rtl(); ?>;
var photostorage = false;
//]]>
</script>

<?php
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');
	my_meta_init(); //scott
//	my_meta_setup();

// here we are checking to see if these PHP settings are turned on. It's required for the HTML dom to work properly
if(!$remove_allow_url_errors)
{
	//||  ini_get('allow_url_include') != 1
	//.' | allow_url_include=' .(ini_get('allow_url_include') ? 'On' : 'Off')
	if(ini_get('allow_url_fopen') != 1)
	{
			echo '<div id="message" class="error"><h3>Curation Traffic Plugin Might Need Attention</h3>
			<p>For the CurateThis Bookmarklet to work the PHP settings "allow_url_fopen" needs to be set to "On" (possibly the "allow_url_include" setting needs to be set to "On" as well).</p>
			<p><strong>It appears that one the allow_url_fopen is currently turned off</strong>.</p>
			
			<p>If after using CurateThis for a couple of curations and everything works then this might be a false error. You can then turn this warning off by visiting <a href="'.get_admin_url().'admin.php?page=curation_traffic_plugin">
			Curation Traffic Plugin Options</a></p>
			
			<p>If there appears to be problems below, such as images not loading, no text being found, or other errors that cause CurateThis not to work then the most likely cause are this setting (or both) are set to off.</p>
			<p>Fixing this is easy, <a href="'.get_admin_url().'admin.php?page=youbrandinc-support-news&tutorials=yes" target="_blank">visit here to learn how to turn on allow_url_open and allow_url_include</a>.</strong>
			
			<br />allow_url_fopen='. (ini_get('allow_url_fopen') ? 'On' : 'Off') . '</div>';
	
			if(BOOKMARKLET_TYPE == 'plugin')
				echo '<strong>Curation Traffic Plugin Options > Remove allow_url_* Errors from Admin';	
	
			echo  '</strong></p></div>';
	}
}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $SUPPORT_FILE_URL; ?>/css/curate.css" />

<!--<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.hoverIntent.js" type="text/javascript"></script>  optional -->
<script src="<?php echo $SUPPORT_FILE_URL; ?>js/jquery.cluetip.min.js" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function() {
/*  jQuery('.twitter_name').cluetip({
    splitTitle: '|', // use the invoking element's title attribute to populate the clueTip...
                     // ...and split the contents into separate divs where there is a "|"
    showTitle: true // hide the clueTip's heading

  });*/
  	jQuery('.twitter_name').cluetip({sticky: true, closePosition: 'title', arrows: true, mouseOutClose: true});
});
</script>
<link rel="stylesheet" href="<?php echo $SUPPORT_FILE_URL; ?>css/jquery.cluetip.css" type="text/css" />
<style type="text/css">
.press-this .categorydiv div.tabs-panel { height: 200px !important;}
#content_ifr html .mceContentBody {
	max-width:100% !important;
}
.press-this-sidebar {width: 21%;float: right;margin-right: 2%;}
.press-this .posting { margin: 0 1%; width: 75%; float: left;}

</style>

</head>
<body class="press-this wp-admin">

<?php 
// BOTH OF THESE functions are used further below
// the below function parses a string and gives you back all the sentances
function getSentances($inTextBlock)
{
  $re = '/# Split sentences on whitespace between them.
  (?<=                # Begin positive lookbehind.
	[.!?]             # Either an end of sentence punct,
  | [.!?][\'"]        # or end of sentence punct and quote.
  )                   # End positive lookbehind.
  (?<!                # Begin negative lookbehind.
	Mr\.              # Skip either "Mr."
  | Mrs\.             # or "Mrs.",
  | Ms\.              # or "Ms.",
  | Jr\.              # or "Jr.",
  | Dr\.              # or "Dr.",
  | Prof\.            # or "Prof.",
  | Sr\.              # or "Sr.",
  | \s[A-Z]\.              # or initials ex: "George W. Bush",
					  # or... (you get the idea).
  )                   # End negative lookbehind.
  \s+                 # Split on whitespace between sentences.
  /ix';
  return preg_split($re, $inTextBlock, -1, PREG_SPLIT_NO_EMPTY);
}

// here we combine the sentances, we did this to be extra careful and parsing out all bad characters...
function getSentancesCombined($inTextBlock)
{
	$theRetString = '';
	// add '/(\'|&#0 *39;)/' to check for apostophre
	// removed ,'<br>' because it was taking out br in words....
	$inTextBlock = preg_replace(array('/\r/', '/\n/', '/\t/'), '', $inTextBlock);
	$inTextBlock = str_replace("\t",'',$inTextBlock);
	$getSentanceArr = getSentances($inTextBlock);
	$I = 0;
	foreach ($getSentanceArr as $val) 
	{
		//echo "<br>" . $val;
		if($i == 0)
			$theRetString .= $val;	
		else
			$theRetString .= ' ' . $val;
		$i++;
	}
	return $theRetString;
}

if ( !isset($posted) && !intval($posted) ) {

	// I moved this up here becuase we want to search for a Twitter Username and have to put it in a hidden field value.
	// http://networkedblogs.com/sYzqQ

	$html = new simple_html_dom();
	// Load a file
	// take @ off when testing
	@$html->load_file($url);
	$currentDomain = getDomainNameCurationTraffic($url);

	// Look for Iframe	
	$isIframe = false;
	if($findIframe)
	{
		$element = $html->find("iframe");
		//echo $element[0]->src;
	}
			
	$checkURL = '';
	if($element[0] != '' && $findIframe)
	{	
		$checkURL = $element[0]->src;
		
		$badURLStrings = array('platform.twitter.com', 'api.tweetmeme.com', 'www.blogger.com/navbar', 'adframe?', 'facebook.com/plugins/', 'nytimes.com/regilite', 'ox-d.businessinsider.com', 'ads/square.', 'youtube.com/embed/', 'web.adblade.com','ad.doubleclick.net', 'docstoc_frame', 'facebook.com/connect/connect.php', 'recaptcha/api/');
		$foundBadURL = false;
		foreach ($badURLStrings as $value) 
		{
			if(strpos($checkURL,$value) !== false)
			{
				$foundBadURL = true;
				break;
			}
		}		
		if(!$foundBadURL)
		{
			//echo $element[0]->src;
			if($checkURL != '')
			{
				$url = $checkURL;
				$html->load_file($url);
				$isIframe = true;
				echo '<div id="iframe_error"><div class="warning">This page might be part of an Iframe object... <strong>Close the Iframe</strong> or find a link that takes you to the original story. This generally happens if you\'re visiting a link that has a top bar included. <a href="'. $url . '" target="_blank">Here\'s the link we found</a>.</div></div>';
			}
		}
	}
// meta description
$meta_desc = '';
foreach($html->find('meta[name=description]') as $element) {
	$meta_desc = $element->content;
}
if(strlen($meta_desc) <= 0)
{
	foreach($html->find('meta[property=og:description]') as $element) {
		$meta_desc = $element->content;
	}
}

/*
		$metaArray = array('meta[name=description]', 'meta[property=og:description]');
		foreach ($metaArray as $value) 
		{
			echo $value;
			$element = $html->find($value);
			// check to see if we found this elelentn and it's not emptry
			if(!empty($element))
			{
				$meta_desc = $element->content;
				if(strlen($meta_desc) > 0)
					break;
			}
		}

*/
	// Start of Find Twitter
	$totalUsersFound=0;
	 if(!$dont_find_twitter) 
	 {
		//echo "it got here";
		if($max_twitter_users == 0)
			$max_twitter_users = 5;
		$TwitterUsersFound = array();	
		$twitterUserName = '';
		$FoundTwitterUserName = '';

		foreach($html->find('a') as $element) 
		{

		   $lookForTwitter = $element->href;
			if($showActions == 3)
				echo '<br>FOUND LINK : ' . $lookForTwitter;
		   $FoundTwitterUserName = '';
		   // look for a link with Twitter.com
			if (strpos($lookForTwitter,'twitter.com') !== false) {

			if($showActions >= 2)
				echo '<br>FOUND <strong>Twitter</strong> : ' . $lookForTwitter;
				
			// first thing is strip off http, https, www., and twitter.com, or any bad ways twitter.com/SHOWS UP
			$lookForTwitter = preg_replace('/(http:\/\/|https:\/\/|www.|twitter.com\/share|statuses\/\w*|twitter.com)/i', '', $lookForTwitter);

			//we stripped twitter.com and all bad twitter.com/URLS, now let's look for more bad stuff
			// these are def bad URLS
			if($showActions >= 2)
				echo '<br>Before BAD WORDS : ' . $lookForTwitter . '<br>';
			$badURLStrings = array('home?status', '/intent/', '?status=', 'search.', 'share?', 'text=', '.rss');
			$foundBadURL = false;
			foreach ($badURLStrings as $value) 
			{
				if(strpos($lookForTwitter,$value) !== false)
				{
					$foundBadURL = true;
					break;
				}
			}
			//if we found a bad url then we skip this element so continue
			if($foundBadURL)
				continue;

			// there are possible finds for users 
			// /status/, /statuses/

				if($showActions > 0)
					echo '<br><br>lookForTwitter: ' . $lookForTwitter;
					
			// checking to see if the Twitter username is valid
			if(preg_match('/\/[a-z|0-9|_]*/i', $lookForTwitter, $matches))
			{
				$lookForTwitter = $matches[0];
				//yeah we might have found  user now let's remove the / that should still be there
				$lookForTwitter = str_replace('/', '', $lookForTwitter);
				if($showActions > 0)
					echo '<br>matches: ' . $matches[0];
				if (strpos($lookForTwitter,'/') === false)
					$FoundTwitterUserName = $lookForTwitter;
				
			}
			// we found a user let's do one more check
			if(strlen($FoundTwitterUserName) > 0)
			{
				// is this user not already in the array? if not then add them
				if(!in_array($FoundTwitterUserName, $TwitterUsersFound))
				{
					$TwitterUsersFound[] = $FoundTwitterUserName;
					$totalUsersFound++;
					if($showActions > 0)
						echo '<br>Found User: ' . $FoundTwitterUserName;
						
					// if the total users we found is the max users we should find then let's stop
					if($totalUsersFound == $max_twitter_users)
						break;
				}
			}
			} //if (strpos($lookForTwitter,'twitter.com') !== false)
		}//foreach($html->find('a') as $element) 
	 }//if(!$dont_find_twitter)
	 
	//$b = strip_tags($first_paragraph, "<p><a>");
	$contentFound = false;
	$s_feedback = "";
	$isSpecialDomain = false;
	if(!$isRepull)
	{
		// okay so we haven't issued a repull, this is the first run.
		// we first look to see if there is an article block with a role element, if so this is higher on the order
		// if we don't find it then we move on to 'article' ELEMENT, ID, CLASS
		// Next we search for 'Content'
		// Others I've found but haven't added:
		// .content, .post-body, #storyContent, #content-wrapper, entry-content, .storyBody
		// #zoom = pinterest.com
		$specialDomains = array('pinterest.com' => '#zoom', 'imgur.com' => 'body', 'nytimes.com' => 'articleBody', 'comicbookresources.com' => '#wrapper');
		
//		if($specialCase = $specialDomains[$currentDomain])
		if (array_key_exists($currentDomain, $specialDomains))
		{
			$contentFindList = array($specialDomains[$currentDomain]);
			$isSpecialDomain = true;
			$ItFound = 'showing: special domains';
		}
		else
		{
			// This is the list of content block elements we cycle thru down below, this order is important as the most common and most relevant ones should be first
			$contentFindList = array('#article_story', 'article[role]', 'article', '#article', '.article','.entry-content', '#content', '#primary-column', '.post', '#post', 'section', 'section[post]', '.entry', '.article_info', '#zoom');
		}
		$s_feedback = "debug: on<br>";
		foreach ($contentFindList as $value) 
		{
			$s_feedback = $s_feedback . 'searching... ' . $value . '<br>';
			//echo $s_feedback . 'searching... ' . $value . '<br>';
			$content = $html->find($value);
				
			// check to see if we found this elelentn and it's not emptry
			if(!empty($content))
			{
				$ItFound .= 'showing: ' . $value;
				// we found content and let's set that so we can do something to it below
				$contentFound = true;
				break;
			}
		}

	}
	else
	{
		//here we send an email
		$ItFound .= 'showing: nothing ';
	}
	if($contentFound)
	{
		// because the content is a valid HTML file (we just grabbed that element) we wrap it in HTML and Body tags
		$thecontent = '<html><body>' . $content[0] . '</body></html>';
		// now let's reload the content to easily read it
		$html->load($thecontent);
	}

	//find the paragraphs
	$element = $html->find("p");
	$elementH2 = $html->find("h2");
	$elementH3 = $html->find("h3");
	$elementH4 = $html->find("h4");
	$elementH5 = $html->find("h5");
	$elementStrong = $html->find("strong");
	$elementB = $html->find("b");

//	$element = $html->find("span");

	// Here's what we are doing below.
	// the IF checks if we found any P elements, if we didn't then we use the HTML->find(text) to find all the text elements, this isn't the best and sometimes gives us bad results but it's the best we can do given
	// that most likely this page isn't built with text within P elements... why, just why?
	$sentances_or_paragraphs = array();
	$h2_arr = array();
	$h3_arr = array();
	$h2_combined_s = '';
	$h3_combined_s = '';
	$h4_combined_s = '';
	$h5_combined_s = '';
	$strong_combined_s = '';
	$b_combined_s = '';
	if(count($element) <= 0)
	{
		$ItFound .= ' - element: text';
		$element = $html->find("text");
		$returnArr = array();
		$allText = '';
		if (count($element) > 0) 
		{
			foreach ($element as $val) 
			{
				//echo "<br>" . $val;
				$allText .= $val;
			}
		}
		//echo $allText;
		// okay, well we grabbed all the text, and combined it into one large string...
		// here's the problem, these don't easily break down into paragraphs, so what we do is get each individual sentance and add that to the array
		// so instead of paragraphs that the user selects as curated content they are going to just get the first X sentances we find
		$sentances_or_paragraphs = getSentances($allText);
	}
	else
	{
		// yeah we found some P's so let's lok thru them and add them to our array we will use down below
		// we took out the \n\n for just single lines for lists
		
		$elementCount = count($element);
		$ItFound .= ' - element: '. $elementCount;
		$i = 0;
		while ($i <= $elementCount):
			$sentances_or_paragraphs[] = $element[$i]->plaintext;
			$i++;
		endwhile;
		

		$elementCount = count($elementH2);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementH2[$i]->plaintext) > 0)
			{
				if($i > 0)
					$h2_combined_s .='\n';
				$h2_combined_s .= $elementH2[$i]->plaintext;
			}
			$i++;
		endwhile;


		$elementCount = count($elementH3);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementH3[$i]->plaintext) > 0)
			{
				if($i > 0)
					$h3_combined_s .='\n';
				$h3_combined_s .= $elementH3[$i]->plaintext;
			}
			$i++;
		endwhile;

		$elementCount = count($elementH4);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementH4[$i]->plaintext) > 0)
			{
				if($i > 0)
					$h4_combined_s .='\n';
				$h4_combined_s .= $elementH4[$i]->plaintext;
			}
			$i++;
		endwhile;
		
		$elementCount = count($elementH5);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementH5[$i]->plaintext) > 0)
			{
				if($i > 0)
					$h5_combined_s .='\n';
				$h5_combined_s .= $elementH5[$i]->plaintext;
			}
			$i++;
		endwhile;

		$elementCount = count($elementStrong);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementStrong[$i]->plaintext) > 0)
			{
				if($i > 0)
					$strong_combined_s .='\n';
				$strong_combined_s .= $elementStrong[$i]->plaintext;
			}
			$i++;
		endwhile;


		$elementCount = count($elementB);
		$i = 0;
		while ($i <= $elementCount):
			if(strlen( $elementB[$i]->plaintext) > 0)
			{
				if($i > 0)
					$b_combined_s .='\n';
				$b_combined_s .= $elementB[$i]->plaintext;
			}
			$i++;
		endwhile;

	}
}
?>
<form action="curate-this.php?action=post" method="post" id="post_form">
<div id="poststuff" class="metabox-holder">

	<div class="posting">

		<div id="wphead" class="curate_this_head" style="padding: 4px 0 4px 0; height: 40px;">
			<a href="<?php echo get_option('home'); ?>/" target="_blank">
				<img src="<?php echo $SUPPORT_FILE_URL; ?>/i/curation-traffic-banner.png" style="float: left;" />
			</a>
            
		<?php if ( !isset($posted) && !intval($posted) ) { ?>
           	<div class="image_selectors_top">
                <div class="top_left_image" style="float: left; padding-left: 20px;">
                    <a href="#" style="margin: 0 auto; padding: 0;" class="prev-link">
                        <i class="fa fa-caret-left fa-3x"></i>
                    </a>
                </div>
                <div style="float: left; width:70px; text-align:center;"><label class="current_image">1</label> of <label id="number_images_label"><?php echo $finalTotalImages; ?></label>
                <br><span style="font-size: 11px;">images</span>
                </div>            
                <div class="top_right_image" style="float: left; width: 40px;">
                    <a href="#" style="margin: 0 auto; padding: 0;" class="next-link">
                        <i class="fa fa-caret-right fa-3x"></i>
                    </a>
                </div>         
                <div class="no_image_checkbox_top" style="padding: 10px 0 0; float:left; width:112px;">
                    <label><input type="checkbox" class="no_img_checkbox" onClick=""> No Image</label>
                </div>
                <div class="headline_editing" style="padding: 12px 0 0; width: 222px; float:left;">
	                <a href="javascript:;" id="cleanHeadline">Clean Headline</a> | <a href="javascript:;" id="originalHeadline">Original Headline</a>
                </div> 
                <div style="float: right;">
                	<p>Something go wrong? <a href="https://docs.google.com/spreadsheet/viewform?formkey=dHhBSXVFbUc3aXdtUWFBaHZpUXNGbkE6MQ&entry_0=<?php echo urlencode($url); ?>&entry_3=<?php urlencode(bloginfo('admin_email')); ?>" target="_blank">Report this Link</a></p>
                </div>
	        </div>            
            <?php } ?>
		</div>
		<?php
		$justPosted = False;
		if ( isset($posted) && intval($posted) ) {
			$post_ID = intval($posted);
			 ?>
                <script type="text/javascript" src="<?php echo $SUPPORT_FILE_URL; ?>/js/jquery.bitly.js"></script>  
             <?php 
		 		$justPosted = True;
				// WindowCloseCheck - if in the admin panel the user chooses to auto-close the curate this window then let's do that
				if($close_curate_window) {
			 ?>
             <script type="text/javascript">
				 window.close();
			</script>
             <?php } ?>
             
        <script type="text/javascript">
        (function() {
            window.PinIt = window.PinIt || { loaded:false };
            if (window.PinIt.loaded) return;
            window.PinIt.loaded = true;
            function async_load(){
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.async = true;
                if (window.location.protocol == "https:")
                    s.src = "https://assets.pinterest.com/js/pinit.js";
                else
                    s.src = "http://assets.pinterest.com/js/pinit.js";
                var x = document.getElementsByTagName("script")[0];
                x.parentNode.insertBefore(s, x);
            }
            if (window.attachEvent)
                window.attachEvent("onload", async_load);
            else
                window.addEventListener("load", async_load, false);
        })();
        </script>
			<style type="text/css">
            .posting {margin-right: 50px;}
            .press-this-sidebar {display:none;}
            </style>
			<div id="message" class="updated">
            <?php 
				$the_permalink =get_permalink($post_ID);
				$the_title = html_entity_decode(get_the_title($post_ID),ENT_QUOTES, 'UTF-8');
				$my_meta = get_post_meta($post_ID,'_my_meta',TRUE);
			?>
            
			<img src="<?php echo $SUPPORT_FILE_URL; ?>/i/success.png" class="alignleft" />
            <p style="padding-left: 40px;"><strong><?php _e('Your post has been saved.'); ?></strong>
            
			<a href="<?php echo $the_permalink; ?>" target="_blank"><?php _e('View post'); ?></a>
			| <a href="<?php echo get_edit_post_link( $post_ID ); ?>" onClick="window.opener.location.replace(this.href); window.close();"><?php _e('Edit Post'); ?></a>
			| <a href="#" onClick="window.close();"><?php _e('Close Window'); ?></a></p>
            
			</div>
			<?php 
				// onClick="window.opener.location.replace(this.href);" took this out of view post above
				//twitter username up at top
				// all this is used below
				// this is the text that goes in the text area
				$wrapUpText =  $the_title. ' - ' . $the_permalink;
				$userCombine = '';
				$i=0;
				// if there are Twitter users submitted in the hidden elements lets add them to our sharing content
				if(isset($TwitterUsersFound))
				{
					$new_array_without_nulls = array_filter($TwitterUsersFound, 'strlen');
					$totalUsersFound = count($new_array_without_nulls);
				}
				if(isset($new_array_without_nulls))
				{	
						
					foreach($new_array_without_nulls as $value)
					{
						if($value != '')
						{
							if($i>0)
								$userCombine .= ', @'.$value;
							else
								$userCombine .= ' via @'.$value;
						}
						$i++;
					}
				}
				// add the twitter users to our share text
				$wrapUpText .=  $userCombine;
				$finalTitleforShare = $the_title . $userCombine;
				$finalTitleforShare = urlencode($finalTitleforShare);
			?>
             <script type="text/javascript">
			 var jTitle = "<?php echo $the_title; ?>";
			 var jUserCombine = "<?php echo $userCombine; ?>";
			
			function createShortURL()
			{
				<?php 
					if($bit_ly_username == '' && $bit_ly_api_key == '')
					{
						$bit_ly_username = 'curationtraffic';
						$bit_ly_api_key = 'R_33e9442aad2c5498a4ae6cec0ffa3adf';
					}
				?>
				 var b = new jQuery.Bitly({login: '<?php echo $bit_ly_username; ?>', key: '<?php echo $bit_ly_api_key; ?>'});
				  b.shorten('<?php echo $the_permalink; ?>', function(short_url){ changeAfterShortURL(short_url)} );
			}
			function changeAfterShortURL(shorturl)
			{
				oFormObject = document.forms['post_form'];
				oFormObject.elements["the_permalink"].value = shorturl;
				oFormObject.elements["textarea-afterpost"].value = jTitle + " - " + shorturl + jUserCombine;	
			}
		 	function SelectAll(id)
			{
				document.getElementById(id).focus();
				document.getElementById(id).select();
			}
				document.getElementById('side-sortables').style.visibility = 'hidden';
				//document.getElementById('title-div').style.visibility = 'hidden';
				document.getElementById('wp-content-wrap').style.visibility = 'hidden';
				document.getElementById('title').style.visibility = 'hidden';
				jQuery("#side-sortables").css("display", "none");
				jQuery(".postdivrich").css("display", "none");
			</script>
            <div style="padding: 10px 0 0 10px;">
                <div style="float: left; width: 440px;">
	                <div style="width: 160px; float: left;">
                    <a href="http://hootsuite.com/hootlet/load?title=<?php echo $finalTitleforShare ?>&address=<?php echo $the_permalink; ?>"><img src="<?php echo $SUPPORT_FILE_URL; ?>/i/hootsuite-share.png" /></a>
					<?php 
                    if($auto_) { ?>
                    <script type="text/javascript">
                    <!--
                    window.location = "http://hootsuite.com/hootlet/load?title=<?php echo $finalTitleforShare ?>&address=<?php echo $the_permalink; ?>";
                    //-->
                    </script>
                    <?php } ?>

                    
                    </div>
                    <div style="width: 170px; float: left;">
                    <a href="http://bufferapp.com/bookmarklet/?url=<?php echo $the_permalink ?>&text=<?php echo $finalTitleforShare; ?>&source=CurationTraffic" target="_blank"><img src="<?php echo $SUPPORT_FILE_URL; ?>/i/bufferapp-logo.png" /></a>
                    </div>
					<div style="padding: 7px 0 6px 5px; display: inline-block; overflow:auto; width: 90px; float: left;">
					<a href="http://pinterest.com/pin/create/button/?url=<?php echo $the_permalink; ?>&media=<?php if($my_meta['curated_image_url']) echo $my_meta['curated_image_url']; ?>&description=<?php echo $finalTitleforShare; ?>" class="pin-it-button" count-layout="horizontal">Pin It</a>
					</div>
                </div>
                <div style="float: left; width: 150px; padding-top: 10px;">
                    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f03efe645473a99"></script>
                    <a id="at-post-<?php echo $post_ID; ?>"></a>
                    <script type="text/javascript">addthis.button('#at-post-<?php echo $post_ID; ?>', {}, {url: "<?php echo $the_permalink; ?>", title: "<?php echo $the_title; ?>"});</script>
                </div>
            </div>
            <div style="clear: left; padding: 10px 0 0 10px;">
            	<p><strong>Title:</strong><input type="text" class="txt_after_post" id="txt_title" name="the_title" value="<?php echo $the_title; ?>" onClick="SelectAll('txt_title');" /></p>
	            <p><strong>Link:</strong> <input type="text" class="txt_after_post" id="txt_link" name="the_permalink" value="<?php echo $the_permalink; ?>" onClick="SelectAll('txt_link');" />
            </div>
            <div style="float: left; padding: 10px; width: 380px;">
                <p><textarea rows="8" name="textarea-afterpost" class="ta_after_post" id="txtarea_all" onClick="SelectAll('txtarea_all');" style="width: 370px;"><?php echo $wrapUpText; ?></textarea></p>
                <p> <a href="javascript:;" onClick="createShortURL();" class="button_blue">Create Short URL</a></p>
            </div>
            	<?php if(!$dont_find_twitter && $totalUsersFound > 0) { ?>
        <div style="float: left; width: 150px; padding: 20px 0 0 0;">
            <?php echo $totalUsersFound; ?> Twitter User(s) Added:<br />
            <?php     
		            foreach($new_array_without_nulls as $value) { 
				?>
             <a href="http://www.twitter.com/#!/<?php echo $value; ?>" target="_blank" rel="<?php echo $SUPPORT_FILE_URL; ?>twitter-user-info.php?twitterUserName=<?php echo $value; ?>&supportURL=<?php echo urlencode($SUPPORT_FILE_URL); ?>" class="twitter_name">@<?php echo $value; ?></a><br />
            <?php } ?>
       </div>
	<?php } ?>
		<?php 
		}  ?>
        
<?php if(!$justPosted) { ?>
		<div id="titlediv">
			<div class="titlewrap">
				<input name="title" id="title" class="text" value="<?php echo esc_attr($title);?>"/>
			</div>
		</div>
		<div id="extra-fields" style="display: none"></div>
		<div class="postdivrich">
		<?php
		// MainEditor
		$editor_settings = array(
			'teeny' => false,
			'textarea_rows' => 23
		);

		$content = '';

		//remove_action( 'media_buttons', 'media_buttons' );
		//add_action( 'media_buttons', 'press_this_media_buttons' );

		wp_editor( $content, 'content', $editor_settings );
		
		?>
		</div>
        
	</div>
    <div id="side-sortables" class="press-this-sidebar">
        <div class="sleeve">
            <?php wp_nonce_field('press-this') ?>
            <input type="hidden" name="post_type" id="post_type" value="curation"/>
            <?php
            // we are adding hidden twitters because we use them after the post
            // FYI there is some jquery that erases these values if the user clicks the checkmark next to the user
            if(!$dont_find_twitter && isset($TwitterUsersFound)){
                $i=0;
                foreach($TwitterUsersFound as $value) {
                    ?>
                    <input type="hidden" name="twitter_username[<?php echo $i; ?>]" id="<?php echo $value; ?>" value="<?php echo $value; ?>"/>
                    <?php
                    $i++;
                }}
            ?>
            <input type="hidden" name="autosave" id="autosave" />
            <input type="hidden" id="original_post_status" name="original_post_status" value="draft" />
            <input type="hidden" id="prev_status" name="prev_status" value="draft" />
            <input type="hidden" id="post_id" name="post_id" value="<?php echo (int) $post_ID; ?>" />
            <!-- This div holds the photo metadata -->
            <div class="photolist"></div>
            <div id="submitdiv" class="postbox">
                <!-- StartOfSidebar -->
                <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
                <h3 class="hndle"><?php _e('CurateThis&trade;') ?><a href="<?php echo $_SERVER['REQUEST_URI'] ?>&repull=true" class="button_red_curate" id="repull_link" style="margin: 5px 0 7px 14px;">Advanced Repull</a></h3>
                <div class="inside">
                    <p id="publishing-actions">
                        <?php
                        submit_button( __( 'Save Draft' ), 'button', 'draft', false, array( 'id' => 'save' ) );
                        if ( current_user_can('publish_posts') ) {
                            submit_button( __( 'Publish' ), 'primary', 'publish', false );
                        } else {
                            echo '<br /><br />';
                            submit_button( __( 'Submit for Review' ), 'primary', 'review', false );
                        } ?>
                        <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" id="saving" style="display:none;" />
                    </p>
                    <!-- PUBLISH BUTTON -->
                    <div class="misc-pub-section curtime misc-pub-curtime">
                        <span id="timestamp">
                        <?php printf($stamp, $date); ?></span>
                        <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><?php _e('Edit') ?></a>
                        <div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
                    </div>
                </div>
            </div>

            <?php $tax = get_taxonomy( 'category' ); ?>
            <div id="categorydiv" class="postbox">
                <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
                <h3 class="hndle"><?php _e('Categories') ?></h3>
                <div class="inside">
                    <div id="taxonomy-category" class="categorydiv">

                        <ul id="category-tabs" class="category-tabs">
                            <li class="tabs"><a href="#category-all"><?php echo $tax->labels->all_items; ?></a></li>
                            <li class="hide-if-no-js"><a href="#category-pop"><?php _e( 'Most Used' ); ?></a></li>
                        </ul>

                        <div id="category-pop" class="tabs-panel" style="display: none;">
                            <ul id="categorychecklist-pop" class="categorychecklist form-no-clear" >
                                <?php $popular_ids = wp_popular_terms_checklist( 'category' ); ?>
                            </ul>
                        </div>

                        <div id="category-all" class="tabs-panel">
                            <ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
                                <?php wp_terms_checklist($post_ID, array( 'taxonomy' => 'category', 'popular_cats' => $popular_ids ) ) ?>
                            </ul>
                        </div>

                        <?php if ( !current_user_can($tax->cap->assign_terms) ) : ?>
                            <p><em><?php _e('You cannot modify this Taxonomy.'); ?></em></p>
                        <?php endif; ?>
                        <?php if ( current_user_can($tax->cap->edit_terms) ) : ?>
                            <div id="category-adder" class="wp-hidden-children">
                                <h4>
                                    <a id="category-add-toggle" href="#category-add" class="hide-if-no-js">
                                        <?php printf( __( '+ %s' ), $tax->labels->add_new_item ); ?>
                                    </a>
                                </h4>
                                <p id="category-add" class="category-add wp-hidden-child">
                                    <label class="screen-reader-text" for="newcategory"><?php echo $tax->labels->add_new_item; ?></label>
                                    <input type="text" name="newcategory" id="newcategory" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->new_item_name ); ?>" aria-required="true"/>
                                    <label class="screen-reader-text" for="newcategory_parent">
                                        <?php echo $tax->labels->parent_item_colon; ?>
                                    </label>
                                    <?php wp_dropdown_categories( array( 'taxonomy' => 'category', 'hide_empty' => 0, 'name' => 'newcategory_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;' ) ); ?>
                                    <input type="button" id="category-add-submit" data-wp-lists="add:categorychecklist:category-add" class="button category-add-submit" value="<?php echo esc_attr( $tax->labels->add_new_item ); ?>" />
                                    <?php wp_nonce_field( 'add-category', '_ajax_nonce-add-category', false ); ?>
                                    <span id="category-ajax-response"></span>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="tagsdiv-post_tag" class="postbox">
                <div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
                <h3><span><?php _e('Tags'); ?></span></h3>
                <div class="inside">
                    <div class="tagsdiv" id="post_tag">
                        <div class="jaxtag">
                            <label class="screen-reader-text" for="newtag"><?php _e('Tags'); ?></label>
                            <input type="hidden" name="tax_input[post_tag]" class="the-tags" id="tax-input[post_tag]" value="" />
                            <div class="ajaxtag">
                                <input type="text" name="newtag[post_tag]" class="newtag form-input-tip" size="16" autocomplete="off" value="" />
                                <input type="button" class="button tagadd" value="<?php esc_attr_e('Add'); ?>" tabindex="3" />
                            </div>
                        </div>
                        <div class="tagchecklist"></div>
                    </div>
                    <p class="tagcloud-link"><a href="#titlediv" class="tagcloud-link" id="link-post_tag"><?php _e('Choose from the most used tags'); ?></a></p>
                </div>
            </div>

        </div>
    </div>
<?php 
	//get Images
	function get_all_images()
	{
		//$html->find
		global $data, $html, $url, $currentDomain, $verifyImageExtension, $checkForBadImageURLS;
			//$url = wp_kses(urldecode($url), null);
		if($currentDomain == 'google.com')
			@$html->load_file($url);
		
	//			$uri = preg_replace('/\/#.+?$/','', $uri);
	//			if ( preg_match('/\.(jpg|jpe|jpeg|png|gif)$/', $uri) && !strpos($uri,'blogger.com') )
	//			return "'" . esc_attr( html_entity_decode($uri) ) . "'";
		
		$sources = array();
		foreach($html->find("img") as $img)
		{
			//for some odd reason the best way to reach certain files is not the SRC tag but the ORIGINAL tag
			$src = $img->src;
			
			// let's remove some bad chars
			$src = preg_replace('/\/#.+?$/','', $src);
//			$src = str_replace('..', '', $src);
			// hey is this a image we like?
			
			if($verifyImageExtension)
			{		
				if (!preg_match('/\.(jpg|jpe|jpeg|png|gif)/', $src))
					continue;
			}
			// getting the host
			$host = parse_url($url);
			
			// what no http? well let's add that bia
			if (strpos($src, 'http') === false)
			{
				// if it doesn't have a relative uri
				if ( strpos($src, '../') === false && strpos($src, './') === false && strpos($src, '/') === 0)
					$src = 'http://'.str_replace('//','/', $host['host'].'/'.$src);
				else
					$src = 'http://'.str_replace('//','/', $host['host'].'/'.dirname($host['path']).'/'.$src);
			}
			
			/* Here we are checking for images we really don't want to show as options */
			// CleanImages
			if($checkForBadImageURLS)
			{
				/*
					Consider adding:
					'/pixel' // Youtube pixels
				*/
				$badURLStrings = 
				array(
					'ad.doubleclick.net', //advertising
					'clear.gif', 'spacer.gif', 'noscript.gif', 'njs.gif', 'event=noscript', 'ajax-loader.gif', '1px.gif', 'loading.gif', 'trans.gif', 'img-dot.gif', 'spaceout.gif', '1.gif', 'transparent.png', 'separator.png', // kinda obvious
					'rss.png','email.jpg','avatar.png', 'gplus-32.png','email-icon.gif','rss.gif', // some pretty common
					'/cgi-bin', '.php?',// unknown
					'lg-share-en.gif', // http://s7.addthis.com/static/btn/lg-share-en.gif
					'zor.livefyre.com', '/livefyre-avatar', // livefyre commenting
					'site-logo-cutout.png', // techcrunch logo
					'scorecardresearch.com', // Comscore research image
					'quantserve.com', // Quantcast Measurement
					'http://stats.wordpress.com/b.gif?v=noscript', // wordpress tracking
					'sharethis.com/chicklets', 'share_save_106_16.gif', // sharethis small icons
					'webtrendslive.com', // webtrends tracking
					'.youtube-nocookie.com','yt/img/pixel', '//i2.ytimg.com', // youtube
					'http://passets-cdn.pinterest.com', 'pinterest.com/avatars/', // pinterest.com common images
					'http://graph.facebook.com', // facebook social graph images
					'gravatar.com/avatar', // gravatar avatar images
					'getclicky.gif', //get clicky
					'/static.networkedblogs.com', // networked blogs logo
					'http://www.1shoppingcart.com/app/', // 1shopping cart buttons
					'wsj.net/img/b.gif', // wsj site
					'leadback.advertising.com', //advertising platform
					'toolkit-addthis.jpg',
					'media.fastclick.net',
					'ico-rss.gif', 'ico-twitter.gif', 'ico-apple.gif', 'ico-facebook.png', 'ico-twitter.png', 'ico-del.png', 'ico-digg.png',// standard icon naming
					'.html', 'adx_remoif',
					// Below are images that if we need to remove because this get's to big we should as they aren't as wide as the ones above
					'print_190.gif',
					'print-button.gif',
					'x.gif',
					'rating_on.gif',
					'rating_off.gif',
					'favicon_poll.png',
					'?P=', // yeahoo images URL parameter
					'reddit.gif' // reddit share
				);
	
				// adding URL specfic doamains to block certain images, no need to add these other places because they might cause problems 
				if($currentDomain == 'pininterest.com')
					$badURLStrings[] = '_t.jpg'; // these seem to be non user uploaded images, user uploaded images end with _c.jpg
				
				$foundBadURL = false;
				foreach ($badURLStrings as $value) 
				{
					if(strpos($src,$value) !== false)
					{
						$foundBadURL = true;
						break;
					}
				}
				//if we found a bad url then we skip this element so continue
				if($foundBadURL)
					continue;
			}
				
	
			if($check_image_sizes == 1)
			{
			
				$imageSize = 0;
			  if($imageData = @getimagesize($src));
			  {
				$imageSize = $imageData[0];
			  }
			  if($imageSize >= 100)
			  {
					$sources[] = esc_url($src);
			  }
			
			}
			else
			{
					$sources[] = esc_url($src);
			}
	
		}
		$sources = array_unique($sources);
		if($currentDomain == "imgur.com")
			$sources[] = $url;
		return $sources;
	}

		function RemoveBS($Str) {  
		  $StrArr = str_split($Str); $NewStr = '';
		  foreach ($StrArr as $Char) {    
			$CharNo = ORD($Char);
			if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
			if ($CharNo > 31 && $CharNo < 127) {
			  $NewStr .= $Char;    
			}
		  }  
		  return $NewStr;
		}
	function returnGoodText($inText)
	{
		/* okay we do alot here, basically we try to get rid of all the bad text and characters that we could find.	
		we need to refactor this sometime
		*/
		// check this: http://stackoverflow.com/questions/8334667/convert-special-characters-to-html-code-with-php

		
		$inTextOG = $inText;
	
		$inText = strip_tags($inText);

		$inText = str_replace(array("\n","\t","\r","\0", "</p>", "<p>", "&nbsp;", " &nbsp;", "&nbsp;&nbsp;", " -&nbsp;", "&nbsp;&nbsp;&nbsp;", "&#10", "&#09", "\r\n", "\xa0",
		 ".&nbsp;", "&#0160;", "  ","\r\n", "\x0B","Ãƒ","Æ’","Ã¢","Â¦","Å¡","&#194","â","â€™", chr(173), chr(0xC2).chr(0xA0)), '', $inText);
		$inText = removeBS($inText);
		// this goes thru the most common html special characters and replaces them... we should use htmlentities!
		$trans = 
			array(
				'&#8216;' => '‘',
				'&#8217;' => '’',
				'&#8220;' => '“', 
				'&#8221;' => '”',
				'&#8211;' => '–',
				'&#8212;' => '—', 
				'&#8230;' => '…',
				'Â' => ''
				);
		$inText = strtr($inText, $trans);

		// this remove all non UTF-8 chars but keeps quotes
		$inText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\xFF]/u', '', $inText);

	
		$paraLen = strlen($inText);
		$inText = mb_substr($inText, 0, $paraLen, "UTF-8");

		// we might in the future want to explode everything		
		$wordChunks = explode(" ", $inText);
		$inText = '';
		for($i = 0; $i < count($wordChunks); $i++){
			$curString = $wordChunks[$i];
			if($curString == '\n')
				$curString = nl2br($curString);
			
			$inText .= ' ' . $curString;
		}
		if(strlen($inText) <= 1)
		{
			$inText = $inTextOG;
			$inText = strip_tags($inText);
		}
		
		$inText = str_replace(array("\n","\r\n", "\n\n", "\r","â"), "", $inText);
		$inText = trim( ( html_entity_decode($inText, ENT_QUOTES, 'UTF-8') ) );	

		//consider removing
		//$inText = nl2br($inText);
		$inText = addslashes(trim($inText));
		$inText = str_replace(array('\\\n'), '\n', $inText);
		$inText = str_replace(array('&nbsp;'), '', $inText);
		return $inText;
	}

	function isGoodParagraph($inPossiblePara)
	{
		// this function cycles through words and phrases looking for common ones we don't want to show as options
		$foundBad = true;
		$inPossiblePara = trim($inPossiblePara);
		$paraLen = strlen($inPossiblePara);
		if($paraLen <= 2)
			return false;
	
		$badStrings = array('jQuery(','Home>Archive', 'Previous / Next', 'Last updated at', 'View the discussion thread', 'Last Updated: ', '| Subscribe');
		foreach ($badStrings as $value) 
		{
			if(strpos($inPossiblePara,$value) !== false)
			{
				$foundBad = false;
				break;
			}
		}
		return $foundBad;	
	}
?>

<script id="curatethis_main_content_and_functions_js">
(function($) {

    $(document).ready(function() {


<?php 

	// here we look through the paragraph and sentance array and create the javacript vars P1 thru Px depending on what the settings are below.
	$currentPara = 1;
	$i = 0;
	$checkPara = '';
	$Paragraphs = array();
	// move up $totalParagraphsToCheck = 18;
	echo "// total s_or_p: ". count($sentances_or_paragraphs);
	while ($i <= $totalParagraphsToCheck):
		$checkPara = returnGoodText($sentances_or_paragraphs[$i]);
		if(isGoodParagraph($checkPara))
		{
			$Paragraphs[] = $checkPara;
		}
		$i++;
	endwhile;
	
	// now we set the javacript vars
	$i=1;
	$foundParaNumber = 1;
	/* here is what we want: p1 = 'VALUE'; */
	$paraLen = 0;
	echo "\nvar paragraphArray = new Array(" . count($Paragraphs) . ");\n";
	while ($i <= $totalParagraphsToCheck):
	{
		$checkPara = $Paragraphs[$i-1];
		// we do this so we can check if it found any paragraphs
		$checkPara = getSentancesCombined($checkPara);
	//	echo "\nparagraphArray[" . $foundParaNumber . "] = '" . addslashes($checkPara) . "';";
		echo "\nparagraphArray[" . $foundParaNumber . "] = '" . $checkPara . "';";
		echo "\n // length: " . strlen($checkPara);
		$foundParaNumber++;
		$i++;
	}
	endwhile;


	// well if we didn't find any paragraphs we need to set the Paragraph (p1-X) variables so we don't show undefined 
	if($foundParaNumber == 0)
	{
			while($foundParaNumber <= 7):
			{
				echo "\np" . $foundParaNumber . " = '';";
				$foundParaNumber++;
			}
			endwhile;
	}
	
	$lenSelection = strlen($selection);
	$trimSelection = returnGoodText($selection);
	if($trimSelection == ' ' && $lenSelection > 1)
	{
		echo "\n//selection if";
		$selection = addslashes($selection);
	}
	else
	{
		echo "\n//selection else";
		$selection = returnGoodText($selection);
	}
//$selection = "len" . strlen($trimSelection);

	echo "\nselection = '" .  $selection . "';";
	echo "\nMetaDescription = '" .  returnGoodText(htmlspecialchars($meta_desc)) . "';";
	echo "\n h2_combined_s = '" .  returnGoodText($h2_combined_s) . "';";
	echo "\n h3_combined_s = '" .  returnGoodText($h3_combined_s) . "';";
	echo "\n h4_combined_s = '" .  returnGoodText($h4_combined_s) . "';";
	echo "\n h5_combined_s = '" .  returnGoodText($h5_combined_s) . "';";
	echo "\n strong_combined_s = '" .  returnGoodText($strong_combined_s) . "';";
	echo "\n b_combined_s = '" .  returnGoodText($b_combined_s) . "';";
	
//	Clipped ME summary
function ybi_isCurlOn(){
    return function_exists('curl_version');
}

// if curl isn't on then don't load the clipped call
if(!ybi_isCurlOn())
	$turn_off_clipped_api = true;
//console("got here".$turn_off_clipped_api);

//$turn_off_clipped_api = false;
if(!$turn_off_clipped_api)
{
		// this function cleans up common things wrong with clipped text
		function cleanForClipped($inText)
		{
			$trans = 
				array(
					',' => ', ',
					':' => ': ',
					);
			$inText = strtr($inText, $trans);			
			return $inText;	
		}

		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// set the timeout
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,"http://clipped.me/algorithm/clippedapi.php?url=".$url);
		// Execute
		$clippJSON=curl_exec($ch);
	
		// this was the old call using file_get_contents
		//$clippJSON = mb_convert_encoding(file_get_contents("http://clipped.me/algorithm/clippedapi.php?url=".$url), "HTML-ENTITIES","UTF-8");
		$ClippData = json_decode($clippJSON, FALSE);
		//var_dump($ClippData);

		$totalSummary = '';
		$i = 0;
		
			// verify the json data exists
			if(is_array($ClippData->summary))
			{
				// if so then we combine all the bullets into one paragraph.
				foreach($ClippData->summary as $bullet)
				{
					if($i>0)
						$totalSummary .= ' ' . cleanForClipped(returnGoodText($bullet));	
					else
						$totalSummary .= cleanForClipped(returnGoodText($bullet));
					$i++;
				}
				echo "\nClippedSummary = '" . $totalSummary . "';\n";
			}
}


	// here we get all the images and create the javascript image vars
	$imagesToShow = array();
	$imagesToShow = get_all_images();
	$i=0;
	$finalTotalImages = 0;
	$firstImageURL = ''; 
	$js_array_string;
	if(!empty($imagesToShow))
	{
		foreach ($imagesToShow as $value) 
		{
			if(strlen($value[0]) > 0) // if(strlen($value) > 0)
			{
				$value = preg_replace('/&([^#])(?![a-z1-4]{1,8};)/i', '&#038;$1', $value);
				$value = esc_url ( $value );
				$value = wp_specialchars_decode($value);
				$js_array_string = $js_array_string . 'imgArray[' . $i . '] = "' . urldecode($value). "\"\n";
				if($i==0)
					$firstImageURL = $value;
				$i++;
			}

		}
		$finalTotalImages = $i;
	}
?>
var NumberOfImages = <?php echo $finalTotalImages; ?>;
var currentImageURL = '';
var imgArray = new Array(NumberOfImages);
var imgNumber = 0;
var currentAlign = 'alignleft';

<?php echo $js_array_string; ?>

	function getOverallText()
	{
		var returnText = '';
		returnText = tinyMCE.activeEditor.getContent({format : 'raw'});
		returnText = returnText.replace('<p><br data-mce-bogus="1"></p>','');
		return returnText;
	}
	function setEditorText(inText)
	{
		jQuery("#content").text(inText); 
		tinyMCE.activeEditor.execCommand('mceSetContent', false, jQuery('#content').text());
	}
$( window ).load(function() {
  // Run code
				var overall_text = '';
				overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});				
				var currentImageSRC = imgArray[imgNumber];			
				var theFirstImageSRC = '<img src="' + currentImageSRC +  ' " class="alignleft curated_image" alt="">';
				$("#content").text(theFirstImageSRC + overall_text); 
				tinyMCE.activeEditor.execCommand('mceSetContent', false, $("#content").text());
				$('.MySelectorDiv').fadeTo(500, 1);
				$('.curate_images').fadeTo(500, 1);
				tinyMCE.activeEditor.execCommand("mceRepaint");
				$('#txtbox_img_url').val(currentImageSRC);
				
//		jQuery("#content").text(inText); 
//  		tinyMCE.activeEditor.execCommand('mceSetContent', false, $('#content').text());	
//		tinyMCE.activeEditor.execCommand("mceRepaint");
});

     function changeImage(inCurImageNumber, inSRC)
      {     
            
			
			var ed = tinyMCE.get('content');                // get editor instance
			var isthisimage = tinyMCE.activeEditor.dom.select('img');
			ed.execCommand('mceRemoveNode', false, isthisimage);
			$('#content-tmce').click();
			/*
			var overall_text = "";
            var inSRCIndex = 0;
			overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			var original = overall_text;
			overall_text = overall_text.replace('<p><br data-mce-bogus="1"></p>','');
			var currentImageSRC = imgArray[inCurImageNumber];

			inSRC= inSRC.replace(/\&amp;/,'&');
			inSRC= inSRC.replace(/\&lt;/,'<');
			currentImageSRC= currentImageSRC.replace(/\&amp;/,'&');
			currentImageSRC= currentImageSRC.replace(/\&lt;/,'<');
			overall_text= overall_text.replace(/\&amp;/,'&');
			overall_text= overall_text.replace(/\&lt;/,'<');
	
			overall_text = overall_text.replace(currentImageSRC,inSRC);

			var the_width = 0
			var the_height = 0;
			var theFirstImageSRC = "";
			var img = new Image();
			img.src = inSRC;
			the_width = 'width="' + img.width + '"';
			the_height = 'height="' + img.height + '"';

			overall_text = overall_text.replace(/width="\d+"/gi, the_width);
			overall_text = overall_text.replace(/height="\d+"/gi, the_height);

			overall_text= overall_text.replace(/\&amp;/,'&');
			overall_text= overall_text.replace(/\&lt;/,'<');

			$("#content").text(overall_text);
			
			tinyMCE.activeEditor.execCommand('mceSetContent', false, $("#content").text());
			tinyMCE.activeEditor.execCommand("mceRepaint");
*/			
			
			
			var overall_text = '';
			var original = overall_text;
			overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});	
			overall_text = overall_text.replace('<p><br data-mce-bogus="1"></p>','');
			overall_text= overall_text.replace(/\&amp;/,'&');
			overall_text= overall_text.replace(/\&lt;/,'<');
			overall_text= overall_text.replace(/\&nbsp;/,'');
			overall_text= overall_text.replace(/(&nbsp;|&#160;|\u00a0| ){2}/gi,'')
			var currentImageSRC = imgArray[imgNumber];			
			var theFirstImageSRC = '<img src="' + inSRC +  '" class="alignleft curated_image" itemprop="image" alt="">';
			$("#content").text(theFirstImageSRC + overall_text); 
			tinyMCE.activeEditor.execCommand('mceSetContent', false, $("#content").text());
			tinyMCE.activeEditor.execCommand("mceRepaint");
			
    }

         $('.no_img_checkbox').click(function() {
            if ($(this).attr('checked'))
            {
				var ed = tinyMCE.get('content');                // get editor instance
				var isthisimage = tinyMCE.activeEditor.dom.select('img');
				ed.execCommand('mceRemoveNode', false, isthisimage);
				
				var isthisimageP = tinyMCE.activeEditor.dom.select('p');
				ed.execCommand('mceRemoveNode', false, isthisimageP);
				
				overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});
				var curNum = imgNumber;
				$('.MySelectorDiv').fadeTo(500, 0.2);
				$('.curate_images').fadeTo(500, 0.2);
					tinyMCE.activeEditor.execCommand("mceRepaint");

            }
            else
            {
				var overall_text = '';
				overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});				
				var currentImageSRC = imgArray[imgNumber];			
				var theFirstImageSRC = '<img src="' + currentImageSRC +  ' " class="alignleft curated_image" alt="">';
				$("#content").text(theFirstImageSRC + overall_text); 
				tinyMCE.activeEditor.execCommand('mceSetContent', false, $("#content").text());
				$('.MySelectorDiv').fadeTo(500, 1);
				$('.curate_images').fadeTo(500, 1);
				tinyMCE.activeEditor.execCommand("mceRepaint");
				$('#txtbox_img_url').val(currentImageSRC);
            }
        });
        
        $('#my_meta_box_select').change(function() {
			overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			overall_text = overall_text.replace('<p><br data-mce-bogus="1"></p>','');
            overall_text = overall_text.replace(currentAlign,$(this).val());
            currentAlign = $(this).val();
			overall_text = overall_text.replace('.jpg ','.jpg');
			overall_text = overall_text.replace('.jpg%20','.jpg');
			jQuery('#content').text(overall_text); 
			tinyMCE.activeEditor.execCommand('mceSetContent', false, jQuery('#content').text());
        });
        
        $('a.prev-link').click(function() {
            isNoImageChecked = $('.no_img_checkbox').attr('checked');
            if(isNoImageChecked)
            {
                alert('To change image unselect No Image checkbox');
                return;
            }
            var curImgNumber = imgNumber;
            imgNumber--;
            if (imgNumber < 0)
                imgNumber = NumberOfImages - 1;
			$(".current_image").text(imgNumber+1);
			$("#txtbox_img_url").val(imgArray[imgNumber]);
			$("#VCRImage").attr("src",imgArray[imgNumber]);
            changeImage(curImgNumber, imgArray[imgNumber]);
            
            //alert(imgNumber);
        });
        $('a.next-link').click(function() {
            isNoImageChecked = $('.no_img_checkbox').attr('checked');
            if(isNoImageChecked)
            {
                alert('To change image unselect No Image checkbox');
                return;
            }
            var curImgNumber = imgNumber;
            imgNumber++;
            if (imgNumber == NumberOfImages)
                imgNumber = 0;
			$(".current_image").text(imgNumber+1);
			$("#txtbox_img_url").val(imgArray[imgNumber]);
			$("#VCRImage").attr("src",imgArray[imgNumber]);
            changeImage(curImgNumber, imgArray[imgNumber]);
            //alert(imgNumber);
        });

        function addText(inParagraphNumber) {
			var inP = '';
			if(inParagraphNumber == '1001')
			{
				tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('blockquote'));
				tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('img'));
				tinyMCE.activeEditor.execCommand("mceRepaint");
				return;
			}
			inP = paragraphArray[inParagraphNumber];
            var overall_text = "";
			var original_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			var ed = tinyMCE.get('content'); 
			ed.dom.add(ed.getBody(), 'blockquote', {'class' : 'curated_content'}, inP );				
        }
		
		function addTextToStaging(inText)
		{
			stageValue = '';
			stageValue = $("#curated_content_txtarea").val();
			isAddTo = $('#add_to_chbx').attr('checked');
			if(isAddTo)
			{
				// old change before fix?
				// stageValue = stageValue + '\n\n';
				if(stageValue.length > 0)
					stageValue = stageValue + '\n\n';

				$("#curated_content_txtarea").val(stageValue + inText);
			}
			else
			{
				$("#curated_content_txtarea").val(inText);
			}
		}

        $('a.change').click(function() {
			addTextToStaging(paragraphArray[this.rel]);
        });
		
        $('a.meta').click(function() {
			addTextToStaging(MetaDescription);
        });
		
        $('a.strongContent').click(function() {
			addTextToStaging(strong_combined_s);
        });
		
        $('a.boldContent').click(function() {
			addTextToStaging(b_combined_s);
        });
				
        $('a.h2').click(function() {
			addTextToStaging(h2_combined_s);
        });
		
        $('a.h3').click(function() {
			addTextToStaging(h3_combined_s);
        });
		
        $('a.h4').click(function() {
			addTextToStaging(h4_combined_s);
        });

        $('a.h5').click(function() {
			addTextToStaging(h5_combined_s);
        });

        $('a.ClippedSummary').click(function() {
			addTextToStaging(ClippedSummary);
        });


        $('a.clear_staging').click(function() {
				$("#curated_content_txtarea").val('');
        });
		
		// the below function turns line breaks into brs
		function nl2br (str, is_xhtml) {   
			var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
			return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
			//return (str + '').replace(/([^>\n\n]?)(\n\n)/g, '$1'+ breakTag +'$2');
		}
		function nl2brSimple(value) {
 			 return value.replace(/\n\n/g, "<br />");
		}
		
        $('a#add_to_post_box').click(function() {
			var stageValue = '';
			$('#content-tmce').click();
			stageValue = $("#curated_content_txtarea").val();
			//after we get the value let's make sure the breaks stay in
			var textWithBreaks = nl2br(stageValue);

			// testing
			//var textWithBreaks = nl2brSimple(stageValue);
			//textWithBreaks = stageValue;
			//textWithBreaks = textWithBreaks.replace("<br>","");

            var overall_text = "";
			var original_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			var ed = tinyMCE.get('content');
			// here we are looking for youtube or vimeo, or another embedded object. If it is we add it without blockquotes
			if((stageValue.search("<object ") >= 0) || (stageValue.search("<iframe ") >= 0))
			{
				ed.dom.add(ed.getBody(), 'p', '', stageValue );	
			}
			else
			{
				// here we add the curated content that was staged, plus wrap it in a blockquote with a class
				<?php if($dont_use_blockquote) { ?>
					ed.dom.add(ed.getBody(), 'p', '', textWithBreaks );	
				<?php } else { ?>
					ed.dom.add(ed.getBody(), 'blockquote', {'class' : 'curated_content'}, textWithBreaks );	
				<?php } ?>
			}
        });

        $('a#create_caption').click(function() {
            var overall_text = "";
			var imageCreditText = "";
			var original_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			var isthisimage = tinyMCE.activeEditor.dom.select('img');
//			isthisimage.insertAdjacentHTML('beforebegin', '[caption]');
			var ed = tinyMCE.get('content');                // get editor instance
			var isthisimage = tinyMCE.activeEditor.dom.select('img');
			var this_height = isthisimage[0].height;
			var this_width = isthisimage[0].width;
			var zero = isthisimage[0].outerHTML;
			ed.execCommand('mceRemoveNode', false, isthisimage);
			var overall_text = '';
			overall_text = tinyMCE.activeEditor.getContent({format : 'raw'});
			imageCreditText = $("#image_credit_text").val();
						
			var theFirstImageSRC = '[caption id="" align="alignleft" width="'+this_width+'" caption="'+imageCreditText+'"]'+zero +'[/caption]';
			theFirstImageSRC = theFirstImageSRC.replace('>',' width="'+this_width+'" height="'+this_height+'" />');
			$("#content").text(theFirstImageSRC + overall_text); 
			tinyMCE.activeEditor.execCommand('mceSetContent', false, $("#content").text());
			tinyMCE.activeEditor.execCommand("mceRepaint");
        });



	    $('a.image_attribution').click(function() {
			
			if(this.rel == 'clear')
			{
				$('#image_credit_text').val('');
				return;
			}
			
			var prevText = '';
			var combinedText = '';
			prevText = $("#image_credit_text").val();
			if(prevText.length > 0)
				combinedText = prevText + this.rel;
			else
				combinedText = this.rel;
				
			$('#image_credit_text').val(combinedText);
        });

	   $("#cleanHeadline").click(function(event){
			var ogHeadline = '';
			// get headline
			ogHeadline = $("#title").val();
			// save to new so we have the og just in case we need it
			changedHeadline = ogHeadline;
			// find common way people name headlines

			//var n = changedHeadline.indexOf('|');
			// get all the stuff before
			//changedHeadline = changedHeadline.substring(0, n != -1 ? n : changedHeadline.length);
			//changedHeadline = $.trim(changedHeadline);
			
			// common headline seperators we check for
			var charArray = ['|',' -', ' —', ' –'];
			$.each( charArray, function( intValue, currentElement ) 
			{
				// find current element location
				var n = changedHeadline.indexOf(currentElement);
				// create a substring
				changedHeadline = changedHeadline.substring(0, n != -1 ? n : changedHeadline.length);
				changedHeadline = $.trim(changedHeadline);
				  // Do work with currentElement 
			});


			// set the headline back and trim
			$("#title").val(changedHeadline);
	   });

	   $("#originalHeadline").click(function(event){
			var ogHeadline = '<?php echo addslashes($title)//esc_attr($title);?>';
			// get headline

			$("#title").val($.trim(ogHeadline));
	   });


    })



	
})(jQuery);


// I think this function get's the current cursor position.

(function($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
	

})(jQuery);

</script>

<?php my_meta_setup_curate(); ?>

</div>
</form>

<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>

<script LANGUAGE="JavaScript" id="curatethis_images_first_p_js">
currentImageURL ='<?php echo $firstImageURL ?>';
//oFormObject.elements["_my_meta[curated_image_url]"].value = '<?php echo $firstImageURL ?>';
//oFormObject.elements["_my_meta[curated_link]"].value = '<?php echo $url ?>';
document.images["VCRImage"].src = '<?php echo $firstImageURL ?>';
document.getElementById('number_images_label').innerHTML = <?php echo $finalTotalImages; ?>;
document.getElementById('curate_domain').innerHTML = '<?php echo getDomainNameCurationTraffic($url)."<br>"; ?>';
<?php 
	global $isRepull;
	$isRepull = $isRepull == 'true';

/*	if($finalTotalImages <= 2)
		echo "document.getElementById('image-selectors').style.visibility = 'hidden';\n";
*/

	if($finalTotalImages == 0 && !$isRepull)
	{
		// need to fix
		//	echo "document.getElementById('no_image_chk_div').style.visibility = 'hidden';\n";
		echo "document.getElementById('image_controls').innerHTML = '';\n";
		echo "document.getElementById('error_box').innerHTML = '<div class=\"warning\">Found no images, consider repull if text below is not found as well.";
		if($isIframe)
			echo " <strong>Also see Iframe warning at the top of the page.</strong>";
		echo "</div>';\n";
		// need to fix
		//echo "document.getElementById('chbx').attr('checked',true);\n";
		// this be a fix
		//echo "jQuery('#no_image_chk_div').hide();";
		//echo "jQuery('#chbx').hide();";
	}
	//echo '//isrepull = ' . $isRepull;
	if($isRepull && $finalTotalImages == 0)
	{
		echo "document.getElementById('image_controls').innerHTML = '';\n";
		echo "document.getElementById('error_box').style.visibility = 'visible';\n";
		echo "document.getElementById('error_box').innerHTML = '<div class=\"info\">If <strong>repull</strong> did not get all you wanted it is best to copy and paste your missing elements.</div>';\n";
		echo "document.getElementById('no_image_chk_div').style.visibility = 'hidden';\n";
	}
	//Okay well some sites will never cooperate so we warn the user that they might have to manually do stuff
	// here we list the sites in the array that post warning
	$warningMessage = '';
	$warningSites = 
	array('pinterest.com');
	foreach ($warningSites as $value) 
	{
		if(strpos($currentDomain,$value) !== false)
		{
			$warningMessage = "When pulling info from " . $currentDomain . " you will want to verify the headline and any text below. Using the highlight selection function works best for this site when pulling in text.";
			break;
		}
	}
			
	if($warningMessage != '')
	{
		echo "\ndocument.getElementById('error_box').innerHTML = '<div class=\"info\">". $warningMessage . "</div>';\n";
	}
			
		
	// below we are setting the first paragraph text and teh first paragraph text
	$firstParagraph = '';
	$firstParagraphNumber = 1;
	if ( $selection != '' )
	{
		$firstParagraph = $selection;
		//okay bad form using the var below but didn't want to rename it.
		$firstParagraphNumber = 'Selected Text';
	}
	else
	{
		while(list(, $val) = each($Paragraphs))
		{
			if(strlen($val) > 5)
			{
				$firstParagraph = $val;
				break;
			}
			$firstParagraphNumber++;
		}
	}

// Find YouTube
// if we are on youtube or vimeo then we automatically create a player and put it in the curated box
	$content = '';
	if ( preg_match("/youtube\.com\/watch/i", $url) ) 
	{
		list($domain, $video_id) = split("v=", $url);
		$video_id = esc_attr($video_id);
	
		if (preg_match('%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) 
		{
			$video_id = $match[1];
			//$content = '<object width="432" height="249"><param name="movie" value="http://www.youtube.com/v/' . $video_id . '"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/' . $video_id . '" type="application/x-shockwave-flash" wmode="transparent" width="432" height="249"></embed></object>';
			$content = '<iframe width="500" height="281" src="http://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
			$firstParagraph = $content;
			$firstParagraphNumber = 'Showing YouTube Video';
		}
	} 
	elseif ( preg_match("/vimeo\.com\/[0-9]+/i", $url) ) 
	{
		list($domain, $video_id) = split(".com/", $url);
		$video_id = esc_attr($video_id);
//		$content = '<object width="432" height="249"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id=' . $video_id . '&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />	<embed src="http://www.vimeo.com/moogaloop.swf?clip_id=' . $video_id . '&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="432" height="249"></embed></object>';

$content = '<iframe src="http://player.vimeo.com/video/'. $video_id .'" width="500" height="250" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';		

	$firstParagraph = $content;
	$firstParagraphNumber = 'Showing Vimeo Video';
		if ( trim($selection) == '' )
			$selection = '<p><a href="http://www.vimeo.com/' . $video_id . '?pg=embed&sec=' . $video_id . '">' . $title . '</a> on <a href="http://vimeo.com?pg=embed&sec=' . $video_id . '">Vimeo</a></p>';
	
	} 
	elseif ( strpos( $selection, '<object' ) !== false ) {
		//$content = $selection;
	}

// if we get thru all the paragraphs and nothign is found then we set the var below to blank so it doesn't show up funny
if($firstParagraphNumber == 17)
	$firstParagraphNumber = '';
	
?>
//oFormObject.elements["_my_meta[curated_content]"].value = '<?php echo ($firstParagraph); ?>';

jQuery('#the_paragraphs').append(' <?php echo $firstParagraphNumber; ?>');

// If the user changes the IMage URL we update the image to show it, BALLER!
jQuery("#txtbox_img_url").change(function() { jQuery("#VCRImage").attr("src",jQuery("#txtbox_img_url").val()); });

function removeTwitterFromPublish(inTwitterUserName)
{
	if(jQuery('#chbx-' + inTwitterUserName).is(":checked"))
		jQuery('#' + inTwitterUserName).attr('value','');
	else
		jQuery('#' + inTwitterUserName).attr('value',inTwitterUserName);
}

// okay here we are adding the ability to remove all twitter users or add them back in on click, cool man!
<?php if(!$dont_find_twitter) { ?>
var showAll = true;
function removeAllTwitterUsers() {
	if(showAll)	
	{
		<?php foreach($TwitterUsersFound as $value) { ?>
			jQuery('#<?php echo $value; ?>').attr('value','');
			jQuery("#chbx-<?php echo $value; ?>").attr("checked", "checked");
		<?php } ?>
		showAll = false;
		jQuery("#twitter_all_link").text("Add All");
	}
	else
	{
		<?php foreach($TwitterUsersFound as $value) { ?>
			jQuery('#<?php echo $value; ?>').attr('value', '<?php echo $value; ?>');
			jQuery("#chbx-<?php echo $value; ?>").removeAttr("checked");
		<?php } ?>
		showAll = true;
		jQuery("#twitter_all_link").text("Remove All");
	}
}
<?php } //if(!$dont_find_twitter) ?>



// let us add some options to the repull url so the user can see if they can fix this themselves
function addRepullValue(inValue)
{
	var href = jQuery("#repull_link").attr('href');
	jQuery("#repull_link").prop("href", href + inValue);
}

var checkbox = document.getElementById("chbx");
function NoImage() {
  if(checkbox.checked){
//    alert("meap");image-selectors
		//document.getElementById('curate_images').style.visibility = 'hidden'; 
		//document.getElementById('image_controls').style.visibility = 'hidden'; 
		jQuery('.MySelectorDiv').fadeTo(500, 0.2);
		jQuery('.curate_images').fadeTo(500, 0.2);
		jQuery("a.prev-link").prop("href", "#")
		jQuery("a.next-link").prop("href", "#")
		jQuery("a.prev-link").attr("onclick", "alert('To select an image unclick No Image box');")
		jQuery("a.next-link").attr("onclick", "alert('To select an image unclick No Image box');")
		jQuery(".align_drop").attr("onclick", "alert('To select an image unclick No Image box');")
		//oFormObject.elements["_my_meta[curated_image_url]"].value = '';
  }
  else
  {
//	  document.getElementById('curate_images').style.visibility = 'visible'; 
// 	  document.getElementById('image_controls').style.visibility = 'visible'; 
		jQuery('.MySelectorDiv').fadeTo(500, 1);
		jQuery('.curate_images').fadeTo(500, 1);
  	  //oFormObject.elements["_my_meta[curated_image_url]"].value = currentImageURL;
		jQuery("a.prev-link").prop("href", "javascript:PreviousImage()")
		jQuery("a.next-link").prop("href", "javascript:NextImage()")
		jQuery("a.prev-link").attr("onclick", "")
		jQuery("a.next-link").attr("onclick", "")
		jQuery(".align_drop").attr("onclick", "")
  }
  
}
//oFormObject.elements["_my_meta[curated_content]"].value = p1;
</script>
<?php 
if (isset($html) || empty($html) || is_object($html))
{
  $html->clear();  // **** very important ****
  unset($html);    // **** very important ****
}
//don't think I need this anymore
/*if (isset($thecontent) || empty($thecontent) || is_object($thecontent))
{
  $thecontent->clear();  // **** very important ****
  unset($thecontent);    // **** very important ****
}
*/?>
<?php } // just posted ?>
<?php //echo $s_feedback; ?>
<script id="IntercomSettingsScriptTag">
  var intercomSettings = {
    // TODO: The current logged in user's email address.
	
    name: "<?php 
			global $current_user;
    	get_currentuserinfo();
		echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>",
    // TODO: The current logged in user's email address.
    email: "<?php echo bloginfo('admin_email'); ?>",
	'site_url' : "<?php echo bloginfo('url'); ?>",
    // TODO: The current logged in user's sign-up date as a Unix timestamp.
    created_at: <?php echo time(); ?>,
	current_url: "<?php echo bloginfo('url'); ?>",
	PHP_uname: "<?php echo php_uname(); ?>",
	PHP_OS: "<?php echo PHP_OS; ?>",
    app_id: "4zueud7i"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://static.intercomcdn.com/intercom.v1.js';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
</body>
<script id="curatethis_set_iniitial_content_js">
// set the inital content

	var first_width = 0;
	var first_height = 0;
	var theFirstImageSRC = "";
	var img = new Image();
	img.src = '<?php echo $firstImageURL; ?>';
	first_width = img.width;
	first_height = img.height;
	
	var domain_name = "<?php echo getDomainNameCurationTraffic($url)?>";
	var theInitialContent = '';	
	<?php if($firstImageURL <> '') 
	{
	?>
		//var theFirstImageSRC = '<img src="<?php echo $firstImageURL; ?>" class="alignleft curated_image" alt="" height="' + first_height + '" width="'+first_width+'">';
		//var theFirstImageSRC = '<img src="<?php echo $firstImageURL; ?>" class="alignleft curated_image" alt="">';
		//theFirstImageSRC = theFirstImageSRC.replace('&lt;', '<').replace('&gt;', '>');
		
		//theInitialContent = theFirstImageSRC;
	<?php 
	} ?>
	//jQuery("#content").text(theFirstImageSRC); 
	var theFirstParagrphText = '<?php echo $firstParagraph; ?>';
//var theFirstParagrphText = '<blockquote class="curated_content"><?php echo $firstParagraph; ?></blockquote>';
//jQuery("#content").text(theFirstImageSRC + '<blockquote class="curated_content"><?php echo $firstParagraph; ?></blockquote>'); 
//jQuery("#content").text(theFirstImageSRC + theFirstParagrphText); 
	<?php 
	// here we check if we auto insert the summary gathered from the clipped API way above, search for clipped
	if($auto_summary_insert)
	{
		// here we check to see if the summary isn't blank, if it's not then we add it to the initaion content
		if($totalSummary <> '')
		{
	//		$totalSummary = mb_convert_encoding($totalSummary, "HTML-ENTITIES", 'UTF-8');
			// check to see if they don't want to use blockquote (taht's set in the admin panels
			if($dont_use_blockquote)
			{
	?>
				theInitialContent = theInitialContent + '<?php echo $totalSummary; ?>';			
	<?php
			}
			else
			{
	?>
				theInitialContent = theInitialContent + '<blockquote class="curated_content"><?php echo $totalSummary; ?></blockquote>';
				theInitialContent = theInitialContent.replace('&lt;', '<').replace('&gt;', '>');
		<?php
			}// dont use blockquote
		}
	}
?>

	if(domain_name == "youtube.com")
	{
		// do nothing
	}
	else
	{
		theInitialContent = theInitialContent.replace('&lt;', '<').replace('&gt;', '>');
		jQuery("#content").text(theInitialContent); 
	
	}
	jQuery("#curated_content_txtarea").val(theFirstParagrphText);


(function($) {


});
</script>
</html>
<?php
do_action('admin_footer');
do_action('admin_print_footer_scripts');
?>