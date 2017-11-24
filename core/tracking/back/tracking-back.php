<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

    $has_tracking	= '';
    $has_tracking	= get_post_meta( $post->ID, '_seur_shipping_order_tracking',	true );

    ?> 	<div id="seur_content_metabox">
	    <input type="text" id="seur-tracking-code" name="seur-tracking-code" class="seur-tracking-code" size="16" autocomplete="off" value="<?php if ( ! empty( $has_tracking ) ) echo $has_tracking; ?>" >
	    <?php wp_nonce_field( 'seur_tracking_action', 'seur_tracking_nonce_field' ); ?>
		</div>
<?php
    }

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function seur_save_tracking_meta_box( $post_id ) {

    if ( ! isset($_POST['seur-tracking-code'] ) ) {
	    return $post_id;
	    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	    return $post_id;
	    }

    if ( ! isset( $_POST['seur_tracking_nonce_field'] )  || ! wp_verify_nonce( $_POST['seur_tracking_nonce_field'], 'seur_tracking_action' ) ) {
	    return $post_id;
	    }
    $seur_tracking_number = $_POST['seur-tracking-code'];

    if ( ! empty( $seur_tracking_number) ) {
	    add_post_meta( $post_id,'_seur_shipping_order_tracking',  $seur_tracking_number, true );
	    }
}
add_action( 'save_post_shop_order', 'seur_save_tracking_meta_box', 999 );