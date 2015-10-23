<?php
/*  
	Copyright 2012 - end of TIME -  You Brand, Inc.  (email : http://www.youbrandinc.com/)
*/
	// Start class ucta_widget //
	class ucta_widget extends WP_Widget {

	// Constructor //
	function ucta_widget() 
	{
		$widget_ops = array( 'classname' => 'ucta_widget', 'description' => 'The ultimate way to display your call to actions in your sidebar' ); // Widget Settings
		$control_ops = array( 'id_base' => 'ucta_widget' ); // Widget Control Settings
		$this->WP_Widget( 'ucta_widget', 'Ultimate Call to Action', $widget_ops, $control_ops ); // Create the widget
	}
	// Extract Args //
	function widget($args, $instance) 
	{
		global $wpdb; 
		extract( $args );
		// we get the title but we don't use it, maybe in a later version we will add the option to have a title
		$title 		= apply_filters('widget_title', $instance['title']); // the widget title
		$cta_id 	= $instance['cta_id']; // get the selected Call to Action
		// Title of widget //
		if ( $title ) { echo $before_title . $title . $after_title; }

		// we check this because there can be multiple call to actions assigned to this category so we give the option to select "All Available" in that case we don't pass
		// a specific CTA ID
		if($cta_id == 'all_available')
			$cta_id = 0;

		// load all available CTA's
		$allCTAs = $wpdb->get_results(buildSQL('sidebar', $cta_id));
		// get a single CTA
		$theCTA =  getFinalCTA($allCTAs, 'sidebar', $cta_id);
		//what is the content?
		
		
		
		//$theContent = stripslashes($CTAs->content);
		if(!is_null($theCTA))
		{
			//var_dump($theCTA);
		  if($theCTA->width > 0)
			  $addStyle = ' style="width:' . $theCTA->width . 'px;"';
		  else
			  $addStyle = '';
			$theContent = '<div class="cta_sidebar"'.$addStyle.'>' . stripslashes($theCTA->content) . '</div>';
			// Widget output //
			// we only show the widget if there is content.
			if($theContent <> '')
			{
				echo $before_widget;
				echo $theContent;
				echo $after_widget;
			}
		}
	}

	// Update Settings //
	function update($new_instance, $old_instance) 
	{
		//update the widget with the selected CTA
		$instance['cta_id'] = $new_instance['cta_id'];
		return $instance;
	}
	// Widget Control Panel //
	function form($instance) 
	{
		global $wpdb; 
		// we check to see if the CTA_D is set to all that way if there is more than one CTA per category or it is set to ALL Categories then 
		// we will randomly disply those, this just ensures that theh title of the widget is set properly via the hidden field below
		if($instance['cta_id'] == 'all_available')
		{
			$ucta_name = 'All Available';	
		}
		else
		{
			$theSQL = "SELECT name FROM " . ULTIMATE_CTA_TABLE  . ' where id = ' . $instance['cta_id'];
			//echo $theSQL;
			$cta=$wpdb->get_row($theSQL);			
			// get all the values for the edit page
			$ucta_name = $cta->name;
		}
		// keep in mind the title is a hidden field below, that sets the title of the individual widget
		$title = apply_filters('widget_title', $ucta_name ); // the widget title
		$defaults = array('title' => $ucta_name, 'cta_id' => 'all_available');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
?>
		<p>
			<label for="<?php echo $this->get_field_id('cta_id'); ?>">Select Call to Action to Display:</label>
			<select id="<?php echo $this->get_field_id('cta_id'); ?>" name="<?php echo $this->get_field_name('cta_id'); ?>" class="widefat" style="width:100%;">
			<option value="all_available" <?php selected('all_available', $instance['cta_id']); ?>>All Available</option>
			<?php 
			$selected_categories = $wpdb->get_results("SELECT id, name FROM ".ULTIMATE_CTA_TABLE." WHERE TYPE =  'sidebar'");
			foreach( $selected_categories as $result )
			{
			?>
				<option value="<?php echo $result->id; ?>" <?php selected($result->id, $instance['cta_id']); ?>><?php echo $result->name; ?></option>
			<?php 
			} 
			
			?>
			</select>

		</p>
        <p><em>Important Setting Note</em>: Only one widget should be set to All Available. While you can have multiple widgets in your sidebars if you have more than one set to "All Available" you risk having repeated Call to Actions.</p>
		<p>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="hidden" value="<?php echo $instance['title']; ?>" />
		</p>
<?php 
	} //function form($instance) 
} // End class ucta_widget
add_action('widgets_init', create_function('', 'return register_widget("ucta_widget");'));
?>