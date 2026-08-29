<?php
/**
 * Template Name: Events
 *
 * All upcoming events, soonest first — past events never reach this
 * template at all (mvb_get_upcoming_events() excludes them at the query).
 * Assign this template to a page (conventionally slugged "events") from
 * the page attributes panel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$mvb_upcoming = mvb_get_upcoming_events();
$mvb_shop     = mvb_get_shop_details();
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">Events</p>
    <h1>Things that happen <i>in the room</i>.</h1>
    <p>Author evenings, the book club, and story time on market mornings. The shop holds about thirty people at a squeeze, so it's worth telling us you're coming.</p>
  </div>
</section>

<section class="band band-paper">
  <div class="wrap">
    <?php if ( $mvb_upcoming ) : ?>
      <div class="events" id="all-events">
        <?php foreach ( $mvb_upcoming as $mvb_event ) : ?>
          <?php get_template_part( 'template-parts/event-item', null, array( 'event' => $mvb_event, 'long_form' => true ) ); ?>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="empty">
        <?php echo mvb_bird_icon(); ?>
        <p>Nothing in the diary just now. Ring the shop on <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $mvb_shop['phone'] ) ); ?>"><?php echo esc_html( $mvb_shop['phone'] ); ?></a> — there is usually something brewing.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
