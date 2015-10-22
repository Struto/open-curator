<?php


/**
* Registers the sidebar(s).
*/

register_sidebar(
	array(
		'name'			=>	'Single Post',
		'id'			=>	'sidebar-single',
		'before_widget'	=>	'<div class="widget">',
		'after_widget'	=>	'</div>'
	)
);

register_sidebar(
	array(
		'name'			=>	'Footer',
		'id'			=>	'sidebar-footer',
		'before_widget'	=>	'<div class="widget">',
		'after_widget'	=>	'</div>'
	)
);

require dirname(__FILE__) . '/theme-updates/theme-update-checker.php';
unset($CurationFluxThemeUpdateChecker);
	$CurationFluxThemeUpdateChecker = new ThemeUpdateChecker(
	'curationflux', //Theme slug. Usually the same as the name of its directory.
	'http://members.youbrandinc.com/wp-update-server/?action=get_metadata&slug=curationflux&license_key=nokeyrequired' //Metadata URL.
);

/**
* Registers the primary menu.
*/

register_nav_menu('primary', 'Primary Menu');

function curationflux_nav_menu_args($args = '')
{
	$args['container'] = false;
	return $args;
}
add_filter('wp_nav_menu_args', 'curationflux_nav_menu_args');


/**
* Configure general theme settings and register styles & scripts.
*/

if(!isset($content_width)) $content_width = 1140;
add_theme_support('automatic-feed-links');
add_theme_support('post-thumbnails');

function curationflux_add_stylesheets()
{
	wp_register_style(
		'curationflux-css-magnific',
		get_template_directory_uri() . '/css/magnific-popup.css'
	);
	wp_enqueue_style('curationflux-css-magnific');
	
	wp_register_style(
		'curationflux-css-fonts',
		'http' . ($_SERVER['SERVER_PORT'] == 443 ? 's' : '') . '://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300'
	);
	wp_enqueue_style('curationflux-css-fonts');
}
add_action('wp_enqueue_scripts', 'curationflux_add_stylesheets');

function curationflux_customizer_preview_js() { 
	wp_enqueue_script( 'curationflux_customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'jquery','customize-preview' ), '20130916', true ); 
} 
add_action( 'customize_preview_init', 'curationflux_customizer_preview_js' );

function curationflux_add_scripts()
{
	wp_deregister_script('jquery');
	wp_register_script(
		'jquery',
		'http' . ($_SERVER['SERVER_PORT'] == 443 ? 's' : '') . '://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
		array(),
		false,
		true
	);
	wp_enqueue_script('jquery');
	
	wp_register_script(
		'curationflux-js-magnific',
		get_template_directory_uri() . '/js/jquery.magnific-popup.min.js',
		array(),
		false,
		true
	);
	wp_enqueue_script('curationflux-js-magnific');
	
	wp_register_script(
		'curationflux-js-init',
		get_template_directory_uri() . '/js/init.js',
		array(),
		false,
		true
	);
	wp_enqueue_script('curationflux-js-init');
}
if(!is_admin()) add_action('wp_enqueue_scripts', 'curationflux_add_scripts', 11);

function curationflux_thumbnail($pID,$thumb='medium') 
{ 
	$imgsrc = FALSE; 
	if (has_post_thumbnail())
	{
		$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($pID),$thumb);
		$imgsrc = $imgsrc[0];
	} elseif ($postimages = get_children("post_parent=$pID&post_type=attachment&post_mime_type=image&numberposts=0")) 
		{
			foreach($postimages as $postimage)
			{
					$imgsrc = wp_get_attachment_image_src($postimage->ID, $thumb);
					$imgsrc = $imgsrc[0];
			}
		}
	elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) 
	{
			$imgsrc = $match[1];
	} 
	if($imgsrc) { 
		$imgsrc = '<a href="'. get_permalink().'"><img src="'.$imgsrc.'" alt="'.get_the_title().'" class="post-thumb" /></a>';
     return $imgsrc;
	}  
} 



/**
* Filter meta title.
* 
* @param mixed $title
* @param mixed $sep
*/

function curationflux_wp_title($title, $sep)
{
	return $title . get_bloginfo('name');
}
add_filter('wp_title', 'curationflux_wp_title', 10, 2);


