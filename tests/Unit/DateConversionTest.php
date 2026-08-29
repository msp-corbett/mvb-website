<?php

namespace MVB\Tests\Unit;

/**
 * Event dates are stored as Ymd (e.g. "20260908") so WP_Query can compare
 * them as plain strings; HTML <input type="date"> needs Y-m-d. These two
 * pure conversions sit between the admin form and post meta.
 */
class DateConversionTest extends TestCase {

	/** @dataProvider ymdToInputDateCases */
	public function test_ymd_to_input_date( string $ymd, string $expected ): void {
		$this->assertSame( $expected, mvb_ymd_to_input_date( $ymd ) );
	}

	public static function ymdToInputDateCases(): array {
		return array(
			'well-formed date' => array( '20260908', '2026-09-08' ),
			'empty string'     => array( '', '' ),
			'too short'        => array( '2026', '' ),
			'not numeric'      => array( 'abcdefgh', '' ),
		);
	}

	/** @dataProvider inputDateToYmdCases */
	public function test_input_date_to_ymd( string $input, ?string $expected ): void {
		$this->assertSame( $expected, mvb_input_date_to_ymd( $input ) );
	}

	public static function inputDateToYmdCases(): array {
		return array(
			'well-formed date' => array( '2026-09-08', '20260908' ),
			'empty string'     => array( '', null ),
			'missing dashes'   => array( '20260908', null ),
			'not a date'       => array( 'not-a-date', null ),
		);
	}

	public function test_round_trip(): void {
		$this->assertSame( '20260908', mvb_input_date_to_ymd( mvb_ymd_to_input_date( '20260908' ) ) );
	}
}
