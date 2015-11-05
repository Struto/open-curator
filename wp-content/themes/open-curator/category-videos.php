<?php get_header(); ?>

    <section class="content">


        <?php get_template_part('inc/page-title'); ?>


        <!-- Featured external YouTube videos -->
        <div class="feat-vids pad">

            <h2 class="video-widgettitle"><?php printf( __( 'Featured Videos', 'curation-hue' ) ); ?></h2>

            <div class="videos-container">

                <div id="part-one" class="work">
                    <div id="harrison-ford" class="box col4">
                        <a class="various fancybox.iframe" href="">
                            <h2>Harrison Ford is</h2>
                            <h1>The Ocean</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="kevin-spacey" class="box col2">
                        <a class="various fancybox.iframe" href="">
                            <h2>Kevin Spacey is</h2>
                            <h1>The Rainforest</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="ian-somerhalder" class="box col2">
                        <a class="various fancybox.iframe" href="#">
                            <h2>Ian Somerhalder is</h2>
                            <h1>Coral Reef</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="lupita-nyongo" class="box col2">
                        <a class="various fancybox.iframe" href="#">
                            <h2>Lupita Nyong'o is</h2>
                            <h1>Flower</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="edward-norton" class="box col2">
                        <a class="various fancybox.iframe" href="#">
                            <h2>Edward Norton is</h2>
                            <h1>Soil</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="julia-roberts" class="box col4">
                        <a class="various fancybox.iframe" href="#">
                            <h2>Julia Roberts is</h2>
                            <h1>Mother Nature</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="edward-norton" class="box col2">
                        <a class="various fancybox.iframe" href="">
                            <h2>Edward Norton is</h2>
                            <h1>Soil</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                    <div id="penelope-cruz" class="box col2">
                        <a class="various fancybox.iframe" href="https://www.youtube.com/watch?v=fwV9OYeGN88?autoplay=1">
                            <h2>Penelope Cruz is</h2>
                            <h1>Water</h1>
                            <i class="fa fa-play"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>


        <!-- Rest of the videos or default categories -->
        <div class="pad group">

            <h2 class="video-widgettitle"><?php printf( __( 'General Videos', 'curation-hue' ) ); ?></h2>

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