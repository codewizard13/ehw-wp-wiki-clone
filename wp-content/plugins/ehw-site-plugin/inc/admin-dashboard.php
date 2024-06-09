<?php
/**
 * admin-dashboard.php
 * 
 * Customize the WP Admin Dashboard - the main place where all the metaboxes are :)
 * 
 */


/**
 * 
 * :: Dashboard: Add Widget - User Info 
 * 
 * ADD custom Dashboard Widget to Wordpress Dashboard to display USER INFO.
 * Adds "User Details" box to admin dashboard including username, user ID, and user role
 * 
 */

/* 
Type: WordPress Code Snippet
Purpose: ADD custom Dashboard Widget to Wordpress Dashboard to display USER INFO
Author: Eric Hepperle
Date Created: 2023-07-10

Code Snippets Plugin Settings:
- Only run in admin area

*/

function ehw_custom_dashboard_widget_user_info()
{

  wp_add_dashboard_widget('user_info_widget', 'User Details', 'custom_widget_user_details');

}
add_action('wp_dashboard_setup', 'ehw_custom_dashboard_widget_user_info');

// Display Form

if (!function_exists('custom_widget_user_details')) {

  function custom_widget_user_details()
  { ?>


    <style>
      #user_info_widget {
        background: aliceblue;
      }

      #user_info_widget h3 {
        font-weight: bold;
        color: brown;
        font-size: 1.4rem;
      }
    </style>

    <?php
    global $current_user;

    echo "<pre>";
    // 	var_dump($current_user);
    echo "</pre>";

    $users = array(3, 4, 5);
    $user_id = $current_user->ID;
    $username = $current_user->user_login;
    $role = $current_user->roles[0];

    echo "<div>";
    echo "<h3>$username</h3>";

    echo "<b>User ID:</b> $user_id<br>";
    echo "<b>User Role:</b> $role<br>";
    echo "</div>";
    ?>

  <?php }

}


/**
 * 
 * :: Dashboard: Remove Admin Widgets for all but ADMINS 
 * 
 * REFERENCE: 
 *  - https://wpbeaches.com/remove-wordpress-backend-dashboard-widgets/
 *  - https://wordpress.stackexchange.com/questions/137582/remove-a-plugin-meta-box-from-the-dashboard
 * 
 */
function ehw_remove_wpadmin_widgets_for_non_admin_users()
{

  global $current_user;

  $users = array(3, 4, 5);

  $role = $current_user->roles[0];

  // 	Remove for all roles except administrator
  if ($role !== "administrator") {
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress.com Blog
//     remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // Plugins
//     remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
    remove_action('welcome_panel', 'wp_welcome_panel'); // Welcome Panel
    remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel'); // Try Gutenberg
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Press widget
//     remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // Recent Drafts
    remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Other WordPress News
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Incoming Links
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); // Gravity Forms
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('icl_dashboard_widget', 'dashboard', 'normal'); // Multi Language Plugin
//     remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity

    // Remove Elementor dashboard widget
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');

    // Remove Broken Link Checker dashboard widget
    remove_meta_box('blc_dashboard_widget', 'dashboard', 'normal');

  }
}
add_action('wp_dashboard_setup', 'ehw_remove_wpadmin_widgets_for_non_admin_users');


/**
 * 
 * :: Dashboard: ADD Widget with Site Version Number 
 * 
 * ADD custom Dashboard Widget to Wordpress Dashboard to display USER INFO.
 * Adds "User Details" box to admin dashboard including username, user ID, and user role
 * 
 */
/* 
Type: WordPress Code Snippet
Snippet Name: Dashboard: ADD Widget with Site Version Number
Purpose: ADD custom Dashboard Widget to Wordpress Dashboard letting me set and store the site version number
Author: Eric Hepperle
Date Created: 2023-07-24

Inspired by: How to add WordPress Dashboard Widget with Custom Options / Fields - WP Dashboard API
URL: https://www.youtube.com/watch?v=Vhf4qU9yKCw
Channel: Raddy

Code Snippets Plugin Settings:
- Only run in admin area

*/

function ehw_widget_set_site_version()
{

  if (current_user_can('manage_options')) {

    wp_add_dashboard_widget('ehw_site_version_widget', 'ehw Site Info', 'custom_dashboard_site_ver');

  }

}
add_action('wp_dashboard_setup', 'ehw_widget_set_site_version');

// Display Form

if (!function_exists('custom_dashboard_site_ver')) {

  function custom_dashboard_site_ver()
  { ?>

    <style>
      #ehw_site_version_widget {
        background: #f0fff1;
      }
    </style>
    <div class="wrap" style="background:#f0fff1;">

      <form action="options.php" method="post">
        <?php wp_nonce_field('update-options');

        if (get_option('site_version')) {
          echo "<h2 style='color:brown; font-weight: 700; font-weight; 900; font-family: Roboto, Arial, sans-serif; text-align: right;'>";
          echo get_option('site_version');
          echo "</h2>";
        }

        ?>

        <table>
          <tr>
            <th scope="row" width="120" align="left" valign="top">Site Version:</th>
            <td>
              <input type="text" name="site_version" size="255"
                value="<?php echo htmlentities(get_option('site_version')); ?>" style="width:100%">
            </td>
          </tr>
        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="site_version" />
        <p class="submit">
          <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
        </p>

      </form>
    </div>


  <?php }

}


/**
 * 
 * :: Dashboard: ADD Widget Basic Hello World text 
 * 
 * ADD custom Dashboard Widget to Wordpress Dashboard. This is basically a
 *  template on how to display simple text in a dashboard widget.
 * 
 */
function hello_hepperle() {
	echo "<h3>Hello Hepperle!</h3>";
}
function ehw_wpadmin_add_hello() {

  wp_add_dashboard_widget('ehw_add_hello', 'Hepperle Hello Section', 'hello_hepperle');

}
// add_action('wp_dashboard_setup', 'ehw_wpadmin_add_hello');

