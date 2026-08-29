<?php
/**
 * The theme's required fallback template. Every real page on this site
 * uses front-page.php, page.php, or one of templates/page-*.php instead —
 * this only renders if something reaches a URL none of those cover.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="pagehead">
  <div class="wrap">
    <h1>Nothing here</h1>
    <p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to the homepage</a>.</p>
  </div>
</section>
<?php
get_footer();
