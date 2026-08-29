<?php
/**
 * PHPUnit bootstrap for mvb-core's unit tests.
 *
 * These are isolated unit tests (Brain Monkey stubs WordPress core
 * functions — no WordPress install, database, or Docker required), scoped
 * to the plugin's pure business logic: date conversion, meta sanitization,
 * the list-books repeater's save-time filtering, and the REST payload
 * shape. See tests/README.md for what this suite does and doesn't cover.
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
require_once dirname( __DIR__ ) . '/wp-content/plugins/mvb-core/inc/pure-functions.php';
require_once dirname( __DIR__ ) . '/wp-content/themes/mvb/inc/pure-functions.php';
