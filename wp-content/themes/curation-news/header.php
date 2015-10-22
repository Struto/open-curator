<!DOCTYPE html>
<!--[if IE 6]><html id="ie6" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html id="ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html id="ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
    <head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php wp_title(); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php if(of_get_option('curationnews_favicon')) { ?><link rel="shortcut icon" href="<?php echo of_get_option('curationnews_favicon'); ?>" />
	<?php } ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); 	?>
</head>
<body <?php body_class(); ?>>

 	<div id="topmenu" class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<i class="icon-bar"></i>
					<i class="icon-bar"></i>
					<i class="icon-bar"></i>
				</a>
              <?php 	$removeTitleTagline = (bool) get_theme_mod('curationnews_remove_title_tagline', true); ?>  

 			<a class="brand<?php if (of_get_option('curationnews_logo') != '') { echo ' logo'; } ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if (of_get_option('curationnews_logo') != '') { ?>
					<img src="<?php echo of_get_option('curationnews_logo') ?>" alt="Logo" style="max-height:40px;" />
				<?php } else {
					if(!$removeTitleTagline)
						bloginfo('name');
				}
				?>
				</a>
	 				<div id="nav-main" class="nav-collapse collapse">
					<?php 
                    wp_nav_menu( array(
	                    'theme_location' => 'top_nav',
                        'depth' => 3,
                        'container' => false,
                        'menu_class' => 'nav',
                        'fallback_cb' => false,
                        'walker' => new wp_bootstrap_navwalker()
					) ); ?>

<?php    if ('' != $prsearch = of_get_option('curationnews_search')) { ?>						
					<form class="navbar-search pull-right" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="text" class="input-medium search-query" placeholder="<?php _e('Search', 'curationnews'); ?>" name="s" id="s" value="<?php the_search_query(); ?>">
					</form>  
<?php } 
if ('' != $prrss = of_get_option('curationnews_rss')) { ?>			
					<a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Subscribe to our RSS Feed', 'curationnews'); ?>" class="social pull-right"><i class="fa fa-rss fa-lg"></i></a>					
	<?php } 
   if ('' != $google = of_get_option('curationnews_google')) { ?>
					<a href="<?php echo $google; ?>" title="<?php _e('Find us on Google+', 'curationnews'); ?>" class="social pull-right"><i class="fa fa-google-plus fa-lg"></i></a>
	<?php } 

if ('' != $linkedin = of_get_option('curationnews_linkedin')) { ?>
					<a href="<?php echo $linkedin; ?>" title="<?php _e('Find us on Linkedin', 'curationnews'); ?>" class="social pull-right"><i class="fa fa fa-linkedin fa-lg"></i></a>	
						<?php } 
	if ('' != $pinterest = of_get_option('curationnews_pinterest')) { ?>
					<a href="<?php echo $pinterest; ?>" title="<?php _e('Find us on Pinterest', 'curationnews'); ?>" class="social pull-right"><i class="fa fa-pinterest fa-lg"></i></a>	
						<?php } 						
if ('' != $twitter = of_get_option('curationnews_twitter')) { ?>
					<a href="<?php echo $twitter; ?>" title="<?php _e('Follow us on Twitter', 'curationnews'); ?>" class="social pull-right"><i class="fa fa-twitter fa-lg"></i></a>
					<?php } 
		if ('' != $facebook = of_get_option('curationnews_facebook')) { ?>
					<a href="<?php echo $facebook; ?>" title="<?php _e('Find us on Facebook', 'curationnews'); ?>" class="social pull-right"><i class="fa fa-facebook fa-lg"></i></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php if ('' != $curationnews_leader_ad = of_get_option('curationnews_leader_ad')) : ?>
<div id="leader-wrapper" class="container"> 
	<div class="row-fluid">					
		<div  class="span12">
				  <div id="leader-ad"><?php echo $curationnews_leader_ad; ?></div> 
		</div>	
	</div>
</div>
<?php endif; ?>