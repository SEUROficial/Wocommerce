<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register meta box(es).
 */
function seur_register_meta_boxes() {
    add_meta_box( 'seurmetabox', __( 'SEUR Labels', 'seur' ), 'seur_metabox_callback', 'shop_order', 'side', 'low' );
}
add_action( 'add_meta_boxes_shop_order', 'seur_register_meta_boxes', 999 );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_callback( $post ) {

    $weight   			  = '';
    $has_label 			  = '';
    $labelID   			  = '';
    $has_label 			  = get_post_meta( $post->ID, '_seur_shipping_order_label_downloaded',	true );
    $labelID   			  = get_post_meta( $post->ID, '_seur_shipping_label_id',				true );
    $url_to_file_down     = get_site_option( 'seur_download_file_url' );
    $label_path           = get_post_meta( $labelID, '_seur_shipping_order_label_path_name',	true );
    $label_path           = str_replace( "\\", "/", $label_path );
    $label_file_name      = get_post_meta( $labelID, '_seur_shipping_order_label_file_name',	true );
    $file_downlo_pass     = get_site_option( 'seur_pass_for_download' );
    $file_type            = get_post_meta( $labelID, '_seur_label_type',						true );

    ?> <div id="seur_content_metabox"> <?php

    if( ! $has_label ) {

           $url = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_get_labels_from_order' ), 'admin.php' ) ) );
           $arrayUrl = array ('order_id' => $post->ID, '?TB_iframe' => 'true', 'width' => '400', 'height' => '300' );
           $final_get_label_url = esc_url( add_query_arg(  $arrayUrl , $url ) );
           add_thickbox(); ?>
           <img src="<?php echo SEUR_PLUGIN_URL; ?>assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />
           <a class='thickbox button' title='<?php _e( 'Get SEUR Label','seur' ); ?>' alt='<?php _e( 'Get SEUR Label','seur' ); ?>' href='<?php echo $final_get_label_url; ?>'><?php _e( 'Get SEUR Label','seur' ); ?></a>
           <?php

	} else { ?>
		 <img src="<?php echo SEUR_PLUGIN_URL; ?>assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />
		 <?php echo '<a href="' . $url_to_file_down . '?label=' . $label_path . '&label_name=' . $label_file_name . '&pass=' . $file_downlo_pass . '&file_type=' . $file_type . '" class="button" target="_blank">' . __( ' See SEUR Label ', 'seur' ) . '</a>';

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

        $label  = seur_get_label( $post_id, $numpackages, $weight );

        $label_result  = $label[0]['result'];
        $labelID       = $label[0]['labelID'];
        $label_message = $label[0]['message'];

        if( $label_result ){

            $order = wc_get_order( $post_id );
            $order->update_status( $new_status, __( 'Label have been created:', 'seur' ), true );
            add_post_meta( $post_id,'_seur_shipping_order_label_downloaded',  'yes', true );
            add_post_meta( $post_id,'_seur_shipping_label_id',  $label_id, true );
            $order->add_order_note( 'The Label for Order #' . $post_id . ' have been downloaded', 0, true);

        } else {

	        // error
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