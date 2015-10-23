<?php
/**
 * Created by PhpStorm.
 * User: Archie22is
 * Date: 15/10/23
 * Time: 12:11 PM
 */



add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/css/custom.css' );
}
