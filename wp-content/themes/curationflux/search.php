<?php
	get_header();
	
	if(have_posts()) :

?>

		<div id="post">
			<div class="post-content post-list-content">
				<h1><?php printf(__('Search Results for %s', 'curationflux'), '<em>' . get_search_query() . '</em>'); ?></h1>
			</div>
		</div>

<?php
	
		get_template_part('content', 'postlist');

	else :
	
		get_template_part('content', 'none');
	
	endif;
	
	get_footer();
?>
