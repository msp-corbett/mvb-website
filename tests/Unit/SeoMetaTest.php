<?php

namespace MVB\Tests\Unit;

/**
 * mvb_document_title() / mvb_meta_description() build the per-page
 * <title> and meta description the demo hand-wrote for its one static
 * page — this is the rule for producing them across a real multi-page
 * site. Front page keeps the shop's own marketing copy; other pages get a
 * generated "Section — Site Name" title and a description matched to
 * what's actually on that page.
 */
class SeoMetaTest extends TestCase {

	private function context( array $overrides = array() ): array {
		return array_merge(
			array(
				'is_front_page' => false,
				'is_bookshelf'  => false,
				'is_events'     => false,
				'site_name'     => 'Matakana Village Books',
				'page_title'    => '',
			),
			$overrides
		);
	}

	public function test_front_page_title_is_the_shop_s_own_copy(): void {
		$title = mvb_document_title( $this->context( array( 'is_front_page' => true ) ) );

		$this->assertSame( 'Matakana Village Books — Boutique independent bookstore', $title );
	}

	public function test_bookshelf_title(): void {
		$title = mvb_document_title( $this->context( array( 'is_bookshelf' => true ) ) );

		$this->assertSame( 'Bookshelf — Matakana Village Books', $title );
	}

	public function test_events_title(): void {
		$title = mvb_document_title( $this->context( array( 'is_events' => true ) ) );

		$this->assertSame( 'Events — Matakana Village Books', $title );
	}

	public function test_generic_page_title_uses_the_page_s_own_title(): void {
		$title = mvb_document_title( $this->context( array( 'page_title' => 'Privacy Policy' ) ) );

		$this->assertSame( 'Privacy Policy — Matakana Village Books', $title );
	}

	public function test_front_page_description_is_the_shop_s_own_copy(): void {
		$description = mvb_meta_description( $this->context( array( 'is_front_page' => true ) ) );

		$this->assertSame(
			'A boutique independent bookshop in Matakana Village, under the cinema and beside the Farmers Market. Open 9–5, seven days.',
			$description
		);
	}

	public function test_bookshelf_description(): void {
		$description = mvb_meta_description( $this->context( array( 'is_bookshelf' => true ) ) );

		$this->assertStringContainsString( 'shelf', $description );
	}

	public function test_events_description(): void {
		$description = mvb_meta_description( $this->context( array( 'is_events' => true ) ) );

		$this->assertStringContainsString( 'book club', $description );
	}

	public function test_generic_page_falls_back_to_an_excerpt(): void {
		$description = mvb_meta_description(
			$this->context( array( 'page_title' => 'Privacy Policy', 'excerpt' => 'How we handle your data.' ) )
		);

		$this->assertSame( 'How we handle your data.', $description );
	}

	public function test_generic_page_with_no_excerpt_has_no_description(): void {
		$description = mvb_meta_description( $this->context( array( 'page_title' => 'Privacy Policy' ) ) );

		$this->assertSame( '', $description );
	}
}
