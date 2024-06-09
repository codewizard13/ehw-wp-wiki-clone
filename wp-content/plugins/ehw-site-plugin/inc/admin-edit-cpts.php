<?php
/**
 * admin-edit-cpts.php
 * 
 * Customize single post editor for custom post types (CPT)
 * 
 */

/**
 * :: Display ID Based Permalink on Backend 
 * 
 * Add ID-based permalink metabox to single post edit pages (Videos, Guests, etc.).
 * This is useful so that the content team can grab the fixed ID-based URL to put in email
 * newsletters. Even if the text of the title changes, the ID stays consistent.
 */
function id_based_permalink($return, $id, $new_title, $new_slug)
{
  $oldURL = 'https://example.com/?p=' . $id;
  $siteDomain = $_SERVER['HTTP_HOST'];
  $protocol = $_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
  $homeURL = get_home_url();
  $postURL = $homeURL . "/?p=" . $id;
  //   $postURL = $protocol . '://' . $siteDomain . "/?p=" . $id;


  $outHTML = <<<OUT
  <style>
  .ehw-box-style-01 {
    color: cadetblue;
    background: aliceblue;
    border-radius: .6rem;
    border: solid 2px navy;
    padding: .8rem;
    display: inline-block;
    margin: 1rem auto;
    min-width: 17rem;
    box-sizing: border-box;
  }

  .ehw-box-style-01 > input {
    color: brown !important;
  }

  .ehw-w-100-percent {
    width: 100%;
  }
</style>


<!-- ID URL box v. 2 -->
<section class="ehw-box-style-01" style="width: 30rem;">
  <label for="postIdURL">URL for Email Newsletter:</label>
  <input value="{$postURL}" type="text" id="postIdURL" name="postIdURL" readonly="" style="
    width: 17rem;
    margin-left: 1rem;">
</section>

<br><!-- BR needed because section is inline-block -->
<hr color="#888" width="2px" />


<script>
  function myFunction() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    document.execCommand("Copy");
    alert("Copied the text: " + copyText.value);
  }
</script>

OUT;

  return $return . '<br>' . $outHTML;
}
add_filter("get_sample_permalink_html", "id_based_permalink", 10, 4);



/**
 * 
 * :: ADMIN: Calculate & Update Video Count for ALL Guests 
 * 
 * Calculate and update the video count for all guests based on reverse relationship.
 * 
 */
function calculate_and_update_video_count()
{
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

      // Get the related video IDs using the ACF reverse relationship field
      $related_videos = get_posts(
        array(
          'post_type' => 'videos', // Replace with your actual video post type
          'numberposts' => -1,
          'meta_query' => array(
            array(
              'key' => 'guest_names', // Replace with the actual ACF field name for reverse relationship
              'value' => get_the_ID(),
              'compare' => 'LIKE',
            ),
          ),
        )
      );

      // Calculate the video count
      $video_count = count($related_videos);
      $vid_count_field = 'video_count';

      // Update the custom field "_video_count"
      update_post_meta(get_the_ID(), $vid_count_field, $video_count);
    }

    // Reset the post data
    wp_reset_postdata();
  }
}

// Hook into an appropriate action (e.g., when saving a post)
add_action('save_post', 'calculate_and_update_video_count');



/**
 * 
 * #WORKS!
 * 
 * :: ADMIN: Output Videos and Counts for All Guests 
 * 
 * Output videos and counts for all guests based on reverse relationship.
 *  Counts videos for guests on-the-fly without storing value in DB.
 *  
 * #GOTCHA: There's not sort ordering here.
 * 
 * #GOTCHA: 'numberposts' => -1, // Don't limit the number of posts displayed (default limit is 5)
 * 
 * REFERENCE:
 *  - https://www.advancedcustomfields.com/resources/querying-relationship-fields/
 *  - https://code.tutsplus.com/mastering-wp_query-using-the-loop--cms-23031t
 * 
 */
