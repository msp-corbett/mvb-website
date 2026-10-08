<?php
/**
 * Template Name: Coming Soon
 *
 * Every published coming-soon (pre-order) book, soonest arrival first,
 * with the same client-side filter-by-kind bar as the Bookshelf. Assign
 * this template to a page (conventionally slugged "coming-soon") from the
 * page attributes panel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$mvb_all_preorders = mvb_get_preorders();
$mvb_kinds         = mvb_get_used_pick_kinds( $mvb_all_preorders );
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">Coming soon</p>
    <h1>Worth the <i>wait</i>.</h1>
    <p>Books we're looking forward to that aren't on the shelf yet. The corner of each card says when it's due — pre-order and we'll put one aside for you the day it lands.</p>
  </div>
</section>

<section class="band band-paper">
  <div class="wrap">
    <?php if ( $mvb_all_preorders ) : ?>
      <?php if ( ! is_wp_error( $mvb_kinds ) && count( $mvb_kinds ) > 1 ) : ?>
        <div class="filters" id="filters" role="group" aria-label="Filter by kind">
          <button type="button" aria-pressed="true">Everything</button>
          <?php foreach ( $mvb_kinds as $mvb_kind ) : ?>
            <button type="button" aria-pressed="false"><?php echo esc_html( $mvb_kind->name ); ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="shelf" id="all-shelf" aria-live="polite">
        <?php foreach ( $mvb_all_preorders as $mvb_preorder ) : ?>
          <?php get_template_part( 'template-parts/pick-card', null, array( 'pick' => $mvb_preorder ) ); ?>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="empty">
        <?php echo mvb_bird_icon(); ?>
        <p>Nothing on order just now — but if there's a new release you're after, ring us on <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', mvb_get_shop_details()['phone'] ) ); ?>"><?php echo esc_html( mvb_get_shop_details()['phone'] ); ?></a> and we'll order it in.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
