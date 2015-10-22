<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />

<?php $blogTitle = get_bloginfo('name'); ?>
<title><?php wp_title('&middot;', true, 'right'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php if(is_singular()) wp_enqueue_script('comment-reply', false, array(), false, true); ?>
<?php wp_head(); 
	$displayHeaderImage = (bool) get_theme_mod('curationflux_header_image', true);
	$removeTitleTagline = (bool) get_theme_mod('curationflux_remove_title_tagline', true);
?>
</head>

<body <?php body_class('lt-480' . (is_single() && is_active_sidebar('sidebar-single') ? ' has-single-sidebar' : '')); ?>>
<div id="page-wrap">
<div id="page">

	<a id="mobile-menu" href="#menu"></a>
	
	<div id="header">
		<div class="wrap">
            	<?php if($displayHeaderImage) : ?><a href="<?php echo home_url(); ?>" title="<?php echo str_replace('"', '\'', $blogTitle); ?>"><div id="header_image"><img src="<?php echo get_theme_mod('curationflux_header_image', __('', 'curationflux')); ?>" class="responsive-image" /></a></div><?php endif; ?>
			<div class="inner">

				<?php if(!$removeTitleTagline) : ?><?php if(is_home()) : ?><h1 id="blog-title"><?php else : ?><strong id="blog-title"><?php endif; ?><a href="<?php echo home_url(); ?>" title="<?php echo str_replace('"', '\'', $blogTitle); ?>"><?php echo $blogTitle ?></a><?php if(is_home()) : ?></h1><?php else : ?></strong><?php endif; ?>
                <?php endif; ?>
				<div id="menu">
				<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'container' => false
					));
				?>
				</div>
			</div>
		</div>
	</div>

	<div id="main" class="wrap clear">