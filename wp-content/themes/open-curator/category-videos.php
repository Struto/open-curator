<?php get_header(); ?>

    <section class="content">


        <?php get_template_part('inc/page-title'); ?>


        <!-- Featured external YouTube videos -->
        <div class="feat-vids pad">

            <h2 class="video-widgettitle"><?php printf( __( 'Featured Videos', 'curation-hue' ) ); ?></h2>

            <div class="videos-container">

                <div id="part-one" class="work">
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col4">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                    <div class="box col2">
                        <h2>Harrison Ford is</h2>
                        <h1>The Ocean</h1>
                        <a href="#">
                            play
                        </a>
                    </div>
                </div>

            </div>


            <!--
            <ul id="masonry" class="grid effect-5" id="grid">
                <li class="grid-item">
                    <a href="http://drbl.in/fWMM">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg">
                    </a>
                </li>
                <li class="grid-item">
                    <a href="http://drbl.in/fWPV">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg" >
                    </a>
                </li>
                <li class="grid-item">
                    <a href="http://drbl.in/fWMM">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg">
                    </a>
                </li>
                <li class="grid-item">
                    <a href="http://drbl.in/fWPV">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg" >
                    </a>
                </li>
                <li class="grid-item">
                    <a href="http://drbl.in/fWMM">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg">
                    </a>
                </li>
                <li class="grid-item">
                    <a href="http://drbl.in/fWPV">
                        <img class="" src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg" >
                    </a>
                </li>
            </ul>
            -->
        </div>


        <!-- Rest of the videos or default categories -->
        <div class="pad group">

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