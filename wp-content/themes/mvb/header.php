<?php
/**
 * The masthead, status strip, and <main> opening tag.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mvb_shop = mvb_get_shop_details();
?><!DOCTYPE html>
<html lang="en-NZ" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'mvb' ); ?></a>

<?php get_template_part( 'template-parts/svg-sprite' ); ?>

<header class="masthead">
  <div class="wrap">
    <a class="lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Matakana Village Books, home', 'mvb' ); ?>">
      <svg class="lockup-bird" aria-hidden="true"><use href="#bird"/></svg>
      <svg class="lockup-word" role="img" aria-label="Matakana Village Books"><use href="#wordmark"/></svg>
    </a>
    <nav aria-label="<?php esc_attr_e( 'Primary', 'mvb' ); ?>">
      <?php
      if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu(
          array(
            'theme_location' => 'primary',
            'container'      => false,
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'menu_class'     => 'nav',
            'depth'          => 1,
          )
        );
      } else {
        ?>
        <ul class="nav">
          <li><a href="<?php echo esc_url( home_url( '/bookshelf/' ) ); ?>">Bookshelf</a></li>
          <li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Events</a></li>
          <li><a class="hide-sm" href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About</a></li>
          <li><a href="<?php echo esc_url( home_url( '/#find' ) ); ?>">Find us</a></li>
          <li><a class="shop" href="https://shop.matakanavillagebooks.co.nz">Shop</a></li>
        </ul>
        <p class="sr"><?php esc_html_e( 'Set up the Primary navigation menu in Appearance → Menus to edit these links.', 'mvb' ); ?></p>
        <?php
      }
      ?>
    </nav>
  </div>
</header>

<div class="status">
  <div class="wrap">
    <span><span class="dot"></span><?php echo esc_html( $mvb_shop['hours'] ); ?></span>
    <?php if ( $mvb_shop['holiday_note'] ) : ?>
      <span><?php echo esc_html( $mvb_shop['holiday_note'] ); ?></span>
    <?php endif; ?>
    <span class="addr"><a href="<?php echo esc_url( home_url( '/#find' ) ); ?>"><?php echo esc_html( $mvb_shop['address_line1'] ); ?> →</a></span>
  </div>
</div>

<main id="main">
