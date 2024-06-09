<?php // V. 0.5 - From chatGPT (PHP loop approach) WORKS


/**
 * Calculate and update the video count for all guests based on reverse relationship.
 */
function admin_display_guest_vid_total()
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
            displayGuestVidCount($guest_name, $video_count);
        }

        // Reset the post data
        wp_reset_postdata();
    }

	// Close the section
	echo "</section>";
}

add_action('admin_notices', 'admin_display_guest_vid_total');

function displayGuestVidCount($guest_name, $vid_count)
{
    $html = <<<HTML
    <article class="admin-debug-row">
        <h3>Guest <span class='details'>$guest_name</span> appears in <span class='details'>$vid_count</span> videos.</h3>
    </article>
    HTML;

    echo $html;
}
