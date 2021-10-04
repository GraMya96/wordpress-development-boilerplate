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
 * Include all post types create via wp-cli
*/
foreach (glob(get_template_directory() . "/post-types/*.php") as $filename)
{
    include $filename;
}

