<?php
/*
Template Name: COP Template
*/
?>
<?php get_header(); ?>

    <section class="content">

        <?php get_template_part('inc/page-title'); ?>

        <div class="pad group">

            <!-- On the homepage where there is a featured piece -->
            <?php //get_template_part('inc/featured'); ?>

            <?php if ( have_posts() ) : ?>

                <h2 class="widgettitle">COP Articles</h2>
                <?php
                // show something else if home page
                if ( is_page('cop-paris') ) {  //return filtered loop
                    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                    $cop_news_query = new WP_Query( array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                            'page' => $paged,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('cop-news'),
                                ),
                            ),
                        )
                    );
                    ?>

                    <div class="post-list group">
                        <?php //$i = 1; echo '<div class="post-row">'; while ( have_posts() ): the_post(); ?>
                        <?php $i = 1; echo '<div class="post-row">'; while ( $cop_news_query->have_posts() ): $cop_news_query->the_post(); ?>

                            <?php get_template_part('content'); ?>
                            <?php if($i % 2 == 0) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
                    </div><!--/.post-list-->


                    <!-- Custom Homepage Bottom Widget Area -->
                    <div class="home-bottom">
                    <?php echo " "; ?>
                    </div>

                <?php } ?>

                <?php wp_reset_query();     // reset loop ?>

            <?php endif; ?>



            <!-- COP Videos -->
            <div class="cop-videos">

                <h2 class="widgettitle">COP Videos</h2>
                <?php
                    $cop_videos = new WP_Query( array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                            'page' => $paged,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('cop-videos'),
                                ),
                            ),
                        )
                    );
                ?>

                <div class="post-list group">
                    <?php //$i = 1; echo '<div class="post-row">'; while ( have_posts() ): the_post(); ?>
                    <?php $i = 1; echo '<div class="post-row">'; while ( $cop_videos->have_posts() ): $cop_videos->the_post(); ?>

                        <?php get_template_part('content'); ?>
                        <?php if($i % 2 == 0) { echo '</div><div class="post-row">'; } $i++; endwhile; echo '</div>'; ?>
                </div><!--/.post-list-->


                <?php //$cop_videos; ?>

            </div>


        </div><!--/.pad-->

    </section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>