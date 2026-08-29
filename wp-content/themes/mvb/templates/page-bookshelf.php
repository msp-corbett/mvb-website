<?php
/**
 * Template Name: Bookshelf
 *
 * Every published pick, with a client-side filter-by-kind bar. Assign
 * this template to a page (conventionally slugged "bookshelf") from the
 * page attributes panel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$mvb_all_picks = get_posts(
	array(
		'post_type'      => 'mvb_pick',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);
$mvb_kinds = mvb_get_used_pick_kinds();
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">The bookshelf</p>
    <h1>Everything here is here <i>on purpose</i>.</h1>
    <p>These are the books we've pressed into people's hands across the counter. Each one has a card under it in the shop, in someone's handwriting. This is the same thing, typed.</p>
  </div>
</section>

<section class="band band-paper">
  <div class="wrap">
    <?php if ( $mvb_all_picks ) : ?>
      <?php if ( ! is_wp_error( $mvb_kinds ) && count( $mvb_kinds ) > 1 ) : ?>
        <div class="filters" id="filters" role="group" aria-label="Filter by kind">
          <button type="button" aria-pressed="true">Everything</button>
          <?php foreach ( $mvb_kinds as $mvb_kind ) : ?>
            <button type="button" aria-pressed="false"><?php echo esc_html( $mvb_kind->name ); ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="shelf" id="all-shelf" aria-live="polite">
        <?php foreach ( $mvb_all_picks as $mvb_pick ) : ?>
          <?php get_template_part( 'template-parts/pick-card', null, array( 'pick' => $mvb_pick ) ); ?>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="empty">
        <?php echo mvb_bird_icon(); ?>
        <p>The shelf's being restocked — check back soon, or ring us on <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', mvb_get_shop_details()['phone'] ) ); ?>"><?php echo esc_html( mvb_get_shop_details()['phone'] ); ?></a>.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
