<?php
/**
 * unsorted.php
 * 
 * Code that we need to figure out where it goes, but its the end of the day,
 *  or maybe this code we are currently testing.
 * 
 */






/**
 * 
 * This code is supposed to track post views. That part works, but it doesn't
 *  display the view count in admin columns pro.
 * 
 * #GOTCHA: This doesn't work with Admin Columns Pro
 * - https://www.isitwp.com/track-post-views-without-a-plugin-using-post-meta/
 * - https://wpcodeus.com/how-to-add-php-code-to-elementor/
 */

 
 

 function getPostViews($postID){
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count === ''){
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, '0');
      return "0 Views";
  } elseif ($count == 1) {
    return "1 View";
  }
  return $count.' Views';
}

function setPostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count === ''){
      $count = 0;
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, '0');
  } else {
      $count++;
      update_post_meta($postID, $count_key, $count);
  }
}

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);





// Add custom column to Admin Columns Pro
add_filter('acp/editing_column/post_views', function($column) {
  $column->set_post_type('post'); // Adjust post type as needed
  $column->set_label('Views');
  $column->set_type('custom_field'); // Display a custom field
  $column->set_meta_key('post_views_count'); // Meta key for post views count
  return $column;
});




# 2024-05-30 - From: https://kinsta.com/blog/wordpress-hooks/

// show a maintenance message for all your site visitors
add_action( 'get_header', 'maintenance_message' );
function maintenance_message() {
    if (current_user_can( 'edit_posts' )) return;
    wp_die( '<h1>Stay Pawsitive!</h1><br>Sorry, we\'re temporarily down for maintenance right meow.' );
}


// show a custom login message above the login form
function custom_login_message( $message ) {
  if ( empty( $message ) ) {
      return "<h2>Welcome to Let's Develop by Salman Ravoof! Please log in to start learning.</h2>";
  } 
  else {
      return $message;
  }
}
add_filter( 'login_message', 'custom_login_message' );




# From: ChatGPT: https://chatgpt.com/c/eb76d760-9f75-402d-b42b-d1ae9dcbdf3c

// Add this code to your theme's functions.php file or a custom plugin.

function add_custom_post_type_to_admin_bar($post_type_singular, $post_type_plural, $use_plural) {
    add_action('admin_bar_menu', function($wp_admin_bar) use ($post_type_singular, $post_type_plural, $use_plural) {
        // Determine the post type to use
        $post_type = $use_plural ? $post_type_plural : $post_type_singular;

        // Get the post count for the custom post type
        $post_count = wp_count_posts($post_type);
        $count = isset($post_count->publish) ? $post_count->publish : 0;

        // Format the text to display
        $display_name = $use_plural ? ucfirst($post_type_plural) : ucfirst($post_type_singular);
        $text = sprintf('%s count: %d', $display_name, $count);

        // Add a node to the admin bar
        $wp_admin_bar->add_node([
            'id'    => $post_type . '_count',
            'title' => $text,
            'href'  => admin_url('edit.php?post_type=' . $post_type),
        ]);
    }, 999);
}

// Example usage:
add_custom_post_type_to_admin_bar('video', 'videos', false);
