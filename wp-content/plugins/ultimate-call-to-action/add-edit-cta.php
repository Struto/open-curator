<?php
global $wpdb;

if(isset($_SERVER['HTTP_REFERER'])){
  // do stuff
} 
		if($_POST['submit_hidden'] == 'Y') {
			//Form data sent
			if (isset($_GET['ucta_id'])) {
				$ucta_id = $_GET['ucta_id'];
			}

				$ucta_id = $_POST['ucta_id'];


				$ucta_name = $_POST['ucta_name'];
				$ucta_type = $_POST['ucta_type'];
				$ucta_width = $_POST['ucta_width'];
				$ucta_categories = $_POST['ucta_categories'];
				$ucta_content = $_POST['ucta_content'];
	//			total_category_count
				$total_category_count = $_POST['total_category_count'];
				$show_categories = $_POST["show_categories"];
				$totalSelectedCategories = count($show_categories);
				$is_all_selected = false;
				$is_all_selected = ($total_category_count == count($show_categories));
				//$ucta_content = $ucta_content . 'total_cat ' . $total_category_count . 'show cats ' . $show_categories . 'is all ' . $is_all_selected;
				//settype($total_category_count, int);
	
				$table_name = $wpdb->prefix . 'ultimate_cta';
			if($ucta_id > 0)
			{
				
				
								//update cta	
				$wpdb->update($table_name, 
				array( 
					'name' => $ucta_name, 
					'type' => $ucta_type, 
					'content' => $ucta_content, 
					'width' => $ucta_width,
					'is_all' => $is_all_selected),
					array('ID' => $ucta_id));
				$wpdb->query(" DELETE FROM $wpdb->ultimate_cta_category WHERE ID = '" .$ucta_id. "' ");
				if(!$is_all_selected)
				{
					$table_name = $wpdb->prefix . 'ultimate_cta_category';
					foreach ($show_categories as $category)
					{
						$wpdb->insert($table_name  , array( 
							'ultimate_cta_id' => $ucta_id, 
							'category_id' => $category
							));
					}
				}
				
			}
			else
			{
					$wpdb->insert($table_name  , array( 
					'name' => $ucta_name, 
					'type' => $ucta_type, 
					'content' => $ucta_content, 
					'width' => $ucta_width,
					'is_all' => $is_all_selected
					));
	
				if(!$is_all_selected)
				{
					$lastid = $wpdb->insert_id;
					$table_name = $wpdb->prefix . 'ultimate_cta_category';
					foreach ($show_categories as $category){
		
					$wpdb->insert($table_name  , array( 
						'ultimate_cta_id' => $lastid, 
						'category_id' => $category
						) 
					);
				}

				
			}


			}


			?>
			<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
			<?php
		} else {
			if (isset($_GET['ucta_id'])) {
				$ucta_id = $_GET['ucta_id'];
			}
			$table_name = $wpdb->prefix . 'ultimate_cta';
			$ultimate_cta_category_table_name = $wpdb->prefix . 'ultimate_cta_category';
			$wp_term_table_name = $wpdb->prefix . 'terms'; //category table

			$cta=$wpdb->get_row("SELECT name, type, content, width, id, is_all FROM " . $table_name . ' where id = ' . $ucta_id);			
			//Normal page display
			$ucta_name = $cta->name;
			$ucta_type = $cta->type;
			$ucta_width = $cta->width;
			$ucta_content = $cta->content;
			$is_all = $cta->is_all;
		}
	?>
	

<div class="wrap">

<form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>?ucta_id=<?php echo $ucta_id; ?>">
<div class="main_heading">
    <div class="left-side"><?php    echo "<h2>" . __( 'Ultimate Call to Action', 'ucta_trdom' ) . "</h2>"; ?></div>
    <div class="right-side-top ">		<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'ucta_trdom' ) ?>" />
	</p></div>
</div>

    <input type="hidden" name="submit_hidden" value="Y">
    <input type="hidden" name="ucta_id" value="<?php echo $ucta_id; ?>">



<style type="text/css">
.column {float: left; width: 150px;}
.categories { width: 400px;}
.row {clear: both; margin: 10px; padding: 10px;}
</style>
<?php 
//$ctas=$wpdb->get_results("SELECT page, count(IP) AS theCount FROM our_tracker GROUP BY page ORDER BY theCount DESC LIMIT 0,10");
$table_name = $wpdb->prefix . 'ultimate_cta';
$ultimate_cta_category_table_name = $wpdb->prefix . 'ultimate_cta_category';
$wp_term_table_name = $wpdb->prefix . 'terms'; //category table

?>





<div class="left-side">


<div class="section">
    <h3 class="heading">Call to Action Name</h3>
    <div class="option">
        <div class="controls">
        <label>Name:</label>
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
                <option value="custom" <?php selected($ucta_type, custom); ?>>Custom</option>
                <option value="inline_above" <?php selected($ucta_type, inline_above); ?>>Inline Above</option>
                <option value="inline_below" <?php selected($ucta_type, inline_below); ?>>Inline Below</option>
                <option value="sidebar" <?php selected($ucta_type, sidebar); ?>>Sidebar</option>
            </select>
        </div>
        <div class="explain">Enter the type of call to action.</div>
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
        <label>Width:</label>
		    <input type="text" name="ucta_width" value="<?php echo $ucta_width; ?>" size="50">
        </div>
        <div class="explain">Enter the width for this Call to Action (not required)</div>
        <div class="clear"> </div>
    </div>
</div>

<?php 
echo '<ul style="clear: both;">';
$categories = get_categories('orderby=name');
echo '<input type="hidden" name="total_category_count" value="' . count($categories) . '">';
foreach ($categories as $category ) {
//echo '<li>' . $category->cat_name . ' - ' . $category->term_id . '</li>';
//echo '<li>' . $category->cat_name . ' - ' . $category->term_id . '</li>';
	?>
    <label><input type="checkbox" class="checkbox of-input" name="show_categories[]" id="show_categories[]" value="<?php echo $category->term_id; ?>" <?php checked($category->term_id, 6); ?> /> <?php echo $category->cat_name; ?></label>

<?php
}
echo '</ul>';
?>
	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'ucta_trdom' ) ?>" />
	</p>



</div>
<div class="right-side">

<p>Ultimate Call to Action Plugin by <a href="http://www.youbrandinc.com" title="You Brand, Inc." target="_blank">You Brand, Inc.</a></p>
<p>For support visit... <a href="http://www.curationtraffic.com/support" title="Curation Traffic support page" target="_blank">http://www.CurationTraffic.com/support</a></p>




</div>
</form>
<br style="clear: both;">

</div>
	
	