<?php

namespace MVB\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * mvb_sanitize_list_books_rows() turns the repeater's raw $_POST rows into
 * a clean array ready for update_post_meta(): sanitized fields, and
 * fully-blank rows (e.g. a spare row left empty) dropped.
 */
class ListBooksSanitizationTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		// Stand-ins for the real sanitizers: trim for text fields, and a
		// URL sanitizer that blanks anything not starting with http(s).
		Functions\when( 'sanitize_text_field' )->alias( fn( $v ) => trim( (string) $v ) );
		Functions\when( 'esc_url_raw' )->alias(
			fn( $v ) => preg_match( '#^https?://#', (string) $v ) ? trim( (string) $v ) : ''
		);
	}

	public function test_sanitizes_a_normal_row(): void {
		$rows = mvb_sanitize_list_books_rows(
			array(
				array(
					'title'  => '  Auē  ',
					'author' => 'Becky Manawatu',
					'url'    => 'https://shop.matakanavillagebooks.co.nz/aue',
				),
			)
		);

		$this->assertSame(
			array(
				array(
					'title'  => 'Auē',
					'author' => 'Becky Manawatu',
					'url'    => 'https://shop.matakanavillagebooks.co.nz/aue',
				),
			),
			$rows
		);
	}

	public function test_drops_fully_empty_rows(): void {
		$rows = mvb_sanitize_list_books_rows(
			array(
				array( 'title' => 'Birnam Wood', 'author' => 'Eleanor Catton', 'url' => '' ),
				array( 'title' => '', 'author' => '', 'url' => '' ),
				array( 'title' => '   ', 'author' => '', 'url' => '' ), // whitespace-only counts as empty
			)
		);

		$this->assertCount( 1, $rows );
		$this->assertSame( 'Birnam Wood', $rows[0]['title'] );
	}

	public function test_keeps_a_row_with_only_a_title(): void {
		$rows = mvb_sanitize_list_books_rows(
			array( array( 'title' => 'The Bone People', 'author' => '', 'url' => '' ) )
		);

		$this->assertCount( 1, $rows );
	}

	public function test_tolerates_missing_keys(): void {
		$rows = mvb_sanitize_list_books_rows( array( array( 'title' => 'No author key set' ) ) );

		$this->assertSame(
			array( array( 'title' => 'No author key set', 'author' => '', 'url' => '' ) ),
			$rows
		);
	}

	public function test_re_indexes_sequentially_after_dropping_rows(): void {
		$rows = mvb_sanitize_list_books_rows(
			array(
				array( 'title' => '', 'author' => '', 'url' => '' ),
				array( 'title' => 'Second row survives', 'author' => '', 'url' => '' ),
			)
		);

		$this->assertSame( array( 0 ), array_keys( $rows ) );
	}

	public function test_empty_input_yields_empty_output(): void {
		$this->assertSame( array(), mvb_sanitize_list_books_rows( array() ) );
	}
}
