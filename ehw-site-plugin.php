<?php
/**
 * 
 * Plugin Name:     EHW Site Plugin
 * Description:     Custom site-specific plugin for Eric Hepperle's Wikipedia Clone website. Contains all the custom PHP code for the site.
 * Version:         0.1.05
 * Author:          Eric Hepperle
 * Author URI:      EricHepperle.com
 * 
 */

// ADMIN INCLUDES
require_once (__DIR__ . '/inc/admin-login.php');
require_once (__DIR__ . '/inc/admin-enqueue-style.php');
require_once (__DIR__ . '/inc/admin-dashboard.php');
require_once (__DIR__ . '/inc/admin-adminbar.php');
require_once (__DIR__ . '/inc/admin-sidebar.php');


// CUSTOM POST TYPES
require_once (__DIR__ . '/inc/cpt/cpt-video.php');
require_once (__DIR__ . '/inc/cpt/cpt-doc.php');


// ADMIN CPTS
require_once (__DIR__ . '/inc/admin-edit-cpts.php');

require_once (__DIR__ . '/inc/admin-cpt-guests.php' );


// SITEWIDE INCLUDES
//   - Much of these is peformance-improvement hacks
require_once (__DIR__ . '/inc/sitewide.php');

// SHORTCODE INCLUDES
require_once (__DIR__ . '/inc/shortcodes.php');

// FRONTEND INCLUDES
require_once (__DIR__ . '/inc/frontend.php');


// SEARCH INCLUDES
require_once (__DIR__ . '/inc/search.php');

// VIDEO EMBEDS & IFRAME INCLUDES
require_once (__DIR__ . '/inc/iframes-vids-embeds.php');

// Unsorted: If we can't figure out a good place, they go here
require_once (__DIR__ . '/inc/unsorted.php');


// CLASS INCLUDES
require_once (__DIR__ . '/inc/class__admin-view-all-shortcodes.php');

// Disable deprecated error messages
error_reporting(error_reporting() & ~E_DEPRECATED);

/*

NOTES:

  - ADD this to wp-config.php to troubleshoot a broken snippet:

    define( 'CODE_SNIPPETS_SAFE_MODE', true );


*/