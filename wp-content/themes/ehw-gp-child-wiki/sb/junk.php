<?php // V. 0.1


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

      echo "<h3>Guest " . get_the_title() . "appears in <span style='color:teal; font-size: 2rem'>" . count($related_videos, true) . "</span> videos.</h3>";

$html = <<<HTML
<article class="admin-debug-row">
  <h3>Guest <span class='details'>$guest_name</span> appears in <span class='details'>$video_count</span> videos.</h3>
</article>
HTML;

      //       echo "<pre>" . print_r($related_videos, true) . "</pre>";

    }

    // Reset the post data
    wp_reset_postdata();
  }
}

add_action('admin_notices', 'admin_display_guest_vid_total');