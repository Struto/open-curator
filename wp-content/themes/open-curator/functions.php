<?php
/**
 * Created by PhpStorm.
 * User: Archie22is
 * Date: 15/10/23
 * Time: 12:11 PM
 */




/*
 * Add default parent theme CSS
 *
 */

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );        // default css
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/custom.css' );    // child theme css

    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ) );
}


/*
 * Load theme files
 *
 */

if ( ! function_exists( 'alx_load' ) ) {

    function alx_load() {
        // Load theme languages
        load_theme_textdomain( 'curation-hue', get_template_directory().'/languages' );

        // Load theme options and meta boxes
        load_template( get_template_directory() . '/functions/theme-options.php' );
        load_template( get_template_directory() . '/functions/meta-boxes.php' );

        // Load custom widgets
        load_template( get_template_directory() . '/functions/widgets/alx-tabs.php' );
        load_template( get_template_directory() . '/functions/widgets/alx-video.php' );
        load_template( get_template_directory() . '/functions/widgets/alx-posts.php' );

        // Load custom shortcodes
        load_template( get_template_directory() . '/functions/shortcodes.php' );

        // Load dynamic styles
        load_template( get_template_directory() . '/functions/dynamic-styles.php' );

        // Load TGM plugin activation
        load_template( get_template_directory() . '/functions/class-tgm-plugin-activation.php' );
    }

}
add_action( 'after_setup_theme', 'alx_load' );