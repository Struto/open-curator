<?php get_header(); ?>

    <section class="content">


        <?php get_template_part('inc/page-title'); ?>


        <!-- Featured external YouTube videos -->
        <div class="feat-vids pad">

            <h2 class="video-widgettitle"><?php printf( __( 'Featured Videos', 'curation-hue' ) ); ?></h2>

            <ul class="grid effect-5" id="grid">
                <li>
                    <a href="http://drbl.in/fWMM">
                        <img src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg">
                    </a>
                </li>
                <li>
                    <a href="http://drbl.in/fWPV">
                        <img src="http://www.joomlaworks.net/images/demos/galleries/abstract/7.jpg" >
                    </a>
                </li>
                <li><a href="http://drbl.in/fWMT"><img src="images/4.jpg"></a></li>
                <li><a href="http://drbl.in/fQdt"><img src="images/12.png"></a></li>
                <li><a href="http://drbl.in/fHaa"><img src="images/13.png"></a></li>
                <li><a href="http://drbl.in/gXMo"><img src="images/10.png"></a></li>
                <li><a href="http://drbl.in/gXMn"><img src="images/9.jpg"></a></li>
                <li><a href="http://drbl.in/fzYo"><img src="images/2.jpg"></a></li>
            </ul>

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