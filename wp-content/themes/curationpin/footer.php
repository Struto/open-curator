<?php
/**
 * The template for displaying the footer.
 */

?>

<?php if ( is_active_sidebar( 'curationpin_footer')) { ?>     
   <div id="footer-area">
			<?php dynamic_sidebar( 'curationpin_footer' ); ?>
        </div><!-- // footer area with widgets -->   
<?php }  ?>           
<footer class="site-footer">
	 <div id="copyright">
	 	<?php _e( 'Curation Pin Theme by', 'curationpin' ); ?> <a href="<?php echo esc_url( 'http://curationtraffic.com/curation-pin-theme' ); ?>" title="Curation Traffic" target="_blank"><?php _e( 'Curation Traffic', 'curationpin' ); ?></a> | 
		<?php _e( 'Copyright', 'curationpin' ) ?> <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>
	 </div><!-- // copyright -->   
</footer>     
</div><!-- // close wrap div -->   

<?php wp_footer(); ?>
	
</body>
</html>

