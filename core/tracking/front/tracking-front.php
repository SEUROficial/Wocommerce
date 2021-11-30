<?php
/**
 * Tracking front.
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Order Details Tracking.
 *
 * @param int $order_id Order ID.
 */
function seur_order_details_tracking( $order_id ) {
	?>
	<h2><?php esc_html_e( 'Where is my Order?', 'seur' ); ?></h2>

	<?php

	$label_id       = get_post_meta( $order_id, '_seur_label_id_number', true );
	$order_tracking = get_post_meta( $label_id, '_seur_shipping_tracking_state', true );
	$ref            = get_post_meta( $order_id, '_seur_shipping_id_number', true );

	if ( ! empty( $order_tracking ) ) {
		$order_tracking_unse = maybe_unserialize( $order_tracking );
		foreach ( $order_tracking_unse as $state => $value ) {

			if ( 'ENTREGA EFECTUADA' === $value['descripcion_cliente'] ) {
				$date         = $value['fecha_situacion'];
				$label_date_a = new DateTime( $date );
				$date         = $label_date_a->format( 'd-m-Y' );
			}
		}
	}
	if ( ! empty( $order_tracking ) ) {

		echo '<a href="https://www.seur.com/livetracking/pages/seguimiento-online.do?segOnlineIdentificador=' . esc_html( $ref ) . '&amp;segOnlineFecha=' . esc_html( $date ) . '" target="_blank" rel="noopener">' . esc_html__( 'Click here', 'seur' ) . '</a> for check where is your order';
		echo '<p>&nbsp;</p>';

	} else {
		esc_html_e( 'Waiting shippment', 'seur' );
		echo '<p>&nbsp;</p>';
	}
}
add_action( 'woocommerce_view_order', 'seur_order_details_tracking', 10 );
