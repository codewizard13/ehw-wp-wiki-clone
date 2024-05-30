<?php
/**
 * shortcodes.php
 * 
 * Shortcodes are basically a way to insert a snippet of PHP into your
 *  WordPress site. All shortcodes should go here.
 * 
 */




/**
 * 
 * #DOESNT_WORK
 * 
 * :: SHORTCODE: Group Episodes by Guest 
 * 
 * Display Episodes by Guest. Currently correctly gets video titles, but not displaying guests yet.
 * 
 * USAGE:
 *  - Add shortcode [ehw_group_eps_by_guest]
 * 
 * REFERENCE:
 *  - https://wpcodeus.com/how-to-add-php-code-to-elementor/
 * 
 */

function videos_query($args = [])
{

  $videos_query = [];

  $post_type = 'videos';

  $args = [
    'post_type' => $post_type,
    'post_status' => 'publish',
    'numberposts' => -1
  ];

  $vids_query = get_posts($args);

  foreach ($vids_query as $video):
    setup_postdata($video);
    $videos_query[] = array(
      'id' => $video->ID,
      'guest_names' => get_post_meta($video->ID, 'guest_names'), // THIS IS  a dummy demo line showing how get custom field values
      // 'guest_names_arr' => "<pre>" . print_r(get_post_meta($video->ID, 'guest_names'), true) . "</pre>",
      'title' => get_the_title($video->ID)
    );

  endforeach;
  wp_reset_postdata();
  return $videos_query;
}

function ehw_groupEpsByGuest($atts)
{

  // $query = new WP_Query( array( 'author' => 1 ) );

  // 	echo "<h3>Query Results Count: " . count($query) . "</h3>";

  // 	$post_types = get_post_types();
  // 	echo "<pre>";
  // 	var_dump($post_types);
  // 	echo "</pre>";
  // 	

  $videos_results = videos_query();

  $vidCount = count($videos_results);

  echo "<h3>Video Count: $vidCount</h3>";
  echo "<pre>" . print_r($videos_results, true) . "</pre>";

}
add_shortcode('ehw_group_eps_by_guest', 'ehw_groupEpsByGuest');





/**
 * 
 * :: SHORTCODE: List Posts Basic 
 * 
 * Displays basic vertical list of posts (any type, cpt, etc.). Demonstrates
 *  querying anywhere (outside the loop) with a shortcode.
 * 
 * USAGE:
 *  - Add shortcode [ehw_list_posts]
 * 
 * REFERENCE:
 *  - https://stackoverflow.com/questions/62756940/trim-characters-from-content-instead-of-words-wordpress
 * 
 */
function ehw_list_posts_basic()
{

  global $wbdb;
  global $query;
  global $post;

  $args = array(
    'posts_per_page' => '12',
    'post_type' => 'videos',
    'post_status' => 'publish',
  );

  $slider_posts = get_posts($args);

  if ($slider_posts) {
    foreach ($slider_posts as $post):

      // First, setup post data
      setup_postdata($post);

      $moreLink = '<a href="' . get_the_permalink($post->ID) . '"> Read More...</a>';

      // echo "<pre>" . print_r($post, true) . "</pre>";
      // Second, define content to display ?>
      <section style='border: solid 1px navy; padding: .4rem 1rem; margin-bottom: 1rem;'>
        <h2 style="font-size: 1rem; color: navy; font-weight: bold;><a href='<?php the_permalink(); ?>'><?php the_title(); ?></a></h2>
        <?php //the_content(); ?>
        <?php echo esc_html(theme_truncate(get_the_content(), 250)); ?>

        <!--setup_postdata() required for the_title() and the_content() to work-->
      </section>
      
      <?php
    endforeach;

    // Third, reset post data after end of the foreach function
    wp_reset_postdata();
  }

}
add_shortcode('ehw_list_posts', 'ehw_list_posts_basic');



/**
 * 
 * :: HELPER_FUNC: Truncate text by character count
 * 
 * @param string $string = text to truncate
 * @param int $length = how many characters should be displayed. Default is 100
 * @param string $append = anything to append to the end. Default is ellipsis, but you could add a "Read More" link, etc.
 * 
 * USAGE:
 *  EX: echo esc_html( theme_truncate( get_the_content(), 15 ) );
 * 
 * REFERENCE:
 *  - https://stackoverflow.com/questions/62756940/trim-characters-from-content-instead-of-words-wordpress
 * 
 */
