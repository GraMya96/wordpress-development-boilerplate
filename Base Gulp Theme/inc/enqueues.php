<?php
/**
 * Styles and Scripts
*/
add_action( 'wp_enqueue_scripts', 'theme_styles_and_scripts' );

function theme_styles_and_scripts() {
        /* CSS */

        // style.css
        wp_register_style( 'main-style', get_template_directory_uri() . '/style.css', array(), 'all' );
        wp_enqueue_style( 'main-style' );

        // Registering all the Page Templates styles
        wp_register_style( 'homepage', get_template_directory_uri() . '/dist/css/homepage.css', array(), 'all' );

        // Enqueing all the Page Templates styles conditionally according to current template
        if( is_front_page() ) { // Homepage
            wp_enqueue_style( 'homepage' );
        }

        /* JS */

        // main.js
        wp_register_script( 'main-js', get_template_directory_uri() . '/dist/js/main.js', array(), '', true );
        wp_enqueue_script( 'main-js' );
}


/**
 * Load Google Fonts asynchronously from CDN.
 */
add_action( 'wp_head', 'wpdd_google_fonts' );

function wpdd_google_fonts() {

    $google_fonts_url = 'paste-google-fonts-url-here'; ?>

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin />

    <link rel="preload"
        as="style"
        href="<?php echo $google_fonts_url; ?>" />

    <link rel="stylesheet"
        href="<?php echo $google_fonts_url; ?>"
        media="print" onload="this.media='all'" />

    <noscript>
        <link rel="stylesheet"
            href="<?php echo $google_fonts_url; ?>" />
    </noscript>
<?php }
