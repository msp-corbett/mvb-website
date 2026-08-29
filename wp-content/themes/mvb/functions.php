<?php
/**
 * Matakana Village Books theme setup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MVB_THEME_VERSION', '1.0.0' );
define( 'MVB_THEME_DIR', get_template_directory() );
define( 'MVB_THEME_URL', get_template_directory_uri() );

require_once MVB_THEME_DIR . '/inc/pure-functions.php';
require_once MVB_THEME_DIR . '/inc/template-functions.php';
require_once MVB_THEME_DIR . '/inc/seo-meta.php';

function mvb_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary', 'mvb' ),
		)
	);

	// Cover images read best as a 2:3 book-cover crop.
	add_image_size( 'mvb-cover', 600, 900, true );

	// The front page uses this for the "Finding us" intro line, so that's
	// independently editable from the "The shop" content above it.
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'mvb_theme_setup' );

function mvb_enqueue_assets() {
	wp_enqueue_style(
		'mvb-fonts',
		'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,300..700;1,6..72,300..600&family=Karla:wght@400;500;700&family=Caveat:wght@500&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'mvb-main', MVB_THEME_URL . '/assets/css/main.css', array(), filemtime( MVB_THEME_DIR . '/assets/css/main.css' ) );

	// Both scripts are small, dependency-free progressive enhancements —
	// each one no-ops (via a guard clause) on pages without its markup.
	wp_enqueue_script( 'mvb-reshuffle', MVB_THEME_URL . '/assets/js/reshuffle.js', array(), filemtime( MVB_THEME_DIR . '/assets/js/reshuffle.js' ), true );
	wp_enqueue_script( 'mvb-filter', MVB_THEME_URL . '/assets/js/filter.js', array(), filemtime( MVB_THEME_DIR . '/assets/js/filter.js' ), true );

	wp_localize_script(
		'mvb-reshuffle',
		'MVB_REST',
		array(
			'root'  => esc_url_raw( rest_url( 'mvb/v1/' ) ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mvb_enqueue_assets' );

/**
 * fonts.googleapis.com needs a preconnect (and its actual font files come
 * from fonts.gstatic.com) — added alongside the stylesheet enqueue above.
 */
function mvb_font_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'mvb_font_preconnect', 0 );
