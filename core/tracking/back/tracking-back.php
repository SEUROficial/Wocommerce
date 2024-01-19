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

	$has_tracking   = get_post_meta( $post->ID, '_seur_shipping_id_number', true );
	$labels_id = seur_get_labels_ids($post->ID);

    foreach ($labels_id as $label_id) {
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
		update_post_meta( $post_id, '_seur_shipping_id_number', $seur_tracking_number );
	}
}
add_action( 'save_post', 'seur_save_tracking_meta_box', 999 );

/**
 * Save meta box content.
 *
 * @param int $label_order_id Label ID.
 * @param int $tracking_number Trackin munber.
 */
function seur_get_tracking_shipment( $label_order_id ) {

	if ( seur()->log_is_acive() ) {
		seur()->slog( 'Checking Tracking' );
		seur()->slog( '$label_order_id:' . $label_order_id );
	}

	$state = seur_tracking( $label_order_id );

    $shipmentStatus = $state['description'];
    updateShipmentStatus($label_order_id, $shipmentStatus);

    $eventCode = $state['eventCode'];
    $expeditionStatus = getStatusExpedition($eventCode);
    if (!isset($expeditionStatus['cod_situ'])) {
        if ( seur()->log_is_acive() ) {
            seur()->slog( ' Label_ID: '.$label_order_id.' - eventCode not found: '.$eventCode );
        }
        return false;
    }
    if ( seur()->log_is_acive() ) {
        seur()->slog( ' Label_ID: '.$label_order_id.' - expeditionStatus : '.$expeditionStatus['grupo'] );
    }
    updateExpeditionStatus($label_order_id, $expeditionStatus['grupo']);

	return true;
}
