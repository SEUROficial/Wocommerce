<?php

if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
}

	// Load defines.
	require_once 'defines/defines-loader.php';

	// Load Tracking.

	require_once 'tracking/loader.php';

	// Load functions.
	 require_once 'functions/functions.php';

	// Load WooCommerce functions if WooCommerce is active.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include_once 'woocommerce/seur-woocommerce.php';
	include_once 'tracking/loader.php';
}

	 require_once 'labels-cpt/labels-cpt.php';

	// Load menus.
	require_once 'menu/menu.php';
