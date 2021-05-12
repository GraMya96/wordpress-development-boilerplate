<?php
// Css Enqueues
add_action( 'wp_enqueue_scripts', 'theme_styles' );
function theme_styles() {
	$themeInfo = wp_get_theme();
	$version = $themeInfo->get( 'Version' );
	$pluginsurl = plugins_url();

        // CSS
        wp_register_style('main-style', get_template_directory_uri() . '/style.css', array(), $version, 'all');
        wp_enqueue_style('main-style');

        // JS
        wp_enqueue_script('main-js', get_template_directory_uri() . '/main.min.js', array(), $version, true);
}

// Add menu navigation in "Appareance"
add_theme_support( 'menus' );