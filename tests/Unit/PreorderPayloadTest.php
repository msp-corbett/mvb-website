<?php

namespace MVB\Tests\Unit;

/**
 * mvb_build_preorder_payload() is the coming-soon counterpart of
 * mvb_build_pick_payload(): the same card fields the reshuffle JS already
 * renders, plus the "Mar 2027"-style arrival label for the corner banner.
 */
class PreorderPayloadTest extends TestCase {

	public function test_is_the_pick_payload_plus_an_arrival_label(): void {
		$fields = array(
			'id'      => '9',
			'kind'    => 'Fiction',
			'title'   => 'A Book Not Out Yet',
			'author'  => 'Some Author',
			'by'      => 'Karen',
			'note'    => 'Can’t wait for this one.',
			'buyUrl'  => 'https://shop.matakanavillagebooks.co.nz/preorder',
			'cover'   => 'https://example.test/cover.jpg',
			'arrival' => 'Mar 2027',
		);

		$this->assertSame(
			array_merge( mvb_build_pick_payload( $fields ), array( 'arrival' => 'Mar 2027' ) ),
			mvb_build_preorder_payload( $fields )
		);
	}

	public function test_missing_arrival_defaults_to_empty_string(): void {
		$payload = mvb_build_preorder_payload( array( 'id' => 1, 'title' => 'x' ) );

		$this->assertSame( '', $payload['arrival'] );
	}

	public function test_field_order_is_stable(): void {
		$payload = mvb_build_preorder_payload( array( 'id' => 1, 'title' => 'x' ) );

		$this->assertSame(
			array( 'id', 'kind', 'title', 'author', 'by', 'note', 'buyUrl', 'cover', 'arrival' ),
			array_keys( $payload )
		);
	}
}
