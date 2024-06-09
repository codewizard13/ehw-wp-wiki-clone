<?php
/**
 * admin-adminbar.php
 * 
 * Customize the WP Dashboard Admin Bar
 * 
 */


/**
 * 
 * :: ADMINBAR: Purge your Object Cache
 * 
 * Adds a button to the WordPress dashboard to clear the object cache.
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 */
/*
Plugin Name: Purge Cache
Description: 
*/

add_action('admin_bar_menu', 'add_purge_cache_button', 999);

function add_purge_cache_button($wp_admin_bar)
{
  if (!current_user_can('manage_options')) {
    return;
  }


  $title_str = "<h2 style='color: rgba(255,255,255,.5); background:brown; padding: 0 1rem; font-weight: 700; font-weight; 900; font-family: Roboto, Arial, sans-serif; text-align: right;'>Purge Cache</h2>";

  $args = array(
    'id' => 'purge-cache',
    'title' => $title_str,
    'href' => '#',
    'meta' => array(
      'class' => 'purge-cache',
    )
  );
  $wp_admin_bar->add_node($args);
}

add_action('admin_footer', 'add_purge_cache_script');

function add_purge_cache_script()
{
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>
  <script>
    jQuery(document).ready(function ($) {
      $('#wp-admin-bar-purge-cache').click(function () {
        if (confirm('Are you sure you want to purge the cache?')) {
          $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
              action: 'purge_cache',
            },
            success: function () {
              alert('Cache purged successfully!');
            },
            error: function () {
              alert('An error occurred while purging the cache.');
            }
          });
        }
      });
    });
  </script>
  <?php
}

add_action('wp_ajax_purge_cache', 'purge_cache_callback');

function purge_cache_callback()
{
  global $wp_object_cache;
  if (!current_user_can('manage_options')) {
    wp_die();
  }

  wp_cache_flush();

  wp_die();
}



/**
 * 
 * :: ADMINBAR: Add CPT Count
 * 
 * Add custom post type (CPT) count to admin bar.
 * 
 * REFERENCE:
 *  - https://wordpress.stackexchange.com/questions/238668/how-to-add-some-custom-html-into-wordpress-admin-bar
 *  - https://wordpress.stackexchange.com/questions/403752/fastest-way-of-counting-posts-of-a-custom-post-type-in-a-specific-taxonomy-term
 *  - https://wp-kama.com/function/wp_count_posts
 *  - https://stackoverflow.com/questions/67809404/use-a-parameter-in-an-add-action-function
 *  - https://developer.wordpress.org/reference/functions/do_action_ref_array/
 *  - https://developer.wordpress.org/reference/functions/do_action/
 *  - https://stackoverflow.com/questions/2843356/can-i-pass-arguments-to-my-function-through-add-action
 * 
 */
// add_action('admin_bar_menu', 'ehw_adminbar_add_cpt_count', 900, 2);
// // do_action('ehw_adminbar_add_cpt_count', $wp_admin_bar, 'videos');

// function ehw_adminbar_add_cpt_count($wp_admin_bar, $cpt_name)
// {

//   $bgcolor = "navy";

//   $cpt_posts_published = wp_count_posts($post_type = $cpt_name)->publish;
//   // echo "<pre>" . "wp_count_posts for videos: $cpt_posts_published" . "</pre>";


//   $admin_bar_args = array(
//     'id' => 'ehw_'. $cpt_name. '_count', // this is the ID of the section on the adminbar
//     'title' => "CPT Posts: $cpt_posts_published" // this is the visible portion in the admin bar.
//   );

//   $wp_admin_bar->add_node($admin_bar_args);
// }



/**
 * 
 *  #DOESNT_WORK!!!
 * 
 * :: ADMINBAR: Add Episodes CPT Count
 * 
 * Add custom term count to admin bar.
 *  
 */
add_action('admin_bar_menu', 'ehw_adminbar_add_episodes_count', 900);

