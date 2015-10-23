<?php
/*
 Heres how this page works:
 - the display in the URL controls the current view that the user sees.
 - CTA = Call to Action or a single record from the ULTIMATE_CTA_TABLE and CTAs would be plural
 - currently these views: CTA list page, CTA Add/Edit Page
*/
// check to see if the plugin is licensed
if (ucta_validate_license()!==true) 
{ 
	die(header('Location: '.self_admin_url('admin.php?page=youbrandinc-license')));
}
	global $wpdb;
	$current_view = 'display';
	$buttonText = "Add";
	if (isset($_GET['c_view'])) {
		$current_view = $_GET['c_view'];
	}

	// action is the drop down above the list, Turn On, Turn Off, and Delete
	if($_POST['action_hidden'] == 'Y') 
	{
		// get the current action	
		$current_action = $_POST['action_dd'];
		// get the array of the selected CTAs
		$cta_ids = $_POST["cta_ids"];
		//var_dump($cta_ids);
		if(!empty($cta_ids))
		{
			
			if($current_action == 'turn_on') // do actions for turning ON a CTAs
			{
				foreach ($cta_ids as $cta_id)
				{
					$wpdb->update(ULTIMATE_CTA_TABLE, array('is_active' => 1), array('ID' => $cta_id));	
				}
			}
			if($current_action == 'turn_off') // do actions for turning OFF a CTAs
			{
				foreach ($cta_ids as $cta_id)
				{
					$wpdb->update(ULTIMATE_CTA_TABLE,array( 'is_active' => 0), array('ID' => $cta_id));	
				}
			}
			if($current_action == 'delete') // do actions for DELETING a CTAs
			{
					foreach ($cta_ids as $cta_id)
					{
						$wpdb->query("DELETE FROM ". ULTIMATE_CTA_TABLE . " WHERE id = " .$cta_id); // delete from the main table
						$wpdb->query("DELETE FROM ". ULTIMATE_CTA_CATEGORY_TABLE . " WHERE ultimate_cta_id = " .$cta_id); // delete from the category join table
					}
			}
		} //if(!empty($cta_ids))
		$current_view = 'display'; // since we are done with actions set view to display list
	}
	// Below is the main submits for the CTA Add/Edit Form
	if($_POST['submit_hidden'] == 'Y') 
	{
		//Form data sent, check to see if this exists, see below where if it is then it's an edit
		if (isset($_GET['ucta_id'])) {
			$ucta_id = $_GET['ucta_id'];
		}
		$ucta_name = $_POST['ucta_name'];
		$ucta_type = $_POST['ucta_type'];
		$ucta_insert_before = $_POST['ucta_insert_before'];
		$ucta_width = $_POST['ucta_width'];
		$ucta_categories = $_POST['ucta_categories'];
		$ucta_content = $_POST['ucta_content'];
		$show_categories = $_POST["show_categories"]; // this is an array of selected categories
		$totalSelectedCategories = count($show_categories); // get total of the selected categories
		$total_category_count = $_POST['total_category_count'];  // this is a hidden element within the form that tells us the total amount of categories, we use to know if the really selected all
		$is_all_selected = false;
		// if the categories selected by the user matches the total categories then we know they want all selected and didn't just use these buttons as shortcuts
		$is_all_selected = ($total_category_count == count($show_categories));
		if($ucta_id > 0)
		{
			// Because we found an CTA_ID in the url then we update cta
			$wpdb->update(ULTIMATE_CTA_TABLE , 
			array( 
				'name' => $ucta_name, 
				'type' => $ucta_type, 
				'insert_before' => $ucta_insert_before,
				'content' => $ucta_content,
				'width' => $ucta_width,
				'is_all' => $is_all_selected),
				array('ID' => $ucta_id));

			// let's delete the old joins as we are about to update that table with new category and CTA links
			$wpdb->query("DELETE FROM ". ULTIMATE_CTA_CATEGORY_TABLE  . " WHERE ultimate_cta_id = " .$ucta_id);

			// here's the only really diferent part, if the user selected ALL Categories as outlined above then we don't enter these into the join table. That logic is going to be in the main table (ULTIMATE_CTA_TABLE) as the is_all field
			if(!$is_all_selected)
			{
				// since the user didn't select them all then we join the CTA to the Category in the join table
				foreach ($show_categories as $category)
				{
					$wpdb->insert(ULTIMATE_CTA_CATEGORY_TABLE, array( 'ultimate_cta_id' => $ucta_id, 'category_id' => $category));
				}
			}
		}
		else
		{
			// since we didn't find a CTAID in the url then this is a new record
			$wpdb->insert(ULTIMATE_CTA_TABLE, 
			  array( 
			  'name' => $ucta_name, 
			  'type' => $ucta_type, 
			  'insert_before' => $ucta_insert_before,
			  'content' => $ucta_content, 
			  'width' => $ucta_width,
			  'is_all' => $is_all_selected,
			  'is_active' => 1
			  ));

			if(!$is_all_selected)
			{
				$lastid = $wpdb->insert_id;
				foreach ($show_categories as $category)
				{
					$wpdb->insert(ULTIMATE_CTA_CATEGORY_TABLE, array('ultimate_cta_id' => $lastid, 'category_id' => $category));
				}
			}
		// now that we are done with either updating or adding a record the current view should be set to display the list
		$current_view = 'display';
		}
?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php
	} //if($_POST['submit_hidden'] == 'Y') 
	else 
	{
		// so this isn't a update from an etnry but it might be an edit, if we find an ID then it must be an edit
		if (isset($_GET['ucta_id'])) {
			$ucta_id = $_GET['ucta_id'];
			$current_view = 'edit';
			$buttonText = "Update";
		}
		if($current_view == 'edit')
		{
			$cta=$wpdb->get_row("SELECT name, type, insert_before, content, width, id, is_all FROM " . ULTIMATE_CTA_TABLE  . ' where id = ' . $ucta_id);			
			// get all the values for the edit page
			$ucta_name = $cta->name;
			$ucta_type = $cta->type;
			$ucta_insert_before = $cta->insert_before;
			$ucta_width = $cta->width;
			$ucta_content = $cta->content;
			$is_all = $cta->is_all;
		}
	}
	
	// this function returns a readable name of the value we save and check in the databae for the type of CTA
	function getTypeFriendlyName($inTypeKey)
	{
		switch($inTypeKey) {
			case '':
				return 'nothing';
			case 'custom':
				return 'Custom Placement';
			case 'inline_above':
				return 'In-Line Above';
			case 'inline_below':
				return 'In-Line Below';
			case 'sidebar':
				return 'Sidebar';
		}
	}
	// return status based on if it's set or not
	function getStatusText($inStatus)
	{
		if($inStatus == 1)
			return "On";
		else
			return "Off";	
	}
	// because we tend to keep the ucta_id in the url this causes issues
	// for certain actions like Add New or DELETE we need a clean URL otherwise it will redirect the user to the edit section
	function getCleanURL()
	{
		$returnURL = '';
		// get url and replace bad chars
		$currentURL = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		
		// we replace any instance of the ucta_id reference in the url
		$returnURL = preg_replace('/&c_view=new/i', '', $currentURL);
		$returnURL = preg_replace('/&ucta_id=[0-9]+/i', '', $returnURL);
		return $returnURL;
	}
