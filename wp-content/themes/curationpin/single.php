<?php
/**
 * Single post template
 <div class="right_sidebar_w masonary-brick">
			<?php get_sidebar('right'); ?>
		</div>
 */
?>
<?php get_header(); ?>
<?php 
$ybi_no_display_date = false;
$ybi_no_display_author = false;
if(function_exists('ybiproducts_customize_register'))
{
	$ybi_no_display_date = (bool) get_theme_mod('ybi_no_display_date', false); 
	$ybi_no_display_author = (bool) get_theme_mod('ybi_no_display_author', false); 
}
?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>     
<div style="width: 100%; text-align:center">
   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if ( has_post_thumbnail() ) { ?>
  <div class="curationpin-image"><?php the_post_thumbnail( 'detail-image' );  ?></div>
  <div class="curationpin-category"><p><?php the_category(', ') ?></p></div>
          <div class="post-prev"><?php previous_post_link('%link', '&larr;'); ?></div>
          <div class="post-next"><?php next_post_link('%link', '&rarr;'); ?></div>
            <div class="curationpin-copy">
<?php } else { ?>           
      <div class="post-nav">


      </div>  
              
            <div class="curationpin-copy">
              <div class="curationpin-category"><p><?php the_category(', ') ?></p></div>
<?php } ?>
                <h1><?php the_title(); ?></h1>
                 <p class="curationpin-meta"><?php if(!$ybi_no_display_author) : _e( 'By', 'curationpin' ); ?> <?php the_author(); endif; ?><?php if(!$ybi_no_display_author && !$ybi_no_display_date) : ?>, <?php endif; ?>
				 <?php if(!$ybi_no_display_date) : the_time(get_option('date_format')); endif; ?></p>
           		 <?php the_content('Read more'); ?> 

		<div class="pagelink"><?php wp_link_pages(); ?></div>                
		<div class="posttags"><?php the_tags(); ?></div>

                <div class="clear"></div>
				<?php comments_template(); ?> 

                </div>
         
       </div>

		<?php endwhile; endif; ?>

        </div>
<?php get_footer(); ?>


