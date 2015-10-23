<?php
// check if the plugin is licensed
if (ct_plugin_validate_license()!==true) 
{ 
	die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
}
$curate_this_url = admin_url('curate-this.php');
// set the default width and height, used in form and when called in the function below.
$curate_this_width = "1250";
$curate_this_height = "820";
function get_curation_link($curate_this_width, $curate_this_height) {
	
	$url = plugins_url() . '/curation_traffic_plugin';
	
	//changed this line for 404 errors
	//u=f+'?u='+e(l.href)+'&t='+e(d.title)+'&s='+e(s)+'&v=4';
	$link = "javascript:
			var d=document,
			w=window,
			e=w.getSelection,
			k=d.getSelection,
			x=d.selection,
			s=(e?e():(k)?k():(x?x.createRange().text:0)),
			f='" . ($url . '/curate_this/curate-this.php') . "',
			l=d.location,
			e=encodeURIComponent,
			u=f+'?u='+e(l.href.replace(/\//g,'\\\/'))+'&t='+e(d.title)+'&s='+e(s)+'&v=4';
			a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=".$curate_this_width.",height=".$curate_this_height."'))l.href=u;};
			if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();
			void(0)";
	$link = str_replace(array("\r", "\n", "\t"),  '', $link);
	return apply_filters('curation_link', $link);
	
//javascript:var d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f='http://localhost/plugin_dev/wp-admin/curate-this.php',l=d.location,e=encodeURIComponent,u=f+'?u='+e(l.href.replace(/\//g,'\\/'))+'&t='+e(d.title)+'&s='+e(s)+'&v=4';a=function(){if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=1100,height=720'))l.href=u;};if (/Firefox/.test(navigator.userAgent)) setTimeout(a, 0); else a();void(0)" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;
}

		if($_POST['oscimp_hidden'] == 'Y') {
			//Form data sent

			$close_curate_window = $_POST['close_curate_window'];
			update_option('close_curate_window', $close_curate_window);

			$auto_hootsuite = $_POST['auto_hootsuite'];
			update_option('auto_hootsuite', $auto_hootsuite);
			
			$auto_summary_insert = $_POST['auto_summary_insert'];
			update_option('auto_summary_insert', $auto_summary_insert);

			$dont_use_blockquote = $_POST['dont_use_blockquote'];
			update_option('dont_use_blockquote', $dont_use_blockquote);

			$dont_find_twitter = $_POST['dont_find_twitter'];
			update_option('dont_find_twitter', $dont_find_twitter);
			
			$check_image_sizes = $_POST['check_image_sizes'];
			update_option('check_image_sizes', $check_image_sizes);

			$bit_ly_username = $_POST['bit_ly_username'];
			update_option('bit_ly_username', $bit_ly_username);

			$bit_ly_api_key = $_POST['bit_ly_api_key'];
			update_option('bit_ly_api_key', $bit_ly_api_key);
			
			$curate_bm_image_align = $_POST['curate_bm_image_align'];
			update_option('curate_bm_image_align', $curate_bm_image_align);

			$max_twitter_users = $_POST['max_twitter_users'];
			update_option('max_twitter_users', $max_twitter_users);

			// this is the featured image handling
			$image_handling_option = $_POST['image_handling_option'];
			update_option('image_handling_option', $image_handling_option);
			
			// this controls the image alignment
			$image_behavior = $_POST['image_behavior'];
			update_option('image_behavior', $image_behavior);
			
			$curation_link_text = $_POST['curation_link_text'];
			update_option('curation_link_text', $curation_link_text);

			$content_display_order = $_POST['content_display_order'];
			update_option('content_display_order', $content_display_order);
			
			$remove_allow_url_errors = $_POST['remove_allow_url_errors'];
			update_option('remove_allow_url_errors', $remove_allow_url_errors);
			
			$turn_off_clipped_api = $_POST['turn_off_clipped_api'];
			update_option('turn_off_clipped_api', $turn_off_clipped_api);

			$auto_image_attribution = $_POST['auto_image_attribution'];
			update_option('auto_image_attribution', $auto_image_attribution);

			
			//$youtube_script = $_POST['youtube_script'];
			//update_option('youtube_script', $youtube_script);
			
			$inline_cta = $_POST['inline_cta'];
			$inline_cta = stripslashes($inline_cta);
			update_option('inline_cta', $inline_cta);

			?>
			<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
			<?php
		} else {
			//Normal page display
			$close_curate_window = get_option('close_curate_window');
			$auto_hootsuite = get_option('auto_hootsuite');
			$auto_summary_insert = get_option('auto_summary_insert');
			$dont_use_blockquote = get_option('dont_use_blockquote');
			$dont_find_twitter = get_option('dont_find_twitter');

			$image_handling_option = get_option('image_handling_option');
		
			$check_image_sizes = get_option('check_image_sizes');
			$image_behavior = get_option('image_behavior');
			$bit_ly_username = get_option('bit_ly_username');
			$bit_ly_api_key = get_option('bit_ly_api_key');
			$curate_bm_image_align = get_option('curate_bm_image_align');
			$max_twitter_users = get_option('max_twitter_users');
			$content_display_order = get_option('content_display_order');
			$curation_link_text = get_option('curation_link_text');
			$auto_image_attribution = get_option('auto_image_attribution');
			$inline_cta = get_option('inline_cta');
			$remove_allow_url_errors = get_option('remove_allow_url_errors');
			$turn_off_clipped_api = get_option('turn_off_clipped_api');
			
			
			//$youtube_script = get_option('youtube_script');
		}
		
		if($_GET['manualCurateThisInstall'] == "yes")
		{
			$dbVersion = get_option('curate-this-version');
			if($dbVersion != '')
				update_option('curate-this-version',CURATETHIS_VERSION);	
			else
				add_option('curate-this-version',CURATETHIS_VERSION);
				
			$returnMessage .= '<p>CurateThis Manual Install Complete (note: this does not copy the file).</p>';
		}
	?>
	

<style type="text/css">
.main_heading {border-bottom: 1px solid #ccc; margin: 0 auto; padding: 0; overflow: auto; clear: both;}
.main_heading h2 {float:left;}
.section h3.heading {
    border-bottom: 1px solid #E7E7E7;
    margin: 10px 0;
    padding: 7px 0;
    display: block;
    font-weight: bold;
    font-size: 1.17em;
}
.section .controls {
    float: left;
    margin: 0 15px 0 0;
    width: 170px;
}
.section-checkbox .controls {
    width: 25px;
}
.controls textarea {
    -moz-border-bottom-colors: none;
    -moz-border-image: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #F1F1F1;
    border-color: #CCCCCC #E6E6E6 #E6E6E6 #CCCCCC;
    border-style: solid;
    border-width: 1px;
    font-family: "Lucida Grande","Lucida Sans Unicode",Arial,Verdana,sans-serif;
    font-size: 12px;
    margin-bottom: 9px !important;
    padding: 4px;
    width: 270px;
	border-radius: 4px;
}
.section .explain {
    color: #666666;
    float: left;
    font-size: 11px;
    padding: 0 10px 0 0;
    width: 225px;
}
.section .explain_large { width: 320px;}
.section .auto_summary { width: 85%;}
.section .nbquote  {width: 250px;}

.controls select {padding: 0 0 0 4px; width: 165px;}
#image_handling_option { width: 386px;}
.left-side { width: 420px; float: left; clear: both;}
.right-side { width: 410px; float: left; margin: 0 0 0 30px; padding-left: 30px; border-left: 1px solid #CCC;}
.right-side-top { width: 300px; float: left; margin: 0 0 0 30px; padding-left: 30px; text-align: right; }
.right-side-top .submit {  padding: .5em 0;}
.bottom_wrapper{padding-left: 30px;}
#how_this_works {display: none;}
.how_works_text {margin: 0 15px; font-size: 13px;}
.how_works_text li { padding: 7px 0;}
.full {width: 100% !important;}
</style>
<script>
jQuery(document).ready(function($){
	
	$(".show_how_works").click(function() {

			if($("#how_this_works").css('display') == 'block')
				$("#how_this_works").css({"display":"none","visibility":"hidden"});		
			else
				$("#how_this_works").css({"display":"block","visibility":"visible"});
	})
});
</script>
<div class="wrap">

<form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<div class="main_heading">
<?php    echo "<h2>" . __( '<i class="fa fa-cogs fa-lg"></i> Curation Traffic Plugin Admin', 'ct_plugin' ) . "</h2>"; ?>
<p class="support_request_w"><a href="<?php echo YBI_SUPPORT_URL; ?>" target="_blank" class="green_button green_button_support"><i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;Create Support Request</a></p>
</div>
    <input type="hidden" name="oscimp_hidden" value="Y">

<div class="bottom_wrapper">
<div class="left-side">
<div class="section section-checkbox">
    <h3 class="heading">Close Curation Window</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="close_curate_window" id="close_curate_window" value="1" <?php checked($close_curate_window, 1); ?> />
        </div>
        <div class="explain explain_large">Select this if you want to automatically close the curation window after you publish.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section section-checkbox">
    <h3 class="heading">Automatically Redirect to Hootsuite Share</h3>
    <div class="option">
        <div class="controls">
		    <input type="checkbox" class="checkbox of-input" name="auto_hootsuite" id="auto_hootsuite" value="1" <?php checked($auto_hootsuite, 1); ?> />
        </div>
        <div class="explain explain_large">Select this if you want to automatically go to the Hootsuite share.</div>
        <div class="clear"> </div>
    </div>
</div>





<div class="section">
    <h3 class="heading">Curated Image Options (Featured Image)</h3>
    <div class="option">
        <div class="controls">
            <select class="of-input" name="image_handling_option" id="image_handling_option">
                <option value="keep_image_in_postbox" <?php selected($image_handling_option, keep_image_in_postbox); ?> />Image in Post Box (default) </option>
                <option value="upload_to_feature_delete_postbox" <?php selected($image_handling_option, upload_to_feature_delete_postbox); ?> />Auto Upload, Set as Featured, Delete from Post Box</option>
                <option value="upload_to_feature_keep_postbox" <?php selected($image_handling_option, upload_to_feature_keep_postbox); ?> />Auto Upload, Set as Featured, Keep Image in Post Box </option>
            </select>
        </div>

        <div class="clear"> </div>
		<p><strong><a href="javascript:;" name="htaccess_backup_files" class="show_how_works">Here's How This Works <i class="fa fa-caret-square-o-down fa-lg"></i></a></strong></p>
        <div id="how_this_works">
        <ul class="how_works_text">
            <li><strong>Image in Post Box (default)</strong>: The image you select will remain in the post box.</li>
            <li><strong>Auto Upload, Set as Featured, Delete from Post Box</strong>: The image you have chosen in the post box will be automatically uploaded to your site (when you hit submit) 
            and set as that posts featured image. <strong>The image in the post box will then be deleted from the postbox.</strong>.</li>
            <li><strong>Auto Upload, Set as Featured, Keep Image in Post Box</strong>: Some themes only use the featured image on main pages, for the single post pages they only show images in the post box. This setting
            is just like the one above except it <strong>does not remove</strong> the image from the post box.</li>
        </ul>
        </div>
    </div>
</div>


<div class="section section-checkbox">
    <h3 class="heading">Check Sizes of Images Found</h3>
    <div class="option">
        <div class="controls">
			<input type="checkbox" class="checkbox of-input" name="check_image_sizes" id="check_image_sizes" value="1" <?php checked($check_image_sizes, 1); ?> />
        </div>
        <div class="explain explain_large">Select this if you want to check the sizes of images that Curate This finds, this generally gives you better results for your images. The downside is it will take Curate This longer to load or timeout on pages that have tons (75-100+) of images.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section">
    <h3 class="heading">Default Image Alignment</h3>
    <div class="option">
        <div class="controls">
            <select class="of-input" name="curate_bm_image_align" id="curate_bm_image_align">
                <option value="alignleft" <?php selected($curate_bm_image_align, alignleft); ?> />Left </option>
                <option value="aligncenter" <?php selected($curate_bm_image_align, aligncenter); ?> />Center </option>
                <option value="alignright" <?php selected($curate_bm_image_align, alignright); ?> />Right </option>
            </select>
        </div>
        <div class="explain">Select your default image aligment selected in the curation window (you can change this for each individual post).</div>
        <div class="clear"> </div>
    </div>
</div>



<!--<div class="section">
    <h3 class="heading">Image Sizing and Behavior</h3>
    <div class="option">
        <div class="controls">
            <select id="image_behavior" class="of-input" name="image_behavior">
                <option value="full_size" <?php selected($image_behavior, full_size); ?>>Full size with text wrap</option>
                <option value="always_clear" <?php selected($image_behavior, always_clear); ?>>Full size and always have text clear image</option>
                <option value="width_120" <?php selected($image_behavior, width_120); ?>>120 - Thumbnail 120 pixels wide with text wrap</option>
                <option value="width_300" <?php selected($image_behavior, width_300); ?>>300 - Thumbnail no bigger than 300 pixels wide with text wrap</option>
                <option value="width_500" <?php selected($image_behavior, width_500); ?>>500 - Thumbnail no bigger than 500 pixels wide with text wrap</option>
            </select>
        </div>
        <div class="explain">Select how you want your images to be displayed and behave. (default: Full size with text wrap). This effects all pages.</div>
        <div class="clear"> </div>
    </div>
</div>-->

<div class="section section-checkbox">
    <h3 class="heading">Stop Finding Twitter Usernames</h3>
    <div class="option">
        <div class="controls">
			<input type="checkbox" class="checkbox of-input" name="dont_find_twitter" id="dont_find_twitter" value="1" <?php checked($dont_find_twitter, 1); ?> />
        </div>
        <div class="explain explain_large">Select this if you don't want the bookmarklet to automatically find and add Twitter users.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section">
    <h3 class="heading">Max Number of Twitter Users to Find</h3>
    <div class="option">
        <div class="controls">
            <select class="of-input" name="max_twitter_users" id="max_twitter_users">
            <?php for ($i = 1; $i <= 19; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php selected($max_twitter_users, $i); ?> /><?php echo $i; ?> </option>
            <?php } ?>
            </select>
        </div>
        <div class="explain">Select how many Twitter users you want found, for some pages that use social comment plugins the bookmarklet will find every Twitter account for anybody that comments. (default: we cap this at 5)</div>
        <div class="clear"> </div>
    </div>
</div>


<div class="section section-checkbox">
    <h3 class="heading">Remove allow_url_* Errors from Admin</h3>
    <div class="option">
        <div class="controls">
			<input type="checkbox" class="checkbox of-input" name="remove_allow_url_errors" id="remove_allow_url_errors" value="1" <?php checked($remove_allow_url_errors, 1); ?> />
        </div>
        <div class="explain explain_large">Click here to remove the Curation Traffic Needs Attention Error. For some sites this might be a false error even when these settings are correct.</div>
        <div class="clear"> </div>
    </div>
</div>

	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'ct_trdom' ) ?>" class="button button-primary button-large" />
	</p>


</div>
<div class="right-side">
    <p class="submit"><input type="submit" name="Submit" value="<?php _e('Update Options', 'ct_trdom' ) ?>" class="button button-primary button-large" /></p>
<p>Curation Traffic Plugin by <a href="http://www.youbrandinc.com" title="You Brand, Inc." target="_blank">You Brand, Inc.</a></p>
<p>For support visit... <a href="<?php echo YBI_SUPPORT_URL; ?>" title="Curation Traffic support page" target="_blank"><?php echo YBI_SUPPORT_URL; ?></a></p>


<div style="padding-bottom: 10px;">
            <div>

<?php 
$filename = 'curate-this.php';
$showInstallLink = false;
$installMessage = '';

		if($installedVersion == $themeVersion)
		{
			echo '<span style="padding-left: 5px; font-size: 10px;">Curate This ' . $installedVersion . '</span>';
	?>
	</div>
<img src="<?php echo plugins_url(); ?>/curation_traffic_plugin/curate_this/i/bookmarklet-background.png" />
<p class="pressthis-bookmarklet-wrapper" style="padding-left: 15px; clear:both;margin-top: -78px;">
    <a onclick="return false;" oncontextmenu="if(window.navigator.userAgent.indexOf('WebKit')!=-1||window.navigator.userAgent.indexOf('MSIE')!=-1)jQuery('.pressthis-code').show().find('textarea').focus().select();return false;" href="<?php echo htmlspecialchars( get_curation_link($curate_this_width, $curate_this_height) ); ?>" id="bookmarklet_link" class="pressthis-bookmarklet"><span class="curate_bookmarklet" style="margin-left: 10px;"><?php _e('Curate') ?></span></a></p>
<div style="clear: both; margin: 0 auto; overflow:auto; padding-top: 18px;">
<h3 class="heading" style="font-size: .9em;">Set Size of Popup Window</h3>
<label>Width:</label>
<input type="text" name="width" value="<?php echo $curate_this_width; ?>" size="6" id="curateThisWidth">
<label>Height:</label>
<input type="text" name="height" value="<?php echo $curate_this_height; ?>" size="6" id="curateThisHeight">
<a href="javascript:;" id="setCurateThisWindowSize"><i class="fa fa-cog fa-lg"></i> Set Size</a>
<p>To change the size of the CurateThis popup window <strong>enter the pixel sizes (numbers only)</strong> above. <strong>Click <em>Set Size</em></strong> and drag Curate to your bookmarks bar.</p>
<script>
//curate_this_url
jQuery(document).ready(function($){
   $("#setCurateThisWindowSize").click(function(event){
		var curateThisLink = jQuery('#bookmarklet_link').attr('href');
		var curateThisWidth = $("#curateThisWidth").val()
		var curateThisHeight = $("#curateThisHeight").val()
//		curateThisLink = curateThisLink.replace('width=1100', 'width=300');
		curateThisLink = curateThisLink.replace(/width=(.+?),/, 'width='+curateThisWidth+',');
		curateThisLink = curateThisLink.replace(/height=(.+?)'/, 'height='+curateThisHeight+'\'');
		jQuery('#bookmarklet_link').attr('href', curateThisLink);

   });
 });
</script>

</div>
<?php
	
}
else
{
	$showInstallLink = true;
	$installMessage = 'Step 1 - Click here to install the Curate This bookmarklet';
}
?>

<?php if($showInstallLink) { ?>
<p style="padding-left: 15px; clear:both;"><a href="<?php echo plugins_url() . '/curation_traffic_plugin/' ?>copy-curation.php?fromURL=<?php echo getTheCurPageURL(); ?>" style="color: #F00; font-weight:bold;"><?php echo $installMessage; ?></a></p>
 	  </div>
<?php } ?>

    	  </div>

<!--<div class="section">
    <h3 class="heading">Inline Call to Action</h3>
    <div class="option">
        <div class="controls">
            <textarea id="inline_cta" class="of-input" rows="8" cols="8" name="inline_cta"><?php echo stripslashes($inline_cta); ?></textarea>
        </div>
        <div class="explain">Enter inline call to action code below. This shows up below your curated content and your commentary.</div>
        <div class="clear"> </div>
    </div>
</div>-->

<div class="section">
    <h3 class="heading">Curation Link Text</h3>
    <div class="option">
        <div class="controls">
		    <input type="text" name="curation_link_text" value="<?php echo $curation_link_text; ?>" size="50">
        </div>
        <div class="explain full">Enter customized text you want to be put before each curated link attribution. If you don't enter anything here it will default to <strong>See full story on DomainName.com</strong>. Note: Only the domainname.com will be linked.</div>
        <div class="clear"> </div>
    </div>
</div>



<div class="section">
    <h3 class="heading">Bit.Ly Information</h3>
    <div class="option">
        <div class="controls">
        <label>Username:</label>
		    <input type="text" name="bit_ly_username" value="<?php echo $bit_ly_username; ?>" size="50">
        </div>
        <div class="explain">Enter you bit.ly username and API key</div>
        <div class="controls">
        <label style="padding-top: 10px;">API Key:</label>
			<input type="text" name="bit_ly_api_key" value="<?php echo $bit_ly_api_key; ?>" size="50">
        </div>
        <div class="explain"></div>
        <div class="clear"> </div>
    </div>
</div>


<div class="section section-checkbox">
    <h3 class="heading">Turn Off Auto Summary Feature</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="turn_off_clipped_api" id="turn_off_clipped_api" value="1" <?php checked($turn_off_clipped_api, 1); ?> />
        </div>
        <div class="explain explain_large turn_off_clipped_api nobquote">Select this if you want turn off the auto summary feature in CurateThis. <em>Note: this means an auto summary will not be created and visible for any curations.</em></div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section section-checkbox">
    <h3 class="heading">Auto Summary Insertion</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="auto_summary_insert" id="auto_summary_insert" value="1" <?php checked($auto_summary_insert, 1); ?> />
        </div>
        <div class="explain explain_large auto_summary">Select this if you want to automatically place the summary text within the post box. Please note this only happens if it exists, if the summary cannot be gathered then only the image will show.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section section-checkbox">
    <h3 class="heading">Don't Wrap Text in Blockquote</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="dont_use_blockquote" id="dont_use_blockquote" value="1" <?php checked($dont_use_blockquote, 1); ?> />
        </div>
        <div class="explain explain_large auto_summary nobquote">Select this if you want <strong>Do NOT</strong> want to automatically wrap your curated content in the blockquote tag. Note: if you're unsure of what this is then keep this option off.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section section-checkbox">
    <h3 class="heading">Auto Image Attribution Text</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="auto_image_attribution" id="auto_image_attribution" value="1" <?php checked($auto_image_attribution, 1); ?> />
        </div>
        <div class="explain explain_large auto_summary nobquote">Select this if you want to automatically include an image attribution text block. The attribution text will say: <em>Image courtesy of CuratedDomainName.com</em>.</div>
        <div class="clear"> </div>
    </div>
</div>

<div class="section section-checkbox">
    <h3 class="heading">Manual CurateThis Installation Confirmation</h3>
    <div class="option">
        <div class="controls">
        </div>
        <div class="explain explain_large auto_summary nobquote">
        <strong>Only click this if you followed the manaul install process</strong><br />
		<a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&manualCurateThisInstall=yes">Click to Confirm Manual CurateThis</a><br />
        Clicking this will manually confirm the installation of the CurateThis file to you wp-admin folder. Once you click this link you should see the Curate shortcut above and it should not prompt you to install.</div>
        <div class="clear"> </div>
    </div>
</div>




<!--<div class="section section-checkbox">
    <h3 class="heading">Add Auto YouTube Resize Script</h3>
    <div class="option">
        <div class="controls">
            <input type="checkbox" class="checkbox of-input" name="youtube_script" id="youtube_script" value="1" <?php checked($youtube_script, 1); ?> />
        </div>
        <div class="explain explain_large">Select this feature if you want YouTube videos to scale automatically.</div>
        <div class="clear"> </div>
    </div>
</div>-->


</div>
</form>
</div>
<br style="clear: both;">
</div>