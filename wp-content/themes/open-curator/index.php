<?php get_header(); ?>

    <section class="content">

        <?php get_template_part('inc/page-title'); ?>

        <div class="pad group">

            <?php get_template_part('inc/featured'); ?>

            <?php if ( have_posts() ) : ?>

                <?php
                    // show something else if home page
                    if ( is_home() ) {  //return filtered loop
                        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                        $my_query = new WP_Query( array(
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'posts_per_page' => 2,
                                'page' => $paged,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field'    => 'slug',
                                        'terms'    => array('featured'),
                                    ),
                                ),
                            )
                        );
                    ?>

                    <div class="post-list group">
                        <?php //$i = 1; echo '<div class="post-row">'; while ( have_posts() ): the_post(); ?>
                        <?php $i = 1; echo '<div class="post-row">'; while ( $my_query->have_posts() ): $my_query->the_post(); ?>

                            <?php get_template_part('content'); ?>
                            <?php if($i % 2 == 0) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
                    </div><!--/.post-list-->

                    <!-- Custom Homepage Bottom Widget Area-->
                    <div class="home-bottom">
                    <?php
                        dynamic_sidebar( 'homebotttomarea' );
                    } else {
                        echo " ";
                    } ?>
                    </div>
                    <!-- Homepage end of additional widgets -->
                <?php //endif; ?>



                <!-- Videos Exception -->
                <?php
                ?>
                <?php $catname = wp_title('', false); ?>
                <?php query_posts("category_name=$catname&showposts=10"); ?>
                <?php $posts = get_posts("category_name=$catname&numberposts=3&offset=0");
                foreach ($posts as $post) : start_wp(); ?>
                    <h1><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>

                    <div class="dateleft">
                        <p><span class="time"><?php the_time('F j, Y'); ?></span> <?php _e("by", 'studiopress'); ?> <?php the_author_posts_link(); ?> &nbsp;<?php edit_post_link(__('(Edit)', 'studiopress'), '', ''); ?> <br /> <?php _e("Filed under", 'studiopress'); ?> <?php the_category(', ') ?></p>
                    </div>

                    <div class="dateright">
                        <p><span class="icomment"><a rel="nofollow" href="<?php the_permalink(); ?>#comments"><?php comments_number(__('Leave a Comment', 'studiopress'), __('1 Comment', 'studiopress'), __('% Comments', 'studiopress')); ?></a></span></p>
                    </div>

                    <div class="clear"></div>
                    <?php the_excerpt(__('Read more', 'studiopress'));?>
                    <div class="clear"></div>

                    <div class="postmeta2">
                        <p><span class="tags">Tags: <?php the_tags('') ?></span></p>
                    </div>
                <?php endforeach; ?>
                





                <?php else :  // show something else if not home page ?>
                <div class="post-list group">

                    <?php $i = 1; echo '<div class="post-row">'; while ( have_posts() ): the_post(); ?>
                        <?php get_template_part('content'); ?>
                        <?php if($i % 2 == 0) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
                </div><!--/.post-list-->

                <?php get_template_part('inc/pagination'); ?>

            <?php endif; ?>


        </div><!--/.pad-->

    </section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>