?>
<div class="wrap">
<div class="main_heading">
    <div class="left-side-top">
		<?php if($current_view == 'display') { ?>
	        <?php    echo "<h2>" . __( 'Ultimate Call to Action', 'ucta_trdom' ); ?> 
            <a class="add-new-h2" href="<?php echo getCleanURL(); ?>&c_view=new">Add New</a>
	        </h2>
        <?php } else { ?>
			<?php if ($current_view == 'edit') { ?>
                <h2 class="editing">Editing: <em><?php echo $ucta_name; ?></em></h2>
            <?php } else { ?>
                <h2 class="editing">Add New Call to Action</h2>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="right-side-top ">
        <p>Ultimate Call to Action Plugin by <a href="http://www.youbrandinc.com" title="You Brand, Inc." target="_blank">You Brand, Inc.</a></p>
        <p>For support visit... <a href="http://www.curationtraffic.com/support" title="Curation Traffic support page" target="_blank">http://www.CurationTraffic.com/support</a></p>
	</div>
</div>
<?php if($current_view == 'new' || $current_view == 'edit') { ?>
<form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="submit_hidden" value="Y">
<div class="left-side">
    <div class="section">
        <h3 class="heading">Call to Action Name</h3>
        <div class="option">
            <div class="controls">
                <input type="text" name="ucta_name" value="<?php echo $ucta_name; ?>" size="50">
            </div>
            <div class="explain">Enter the name for this Call to Action</div>
            <div class="clear"> </div>
        </div>
    </div>
    
    <div class="section">
        <h3 class="heading">Type of Call to Action</h3>
        <div class="option">
            <div class="controls">
                <select id="ucta_type" class="of-input" name="ucta_type">
                    <option value="inline_above" <?php selected($ucta_type, inline_above); ?>>Inline Above</option>
                    <option value="inline_below" <?php selected($ucta_type, inline_below); ?>>Inline Below</option>
                    <option value="sidebar" <?php selected($ucta_type, sidebar); ?>>Sidebar</option>
                    <option value="custom" <?php selected($ucta_type, custom); ?>>Custom Placement</option>
                </select>
            </div>
            <div class="explain">Enter the type of call to action.</div>
            <div class="clear"> </div>
        </div>
    </div>
    
    
    <div class="section" id="insert_before_div">
        <h3 class="heading">Insert Before Selector</h3>
        <div class="option">
            <div class="controls">
                <input type="text" name="ucta_insert_before" value="<?php echo $ucta_insert_before; ?>" size="50">
            </div>
            <div class="explain">Enter the element Class or ID that you want the call to action to show up before. <strong>IMPORTANT:</strong> If you are unsure of how to do this or make this work <a href="">click this link to watch a tutorial video</a>.</div>
            <div class="clear"> </div>
        </div>
    </div>
    
    
    <div class="section">
        <h3 class="heading">Call to Action Content</h3>
        <div class="option">
            <div class="controls">
                <textarea id="ucta_content" class="of-input" rows="8" cols="8" name="ucta_content"><?php echo stripslashes($ucta_content); ?></textarea>
            </div>
            <div class="explain">Enter inline call to action code below. This shows up below your curated content and your commentary.</div>
            <div class="clear"> </div>
        </div>
    </div>
    
    <div class="section">
        <h3 class="heading">Call to Action Width</h3>
        <div class="option">
            <div class="controls">
                <input type="text" name="ucta_width" value="<?php echo $ucta_width; ?>" size="50">
            </div>
            <div class="explain">Enter the width for this Call to Action (not required)</div>
            <div class="clear"> </div>
        </div>
    </div>
</div><!-- LEFT SIDE -->
<div class="right-side">
    <h3>Selected Categories</h3>
	<div class="add_remove_all">
        <a href="javascript:;" onclick="SetAllCheckBoxes('oscimp_form', 'show_categories[]', true);" id="twitter_all_link">Add All</a> / 
        <a href="javascript:;" onclick="SetAllCheckBoxes('oscimp_form', 'show_categories[]', false);" id="twitter_all_link">Remove All</a>
	</div>
<?php 
	// here we get all the selected categories
	if($current_view == 'edit')
	{
		$selected_categories = $wpdb->get_results("SELECT ".ULTIMATE_CTA_CATEGORY_TABLE.".category_id FROM ".ULTIMATE_CTA_CATEGORY_TABLE." WHERE ".ULTIMATE_CTA_CATEGORY_TABLE.".ultimate_cta_id = " . $ucta_id);
		// we take these results and put them into a single array so we can easily check down below if they are selected
		$single_array = array();
		foreach( $selected_categories as $result )
			$single_array[] = $result->category_id;
	}
	//echo $selected_categories;
	//var_dump($selected_categories);
	$isChecked = false;
	$totalListedCategories = 0;
	// start the list
	echo '<ul style="clear: both;" class="category_list">';
	//get all the categories even empty ones
	$categories = get_categories(array('hide_empty' => 0, 'orderby' => 'name', 'depth' => 1, 'hierarchical' => 1, 'parent' => ''));

	// loop through the high level categories and create check boxes
	foreach ($categories as $category )
	{
		// if the category has a parent then move on to the next one
		if ($category->parent > 0) { continue; }
		// this is incremented here and this get's put into a hidden value down below
		$totalListedCategories++;

		// if we are in edit mode then check to see if this category is checked
		$isChecked = false;
		if($current_view == 'edit')
		{
			if($is_all)
				$isChecked = true;
			else
				$isChecked = in_array($category->term_id, $single_array);
		}
	?>
	<li><label><input type="checkbox" class="checkbox of-input" name="show_categories[]" id="show_categories[]" value="<?php echo $category->term_id; ?>" <?php checked($isChecked); ?> /> <?php echo $category->cat_name; ?></label></li>
	<?php     
		$sub_categories = get_categories(array('hide_empty' => 0, 'child_of' => $category->term_id));
		foreach ($sub_categories as $sub_category) 
		{
			$addCSS = '';
			if($category->cat_ID == $sub_category->category_parent)
			{
				$addCSS = 'class="sub_cat"';
			}
			if($category->cat_ID != $sub_category->category_parent)
			{
				$addCSS = 'class="sub_sub_cat"';
			}	
			// this is incremented here and this get's put into a hidden value down below
			// NOTE: we do this in two places because this is a sub category or a sub sub category LOOP
			$totalListedCategories++;
	
			// if we are in edit mode then check to see if this category is checked
			if($current_view == 'edit')
			{
				if($is_all)
					$isChecked = true;
				else
					$isChecked = in_array($sub_category->term_id, $single_array);
			}
			?>
			<li <?php echo $addCSS;?>><label><input type="checkbox" class="checkbox of-input" name="show_categories[]" id="show_categories[]" value="<?php echo $sub_category->term_id; ?>" <?php checked($isChecked); ?> /> <?php echo $sub_category->cat_name; ?></label></li>
			<?php
		} //foreach ($categories as $category ) 
		?>
		<?php
		} // foreach ($categories as $category )
		echo '</ul>';
		// this value is here because we check against the amount of selected values from the user, if this and the selected match then we set the CTA to is_all
		echo '<input type="hidden" name="total_category_count" value="' . $totalListedCategories . '">';
		?>
	<p class="submit">
	<input type="submit" name="Submit" value="<?php echo $buttonText; ?> Call to Action" id="btnsave" />
	</p>
</div>
</form>
<?php } // if($current_view == 'new') ?>

<br style="clear: both;">
<?php if($current_view == 'display') { ?>
<form name="change_action_form" id="change_action_form" method="post" action="<?php echo getCleanURL(); ?>">
<input type="hidden" name="action_hidden" value="Y">
<div id="action_w">
<select id="action_dd" class="of-input" name="action_dd" style="width: 100px;">
    <option value="turn_on">Turn On</option>
    <option value="turn_off">Turn Off</option>
    <option value="delete">Delete</option>
</select>
<input id="doaction" class="button-secondary action" type="submit" value="Apply" name="">
</div>
<div class="list_w">
    <div class="row title-row">
        <div class="column name title">
        <input type="checkbox" class="checkbox of-input" name="select_all_rows" id="select_all_rows" value="select_all_rows">
        Name</div>
        <div class="column type title">Type</div>
        <div class="column status title">Status</div>
        <!--<div class="column width_column title">Width</div>-->
        <div class="column categories title">Categories</div>
    </div>
<?php 
	// get all the CTA's
	$ctas=$wpdb->get_results("SELECT id, name, type, insert_before, content, width, is_all, is_active FROM " . ULTIMATE_CTA_TABLE . ' ORDER BY is_active DESC');
	$numRows = $wpdb->num_rows;
	$row = 1;
	$addCss = '';
	$widthText = '-';
	foreach ($ctas as $cta)
	{
		//($row&1) ? "odd" : "even";
		if($row&1)
			$addCSS = ' alt';
		else
			$addCSS = '';
	
		echo '<div class="row'. $addCSS . '">';
		// get a clean url for edits
		$the_url = getCleanURL();

		// if this is a feature box then we get the insert_before text to display in that column down below
		$insert_before = $cta->insert_before;
		if(strlen($insert_before) > 0)
			$insertBeforeText = ' @ ' . $cta->insert_before;
		else
			$insertBeforeText = '';

		// create the edit checkbox and all other columents			
		echo '<div class="column name" id="name_column"><input type="checkbox" class="checkbox of-input" name="cta_ids[]" id="cta_ids[]" value="'.$cta->id .'"><a href="'. $the_url . '&ucta_id=' . $cta->id . '" title="edit this row">' . $cta->name . '</a></div>'; 
		echo '<div class="column type">'. getTypeFriendlyName($cta->type) . $insertBeforeText. '</div>';
		echo '<div class="column status '.getStatusText($cta->is_active).'">'. getStatusText($cta->is_active) . '</div>';
		// took this out because it's not really relevant to show on the list page
		//echo '<div class="column content">'. $cta->content . '</div>';
	
		$widthText = '-';
		if($cta->width > 0)
			$widthText = $cta->width;
		// took this out because it's not really relevant to show on the list page
		// echo '<div class="column width_column">'. $widthText . '</div>';
		echo '<div class="column categories">';
		// if it's set to all then show that if not let's get a list of categories
		if($cta->is_all)
		{
			echo 'All Categories';
		}
		else
		{
			// here we are joining with the category table in Wordpress and our Link table for CTA's
			$wp_term_table_name = $wpdb->prefix . 'terms'; //category table
			$categories=$wpdb->get_results("SELECT ".$wp_term_table_name.".name FROM ".$wpdb->prefix."terms, ".ULTIMATE_CTA_CATEGORY_TABLE." WHERE ".$wp_term_table_name.".term_id = ".ULTIMATE_CTA_CATEGORY_TABLE.".category_id and ".ULTIMATE_CTA_CATEGORY_TABLE.".ultimate_cta_id = " . $cta->id );
			$i=0;
			$numRows = $wpdb->num_rows;
			//echo '$numRows; ' . $numRows;
			foreach ($categories as $category)
			{
				// adding the comma
				if($i > 0 && $i < $numRows)
					echo ', ';
				// display name
				echo $category->name;
				$i++;
			}
		}
		$row++; // next row of CTA for Loop
		echo '</div></div>'; // closing the overall divs
	} //foreach ($ctas as $cta)

	// if ther eare no CTA's then we prompt the user to enter some
	if($numRows ==0)
	{
	?>
		<div class="no_cta_message">
			You currently have no Ultimate Call to Action's - <a class="add-new-h2" href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&c_view=new">Add New</a>
		</div>
	<?php } ?>
	</div>
	<?php
	} //$current_view == 'display'
?>
</form>
</div>