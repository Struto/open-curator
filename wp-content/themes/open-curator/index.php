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

                    $post_id = "";
                    $post_id = get_the_ID();
                    echo $post_id;
                    echo "Blah";



                    /*
                    if( is_category() ) {
                        $catName = single_cat_title("",false);
                        $catID = get_cat_ID($catName);

                        echo "Testing";
                        echo '<a href="'. get_category_link($catID) .'">'. $catName .' </a>';
                    }
                    */

                    /*
                    //all extra loop for the videos template
                    //if ( is_category( 'category-videos' ) || is_category( 'videos' ) ) {
                    if ( in_category( 'category-videos' ) || is_category( 'videos' ) ) {

                        echo "Videos page test";

                    }
                    */

                ?>


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