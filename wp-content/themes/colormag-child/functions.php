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

add_action( 'wp_enqueue_scripts', 'load_custom_styles' );
function load_custom_styles() {
    wp_enqueue_style( 'parent-css', get_template_directory_uri() . '/style.css' );            // default css
    wp_enqueue_style( 'child-css', get_stylesheet_directory_uri() . '/css/custom.css' );      // child theme css

    wp_enqueue_script('child-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ) );
}


