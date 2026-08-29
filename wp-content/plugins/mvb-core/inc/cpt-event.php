<?php
/**
 * Event custom post type — author evenings, book club, story time.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_event_cpt() {
	register_post_type(
		'mvb_event',
		array(
			'labels'       => array(
				'name'          => __( 'Events', 'mvb-core' ),
				'singular_name' => __( 'Event', 'mvb-core' ),
				'add_new_item'  => __( 'Add New Event', 'mvb-core' ),
				'edit_item'     => __( 'Edit Event', 'mvb-core' ),
				'new_item'      => __( 'New Event', 'mvb-core' ),
				'view_item'     => __( 'View Event', 'mvb-core' ),
				'search_items'  => __( 'Search Events', 'mvb-core' ),
				'not_found'     => __( 'No events found', 'mvb-core' ),
				'all_items'     => __( 'Events', 'mvb-core' ),
				'menu_name'     => __( 'Events', 'mvb-core' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'supports'     => array( 'title', 'editor' ),
			'rewrite'      => array( 'slug' => 'events' ),
		)
	);
}
add_action( 'init', 'mvb_register_event_cpt' );
