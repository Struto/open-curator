<?php
/**
 * Curation Pin functions
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 */	
    
if ( ! isset( $content_width ) )
	$content_width = 630; /* pixels */

/**
* Custom Theme Options
*/

//if ( is_admin() && is_readable( get_template_directory() . '/options/theme-options.php' ) )
	//require_once( get_template_directory() . '/options/theme-options.php' );

if ( ! function_exists( 'curationpin_setup' ) ):
	
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */

function curationpin_setup() {
	
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	// post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size('summary-image', 300, 9999);
	add_image_size('detail-image', 750, 9999);

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Buttercream, use a find and replace
	 * to change 'buttercream' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'curationpin', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'main_nav' => __( 'Main Menu', 'curationpin' ),
	) );

	/**
	* Add support for editor style
	*/
	add_editor_style();

	/**
	 * Add support for custom backgrounds
	 */
	$args = array(
		'default-color' => '',
		'default-image' => get_template_directory_uri() . '/images/bubble.png',
	);

	$args = apply_filters( 'curationpin_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	}
	else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_theme_support( 'custom-background', $args );
	}
}	
endif;
add_action( 'after_setup_theme', 'curationpin_setup' );
//register_sidebar(array('name' => 'Right Sidebar',  'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => "</div>", 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
function curationpin_widgets_init() {
     //setup footer widget area
	register_sidebar(array(
    		'name' => 'Footer',
    		'id'   => 'curationpin_footer',
    		'description'   => 'Footer Widget Area',
    		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-copy">',
    		'after_widget'  => '</div></div>',
    		'before_title'  => '<h3>',
    		'after_title'   => '</h3>'
    	)
	);
}
add_action( 'widgets_init', 'curationpin_widgets_init' );

if (!is_admin())
	add_action( 'wp_enqueue_scripts', 'curationpin_scripts' ); 

function curationpin_scripts() { 
	global $post;

	$curationpin_options = get_option('curationpin_theme_options');

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'curationpin.functions', get_template_directory_uri() . '/js/functions.js', array( 'jquery-masonry' ), '20130605', true );

	wp_enqueue_script( 'jquery-masonry' );

	wp_enqueue_script( 'mobile-nav', get_template_directory_uri() . '/js/mobile-nav.min.js', array( 'jquery' ), '20130605', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}

/**
 * wp_title() Filter for better SEO.
 *
 * Adopted from Twenty Twelve
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_title
 *
 */
if ( !function_exists('curationpin_wp_title') && !defined( 'AIOSEOP_VERSION' ) ) :

	function curationpin_wp_title( $title, $sep ) {
		global $page, $paged;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', 'curationpin' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'curationpin_wp_title', 10, 2 );

endif;


function curationpin_thumbnail($pID,$thumb='medium') { 
$imgsrc = FALSE; 
 if (has_post_thumbnail()) {
						$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($pID),$thumb);
						$imgsrc = $imgsrc[0];
} elseif ($postimages = get_children("post_parent=$pID&post_type=attachment&post_mime_type=image&numberposts=0")) {
				foreach($postimages as $postimage) {
							$imgsrc = wp_get_attachment_image_src($postimage->ID, $thumb);
							$imgsrc = $imgsrc[0];
						}
					} elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
						$imgsrc = $match[1];
					} 
		if($imgsrc) { 
$imgsrc = '<a href="'. get_permalink().'"><img src="'.$imgsrc.'" alt="'.get_the_title().'" class="summary-image" /></a>';
     	return $imgsrc;
		}  
} 


