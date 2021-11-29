<?php
/**
 * Tracking back
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register meta box(es).
 */
function seur_register_meta_boxes_tracking() {
	add_meta_box( 'seurmetaboxtracking', __( 'SEUR Tracking', 'seur' ), 'seur_metabox_tracking_callback', 'shop_order', 'side', 'low' );
}
add_action( 'add_meta_boxes_shop_order', 'seur_register_meta_boxes_tracking', 999 );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_tracking_callback( $post ) {

	$has_tracking   = '';
	$has_tracking   = get_post_meta( $post->ID, '_seur_shipping_id_number', true );
	$label_id       = get_post_meta( $post->ID, '_seur_label_id_number', true );
	$order_tracking = get_post_meta( $label_id, '_seur_shipping_tracking_state', true );

	?>  <div id="seur_content_metabox">
		<label for="seur-tracking-code"><?php esc_html_e( 'Tracking ID', 'seur' ); ?></label>
		<input type="text" id="seur-tracking-code" name="seur-tracking-code" class="seur-tracking-code" size="16" autocomplete="off" style="width:100%;" value="
		<?php
		if ( ! empty( $has_tracking ) ) {
			echo esc_html( $has_tracking );
		}
		?>
		" >
		<?php wp_nonce_field( 'seur_tracking_action', 'seur_tracking_nonce_field' ); ?><br />
		<?php if ( empty( $label_id ) ) { ?>
				<ul class="order_notes">
					<li class="note system-note">
						<div class="note_content">
							<p><?php esc_html_e( 'Waiting Seur Label', 'seur' ); ?> </p>
						</div>
					</li>
				</ul>
			<?php

		} elseif ( ! empty( $label_id ) && empty( $order_tracking ) ) {
			?>
				<ul class="order_notes">
					<li class="note system-note">
						<div class="note_content">
							<p><?php esc_html_e( 'Waiting Collection or update tracking', 'seur' ); ?> </p>
						</div>
					</li>
				</ul>
			<?php
		} else {
			$order_tracking_unse = maybe_unserialize( $order_tracking );
			echo '<ul class="order_notes">';
			foreach ( $order_tracking_unse as $state => $value ) {
					echo '<li class="note">';
					echo '<div class="note_content">';
					echo '<p>' . esc_html( $value['descripcion_cliente'] ) . '</p>';
					echo '</div>';
					echo '<p class="meta">';
					echo '<abbr class="exact-date" title="' . esc_html( $value['fecha_situacion'] ) . '">' . esc_html__( 'added on', 'seur' ) . ' ' . esc_html( $value['fecha_situacion'] ) . '</abbr>';
					echo '</p>';
					echo '</li>';
			}
			echo '</ul>';
		}
		?>
		</div>
		<?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID.
 */
function seur_save_tracking_meta_box( $post_id ) {

	if ( ! isset( $_POST['seur-tracking-code'] ) ) {
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	if ( ! isset( $_POST['seur_tracking_nonce_field'] ) || ! wp_verify_nonce( $_POST['seur_tracking_nonce_field'], 'seur_tracking_action' ) ) {
		return $post_id;
	}
	$seur_tracking_number = sanitize_text_field( $_POST['seur-tracking-code'] );

	if ( ! empty( $seur_tracking_number ) ) {
		$label_id = get_post_meta( $post_id, '_seur_label_id_number', true );
		update_post_meta( $post_id, '_seur_shipping_id_number', $seur_tracking_number );
		update_post_meta( $label_id, '_seur_shipping_id_number', $seur_tracking_number );
	}
}
add_action( 'save_post', 'seur_save_tracking_meta_box', 999 );


function seur_get_tracking_shipment( $label_order_id, $tracking_number = false ) {

	$shipping_id    = get_post_meta( $label_order_id, '_seur_shipping_id_number', true );
	$user_data      = seur_get_user_settings();
	$franquicia     = $user_data[0]['franquicia'];
	$ccc            = $user_data[0]['ccc'];
	$usercom        = $user_data[0]['seurcom_usuario'];
	$passcom        = $user_data[0]['seurcom_contra'];
	$get_date       = get_the_date( 'Y-m-d', $label_order_id );
	$label_date_a   = new DateTime( $get_date );
	$label_date_str = get_the_date( 'd-m-Y', $label_order_id );
	$label_date     = new DateTime( $label_date_str );
	$now_str        = date( 'd-m-Y', current_time( 'timestamp', 0 ) ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested,WordPress.DateTime.RestrictedFunctions.date_date
	$now            = new DateTime( $now_str );
	$diff           = $label_date_a->diff( $now );
	$label_date_a->add( new DateInterval( 'P' . $diff->days . 'D' ) );

	if ( $diff->days <= 15 ) {
		$now = $now_str;
	} else {
		$label_date->add( new DateInterval( 'P' . $diff->days . 'D' ) );
		$now = $label_date->format( 'd-m-Y' );
	}
	$params     = array(
		'in0'  => 'S',
		'in1'  => '',
		'in2'  => '',
		'in3'  => $shipping_id,
		'in4'  => $ccc . '-' . $franquicia,
		'in5'  => $label_date_str,
		'in6'  => $now,
		'in7'  => '',
		'in8'  => '',
		'in9'  => '',
		'in10' => '',
		'in11' => '0',
		'in12' => $usercom,
		'in13' => $passcom,
		'in14' => 'N',
	);
	$sc_options = array(
		'connection_timeout' => 30,
	);

	$client  = new SoapClient( 'https://ws.seur.com/webseur/services/WSConsultaExpediciones?wsdl', $sc_options );
	$respons = $client->consultaExpedicionesStr( $params );

	$xml     = simplexml_load_string( $respons->out );
	$howmany = $xml->attributes()->NUM;

	if ( $howmany > 0 ) {
		foreach ( $xml->EXPEDICION->SITUACIONES->SITUACION as $item ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			delete_post_meta( $label_order_id, '_seur_shipping_tracking_state' );
			$expedition[] = array(
				'descripcion_cliente_ingles'    => (string) $item->DESCRIPCION_CLIENTE_INGLES, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'descripcion_cliente_frances'   => (string) $item->DESCRIPCION_CLIENTE_FRANCES, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'sit1'                          => (string) $item->SIT1, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'descripcion_cliente_ingles'    => (string) $item->SIT2, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'sit2'                          => (string) $item->ESTADO_CRM, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'area'                          => (string) $item->AREA, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'descripcion_cliente'           => (string) $item->DESCRIPCION_CLIENTE, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'descripcion_cliente_portugues' => (string) $item->DESCRIPCION_CLIENTE_PORTUGUES, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'fecha_situacion'               => (string) $item->FECHA_SITUACION, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'situacion_crm'                 => (string) $item->SITUACION_CRM, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'activa'                        => (string) $item->ACTIVA, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'transito'                      => (string) $item->TRANSITO, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			);

		}
			$all_expedition = maybe_serialize( $expedition );

	} else {
		$order_tracking = get_post_meta( $order_id, '_seur_shipping_tracking_state', true );

		if ( $order_tracking ) {
			$all_expedition = $order_tracking;
			update_post_meta( $label_order_id, '_seur_shipping_tracking_state', $all_expedition );
		}
		return true;
	}
	update_post_meta( $label_order_id, '_seur_shipping_tracking_state', $all_expedition );

	return true;
}
