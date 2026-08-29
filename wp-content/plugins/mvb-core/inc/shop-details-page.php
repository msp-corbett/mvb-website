<?php
/**
 * A single options page (Settings → Shop Details) for the shop's hours,
 * address, phone, email, and map link — used across the theme so opening
 * hours etc. can change without touching a template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MVB_SHOP_DETAILS_OPTION = 'mvb_shop_details';

// mvb_default_shop_details(), mvb_merge_shop_details(), and
// mvb_sanitize_shop_details() are pure logic, defined (and unit-tested) in
// inc/pure-functions.php.

/**
 * Reads the shop details option, filled in with defaults for any field an
 * admin hasn't saved yet.
 */
function mvb_get_shop_details() {
	$saved = get_option( MVB_SHOP_DETAILS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	return mvb_merge_shop_details( $saved );
}

function mvb_register_shop_details_settings() {
	register_setting(
		'mvb_shop_details_group',
		MVB_SHOP_DETAILS_OPTION,
		array(
			'sanitize_callback' => 'mvb_sanitize_shop_details',
			'default'           => mvb_default_shop_details(),
		)
	);
}
add_action( 'admin_init', 'mvb_register_shop_details_settings' );

function mvb_shop_details_menu() {
	add_options_page(
		__( 'Shop Details', 'mvb-core' ),
		__( 'Shop Details', 'mvb-core' ),
		'manage_options',
		'mvb-shop-details',
		'mvb_render_shop_details_page'
	);
}
add_action( 'admin_menu', 'mvb_shop_details_menu' );

function mvb_render_shop_details_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$d = mvb_get_shop_details();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Shop Details', 'mvb-core' ); ?></h1>
		<p><?php esc_html_e( 'Used across the site — homepage, footer, and the Finding Us section.', 'mvb-core' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'mvb_shop_details_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th><label for="mvb_address_line1"><?php esc_html_e( 'Address line 1', 'mvb-core' ); ?></label></th>
					<td><input type="text" id="mvb_address_line1" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[address_line1]" class="regular-text" value="<?php echo esc_attr( $d['address_line1'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_address_line2"><?php esc_html_e( 'Address line 2', 'mvb-core' ); ?></label></th>
					<td><input type="text" id="mvb_address_line2" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[address_line2]" class="regular-text" value="<?php echo esc_attr( $d['address_line2'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_phone"><?php esc_html_e( 'Phone', 'mvb-core' ); ?></label></th>
					<td><input type="text" id="mvb_phone" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[phone]" class="regular-text" value="<?php echo esc_attr( $d['phone'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_email"><?php esc_html_e( 'Email', 'mvb-core' ); ?></label></th>
					<td><input type="email" id="mvb_email" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[email]" class="regular-text" value="<?php echo esc_attr( $d['email'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_hours"><?php esc_html_e( 'Opening hours', 'mvb-core' ); ?></label></th>
					<td><input type="text" id="mvb_hours" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[hours]" class="regular-text" value="<?php echo esc_attr( $d['hours'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_holiday_note"><?php esc_html_e( 'Holiday note', 'mvb-core' ); ?></label></th>
					<td><input type="text" id="mvb_holiday_note" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[holiday_note]" class="regular-text" value="<?php echo esc_attr( $d['holiday_note'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="mvb_map_url"><?php esc_html_e( 'Map / directions link', 'mvb-core' ); ?></label></th>
					<td><input type="url" id="mvb_map_url" name="<?php echo esc_attr( MVB_SHOP_DETAILS_OPTION ); ?>[map_url]" class="large-text" value="<?php echo esc_attr( $d['map_url'] ); ?>"></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
