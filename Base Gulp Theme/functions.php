<?php
/**
 * @package WordPress
 * @subpackage Test
 * @since 1.0
*/
require_once ( 'inc/Dump.class.php' );
require_once ( 'inc/acf.php' );
require_once ( 'inc/backend-removals.php' );
require_once ( 'inc/custom-functions.php' );
require_once ( 'inc/enqueues.php' );
require_once ( 'inc/image-sizes.php' );
require_once ( 'inc/admin-css.php' );


/**
 * Theme Setup
*/
if ( ! function_exists( 'theme_setup' ) ) {

	function theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		*/
		load_theme_textdomain( 'stnm', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		*/
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
        */
		add_theme_support( 'post-thumbnails' );

        /*
		 * Add Menu support in Appaerance
        */
		add_theme_support( 'menus' );

		/*
         * Register Primary Menu
        */
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'stnm' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
        */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
    }
}

add_action( 'after_setup_theme', 'theme_setup' );



/**
 * Include all post types create via wp-cli
*/
foreach (glob(get_template_directory() . "/post-types/*.php") as $filename)
{
    include $filename;
}


