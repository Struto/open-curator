<?php get_header(); ?>

    <section class="content">


        <?php get_template_part('inc/page-title'); ?>


        <!-- Featured external YouTube videos -->
        <div class="feat-vids pad">

            <div class="entry">
                <h2><?php printf( __( "'Nature is Speaking': Will we listen?", 'curation-hue' ) ); ?></h2>

                <p>In a recent video series from Conservation International, A-list actors including Julia Roberts,
                    Harrison Ford and Kevin Spacey personify nature. The aim of the series is to make it clear to
                    everyone that the planet has been evolving for millions of years and will continue to do so,
                    with or without humans. It is our choice to address environmental issues and change our ways
                    to ensure we play a part in its future.</p>
                <br>
                <div class="videos-container">

                    <div id="part-one" class="work">
                        <div id="harrison-ford" class="box col4">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/rM6txLtoaoc?autoplay=1">
                                <h2>Harrison Ford is</h2>
                                <h1>The Ocean</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="kevin-spacey" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/jBqMJzv4Cs8?autoplay=1">
                                <h2>Kevin Spacey is</h2>
                                <h1>The Rainforest</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="ian-somerhalder" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/lVMV3StvLCs?autoplay=1">
                                <h2>Ian Somerhalder is</h2>
                                <h1>Coral Reef</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="lupita-nyongo" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/0_OxI2JZex4?autoplay=1">
                                <h2>Lupita Nyong'o is</h2>
                                <h1>Flower</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="edward-norton" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/Dor4XvjA8Wo?autoplay=1">
                                <h2>Edward Norton is</h2>
                                <h1>Soil</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="julia-roberts" class="box col4">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/WmVLcj-XKnM?autoplay=1">
                                <h2>Julia Roberts is</h2>
                                <h1>Mother Nature</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="edward-norton" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/Dor4XvjA8Wo?autoplay=1">
                                <h2>Edward Norton is</h2>
                                <h1>Soil</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
                        <div id="penelope-cruz" class="box col2">
                            <a class="various fancybox.iframe" href="http://www.youtube.com/embed/fwV9OYeGN88?autoplay=1">
                                <h2>Penelope Cruz is</h2>
                                <h1>Water</h1>
                                <i class="fa fa-play"></i>
                            </a>
                        </div>
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