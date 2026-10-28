<?php

namespace MVB\Tests\Unit;

/**
 * mvb_has_visible_text() decides whether a page's own content replaces a
 * template's built-in intro copy. The block editor leaves markup behind
 * even when a page looks empty, so "empty" has to mean "no words", not "".
 */
class VisibleTextTest extends TestCase {

	public function test_truly_empty_content_has_no_text(): void {
		$this->assertFalse( mvb_has_visible_text( '' ) );
	}

	public function test_an_emptied_block_editor_has_no_text(): void {
		$html = "<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n";
		$this->assertFalse( mvb_has_visible_text( $html ) );
	}

	public function test_whitespace_and_non_breaking_spaces_are_not_text(): void {
		$this->assertFalse( mvb_has_visible_text( "<p>&nbsp;</p>\n<p> \u{00A0} </p>" ) );
	}

	public function test_a_paragraph_with_words_has_text(): void {
		$html = "<!-- wp:paragraph -->\n<p>Our favourite books.</p>\n<!-- /wp:paragraph -->";
		$this->assertTrue( mvb_has_visible_text( $html ) );
	}

	public function test_a_lone_link_counts_as_text(): void {
		$this->assertTrue( mvb_has_visible_text( '<p><a href="https://example.com">Shop</a></p>' ) );
	}
}