function ehw_adminbar_add_episodes_count($wp_admin_bar)
{

  // $the_query = new WP_Query( array(
  //   'post_type'     => 'post',
  //   'no_found_rows' => true,
  //   'tax_query'     => array( array(
  //      'taxonomy'   => ,
  //      'field'      => 'term_id',
  //      'terms'      => $term->term_id
  //   ) ),
  //   'no_found_rows' => true,    // Don't calculate pagination
  //   'fields'        => 'ids',
  // ) );

  $post_type = 'videos';
  $taxonomy = 'video-category';
  $terms = ['episodes'];

  $args = [
    'post_type' => 'videos',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'tax_query' => [
      'taxonomy' => 'video-category',
      'field' => 'slug',
      'terms' => ['episodes'],
      'include_children' => false
    ]
  ];

  $query = new WP_Query($args);
  $post_count = $query->found_posts;

  // echo "<pre>" . "WP_Query->found_posts with tax_query for episodes: $post_count" . "</pre>";

  // $video_posts_published = wp_count_posts( $post_type = 'videos')->publish;

  $admin_bar_args = array(
    'id' => 'ehw_episode_count',
    'title' => "Episodes: $post_count" // this is the visible portion in the admin bar.
  );

  $wp_admin_bar->add_node($admin_bar_args);
}



/**
 * 
 * :: ADMINBAR: Add Guests CPT Count
 * 
 * Add custom post type (CPT) count to admin bar.
 * 
 * REFERENCE:
 *  - https://wordpress.stackexchange.com/questions/238668/how-to-add-some-custom-html-into-wordpress-admin-bar
 * 
 */
// add_action('admin_bar_menu', 'ehw_admin_bar_add_guests_count', 900);

// function ehw_admin_bar_add_guests_count($wp_admin_bar)
// {

//   $bgcolor = "navy";

//   $guest_posts_published = wp_count_posts($post_type = 'guests')->publish;

//   $admin_bar_args = array(
//     'id' => 'ehw_guests_count',
//     'title' => "Guests: $guest_posts_published" // this is the visible portion in the admin bar.
//   );

//   $wp_admin_bar->add_node($admin_bar_args);
// }



/**
 * 
 * :: ADMINBAR: Add Time & Date to Right Side
 * 
 * Add date and time to right side of the admin bar near the "Howdy" / avatar section
 *
 * CAVEAT: This code is tested and verified it works as of Sept 2023. However,
 *  WordPress is always changing, so it is possible if you are reading this in ten years it may not be valid anymore.
 * 
 *  - $parent_slug : this is an id you make up. It should be self explanatory,
 *     in this case 'adminbar-date-time'
 * 
 *  - 'top-secondary' : tells WP to put this node / link / text on the right side
 * 
 *  - the "500" in add_action() : means keep it on the leftmost of that
 *     rightmost section
 * 
 *  - $local_time : uses the WP API current_time() function to grab the time
 *     and date for the timezone registered in Settings > General
 * 
 *  - $title : This the text that actually displays in the admin bar. Can be
 *     text, variables, and HTML. Here we calculate the date and time first as
 *     $local_time and the result of that  variable is what will display in the
 *     admin bar. If we wanted we could do 'title' => __( 4 * 8 ), and "32" is
 *     what would be shown in the admin bar.
 * 
 *  - href : If you are making this a hyperlink then put the destination URL
 *     here. In this example, "options-general.php" is the Settings > 
 *     General page so you can change time and date if you want
 * 
 */
add_action('admin_bar_menu', 'ehw_add_date_time_adminbar_right', 500);
function ehw_add_date_time_adminbar_right(WP_Admin_Bar $wp_admin_bar)
{

  $parent_slug = 'ehw-adminbar-date-time';

  $local_time = date('Y-m-d, g:i a', current_time('timestamp', 0));

  $wp_admin_bar->add_menu(
    array(
      'id' => $parent_slug,
      'parent' => 'top-secondary',
      'group' => null,
      'title' => __("🕒 $local_time"),
      'href' => admin_url('/options-general.php'),
    )
  );

}



/**
 * 
 * :: ADMINBAR: Add Site Version
 * 
 * Add site version to right side of admin bar.
 * 
 */
