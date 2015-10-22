<?php
/**
 * Custom theme options available via WP Dashboard
 */

?>
<?php

function curationpin_get_default_options() {
	$options = array(
		'logo' => get_template_directory_uri() .'/images/logo.png'
	);
	return $options;
}


function curationpin_options_init() {
     $curationpin_options = get_option( 'theme_curationpin_options' );
	 
	 // Are our options saved in the DB?
     if ( false === $curationpin_options ) {
		  // If not, we'll save our default options
          $curationpin_options = curationpin_get_default_options();
		  add_option( 'theme_curationpin_options', $curationpin_options );
     }
	 
     // In other case we don't need to update the DB
}
// Initialize Theme options
add_action( 'after_setup_theme', 'curationpin_options_init' );

function curationpin_options_setup() {
	global $pagenow;
	if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
		// Now we'll replace the 'Insert into Post Button inside Thickbox' 
		add_filter( 'gettext', 'replace_thickbox_text' , 1, 2 );
	}
}
add_action( 'admin_init', 'curationpin_options_setup' );

function replace_thickbox_text($translated_text, $text ) {	
	if ( 'Insert into Post' == $text ) {
		$referer = strpos( wp_get_referer(), 'curationpin-settings' );
		if ( $referer != '' ) {
			return __('I want this to be my logo!', 'curationpin' );
		}
	}

	return $translated_text;
}

// Add "Curation Pin Options" link to the "Appearance" menu
function curationpin_menu_options() {
	//add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $function);
     add_theme_page('Curation Pin Options', 'Curationbin Options', 'edit_theme_options', 'curationpin-settings', 'curationpin_admin_options_page');
}
// Load the Admin Options page
add_action('admin_menu', 'curationpin_menu_options');

function curationpin_admin_options_page() {
	?>
		<!-- 'wrap','submit','icon32','button-primary' and 'button-secondary' are classes 
		for a good WP Admin Panel viewing and are predefined by WP CSS -->
		
		
		
		<div class="wrap">
			
			<div id="icon-themes" class="icon32"><br /></div>
		
			<h2><?php _e( 'Curaton Pin Options', 'curationpin' ); ?></h2>
			
			<!-- If we have any error by submiting the form, they will appear here -->
			<?php settings_errors( 'curationpin-settings-errors' ); ?>
			
			<form id="form-curationpin-options" action="options.php" method="post" enctype="multipart/form-data">
			
				<?php
					settings_fields('theme_curationpin_options');
					do_settings_sections('curationpin');
				?>
			
				<p class="submit">
					<?php submit_button( __( 'Save Settings', 'curationpin' ), 'primary', 'theme_curationpin_options[submit]', false ); ?>
					<?php submit_button( __( 'Restore Defaults', 'curationpin' ), 'secondary', 'theme_curationpin_options[reset]', false ); ?>
				</p>
	
   
			</form>
	
		</div>
	<?php
}

function curationpin_options_validate( $input ) {
	$default_options = curationpin_get_default_options();
	$valid_input = $default_options;
	
	$curationpin_options = get_option('theme_curationpin_options');
	
	$submit = ! empty($input['submit']) ? true : false;
	$reset = ! empty($input['reset']) ? true : false;
	$delete_logo = ! empty($input['delete_logo']) ? true : false;
	
	if ( $submit ) {
		if ( $curationpin_options['logo'] != $input['logo']  && $curationpin_options['logo'] != '' )
			delete_image( $curationpin_options['logo'] );
		
		$valid_input['logo'] = $input['logo'];
	}
	elseif ( $reset ) {
		delete_image( $curationpin_options['logo'] );
		$valid_input['logo'] = $default_options['logo'];
	}
	elseif ( $delete_logo ) {
		delete_image( $curationpin_options['logo'] );
		$valid_input['logo'] = '';
	}
	
	return $valid_input;
}

function delete_image( $image_url ) {
	global $wpdb;
	
	// We need to get the image's meta ID..
	$query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";  
	$results = $wpdb -> get_results($query);

	// And delete them (if more than one attachment is in the Library
	foreach ( $results as $row ) {
		wp_delete_attachment( $row -> ID );
	}	
}

/********************* JAVASCRIPT ******************************/
function curationpin_options_enqueue_scripts() {
	wp_register_script( 'curationpin-upload', get_template_directory_uri() .'/js/curationpin-upload.js', array('jquery','media-upload','thickbox') );	

	if ( 'appearance_page_curationpin-settings' == get_current_screen() -> id ) {
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		
		wp_enqueue_script('media-upload');
		wp_enqueue_script('curationpin-upload');
		
	}
	
}
add_action('admin_print_styles-appearance_page_curationpin-settings', 'curationpin_options_enqueue_scripts');


function curationpin_options_settings_init() {
	register_setting( 'theme_curationpin_options', 'theme_curationpin_options', 'curationpin_options_validate' );
	
	// Add a form section for the Logo
	add_settings_section('curationpin_settings_header', __( 'Logo Options', 'curationpin' ), 'curationpin_settings_header_text', 'curationpin');
	
	// Add Logo uploader
	add_settings_field('curationpin_setting_logo',  __( 'Logo', 'curationpin' ), 'curationpin_setting_logo', 'curationpin', 'curationpin_settings_header');
	
	// Add Current Image Preview 
	add_settings_field('curationpin_setting_logo_preview',  __( 'Logo Preview', 'curationpin' ), 'curationpin_setting_logo_preview', 'curationpin', 'curationpin_settings_header');
}
add_action( 'admin_init', 'curationpin_options_settings_init' );

function curationpin_setting_logo_preview() {
	$curationpin_options = get_option( 'theme_curationpin_options' );  ?>
	<div id="upload_logo_preview" style="min-height: 100px;">
		<img style="max-width:100%;" src="<?php echo esc_url( $curationpin_options['logo'] ); ?>" />
	</div>
	<?php
}

function curationpin_settings_header_text() {
	?>
		<p><?php _e( 'Upload your logo. Theme supports GIF, JPEG, PNG.', 'curationpin' ); ?></p>
	<?php
}

function curationpin_setting_logo() {
	$curationpin_options = get_option( 'theme_curationpin_options' );
	?>
		<input type="hidden" id="logo_url" name="theme_curationpin_options[logo]" value="<?php echo esc_url( $curationpin_options['logo'] ); ?>" />
		<input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload Logo', 'curationpin' ); ?>" />
		<?php if ( '' != $curationpin_options['logo'] ): ?>
			<input id="delete_logo_button" name="theme_curationpin_options[delete_logo]" type="submit" class="button" value="<?php _e( 'Delete Logo', 'curationpin' ); ?>" />
		<?php endif; ?>
		<span class="description"><?php _e('Upload an image for the banner.', 'curationpin' ); ?></span>
	<?php
}


?>