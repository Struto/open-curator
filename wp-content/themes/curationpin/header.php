<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>  
  	<meta charset="<?php bloginfo('charset'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php wp_title('&#124;', true, 'right'); ?></title>
<?php $blogTitle = get_bloginfo('name'); ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); 
	$displayHeaderImage = (bool) get_theme_mod('curationpin_header_image', true);
	?>
</head>
  <body <?php body_class(); ?>>
 	<!-- logo and navigation -->
 <nav id="site-navigation" class="main-nav" role="navigation">
    <div id="main-nav-wrapper"> 
                <div id="logo">
<?php if($displayHeaderImage) : ?><a href="<?php echo home_url(); ?>" title="<?php echo str_replace('"', '\'', $blogTitle); ?>">
<img src="<?php echo get_theme_mod('curationpin_header_image', __('', 'curationpin')); ?>" class="responsive-image main_logo" /></a><?php endif; ?>              
         </div>  
          <?php if ( has_nav_menu( 'main_nav' ) ) { ?>
          <?php wp_nav_menu( array( 'theme_location' => 'main_nav' ) ); ?>
          <?php } else { ?>
          <ul><?php wp_list_pages("depth=3&title_li=");  ?></ul>
          <?php } ?> 

    </div>
  </nav>  
<div class="clear"></div>
<div id="wrap">
  <div id="header"></div>
<?php $top_banner = get_theme_mod('top_banner', ''); ?>
<?php if($top_banner != '' && $top_banner != 'Enter banner or ad code') : ?>
  <div id="top_banner_w">
  	<div id="top_banner"><?php echo $top_banner; ?></div>
  </div>
  <?php endif; ?>