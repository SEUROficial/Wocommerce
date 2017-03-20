<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register meta box(es).
 */
function seur_register_meta_boxes() {
    add_meta_box( 'seurmetabox', __( 'SEUR Labels', SEUR_TEXTDOMAIN ), 'seur_metabox_callback', 'shop_order', 'side', 'low' );
}
add_action( 'add_meta_boxes_shop_order', 'seur_register_meta_boxes', 999 );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_callback( $post ) {

    $weight    = '';
    $has_label = '';
    $labelID   = '';

    $has_label = get_post_meta( $post->ID, '_seur_shipping_order_label_downloaded', true );
    $labelID   = get_post_meta( $post->ID, '_seur_shipping_label_id', true );

    ?> <div id="seur_content_metabox"> <?php

    if( ! $has_label ) {

           $url = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_get_labels_from_order' ), 'admin.php' ) ) );
           $arrayUrl = array ('order_id' => $post->ID, '?TB_iframe' => 'true', 'width' => '900', 'height' => '550' );
           $final_get_label_url = esc_url( add_query_arg(  $arrayUrl , $url ) );
           add_thickbox(); ?>
           <a class='thickbox' title='<?php _e( 'Get Label',SEUR_TEXTDOMAIN ); ?>' alt='<?php _e( 'Get Label',SEUR_TEXTDOMAIN ); ?>' href='<?php echo $final_get_label_url; ?>'><?php _e( 'Get Label',SEUR_TEXTDOMAIN ); ?></a>
           <?php

	} else {

    echo $labelID;
} ?>
</div>
<?php
    }

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function seur_save_meta_box( $post_id ) {

    $numpackages = '';
    $weight      = '';
    $has_label   = '';
    $label_id    = '';

    if ( ! isset($_POST['seur-weight'] ) || ! isset($_POST['seur-number-packages'] ) ) {
	    return $post_id;
	    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	    return $post_id;
	    }

    if ( ! isset( $_POST['seur_get_label_metabox_nonce_field'] )  || ! wp_verify_nonce( $_POST['seur_get_label_metabox_nonce_field'], 'seur_get_label_metabox_action' ) ) {
	    return $post_id;
	    }
    $numpackages = $_POST['seur-number-packages'];
    $weight      = $_POST['seur-weight'];
    $new_status  = 'seur-shipment';
    $has_label   = get_post_meta( $post_id, '_seur_shipping_order_label_downloaded', true );

    if ( $has_label != 'yes' ) {

        $label_id = seur_get_label( $post_id, $numpackages, $weight );

        if( $label_id ){

            $order = wc_get_order( $post_id );
            $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
            add_post_meta( $post_id,'_seur_shipping_order_label_downloaded',  'yes', true );
            add_post_meta( $post_id,'_seur_shipping_label_id',  $label_id, true );
            $order->add_order_note( 'The Label for Order #' . $post_id . ' have been downloaded', 0, true);

        }
    }
}
add_action( 'save_post_shop_order', 'seur_save_meta_box', 999 );

function seur_metabox_save_buttom(){
    global $post_type;

    if($post_type == 'shop_order') {

         wp_enqueue_script( 'seurshoporderssavemeta', SEUR_PLUGIN_URL . 'assets/js/seur-shop-orders-cpt.js', array(), SEUR_OFFICIAL_VERSION );
         }

}
add_action( 'admin_print_scripts-post.php', 'seur_metabox_save_buttom', 11 );