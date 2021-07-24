<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_update_custom_rate(){

    if ( ! isset( $_POST['edit_rate_nonce_field'] ) || ! wp_verify_nonce( $_POST['edit_rate_nonce_field'], 'edit_seur_rate' ) ) {

        print 'Sorry, your nonce did not verify.';
        exit;

    } else {

	    if ( $_POST ) {
            global $wpdb;

            $table = $wpdb->prefix . 'seur_custom_rates';

            $seur_id        = sanitize_text_field ( $_POST['id']                         );
            $seur_rate      = sanitize_text_field ( $_POST['rate']                       );
            $seur_country   = sanitize_text_field ( $_POST['country']                    );
            $seur_state     = sanitize_text_field ( $_POST['state']                      );
            $seur_minprice  = sanitize_text_field ( $_POST['minprice']                   );
            $seur_maxprice  = sanitize_text_field ( $_POST['maxprice']                   );
            $seur_rateprice = sanitize_text_field ( $_POST['rateprice']                  );
            $seur_rate_type	= sanitize_text_field( $_POST['rate_type']                   );
            $seur_postcode  = sanitize_text_field( $_POST['postcode'], $seur_country     );

			if ( empty( $seur_minprice ) )	$seur_minprice	= '0';
			if ( empty( $seur_postcode ) || $seur_postcode == '00000' || $seur_postcode == '0000' || $seur_postcode == '*' )	$seur_postcode	= '*';
			if ( empty( $seur_rateprice ) )	$seur_rateprice	= '0';
			if ( empty( $seur_state ) )		$seur_state		= '*';
			if ( empty( $seur_country ) )	$seur_country	= '*';
			if ( empty( $seur_maxprice ) || $seur_maxprice == '*' ||  $seur_maxprice > '9999999' )	$seur_maxprice	= '9999999';


            $wpdb->update(
                $table,
                array(
                    'rate'      => $seur_rate,
                    'country'   => $seur_country,
                    'state'     => $seur_state,
                    'minprice'  => $seur_minprice,
                    'maxprice'  => $seur_maxprice,
                    'rateprice' => $seur_rateprice,
                    'postcode'  => $seur_postcode,
                    'type'		=> $seur_rate_type
                ),
                array( 'ID' => $seur_id ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%f',
                    '%f',
                    '%f',
                    '%s',
                    '%s'
                ),
                array( '%d' )
            );

            if ( ! $wpdb->insert_id ) {

                echo '<div class="notice notice-success">' . __('Rate successfully updated', 'seur' ) . '</div>';

            } else {

                echo '<div class="notice notice notice-error">' . __('There was and error at rate update, please try again', 'seur' ) . '</div>';

            }
        }
    }
}
?>