function theme_truncate($string, $length = 100, $append = '&hellip;')
{

  $string = trim($string);

  if (strlen($string) > $length) {
    $string = wordwrap($string, $length);
    $string = explode("\n", $string, 2);
    $string = $string[0] . $append;
  }

  return $string;
}


/**
 * 
 * #DOESNT_WORK: Lists all correctly, but the limit "top N guests" isn't working
 * 
 * :: SHORTCODE: Display Guests with Most Vids On-The-Fly
 * 
 * Display guests with most vids on-the-fly without storing value in DB.
 * 
 *  - HOOK: Only display on main wpadmin dashboard index page = add_action('load-index.php' ... 
 * 
 * REFERENCE:
 *  - https://www.advancedcustomfields.com/resources/querying-relationship-fields/
 *  - https://code.tutsplus.com/mastering-wp_query-using-the-loop--cms-23031t
 *  * 
 */

function shortcode_disp_guest_with_most_vids($top_x_guests = 10)
{

  $guests_limit = isset($top_x_guests) ? $top_x_guests : -1;

  ?>
  <style>
    .ehw-debug {
      background: honeydew;
      padding: .4rem;
      position: relative;
      z-index: 99999;
      display: inline-block;
    }

    .admin-debug-row {
      border: solid 1px;
    }

    .admin-debug-row h3 {
      color: darkgray;
      font-weight: medium;
      font-size: 1rem;
      margin: .2rem .8rem;
    }

    .details {
      color: teal;
      font-size: 1.2rem;
      font-weight: bold;
    }
  </style>

  <?php

  // Get all guest posts (adjust the post type name if needed)
  $guests_query = new WP_Query(
    array(
      'post_type' => 'guests', // Replace with your actual guest post type
      'posts_per_page' => -1, // Retrieve all posts
      'numberposts' => 10,
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

add_shortcode('ehw_guests_with_most_vids', 'shortcode_disp_guest_with_most_vids');




/**
 * 
 * #WORKS!: 
 * 
 * :: SHORTCODE: Display Guests Related to a Video Post 
 * 
 * Displays guests related to a video. Currently, the video ID is hard-coded
 * 
 * USAGE:
 *  - Add shortcode [related_guests]
 * 
 * REFERENCE:
 *  - https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
 *  - #GOTCHA: get_the_post_thumbnail() lets you specify for which post ID, so this is the one to use
 * 
 */
function display_related_guests_shortcode($atts)
{

  $atts = shortcode_atts(
    [
      'post_id' => get_the_ID(), // Default to the current post ID
    ],
    $atts
  );

  // This hardcoded test ID is a Kat Kerr vid
  $vid_id = 21732;

  $rel_field = "guest_names";

  // Get related guest IDs (assuming you've stored them as post meta)
  $related_guests = get_post_meta($vid_id, $rel_field, true);

  // Sort the guest names alphabetically
  if (!empty($related_guests)) {
    // Sort the guest names
    sort($related_guests);

    $output = '<h3>Related Guests:</h3>';
    foreach ($related_guests as $guest_id) {
      $guest_title = get_the_title($guest_id);
      $output .= '<div>' . esc_html($guest_title) . '</div>';
    }
    return $output;
  } else {
    return '<p>No related guests found.</p>';
  }
}
add_shortcode('ehw_related_guests', 'display_related_guests_shortcode');




/**
 * 
 * #WORKS!
 *  
 * :: SHORTCODE: Most Recent Guests
 * 
 * Displays formatted rows of to 10 or less most recent guests.
 * 
 * USAGE:
 *  - Add shortcode [list_recent_guests]
 * 
 * REFERENCE:
 *  - 
 * 
 */
function shortcode_most_recent_guests($atts)
{
  // Query videos
  $videos_query = new WP_Query(
    array(
      'post_type' => 'videos',
      'posts_per_page' => 10,
      'orderby' => 'date',
      'order' => 'DESC',
    )
  );

  ?>
<style>
.guest-post {
  background: bisque;
  padding: .3rem 1rem;
  margin-bottom: 1rem;
  border: solid brown 2px;
  border-radius: .3rem;
}
.guest-title {
    font-size: 1.4rem !important;
    margin-bottom: 0 !important;
}
.guest-data-item {
    color: darkgreen
}
</style>

<?php
    $displayed_guests = []; // Initialize blank global array
  
    // Start output buffer
    ob_start();

    // Check if there are videos
    if ($videos_query->have_posts()) {
      // Loop through each video
      while ($videos_query->have_posts()) {
        $videos_query->the_post();

        // Get related guest posts using ACF relationship field
        $related_guests = get_field('guest_names');

        // Output related guest info
        output_guests_info($related_guests, $displayed_guests);
      }

      echo "<h4>Displayed Guests final array: " . join(", ", $displayed_guests) . "</h4>";
      // echo "<h4>Displayed Guests final array:" . print_r($displayed_guests, true) . "</h4>";
  
      // Reset post data
      wp_reset_postdata();
    }

    // Return the output
    return ob_get_clean();

}

// Register the shortcode
add_shortcode('ehw_list_recent_guests', 'shortcode_most_recent_guests');



/**
 * 
 * :: HELPER_FUNC: Output Guests Info.
 * 
 * This version includes:
 *  - Guest name
 *  - Guest ID
 *  - Published Date 
 * 
 * @param array $rel_guests = Array of guest Post Objects
 * @param array $displayed_guests = Blank array we pass by reference. Whenever
 *  a new guest ID is encountered, it is added to this "seen" basket. Seen
 *  guests are ignored the next time around because we want to avoid
 *  duplicates.
 * 
 * USAGE:
 * 
 * REFERENCE:
 *  - 
 * 
 */
function output_guests_info($rel_guests, &$displayed_guests)
{

  $ignore_ids = [
    12737, // Steve Shultz
    15492, // Jeff Tharp
    14995, // Kelsey O'Malley
  ];

  // Output each guest post as Post Object
  foreach ($rel_guests as $guest) {



    // Check if guest post has already been displayed
    if (!in_array($guest->ID, $displayed_guests) && !in_array($guest->ID, $ignore_ids)) {
      // Display guest post
      $post_date = get_the_date('l F j, Y');
      ?>
      <div class=" guest-post">
          <div>
            <h2 class="guest-title">
              <?php echo $guest->post_title; ?>
              <span class="guest-data-item"><?php echo $guest->ID; ?></span>
            </h2>
          </div>
          <div><?php //echo get_the_excerpt($guest->ID); ?></div>
          <div class="guest-data-item"><?php echo $post_date; ?></div>
          </div>
          <?php
          // Add guest post ID to the displayed list
          // $displayed_guests[] = $guest->ID;
          array_push($displayed_guests, $guest->ID);
      // echo "<h4>Displayed Guests final array:" . join(", ", $displayed_guests) . "</h4>";

    }
  }

}




/**
 * 
 *  
 * :: SHORTCODE: Get Related Guests for Video by ID
 * 
 * Gets guest info from a video ID.
 * 
 * USAGE:
 *  - Add shortcode [rel_guest_info video_id="20947"]
 *  - Change the video_id for whatever video you are targeting
 *  - Or, drop the video ID and it will use a default
 * 
 * REFERENCE:
 *  - 
 * 
 */
// Register the shortcode
add_shortcode('ehw_rel_guest_info', 'rel_guest_from_vid_id');

// Define the shortcode function
function rel_guest_from_vid_id($atts)
{
  // Extract shortcode attributes
  $atts = shortcode_atts(
    array(
      'video_id' => 12834, // Default value
    ),
    $atts,
    'rel_guest_info'
  );

  $rel_field = 'guest_names'; // define ACF relationship field

  ?>
      <style>
        .guest-info {
          background: aliceblue;
          padding: .3rem 1rem;
          border: solid navy 2px;
          border-radius: .3rem;
          font-size: 1.4rem !important;
          margin-bottom: .4rem;
        }

        .guest-name {
          font-weight: bold;
        }

        .guest-role {
          font-style: italic;
        }

        .vid-count {
          color: brown;
          font-weight: 600;
        }
      </style>

      <?php
      // Ensure video_id is set and numeric
      if (!empty($atts['video_id']) && is_numeric($atts['video_id'])) {
        // Query related guests using ACF relationship field
        $related_guests = get_field($rel_field, $atts['video_id']);

        echo "<h5 class='vid-name'>" . get_the_title($atts['video_id']) . "</h5>";


        // Start output buffer
        ob_start();

        // Output guest info
        if ($related_guests) {

          foreach ($related_guests as $guest) {
            ?>
            <div class="guest-info">
              <span
                class="guest-name"><?php echo get_field('first_name', $guest->ID) . ' ' . get_field('last_name', $guest->ID); ?></span>,
              <span class="guest-role"><?php echo ucwords(get_field('Primary Role', $guest->ID)); ?></span>
              <span class="vid-count">[<?php echo get_field('video_count', $guest->ID); ?> videos]</span>
            </div>
            <?php
          }
        } else {
          echo 'No related guests found.';
        }

        // Reset post data
        wp_reset_postdata();

        // Return the output
        return ob_get_clean();
      } else {
        return 'Invalid video ID.';
      }
}





/**
 * 
 * #WORKS 
 * 
 * :: SHORTCODE: Display post view count
 * 
 * #GOTCHA: Causes delay in performance, probably due to database hits.
 * 
 * USAGE:
 *  - Add shortcode [post_views_counter] to a single post template.
 * 
 * DEPENDENCIES:
 *  - getPostViews() and setPostViews() functions
 * 
 * REFERENCE:
 * - https://www.isitwp.com/track-post-views-without-a-plugin-using-post-meta/
 * - https://wpcodeus.com/how-to-add-php-code-to-elementor/
 * 
 */
add_shortcode('ehw_post_views_counter', 'shortcode_countPostViews');
function shortcode_countPostViews()
{

  // $cur_user = get_user_meta( $user_id:integer, $key:string, $single:boolean );
  $cur_user = get_current_user();

  echo "<pre>" . print_r($cur_user, true) . "</pre>";

  // Check if user is logged in
  if (is_user_logged_in()) {
    echo '<h4 style="background:lightgreen;">Welcome back, <span style="color:forestgreen; text-transform:uppercase;">' . $cur_user . '</span>!</h3>';
  } else {
    // Only increment view count if user not logged in
    echo '<h3 style="background:lightgreen; color:forestgreen">Welcome, visitor!</h3>';
    setPostViews(get_the_ID());

  }

  echo getPostViews(get_the_ID());
}






/***
 * #BROKEN
 * 
 * Displays daily archives, and counts, but the generated archive link doesn't work.
 * 
 */
function getDailyArchives()
{
  $archives = wp_get_archives([
    'type' => 'daily',
    'limit' => 14,
    'show_post_count' => 'true',
    'post_type' => 'testimonial'
  ]);

  $my_archives = wp_get_archives(
    array(
      'type' => 'monthly',
      'limit' => 10,
      'echo' => 0,
      'in_year' => '2018', // custom parameter
    )
  );

  return $archives;

  echo "<pre>" . print_r($archives, true) . "</pre>";

}
add_shortcode('ehw_daily_archives', 'getDailyArchives');
//https://developer.wordpress.org/reference/functions/wp_get_archives/#div-comment-3570


/**
 * Adds a span around post counts in the archive widget.
 *
 * @param   string  $links      The comment fields.
 * @return  string
 */
function wpdocs_archive_count_span($links)
{

  echo "<pre>" . print_r($links, true) . "</pre>";

  $links = str_replace('</a>&nbsp;(', '<span class="count">', $links);
  $links = str_replace(')', '</span></a>', $links);
  return $links;
}
// add_filter( 'get_archives_link', 'wpdocs_archive_count_span' );



add_filter('getarchives_where', function ($where, $parsed_args) {
  if (!empty ($parsed_args['in_year'])) {
    $year = absint($parsed_args['in_year']);
    $where .= " AND YEAR(post_date) = $year";
  }
  return $where;
}, 10, 2);



/**
 * CHATGPT:
 * Create single link to videos monthly archive
 * 
 */

// Define the shortcode function
function get_monthly_archive_shortcode($atts)
{
  // Shortcode attributes with default values
  $atts = shortcode_atts(
    array(
      'post_type' => 'videos',
      'year' => date('Y'),   // Default to current year
      'month' => date('m'),   // Default to current month
    ),
    $atts,
    'get_monthly_archive'
  );

  // Build the archive URL based on shortcode attributes
  $archive_link = get_post_type_archive_link($atts['post_type']);

  if ($archive_link) {
    // Append the year and month to the archive link
    $archive_link .= $atts['year'] . '/' . sprintf("%02d", $atts['month']) . '/';
  }

  // Output the generated archive link
  if ($archive_link) {
    return '<a href="' . esc_url($archive_link) . '">' . $atts['month'] . '/' . $atts['year'] . ' ' . ucfirst($atts['post_type']) . ' Archive</a>';
  }

  return ''; // Return empty if archive link is not available
}

// Register the shortcode
add_shortcode('ehw_get_monthly_archive', 'get_monthly_archive_shortcode');





/**
 * 
 * Create a Custom Endpoint: You'll want to create a custom endpoint for your
 *  archive URL structure (sitename.com/videos/2024/04/). This involves
 *  registering a custom rewrite rule to map this URL structure to a specific
 *  template or query.To do this, you can add the following code to your
 *  theme's functions.php file or create a custom plugin:
 * 
 * This code adds a rewrite rule that captures URLs like
 *  sitename.com/videos/2024/04/ and maps them to the main query, specifying
 *  the post_type as post and passing the year and month parameters (year and
 *  monthnum) to WordPress.
 * 
 */
function custom_rewrite_rule()
{
  add_rewrite_rule('^videos/(\d{4})/(\d{2})/?$', 'index.php?post_type=post&year=$matches[1]&monthnum=$matches[2]', 'top');
}
add_action('init', 'custom_rewrite_rule');






/**
 * #WORKS
 * 
 * USAGE:
 * 
 * [ehw_date_archive post_type="videos"  year="2018" month="09"]
 *
 */
function custom_date_archive_shortcode($atts)
{
  // Extract shortcode attributes (if any) with default values
  $atts = shortcode_atts(
    array(
      'post_type' => 'videos', // Default post type
      'year' => date('Y'), // Default to current year
      'month' => date('m'), // Default to current month
    ),
    $atts
  );

  // Prepare arguments for WP_Query
  $args = array(
    'post_type' => $atts['post_type'],
    'posts_per_page' => -1,
    'year' => $atts['year'],
    'monthnum' => $atts['month'],
  );

  // Query posts based on arguments
  $posts_query = new WP_Query($args);

  ob_start(); // Start output buffering

  if ($posts_query->have_posts()) {
    ?>

        <style>
          .ehw-posts-set {
            margin: 0 auto;
            background: bisque;
            padding: 2rem;
            display: flex;
            flex-wrap: wrap;
          }

          .ehw-card {
            border: solid 1px;
            border-radius: .4rem;
            background: rgba(256, 256, 256, .7);
            padding: .4rem;
            margin: .2rem;
            flex: 0 1 24%;
          }

          .ehw-card .ehw-post-title {
            font-size: 1rem;
            margin-bottom: .4rem;
            color: brown;
            font-weight: bold;
          }

          .ehw-card .ewh-post-date {
            font-size: .8rem;
            color: gray;
            opacity: .7;
          }
        </style>
        <section class="ehw-posts-set">

          <?php

          while ($posts_query->have_posts()) {
            $posts_query->the_post();
            // Display post content here
            $post_date = get_the_date('l F j, Y');
            ?>

            <div class="ehw-card video-archive-item">
              <a href="<?php echo esc_url(get_permalink()); ?>">
                <h3 class="ehw-post-title"><?php the_title(); ?></h3>
                <h4 class="ewh-post-date"><?php echo $post_date; ?></h4>
              </a>
            </div>

            <?php
          }
          ?>

        </section>

        <?php
  } else {
    echo 'No posts found';
  }

  wp_reset_postdata(); // Reset post data

  return ob_get_clean(); // Return buffered output
}
add_shortcode('ehw_date_archive', 'custom_date_archive_shortcode');






/**
 * 
 * #WORKS 
 * 
 * :: SHORTCODE: List all registered / available shortcodes.
 * 
 * USAGE:
 *  - Add shortcode [ehw_list_shortcodes]
 *  * 
 * REFERENCE:
 * - https://paulund.co.uk/get-list-of-all-available-shortcodes
 * - https://wordpress.stackexchange.com/questions/340814/get-list-of-shortcodes-from-content
 * 
 */
function list_all_shortcodes()
{
  global $shortcode_tags;

  ?>
  <style>
    h3 {
      background: lightgreen;
    }
    .sc-list {
      list-style-type: none;
    }
    .sc-list li {
      color: brown;
      font-weight: bold;
    }
  </style>

  <h3 style='background:lightgreen'>Shortcode Tags:</h3>
  <ul class="sc-list">
    
    <?php foreach ($shortcode_tags as $name => $function): ?>

      <li><?php echo $name; ?></li>

    <?php endforeach; ?>

    </ul>
<?php
}
add_shortcode('ehw_list_shortcodes', 'list_all_shortcodes');

