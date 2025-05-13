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
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification is not applicable here, ajax request
    if ( isset( $_POST['del_id'] ) ) { // Validación básica
        global $wpdb;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $id    = absint( wp_unslash( $_POST['del_id'] ) );
        $table = $wpdb->prefix . 'seur_custom_rates';
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Safe custom table deletion no caching applicable
        $wpdb->delete( $table, array( 'ID' => $id ), array( '%d' ) );
    }
}