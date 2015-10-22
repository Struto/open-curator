<?php
	get_header();

	if(have_posts()) :
	
		get_template_part('content', 'postlist');

	else :
	
		get_template_part('content', 'none');
	
	endif;

	get_footer();
?>
