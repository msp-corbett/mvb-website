<?php
/**
 * Pure logic behind the theme's per-page <title>/meta description
 * (see inc/seo-meta.php, which wires this into wp_head using real query
 * conditionals). Kept free of WordPress calls so it's unit-tested in
 * isolation — see /tests/Unit/SeoMetaTest.php.
 */

/**
 * Builds the <title> text for a page. The front page keeps the shop's own
 * hand-written copy (matching the site's original design brief); every
 * other page gets a generated "Section — Site Name" title.
 *
 * @param array{is_front_page: bool, is_bookshelf: bool, is_events: bool, site_name: string, page_title: string} $context
 */
function mvb_document_title( array $context ): string {
	if ( $context['is_front_page'] ) {
		return $context['site_name'] . ' — Boutique independent bookstore';
	}
	if ( $context['is_bookshelf'] ) {
		return 'Bookshelf — ' . $context['site_name'];
	}
	if ( $context['is_events'] ) {
		return 'Events — ' . $context['site_name'];
	}
	if ( '' !== $context['page_title'] ) {
		return $context['page_title'] . ' — ' . $context['site_name'];
	}
	return $context['site_name'];
}

/**
 * Builds the meta description for a page. The front page and the two
 * data-driven sections (Bookshelf, Events) get fixed, hand-written copy;
 * any other page falls back to its own excerpt, or no description at all
 * rather than a generic placeholder.
 *
 * @param array{is_front_page: bool, is_bookshelf: bool, is_events: bool, excerpt?: string} $context
 */
function mvb_meta_description( array $context ): string {
	if ( $context['is_front_page'] ) {
		return 'A boutique independent bookshop in Matakana Village, under the cinema and beside the Farmers Market. Open 9–5, seven days.';
	}
	if ( $context['is_bookshelf'] ) {
		return 'Every book on the shelf at Matakana Village Books, picked by hand — filter by kind to browse.';
	}
	if ( $context['is_events'] ) {
		return 'Author evenings, the book club, and story time at Matakana Village Books, Matakana.';
	}
	return $context['excerpt'] ?? '';
}
