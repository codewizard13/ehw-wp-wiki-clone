<?php
/**
 * admin-login.php
 * 
 * Customize the WP Admin Dashboard Sidebar
 * 
 */

/**
* Change wpadmin login url link
*/
function wp_login_page_URL( $url ) {

  $url = home_url( '/' );
  return $url;

}
add_filter( 'login_headerurl', 'wp_login_page_URL');

/**
 * Replace default WordPress logo
 */
function add_logo_login_page() {

  $img_url = 'https://elijahstreams.com/wp-content/uploads/2023/02/Elijah-Streams-Logo_Horizontal-Full-Color.png';

  echo "<style>.login h1 a {
    background-repeat: no-repeat;
    background-image: url($img_url);
    background-position: center center;
    background-size: contain !important;
    width: 100% !important;
  }
  </style>";

}
add_action( 'login_head', 'add_logo_login_page' );

/**
 * Enqueue WP Login Styles
 */
function enqueue_login_style() {
  wp_enqueue_style( 'ehw-login-style', plugins_url('ehw-site-plugin/css/index.css')  );
  // wp_enqueue_style( 'ehw-login-style', plugins_url('ehw-client-plugin/js/index.js')  );
}
add_action( 'login_enqueue_scripts', 'enqueue_login_style' );