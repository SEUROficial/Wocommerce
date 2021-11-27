<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function seur_create_custom_rate() {

	if ( ! isset( $_POST['new_seur_rate_nonce_field'] ) || ! wp_verify_nonce( $_POST['new_seur_rate_nonce_field'], 'add_new_seur_rate' ) ) {

		print 'Sorry, your nonce did not verify.';
		exit;

	} else {
		if ( $_POST ) {
			global $wpdb;

			$table = $wpdb->prefix . 'seur_custom_rates';

			$seur_rate      = sanitize_text_field( wp_unslash( $_POST['rate'] ) );
			$seur_country   = sanitize_text_field( wp_unslash( $_POST['country'] ) );
			$seur_state     = sanitize_text_field( wp_unslash( $_POST['state'] ) );
			$seur_minprice  = sanitize_text_field( wp_unslash( $_POST['minprice'] ) );
			$seur_maxprice  = sanitize_text_field( wp_unslash( $_POST['maxprice'] ) );
			$seur_rateprice = sanitize_text_field( wp_unslash( $_POST['rateprice'] ) );
			$seur_rate_type = sanitize_text_field( wp_unslash( $_POST['rate_type'] ) );
			$seur_postcode  = seur_sanitize_postcode( wp_unslash( $_POST['postcode'] ), $seur_country );

			if ( empty( $seur_city ) ) {
				$seur_city = '*';
			}
			if ( empty( $seur_minprice ) ) {
				$seur_minprice = '0';
			}
			if ( empty( $seur_postcode ) || $seur_postcode == '00000' || $seur_postcode == '0000' || $seur_postcode == '*' ) {
				$seur_postcode = '*';
			}
			if ( empty( $seur_rateprice ) ) {
				$seur_rateprice = '0';
			}
			if ( empty( $seur_state ) ) {
				$seur_state = '0';
			}
			if ( empty( $seur_country ) ) {
				$seur_country = '*';
			}
			if ( empty( $seur_maxprice ) || $seur_maxprice == '*' || $seur_maxprice > '9999999' ) {
				$seur_maxprice = '9999999';
			}

			$wpdb->insert(
				$table,
				array(
					'rate'      => $seur_rate,
					'country'   => $seur_country,
					'state'     => $seur_state,
					'postcode'  => $seur_postcode,
					'minprice'  => $seur_minprice,
					'maxprice'  => $seur_maxprice,
					'rateprice' => $seur_rateprice,
					'type'      => $seur_rate_type,
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%f',
					'%f',
					'%f',
					'%s',
				)
			);
			if ( $wpdb->insert_id ) {
				echo '<div class="notice notice-success">' . esc_html__( 'New rate successfully added', 'seur' ) . '</div>';
			} else {
				echo '<div class="notice notice notice-error">' . esc_html__( 'There was and error adding the new rate, please try again', 'seur' ) . '</div>';
			}
		} else {
			esc_html_e( "Sorry, you didn't post data.", 'seur' );
			exit;
		}
	}
}

