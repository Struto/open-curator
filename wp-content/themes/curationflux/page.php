<?php get_header(); ?>

	<div id="post">
		<div class="post-content clear">
<?php while(have_posts()) : the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
<?php endwhile; ?>
		</div>
		<?php comments_template(); ?>
	</div>

<?php get_footer(); ?>