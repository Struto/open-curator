<?php 
	global $curate_bm_image_align,$TwitterUsersFound,$dont_find_twitter, $totalUsersFound, $isRepull, $Paragraphs, $selection, $finalTotalImages, $auto_image_attribution, $ItFound, $url, $meta_desc, $SUPPORT_FILE_URL, $SUPPORT_FILE_DIR,$totalSummary,$h2_combined_s,$h3_combined_s,$h4_combined_s,$h5_combined_s,$strong_combined_s,$b_combined_s;
//echo	$SUPPORT_FILE_URL;
?>
<div id="normal-sortables" class="meta-box-sortables">
	<div id="my_all_meta" class="postbox" style="clear: both;">
        <h3 class='hndle'><span>Curated Content</span></h3>
        
        <div id="error_box"></div>
		<div class="inside">
	<?php if(!$dont_find_twitter) { ?>
        <div class="user_list">

            <?php if($totalUsersFound > 0) { ?><div style="width: 100px;float: left; padding: 0 0 3px 10px;"><p style="line-height: 10px; margin: 0; font-size: 12px;"><i class="fa fa-twitter fa-lg" style="color: #4099FF;"></i> Twitter Users</p><span style="font-size:9px; font-weight:bold;">check to remove</span></div><?php } ?>
            <div style="float: left; padding-top: 4px;">
            <?php     
					$i=0;
		            foreach($TwitterUsersFound as $value) { 
				?>
             <input type="checkbox" id="chbx-<?php echo $value; ?>" onclick="removeTwitterFromPublish('<?php echo $value; ?>');" style="width: 12px; margin-right: 5px;"><a href="javascript:;" rel="<?php echo $SUPPORT_FILE_URL; ?>/twitter-user-info.php?twitterUserName=<?php echo $value; ?>&supportURL=<?php echo urlencode($SUPPORT_FILE_URL); ?>" class="twitter_name">@<?php echo $value; ?></a>
            <?php 
				$i++;
				} 
			?>
            <?php if($totalUsersFound > 0) { ?>:
            <span style="font-size:9px; font-weight:bold;"><a href="javascript:;" onclick="removeAllTwitterUsers();" id="twitter_all_link">Remove All</a></span><?php } ?>
            </div>
       </div>
	<?php } ?>
            <div class="my_meta_control" style="clear: both; padding-top: 5px;">
                <div style="padding-bottom: 12px; display: inline; float:left; padding-right: 35px;">
                    <em>Select Paragraph:</em><label style="display:inline;"><input type="checkbox" id="add_to_chbx" onclick=""> Add to...</label>
                    <?php 
                    function makeNiceDisplay($inString)
                    {
                        //$curParagraph = iconv('UTF-8', 'ASCII//TRANSLIT', $curParagraph);
                        $inString = str_replace('\n','&#10;',$inString);
                        $inString = str_replace('"','',$inString);
                        return $inString;
                    }
                    
                    $i = 1;
                    $allText = '';
                    while ($i <= count($Paragraphs)): 
                        $curParagraph = $Paragraphs[$i-1];
                        //$curParagraph = iconv('UTF-8', 'ASCII//TRANSLIT', $curParagraph);
                        $curParagraph = (stripslashes($curParagraph));
            
                    ?>
                        <a href="javascript:;" class="button_blue p<?php echo $i; ?> change" rel="<?php echo $i; ?>" title="<?php echo $curParagraph; ?>"><?php echo $i; ?></a>         
                    <?php 
                        $allText .= $curParagraph;
                        $i++;
                    endwhile; 
            
                    $trans = array("\n" => "&#10;", "\"" => "");
                    ?>
                    <?php if ( $selection != '' ) { ?><a href="javascript:;" onClick="changeCurateText(99);" class="button_blue" title="<?php echo $selection; ?>">Add Selection</a> | <?php } ?>
                    <!--<a href="javascript:;" onClick="changeCurateText(100);" class="button_blue" title="<?php echo $allText; ?>">Add All</a> | -->
                    <?php if ( $strong_combined_s != '' ) { ?> | <a href="javascript:;" class="button_blue strongContent" title="<?php echo makeNiceDisplay($strong_combined_s); ?>"><i class="fa fa-bold"> 1</i></a><?php } ?>
                    <?php if ( $b_combined_s != '' ) { ?><a href="javascript:;" class="button_blue boldContent" title="<?php echo makeNiceDisplay($b_combined_s); ?>"><i class="fa fa-bold"> 2</i></a><?php } ?> | 
                    <?php if ( $h2_combined_s != '' ) { ?><a href="javascript:;" class="button_blue h2" title="<?php echo makeNiceDisplay($h2_combined_s); ?>"><i class="fa fa-list-ul"> 1</i></a><?php } ?>
                    <?php if ( $h3_combined_s != '' ) { ?><a href="javascript:;" class="button_blue h3" title="<?php echo makeNiceDisplay($h3_combined_s); ?>"><i class="fa fa-list-ul"> 2</i></a><?php } ?>
                    <?php if ( $h4_combined_s != '' ) { ?><a href="javascript:;" class="button_blue h4" title="<?php echo makeNiceDisplay($h4_combined_s); ?>"><i class="fa fa-list-ul"> 3</i></a><?php } ?>
                    <?php if ( $h5_combined_s != '' ) { ?><a href="javascript:;" class="button_blue h5" title="<?php echo makeNiceDisplay($h5_combined_s); ?>"><i class="fa fa-list-ul"> 4</i></a><?php } ?>
                    <?php if ( $meta_desc != '' ) { ?> | <a href="javascript:;" class="button_blue meta" title="<?php echo $meta_desc; ?>">Meta</a><?php } ?>
                    <?php if ( $totalSummary != '' ) { ?> <a href="javascript:;" class="ClippedSummary" title="<?php echo $totalSummary; ?>">
                    	<img src="<?php echo $SUPPORT_FILE_URL; ?>/i/clipped-icon.png" style="padding: 7px 0 0 5px; vertical-align:middle;" /></a><?php } ?>
                     | <a href="javascript:;" rel="1001" class="button_red_curate clear_staging"><i class="fa fa-eraser"></i> Clear</a>
            </div>
   	<div style="padding: 4px; float:left; width: 150px; display:inline;"><a href="javascript:;" class="green_button" id="add_to_post_box" title=""><i class="fa fa-plus-square-o fa-lg"></i> &nbsp;Add to Post</a></div>
		<textarea name="_my_meta[curated_content]" id="curated_content_txtarea" rows="6"><?php echo $first_paragraph_normal ?></textarea>

