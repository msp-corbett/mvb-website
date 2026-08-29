<?php
/**
 * Wires the tested title/description rules (inc/pure-functions.php) into
 * wp_head — no SEO plugin, per the "keep it in-house" preference.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mvb_seo_context(): array {
	$page_title = '';
	$excerpt    = '';

	if ( is_singular() && ! is_front_page() ) {
		$page_title = get_the_title();
		$excerpt    = get_the_excerpt();
	} elseif ( is_search() ) {
		$page_title = sprintf( 'Search results for “%s”', get_search_query() );
	} elseif ( is_404() ) {
		$page_title = 'Page not found';
	}

	return array(
		'is_front_page' => is_front_page(),
		'is_bookshelf'  => is_page_template( 'templates/page-bookshelf.php' ),
		'is_events'     => is_page_template( 'templates/page-events.php' ),
		'site_name'     => get_bloginfo( 'name' ),
		'page_title'    => $page_title,
		'excerpt'       => $excerpt,
	);
}

/**
 * Overrides core's default <title> building with mvb_document_title() so
 * every page follows the one rule tested in SeoMetaTest.
 */
function mvb_filter_document_title_parts( $title_parts ) {
	return array( 'title' => mvb_document_title( mvb_seo_context() ) );
}
add_filter( 'document_title_parts', 'mvb_filter_document_title_parts', 20 );

function mvb_output_meta_description() {
	$description = mvb_meta_description( mvb_seo_context() );
	if ( '' === $description ) {
		return;
	}
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
}
add_action( 'wp_head', 'mvb_output_meta_description', 1 );

function mvb_output_og_tags() {
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_get_document_title() ) );
	printf( '<meta property="og:type" content="website">' . "\n" );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( home_url( add_query_arg( array(), '' ) ) ) );
}
add_action( 'wp_head', 'mvb_output_og_tags', 2 );
