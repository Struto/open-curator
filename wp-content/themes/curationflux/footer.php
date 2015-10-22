	</div><?php /* #main */ ?>
	
	<?php
		$displayFooter = (bool) get_theme_mod('curationflux_display_footer', true);
		if(!is_single() || $displayFooter) :
	?>
	<div id="footer" class="wrap">
		<?php if(!is_404()) get_sidebar('footer'); ?>
	</div>
	<?php endif; ?>
	
	<?php /* Keep the theme attribution to share a little love. If you want. No pressure! */ ?>
	<small class="themeinfo"><a href="http://www.curationtraffic.com/curationflux" title="CurationFlux Wordpress Theme" target="_blank">CurationFlux Theme</a></small>
	
</div><?php /* #page */ ?>
</div><?php /* #page-wrap */ ?>


<?php wp_footer(); ?>
</body>
</html>