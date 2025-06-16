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
    $screen = seur_get_order_screen();
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended -- Nonce verification is not applicable here
	if (isset($_GET['id'])) {
		$order_id = absint(wp_unslash($_GET['id']));
	} elseif (isset($_GET['post'])) {
		$order_id = absint(wp_unslash($_GET['post']));
	} else {
		$order_id = 0;
	}

	if (seur()->is_seur_order($order_id)) {
        add_meta_box('seurmetaboxtracking', __('SEUR Tracking', 'seur'), 'seur_metabox_tracking_callback', $screen, 'side', 'low');
    }
}
add_action( 'add_meta_boxes', 'seur_register_meta_boxes_tracking', 999 );

/**
 * Meta box display callback.
 */
function seur_metabox_tracking_callback( $post_or_order_object )
{
    $order = seur_get_order( $post_or_order_object );

	$has_tracking   = $order->get_meta( '_seur_shipping_id_number', true );
	$labels_id = seur_get_labels_ids($order->get_id());

    foreach ($labels_id as $label_id) {
        $order_tracking = get_post_meta( $label_id, '_seur_shipping_tracking_state', true );
        ?>
        <div id="seur_content_metabox">
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

	if ( ! isset( $_POST['seur_tracking_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_tracking_nonce_field'] ) ), 'seur_tracking_action' ) ) {
		return $post_id;
	}
	$seur_tracking_number = sanitize_text_field( wp_unslash( $_POST['seur-tracking-code'] ) );

	if ( ! empty( $seur_tracking_number ) ) {
		$label_ids = seur_get_labels_ids($post_id);
        foreach ($label_ids as $label_id) {
            update_post_meta( $label_id, '_seur_shipping_id_number', $seur_tracking_number );
        }
		//update_post_meta( $post_id, '_seur_shipping_id_number', $seur_tracking_number );
        $order = seur_get_order( $post_id );
        $order->update_meta_data('_seur_shipping_id_number', $seur_tracking_number );
        $order->save_meta_data();
	}
}
if (seur_is_wc_order_hpos_enabled()) {
    add_action( 'woocommerce_process_shop_order_meta', 'seur_save_tracking_meta_box', 999 );
} else {
    add_action('save_post', 'seur_save_tracking_meta_box', 999);
}

/**
 * Save meta box content.
 *
 * @param int $label_id Label ID.
 * @param int $tracking_number Trackin munber.
 */
function seur_get_tracking_shipment( $label_id ) {

	if ( seur()->log_is_acive() ) {
		seur()->slog( 'Checking Tracking' );
		seur()->slog( '$label_order_id:' . $label_id );
	}

	$state = seur_tracking( $label_id );

    $shipmentStatus = $state['description'];
    if (isset($shipmentStatus) && !empty($shipmentStatus)) {
        updateShipmentStatus($label_id, $shipmentStatus);
    }
    $eventCode = $state['eventCode'];
    $expeditionStatus = getStatusExpedition($eventCode);
    if (!isset($expeditionStatus['cod_situ'])) {
        if ( seur()->log_is_acive() ) {
            seur()->slog( ' Label_ID: '.$label_id.' - eventCode not found: '.$eventCode );
        }
        return false;
    }
    if ( seur()->log_is_acive() ) {
        seur()->slog( ' Label_ID: '.$label_id.' - expeditionStatus : '.$expeditionStatus['grupo'] );
    }
    updateExpeditionStatus($label_id, $expeditionStatus['grupo']);

	return true;
}
