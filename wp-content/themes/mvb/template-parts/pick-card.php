<?php
/**
 * One "shelf-talker" card. Expects $args['pick'] (a WP_Post of type
 * mvb_pick or mvb_preorder). Used on the front page, the Bookshelf page,
 * and the Coming Soon page. A coming-soon book gets the same card plus a
 * diagonal corner banner with its expected arrival month.
 *
 * reshuffle.js builds this same markup client-side — keep the two in step.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mvb_pick = $args['pick'] ?? null;
if ( ! $mvb_pick instanceof WP_Post ) {
	return;
}

$mvb_terms = get_the_terms( $mvb_pick->ID, 'pick_kind' );
$mvb_kind  = ( $mvb_terms && ! is_wp_error( $mvb_terms ) && ! empty( $mvb_terms ) ) ? $mvb_terms[0]->name : '';

$mvb_author  = get_post_meta( $mvb_pick->ID, 'book_author', true );
$mvb_by      = get_post_meta( $mvb_pick->ID, 'picked_by', true );
$mvb_note    = get_post_meta( $mvb_pick->ID, 'pick_note', true );
$mvb_buy_url = get_post_meta( $mvb_pick->ID, 'buy_url', true );
$mvb_cover   = get_the_post_thumbnail_url( $mvb_pick->ID, 'mvb-cover' );
$mvb_arrival = 'mvb_preorder' === $mvb_pick->post_type
  ? mvb_arrival_label( (string) get_post_meta( $mvb_pick->ID, 'arrival_month', true ) )
  : '';
?>
<figure class="talker<?php echo $mvb_arrival ? ' has-ribbon' : ''; ?>" data-kind="<?php echo esc_attr( $mvb_kind ); ?>">
  <svg class="pin" aria-hidden="true"><use href="#bird"/></svg>
  <?php if ( $mvb_arrival ) : ?>
    <p class="ribbon"><span><span class="sr">Expected </span><?php echo esc_html( $mvb_arrival ); ?></span></p>
  <?php endif; ?>
  <p class="kind"><?php echo esc_html( $mvb_kind ); ?></p>
  <div class="cover<?php echo $mvb_cover ? ' has-image' : ''; ?>"<?php echo $mvb_cover ? ' style="background-image:url(' . esc_url( $mvb_cover ) . ')"' : ''; ?>>
    <em><?php echo esc_html( get_the_title( $mvb_pick ) ); ?></em>
    <small><?php echo esc_html( $mvb_author ); ?></small>
  </div>
  <?php if ( $mvb_note ) : ?>
    <blockquote class="note"><?php echo esc_html( $mvb_note ); ?></blockquote>
  <?php endif; ?>
  <?php if ( $mvb_by ) : ?>
    <figcaption class="sig"><?php echo esc_html( $mvb_by ); ?></figcaption>
  <?php endif; ?>
  <?php if ( $mvb_buy_url ) : ?>
    <a class="buy" href="<?php echo esc_url( $mvb_buy_url ); ?>">Buy this online →</a>
  <?php endif; ?>
</figure>
