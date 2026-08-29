<?php

namespace MVB\Tests\Unit;

/**
 * mvb_build_pick_payload() assembles the JSON shape the homepage's
 * reshuffle JS expects from /wp-json/mvb/v1/picks/random, given the
 * primitive values already pulled out of a mvb_pick post by
 * mvb_format_pick_for_rest(). Kept separate from that WP_Post-reading
 * function so the payload shape itself is unit-testable.
 */
class PickPayloadTest extends TestCase {

	public function test_builds_the_full_payload(): void {
		$payload = mvb_build_pick_payload(
			array(
				'id'     => '42',
				'kind'   => 'Aotearoa',
				'title'  => 'Auē',
				'author' => 'Becky Manawatu',
				'by'     => 'Karen',
				'note'   => 'Hard, tender, and the best thing out of this country in a decade.',
				'buyUrl' => 'https://shop.matakanavillagebooks.co.nz/aue',
				'cover'  => 'https://example.test/aue.jpg',
			)
		);

		$this->assertSame(
			array(
				'id'     => 42,
				'kind'   => 'Aotearoa',
				'title'  => 'Auē',
				'author' => 'Becky Manawatu',
				'by'     => 'Karen',
				'note'   => 'Hard, tender, and the best thing out of this country in a decade.',
				'buyUrl' => 'https://shop.matakanavillagebooks.co.nz/aue',
				'cover'  => 'https://example.test/aue.jpg',
			),
			$payload
		);
	}

	public function test_id_is_cast_to_int(): void {
		$payload = mvb_build_pick_payload( array( 'id' => '7', 'title' => 'x' ) );

		$this->assertSame( 7, $payload['id'] );
		$this->assertIsInt( $payload['id'] );
	}

	public function test_missing_optional_fields_default_to_empty_string(): void {
		$payload = mvb_build_pick_payload( array( 'id' => 1, 'title' => 'Only a title' ) );

		$this->assertSame( '', $payload['kind'] );
		$this->assertSame( '', $payload['author'] );
		$this->assertSame( '', $payload['by'] );
		$this->assertSame( '', $payload['note'] );
		$this->assertSame( '', $payload['buyUrl'] );
		$this->assertSame( '', $payload['cover'] );
	}

	public function test_field_order_is_stable(): void {
		$payload = mvb_build_pick_payload( array( 'id' => 1, 'title' => 'x' ) );

		$this->assertSame(
			array( 'id', 'kind', 'title', 'author', 'by', 'note', 'buyUrl', 'cover' ),
			array_keys( $payload )
		);
	}
}