// add the theme customizer js
function curationpin_customizer_preview_js() { 
	wp_enqueue_script( 'curationpin_customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'jquery','customize-preview' ), '20130916', true ); 
} 
add_action( 'customize_preview_init', 'curationpin_customizer_preview_js' );

// add some customizer settings
add_action('customize_register', 'curationpin_customize_register');
function curationpin_customize_register( $wp_customize )
{
	require get_template_directory() . '/ybi-customizer-awesome/ybi-customizer-awesome.php';
	// header image
	$wp_customize->add_section(
		'curationpin_header_image_options',
		array(
			'title'		=> __('Header Image & Options', 'curationpin'),

		)
	);
	
	$wp_customize->add_setting(
		'curationpin_header_image',
		array(
			'default'	=> '',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'curationpin_header_image',
           array(
               'label'          => __( 'Upload a header logo', 'curationpin' ),
               'section'        => 'curationpin_header_image_options',
               'settings'       => 'curationpin_header_image',
           )
       )
   );
   
	
	// we call this to include the CSS styling for the slider
	$wp_customize->add_setting( 'css_include', array('default' => '',));
	$wp_customize->add_control( new YBI_IncludeCSS( $wp_customize, 'css_include', 
		array('label'   => '','section' => 'curationpin_header_image_options','settings'   => 'css_include',) ) 
	);
	
	$wp_customize->add_setting( 'curationpin_logo_padding_right', array('default' => '0','transport'=>'postMessage') );
	$wp_customize->add_control(new YBI_Slider( $wp_customize, 'curationpin_logo_padding_right', 
		array('label'   => 'Logo Right Padding','section' => 'curationpin_header_image_options','settings'   => 'curationpin_logo_padding_right'), 
		array('slider_min' => -1, 'slider_max' => 101, 'can_edit' => false)));



	$wp_customize->add_setting(
		'curationpin_menu_bg_color',
		array(
			'default' => '#FFFFFF',
			'transport'=>'postMessage'
		)
	);	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'curationpin_menu_bg_color', 
		array(
			'label'      => __( 'Menu Background Color', 'curationpin' ),
			'section'    => 'curationpin_header_image_options',
			'settings'   => 'curationpin_menu_bg_color',
		) ) 
	);

	$wp_customize->add_setting(
		'curationpin_menu_link_color',
		array(
			'default' => '#999999',
			'transport'=>'postMessage'
		)
	);	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'curationpin_menu_link_color', 
		array(
			'label'      => __( 'Menu Link Color', 'curationpin' ),
			'section'    => 'curationpin_header_image_options',
			'settings'   => 'curationpin_menu_link_color',
		) ) 
	);
	$wp_customize->add_setting(
		'curationpin_menu_hover_link_color',
		array(
			'default' => '#111',
		)
	);	


	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'curationpin_menu_hover_link_color', 
		array(
			'label'      => __( 'Menu Mouse Over Link Color', 'curationpin' ),
			'section'    => 'curationpin_header_image_options',
			'settings'   => 'curationpin_menu_hover_link_color',
		) ) 
	);
	
	
	$wp_customize->add_setting( 'top_banner', array(
	    'default' => '',
	) );
 
	$wp_customize->add_control( new YBI_Textarea_Control( $wp_customize, 'top_banner', array(
		'label'   => 'Top Banner Code (728x90 banner works perfect)',
		'section' => 'curationpin_header_image_options',
		'settings'   => 'top_banner',
		) ) 
	);
	
	$wp_customize->add_section('curationpin_customcss_options',array('title'=> __('Custom CSS', 'curationpin'), 'priority' => 100));	
	$wp_customize->add_setting( 'curationpin_customcss', array('default' => '',) );
 	$wp_customize->add_control( new YBI_Textarea_Control( $wp_customize, 'curationpin_customcss', array(
		'label'   => 'Enter Custom CSS',
		'section' => 'curationpin_customcss_options',
		'settings'   => 'curationpin_customcss',
		) ) 
	);
	
} //curationpin_customize_register

function curationpin_customize_css()
{

	$curationpin_logo_padding_right = get_theme_mod('curationpin_logo_padding_right', '0');
	$curationpin_menu_link_color = get_theme_mod('curationpin_menu_link_color', '#999999');
	$curationpin_menu_hover_link_color = get_theme_mod('curationpin_menu_hover_link_color', '#111');
	$curationpin_menu_bg_color = get_theme_mod('curationpin_menu_bg_color', '#FFFFFF');
	$curationpin_customcss = get_theme_mod('curationpin_customcss', '');

?>
	<style type="text/css">
	
	.main_logo{ padding-right: <?php echo $curationpin_logo_padding_right; ?>px !important;}
	.main-nav ul li a {color: <?php echo $curationpin_menu_link_color; ?>;}
	.main-nav ul ul li a { color: <?php echo $curationpin_menu_link_color; ?> !important;}
	.main-nav ul li a:hover, .main-nav ul ul li a:hover { color: <?php echo $curationpin_menu_hover_link_color; ?> !important;}
	 #main-nav-wrapper { background: <?php echo $curationpin_menu_bg_color; ?>;}
	 <?php echo $curationpin_customcss; ?>
	</style>
	<script>
	// when the banner is on the page the padding is broke so the below
	// function fixes this. It resizes based on the window size
	// this is also called once the page is loaded
	jQuery(window).on("resize", fixPadding);
	function fixPadding()
	{
		var theHeight = jQuery('#site-navigation')[0].scrollHeight;
		var navWrapperHeight = jQuery('#main-nav-wrapper')[0].scrollHeight;
		if(navWrapperHeight == 14)
		{
			jQuery('#header').css("padding-bottom","120px");
		}
		else
		{
			jQuery('#header').css("padding-bottom","70px");
		}
	}
    </script>
<?php
}
add_action('wp_head', 'curationpin_customize_css');
function runLayoutFix() {
?>
<script>jQuery(document).ready(function() {fixPadding();});</script>
<?php	
}
add_action('wp_footer', 'runLayoutFix');
function add_ybi_admin_footer()
{?>
<script id="IntercomSettingsScriptTag">
  window.intercomSettings = {
    // TODO: The current logged in user's full name
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
	<?php $my_theme = wp_get_theme(); ?>
	theme: "<?php echo $my_theme->get( 'Name' ); ?>",
	
    app_id: "bonlegv"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://static.intercomcdn.com/intercom.v1.js';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
<?php }?>