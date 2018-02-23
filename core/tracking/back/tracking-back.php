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


function seur_get_tracking_shipment( $label_order_id, $tracking_number = false ) {

	$shipping_id    = get_post_meta( $label_order_id, '_seur_shipping_id_number', true );
	$user_data      = seur_get_user_settings();
    $franquicia     = $user_data[0]['franquicia'];
    $ccc            = $user_data[0]['ccc'];
    $usercom        = $user_data[0]['seurcom_usuario'];
    $passcom        = $user_data[0]['seurcom_contra'];
    $get_date       = get_the_date( 'Y-m-d', '666' );
	$label_date_A   = new DateTime( $get_date );
    $label_date_str = get_the_date( 'd-m-Y', '666' );
	$label_date     = new DateTime( $label_date_str );
    $now_str        = date( 'Y-m-d', current_time( 'timestamp', 0 ) );
	$now            = new DateTime( $now_str );
	$diff           = $label_date_A->diff( $now );

	if ( $diff->days <= 15 ) {
		$now = $now;
	} else {
		$now = $now;
	}
	$params = array(
				'in0'  => 'S',
				'in1'  => $shipping_id,
				'in2'  => '',
				'in3'  => '',
				'in4'  => $ccc. "-".$franquicia,
				'in5'  => '',
				'in6'  => '',
				'in7'  => '',
				'in8'  => '',
				'in9'  => '',
				'in10' => '',
				'in11' => '0',
				'in12' => $usercom,
				'in13' => $passcom,
				'in14' => 'N'
			);
	$sc_options = array(
				'connection_timeout' => 30
				);

	$client    = new SoapClient('https://ws.seur.com/webseur/services/WSConsultaExpediciones?wsdl', $sc_options );
	$respons   = $client->consultaListadoExpedicionesStr( $params );

	$xml = simplexml_load_string( $respons->out );

	add_post_meta( $label_order_id, '_seur_tracking_states', $respons, true );

	// An instance of
	$order = wc_get_order( $label_order_id );

	// Iterating through order shipping items
	foreach( $order->get_items( 'shipping' ) as $item_id => $shipping_item_obj ){
	    $shipping_method_id = $shipping_item_obj->get_method_id(); // The method ID
	}
	//retorno temporal para que no haya un error.
	return true;

}