<?php
/**
 * SEUR Create Rate
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Create Custom Rate
 */
function seur_create_custom_rate() {

	if ( ! isset( $_POST['new_seur_rate_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['new_seur_rate_nonce_field'] ) ), 'add_new_seur_rate' ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		if ( $_POST ) {
			global $wpdb;

			$table = $wpdb->prefix . 'seur_custom_rates';

			$seur_rate      = sanitize_text_field( wp_unslash( $_POST['rate'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_country   = sanitize_text_field( wp_unslash( $_POST['country'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_state     = sanitize_text_field( wp_unslash( $_POST['state'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_minprice  = sanitize_text_field( wp_unslash( $_POST['minprice']??'0' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_maxprice  = sanitize_text_field( wp_unslash( $_POST['maxprice']??'9999999' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            $seur_minweight = sanitize_text_field( wp_unslash( $_POST['minweight']??'0' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            $seur_maxweight = sanitize_text_field( wp_unslash( $_POST['maxweight']??'9999999' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            $seur_rateprice = sanitize_text_field( wp_unslash( $_POST['rateprice'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_rate_type = sanitize_text_field( wp_unslash( $_POST['rate_type'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_postcode  = seur_sanitize_postcode( sanitize_text_field( wp_unslash( $_POST['postcode'] ) ), $seur_country ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated

			if ( empty( $seur_city ) ) {
				$seur_city = '*';
			}
			if ( empty( $seur_minprice ) ) {
				$seur_minprice = '0';
			}
            if ( empty( $seur_minweight ) ) {
                $seur_minweight = '0';
            }
			if ( empty( $seur_postcode ) || '00000' === $seur_postcode || '0000' === $seur_postcode || '*' === $seur_postcode ) {
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
            if (empty($seur_maxprice) || '*' === $seur_maxprice || $seur_maxprice > '9999999') {
                $seur_maxprice = '9999999';
            }

            if (empty($seur_maxweight) || '*' === $seur_maxweight || $seur_maxweight > '9999999') {
                $seur_maxweight = '9999999';
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
                    'minweight'  => $seur_minweight,
                    'maxweight'  => $seur_maxweight,
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
