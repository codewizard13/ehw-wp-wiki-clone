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

