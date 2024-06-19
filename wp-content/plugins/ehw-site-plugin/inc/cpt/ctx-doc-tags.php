<?php
/**
 * ctx-doc-topics.php
 * 
 * CUSTOM TAXONOMY: doc-topics
 * 
 */


add_action( 'init', 'register_tax_doc_tag' );
function register_tax_doc_tag() {
  $args = [
    'label'  => esc_html__( 'Doc Tags', 'ehw-site-plugin' ),
    'labels' => [
      'menu_name'                  => esc_html__( 'Doc Tags', 'ehw-site-plugin' ),
      'all_items'                  => esc_html__( 'All Doc Tags', 'ehw-site-plugin' ),
      'edit_item'                  => esc_html__( 'Edit Doc Tag', 'ehw-site-plugin' ),
      'view_item'                  => esc_html__( 'View Doc Tag', 'ehw-site-plugin' ),
      'update_item'                => esc_html__( 'Update Doc Tag', 'ehw-site-plugin' ),
      'add_new_item'               => esc_html__( 'Add new Doc Tag', 'ehw-site-plugin' ),
      'new_item'                   => esc_html__( 'New Doc Tag', 'ehw-site-plugin' ),
      'parent_item'                => esc_html__( 'Parent Doc Tag', 'ehw-site-plugin' ),
      'parent_item_colon'          => esc_html__( 'Parent Doc Tag', 'ehw-site-plugin' ),
      'search_items'               => esc_html__( 'Search Doc Tags', 'ehw-site-plugin' ),
      'popular_items'              => esc_html__( 'Popular Doc Tags', 'ehw-site-plugin' ),
      'separate_items_with_commas' => esc_html__( 'Separate Doc Tags with commas', 'ehw-site-plugin' ),
      'add_or_remove_items'        => esc_html__( 'Add or remove Doc Tags', 'ehw-site-plugin' ),
      'choose_from_most_used'      => esc_html__( 'Choose most used Doc Tags', 'ehw-site-plugin' ),
      'not_found'                  => esc_html__( 'No Doc Tags found', 'ehw-site-plugin' ),
      'name'                       => esc_html__( 'Doc Tags', 'ehw-site-plugin' ),
      'singular_name'              => esc_html__( 'Doc Tag', 'ehw-site-plugin' ),
    ],
    'public'               => true,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => true,
    'show_tagcloud'        => true,
    'show_in_quick_edit'   => true,
    'show_admin_column'    => true,
    'show_in_rest'         => true,
    'hierarchical'         => false,
    'query_var'            => true,
    'sort'                 => false,
    'rewrite_no_front'     => false,
    'rewrite_hierarchical' => false,
    'rewrite' => [ 'slug' => 'doc-tags' ]
  ];
  register_taxonomy( 'doc-tag', [ 'video', 'doc' ], $args );
}