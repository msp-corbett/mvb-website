<?php
/**
 * One event card. Expects $args['event'] (a WP_Post of type mvb_event)
 * and optionally $args['long_form'] (bool, adds the weekday to the meta
 * line — used on the full Events page but not the homepage teaser).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mvb_event = $args['event'] ?? null;
if ( ! $mvb_event instanceof WP_Post ) {
	return;
}

$mvb_long_form = ! empty( $args['long_form'] );
$mvb_date      = get_post_meta( $mvb_event->ID, 'event_date', true );
$mvb_time      = get_post_meta( $mvb_event->ID, 'event_time', true );
$mvb_cost      = get_post_meta( $mvb_event->ID, 'event_cost', true );

$mvb_timestamp = $mvb_date ? mktime( 12, 0, 0, (int) substr( $mvb_date, 4, 2 ), (int) substr( $mvb_date, 6, 2 ), (int) substr( $mvb_date, 0, 4 ) ) : false;

$mvb_meta_parts = array();
if ( $mvb_long_form && $mvb_timestamp ) {
	$mvb_meta_parts[] = date_i18n( 'l', $mvb_timestamp );
}
if ( $mvb_time ) {
	$mvb_meta_parts[] = $mvb_time;
}
if ( $mvb_cost ) {
	$mvb_meta_parts[] = $mvb_cost;
}
?>
<article class="event">
  <div class="when">
    <?php if ( $mvb_timestamp ) : ?>
      <b><?php echo esc_html( date_i18n( 'j', $mvb_timestamp ) ); ?></b>
      <span><?php echo esc_html( date_i18n( 'M', $mvb_timestamp ) ); ?></span>
    <?php endif; ?>
  </div>
  <div>
    <h3><?php echo esc_html( get_the_title( $mvb_event ) ); ?></h3>
    <?php if ( has_excerpt( $mvb_event ) || $mvb_event->post_content ) : ?>
      <p><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $mvb_event ) ) ); ?></p>
    <?php endif; ?>
    <?php if ( $mvb_meta_parts ) : ?>
      <p class="meta"><?php echo esc_html( implode( ' · ', $mvb_meta_parts ) ); ?></p>
    <?php endif; ?>
  </div>
</article>
