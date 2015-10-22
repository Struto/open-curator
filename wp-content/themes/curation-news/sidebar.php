<div id="sidebar-home" class="sidebar">
	<!-- breadcrumb -->
 <?php if (is_author()) : ?>  
     <div class="widget">        <div class="posttitle">
   <?php   $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));?>
  <i class="icon-user"></i> <?php _e('Posts By: ', 'curationnews');  echo $curauth->display_name; ?>	                                                      
       </div>         </div>
            <?php elseif (is_search()) : ?>
        <div class="widget">       <div class="posttitle">
             <i class="icon-search"></i>   <?php _e('Search results: ', 'curationnews');  the_search_query() ?> 
        </div>        </div>
            <?php elseif (is_category()) : ?>
        <div class="widget">       <div class="posttitle">
                 <i class="icon-folder-open"></i>   <?php _e('Posts in category: ', 'curationnews'); $category = get_queried_object(); echo $category->name; ?>
       </div>         </div>
            <?php elseif (is_tag()) : ?>
         <div class="widget">      <div class="posttitle">
  <i class="icon-tags"></i>    <?php _e('Browsing posts tagged: ', 'curationnews');  $tag = get_queried_object(); echo $tag->name; ?>
           </div>     </div>
            <?php elseif (is_archive()) : ?>
        <div class="widget">       <div class="posttitle">
                    <?php _e('Browsing archived posts: ', 'curationnews'); ?>
             </div>   </div>
            <?php endif; ?> 
<!-- // posttitle -->

<?php if ( dynamic_sidebar('sidebar-home') );?>
</div>