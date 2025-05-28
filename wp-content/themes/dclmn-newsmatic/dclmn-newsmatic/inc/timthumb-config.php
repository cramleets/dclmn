<?php
/**
 * Allow a NAPCO WP Core site to hook in first.
 */
$napco_timthumb_config_file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/napco/config/napco-timthumb-config.php';
if ( is_file($napco_timthumb_config_file) ) include_once $napco_timthumb_config_file;

define( 'DEBUG_ON', FALSE );
define( 'DEBUG_LEVEL', 1 );
define( 'MEMORY_LIMIT', '500M' );    // Set PHP memory limit

define( 'ALLOW_EXTERNAL', TRUE );   // Allow image fetching from external websites. Will check against ALLOWED_SITES if ALLOW_ALL_EXTERNAL_SITES is false
define( 'ALLOW_ALL_EXTERNAL_SITES', FALSE );  // Less secure.
define( 'FILE_CACHE_ENABLED', TRUE );   // Should we store resized/modified images on disk to speed things up?
define( 'FILE_CACHE_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/timthumb-cache' );  // Directory where images are cached. Left blank it will use the system temporary directory (which is better for security)
define( 'BROWSER_CACHE_MAX_AGE', 864000 );  // Time to cache in the browser
define( 'BROWSER_CACHE_DISABLE', FALSE );  // Use for testing if you want to disable all browser caching
//Image size and defaults
define( 'MAX_WIDTH', 1500 );    // Maximum image width
define( 'MAX_HEIGHT', 1500 );    // Maximum image height
define( 'NOT_FOUND_IMAGE', '' );    //Image to serve if any 404 occurs 
define( 'ERROR_IMAGE', '' );    //Image to serve if an error occurs instead of showing error message 

