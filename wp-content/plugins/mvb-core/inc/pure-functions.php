<?php
/**
 * Pure business logic behind mvb-core, kept free of WordPress hook
 * registration (add_action/register_post_type/etc.) so it can be
 * unit-tested in isolation — see /tests/Unit. Everything in this file may
 * still call WordPress *functions* (sanitize_text_field and similar);
 * what it must never do is anything at file scope beyond declaring
 * functions, since that runs the instant this file is required.
 *
 * Deliberately no ABSPATH guard here: nothing executes on include, and
 * the unit test suite requires this file directly with no WordPress
 * loaded at all.
 */

/**
 * Meta is stored as Ymd (e.g. 20260908) so it sorts and compares correctly
 * as a string in WP_Query meta queries; HTML date inputs need Y-m-d.
 */
function mvb_ymd_to_input_date( string $ymd ): string {
	if ( ! preg_match( '/^\d{8}$/', $ymd ) ) {
		return '';
	}
	return substr( $ymd, 0, 4 ) . '-' . substr( $ymd, 4, 2 ) . '-' . substr( $ymd, 6, 2 );
}

/**
 * The reverse of mvb_ymd_to_input_date(), for saving the admin form's
 * <input type="date"> value back to post meta.
 */
function mvb_input_date_to_ymd( string $input_date ): ?string {
	if ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $input_date, $m ) ) {
		return null;
	}
	return $m[1] . $m[2] . $m[3];
}

/**
 * Turns the "books in this list" repeater's raw $_POST rows into a clean
 * array ready for update_post_meta(): each field sanitized, and rows that
 * are entirely blank (e.g. a spare row left empty) dropped, re-indexed
 * sequentially so it stores as a tidy list rather than a sparse array.
 *
 * @param array<int, array<string, mixed>> $raw_rows
 * @return array<int, array{title: string, author: string, url: string}>
 */
function mvb_sanitize_list_books_rows( array $raw_rows ): array {
	$rows = array();

	foreach ( $raw_rows as $row ) {
		$title  = sanitize_text_field( $row['title'] ?? '' );
		$author = sanitize_text_field( $row['author'] ?? '' );
		$url    = esc_url_raw( $row['url'] ?? '' );

		if ( '' === $title && '' === $author && '' === $url ) {
			continue;
		}

		$rows[] = array(
			'title'  => $title,
			'author' => $author,
			'url'    => $url,
		);
	}

	return $rows;
}

/**
 * The Shop Details fields and their fallback values, used everywhere the
 * theme reads hours/address/phone/etc. before an admin has saved anything.
 */
function mvb_default_shop_details(): array {
	return array(
		'address_line1' => '2 Matakana Valley Road',
		'address_line2' => 'Matakana 0948',
		'phone'         => '09 423 0315',
		'email'         => 'books@matakanavillagebooks.co.nz',
		'hours'         => '9am – 5pm, seven days',
		'holiday_note'  => 'Closed public holidays',
		'map_url'       => 'https://maps.google.com/?q=2+Matakana+Valley+Road,+Matakana',
	);
}

/**
 * Fills in any field missing from the saved option with its default.
 * Unknown keys in $saved (e.g. left over from a removed field) are
 * dropped rather than carried through.
 */
function mvb_merge_shop_details( array $saved ): array {
	$merged = array();
	foreach ( mvb_default_shop_details() as $key => $default ) {
		$merged[ $key ] = $saved[ $key ] ?? $default;
	}
	return $merged;
}

/**
 * Sanitizes the Shop Details settings form's raw $_POST value field by
 * field. Missing fields become an empty string, not their default — the
 * default only applies at read time (mvb_merge_shop_details), so an admin
 * clearing a field to blank is respected rather than silently reverted.
 */
function mvb_sanitize_shop_details( array $input ): array {
	return array(
		'address_line1' => sanitize_text_field( $input['address_line1'] ?? '' ),
		'address_line2' => sanitize_text_field( $input['address_line2'] ?? '' ),
		'phone'         => sanitize_text_field( $input['phone'] ?? '' ),
		'email'         => sanitize_email( $input['email'] ?? '' ),
		'hours'         => sanitize_text_field( $input['hours'] ?? '' ),
		'holiday_note'  => sanitize_text_field( $input['holiday_note'] ?? '' ),
		'map_url'       => esc_url_raw( $input['map_url'] ?? '' ),
	);
}

/**
 * Assembles the JSON shape the homepage's reshuffle JS expects from
 * /wp-json/mvb/v1/picks/random, given the primitive values already read
 * off a mvb_pick post. Kept separate from the WP_Post-reading code so the
 * payload shape itself is unit-testable without a real post object.
 *
 * @param array<string, mixed> $fields
 */
function mvb_build_pick_payload( array $fields ): array {
	return array(
		'id'     => (int) ( $fields['id'] ?? 0 ),
		'kind'   => (string) ( $fields['kind'] ?? '' ),
		'title'  => (string) ( $fields['title'] ?? '' ),
		'author' => (string) ( $fields['author'] ?? '' ),
		'by'     => (string) ( $fields['by'] ?? '' ),
		'note'   => (string) ( $fields['note'] ?? '' ),
		'buyUrl' => (string) ( $fields['buyUrl'] ?? '' ),
		'cover'  => (string) ( $fields['cover'] ?? '' ),
	);
}
