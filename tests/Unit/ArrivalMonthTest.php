<?php

namespace MVB\Tests\Unit;

/**
 * A coming-soon book's expected arrival is stored as Ym (e.g. "202703") so
 * it sorts as a plain string, the same way event dates are stored as Ymd;
 * HTML <input type="month"> needs Y-m, and the card's corner banner shows
 * it as "Mar 2027".
 */
class ArrivalMonthTest extends TestCase {

	/** @dataProvider ymToInputMonthCases */
	public function test_ym_to_input_month( string $ym, string $expected ): void {
		$this->assertSame( $expected, mvb_ym_to_input_month( $ym ) );
	}

	public static function ymToInputMonthCases(): array {
		return array(
			'well-formed month' => array( '202703', '2027-03' ),
			'empty string'      => array( '', '' ),
			'a full Ymd date'   => array( '20270301', '' ),
			'not numeric'       => array( 'abcdef', '' ),
		);
	}

	/** @dataProvider inputMonthToYmCases */
	public function test_input_month_to_ym( string $input, ?string $expected ): void {
		$this->assertSame( $expected, mvb_input_month_to_ym( $input ) );
	}

	public static function inputMonthToYmCases(): array {
		return array(
			'well-formed month'   => array( '2027-03', '202703' ),
			'surrounding spaces'  => array( ' 2027-03 ', '202703' ),
			'empty string'        => array( '', null ),
			'missing dash'        => array( '202703', null ),
			'month thirteen'      => array( '2027-13', null ),
			'month zero'          => array( '2027-00', null ),
			'a full Y-m-d date'   => array( '2027-03-01', null ),
		);
	}

	public function test_round_trip(): void {
		$this->assertSame( '2027-11', mvb_ym_to_input_month( mvb_input_month_to_ym( '2027-11' ) ) );
	}

	/** @dataProvider arrivalLabelCases */
	public function test_arrival_label( string $ym, string $expected ): void {
		$this->assertSame( $expected, mvb_arrival_label( $ym ) );
	}

	public static function arrivalLabelCases(): array {
		return array(
			'march'          => array( '202703', 'Mar 2027' ),
			'january'        => array( '202601', 'Jan 2026' ),
			'december'       => array( '202612', 'Dec 2026' ),
			'empty string'   => array( '', '' ),
			'month thirteen' => array( '202713', '' ),
			'malformed'      => array( '2027-03', '' ),
		);
	}
}
