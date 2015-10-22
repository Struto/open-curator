<?php
/**
 * Theme index file
 */

?>

<?php get_header();

$ybi_display_date = (bool) get_theme_mod('ybi_display_date', true);
 ?>

<?php if (have_posts()) : ?>
<div id="post-area">

<?php while (have_posts()) : the_post(); ?>	

   		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		 <?php if(curationpin_thumbnail($post->ID) !='') { ?>
         <div class="curationpin-image"><a href="<?php the_permalink() ?>"><?php echo curationpin_thumbnail($post->ID); ?></a></div>
          <div class="curationpin-category"><p><?php the_category(', ') ?></p></div>
       
		  <?php } ?>
       			<div class="curationpin-copy"><h2><a class="front-link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                <?php if(!$ybi_display_date) : ?><p class="curationpin-date"><?php the_time(get_option('date_format')); ?>  </p><?php endif; ?>

                  <?php the_excerpt(); ?> 

               <p class="curationpin-link"><a href="<?php the_permalink() ?>">&rarr;</a></p>
         </div>
       </div>
       
<?php endwhile; ?>
</div>
<?php else : ?>

<article id="post-0" class="post no-results not-found">
        <header class="entry-header">
          <h1 class="entry-title"><?php _e( 'Nothing Found', 'curationpin' ); ?></h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'curationpin' ); ?></p>
          <?php get_search_form(); ?>
        </div><!-- .entry-content -->
</article><!-- #post-0 -->

<?php endif; ?>

	<?php if ( function_exists('wp_pagenavi') ): ?>
		<?php wp_pagenavi(); ?>
	<?php else: ?>
    <nav id="nav-below" class="navigation" role="navigation">
	<?php 
		$big = 999999999; // need an unlikely integer
		global $wp_query;
		echo paginate_links(array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'       => '?page=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'show_all'     => true,
			'end_size'     => 1,
			'mid_size'     => 2,
			'prev_next'    => True,
			'prev_text'    => __('« Previous'),
			'next_text'    => __('Next »'),
			'type'         => 'plain',
			'add_args'     => False,
			'add_fragment' => ''
		)); ?>
   </nav> 
    <?php endif; ?>
<?php get_footer(); ?>
