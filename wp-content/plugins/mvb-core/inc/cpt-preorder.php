<?php
/**
 * "Coming soon" pre-order custom post type — a shelf-talker for a book that
 * isn't out yet. Same fields and pick_kind taxonomy as mvb_pick, plus an
 * expected arrival month (see inc/meta-boxes.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_preorder_cpt() {
	register_post_type(
		'mvb_preorder',
		array(
			'labels'       => array(
				'name'               => __( 'Coming Soon', 'mvb-core' ),
				'singular_name'      => __( 'Coming Soon Book', 'mvb-core' ),
				'add_new_item'       => __( 'Add New Coming Soon Book', 'mvb-core' ),
				'edit_item'          => __( 'Edit Coming Soon Book', 'mvb-core' ),
				'new_item'           => __( 'New Coming Soon Book', 'mvb-core' ),
				'view_item'          => __( 'View Coming Soon Book', 'mvb-core' ),
				'search_items'       => __( 'Search Coming Soon', 'mvb-core' ),
				'not_found'          => __( 'No coming soon books found', 'mvb-core' ),
				'all_items'          => __( 'Coming Soon', 'mvb-core' ),
				'menu_name'          => __( 'Coming Soon', 'mvb-core' ),
				'featured_image'     => __( 'Cover image', 'mvb-core' ),
				'set_featured_image' => __( 'Set cover image', 'mvb-core' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-clock',
			'supports'     => array( 'title', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'preorders' ),
		)
	);
}
add_action( 'init', 'mvb_register_preorder_cpt' );
