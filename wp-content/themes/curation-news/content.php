<?php 
$ybi_no_display_date = false;
$ybi_no_display_author = false;
if(function_exists('ybiproducts_customize_register'))
{
	$ybi_no_display_date = (bool) get_theme_mod('ybi_no_display_date', false); 
	$ybi_no_display_author = (bool) get_theme_mod('ybi_no_display_author', false); 
}
?>
<div class="container" style="padding-top: 30px;">
	<div class="row">
		<div class="span9">
			<div class="row">
				<div id="double-left-column" class="span6 pull-right">
					<?php while (have_posts()) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrapper'); ?>>
					<?php the_title( '<div class="h1-wrapper"><h1>', '</h1></div>' ); ?>
                    <?php if ( has_post_thumbnail() ) {
					the_post_thumbnail('large');  
					} ?> 
						<div class="post-content">
<div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector=".immodal">
							<?php	the_content();		?>
</div>		
						<?php		wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'curationnews') . '</strong>', 'after' => '</p>' ) );	?>
							
							<div class="clearfix"></div>
						<div class="post-meta-top">
							<div class="pull-right"><?php edit_post_link(__('Edit', 'curationnews'),'<i class="icon-pencil"></i> ',''); ?></div>
                            <div class="pull-left">

                            <?php if(!$ybi_no_display_date) :  ?><i class="fa fa-calendar"></i>
							<?php echo get_the_date(); endif;?> &nbsp; <?php if(!$ybi_no_display_author) :  ?><i class="fa fa-user"></i> <?php the_author_posts_link(); endif; ?>
                            </div>
						</div>		
	<div class="category-tag">
	<?php if(get_the_category()){ ?>
<i class="icon-folder-open"></i> <?php the_category(', '); }?>  <?php the_tags(' <i class="icon-tags"></i> ',', '); ?>
	 </div>

						 <!--  modal-gallery is the modal dialog used for the image gallery -->
 <div id="modal-gallery" class="modal modal-gallery hide fade modal-fullscreen" tabindex="-1" >
         <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <p class="modal-title"></p>
          </div>	
    <div class="modal-body"> 
			<div class="modal-image"></div>
	</div>
</div>
                    <!-- // modal-gallery is the modal dialog used for the image gallery -->		
					
							<div id="navigation">
								<ul class="pager">
									<li class="previous"><?php previous_post_link('%link', __('<i class="fa fa-chevron-circle-left fa-lg"></i> %title', 'curationnews')); ?></li>
									<li class="next"><?php next_post_link('%link', __('%title <i class="fa fa-chevron-circle-right fa-lg"></i>', 'curationnews')); ?></li>
								</ul>
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
<div id="single-right-column" class="span3">
					<?php get_sidebar('left'); ?>
				</div>
			</div>
		</div>
<div class="span3">
			<?php get_sidebar('right'); ?>
		</div>
	</div>
  <div id="scroll-top"><a href="#"><i class="fa fa-chevron-up fa-3x"></i></a></div>
</div>
