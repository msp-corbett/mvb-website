<?php
/**
 * Small WordPress-coupled helpers shared by the templates. Business rules
 * that don't need WordPress live in inc/pure-functions.php instead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Markup for one use of the bird mark (the sprite itself is inlined once
 * in header.php via template-parts/svg-sprite.php).
 */
function mvb_bird_icon( string $class = 'bird' ): string {
	return sprintf( '<svg class="%s" aria-hidden="true"><use href="#bird"/></svg>', esc_attr( $class ) );
}

/**
 * $count random shelf-talker picks (or, with $post_type 'mvb_preorder',
 * coming-soon books), topped back up from the full set if excluding
 * $exclude_ids would otherwise leave fewer than requested (the same "don't
 * show fewer just to avoid an immediate repeat" rule the REST endpoint uses
 * for the JS-powered reshuffle).
 */
function mvb_get_random_picks( int $count = 3, array $exclude_ids = array(), string $post_type = 'mvb_pick' ): array {
	$query = new WP_Query(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'orderby'        => 'rand',
			'post__not_in'   => $exclude_ids,
			'no_found_rows'  => true,
		)
	);

	if ( count( $query->posts ) < $count ) {
		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'orderby'        => 'rand',
				'no_found_rows'  => true,
			)
		);
	}

	return $query->posts;
}

/**
 * One random "top ten" list, or null if none exist yet.
 */
function mvb_get_random_list(): ?WP_Post {
	$query = new WP_Query(
		array(
			'post_type'      => 'mvb_list',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'rand',
			'no_found_rows'  => true,
		)
	);
	return $query->posts[0] ?? null;
}

/**
 * Upcoming mvb_event posts (event_date >= today), soonest first. Past
 * events never come back from this query at all — nothing to filter out
 * downstream.
 */
function mvb_get_upcoming_events( int $limit = -1 ): array {
	$query = new WP_Query(
		array(
			'post_type'      => 'mvb_event',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => 'event_date',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => 'event_date',
					'value'   => current_time( 'Ymd' ),
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);
	return $query->posts;
}

/**
 * Every published coming-soon book, soonest expected arrival first (then
 * by title). arrival_month is stored as Ym, so it sorts as a string. The
 * NOT EXISTS clause keeps a book with no month saved on the page (sorted
 * first) rather than letting the ordering silently drop it.
 */
function mvb_get_preorders(): array {
	return get_posts(
		array(
			'post_type'      => 'mvb_preorder',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'OR',
				'arrival'  => array(
					'key'     => 'arrival_month',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => 'arrival_month',
					'compare' => 'NOT EXISTS',
				),
			),
			'orderby'        => array(
				'arrival' => 'ASC',
				'title'   => 'ASC',
			),
		)
	);
}

/**
 * The pick_kind terms used by $posts, in the order a filter bar should
 * show them. Scoped to the posts actually on the page because the taxonomy
 * is shared by picks and coming-soon books — a kind only one of them uses
 * shouldn't show up as an empty filter on the other's page.
 *
 * @param WP_Post[] $posts
 * @return WP_Term[]|WP_Error
 */
function mvb_get_used_pick_kinds( array $posts ) {
	if ( ! $posts ) {
		return array();
	}
	return get_terms(
		array(
			'taxonomy'   => 'pick_kind',
			'hide_empty' => true,
			'object_ids' => wp_list_pluck( $posts, 'ID' ),
		)
	);
}
