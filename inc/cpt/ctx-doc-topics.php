<?php
/**
 * ctx-doc-topics.php
 * 
 * CUSTOM TAXONOMY: doc-topics
 * 
 */


add_action( 'init', 'register_tax_doc_topic' );
function register_tax_doc_topic() {
  $args = [
    'label'  => esc_html__( 'Doc Topics', 'ehw-site-plugin' ),
    'labels' => [
      'menu_name'                  => esc_html__( 'Doc Topics', 'ehw-site-plugin' ),
      'all_items'                  => esc_html__( 'All Doc Topics', 'ehw-site-plugin' ),
      'edit_item'                  => esc_html__( 'Edit Doc Topic', 'ehw-site-plugin' ),
      'view_item'                  => esc_html__( 'View Doc Topic', 'ehw-site-plugin' ),
      'update_item'                => esc_html__( 'Update Doc Topic', 'ehw-site-plugin' ),
      'add_new_item'               => esc_html__( 'Add new Doc Topic', 'ehw-site-plugin' ),
      'new_item'                   => esc_html__( 'New Doc Topic', 'ehw-site-plugin' ),
      'parent_item'                => esc_html__( 'Parent Doc Topic', 'ehw-site-plugin' ),
      'parent_item_colon'          => esc_html__( 'Parent Doc Topic', 'ehw-site-plugin' ),
      'search_items'               => esc_html__( 'Search Doc Topics', 'ehw-site-plugin' ),
      'popular_items'              => esc_html__( 'Popular Doc Topics', 'ehw-site-plugin' ),
      'separate_items_with_commas' => esc_html__( 'Separate Doc Topics with commas', 'ehw-site-plugin' ),
      'add_or_remove_items'        => esc_html__( 'Add or remove Doc Topics', 'ehw-site-plugin' ),
      'choose_from_most_used'      => esc_html__( 'Choose most used Doc Topics', 'ehw-site-plugin' ),
      'not_found'                  => esc_html__( 'No Doc Topics found', 'ehw-site-plugin' ),
      'name'                       => esc_html__( 'Doc Topics', 'ehw-site-plugin' ),
      'singular_name'              => esc_html__( 'Doc Topic', 'ehw-site-plugin' ),
    ],
    'public'               => true,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => true,
    'show_tagcloud'        => true,
    'show_in_quick_edit'   => true,
    'show_admin_column'    => true,
    'show_in_rest'         => true,
    'hierarchical'         => 'doc-topics',
    'query_var'            => true,
    'sort'                 => false,
    'rewrite_no_front'     => false,
    'rewrite_hierarchical' => true,
    'rewrite' => [ 'slug' => 'doc-topics' ]
  ];
  register_taxonomy( 'doc-topic', [ 'video', 'doc' ], $args );
}