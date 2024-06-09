<?php // V. 0.3 - From chatGPT


/**
 * Calculate and update the video count for all guests based on reverse relationship.
 */
function admin_display_guest_vid_total()
{
  ?>
  <style>
  .admin-debug-row h3 {
    color: darkgray;
    font-weight: medium;
  }

  .details {
    color: teal;
    font-size: 1.4rem;
    font-weight: bold;
  }
  </style>

  <?php

  // Get all guest posts (adjust the post type name if needed)
  $guests_query = new WP_Query(
    array(
      'post_type' => 'guests', // Replace with your actual guest post type
      'posts_per_page' => -1, // Retrieve all posts
      'orderby' => 'guest_video_count DESC', // Order by the calculated video count
    )
  );

  if ($guests_query->have_posts()) {
    while ($guests_query->have_posts()) {
      $guests_query->the_post();

      // Store current guest ID
      $cur_guest_id = get_the_ID();

      // Get the related video IDs using the ACF reverse relationship field
      $related_videos = get_posts(
        array(
          'post_type' => 'videos', // Replace with your actual video post type
          'numberposts' => -1,
          'meta_query' => array(
            array(
              'order' => 'DESC',
              'key' => 'guest_names', // Replace with the actual ACF field name for reverse relationship
              'value' => $cur_guest_id,
              'compare' => 'LIKE',
            ),
          ),
        )
      );

      // Calculate the video count for current guest
      $guest_name = get_the_title();
      $video_count = count($related_videos);

      displayGuestVidCount($guest_name, $video_count);
    }

    // Reset the post data
    wp_reset_postdata();
  }
}

add_action('admin_notices', 'admin_display_guest_vid_total');

function displayGuestVidCount($guest_name, $vid_count) {
  $html = <<<HTML
  <article class="admin-debug-row">
    <h3>Guest <span class='details'>$guest_name</span> appears in <span class='details'>$vid_count</span> videos.</h3>
  </article>
  HTML;

  echo $html;
}

// Add filter to modify orderby clause
add_filter('posts_orderby', 'orderby_guest_video_count', 10, 2);

// Function to add custom orderby clause
function orderby_guest_video_count($orderby, $query) {
  global $wpdb;
  
  if ($query->get('post_type') === 'guests' && $query->is_main_query()) {
    // Adding custom sorting by calculating the video count
    $orderby = "(
        SELECT COUNT(*)
        FROM $wpdb->posts
        INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
        WHERE $wpdb->posts.post_type = 'videos' 
        AND $wpdb->postmeta.meta_key = 'guest_names' 
        AND $wpdb->postmeta.meta_value LIKE CONCAT('%', $wpdb->posts.ID, '%')
    ) DESC";
  }
  
  return $orderby;
}
