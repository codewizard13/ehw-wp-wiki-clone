<?php
/*
 * functions.php
 * 
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'ehw-astra-child', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), false, 'all' );

}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// NOTE: The rest of the "plugins" and custom PHP code should be either in the CUSTOM SITE PLUGIN or Code Snippets