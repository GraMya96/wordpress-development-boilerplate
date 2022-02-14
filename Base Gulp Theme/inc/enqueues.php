<?php
/**
 * Styles and Scripts
*/
add_action( 'wp_enqueue_scripts', 'theme_styles_and_scripts' );

function theme_styles_and_scripts() {
        // CSS
        wp_register_style('main-style', get_template_directory_uri() . '/style.css', array(), 'all');
        wp_register_style('tailwind-css', get_template_directory_uri() . '/dist/css/base/tailwind.css', array(), 'all');

        wp_enqueue_style('main-style');
        wp_enqueue_style('tailwind-css');

        // JS
        wp_enqueue_script('main-js', get_template_directory_uri() . '/main.min.js', array(), '', true);
}
