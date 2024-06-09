<?php
/**
 * cpt-video.php
 * 
 * CUSTOM POST TYPE: Video
 * 
 */


add_action( 'init', 'register_cpt_video' );
function register_cpt_video() {
	$args = [
		'label'  => esc_html__( 'Videos', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Videos', 'ehw-site-plugin' ),
			'name_admin_bar'     => esc_html__( 'Video', 'ehw-site-plugin' ),
			'add_new'            => esc_html__( 'Add Video', 'ehw-site-plugin' ),
			'add_new_item'       => esc_html__( 'Add new Video', 'ehw-site-plugin' ),
			'new_item'           => esc_html__( 'New Video', 'ehw-site-plugin' ),
			'edit_item'          => esc_html__( 'Edit Video', 'ehw-site-plugin' ),
			'view_item'          => esc_html__( 'View Video', 'ehw-site-plugin' ),
			'update_item'        => esc_html__( 'View Video', 'ehw-site-plugin' ),
			'all_items'          => esc_html__( 'All Videos', 'ehw-site-plugin' ),
			'search_items'       => esc_html__( 'Search Videos', 'ehw-site-plugin' ),
			'parent_item_colon'  => esc_html__( 'Parent Video', 'ehw-site-plugin' ),
			'not_found'          => esc_html__( 'No Videos found', 'ehw-site-plugin' ),
			'not_found_in_trash' => esc_html__( 'No Videos found in Trash', 'ehw-site-plugin' ),
			'name'               => esc_html__( 'Videos', 'ehw-site-plugin' ),
			'singular_name'      => esc_html__( 'Video', 'ehw-site-plugin' ),
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
		'has_archive'         => 'videos',
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-video-alt3',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
			'revisions',
		],
		'taxonomies' => [
			'category',
			'post_tag',
		],
		'rewrite' => [ 'slug' => 'videos' ]
	];

	register_post_type( 'video', $args );
}