function output_videos_counts_for_guests()
{
  // Get all guest posts (adjust the post type name if needed)
  $guests_query = new WP_Query(
    array(
      'post_type' => 'guests', // Replace with your actual guest post type
      'posts_per_page' => -1, // Retrieve all posts
    )
  );

  $rel_vids_arr = [];

  if ($guests_query->have_posts()) {
    while ($guests_query->have_posts()) {
      $guests_query->the_post();

      $guest_name = get_the_title();

      // Get the related video IDs using the ACF reverse relationship field
      $related_videos = get_posts(
        array(
          'post_type' => 'videos', // Replace with your actual video post type
          'numberposts' => -1,
          'meta_query' => array(
            array(
              'key' => 'guest_names', // Replace with the actual ACF field name for reverse relationship
              'value' => get_the_ID(),
              'compare' => 'LIKE',
            ),
          ),
        )
      );
?>
<style>
.guest-row {
  padding: .2rem 1rem;
  border: solid 2px red;
  margin-bottom: 0.2rem;
  background: #ffffe3;
  display: inline-block;
}
.guest-name {
  font-weight: 700;
  margin: .2rem 0;
  padding: 0;
}
.guest-details {
  color: brown;
  font-weight: 600;
}
</style>

      <blockquote class="guest-row">
        <h3 class="guest-name"><?php echo $guest_name; ?></h3>
        Video Count: <span class="guest-details"><?php echo count($related_videos); ?></span>
      </blockquote>

<?php

      // Calculate the video count
      $video_count = count($related_videos);

    }

    // Reset the post data
    wp_reset_postdata();
  }

  echo "<pre>" . print_r($rel_vids_arr, true) . "</pre>";

}

// Hook into an appropriate action (e.g., admin_notices -- however, we probably
//  want to convert to a shortcode).
// add_action('admin_notices', 'output_videos_counts_for_guests');





/**
 * 
 * :: PG TESTIMONIALS: Change Tab Order in Testimonials Form
 * 
 * Change tab order inside layout field
 * @requires: Changing form id
 * 
 * WHERE: Only run on front-end
 * 
 * REFERENCE:
 *  - https://wpforms.com/developers/how-to-change-the-tab-order-inside-the-layout-field/
 * 
 */
function wpf_dev_change_layout_field_tab_order()
{
  ?>

  <script type="text/javascript">

    const form_id = 21301;

    jQuery(function ($) {
      console.log(this);

      // form ID 20849 and field ID 8 - First Name field
      document.getElementById(`wpforms-${form_id}-field_8`).tabIndex = 1;

      // form ID 20849 and field ID 9 - Last Name field
      document.getElementById(`wpforms-${form_id}-field_9`).tabIndex = 2;

      // form ID 20849 and field ID 17 - Location field
      document.getElementById(`wpforms-${form_id}-field_17`).tabIndex = 3;

      // form ID 20849 and field ID 1 - Email field
      document.getElementById(`wpforms-${form_id}-field_1`).tabIndex = 4;

      // form ID 20849 and field ID 2 - Testimonial field
      document.getElementById(`wpforms-${form_id}-field_2`).tabIndex = 5;

      // form ID 20849 and field ID 2 - Testimonial field
      document.getElementById(`wpforms-${form_id}-field_2`).tabIndex = 6;

      // form ID 20849 and field ID 4 - Consent checkbox
      document.getElementById(`wpforms-${form_id}-field_4_1`).tabIndex = 7;

      // form ID 20849 and field ID 12 - Anonymous field
      document.getElementById(`wpforms-${form_id}-field_12_1`).tabIndex = 8;

      // form ID 20849 - submit button
      document.querySelector(`#wpforms-form-${form_id} button.wpforms-page-button.wpforms-page-next`).tabIndex = 9;


    });

  </script>

  <?php
}

add_action('wpforms_wp_footer_end', 'wpf_dev_change_layout_field_tab_order', 30);




/**
 * 
 * :: ADMIN: Generate Testimonial Post Title from ID
 * 
 * Generate testimonial title based on user first_name and location.
 * 
 * WHERE: Only run on back-end
 * 
 * REFERENCE:
 *  - https://toolset.com/forums/topic/how-to-auto-generate-post-title-using-post-id/
 *  - https://stackoverflow.com/questions/1233290/making-sure-php-substr-finishes-on-a-word-not-a-character/26098951#26098951
 *  - https://www.tutorialspoint.com/php_webview_online.php
 * 
 */
