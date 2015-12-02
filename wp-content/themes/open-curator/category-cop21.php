<?php
/**
 * Created by PhpStorm.
 * User: Archie22is
 * Date: 15/12/02
 * Time: 9:47 AM
 */
?>

<?php get_header(); ?>

    <section class="content">


        <?php get_template_part('inc/page-title'); ?>


        <!-- Featured external YouTube videos -->
        <div class="cop21-featured pad">

            <div class="featured">
                <?php query_posts('p=40'); ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="entry">
                        <h2><?php the_title(); ?></h2>
                        <?php the_content(); ?>
                    </div>
                <?php endwhile;?>
            </div>

        </div>


        <!-- Rest of the videos or default categories -->
        <div class="pad group">

            <h2 class="video-widgettitle"><?php printf( __( 'COP21 Featured', 'curation-hue' ) ); ?></h2>

            <?php if ((category_description() != '') && !is_paged()) : ?>
                <div class="notebox">
                    <?php echo category_description(); ?>
                </div>
            <?php endif; ?>

            <?php if ( have_posts() ) : ?>

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