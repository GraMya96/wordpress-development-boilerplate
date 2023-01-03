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
 * Defer all JS scripts expect backend JS (WP-Admin) and jQuery
 */
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );

function defer_parsing_of_js( $tag ) {
    if ( is_user_logged_in() ) return $tag; //don't break WP Admin
    if ( strpos( $tag, '.js' ) === FALSE ) return $tag;
    if ( strpos( $tag, 'jquery.js' ) ) return $tag;
    return str_replace( ' src', ' defer src', $tag );
}


/**
 * Defer all CSS (for now, only in the Homepage), excluding homepage.css and the
 * theme general style.css file
 */
add_filter( 'style_loader_tag', 'defer_non_critical_styles', 10, 4 );

function defer_non_critical_styles( $html, $handle, $href, $media ) {
    if ( !is_admin() && is_front_page() && !str_contains( $href, 'homepage' ) && !str_contains( $href, 'style.css' ) ) {
        $html = '<link rel="stylesheet" href="' . $href . '" media="print" onload="this.media=\'all\'; this.onload=null;">';
    }
    return $html;
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