add_action('admin_bar_menu', 'ehw_add_site_version_admin_bar', 600);
function ehw_add_site_version_admin_bar(WP_Admin_Bar $wp_admin_bar)
{
  ?>

  <style>
    li#wp-admin-bar-ehw-adminbar-site-version {
      background: whitesmoke !important;
    }

    li#wp-admin-bar-ehw-adminbar-site-version * {
      background: inherit !important;
    }
  </style>

  <?php


  if (get_option('site_version')) {
    $site_ver = get_option('site_version');
    $title_str = "<h2 style='color:brown; font-weight: 700; font-weight; 900; font-family: Roboto, Arial, sans-serif; text-align: right;'>$site_ver</h2>";
  }


  $parent_slug = 'ehw-adminbar-site-version';

  $wp_admin_bar->add_menu(
    array(
      'id' => $parent_slug,
      'parent' => 'top-secondary',
      'group' => null,
      'title' => __($title_str),
      'href' => null,
    )
  );

}



/**
 * 
 * :: ADMINBAR: Add Credits Link on right
 * 
 * Add custom link to "Credits" page.
 * 
 */
add_action('admin_bar_menu', 'toolbar_link_to_mypage', 999);

function toolbar_link_to_mypage($wp_admin_bar)
{
  $args = array(
    'id' => 'ehw_btn_credits',
    'title' => 'Credits',
    'href' => '/credits/',
    'meta' => array('class' => 'ehw-custom-adminbar-link')
  );
  $wp_admin_bar->add_node($args);
}



/**
 * 
 * :: ADMINBAR: Add Link with no Hover on right for Elementor Tools
 * 
 * Add custom link to "Elementor Tools" page.
 * 
 */
add_action('admin_bar_menu', 'admin_bar_menus', 500);
function admin_bar_menus(WP_Admin_Bar $wp_admin_bar)
{

  $parent_slug = 'ehw-adminbar-ele-tools';

  $wp_admin_bar->add_menu(
    array(
      'id' => $parent_slug,
      'parent' => 'top-secondary',
      'group' => null,
      'title' => __('Elementor Tools', 'ehw'), // This second param is "text domain", but I'm not sure why
      'href' => esc_url(admin_url('admin.php?page=elementor-tools')),
    )
  );

}

// http://lardev-dev-es-562.test/wp-admin/admin.php?page=elementor-tools



/**
 * 
 * :: ADMINBAR: Add Link with Avatar / Icon Image
 * 
 * Add custom link with icon image.
 * 
 */
function adminbar_img_link(WP_Admin_Bar $admin_bar)
{
  if (!current_user_can('manage_options')) {
    return;
  }

  $icon_link = "/wp-content/uploads/2023/02/favicon-android-chrome-512x512-1-150x150.png";

  $admin_bar->add_menu(
    array(
      'id' => 'ehw-img-link',
      'parent' => null,
      'group' => null,
      'title' => "<img src='$icon_link' style='height: 70%;'>", //you can use img tag with image link. it will show the image icon Instead of the title.
      'href' => esc_url(admin_url('edit.php?post_type=acf-field-group')),
      'meta' => [
        'title' => __('ACF: Field Groups', 'ehw'), //This title will show on hover
      ]
    )
  );
}

add_action('admin_bar_menu', 'adminbar_img_link', 900);



/**
 * 
 * :: ADMINBAR: Add Testimonials CPT count
 * 
 * Add the count of a specific custom post type to admin bar.
 * 
 * REFERENCE:
 *  - https://wordpress.stackexchange.com/questions/238668/how-to-add-some-custom-html-into-wordpress-admin-bar
 *  - https://developer.wordpress.org/reference/classes/wp_admin_bar/
 *  - https://wordpress.stackexchange.com/questions/3233/showing-users-post-counts-by-custom-post-type-in-the-admins-user-list
 * 
 */
add_action('admin_bar_menu', 'ehw_admin_bar', 900);
// The first argument is the name of the hook,
// the second is the callback function that you see below,
// and the 900 denotes the priority with which the hook is called
//This high number means this code will run later, and thus show up at the end of the list.

function ehw_admin_bar($wp_admin_bar)
{
  $args = array(
    //Type & Status Parameters
    'post_type' => 'testimonial',
    // 'post_status' => ['publish', 'draft', 'private', 'pending'],
    'post_status' => ['pending', 'publish'],
  );

  $ehw_cpt = new WP_Query($args);

  $admin_bar_args = array(
    'id' => 'ehw-testimonial-count'
    ,
    'title' => '📜 Testimonials:' . count($ehw_cpt->posts) // this is the visible portion in the admin bar.
  );

  $wp_admin_bar->add_node($admin_bar_args);
}



