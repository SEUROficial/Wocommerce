<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function seur_process_label_woocommerce() {

	/*if ( ! isset( $_POST['new_seur_label_nonce_field'] ) || ! wp_verify_nonce( $_POST['new_seur_rate_nonce_field'], 'add_new_seur_rate' ) ) {

	   print 'Sorry, your nonce did not verify.';
	   exit;

	} else { */
		if( $_POST ) {
			global $wpdb;

			$table = $wpdb->base_prefix . 'seur_custom_rates';

			$seur_rate		= sanitize_text_field ( $_POST['rate']			);
			$seur_country	= sanitize_text_field ( $_POST['country']		);
			$seur_state		= sanitize_text_field ( $_POST['state']			);
			$seur_minprice	= sanitize_text_field ( $_POST['minprice']		);
			$seur_maxprice	= sanitize_text_field ( $_POST['maxprice']		);
			$seur_rateprice	= sanitize_text_field ( $_POST['rateprice']		);
			$seur_postcode	= seur_sanitize_postcode ( $_POST['postcode']	);

			if ( empty( $seur_city ) )		$seur_city		= '*';
			if ( empty( $seur_minprice ) )	$seur_minprice	= '0';
			if ( empty( $seur_postcode ) )	$seur_postcode	= '*';
			if ( empty( $seur_rateprice ) )	$seur_rateprice	= '0';
			if ( empty( $seur_state ) )		$seur_state		= '0';
			if ( empty( $seur_country ) )	$seur_country	= '0';
			if ( empty( $seur_maxprice ) )	$seur_maxprice	= '9999999';

			$wpdb->insert(
				$table,
				array(
					'rate'		=> $seur_rate,
					'country'	=> $seur_country,
					'state'		=> $seur_state,
					'postcode'	=> $seur_postcode,
					'minprice'	=> $seur_minprice,
					'maxprice'	=> $seur_maxprice,
					'rateprice'	=> $seur_rateprice
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d'
				)
			);
			if ( $wpdb->insert_id ) {
				echo '<div class="notice notice-success">' . __('New rate successfully added', 'seur-oficial' ) . '</div>';
			} else {
				echo '<div class="notice notice notice-error">' . __('There was and error adding the new rate, please try again', 'seur-oficial' ) . '</div>';
			}
		} else {
			_e("Sorry, you didn't post data.", 'seur-oficial' );
			exit;
		}
	//}

	}