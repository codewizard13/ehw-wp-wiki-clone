<?php
/**
 * frontend.php
 * 
 * Customize the website frontend
 * 
 */


/**
 * 
 * :: FRONTEND: Remove Gutenberg CSS from Front-End
 * 
 * Remove Gutenberg Block Library CSS from loading on the frontend.
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 */
function ehw_remove_wp_block_library_css()
{
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'ehw_remove_wp_block_library_css');