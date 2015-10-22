<?php
/*
Template Name: Full Width Page
*/
get_header(); ?>
<div class="container">
	<div class="row">
		<div class="span12">
			<div class="row">
					<?php while (have_posts()) : the_post(); ?>
			
					<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrapper'); ?>>
                    <?php the_title( '<div class="h1-wrapper"><h1>', '</h1></div>' ); ?>
						<div class="post-content">
							<?php 	the_content();
							wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'curationnews') . '</strong>', 'after' => '</p>' ) ); ?>
							<div class="clearfix"></div>
						    <div class="post-meta-top">
						  <?php edit_post_link(__('Edit', 'curationnews'),'<i class="icon-pencil"></i> [ ',' ]</p>');
							?>
						     </div>
						</div>
						
						<div class="post-comments">
							<div class="post-comments-wrapper">
								<?php comments_template(); ?>
							</div>
						</div>
						
					</div>
					<?php endwhile; ?>
				</div>
				
			</div>
	</div>

  <div id="scroll-top"><a href="#"><i class="icon-chevron-up icon-2x"></i></a></div>
</div>

<?php get_footer(); ?>