function gen_testimonial_title( $post_id ) {

  $target_post_type = 'testimonial'; //Change your post_type here
  
  if ( $target_post_type == get_post_type ( $post_id ) ) {  //Check and update for the specific $target_post_type
  
  $first_name = get_field('first_name', $post_id);
  $testimonial_loc = get_field('testimonial_location', $post_id);
  $loc_char_limit = 15;
  
  $s = substr($testimonial_loc, 0, $loc_char_limit); // Truncate the testimonial location value at 12 chars
  $result = substr($s, 0, strrpos($s, ' ')); // Work backwards to find the next space and split the string there, grabbing everything before the space
  $result = trim(preg_replace('/[^a-z0-9]+/i', ' ', $result)); // Replace all punctuation with underscores
  $result = preg_replace('/\s/i', '_', $result); // Replace all spaces with underscores

  $post_title = $target_post_type . '_' . $post_id . '_' . $first_name . '_' . $result; //Construct post_title
       
      $my_post = array(
          'ID'           => $post_id,
          'post_title' =>  $post_title
      );

      remove_action('save_post', 'gen_testimonial_title'); //Avoid the infinite loop

      // Update the post into the database
      wp_update_post( $my_post );     
       
  }
}
add_action( 'save_post', 'gen_testimonial_title' );










/*
This updated shortcode does the following:

Displays a launch button and an empty message div.
Includes JavaScript code that listens for the form submission event. Upon form submission, it prevents the default behavior, gathers form data, and sends an AJAX POST request to the WordPress backend (admin-ajax.php).
The AJAX request triggers the ehw_rename_all_testimonials function, which processes the testimonials by updating their titles in the database.
If the processing is successful, it sends a JSON success response back to the frontend JavaScript, which then displays a green success message in the message div.
If there's an error during processing, it sends a JSON error response, and a red error message is displayed.
To use this shortcode, you can add [process_testimonials] to any page or post content. When the "Launch" button is clicked, it will process the testimonials and display a success or error message accordingly. Note that this code assumes you have jQuery already included on your WordPress site for the admin-ajax.php to function correctly.


*/


// Define WordPress shortcode function
// Define WordPress shortcode function
function process_testimonials_shortcode() {
  ob_start(); // Start output buffering
  ?>
  <form id="process-testimonials-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="process_testimonials">
      <input type="submit" name="process_testimonials" value="Launch" class="button button-primary" />
  </form>
  <div id="process-testimonials-message"></div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Optional: You may want to log to console for client-side debugging
          console.log('Form loaded');
      });
  </script>
  <?php
  return ob_get_clean(); // Return buffered output
}

// Register shortcode
add_shortcode('process_testimonials', 'process_testimonials_shortcode');

// Handle form submission
function ehw_rename_all_testimonials() {
  global $wpdb;

  // Log to WordPress debug log
  if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
      error_log('Process testimonials action triggered');
  }

  // Get all posts of type 'testimonials'
  $testimonials = get_posts(array(
      'post_type' => 'testimonial',
      'posts_per_page' => -1,
  ));
  
  error_log('$testimonials:');
  // error_log($testimonials);

  if ($testimonials) {
      foreach ($testimonials as $testimonial) {
          $post_id = $testimonial->ID;

          // Calculate post title
          $first_name = get_field('first_name', $post_id);
          $testimonial_loc = get_field('testimonial_location', $post_id);
          $loc_char_limit = 15;
          
          $s = substr($testimonial_loc, 0, $loc_char_limit); // Truncate the testimonial location value at 12 chars
          $result = substr($s, 0, strrpos($s, ' ')); // Work backwards to find the next space and split the string there, grabbing everything before the space
          $result = trim(preg_replace('/[^a-z0-9]+/i', ' ', $result)); // Replace all punctuation with underscores
          $result = preg_replace('/\s/i', '_', $result); // Replace all spaces with underscores
        
          $post_title = 'testimonial' . '_' . $post_id . '_' . $first_name . '_' . $result; //Construct post_title


          // $post_title = gen_testimonial_title($post_id);
          error_log("\$post_title = $post_title");

          // Update post title in the database
          $wpdb->update(
              $wpdb->posts,
              array('post_title' => $post_title),
              array('ID' => $post_id)
          );
      }
      wp_safe_redirect(wp_get_referer()); // Redirect back to the referring page
      exit;
  } else {
      // Log to WordPress debug log
      if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
          error_log('No testimonials found');
      }
      wp_safe_redirect(wp_get_referer()); // Redirect back to the referring page
      exit;
  }
}

// Handle form submission for logged-in and non-logged-in users
add_action('admin_post_process_testimonials', 'ehw_rename_all_testimonials');
add_action('admin_post_nopriv_process_testimonials', 'ehw_rename_all_testimonials');

