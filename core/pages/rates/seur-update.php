<?php
/**
 * SEUR Update
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Update Custom Rate.
 */
function seur_update_custom_rate() {

	if ( ! isset( $_POST['edit_rate_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edit_rate_nonce_field'] ) ), 'edit_seur_rate' ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;
	} else {
		if ( $_POST ) {
			global $wpdb;

			$table          = $wpdb->prefix . 'seur_custom_rates';
			$seur_id        = sanitize_text_field( wp_unslash( $_POST['id'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_rate      = sanitize_text_field( wp_unslash( $_POST['rate'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_country   = sanitize_text_field( wp_unslash( $_POST['country'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_state     = sanitize_text_field( wp_unslash( $_POST['state'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_minprice  = sanitize_text_field( wp_unslash( $_POST['minprice'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_maxprice  = sanitize_text_field( wp_unslash( $_POST['maxprice'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            $seur_minweight = sanitize_text_field( wp_unslash( $_POST['minweight'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            $seur_maxweight = sanitize_text_field( wp_unslash( $_POST['maxweight'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_rateprice = sanitize_text_field( wp_unslash( $_POST['rateprice'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_rate_type = sanitize_text_field( wp_unslash( $_POST['rate_type'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_postcode  = sanitize_textarea_field( wp_unslash( $_POST['postcode'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated

			if ( empty( $seur_minprice ) ) {
				$seur_minprice = '0';
			}
			if ( empty( $seur_postcode ) || '00000' === $seur_postcode || '0000' === $seur_postcode || '*' === $seur_postcode ) {
				$seur_postcode = '*';
			} else {
                if (!validatePostcodes($seur_postcode)) {
                    echo '<div class="notice notice notice-error"><p>' . esc_html__( 'The postcode is not valid', 'seur' ) . '</p></div>';
                    exit;
                }
            }
			if ( empty( $seur_rateprice ) ) {
				$seur_rateprice = '0';
			}
			if ( empty( $seur_state ) ) {
				$seur_state = '*';
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

			$wpdb->update(
				$table,
				array(
					'rate'      => $seur_rate,
					'country'   => $seur_country,
					'state'     => $seur_state,
					'minprice'  => $seur_minprice,
					'maxprice'  => $seur_maxprice,
                    'minweight'  => $seur_minweight,
                    'maxweight'  => $seur_maxweight,
					'rateprice' => $seur_rateprice,
					'postcode'  => $seur_postcode,
					'type'      => $seur_rate_type,
				),
				array( 'ID' => $seur_id ),
				array(
					'%s',
					'%s',
					'%s',
					'%f',
					'%f',
                    '%f',
                    '%f',
					'%f',
					'%s',
					'%s',
				),
				array( '%d' )
			);
			if ( ! $wpdb->insert_id ) {
				echo '<div class="notice notice-success">' . esc_html__( 'Rate successfully updated', 'seur' ) . '</div>';
			} else {
				echo '<div class="notice notice notice-error">' . esc_html__( 'There was and error at rate update, please try again', 'seur' ) . '</div>';
			}
		}
	}
}
