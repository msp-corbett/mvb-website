<?php
/**
 * Closes <main>, and the site footer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mvb_shop = mvb_get_shop_details();
?>
</main>

<footer>
  <div class="wrap">
    <div class="brand">
      <?php echo mvb_bird_icon(); ?>
      <b><?php bloginfo( 'name' ); ?></b>
      <p><?php esc_html_e( 'A boutique independent bookshop in Matakana Village. Chosen by hand.', 'mvb' ); ?></p>
    </div>
    <div>
      <h4><?php esc_html_e( 'Visit', 'mvb' ); ?></h4>
      <ul>
        <li><?php echo esc_html( $mvb_shop['address_line1'] ); ?><br><?php echo esc_html( $mvb_shop['address_line2'] ); ?></li>
        <li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $mvb_shop['phone'] ) ); ?>"><?php echo esc_html( $mvb_shop['phone'] ); ?></a></li>
        <li><a href="mailto:<?php echo esc_attr( $mvb_shop['email'] ); ?>"><?php echo esc_html( $mvb_shop['email'] ); ?></a></li>
        <li><?php echo esc_html( $mvb_shop['hours'] ); ?><br><?php echo esc_html( $mvb_shop['holiday_note'] ); ?></li>
      </ul>
    </div>
    <div>
      <h4><?php esc_html_e( 'Browse', 'mvb' ); ?></h4>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/bookshelf/' ) ); ?>"><?php esc_html_e( 'The bookshelf', 'mvb' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>"><?php esc_html_e( 'Events', 'mvb' ); ?></a></li>
        <li><a href="https://shop.matakanavillagebooks.co.nz"><?php esc_html_e( 'Online shop', 'mvb' ); ?></a></li>
      </ul>
    </div>
  </div>
  <div class="colophon">
    <div class="wrap">
      <span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span>
      <span><?php esc_html_e( 'Online shop by Circle', 'mvb' ); ?></span>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
