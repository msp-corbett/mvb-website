<?php
/**
 * A small REST route behind the homepage's "Show me three more" button —
 * everything else on the site is server-rendered with no JS required.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_register_rest_routes() {
	register_rest_route(
		'mvb/v1',
		'/picks/random',
		array(
			'methods'             => 'GET',
			'callback'            => 'mvb_rest_random_picks',
			'permission_callback' => '__return_true',
			'args'                => array(
				'count'   => array(
					'sanitize_callback' => 'absint',
				),
				'exclude' => array(
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'mvb_register_rest_routes' );

function mvb_rest_random_picks( WP_REST_Request $request ) {
	$count = (int) $request->get_param( 'count' );
	$count = $count > 0 ? min( 12, $count ) : 3;

	$exclude = array_filter( array_map( 'intval', explode( ',', (string) $request->get_param( 'exclude' ) ) ) );

	$query = new WP_Query(
		array(
			'post_type'      => 'mvb_pick',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'orderby'        => 'rand',
			'post__not_in'   => $exclude,
			'no_found_rows'  => true,
		)
	);

	// If excluding the currently-shown picks leaves fewer than requested
	// (small catalog), top back up from the full set rather than showing less.
	if ( count( $query->posts ) < $count ) {
		$topup = new WP_Query(
			array(
				'post_type'      => 'mvb_pick',
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'orderby'        => 'rand',
				'no_found_rows'  => true,
			)
		);
		$query = $topup;
	}

	return rest_ensure_response( array_map( 'mvb_format_pick_for_rest', $query->posts ) );
}

function mvb_format_pick_for_rest( $post ) {
	$terms = get_the_terms( $post->ID, 'pick_kind' );
	$kind  = ( $terms && ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';

	// The payload shape itself (mvb_build_pick_payload) is unit-tested in
	// tests/Unit/PickPayloadTest.php; this function's job is just to pull
	// the primitive values off a real WP_Post/meta, which needs WordPress
	// loaded and so isn't part of that isolated suite.
	return mvb_build_pick_payload(
		array(
			'id'     => $post->ID,
			'kind'   => $kind,
			'title'  => get_the_title( $post ),
			'author' => get_post_meta( $post->ID, 'book_author', true ),
			'by'     => get_post_meta( $post->ID, 'picked_by', true ),
			'note'   => get_post_meta( $post->ID, 'pick_note', true ),
			'buyUrl' => get_post_meta( $post->ID, 'buy_url', true ),
			'cover'  => get_the_post_thumbnail_url( $post->ID, 'medium' ) ?: '',
		)
	);
}
