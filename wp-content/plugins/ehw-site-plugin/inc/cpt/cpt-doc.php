<?php
/**
 * cpt-video.php
 * 
 * CUSTOM POST TYPE: Video
 * 
 */


add_action( 'init', 'register_cpt_doc' );
function register_cpt_doc() {
	$args = [
		'label'  => esc_html__( 'Docs', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Docs', 'ehw-site-plugin' ),
			'name_admin_bar'     => esc_html__( 'Doc', 'ehw-site-plugin' ),
			'add_new'            => esc_html__( 'Add Doc', 'ehw-site-plugin' ),
			'add_new_item'       => esc_html__( 'Add new Doc', 'ehw-site-plugin' ),
			'new_item'           => esc_html__( 'New Doc', 'ehw-site-plugin' ),
			'edit_item'          => esc_html__( 'Edit Doc', 'ehw-site-plugin' ),
			'view_item'          => esc_html__( 'View Doc', 'ehw-site-plugin' ),
			'update_item'        => esc_html__( 'View Doc', 'ehw-site-plugin' ),
			'all_items'          => esc_html__( 'All Docs', 'ehw-site-plugin' ),
			'search_items'       => esc_html__( 'Search Docs', 'ehw-site-plugin' ),
			'parent_item_colon'  => esc_html__( 'Parent Doc', 'ehw-site-plugin' ),
			'not_found'          => esc_html__( 'No Docs found', 'ehw-site-plugin' ),
			'not_found_in_trash' => esc_html__( 'No Docs found in Trash', 'ehw-site-plugin' ),
			'name'               => esc_html__( 'Docs', 'ehw-site-plugin' ),
			'singular_name'      => esc_html__( 'Doc', 'ehw-site-plugin' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'capability_type'     => 'post',
		'hierarchical'        => true,
		'has_archive'         => 'docs',
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-aside',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
			'revisions',
		],
		'taxonomies' => [
		],
		'rewrite' => [ 'slug' => 'docs' ]
	];

	register_post_type( 'doc', $args );
}