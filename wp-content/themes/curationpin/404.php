<?php
/**
 * Error page displayed when no results are found
 */

?>

<?php get_header(); ?>

   		<div class="type-page">
                                
       			<div class="curationpin-copy">
                
					<h1><?php _e( '404: Page or File Not Found', 'curationpin') ?></h1>
			
						<p><?php _e( 'Oops! It seems you stumbled on something that does not exist or was moved', 'curationpin') ?></p>
					
					<h2><?php _e( 'Need help?', 'curationpin') ?></h2>

					<p><?php _e( 'You might try the following:', 'curationpin') ?></p>
					<ul> 
						<li><?php _e( 'Check spelling', 'curationpin') ?></li>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>/"><?php _e( 'Return to  home page', 'curationpin') ?></a></li> 
						<li><?php _e( 'Click ', 'curationpin') ?> <a href="javascript:history.back()"><?php _e( 'Return button', 'curationpin') ?></a></li>
					</ul>
		      
         		</div>
                              
       </div>
              
<?php get_footer(); ?>
