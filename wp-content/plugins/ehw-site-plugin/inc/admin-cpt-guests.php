<?php
/**
 * admin-cpt-guests.php
 * 
 * Functions concerning CPT: guests
 * 
 */


/**
 * 
 * :: ADMIN: Display Guests with Most Vids On-The-Fly
 * 
 * Display guests with most vids on-the-fly without storing value in DB.
 * 
 * Currently shows as a mint-green box with guest vid count rows at top on main
 *  dashboard.
 * 
 *  - HOOK: Only display on main wpadmin dashboard index page = add_action('load-index.php' ... 
 * 
 * REFERENCE:
 *  - https://www.advancedcustomfields.com/resources/querying-relationship-fields/
 *  - https://code.tutsplus.com/mastering-wp_query-using-the-loop--cms-23031t
 * 
 */

function displayVidCountForGuest($guest_name, $vid_count)
{
  $html = <<<HTML
    <article class="admin-debug-row">
        <h3>Guest <span class='details'>$guest_name</span> appears in <span class='details'>$vid_count</span> videos.</h3>
    </article>
    HTML;

  echo $html;
}
function admin_display_guest_vid_totals()
{
  ?>
  <style>
    .ehw-debug {
      background: honeydew;
      padding: 1rem;
      position: relative;
      z-index: 99999;
      display: inline-block;
    }

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

  // Open the section
  echo "<section class='ehw-debug'>";

  if ($guests_query->have_posts()) {
    // Initialize an empty array to store guest posts with video count
    $guests_with_count = array();

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
      $video_count = count($related_videos);

      // Store guest post ID and video count in an array
      $guests_with_count[] = array(
        'id' => $cur_guest_id,
        'video_count' => $video_count,
      );
    }

    // Sort the array of guests based on video count in descending order
    usort($guests_with_count, function ($a, $b) {
      return $b['video_count'] - $a['video_count'];
    });

    // Display guest names and their corresponding video counts
    foreach ($guests_with_count as $guest) {
      $guest_name = get_the_title($guest['id']);
      $video_count = $guest['video_count'];
      displayVidCountForGuest($guest_name, $video_count);
    }

    // Reset the post data
    wp_reset_postdata();
  }

  // Close the section
  echo "</section>";
}

// Add on main wpadmin dashboard page only
// add_action('load-index.php', 'admin_display_guest_vid_totals');







/**
 * 
 * #WORKS!
 * 
 * :: ELEMENTOR CUSTOM QUERY: Show guests and testimonials.
 *
 * This fuction adds a custom query to the post widget in Elementor which
 *  displays multiple post types.
 * 
 * @param \WP_Query $query The WordPress query instance.
 * 
 * USAGE:
 *  - Use anywhere as you would a shortcode
 *  - Add Elementor Posts Widget
 *  - Query Source: Guests [this gets overridden by your "post_type" setting]
 *  - Query ID: efltr_show_multiple_post_types
 *  
 * REFERENCE:
 *  - https://developers.elementor.com/docs/hooks/custom-query-filter/
 *  - https://www.youtube.com/watch?v=MlfD5V8yJqg&t=271s&ab_channel=WPTuts
 * 
 */
add_action('elementor/query/efltr_show_multiple_post_types', function ($query) {

  // Here we set the query to fetch pots with post type of 'custom-post-type1'
  //  and 'custom-post-type2'
  $query->set('post_type', ['guests', 'testimonial']);

});






/**
 * 
 * #WORKS!
 * 
 * :: ELEMENTOR CUSTOM QUERY: Filter Guests with Primary Role=patriot and
 *    Surname contains "in"
 *
 * This fuction adds a custom query to the post widget in Elementor which
 *  displays guests filtered by a meta_query.
 * 
 * @param \WP_Query $query The WordPress query instance.
 * 
 * USAGE:
 *  - Use anywhere as you would a shortcode
 *  - Add Elementor Posts Widget
 *  - Query Source: Guests
 *  - Query ID: efltr_guest_by_primary_role
 *  
 * REFERENCE:
 *  - https://developers.elementor.com/docs/hooks/custom-query-filter/
 * 
 */
function filter_guests_by_primary_role($query)
{
  // Get current meta Query
  $meta_query = $query->get('meta_query');

  // Log whether meta_query is present
  if (isset($meta_query) && is_array($meta_query) && !empty($meta_query)) {
    error_log('Meta query is present: ' . print_r($meta_query, true));
  } else {
    error_log('Meta query is not present');
  }

  // If there is no meta query when this filter runs, initialize it as an empty array.
  if (empty($meta_query)) {
    $meta_query = [];
  }

  // Append our meta query
  $meta_query[] = [
      'key' => 'Primary Role',
      'value' => ['patriot'],
      'compare' => 'IN', // 'IN' instead of 'in' for comparison
  ];
  $meta_query[] = [
    'key' => 'last_name',
    'value' => 'in',
    'compare' => 'LIKE', // 'IN' instead of 'in' for comparison
  ];

  $query->set('meta_query', $meta_query);
}
add_action('elementor/query/efltr_guest_by_primary_role', 'filter_guests_by_primary_role');







/**
 * 
 * #WORKS!
 * 
 * :: ELEMENTOR CUSTOM QUERY: Filter by related guests for current video
 *
 * This fuction adds a custom query to the post widget in elementor based
 *  on an ACF relationship field. It displays guests related to a video.
 * 
 * USAGE:
 *  - On a video post (or in single video template)
 *  - Add Elementor Posts Widget
 *  - Query Source: Guests
 *  - Query ID: efltr_related_guests_for_vid
 *  - To display a specific video post ID, just set $postid
 *  
 * REFERENCE:
 *  - https://github.com/elementor/elementor/issues/4916
 * 
 */
add_action('elementor/query/efltr_related_guests_for_vid', 'rel_guests_for_vid');
function rel_guests_for_vid($query)
{
  // $postid = get_the_ID();
  $postid = 21609;
  $ids = get_post_meta($postid, 'guest_names', true);
  if ($ids) {
    $query->set('post__in', $ids);
  }
}