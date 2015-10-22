<?php get_header(); ?>

	<div id="post">
<?php
	while(have_posts()) :
			
			the_post();
		
			$displayTags = (bool) get_theme_mod('curationflux_display_tags', true);
?>
			<div class="post-content clear">
				<h1><?php the_title(); ?></h1>
                <?php the_post_thumbnail('large', array('class' => 'post-thumb single_post_thumb_large', 'alt' => htmlspecialchars(strip_tags(get_the_title())))); ?>
				<?php the_content(); ?>
				<?php
					wp_link_pages(array(
						'before'           => '<p class="tr post-pages">' . __('Pages:', 'curationflux'),
						'after'            => '</p>',
						'nextpagelink'     => __('Next page', 'curationflux'),
						'previouspagelink' => __('Previous page', 'curationflux'),
					));
				?>
				<?php if($displayTags) : ?><?php the_tags('<div id="tags">', ', ', '</div>'); ?><?php endif; ?>
			</div>
			<?php comments_template(); ?>
<?php endwhile; ?>
	</div>
	
	<?php if(!is_404()) get_sidebar('single'); ?>

<?php get_footer(); ?>