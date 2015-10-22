<?php
if ( ! defined( 'CURATIONNEWS_VERSION' ) )
	define( 'CURATIONNEWS_VERSION', '1.0' );

define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once get_template_directory() . '/inc/options-framework.php';

function curationnews_setup() {
	load_theme_textdomain( 'curationnews', get_template_directory() . '/languages' );
	add_editor_style();
	if ( ! isset( $content_width ) ) $content_width = 900;
	register_nav_menus(array('top_nav' => __('Top Navigation', 'curationnews')));
	register_nav_menus(array('boot_nav' => __('Bottom Navigation', 'curationnews')));
	add_theme_support('automatic-feed-links');
	add_theme_support('post-thumbnails');
	add_theme_support('custom-background', array(
		'default-color' => 'f2f2f2',
	));
	add_theme_support( 'menus' );
			$defaults = array(
				'height' => 80,
			);
//	add_theme_support( 'custom-header',$defaults );
	
	add_theme_support('post-formats', array( 'aside', 'gallery','link','image','quote','status','video','audio','chat' ) );
}
add_action( 'after_setup_theme', 'curationnews_setup' );

function curationnews_widgets_init() {
register_sidebar(array('name' => 'sidebar-left',  'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => "</div>", 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
register_sidebar(array('name' => 'sidebar-right',  'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => "</div>", 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
register_sidebar(array('name' => 'sidebar-home', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => "</div>", 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>'));
}
add_action( 'widgets_init', 'curationnews_widgets_init' );

function curationnews_scripts() {
wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', null, '2.3.1');
wp_enqueue_style('fontawesome',  get_template_directory_uri() . '/font/font-awesome/css/font-awesome.min.css', array( 'bootstrap' ), '4.0.3' ); 
wp_enqueue_style('gallery', get_template_directory_uri() . '/css/bootstrap-image-gallery.min.css', array( 'bootstrap' ), '3.0.2' );
			// Load Theme Stylesheet
wp_enqueue_style( 'style', get_stylesheet_uri(), array( 'core-style' ), '1.0' );
wp_enqueue_script('curationnews_bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
wp_enqueue_script('curationnews_modal', get_template_directory_uri() . '/js/bootstrap-modal.js', array('jquery'), null, true);

if (is_singular() && comments_open() && get_option( 'thread_comments' )) {
wp_enqueue_script('comment-reply');
}
		
	if (!is_singular()) {		
      wp_enqueue_script('curationnews_masonry', get_template_directory_uri() . '/js/jquery.masonry.min.js', array('jquery'), null, false);
       wp_enqueue_script('curationnews_infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.js', array('jquery'), null, false);
     	}
		if (is_singular()) {
      wp_enqueue_script('curationnews_loadimage', get_template_directory_uri() . '/js/load-image.min.js', array('jquery'), null, true);
	  wp_enqueue_script('curationnews_imagegallery', get_template_directory_uri() . '/js/bootstrap-image-gallery.min.js', array('jquery'), null, true);
	}
}

add_action('wp_enqueue_scripts', 'curationnews_scripts');

function curationnews_foot_scripts() {
	if (!is_singular()) {	?>
	<script>		
		(function($){
			//set body width for IE8
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
				var ieversion=new Number(RegExp.$1)
				if (ieversion==8) {
					$('body').css('max-width', $(window).width());
				}
			}
			  init_masonry();
         })(jQuery);


		 function init_masonry(){
    var $container = jQuery('#mcontainer');
    var gutter = 10;
    var min_width = 300;
    $container.imagesLoaded( function(){
        $container.masonry({
            itemSelector : '.boxy',
            gutterWidth: gutter,
            isAnimated: true,
              columnWidth: function( containerWidth ) {
                var num_of_boxes = (containerWidth/min_width | 0);

                var box_width = (((containerWidth - (num_of_boxes-1)*gutter)/num_of_boxes) | 0) ;

                if (containerWidth < min_width) {
                    box_width = containerWidth;
                }

                jQuery('.boxy').width(box_width);

                return box_width;
              }
        });
    });
}

		jQuery(document).ready(function($){
			var $mcontainer = $('#mcontainer');
			
			$mcontainer.infinitescroll({
				navSelector : '#navigation',
				nextSelector : '#navigation #navigation-next a',
				itemSelector : '.boxy',
				loading: {
					msgText: '<?php _e('Loading', 'curationnews') ?>',
					finishedMsg: '<?php _e('All items loaded', 'curationnews') ?>',
					img: '<?php echo get_template_directory_uri(); ?>/img/loading.gif',
					finished: function() {
					  init_masonry();
					  },
				},
			}, function(newElements) {
				var $newElems = $(newElements).css({opacity: 0});

				$newElems.imagesLoaded(function(){
					$('#infscr-loading').fadeOut('normal');
					$newElems.animate({opacity: 1});
					$mcontainer.masonry('appended', $newElems, true);
				});
			});
			  init_masonry();
		});
	</script>
	<?php } // end if !is_singular() ?>
	<script>
		jQuery(document).ready(function($) {
			var $scrolltotop = $("#scroll-top");
			$scrolltotop.css('display', 'none');

			$(function () {
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						$scrolltotop.slideDown('fast');
					} else {
						$scrolltotop.slideUp('fast');
					}
				});
		
				$scrolltotop.click(function () {
					$('body,html').animate({
						scrollTop: 0
					}, 'fast');
					return false;
				});
			});
		});

		jQuery(document).ready(function($) {
			var $footernav = $("#footernav");
			$footernav.css('display', 'none');

			$(function () {
				$(window).scroll(function () {
					if ($(this).scrollTop() > 100) {
						$footernav.slideDown('fast');
					} else {
						$footernav.slideUp('fast');
					}
				});
			});
		});
	</script>
		<?php
}
add_action('wp_footer', 'curationnews_foot_scripts');

	function curationnews_attachment_link( $link, $id ) {
		if ( is_feed() || is_admin() )
			return $link;

		$post = get_post( $id );

		if ( 'image/' == substr( $post->post_mime_type, 0, 6 ) ) {
		$link =  wp_get_attachment_url( $id );
		    	return $link; 
		} else 
			return $link;
	}
add_filter( 'attachment_link', 'curationnews_attachment_link', 10, 2 );

function curationnews_class_attachment_link($html){
    $postid = get_the_ID();
    $html = str_replace('<a','<a class="immodal"',$html);
    return $html;
}
add_filter('wp_get_attachment_link','curationnews_class_attachment_link',10,3);

function curationnews_img_modal ($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([A-Za-z0-9\/_\.\~\:-]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)>/i";
	$replacement = '<a$1href=$2$3$4$5$6 class="immodal">';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}
add_filter('the_content', 'curationnews_img_modal');

/**
 * Bootstrap:  Register Custom Navigation Walker
 */
require_once('inc/wp_bootstrap_navwalker.php');
 
function curationnews_thumbnail($pID,$thumb='medium') { 
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
$imgsrc = '<a href="'. get_permalink().'"><img src="'.$imgsrc.'" alt="'.get_the_title().'" /></a>';
     	return $imgsrc;
		}  
	} 

function curationnews_sidebar_home($paged) {
         	if ($paged == 0){ ?>
	<div id="home-sidebar" class="sidebar boxy">
				<?php get_sidebar(); ?>
				</div>
<?php }
}

 /**
 * Replace rel="category tag" with rel="tag"
 * For W3C validation purposes only.
 */
function curationnews_replace_rel_category ($output) {
    $output = str_replace(' rel="category tag"', ' rel="tag"', $output);
    return $output;
}

add_filter('wp_list_categories', 'curationnews_replace_rel_category');
add_filter('the_category', 'curationnews_replace_rel_category');

 // Comment Layout

function curationnews_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

		<?php if ('1' == $show_avatars = get_option('show_avatars')) { ?>
		<div class="comment-avatar"><?php echo get_avatar(get_comment_author_email(), '48'); ?></div>
		<?php } ?>
		<div class="comment-content<?php if ($show_avatars == '1') { echo ' comment-content-with-avatar'; } ?>">
			
			<strong><span <?php comment_class(); ?>><?php comment_author_link() ?></span></strong> / <?php comment_date('j M Y g:ia'); ?> <a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e('Comment Permalink', 'curationnews'); ?>">#</a> <?php edit_comment_link('<i class="icon-pencil"></i>','','');?>
			<?php if ($comment->comment_approved == '0') : ?>
			<br /><em><?php _e('Your comment is awaiting moderation.', 'curationnews'); ?></em>
			<?php endif; ?>
	
			<?php comment_text() ?>
			<?php comment_reply_link(array('reply_text' => __('<i class="icon-reply"></i> Reply', 'curationnews'), 'depth' => $depth, 'max_depth'=> $args['max_depth'])) ?>
        </div>
	<?php
}

// Fonts
require_once("inc/curationnews-fonts.php");

/**
 * Title tag filter
 */
function curationnews_title_filter( $title, $sep, $seplocation ) {
    // get special index page type (if any)
    if( is_category() ) $type = 'Category';
    elseif( is_tag() ) $type = 'Tag';
    elseif( is_author() ) $type . 'Author';
    elseif( is_date() || is_archive() ) $type = 'Archives';
    else $type = false;
 
    // get the page number
    if( get_query_var( 'paged' ) )
        $page_num = get_query_var( 'paged' ); // on index
    elseif( get_query_var( 'page' ) )
        $page_num = get_query_var( 'page' ); // on single
    else $page_num = false;
 
    // strip title separator
    $title = trim( str_replace( $sep, '', $title ) );
     
    // determine order based on seplocation
    $parts = array( get_bloginfo( 'name' ), $type, $title, $page_num );
    if( $seplocation == 'left' )
        $parts = array_reverse( $parts ); 
     
    // strip blanks, implode, and return title tag
    $parts = array_filter( $parts );
    return implode( ' ' . $sep . ' ', $parts );     
}
// call our custom wp_title filter, with normal (10) priority, and 3 args
add_filter( 'wp_title', 'curationnews_title_filter', 10, 3 );


function curationnews_customizer_preview_js() { 
	wp_enqueue_script( 'curationnews_customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'jquery','customize-preview' ), '20130916', true ); 
} 
add_action( 'customize_preview_init', 'curationnews_customizer_preview_js' );

add_action('customize_register', 'curationnews_customize_register');
function curationnews_customize_register( $wp_customize )
{
	require get_template_directory() . '/ybi-customizer-awesome/ybi-customizer-awesome.php';

	// header image
	$wp_customize->add_section(
		'curationnews_header_image_options',
		array(
			'title'		=> __('Header Image & Options', 'curationnews'),

		)
	);
	
   	$wp_customize->add_setting(
		'curationnews_remove_title_tagline',
		array(
			'default'	=> false,
			'transport'	=> 'refresh',
		)
	);
	 
	$wp_customize->add_control(
		'curationnews_remove_title_tagline',
		array(
			'label' => __('Remove Title & Tagline', 'curationnews'),
			'section' => 'curationnews_header_image_options',
			'settings' => 'curationnews_remove_title_tagline',
			'type' => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'curationnews_menu_top_padding',
		array(
			'default' => '0',
		)
	);
	
	
	// we call this to include the CSS styling for the slider
	$wp_customize->add_setting( 'css_include', array('default' => '',));
	$wp_customize->add_control( new YBI_IncludeCSS( $wp_customize, 'css_include', 
		array('label'   => '','section' => 'curationnews_header_image_options','settings'   => 'css_include',) ) 
	);
	$ybi_control_name = 'curationnews_menu_top_padding';
	$wp_customize->add_setting( $ybi_control_name, array('default' => '0','transport'=>'postMessage') );
	$wp_customize->add_control(new YBI_Slider( $wp_customize, $ybi_control_name, 
		array('label'   => 'Menu Top Padding','section' => 'curationnews_header_image_options','settings'   => $ybi_control_name), 
		array('slider_min' => -1, 'slider_max' => 20, 'can_edit' => false)));
	
	$wp_customize->add_section('curationnews_customcss_options',array('title'=> __('Custom CSS', 'curationnews'), 'priority' => 100));	
	$wp_customize->add_setting( 'curationnews_customcss', array('default' => '',) );
 	$wp_customize->add_control( new YBI_Textarea_Control( $wp_customize, 'curationnews_customcss', array(
		'label'   => 'Enter Custom CSS',
		'section' => 'curationnews_customcss_options',
		'settings'   => 'curationnews_customcss',
		) ) 
	);

} //curationnews_customize_register

function curationnews_customize_css()
{

	$curationnews_menu_top_padding = get_theme_mod('curationnews_menu_top_padding', '0');
	$curationnews_customcss = get_theme_mod('curationnews_customcss', '');

?>
	<style type="text/css">
	#nav-main { padding-top: <?php echo $curationnews_menu_top_padding; ?>px;}
	<?php echo $curationnews_customcss; ?>
	</style>
<?php
}
add_action('wp_head', 'curationnews_customize_css');
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