<!--<div id="showing_p">showing paragraph(s): <div id="the_paragraphs" style="display: inline; font-weight:bold;"></div></div>-->

    


<div style="visibility:hidden; display:none;">
    <div id="curate_images" class="curate_images">
    		<div id="waiting" style="display: none"><img src="<?php echo $SUPPORT_FILE_URL; ?>/i/ajax-loader.gif" alt="" /> <?php esc_html_e( 'Loading...' ); ?></div>
        <IMG SRC="<?php echo $firstImageURL; ?>" NAME="VCRImage" id="VCRImage" style="width: 150px; height: auto;">
    </div>

</div>


<div style="clear: both;"></div>
<?php 
$theDomainName = getDomainNameCurationTraffic($url);
?>
	<label>Image Credit Text<span> (optional)</span> 
    <span style="font-style:normal;">
    <a href="javascript:;" rel="Image" class="button_blue image_attribution" title="">Image</a> | 
    <a href="javascript:;" rel="Photo" class="button_blue image_attribution" title="">Photo</a> | 
    <a href="javascript:;" rel=" courtesy of " class="button_blue image_attribution" title="">courtesy of</a> | 
    <a href="javascript:;" rel=" by " class="button_blue image_attribution" title="">by</a> | 
    <a href="javascript:;" rel=" Credit" class="button_blue image_attribution" title="">Credit</a> | 
    <a href="javascript:;" rel="<?php echo $theDomainName; ?>" class="button_blue image_attribution" title=""><?php echo $theDomainName; ?></a> | 
    <a href="javascript:;" rel="clear" class="button_red_curate image_attribution"><i class="fa fa-eraser"></i> Clear</a>
<!--     <a href="javascript:;" class="green_button" id="create_caption" title="">Create Caption</a> |
       <a href="javascript:;" class="green_button" id="add_attribute_image" title="">Add link attribution</a>-->    
    </span></label>
     <?php 
	 $imageAttributionText = '';
	 if($auto_image_attribution)
	 {
	 	 $imageAttributionText = 'Image courtesy of '. $theDomainName;
	 }
	  ?>
	<p>
		<input type="text" name="_my_meta[image_credit_text]" id="image_credit_text" value="<?php echo $imageAttributionText; ?>"/>
		<span>Enter the image credit text.</span>
	</p>
    
	<label>Image Credit Link<span> (optional, if you include a link you must enter Image Credit Text above)</span></label>
	<p>
		<input type="text" name="_my_meta[image_credit_link]" value=""/>
		<span>Enter the image credit link.</span>
	</p>
    
	<label>Curated Link from: <em style="font-weight: normal;"><label id="curate_domain" style="display:inline;"></label></em></label>
	<p>
		<input type="text" name="_my_meta[curated_link]" value="<?php echo $url ?>"/>
		<span>This is your curated URL... you can modify this if it is not the correct URL.</span>
	</p>
    <p>
        <hr />
	</p>
	<label>Image URL <span>(optional)</span></label>
	<p>
		<input type="text" name="_my_meta[curated_image_url]" value="<?php echo $firstImageURL; ?>" id="txtbox_img_url" />
		<span>This is the URL from the image shown above. Or you can enter your own image URL.</span>
	</p>
    <p>
        <hr />
	</p>
    <p><a href="<?php echo $_SERVER['REQUEST_URI'] ?>&repull=true" class="button_red_curate" id="repull_link">Repull the Curation Link</a> (You typically want to do this if something went wrong)</p>
    <p id="repull_link_w"><label><input type="checkbox" id="chbx_no_image" onclick="addRepullValue('&verifyImageExtension=false')"> Ignore image extension check</label>
	   <label><input type="checkbox" id="chbx_no_image" onclick="addRepullValue('&checkForBadImageURLS=false')"> Ignore bad image check</label>
	   <label><input type="checkbox" id="chbx_no_image" onclick="addRepullValue('&totalParagraphsToCheck=7')"> Limit paragraph check to 7</label>
    </p>
    <p>
        <hr />
	</p>
</div>
</div>

<p style="padding-left: 10px; font-size:9px;"><?php echo $ItFound; ?></p>
</div>
</div>
