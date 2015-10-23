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
 * Add custom child theme CSS
 *
 */
