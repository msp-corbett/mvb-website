<?php
/**
 * Native (no-plugin) meta boxes for picks, events, and lists — including a
 * hand-built repeater for a list's books, in place of ACF/Meta Box.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -----------------------------------------------------------------------
 * Registration
 * --------------------------------------------------------------------- */

function mvb_add_meta_boxes() {
	add_meta_box( 'mvb_pick_details', __( 'Pick details', 'mvb-core' ), 'mvb_render_pick_meta_box', 'mvb_pick', 'normal', 'high' );
	add_meta_box( 'mvb_event_details', __( 'Event details', 'mvb-core' ), 'mvb_render_event_meta_box', 'mvb_event', 'normal', 'high' );
	add_meta_box( 'mvb_list_details', __( 'List details', 'mvb-core' ), 'mvb_render_list_meta_box', 'mvb_list', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mvb_add_meta_boxes' );

/* -----------------------------------------------------------------------
 * Pick meta box: author, picked by, note, buy URL
 * --------------------------------------------------------------------- */

function mvb_render_pick_meta_box( $post ) {
	wp_nonce_field( 'mvb_save_pick', 'mvb_pick_nonce' );

	$author  = get_post_meta( $post->ID, 'book_author', true );
	$by      = get_post_meta( $post->ID, 'picked_by', true );
	$note    = get_post_meta( $post->ID, 'pick_note', true );
	$buy_url = get_post_meta( $post->ID, 'buy_url', true );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="mvb_book_author"><?php esc_html_e( 'Author', 'mvb-core' ); ?></label></th>
			<td><input type="text" id="mvb_book_author" name="mvb_book_author" class="large-text" value="<?php echo esc_attr( $author ); ?>"></td>
		</tr>
		<tr>
			<th><label for="mvb_picked_by"><?php esc_html_e( 'Picked by', 'mvb-core' ); ?></label></th>
			<td>
				<input type="text" id="mvb_picked_by" name="mvb_picked_by" class="regular-text" value="<?php echo esc_attr( $by ); ?>">
				<p class="description"><?php esc_html_e( 'The staff member’s name, shown as a signature.', 'mvb-core' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mvb_pick_note"><?php esc_html_e( 'Note', 'mvb-core' ); ?></label></th>
			<td>
				<textarea id="mvb_pick_note" name="mvb_pick_note" class="large-text" rows="4"><?php echo esc_textarea( $note ); ?></textarea>
				<p class="description"><?php esc_html_e( 'The short, hand-written-style blurb about why this book was picked.', 'mvb-core' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="mvb_buy_url"><?php esc_html_e( 'Buy link', 'mvb-core' ); ?></label></th>
			<td>
				<input type="url" id="mvb_buy_url" name="mvb_buy_url" class="large-text" value="<?php echo esc_attr( $buy_url ); ?>" placeholder="https://shop.matakanavillagebooks.co.nz/...">
				<p class="description"><?php esc_html_e( 'Link to this title on the online shop.', 'mvb-core' ); ?></p>
			</td>
		</tr>
	</table>
	<p class="description"><?php esc_html_e( 'Set the cover image using the Featured Image panel. Use the Kinds panel to file this pick under a category (Fiction, Aotearoa, Cooking, …).', 'mvb-core' ); ?></p>
	<?php
}

function mvb_save_pick_meta( $post_id ) {
	if ( ! isset( $_POST['mvb_pick_nonce'] ) || ! wp_verify_nonce( $_POST['mvb_pick_nonce'], 'mvb_save_pick' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['mvb_book_author'] ) ) {
		update_post_meta( $post_id, 'book_author', sanitize_text_field( wp_unslash( $_POST['mvb_book_author'] ) ) );
	}
	if ( isset( $_POST['mvb_picked_by'] ) ) {
		update_post_meta( $post_id, 'picked_by', sanitize_text_field( wp_unslash( $_POST['mvb_picked_by'] ) ) );
	}
	if ( isset( $_POST['mvb_pick_note'] ) ) {
		update_post_meta( $post_id, 'pick_note', sanitize_textarea_field( wp_unslash( $_POST['mvb_pick_note'] ) ) );
	}
	if ( isset( $_POST['mvb_buy_url'] ) ) {
		update_post_meta( $post_id, 'buy_url', esc_url_raw( wp_unslash( $_POST['mvb_buy_url'] ) ) );
	}
}
add_action( 'save_post_mvb_pick', 'mvb_save_pick_meta' );

/* -----------------------------------------------------------------------
 * Event meta box: date, time, cost
 * --------------------------------------------------------------------- */

function mvb_render_event_meta_box( $post ) {
	wp_nonce_field( 'mvb_save_event', 'mvb_event_nonce' );

	$date = get_post_meta( $post->ID, 'event_date', true );
	$time = get_post_meta( $post->ID, 'event_time', true );
	$cost = get_post_meta( $post->ID, 'event_cost', true );

	$date_value = $date ? mvb_ymd_to_input_date( $date ) : '';
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="mvb_event_date"><?php esc_html_e( 'Date', 'mvb-core' ); ?></label></th>
			<td><input type="date" id="mvb_event_date" name="mvb_event_date" value="<?php echo esc_attr( $date_value ); ?>" required></td>
		</tr>
		<tr>
			<th><label for="mvb_event_time"><?php esc_html_e( 'Time', 'mvb-core' ); ?></label></th>
			<td>
				<input type="text" id="mvb_event_time" name="mvb_event_time" class="regular-text" value="<?php echo esc_attr( $time ); ?>" placeholder="6pm, in the shop">
			</td>
		</tr>
		<tr>
			<th><label for="mvb_event_cost"><?php esc_html_e( 'Cost', 'mvb-core' ); ?></label></th>
			<td>
				<input type="text" id="mvb_event_cost" name="mvb_event_cost" class="regular-text" value="<?php echo esc_attr( $cost ); ?>" placeholder="Free, but let us know you're coming">
				<p class="description"><?php esc_html_e( 'Leave blank if there’s nothing to say about cost.', 'mvb-core' ); ?></p>
			</td>
		</tr>
	</table>
	<p class="description"><?php esc_html_e( 'Use the main content box above for the event blurb. Past events drop off the site automatically.', 'mvb-core' ); ?></p>
	<?php
}

// mvb_ymd_to_input_date() is pure logic, defined (and unit-tested) in
// inc/pure-functions.php.

function mvb_save_event_meta( $post_id ) {
	if ( ! isset( $_POST['mvb_event_nonce'] ) || ! wp_verify_nonce( $_POST['mvb_event_nonce'], 'mvb_save_event' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['mvb_event_date'] ) ) {
		$ymd = mvb_input_date_to_ymd( sanitize_text_field( wp_unslash( $_POST['mvb_event_date'] ) ) );
		if ( null !== $ymd ) {
			update_post_meta( $post_id, 'event_date', $ymd );
		}
	}
	if ( isset( $_POST['mvb_event_time'] ) ) {
		update_post_meta( $post_id, 'event_time', sanitize_text_field( wp_unslash( $_POST['mvb_event_time'] ) ) );
	}
	if ( isset( $_POST['mvb_event_cost'] ) ) {
		update_post_meta( $post_id, 'event_cost', sanitize_text_field( wp_unslash( $_POST['mvb_event_cost'] ) ) );
	}
}
add_action( 'save_post_mvb_event', 'mvb_save_event_meta' );

/* -----------------------------------------------------------------------
 * List meta box: subtitle + hand-built "list_books" repeater
 * --------------------------------------------------------------------- */

function mvb_get_list_books( $post_id ) {
	$books = get_post_meta( $post_id, 'list_books', true );
	return is_array( $books ) ? $books : array();
}

function mvb_render_list_meta_box( $post ) {
	wp_nonce_field( 'mvb_save_list', 'mvb_list_nonce' );

	$subtitle = get_post_meta( $post->ID, 'list_subtitle', true );
	$books    = mvb_get_list_books( $post->ID );
	if ( empty( $books ) ) {
		$books = array( array( 'title' => '', 'author' => '', 'url' => '' ) );
	}
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="mvb_list_subtitle"><?php esc_html_e( 'Subtitle', 'mvb-core' ); ?></label></th>
			<td>
				<input type="text" id="mvb_list_subtitle" name="mvb_list_subtitle" class="large-text" value="<?php echo esc_attr( $subtitle ); ?>" placeholder="New Zealand titles this month">
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Books in this list', 'mvb-core' ); ?></h4>
	<table class="widefat mvb-repeater" id="mvb-list-books">
		<thead>
			<tr>
				<th style="width:2em"></th>
				<th><?php esc_html_e( 'Title', 'mvb-core' ); ?></th>
				<th><?php esc_html_e( 'Author', 'mvb-core' ); ?></th>
				<th><?php esc_html_e( 'Link (optional)', 'mvb-core' ); ?></th>
				<th style="width:3em"></th>
			</tr>
		</thead>
		<tbody id="mvb-list-books-rows" data-next-index="<?php echo esc_attr( count( $books ) ); ?>">
			<?php foreach ( $books as $i => $book ) : ?>
				<?php echo mvb_render_list_book_row( $i, $book ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside helper ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>
		<button type="button" class="button" id="mvb-add-list-book"><?php esc_html_e( '+ Add book', 'mvb-core' ); ?></button>
	</p>

	<template id="mvb-list-book-row-template">
		<?php echo mvb_render_list_book_row( '__INDEX__', array( 'title' => '', 'author' => '', 'url' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</template>
	<?php
}

/**
 * Renders one repeater row. $index may be an integer (real row) or the
 * literal string '__INDEX__' (the JS template row, renumbered on clone).
 */
function mvb_render_list_book_row( $index, $book ) {
	ob_start();
	?>
	<tr class="mvb-repeater-row">
		<td class="mvb-repeater-handle">::</td>
		<td><input type="text" name="mvb_list_books[<?php echo esc_attr( $index ); ?>][title]" class="widefat" value="<?php echo esc_attr( $book['title'] ?? '' ); ?>"></td>
		<td><input type="text" name="mvb_list_books[<?php echo esc_attr( $index ); ?>][author]" class="widefat" value="<?php echo esc_attr( $book['author'] ?? '' ); ?>"></td>
		<td><input type="url" name="mvb_list_books[<?php echo esc_attr( $index ); ?>][url]" class="widefat" value="<?php echo esc_attr( $book['url'] ?? '' ); ?>" placeholder="https://shop.matakanavillagebooks.co.nz/..."></td>
		<td><button type="button" class="button-link mvb-remove-row" aria-label="<?php esc_attr_e( 'Remove this book', 'mvb-core' ); ?>">&times;</button></td>
	</tr>
	<?php
	return ob_get_clean();
}

function mvb_save_list_meta( $post_id ) {
	if ( ! isset( $_POST['mvb_list_nonce'] ) || ! wp_verify_nonce( $_POST['mvb_list_nonce'], 'mvb_save_list' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['mvb_list_subtitle'] ) ) {
		update_post_meta( $post_id, 'list_subtitle', sanitize_text_field( wp_unslash( $_POST['mvb_list_subtitle'] ) ) );
	}

	$raw_rows = array();
	if ( isset( $_POST['mvb_list_books'] ) && is_array( $_POST['mvb_list_books'] ) ) {
		$raw_rows = wp_unslash( $_POST['mvb_list_books'] );
	}
	update_post_meta( $post_id, 'list_books', mvb_sanitize_list_books_rows( $raw_rows ) );
}
add_action( 'save_post_mvb_list', 'mvb_save_list_meta' );

/* -----------------------------------------------------------------------
 * Admin assets for the repeater control
 * --------------------------------------------------------------------- */

function mvb_admin_repeater_assets( $hook ) {
	global $post_type;
	if ( 'mvb_list' !== $post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_style( 'mvb-admin', MVB_CORE_URL . 'assets/admin.css', array(), '1.0.0' );
	wp_enqueue_script( 'mvb-admin-repeater', MVB_CORE_URL . 'assets/admin-repeater.js', array(), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'mvb_admin_repeater_assets' );
