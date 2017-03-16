<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_update_custom_rate(){

    if ( ! isset( $_POST['edit_rate_nonce_field'] ) || ! wp_verify_nonce( $_POST['edit_rate_nonce_field'], 'edit_seur_rate' ) ) {

        print 'Sorry, your nonce did not verify.';
        exit;

    } else { if( $_POST )
        {
            global $wpdb;

            $table = $wpdb->base_prefix . 'seur_custom_rates';

            $seur_id        = sanitize_text_field ( $_POST['id']            );
            $seur_rate      = sanitize_text_field ( $_POST['rate']          );
            $seur_country   = sanitize_text_field ( $_POST['country']       );
            $seur_state     = sanitize_text_field ( $_POST['state']         );
            $seur_minprice  = sanitize_text_field ( $_POST['minprice']      );
            $seur_maxprice  = sanitize_text_field ( $_POST['maxprice']      );
            $seur_rateprice = sanitize_text_field ( $_POST['rateprice']     );
            $seur_postcode  = seur_sanitize_postcode ( $_POST['postcode']   );

            if ( empty( $seur_state ) )  $seur_state = '*';
            if ( empty( $seur_minprice ) ) $seur_minprice = '0';
            if ( empty( $seur_rateprice ) ) $seur_rateprice = '0';
            if ( empty( $seur_postcode ) || $seur_postcode == '00000' ) $seur_postcode = '*';

            $wpdb->update(
                $table,
                array(
                    'rate'      => $seur_rate,
                    'country'   => $seur_country,
                    'state'     => $seur_state,
                    'minprice'  => $seur_minprice,
                    'maxprice'  => $seur_maxprice,
                    'rateprice' => $seur_rateprice,
                    'postcode'  => $seur_postcode
                ),
                array( 'ID' => $seur_id ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s'
                ),
                array( '%d' )
            );

            if ( ! $wpdb->insert_id ) {
                echo '<div class="notice notice-success">' . __('Rate successfully updated', SEUR_TEXTDOMAIN ) . '</div>';
            } else {
                echo '<div class="notice notice notice-error">' . __('There was and error at rate update, please try again', SEUR_TEXTDOMAIN ) . '</div>';
            }
        }
    }
}
?>