<?php
/**
 * "Shelf-talker" pick custom post type — a staff-recommended book.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_pick_cpt() {
	register_post_type(
		'mvb_pick',
		array(
			'labels'       => array(
				'name'               => __( 'Picks', 'mvb-core' ),
				'singular_name'      => __( 'Pick', 'mvb-core' ),
				'add_new_item'       => __( 'Add New Pick', 'mvb-core' ),
				'edit_item'          => __( 'Edit Pick', 'mvb-core' ),
				'new_item'           => __( 'New Pick', 'mvb-core' ),
				'view_item'          => __( 'View Pick', 'mvb-core' ),
				'search_items'       => __( 'Search Picks', 'mvb-core' ),
				'not_found'          => __( 'No picks found', 'mvb-core' ),
				'all_items'          => __( 'Shelf Picks', 'mvb-core' ),
				'menu_name'          => __( 'Shelf Picks', 'mvb-core' ),
				'featured_image'     => __( 'Cover image', 'mvb-core' ),
				'set_featured_image' => __( 'Set cover image', 'mvb-core' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-book-alt',
			'supports'     => array( 'title', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'picks' ),
		)
	);
}
add_action( 'init', 'mvb_register_pick_cpt' );