/**
* Setup theme customization.
* 
* @param mixed $wp_customize
*/
function curationflux_customize_register( $wp_customize )
{
	// include ybi customizer awesome!
	require get_template_directory() . '/ybi-customizer-awesome/ybi-customizer-awesome.php';

	// header image
	$wp_customize->add_section(
		'curationflux_header_image_options',
		array(
			'title'		=> __('Header Image & Options', 'curationflux'),
		)
	);
	
   	$wp_customize->add_setting(
		'curationflux_remove_title_tagline',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_remove_title_tagline',
		array(
			'label' => __('Remove Title & Tagline', 'curationflux'),
			'section' => 'curationflux_header_image_options',
			'settings' => 'curationflux_remove_title_tagline',
			'type' => 'checkbox',
		)
	);
	
	$wp_customize->add_setting(
		'curationflux_header_image',
		array(
			'default'	=> '',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'curationflux_header_image',
           array(
               'label'          => __( 'Upload a header image (width: 1140px)', 'curationflux' ),
               'section'        => 'curationflux_header_image_options',
               'settings'       => 'curationflux_header_image',
           )
       )
   );
   
   $wp_customize->add_setting(
		'curationflux_remove_menu_bottom_border',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_remove_menu_bottom_border',
		array(
			'label' => __('Remove Menu Bottom Border', 'curationflux'),
			'section' => 'curationflux_header_image_options',
			'settings' => 'curationflux_remove_menu_bottom_border',
			'type' => 'checkbox',
		)
	);
   
	// we call this to include the CSS styling for the slider
	$wp_customize->add_setting( 'css_include', array('default' => '',));
	$wp_customize->add_control( new YBI_IncludeCSS( $wp_customize, 'css_include', 
		array('label'   => '','section' => 'curationflux_header_image_options','settings'   => 'css_include',) ) 
	);

	$ybi_control_name = 'curationflux_header_image_bottom_padding';
	$wp_customize->add_setting( $ybi_control_name, array('default' => '0','transport'=>'postMessage') );
	$wp_customize->add_control(new YBI_Slider( $wp_customize, $ybi_control_name, 
		array('label'   => 'Header Bottom Padding','section' => 'curationflux_header_image_options','settings'   => $ybi_control_name), 
		array('slider_min' => -1, 'slider_max' => 101, 'can_edit' => false)));

	$ybi_control_name = 'curationflux_header_image_top_padding';
	$wp_customize->add_setting( $ybi_control_name, array('default' => '0','transport'=>'postMessage') );
	$wp_customize->add_control(new YBI_Slider( $wp_customize, $ybi_control_name, 
		array('label'   => 'Header Top Padding','section' => 'curationflux_header_image_options','settings'   => $ybi_control_name), 
		array('slider_min' => -1, 'slider_max' => 101, 'can_edit' => false)));


	$ybi_control_name = 'curationflux_post_top_padding';
	$wp_customize->add_setting( $ybi_control_name, array('default' => '0','transport'=>'postMessage') );
	$wp_customize->add_control(new YBI_Slider( $wp_customize, $ybi_control_name, 
		array('label'   => 'Post Top Padding','section' => 'curationflux_header_image_options','settings'   => $ybi_control_name), 
		array('slider_min' => -1, 'slider_max' => 101, 'can_edit' => false)));
	

	// color options section
	$wp_customize->add_section(
		'curationflux_color_options',
		array(
			'title'		=> __('Color Options', 'curationflux'),
			'priority'	=> 31,
		)
	);
	
	// background color
	$wp_customize->add_setting(
		'curationflux_background_color',
		array(
			'default'	=> '#f0f0f0',
			'transport'=>'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'curationflux_background_color',
			array(
				'label'		=> __('Background Color', 'curationflux'),
				'section'	=> 'curationflux_color_options',
				'settings'	=> 'curationflux_background_color',
			)
		)
	);
	
	// post box color
	$wp_customize->add_setting(
		'curationflux_postbox_color',
		array(
			'default'	=> '#ffffff',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'curationflux_postbox_color',
			array(
				'label'		=> __('Post Box Color', 'curationflux'),
				'section'	=> 'curationflux_color_options',
				'settings'	=> 'curationflux_postbox_color',
			)
		)
	);
	
	// text color
	$wp_customize->add_setting(
		'curationflux_text_color',
		array(
			'default'	=> '#333333',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'curationflux_text_color',
			array(
				'label'		=> __('Text Color', 'curationflux'),
				'section'	=> 'curationflux_color_options',
				'settings'	=> 'curationflux_text_color',
			)
		)
	);
	
	// text hover color
	$wp_customize->add_setting(
		'curationflux_text_hover_color',
		array(
			'default'	=> '#ffffff',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'curationflux_text_hover_color',
			array(
				'label'		=> __('Text Color (Hover)', 'curationflux'),
				'section'	=> 'curationflux_color_options',
				'settings'	=> 'curationflux_text_hover_color',
			)
		)
	);
	
	// highlight color
	$wp_customize->add_setting(
		'curationflux_highlight_color',
		array(
			'default'	=> '#ff0099',
			'transport'	=> 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'curationflux_highlight_color',
			array(
				'label'		=> __('Highlight Color', 'curationflux'),
				'section'	=> 'curationflux_color_options',
				'settings'	=> 'curationflux_highlight_color',
			)
		)
	);
	

	
	// category options section
	$wp_customize->add_section(
		'curationflux_category_options',
		array(
			'title'		=> __('Category Options', 'curationflux'),
			'priority'	=> 32,
		)
	);
	

	
	// display excerpts
	$wp_customize->add_setting(
		'curationflux_display_excerpts',
		array(
			'default'	=> true,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_display_excerpts',
		array(
			'label' => __('Display Excerpts', 'curationflux'),
			'section' => 'curationflux_category_options',
			'settings' => 'curationflux_display_excerpts',
			'type' => 'checkbox',
		)
	);
	
	// display more link
	$wp_customize->add_setting(
		'curationflux_display_more',
		array(
			'default'	=> true,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_display_more',
		array(
			'label' => __('Display More Link', 'curationflux'),
			'section' => 'curationflux_category_options',
			'settings' => 'curationflux_display_more',
			'type' => 'checkbox',
		)
	);
	
	// more link text
	$wp_customize->add_setting(
		'curationflux_more_link',
		array(
			'default' => 'more&hellip;',
		)
	);
	
	$wp_customize->add_control(
		'curationflux_more_link',
		array(
			'label' => 'Post Box Link Text',
			'section' => 'curationflux_category_options',
			'type' => 'text',
		)
	);
	
	
	// post options section
	$wp_customize->add_section(
		'curationflux_post_options',
		array(
			'title'		=> __('Post Options', 'curationflux'),
			'priority'	=> 33,
		)
	);
	
	// display tags
	$wp_customize->add_setting(
		'curationflux_display_tags',
		array(
			'default'	=> true,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_display_tags',
		array(
			'label' => __('Display Tags', 'curationflux'),
			'section' => 'curationflux_post_options',
			'settings' => 'curationflux_display_tags',
			'type' => 'checkbox',
		)
	);
	
	// display footer widgets
	$wp_customize->add_setting(
		'curationflux_display_footer',
		array(
			'default'	=> true,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationflux_display_footer',
		array(
			'label' => __('Display Footer Widgets on Single Post Pages', 'curationflux'),
			'section' => 'curationflux_post_options',
			'settings' => 'curationflux_display_footer',
			'type' => 'checkbox',
		)
	);
	
	$wp_customize->add_section('curationflux_customcss_options',array('title'=> __('Custom CSS', 'curationflux'), 'priority' => 100));	
	$wp_customize->add_setting( 'curationflux_customcss', array('default' => '',) );
 	$wp_customize->add_control( new YBI_Textarea_Control( $wp_customize, 'curationflux_customcss', array(
		'label'   => 'Enter Custom CSS',
		'section' => 'curationflux_customcss_options',
		'settings'   => 'curationflux_customcss',
		) ) 
	);
	
}
add_action('customize_register', 'curationflux_customize_register');

function ybi_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
	else
		return 0;
}

/**
* Parse custom css code.
*/

function curationflux_customize_css()
{
	$bgColor = get_theme_mod('curationflux_background_color', '#f0f0f0');
	$textColor = get_theme_mod('curationflux_text_color', '#333333');
	$textHoverColor = get_theme_mod('curationflux_text_hover_color', '#ffffff');
	$postBgColor = get_theme_mod('curationflux_postbox_color', '#ffffff');
	$highlightColor = get_theme_mod('curationflux_highlight_color', '#ff0099');
	$thumbnailWidth = (int) get_option('thumbnail_size_w');
	$thumbnailHeight = (int) get_option('thumbnail_size_h');
	$curationflux_header_image_top_padding = get_theme_mod('curationflux_header_image_top_padding', '0');
	$curationflux_header_image_bottom_padding = get_theme_mod('curationflux_header_image_bottom_padding', '0');
	$curationflux_post_top_padding = get_theme_mod('curationflux_post_top_padding', '40');
	
	$curationflux_remove_menu_bottom_border = (bool)get_theme_mod('curationflux_remove_menu_bottom_border', true);
	$curationflux_customcss = get_theme_mod('curationflux_customcss', '');
	
	$hex = $textColor;
	if($hex{0} === '#') $hex = substr($hex, 1);
	
	if(strlen($hex) == 6)
	{
		list($r, $g, $b) = array($hex{0} . $hex{1}, $hex{2} . $hex{3}, $hex{4} . $hex{5});
	}
	elseif(strlen($hex) == 3)
	{
		list($r, $g, $b) = array($hex{0} . $hex{0}, $hex{1} . $hex{1}, $hex{2} . $hex{2});
	}
	else
	{
		return array();
	}
	
	$r = hexdec($r);
	$g = hexdec($g);
	$b = hexdec($b);
	$textColorRGB = array('r' => $r, 'g' => $g, 'b' => $b);
?>
	<style type="text/css">
		body, .mfp-bg, #menu .menu > ul > li > .children, #menu .menu > li > .sub-menu { background: <?php echo $bgColor; ?>; }
		body, #blog-title a, #menu .menu a, #post-list .post .no-more, .comment-author a, .comment-meta a, .ti, .ta, .form .hint, .themeinfo, .mfp-title, .mfp-counter, .mfp-close-btn-in .mfp-close, #page.open #menu .menu .current_page_ancestor > a {
			color: <?php echo $textColor; ?>;
		}
		a, #blog-title a:hover, #menu .menu a:hover, #menu .menu .current-menu-item > a, #menu .menu .current_page_item > a, #menu .menu .current_page_ancestor > a, .bypostauthor .comment-author a, .bypostauthor .comment-author cite, #post-navi div, #post blockquote:before, #post blockquote:after, .form .req label span {
			color: <?php echo $highlightColor; ?>;
		}
		<?php if(!$curationflux_remove_menu_bottom_border): ?>
		#header .inner { border-bottom: 1px solid <?php echo $textColor; ?>; }
		#sidebar-footer { border-top: 1px solid <?php echo $textColor; ?>;  }
		.comment, #sidebar-single .widget, #footer .widget { border-bottom: 1px solid rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .3); }
		<?php endif; ?>

		#header_image {padding-top: <?php echo $curationflux_header_image_top_padding ?>px; padding-bottom: <?php echo $curationflux_header_image_bottom_padding ?>px;}
		#menu .menu > ul > li > .children, #menu .menu > li > .sub-menu { border-top: 1px solid <?php echo $highlightColor; ?>; }
		.gt-800 #blog-title a:after { content: "<?php _e('Home', 'curationflux'); ?>"; }
		.home #post-list {padding-top:<?php echo $curationflux_post_top_padding; ?>px; }
		#post-navi { border-top: 1px solid <?php echo $textColor; ?>; }
		
		.mfp-title, .mfp-counter { text-shadow: 1px 1px 0 <?php echo $bgColor; ?>; }
		.mfp-arrow-left:after, .mfp-arrow-left .mfp-a { border-right-color: <?php echo $bgColor; ?>; }
		.mfp-arrow-left:before, .mfp-arrow-left .mfp-b { border-right-color: <?php echo $textColor; ?>; }
		.mfp-arrow-right:after, .mfp-arrow-right .mfp-a { border-left-color: <?php echo $bgColor; ?>; }
		.mfp-arrow-right:before, .mfp-arrow-right .mfp-b { border-left-color: <?php echo $textColor; ?>; }
		.post { background: <?php echo $postBgColor; ?>; }
		#post-list .post:hover { background: <?php echo $highlightColor; ?>; }
		#post-list .sticky-icon { border-color: transparent <?php echo $highlightColor; ?> transparent transparent; }
		#post-list .post:hover, #post-list .post:hover a { color: <?php echo $textHoverColor; ?>; }
		#post .gallery .gallery-item {
			width: <?php echo $thumbnailWidth . 'px'; ?>;
			height: <?php echo $thumbnailHeight . 'px'; ?>;
		}
		#comments { border-top: 1px solid rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .3); }

		#page.open { box-shadow: 10px 0 20px 0 rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .3); }
		@media only screen and (max-width: 800px) {
			#menu { background: rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .2); }
			#sidebar-single { border-top: 1px solid <?php echo $textColor; ?>; }
		}
		#mobile-menu { background: rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .1); }
		#mobile-menu:before { border-color: rgba(<?php echo $textColorRGB['r']; ?>, <?php echo $textColorRGB['g']; ?>, <?php echo $textColorRGB['b']; ?>, .7); }
		#mobile-menu:hover { background: <?php echo $highlightColor; ?>; }
		#mobile-menu:hover:before { border-color: <?php echo $textHoverColor; ?>; }
		<?php echo $curationflux_customcss; ?>
	</style>
<?php
}
add_action('wp_head', 'curationflux_customize_css');

 function remove_recent_comments_style() {
 global $wp_widget_factory;
 remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'remove_recent_comments_style');
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