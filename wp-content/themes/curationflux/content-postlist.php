		<div id="post-list">
			<div id="col1" class="col"></div>
			<div id="col2" class="col"></div>
			<div id="col3" class="col"></div>
			<div id="col4" class="col"></div>
<?php
		$i = 0;
		while(have_posts()) :
			the_post();
			$displayExcerpt = (bool) get_theme_mod('curationflux_display_excerpts', true);
			$displayMoreLink = (bool) get_theme_mod('curationflux_display_more', true);
			
			$classes = array();
			if(!$displayExcerpt) $classes[] = 'no-excerpt';
			if(!$displayMoreLink) $classes[] = 'no-more';
?>
			<div id="post-<?php echo $i++; ?>" <?php post_class(implode(' ', $classes)); ?> title="<?php echo htmlspecialchars(strip_tags(get_the_title())); ?>">
				<?php if(is_sticky()) : ?><span class="sticky-icon"></span><?php endif; ?>
				<?php if(!$displayMoreLink) : ?><a href="<?php the_permalink(); ?>" title="<?php echo htmlspecialchars(strip_tags(get_the_title())); ?>" class="no-more"><?php endif; ?>
<?php if(curationflux_thumbnail($post->ID) !='') :  echo curationflux_thumbnail($post->ID); endif; ?>


				<h2><?php the_title(); ?></h2>
				<?php if($displayExcerpt) : ?><span><?php echo strip_tags(get_the_excerpt(), '<strong><em>'); ?></span><?php endif; ?>
				<?php if($displayMoreLink) : ?><span class="tr"><a href="<?php the_permalink(); ?>" title="<?php echo htmlspecialchars(strip_tags(get_the_title())); ?>">
				<?php echo get_theme_mod('curationflux_more_link', __('more&hellip;', 'curationflux')); ?></a></span><?php endif; ?>
				<?php if(!$displayMoreLink) : ?></a><?php endif; ?>
			</div>
<?php
		endwhile;
?>
		</div>
	<?php if ( function_exists('wp_pagenavi') ): ?>
		<?php wp_pagenavi(); ?>
	<?php else: 
			global $posts_per_page, $wp_query;
			$paged = get_query_var('paged') ? get_query_var('paged') : 1;
			if($wp_query->found_posts > $posts_per_page || $paged > 1) :
		?>
		<div id="post-navi">
			<div class="prev"><?php next_posts_link(__('&laquo;&nbsp;Older posts', 'curationflux')); ?></div>
			<div class="next"><?php previous_posts_link(__('Newer posts&nbsp;&raquo;', 'curationflux')); ?></div>
		</div>
		<?php endif; ?><?php endif; ?>