<?php
/**
 * The "top ten" band's two-column numbered list. Expects $args['list']
 * (a WP_Post of type mvb_list). Renders nothing if the list has no books.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mvb_list = $args['list'] ?? null;
if ( ! $mvb_list instanceof WP_Post ) {
	return;
}

// mvb_get_list_books() comes from the MVB Core plugin, not the theme —
// guard against it being inactive rather than fataling the homepage.
if ( ! function_exists( 'mvb_get_list_books' ) ) {
	return;
}

$mvb_books = mvb_get_list_books( $mvb_list->ID );
if ( empty( $mvb_books ) ) {
	return;
}

$mvb_half     = (int) ceil( count( $mvb_books ) / 2 );
$mvb_columns  = array( array_slice( $mvb_books, 0, $mvb_half ), array_slice( $mvb_books, $mvb_half ) );
$mvb_shop_url = 'https://shop.matakanavillagebooks.co.nz';
?>
<div class="band-head">
  <h2 id="ten-title">
    <?php echo esc_html( get_the_title( $mvb_list ) ); ?>
    <?php $mvb_subtitle = get_post_meta( $mvb_list->ID, 'list_subtitle', true ); ?>
    <?php if ( $mvb_subtitle ) : ?>
      <span class="sub"><?php echo esc_html( $mvb_subtitle ); ?></span>
    <?php endif; ?>
  </h2>
  <a class="more" href="<?php echo esc_url( $mvb_shop_url ); ?>">Buy online →</a>
</div>
<div class="ten" id="ten">
  <?php foreach ( $mvb_columns as $mvb_i => $mvb_column ) : ?>
    <?php if ( empty( $mvb_column ) ) { continue; } ?>
    <ol<?php echo 1 === $mvb_i ? ' style="counter-reset:n ' . esc_attr( $mvb_half ) . '"' : ''; ?>>
      <?php foreach ( $mvb_column as $mvb_book ) : ?>
        <li>
          <a href="<?php echo esc_url( $mvb_book['url'] ?: $mvb_shop_url ); ?>"><?php echo esc_html( $mvb_book['title'] ); ?></a>
          <span class="by"><?php echo esc_html( $mvb_book['author'] ); ?></span>
        </li>
      <?php endforeach; ?>
    </ol>
  <?php endforeach; ?>
</div>
