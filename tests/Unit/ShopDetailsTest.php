<?php

namespace MVB\Tests\Unit;

use Brain\Monkey\Functions;

class ShopDetailsTest extends TestCase {

	public function test_defaults_cover_every_field(): void {
		$defaults = mvb_default_shop_details();

		$this->assertSame(
			array( 'address_line1', 'address_line2', 'phone', 'email', 'hours', 'holiday_note', 'map_url' ),
			array_keys( $defaults )
		);
		$this->assertSame( '09 423 0315', $defaults['phone'] );
	}

	public function test_merge_fills_in_missing_fields_from_defaults(): void {
		$merged = mvb_merge_shop_details( array( 'phone' => '09 000 0000' ) );

		$this->assertSame( '09 000 0000', $merged['phone'] );
		$this->assertSame( mvb_default_shop_details()['email'], $merged['email'] );
	}

	public function test_merge_ignores_unknown_keys(): void {
		$merged = mvb_merge_shop_details( array( 'not_a_real_field' => 'oops' ) );

		$this->assertArrayNotHasKey( 'not_a_real_field', $merged );
	}

	public function test_sanitize_passes_each_field_through_its_sanitizer(): void {
		Functions\when( 'sanitize_text_field' )->alias( fn( $v ) => trim( (string) $v ) );
		Functions\when( 'sanitize_email' )->alias( fn( $v ) => strtolower( trim( (string) $v ) ) );
		Functions\when( 'esc_url_raw' )->alias( fn( $v ) => trim( (string) $v ) );

		$clean = mvb_sanitize_shop_details(
			array(
				'address_line1' => '  2 Matakana Valley Road  ',
				'email'         => '  Books@MatakanaVillageBooks.CO.NZ  ',
				'map_url'       => '  https://maps.google.com/?q=test  ',
			)
		);

		$this->assertSame( '2 Matakana Valley Road', $clean['address_line1'] );
		$this->assertSame( 'books@matakanavillagebooks.co.nz', $clean['email'] );
		$this->assertSame( 'https://maps.google.com/?q=test', $clean['map_url'] );
	}

	public function test_sanitize_defaults_missing_fields_to_empty_string(): void {
		Functions\when( 'sanitize_text_field' )->alias( fn( $v ) => trim( (string) $v ) );
		Functions\when( 'sanitize_email' )->alias( fn( $v ) => trim( (string) $v ) );
		Functions\when( 'esc_url_raw' )->alias( fn( $v ) => trim( (string) $v ) );

		$clean = mvb_sanitize_shop_details( array() );

		$this->assertSame( '', $clean['phone'] );
		$this->assertSame( array_keys( mvb_default_shop_details() ), array_keys( $clean ) );
	}
}
