<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Load defines
	include_once ( 'defines/defines-loader.php' );

	// Load functions
	include_once ( 'functions/functions.php' );

	// Load WooCommerce functions if WooCommerce is active
	if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {
		include_once ( 'woocommerce/seur-woocommerce.php' );
		}

	// Load importer

	include_once ( 'importer/seur-importer.php' );

	include_once 'labels-cpt/labels-cpt.php';

	//Load menus
	include_once ( 'menu/menu.php' );