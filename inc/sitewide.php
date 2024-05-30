<?php
/**
 * sitewide.php
 * 
 * Code the applies to both frontend and backend.
 * 
 */


/**
 * 
 * :: SITEWIDE: Disable Google Fonts in Astra Theme
 * 
 * Here are the reasons to disable Google Fonts:
 * 
 * - Google Fonts can slow down your website as it requires the site to fetch
 *     fonts from another server, impacting loading speed and site load time.
 * 
 * - Using Google Fonts compromises privacy as it shares visitor data with
 *     Google, raising concerns for privacy-conscious individuals.
 * 
 * - Google Fonts does not follow GDPR regulations, which are crucial for
 *     websites engaging with European visitors, potentially complicating
 *     adherence to these regulations.
 * 
 * TAGS: PERFORMANCE
 * 
 * 
 * REFERENCE:
 *  - https://wpastra.com/docs/remove-google-fonts-suggestions-in-astra-theme/
 *  - https://technumero.com/remove-google-fonts-from-wordpress-theme
 * 
 */
add_filter('astra_google_fonts', '__return_empty_array');



/**
 * 
 * :: SITEWIDE: Ensure Webfont is Loaded in Elementor
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 * Purpose:
 * Google requires that your text (web fonts, font icons, etc.) always remain
 *  visible as your WordPress site loads. This impacts both First Contentful
 *  Paint (FCP) and Largest Contentful Paint (LCP), as well as the user
 *  experience. If your text flashes or takes a while to load, you’ll see the
 *  warning in Google PageSpeed Insights to “ensure text remains visible during
 *  webfont load.”
 * 
 * TAGS: PERFORMANCE, ELEMENTOR
 * 
 * REFERENCE:
 *  - https://perfmatters.io/docs/ensure-text-remains-visible-during-webfont-load/
 * 
 */
add_filter('elementor_pro/custom_fonts/font_display', function ($current_value, $font_family, $data) {
  return 'swap';
}, 10, 3);



/**
 * 
 * :: SITEWIDE: Remove Unused JS
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 * Purpose:
 * We will Dequeue the jQuery UI script as example.
 *
 * Hooked to the wp_print_scripts action, with a late priority (99),
 * so that it is after the script was enqueued.
 * 
 * #GOTCHA: Not 100% how this works or what it does.
 * 
 * TAGS: PERFORMANCE
 * 
 * REFERENCE:
 *  - https://www.youtube.com/watch?v=Lr-4RHsEKjY&t=1301s
 *  - https://websquadron.co.uk/page-speed-performance-wordpress-code-snippets/
 * 
 */
function wp_remove_scripts()
{
  // check if user is admina
  if (current_user_can('update_core')) {
    return;
  } else {
    // Check for the page you want to target
    if (is_page('homepage')) {
      // Remove Scripts
      wp_dequeue_style('jquery-ui-core');
    }
  }
}
add_action('wp_enqueue_scripts', 'wp_remove_scripts', 99);



/**
 * 
 * :: SITEWIDE: Stop Lazy Load
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 * #GOTCHA: Not 100% how this works or what it does.
 * 
 * TAGS: PERFORMANCE
 * 
 * REFERENCE:
 *  - https://www.youtube.com/watch?v=Lr-4RHsEKjY&t=1301s
 *  - https://websquadron.co.uk/page-speed-performance-wordpress-code-snippets/
 * 
 */
add_filter('wp_lazy_loading_enabled', '__return_false');



/**
 * 
 * :: SITEWIDE: Remove Google Fonts in Elementor
 * 
 * Inspired by: Imran (WebSquadron)
 * 
 * Remove Google fonts from Elementor.
 * 
 * TAGS: PERFORMANCE, ELEMENTOR
 * 
 * REFERENCE:
 *  - https://www.youtube.com/watch?v=Lr-4RHsEKjY&t=1301s
 *  - https://websquadron.co.uk/page-speed-performance-wordpress-code-snippets/
 * 
 */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );