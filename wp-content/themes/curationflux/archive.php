<?php
	get_header();
	
	if(have_posts()) :
?>
		<div id="post">
			<div class="post-content post-list-content">
				<h1><?php
					if ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'curationflux' ), '<span>' . get_the_date() . '</span>' );
					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'curationflux' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'curationflux' ) ) . '</span>' );
					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'curationflux' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'curationflux' ) ) . '</span>' );
					else :
						_e( 'Archives', 'curationflux' );
					endif;
				?></h1>
			</div>
		</div>
		
<?php
		get_template_part('content', 'postlist');

	else :
	
		get_template_part('content', 'none');
	
	endif;
	
	get_footer();
?>
