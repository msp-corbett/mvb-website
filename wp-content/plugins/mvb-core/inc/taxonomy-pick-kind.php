<?php
/**
 * "Kind" taxonomy for shelf-talker picks (Fiction, Aotearoa, Cooking, …),
 * used to drive the Bookshelf and Coming Soon filter bars. Shared by
 * mvb_pick and mvb_preorder so both are filed under the same kinds.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_pick_kind_taxonomy() {
	register_taxonomy(
		'pick_kind',
		array( 'mvb_pick', 'mvb_preorder' ),
		array(
			'labels'            => array(
				'name'          => __( 'Kinds', 'mvb-core' ),
				'singular_name' => __( 'Kind', 'mvb-core' ),
				'search_items'  => __( 'Search Kinds', 'mvb-core' ),
				'all_items'     => __( 'All Kinds', 'mvb-core' ),
				'edit_item'     => __( 'Edit Kind', 'mvb-core' ),
				'update_item'   => __( 'Update Kind', 'mvb-core' ),
				'add_new_item'  => __( 'Add New Kind', 'mvb-core' ),
				'new_item_name' => __( 'New Kind Name', 'mvb-core' ),
				'menu_name'     => __( 'Kinds', 'mvb-core' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'kind' ),
		)
	);
}
add_action( 'init', 'mvb_register_pick_kind_taxonomy' );
