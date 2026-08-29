<?php
/**
 * "Top ten" curated list custom post type. Books inside a list are stored
 * as a repeater in post meta (see inc/meta-boxes.php) rather than as their
 * own posts — they have no independent detail page, cover, or review.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_list_cpt() {
	register_post_type(
		'mvb_list',
		array(
			'labels'       => array(
				'name'          => __( 'Lists', 'mvb-core' ),
				'singular_name' => __( 'List', 'mvb-core' ),
				'add_new_item'  => __( 'Add New List', 'mvb-core' ),
				'edit_item'     => __( 'Edit List', 'mvb-core' ),
				'new_item'      => __( 'New List', 'mvb-core' ),
				'view_item'     => __( 'View List', 'mvb-core' ),
				'search_items'  => __( 'Search Lists', 'mvb-core' ),
				'not_found'     => __( 'No lists found', 'mvb-core' ),
				'all_items'     => __( 'Top Ten Lists', 'mvb-core' ),
				'menu_name'     => __( 'Top Ten Lists', 'mvb-core' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-list-view',
			'supports'     => array( 'title' ),
			'rewrite'      => array( 'slug' => 'lists' ),
		)
	);
}
add_action( 'init', 'mvb_register_list_cpt' );
