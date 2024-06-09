<?php // V. 0.6 - From chatGPT (SQL simplified) #DOESNT_WORK

function admin_display_guest_vid_total()
{
  echo "<style>.ehw-debug{background:honeydew;padding:1rem;position:relative;z-index:99999;display:inline-block}.admin-debug-row h3{color:darkgray;font-weight:medium}.details{color:teal;font-size:1.4rem;font-weight:bold}</style>";
  $guests_query = new WP_Query(['post_type' => 'guests', 'posts_per_page' => -1, 'orderby' => 'guest_video_count DESC']);
  echo "<section class='ehw-debug'>";
  if ($guests_query->have_posts()) {
    while ($guests_query->have_posts()) {
      $guests_query->the_post();
      $cur_guest_id = get_the_ID();
      $related_videos = get_posts(['post_type' => 'videos', 'numberposts' => -1, 'meta_query' => [['key' => 'guest_names', 'value' => $cur_guest_id, 'compare' => 'LIKE']]]);
      displayGuestVidCount(get_the_title(), count($related_videos));
    }
    wp_reset_postdata();
  }
  echo "</section>";
}

add_action('load-index.php', 'admin_display_guest_vid_total');

function displayGuestVidCount($guest_name, $vid_count)
{
  echo "<article class='admin-debug-row'><h3>Guest <span class='details'>$guest_name</span> appears in <span class='details'>$vid_count</span> videos.</h3></article>";
}

add_filter('posts_orderby', 'orderby_guest_video_count', 10, 2);

function orderby_guest_video_count($orderby, $query)
{
  global $wpdb;
  if ($query->get('post_type') === 'guests' && $query->is_main_query()) {
    $orderby = "(SELECT COUNT(*) FROM $wpdb->posts AS videos JOIN $wpdb->postmeta AS meta ON videos.ID = meta.post_id WHERE videos.post_type = 'videos' AND meta.meta_key = 'guest_names' AND meta.meta_value LIKE CONCAT('%', {$wpdb->posts}.ID, '%')) DESC";
  }
  return $orderby;
}
?>