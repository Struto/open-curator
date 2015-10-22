<?php if(is_active_sidebar('sidebar-single')) : ?>

<div id="sidebar-single">
	<div class="inner">
		<div id="scol1" class="col"></div>
		<div id="scol2" class="col"></div>
		<div id="scol3" class="col"></div>
		<div id="scol4" class="col"></div>
	</div>
	
	<?php dynamic_sidebar('sidebar-single'); ?>

</div>

<?php endif; ?>