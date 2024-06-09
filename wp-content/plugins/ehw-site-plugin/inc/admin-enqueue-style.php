<?php
/**
 * admin-enqueue-style.php
 * 
 * Load the admin styles when in the admin area
 * 
 */

/**
 * Only run in admin area
 */
function theme_admin_styles()
{

  // if (!is_admin()) return;


  wp_enqueue_style('theme_main_admin_style', get_theme_file_uri('admin-style.css'));
}
add_action('admin_enqueue_scripts', 'theme_admin_styles');