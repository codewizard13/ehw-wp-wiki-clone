<?php
/**
 * admin-sidebar.php
 * 
 * Customize the WP Admin Dashboard Sidebar
 * 
 */


/**
 * :: Dashboard: Remove Admin Menu Items for all but ADMINS 
 * 
 * Ref: https://www.youtube.com/watch?v=n0m9N9c6_uY&t=1149s
 * 
 */
function ehw_remove_wpadmin_menus_for_non_admin_users()
{
  global $current_user;

  $users = array(3, 4, 5);

  $role = $current_user->roles[0];

  // 	Remove for all roles except administrator
  if ($role !== "administrator") {
    remove_menu_page('themes.php');
    remove_menu_page('plugins.php');
    remove_menu_page('users.php');
    remove_menu_page('tools.php');
    remove_menu_page('upload.php'); // Media
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('options-general.php'); // Settings
    remove_menu_page('edit.php?post_type=page');
    // 		remove_menu_page('index.php'); // Dashboard

    // Menu Items from Plugins
    remove_menu_page('templately'); // Templately
    remove_menu_page('blc_dash'); // Broken Link Checker

  }
}
add_action('admin_menu', 'ehw_remove_wpadmin_menus_for_non_admin_users');

/**
 * :: Dashboard: Remove Admin Menus for ALL User Roles 
 * 
 * These are admin sidebar menu items not even admin needs access to. Primarily
 *  things not in use. For instance, the default "Posts" post type will never
 *  be used so we will disable it.
 * 
 */
function ehw_remove_wpadmin_menus_for_all_user_roles()
{
  global $current_user;

  $role = $current_user->roles[0];

// 		remove_menu_page('themes.php');
// 		remove_menu_page('plugins.php');
// 		remove_menu_page('users.php');
// 		remove_menu_page('tools.php');
		// remove_menu_page('upload.php'); // Media
  remove_menu_page('edit.php'); // Posts
// 		remove_menu_page('edit-comments.php'); // Comments
// 		remove_menu_page('options-general.php'); // Settings
// 		remove_menu_page('edit.php?post_type=page');
// 		remove_menu_page('index.php'); // Dashboard
}
add_action('admin_menu', 'ehw_remove_wpadmin_menus_for_all_user_roles');