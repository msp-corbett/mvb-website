<?php
/**
 * Plugin Name: MVB Core
 * Description: Custom post types, taxonomy, meta boxes and REST route behind the Matakana Village Books site (shelf-talker picks, top-ten lists, events, shop details).
 * Version: 1.0.0
 * Author: Matakana Village Books
 * Text Domain: mvb-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MVB_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MVB_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once MVB_CORE_DIR . 'inc/pure-functions.php';
require_once MVB_CORE_DIR . 'inc/cpt-pick.php';
require_once MVB_CORE_DIR . 'inc/cpt-list.php';
require_once MVB_CORE_DIR . 'inc/cpt-event.php';
require_once MVB_CORE_DIR . 'inc/taxonomy-pick-kind.php';
require_once MVB_CORE_DIR . 'inc/meta-boxes.php';
require_once MVB_CORE_DIR . 'inc/shop-details-page.php';
require_once MVB_CORE_DIR . 'inc/rest-endpoints.php';

/**
 * Flush rewrite rules once on activation so the CPT archives/permalinks work immediately.
 */
function mvb_core_activate() {
	mvb_register_pick_cpt();
	mvb_register_list_cpt();
	mvb_register_event_cpt();
	mvb_register_pick_kind_taxonomy();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mvb_core_activate' );

function mvb_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mvb_core_deactivate' );
