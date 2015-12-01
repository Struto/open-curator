<?php
/*
Template Name: COP Template
*/
?>
<?php get_header(); ?>

    <section class="content">

        <?php get_template_part('inc/page-title'); ?>

        <div class="pad group">

            <?php if ( have_posts() ) : ?>

                <?php
                // show something else if home page
                if ( is_home() ) {  //return filtered loop
                    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                    $my_query = new WP_Query( array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                            'page' => $paged,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('cop'),
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


            <?php else :  ?>
                <?php echo "Empty"; ?>

            <?php endif;?>

        </div><!--/.pad-->

    </section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>