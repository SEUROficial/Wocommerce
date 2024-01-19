<?php
/**
 * Seur help Tabs
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Seur settings add help tab.
 */
function seur_settings_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_user_settings_help_tab',
			'title'   => __( 'Users Settings', 'seur' ),
			'content' => '<p>' . __( 'From this screen, you&apos;ll be able to set up all the identifying details of your business.', 'seur' ) . '</p>
						<p>' . __( 'If you have any questions or need to add any additional information, you can contact your Sales Advisor for assistance.', 'seur' ) . '</p>',
		)
	);
	$screen->add_help_tab(
		array(
			'id'      => 'seur_advanced_settings_help_tab',
			'title'   => __( 'Advanced Settings', 'seur' ),
			'content' => '<p>' . __( 'From this screen, you&apos;ll be able to set up advanced options, such as the type of notification to your customers, the type of transport label generated and customs and international control data.', 'seur' ) . '</p>',
		)
	);
}

/**
 * Seur rates add help tab.
 */
function seur_rates_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_calculate_rates_help_tab',
			'title'   => __( 'Calculate Rates', 'seur' ),
			'content' => '<p>' . __( 'It calculates the rates that you have agreed with SEUR for a specific destination.', 'seur' ) . '</p>
						<p>' . __( 'Simply specify the postcode, town/city, country, number of packages and number of kilos, and we&apos;ll provide you with the corresponding rate that you have agreed.', 'seur' ) . '</p>',
		)
	);
	$screen->add_help_tab(
		array(
			'id'      => 'seur_custom_rates_help_tab',
			'title'   => __( 'Custom Rates', 'seur' ),
			'content' => '<p>' . __( 'With the information on the associated SEUR rate from the Calculate Rates menu, you have a better idea of the rates to pose to your customers. On this screen, you&apos;ll be able to create the rates that your customers can select for their shipments.', 'seur' ) . '</p>
						<p>' . __( 'To create a rate, click on Add Custom Rate, and select the type of Service/Product, the Country, the Province and, if you wish, the Postcode (add an * so that any one applies). Then indicate the shopping cart price range within which the rate must be applied and the amount of euros that your customers must pay in the Rate Price field.', 'seur' ) . '</p>
						<p>' . __( 'Remember that you can edit or eliminate previously created rates from the main, Custom Rates screen.', 'seur' ) . '</p>',
		)
	);
}

/**
 * Seur manifest add help tab.
 */
function seur_manifests_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_manifest_help_tab',
			'title'   => __( 'Manifest' ),
			'content' => '<p>' . __( 'Download the list of packages with the content of the deliveries notified to SEUR as from the date you choose.', 'seur' ) . '</p>
						<p>' . __( 'If you must hand over a list to the carrier, remember to print two copies: one for you and one for the carrier', 'seur' ) . '</p>',
		)
	);
}

/**
 * Seur Nomenclator add help tab.
 */
function seur_nomenclator_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_nomenclator_help_tab',
			'title'   => __( 'Nomenclator', 'seur' ),
			'content' => '<p>' . __( 'Consult the Postcode and Towns in the SEUR database.', 'seur' ) . '</p>',
		)
	);
}

/**
 * Seur product service add help tab.
 */
function seur_product_service_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_product_service_help_tab',
			'title'   => __( 'Product/Service', 'seur' ),
			'content' => '<p>' . __( 'Consult the combinations of available SEUR Services and Products in WooCommerce.', 'seur' ) . '</p>',
		)
	);
}

/**
 * Seur pickups add help tab.
 */
function seur_pickup_add_help_tab() {
	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'seur_pickupe_help_tab',
			'title'   => __( 'Collection', 'seur' ),
			'content' => '<p>' . __( 'Request that we go by to collect a package whenever you may need it.', 'seur' ) . '</p>
				<p>' . __( 'Remember to specify the number of packages and kilos that you are going to deliver, and when selecting the schedule, give us two-hour window for collecting a package.', 'seur' ) . '</p>
				<p>' . __( 'For example, if it&apos;s now 3 p.m., you could request that we come by to collect a package between 5 p.m. and 7 p.m', 'seur' ) . '</p>',
		)
	);
}

add_action( 'admin_head', 'seur_label_list_add_help_tab' );

/**
 * Seur label list add help tab.
 */
function seur_label_list_add_help_tab() {
	global $post_ID;
	$screen = get_current_screen();

	if ( isset( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} else {
		$post_type = get_post_type( $post_ID );
	}

	if ( 'seur_labels' === $post_type ) {

		// Add my_help_tab if current screen is My Admin Page.
		$screen->add_help_tab(
			array(
				'id'      => 'seur_label_help_tab',
				'title'   => __( 'Label List' ),
				'content' => '<p>' . __( 'From this screen you can get the order labels requested from menu WooCommerce > Orders.', 'seur' ) . '</p>',
			)
		);
	}
}

/**
 * Seur WooCommerce order list list add help tab.
 */
function seur_woocommercel_order_list_add_help_tab() {
	global $post_ID;
	$screen = get_current_screen();

	if ( isset( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} else {
		$post_type = get_post_type( $post_ID );
	}

	if ( 'shop_order' === $post_type ) {

		// Add my_help_tab if current screen is My Admin Page.
		$screen->add_help_tab(
			array(
				'id'      => 'seur_woocommerce_order_help_tab',
				'title'   => __( 'SEUR Options', 'seur' ),
				'content' => '<p>' . __( 'Help about WooCommerce SEUR Options.', 'seur' ) . '</p>',
			)
		);
	}
}
add_action( 'admin_head', 'seur_woocommercel_order_list_add_help_tab' );