/**
 * 
 * :: ADMINBAR: Add Menu with Submenus
 * 
 * Add custom link to "Elementor Tools" page.
 * 
 * REFERENCE:
 *  - https://developer.wordpress.org/reference/classes/wp_admin_bar/add_node/#comment-251
 * 
 */
// add_action( 'admin_bar_menu', 'add_top_link_to_admin_bar',999 );

function add_top_link_to_admin_bar($admin_bar) {
         // add a parent item
         	$args = array(
         		'id'    => 'custom',
         		'title' => 'Custom Made',
         		'href'   => 'http://example.com/', // Showing how to add an external link
         	);
         	$admin_bar->add_node( $args );
         	
         // add a child item to our parent item	
         	$args = array(
         		'parent' => 'custom',
         		'id'     => 'media-libray',
         		'title'  => 'Media Library',
         		'href'   => esc_url( admin_url( 'upload.php' ) ),
         		'meta'   => false		
         	);
         	$admin_bar->add_node( $args );
         	
         // add a child item to our parent item	
         	$args = array(
         		'parent' => 'custom',
         		'id'     => 'plugins',
         		'title'  => 'Plugins',
         		'href'   => esc_url( admin_url( 'plugins.php' ) ),
         		'meta'   => false		
         	);
         	$admin_bar->add_node( $args );     	         		 
}



/**
 * 
 * :: ADMINBAR: Add Menu with Attributes
 * 
 * Example of having the 'meta' properties completly filled out.
 * This is a custom toolbar I generated with GenerateWP
 * 
 * REFERENCE:
 *  - https://generatewp.com/snippet/5XlR5lb/
 * 
 */
function custom_toolbar() {
	global $wp_admin_bar;

	$args = array(
		'id'     => 'menu-1',
		'title'  => __( 'Toolbar Menu', 'text_domain' ),
		'href'   => 'https://.../',
		'meta'   => array(
			'html'     => '<span style="color:green">Dummy HTML Here</span>',
			'class'    => 'custom-toolbar-class',
			'target'   => '_top',
			'onclick'  => 'doThisJS()',
			'title'    => 'Toolbar Menu',
			'tabindex' => '1',
		),
	);
	$wp_admin_bar->add_menu( $args );

}
// add_action( 'wp_before_admin_bar_render', 'custom_toolbar', 999 );




/**
 * 
 * :: ADMINBAR: Add Menu with Group Children
 * 
 * This example adds a parent node, child nodes and a group to the toolbar.
 * 
 * REFERENCE:
 *  - https://developer.wordpress.org/reference/classes/wp_admin_bar/add_group/#comment-1480
 * 
 */
function add_nodes_and_groups_to_toolbar( $wp_admin_bar ) {

	// add a parent item
	$args = array(
		'id'    => 'parent_node',
		'title' => 'parent node'
	);
	$wp_admin_bar->add_node( $args );

	// add a child item to our parent item
	$args = array(
		'id'     => 'child_node',
		'title'  => 'child node',
		'parent' => 'parent_node'
	);
	$wp_admin_bar->add_node( $args );

	// add a group node with a class "first-toolbar-group"
	$args = array(
		'id'     => 'first_group',
		'parent' => 'parent_node',
		'meta'   => array( 'class' => 'first-toolbar-group' )
	);
	$wp_admin_bar->add_group( $args );

	// add an item to our group item
	$args = array(
		'id'     => 'first_grouped_node',
		'title'  => 'first group node',
		'parent' => 'first_group'
	);
	$wp_admin_bar->add_node( $args );

	// add another child item to our parent item (not to our first group)
	$args = array(
		'id'     => 'another_child_node',
		'title'  => 'another child node',
		'parent' => 'parent_node'
	);
	$wp_admin_bar->add_node( $args );

}
// add_action( 'admin_bar_menu', 'add_nodes_and_groups_to_toolbar', 999 );





