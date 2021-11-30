<?php
/**
 * SEUR Delete
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Delete rate
 */
function seur_delete_rate() {
	if ( sanitize_text_field( wp_unslash( $_POST['del_id'] ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing

		global $wpdb;

		$id       = sanitize_text_field( wp_unslash( $_POST['del_id'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$table    = $wpdb->prefix . 'seur_custom_rates';
		$getrates = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}seur_custom_rates ORDER BY ID ASC" );
		$wpdb->delete( $table, array( 'ID' => $id ), array( '%d' ) );
	}
}
