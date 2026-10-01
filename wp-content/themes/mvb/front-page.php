<?php
/**
 * Homepage: hero, "from the shelf" (3 random picks + reshuffle), "top
 * ten" (one random list), "what's on" (up to 3 upcoming events, hidden
 * entirely if none), "the shop" about copy, and "finding us".
 *
 * The About body and the Finding Us intro line are both editable from
 * wp-admin — the front page's own content and excerpt, respectively —
 * everything else here is data-driven from picks/lists/events/Shop
 * Details.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$mvb_shop           = mvb_get_shop_details();
$mvb_picks          = mvb_get_random_picks( 3 );
$mvb_pick_ids       = wp_list_pluck( $mvb_picks, 'ID' );
$mvb_list           = mvb_get_random_list();
$mvb_upcoming       = mvb_get_upcoming_events( 3 );
?>

<section class="hero">
  <svg class="bird hero-bird" aria-hidden="true"><use href="#bird"/></svg>
  <div class="wrap">
    <p class="eyebrow">Matakana Village · Under the cinema</p>
    <h1>Find it, <i>read it,</i></br>love it.</h1>
    <p class="lede">A small bookshop beside the Farmers Market, where every book on the shelf was chosen with care.</p>
    <div class="actions">
      <a class="btn btn-solid" href="<?php echo esc_url( home_url( '/bookshelf/' ) ); ?>">See the bookshelf</a>
      <a class="btn" href="<?php echo esc_url( home_url( '/#find' ) ); ?>">Plan a visit</a>
    </div>
  </div>
</section>

<?php if ( $mvb_picks ) : ?>
<section class="band band-paper">
  <div class="wrap">
    <div class="band-head">
      <h2>From the shelf<span class="sub"></span></h2>
      <a class="more" href="<?php echo esc_url( home_url( '/bookshelf/' ) ); ?>">The whole shelf →</a>
    </div>
    <div class="shelf" id="home-shelf" aria-live="polite" data-reshuffle-endpoint="picks/random" data-reshuffle-exclude="<?php echo esc_attr( implode( ',', $mvb_pick_ids ) ); ?>">
      <?php foreach ( $mvb_picks as $mvb_pick ) : ?>
        <?php get_template_part( 'template-parts/pick-card', null, array( 'pick' => $mvb_pick ) ); ?>
      <?php endforeach; ?>
    </div>
    <div class="actions">
      <button class="btn" id="reshuffle" type="button">Show me three more</button>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ( $mvb_list ) : ?>
<section class="band">
  <div class="wrap">
    <?php get_template_part( 'template-parts/list-columns', null, array( 'list' => $mvb_list ) ); ?>
  </div>
</section>
<?php endif; ?>

<?php if ( $mvb_upcoming ) : ?>
<section class="band band-paper" id="events-band">
  <div class="wrap">
    <div class="band-head">
      <h2>What's on</h2>
      <a class="more" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">All events →</a>
    </div>
    <div class="events" id="home-events">
      <?php foreach ( $mvb_upcoming as $mvb_event ) : ?>
        <?php get_template_part( 'template-parts/event-item', null, array( 'event' => $mvb_event ) ); ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="band" id="about">
  <div class="wrap about">
    <div class="entry-content">
      <h2>The shop</h2>
      <?php
      if ( have_posts() ) {
        while ( have_posts() ) {
          the_post();
          the_content();
        }
      }
      ?>
    </div>
    <div>
      <p class="pull">We can't stock everything, so we stock the good ones. If we haven't got it, we'll order it in and ring you when it lands.</p>
    </div>
  </div>
</section>

<section class="band band-paper" id="find">
  <div class="wrap find">
    <div>
      <h2>Finding us</h2>
      <?php
      $mvb_find_intro = has_excerpt()
        ? get_the_excerpt()
        : 'In the heart of Matakana Village, adjacent to the famous Farmers Market, and directly under the cinemas.';
      ?>
      <p class="intro"><?php echo esc_html( $mvb_find_intro ); ?></p>
      <dl>
        <div><dt>Open</dt><dd><?php echo esc_html( $mvb_shop['hours'] ); ?></dd></div>
        <div><dt>Public holidays</dt><dd><?php echo esc_html( $mvb_shop['holiday_note'] ); ?></dd></div>
        <div><dt>Address</dt><dd><?php echo esc_html( $mvb_shop['address_line1'] ); ?><br><?php echo esc_html( $mvb_shop['address_line2'] ); ?></dd></div>
        <div><dt>Phone</dt><dd><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $mvb_shop['phone'] ) ); ?>"><?php echo esc_html( $mvb_shop['phone'] ); ?></a></dd></div>
        <div><dt>Email</dt><dd><a href="mailto:<?php echo esc_attr( $mvb_shop['email'] ); ?>"><?php echo esc_html( $mvb_shop['email'] ); ?></a></dd></div>
      </dl>
      <div class="actions">
        <?php if ( $mvb_shop['map_url'] ) : ?>
          <a class="btn" href="<?php echo esc_url( $mvb_shop['map_url'] ); ?>">Directions →</a>
        <?php endif; ?>
      </div>
    </div>
    <div>
      <ul class="landmarks">
        <li><b>The market</b><span>Saturday mornings. We're a two-minute walk from the stalls.</span></li>
        <li><b>The cinemas</b><span>We're directly underneath. Come down before your session.</span></li>
        <li><b>Parking</b><span>Village car park, sixty seconds away.</span></li>
        <li><b>Too far?</b><span>The online shop carries our stock and posts nationwide.</span></li>
      </ul>
    </div>
  </div>
</section>

<?php get_footer(); ?>
