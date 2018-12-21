<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function seur_after_get_label() {
	$seur_do = get_option( 'seur_after_get_label_field' );

	if ( 'shipping' === $seur_do ) {
		$return = 'seur-shipment';
	} else {
		$return = 'completed';
	}
	return $return;
}
// Store cart weight in the database
add_action( 'woocommerce_checkout_update_order_meta', 'seur_add_cart_weight' );

function seur_add_cart_weight( $order_id ) {
	global $woocommerce;

	$order        = new WC_Order( $order_id );
	$ship_methods = maybe_unserialize( $order->get_shipping_methods() );

	foreach ( $ship_methods as $ship_method ) {
		$product_name = $ship_method['name'];
	}
	$seur_bc2_custom_name                          = '';
	$seur_10e_custom_name                          = '';
	$seur_10ef_custom_name                         = '';
	$seur_13e_custom_name                          = '';
	$seur_13f_custom_name                          = '';
	$seur_48h_custom_name                          = '';
	$seur_72h_custom_name                          = '';
	$seur_cit_custom_name                          = '';
	$seur_2SHOP_custom_name                        = '';
	$seur_courier_int_aereo_paqueteria_custom_name = '';
	$seur_courier_int_aereo_documentos_custom_name = '';
	$seur_netexpress_int_terrestre_custom_name     = '';
	$seur_bc2_custom_name                          = get_option( 'seur_bc2_custom_name_field' );
	$seur_10e_custom_name                          = get_option( 'seur_10e_custom_name_field' );
	$seur_10ef_custom_name                         = get_option( 'seur_10ef_custom_name_field' );
	$seur_13e_custom_name                          = get_option( 'seur_13e_custom_name_field' );
	$seur_13f_custom_name                          = get_option( 'seur_13f_custom_name_field' );
	$seur_48h_custom_name                          = get_option( 'seur_48h_custom_name_field' );
	$seur_72h_custom_name                          = get_option( 'seur_72h_custom_name_field' );
	$seur_cit_custom_name                          = get_option( 'seur_cit_custom_name_field' );
	$seur_2SHOP_custom_name                        = get_option( 'seur_2SHOP_custom_name_field');
	$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
	$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
	$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

    if ( ! empty ( $seur_bc2_custom_name ) )  { $seur_bc2_custom_name  = $seur_bc2_custom_name;  } else { $seur_bc2_custom_name  =  'B2C Estándar';                    }
    if ( ! empty ( $seur_10e_custom_name ) )  { $seur_10e_custom_name  = $seur_10e_custom_name;  } else { $seur_10ef_custom_name =  'SEUR 10 Estándar';                }
    if ( ! empty ( $seur_10ef_custom_name ) ) { $seur_10ef_custom_name = $seur_10ef_custom_name; } else { $seur_bc2_custom_name  =  'SEUR 10 Frío';                    }
    if ( ! empty ( $seur_13e_custom_name ) )  { $seur_13e_custom_name  = $seur_13e_custom_name;  } else { $seur_13e_custom_name  =  'SEUR 13:30 Estándar';             }
    if ( ! empty ( $seur_13f_custom_name ) )  { $seur_13f_custom_name  = $seur_13f_custom_name;  } else { $seur_13f_custom_name  =  'SEUR 13:30 Frío';                 }
    if ( ! empty ( $seur_48h_custom_name ) )  { $seur_48h_custom_name  = $seur_48h_custom_name;  } else { $seur_48h_custom_name  =  'SEUR 48H Estándar';               }
    if ( ! empty ( $seur_72h_custom_name ) )  { $seur_72h_custom_name  = $seur_72h_custom_name;  } else { $seur_72h_custom_name  =  'SEUR 72H Estándar';               }
    if ( ! empty ( $seur_cit_custom_name ) )  { $seur_cit_custom_name  = $seur_cit_custom_name;  } else { $seur_cit_custom_name  =  'Classic Internacional Terrestre'; }
    if ( ! empty ( $seur_2SHOP_custom_name ) )  { $seur_2SHOP_custom_name  = $seur_2SHOP_custom_name;  } else { $seur_2SHOP_custom_name  =  'SEUR 2SHOP'; }
    if ( ! empty ( $seur_courier_int_aereo_paqueteria_custom_name ) )  { $seur_courier_int_aereo_paqueteria_custom_name  = $seur_courier_int_aereo_paqueteria_custom_name;  } else { $seur_courier_int_aereo_paqueteria_custom_name  =  'COURIER INT AEREO PAQUETERIA'; }
        if ( ! empty ( $seur_courier_int_aereo_documentos_custom_name ) )  { $seur_courier_int_aereo_documentos_custom_name  = $seur_courier_int_aereo_documentos_custom_name;  } else { $seur_courier_int_aereo_documentos_custom_name  =  'COURIER INT AEREO DOCUMENTOS'; }
        if ( ! empty ( $seur_netexpress_int_terrestre_custom_name ) )  { $seur_netexpress_int_terrestre_custom_name  = $seur_netexpress_int_terrestre_custom_name;  } else { $seur_netexpress_int_terrestre_custom_name  =  'NETEXPRESS INT TERRESTRE'; }

    $seur_shipments = array(
        $seur_bc2_custom_name,
        $seur_10e_custom_name,
        $seur_10ef_custom_name,
        $seur_13e_custom_name,
        $seur_13f_custom_name,
        $seur_48h_custom_name,
        $seur_72h_custom_name,
        $seur_cit_custom_name,
        $seur_2SHOP_custom_name,
        $seur_courier_int_aereo_paqueteria_custom_name,
        $seur_courier_int_aereo_documentos_custom_name,
        $seur_netexpress_int_terrestre_custom_name,
    );

    foreach ( $seur_shipments as $seur_shipment ) {
        if ( $seur_shipment == $product_name ) {
            update_post_meta( $order_id, '_seur_shipping_method_service', sanitize_title( $product_name ) );
            update_post_meta( $order_id, '_seur_shipping', 'seur' );
        }
    }

    $weight = WC()->cart->cart_contents_weight;
    update_post_meta( $order_id, '_seur_cart_weight', $weight );
}

// Add order new column in administration
add_filter( 'manage_edit-shop_order_columns', 'seur_order_weight_column', 20 );
function seur_order_weight_column( $columns ) {
    $offset = 8;
    $updated_columns = array_slice( $columns, 0, $offset, true) +
    array( 'total_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
    array_slice($columns, $offset, NULL, true);
    return $updated_columns;
}

// Populate weight column
add_action( 'manage_shop_order_posts_custom_column', 'seur_custom_order_weight_column', 2 );
function seur_custom_order_weight_column( $column ) {
    global $post;

    if ( $column == 'total_weight' ) {
        $weight = get_post_meta( $post->ID, '_seur_cart_weight', true );
        if ( $weight > 0 )
            print $weight . ' ' . esc_attr( get_option('woocommerce_weight_unit' ) );
        else print 'N/A';
    }
}

/**
 * Register new status with ID "wc-seur-shipment" and label "Awaiting shipment"
 */
function seur_register_awaiting_shipment_status() {

    register_post_status( 'wc-seur-shipment', array(
        'label'     => 'Awaiting SEUR shipment',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting SEUR shipment <span class="count">(%s)</span>', ''), __('Awaiting SEUR shipment <span class="count">(%s)</span>', 'seur') )
    ) );

}
add_action( 'init', 'seur_register_awaiting_shipment_status' );

/*
 * Add registered status to list of WC Order statuses
 * @param array $wc_statuses_arr Array of all order statuses on the website
 */
function seur_add_awaiting_shipment_status( $wc_statuses_arr ) {

    $new_statuses_arr = array();

    // add new order status after processing
    foreach ( $wc_statuses_arr as $id => $label ) {
        $new_statuses_arr[ $id ] = $label;

        if ( 'wc-processing' === $id ) { // after "Completed" status
            $new_statuses_arr['wc-seur-shipment'] = 'Awaiting SEUR Shipment';
        }
    }

    return $new_statuses_arr;

}
add_filter( 'wc_order_statuses', 'seur_add_awaiting_shipment_status' );

function seur_register_awaiting_labels_status() {

	register_post_status(
		'wc-seur-label',
		array(
			'label'                     => 'Awaiting SEUR Labels',
			'public'                    => true,
			'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
			'label_count'               => _n_noop( __('Awaiting SEUR Label <span class="count">(%s)</span>', 'seur' ), __('Awaiting SEUR Label <span class="count">(%s)</span>', 'seur' ) ),
		)
	);

}
add_action( 'init', 'seur_register_awaiting_labels_status' );

/*
 * Add registered status to list of WC Order statuses
 * @param array $wc_statuses_arr Array of all order statuses on the website
 */
function seur_add_awaiting_labels_status( $wc_statuses_arr ) {

    $new_statuses_arr = array();

    // add new order status after processing
    foreach ( $wc_statuses_arr as $id => $label ) {
        $new_statuses_arr[ $id ] = $label;

        if ( 'wc-processing' === $id ) { // after "Completed" status
            $new_statuses_arr['wc-seur-label'] = 'Awaiting SEUR Label';
        }
    }

    return $new_statuses_arr;
}
add_filter( 'wc_order_statuses', 'seur_add_awaiting_labels_status' );

function seur_add_order_meta_box_action( $actions ) {
    global $theorder;

    // add "mark printed" custom action
    $actions['wc_custom_order_action'] = __( 'Mark as printed for packaging', 'seur' );
    return $actions;
}
add_action( 'woocommerce_order_actions', 'seur_add_order_meta_box_action' );

function seur_register_awaiting_shipment_status_list() {

    register_post_status( 'wc-seur-shipment', array(
        'label'     => 'Awaiting SEUR Shipment',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting SEUR Shipment <span class="count">(%s)</span>', 'seur' ), __('Awaiting SEUR Shipment <span class="count">(%s)</span>', 'seur' ) )
    ) );

}
add_action( 'init', 'seur_register_awaiting_shipment_status_list' );

function seur_process_order_meta_box_action( $order ) {

    // add the order note
    // translators: Placeholders: %s is a user's display name
    $message = sprintf( __( 'Order information printed by %s for packaging.', 'seur' ), wp_get_current_user()->display_name );
    $order->add_order_note( $message );

    // add the flag
    update_post_meta( $order->get_id(), '_wc_order_marked_printed_for_packaging', 'yes' );
}
add_action( 'woocommerce_order_action_wc_custom_order_action', 'seur_process_order_meta_box_action' );

add_action('admin_footer-edit.php', 'seur_custom_bulk_admin_footer');

function seur_custom_bulk_admin_footer() {
	global $post_type;

	if ( 'shop_order' === $post_type ) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('<option>').val('mark_seur-label').text('<?php _e('Mark Awaiting SEUR Label', 'seur' )?>').appendTo("select[name='action']");
			jQuery('<option>').val('mark_seur-shipment').text('<?php _e('Mark Awaiting SEUR Shipment', 'seur' )?>').appendTo("select[name='action']");
			jQuery('<option>').val('mark_seur-label').text('<?php _e('Mark Awaiting SEUR Label', 'seur' )?>').appendTo("select[name='action2']");
			jQuery('<option>').val('mark_seur-shipment').text('<?php _e('Mark Awaiting SEUR Shipment', 'seur' )?>').appendTo("select[name='action2']");
			jQuery('<option>').val('seur-createlabel').text('<?php _e('Create SEUR Label (Only 1 label per order)', 'seur' )?>').appendTo("select[name='action']");
			jQuery('<option>').val('seur-createlabel').text('<?php _e('Create SEUR Label (Only 1 label per order)', 'seur' )?>').appendTo("select[name='action2']");
		});
	</script>
	<?php
	}
}

function seur_woo_bulk_action() {
    $changed = '';

  // 1. get the action
  $wp_list_table = _get_list_table('WP_Posts_List_Table');
  $action = $wp_list_table->current_action();

  if ( ! isset($_REQUEST['post']) ) return;

  //check_admin_referer('bulk-posts');

  $post_ids = array_map( 'absint', (array) $_REQUEST['post'] );
  $report_action = 'marked_seur-createlabel';

  switch($action) {
    // 3. Perform the action
    case 'seur-createlabel':


      $new_status = seur_after_get_label();
      $exported = 0;

      foreach( $post_ids as $post_id ) {

            $has_label = get_post_meta( $post_id, '_seur_shipping_order_label_downloaded', true );

            if ( $has_label != 'yes' ) {

                $label  = seur_get_label( $post_id, '1' );

                $label_result  = $label[0]['result'];
                $labelID       = $label[0]['labelID'];
                $label_message = $label[0]['message'];

                if( $label_result ){

                    $order = wc_get_order( $post_id );
                    $order->update_status( $new_status, __( 'Label have been created:', 'seur' ), true );
                    add_post_meta( $post_id,'_seur_shipping_order_label_downloaded',  'yes', true );
                    add_post_meta( $post_id,'_seur_shipping_label_id',  $labelID, true );
                    $order->add_order_note( 'The Label for Order #' . $post_id . ' have been downloaded', 0, true);
                    //do_action( 'woocommerce_order_edit_status', $post_id, $new_status );

                    } else {

                        set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice', 'The order ID ' . $post_id . ' has an Error: ' . $label_message );

                    }

            }

                $exported++;
      }

      // build the redirect url
      $sendback = add_query_arg( array( 'post_type' => 'shop_order', $report_action => true, 'changed' => $changed, 'ids' => join( ',', $post_ids ) ), '' );

    break;
    default: return;
  }

  if ( isset( $_GET['post_status'] ) ) {
            $sendback = add_query_arg( 'post_status', sanitize_text_field( $_GET['post_status'] ), $sendback );
        }

  // 4. Redirect client
  wp_redirect( esc_url_raw( $sendback ) );

  exit();
}
add_action( 'load-edit.php', 'seur_woo_bulk_action' );

/**
 * This snippet will add get label to orders screen.
 */
add_filter( 'woocommerce_admin_order_actions', 'seur_add_label_order_actions_button', PHP_INT_MAX, 2 );
function seur_add_label_order_actions_button( $actions, $the_order ) {

    $has_label = get_post_meta( $the_order->get_id(), '_seur_shipping_order_label_downloaded', true );
    if ( $has_label != 'yes' ) { // if order has not label
        $actions['cancel'] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=seur_get_label&order_id=' . $the_order->get_id() ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Get SEUR Label (Only 1 label per order)', 'seur' ),
            'action'    => "view label", // setting "view" for proper button CSS
        );
    }
    return $actions;
}
add_action( 'admin_head', 'seur_add_label_order_actions_button_css' );
function seur_add_label_order_actions_button_css() {
    echo '<style>.view.label::after {
                            content: "\e000" !important;
                            font-family: seur-font;
                            speak: none;
                            font-weight: 400;
                            font-variant: normal;
                            text-transform: none;
                            line-height: 1;
                            -webkit-font-smoothing: antialiased;
                            margin-top: 6px;
                            text-indent: 0;
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            text-align: center;
                        }
               .widefat .column-wc_actions a.view.label::after {
                            content: "\e000" !important;
                            font-family: seur-font;
                            speak: none;
                            font-weight: 400;
                            font-variant: normal;
                            text-transform: none;
                            line-height: 1;
                            -webkit-font-smoothing: antialiased;
                            margin-top: 6px;
                            text-indent: 0;
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            text-align: center;
                        }
          </style>';
}

function seur_get_label_ajax() {

         $order_id = absint( $_GET['order_id'] );

         $has_label = get_post_meta( $order_id, '_seur_shipping_order_label_downloaded', true );

         if ( $has_label != 'yes' ) {

            if ( current_user_can( 'edit_shop_orders' ) && check_admin_referer( 'woocommerce-mark-order-status' ) ) {

                $label  = seur_get_label( $order_id, '1' );

                $label_result  = $label[0]['result'];
                $labelID       = $label[0]['labelID'];
                $label_message = $label[0]['message'];

                $new_status = seur_after_get_label();

                 if ( $label_result ) {
                    $order = wc_get_order( $order_id );
                    $order->update_status( $new_status, __( 'Label have been created:', 'seur' ), true );
                    add_post_meta( $order_id,'_seur_shipping_order_label_downloaded',  'yes', true );
                    add_post_meta( $order_id,'_seur_shipping_label_id',  $labelID, true );
                    $order->add_order_note( 'The Label for Order #' . $order_id . ' have been downloaded', 0, true);
                    //add_action( 'woocommerce_order_edit_status', $order_id, $new_status );

                    } else {
                    set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice', 'The order ID ' . $order_id . ' has an Error: ' . $label_message );
                    }
            }
        }

        wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=shop_order' ) );
        die();
    }

add_action( 'wp_ajax_seur_get_label', 'seur_get_label_ajax' );

add_filter( 'woocommerce_checkout_fields', 'seur_billing_mobil_phone_fields' );

function seur_billing_mobil_phone_fields( $fields ) {

	$fields['billing']['billing_mobile_phone'] = array(
		'label'        => __( 'Mobile Phone', 'seur' ),  // Add custom field label
		'placeholder'  => _x( 'Mobile Phone', 'placeholder', 'seur' ),  // Add custom field placeholder
		'required'     => false,             // if field is required or not
		'class'        => array( 'billing-mobile-phone-field' ), // add class name
		'autocomplete' => 'mobile',
		'clear'        => true,
	);
	return $fields;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'seur_billing_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_billing_mobil_phone_fields_display_admin_order_meta( $order ){
    echo '<p><strong>'.__('Billing Mobile Phone').':</strong> ' . get_post_meta( $order->get_id(), '_billing_mobile_phone', true ) . '</p>';
}

add_filter( 'woocommerce_checkout_fields', 'seur_shipping_mobil_phone_fields' );

function seur_shipping_mobil_phone_fields( $fields ) {

   $fields['shipping']['shipping_mobile_phone'] = array(
    'label'       => __('Mobile Phone', 'seur' ),  // Add custom field label
    'placeholder' => _x('Mobile Phone', 'placeholder', 'seur' ),  // Add custom field placeholder
    'required'    => false,             // if field is required or not
    'class'       => array('shipping-mobile-phone-field'),      // add class name
    'autocomplete' => 'mobile',
    'clear'     => true
    );

 return $fields;
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'seur_shipping_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_shipping_mobil_phone_fields_display_admin_order_meta( $order ){
    echo '<p><strong>'.__('Shipping Mobile Phone').':</strong> ' . get_post_meta( $order->get_id(), '_shipping_mobile_phone', true ) . '</p>';
}

function seur_filter_price_rate_weight( $package_price, $raterate, $ratepricerate ){

    $raterate = seur_get_real_rate_name( $raterate );

    $seur_bc2_max_price_field  = get_option( 'seur_bc2_max_price_field'  );
    $seur_10e_max_price_field  = get_option( 'seur_10e_max_price_field'  );
    $seur_10ef_max_price_field = get_option( 'seur_10ef_max_price_field' );
    $seur_13e_max_price_field  = get_option( 'seur_13e_max_price_field'  );
    $seur_13f_max_price_field  = get_option( 'seur_13f_max_price_field'  );
    $seur_48h_max_price_field  = get_option( 'seur_48h_max_price_field'  );
    $seur_72h_max_price_field  = get_option( 'seur_72h_max_price_field'  );
    $seur_cit_max_price_field  = get_option( 'seur_cit_max_price_field'  );
    $seur_2SHOP_max_price_field = get_option( 'seur_2SHOP_max_price_field');



  $seur_courier_int_aereo_paqueteria_max_price_field = get_option( 'seur_courier_int_aereo_paqueteria_max_price_field' );
	$seur_courier_int_aereo_documentos_max_price_field = get_option( 'seur_courier_int_aereo_documentos_max_price_field' );
	$seur_netexpress_int_terrestre_max_price_field     = get_option( 'seur_netexpress_int_terrestre_max_price_field' );





    if ( $seur_bc2_max_price_field  == '0' || ! $seur_bc2_max_price_field  ) $seur_bc2_max_price_field  = '99999999999';
    if ( $seur_10e_max_price_field  == '0' || ! $seur_10e_max_price_field  ) $seur_10e_max_price_field  = '99999999999';
    if ( $seur_10ef_max_price_field == '0' || ! $seur_10ef_max_price_field ) $seur_10ef_max_price_field = '99999999999';
    if ( $seur_13e_max_price_field  == '0' || ! $seur_13e_max_price_field  ) $seur_13e_max_price_field  = '99999999999';
    if ( $seur_13f_max_price_field  == '0' || ! $seur_13f_max_price_field  ) $seur_13f_max_price_field  = '99999999999';
    if ( $seur_48h_max_price_field  == '0' || ! $seur_48h_max_price_field  ) $seur_48h_max_price_field  = '99999999999';
    if ( $seur_72h_max_price_field  == '0' || ! $seur_72h_max_price_field  ) $seur_72h_max_price_field  = '99999999999';
    if ( $seur_cit_max_price_field  == '0' || ! $seur_cit_max_price_field  ) $seur_cit_max_price_field  = '99999999999';
    if ( $seur_2SHOP_max_price_field  == '0' || ! $seur_2SHOP_max_price_field  ) $seur_2SHOP_max_price_field  = '99999999999';

    if ( $seur_courier_int_aereo_paqueteria_max_price_field  == '0' || ! $seur_courier_int_aereo_paqueteria_max_price_field  ) $seur_courier_int_aereo_paqueteria_max_price_field  = '99999999999';
    if ( $seur_courier_int_aereo_documentos_max_price_field  == '0' || ! $seur_courier_int_aereo_documentos_max_price_field  ) $seur_courier_int_aereo_documentos_max_price_field  = '99999999999';
    if ( $seur_netexpress_int_terrestre_max_price_field  == '0' || ! $seur_netexpress_int_terrestre_max_price_field  ) $seur_netexpress_int_terrestre_max_price_field  = '99999999999';

    if ( $raterate == 'B2C Estándar' ){
        if( $package_price > $seur_bc2_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 10 Estándar' ){
        if( $package_price > $seur_10e_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 10 Frío' ){
        if( $package_price > $seur_10ef_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 13:30 Estándar' ){
        if( $package_price > $seur_13e_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 13:30 Frío' ){
        if( $package_price > $seur_13f_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 48H Estándar' ){
        if( $package_price > $seur_48h_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 72H Estándar' ){
        if( $package_price > $seur_72h_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'Classic Internacional Terrestre' ){
        if( $package_price > $seur_cit_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'SEUR 2SHOP' ){
        if( $package_price > $seur_2SHOP_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    if ( $raterate == 'COURIER INT AEREO PAQUETERIA' ){
        if( $package_price > $seur_courier_int_aereo_paqueteria_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }
    if ( $raterate == 'COURIER INT AEREO DOCUMENTOS' ){
        if( $package_price > $seur_courier_int_aereo_documentos_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }
    if ( $raterate == 'NETEXPRESS INT TERRESTRE' ){
        if( $package_price > $seur_netexpress_int_terrestre_max_price_field ){
            $ratepricerate = '0';
        } else {
            $ratepricerate = $ratepricerate;
        }
    }

    return $ratepricerate;
}

//defining the filter that will be used to select posts by 'post formats'
function seur_post_formats_filter_to_woo_order_administration(){
	global $post_type;

	if ( 'shop_order' === $post_type ) {
		$seur_bc2_custom_name                          = '';
		$seur_10e_custom_name                          = '';
		$seur_10ef_custom_name                         = '';
		$seur_13e_custom_name                          = '';
		$seur_13f_custom_name                          = '';
		$seur_48h_custom_name                          = '';
		$seur_72h_custom_name                          = '';
		$seur_cit_custom_name                          = '';
		$seur_2SHOP_custom_name                        = '';
		$seur_courier_int_aereo_paqueteria_custom_name = '';
		$seur_courier_int_aereo_documentos_custom_name = '';
		$seur_netexpress_int_terrestre_custom_name     = '';
		$seur_bc2_custom_name                          = get_option( 'seur_bc2_custom_name_field'  );
		$seur_10e_custom_name                          = get_option( 'seur_10e_custom_name_field'  );
		$seur_10ef_custom_name                         = get_option( 'seur_10ef_custom_name_field' );
		$seur_13e_custom_name                          = get_option( 'seur_13e_custom_name_field'  );
		$seur_13f_custom_name                          = get_option( 'seur_13f_custom_name_field'  );
		$seur_48h_custom_name                          = get_option( 'seur_48h_custom_name_field'  );
		$seur_72h_custom_name                          = get_option( 'seur_72h_custom_name_field'  );
		$seur_cit_custom_name                          = get_option( 'seur_cit_custom_name_field'  );
		$seur_2SHOP_custom_name                        = get_option( 'seur_2SHOP_custom_name_field');
		$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
		$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
		$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

        if ( ! empty ( $seur_bc2_custom_name ) )  { $seur_bc2_custom_name  = $seur_bc2_custom_name;  } else { $seur_bc2_custom_name  =  'B2C Estándar';                    }
        if ( ! empty ( $seur_10e_custom_name ) )  { $seur_10e_custom_name  = $seur_10e_custom_name;  } else { $seur_10e_custom_name  =  'SEUR 10 Estándar';                }
        if ( ! empty ( $seur_10ef_custom_name ) ) { $seur_10ef_custom_name = $seur_10ef_custom_name; } else { $seur_10ef_custom_name =  'SEUR 10 Frío';                    }
        if ( ! empty ( $seur_13e_custom_name ) )  { $seur_13e_custom_name  = $seur_13e_custom_name;  } else { $seur_13e_custom_name  =  'SEUR 13:30 Estándar';             }
        if ( ! empty ( $seur_13f_custom_name ) )  { $seur_13f_custom_name  = $seur_13f_custom_name;  } else { $seur_13f_custom_name  =  'SEUR 13:30 Frío';                 }
        if ( ! empty ( $seur_48h_custom_name ) )  { $seur_48h_custom_name  = $seur_48h_custom_name;  } else { $seur_48h_custom_name  =  'SEUR 48H Estándar';               }
        if ( ! empty ( $seur_72h_custom_name ) )  { $seur_72h_custom_name  = $seur_72h_custom_name;  } else { $seur_72h_custom_name  =  'SEUR 72H Estándar';               }
        if ( ! empty ( $seur_cit_custom_name ) )  { $seur_cit_custom_name  = $seur_cit_custom_name;  } else { $seur_cit_custom_name  =  'Classic Internacional Terrestre'; }
        if ( ! empty ( $seur_2SHOP_custom_name ) )  { $seur_2SHOP_custom_name  = $seur_2SHOP_custom_name;  } else { $seur_2SHOP_custom_name  =  'SEUR 2SHOP'; }

        if ( ! empty ( $seur_courier_int_aereo_paqueteria_custom_name ) )  { $seur_courier_int_aereo_paqueteria_custom_name  = $seur_courier_int_aereo_paqueteria_custom_name;  } else { $seur_courier_int_aereo_paqueteria_custom_name  =  'COURIER INT AEREO PAQUETERIA'; }
        if ( ! empty ( $seur_courier_int_aereo_documentos_custom_name ) )  { $seur_courier_int_aereo_documentos_custom_name  = $seur_courier_int_aereo_documentos_custom_name;  } else { $seur_courier_int_aereo_documentos_custom_name  =  'COURIER INT AEREO DOCUMENTOS'; }
        if ( ! empty ( $seur_netexpress_int_terrestre_custom_name ) )  { $seur_netexpress_int_terrestre_custom_name  = $seur_netexpress_int_terrestre_custom_name;  } else { $seur_netexpress_int_terrestre_custom_name  =  'NETEXPRESS INT TERRESTRE'; }

		$seur_shipments = array(
			$seur_bc2_custom_name,
			$seur_10e_custom_name,
			$seur_10ef_custom_name,
			$seur_13e_custom_name,
			$seur_13f_custom_name,
			$seur_48h_custom_name,
			$seur_72h_custom_name,
			$seur_cit_custom_name,
			$seur_2SHOP_custom_name,
			$seur_courier_int_aereo_paqueteria_custom_name,
			$seur_courier_int_aereo_documentos_custom_name,
			$seur_netexpress_int_terrestre_custom_name,
		);
        ?>

        <label for="dropdown_shop_order_seur_shipping_method" class="screen-reader-text"><?php _e( 'Seur Shippments', 'seur' ); ?></label>
        <select name="_shop_order_seur_shipping_method" id="dropdown_shop_order_seur_shipping_method">
            <option value=""><?php _e( 'All', 'seur' ); ?></option>
            <option value="seur" <?php if ( ( esc_attr( isset( $_GET['_shop_order_seur_shipping_method'] ) ) ) &&  ( esc_attr( $_GET['_shop_order_seur_shipping_method'] ) == 'seur' ) ){ echo 'selected'; }?>><?php _e( 'All Seur Shipping', 'seur' ); ?></option>
<!--            <option value="all_seur"><?php _e( 'All Seur Shippments', 'seur' ); ?></option> -->
        <?php foreach ( $seur_shipments as $shippment ) :
                $shippment_sani = sanitize_title( $shippment );
        ?>
              <option value="<?php echo esc_attr( $shippment_sani ); ?>" <?php echo esc_attr( isset( $_GET['_shop_order_seur_shipping_method'] ) ? selected( $shippment_sani, $_GET['_shop_order_seur_shipping_method'], false ) : '' ); ?>>
                    <?php echo esc_html( $shippment ); ?>
              </option>

        <?php endforeach; ?>
        </select>
        <?php
    }
}
add_action('restrict_manage_posts','seur_post_formats_filter_to_woo_order_administration');

function seur_filter_orders_by_shipping_method_query( $vars ) {
    global $typenow;

    if ( 'shop_order' === $typenow && isset( $_GET['_shop_order_seur_shipping_method'] ) ) {

        $seur_bc2_custom_name  = '';
        $seur_10e_custom_name  = '';
        $seur_10ef_custom_name = '';
        $seur_13e_custom_name  = '';
        $seur_13f_custom_name  = '';
        $seur_48h_custom_name  = '';
        $seur_72h_custom_name  = '';
        $seur_cit_custom_name  = '';
        $seur_2SHOP_custom_name = '';
        $seur_courier_int_aereo_paqueteria_custom_name = '';
		$seur_courier_int_aereo_documentos_custom_name = '';
		$seur_netexpress_int_terrestre_custom_name     = '';

        $seur_bc2_custom_name  = get_option( 'seur_bc2_custom_name_field'  );
        $seur_10e_custom_name  = get_option( 'seur_10e_custom_name_field'  );
        $seur_10ef_custom_name = get_option( 'seur_10ef_custom_name_field' );
        $seur_13e_custom_name  = get_option( 'seur_13e_custom_name_field'  );
        $seur_13f_custom_name  = get_option( 'seur_13f_custom_name_field'  );
        $seur_48h_custom_name  = get_option( 'seur_48h_custom_name_field'  );
        $seur_72h_custom_name  = get_option( 'seur_72h_custom_name_field'  );
        $seur_cit_custom_name  = get_option( 'seur_cit_custom_name_field'  );
        $seur_2SHOP_custom_name = get_option( 'seur_2SHOP_custom_name_field');
        $seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
		$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
		$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

        if ( ! empty ( $seur_bc2_custom_name ) )  { $seur_bc2_custom_name  = $seur_bc2_custom_name;  } else { $seur_bc2_custom_name  =  'B2C Estándar';                    }
        if ( ! empty ( $seur_10e_custom_name ) )  { $seur_10e_custom_name  = $seur_10e_custom_name;  } else { $seur_10e_custom_name  =  'SEUR 10 Estándar';                }
        if ( ! empty ( $seur_10ef_custom_name ) ) { $seur_10ef_custom_name = $seur_10ef_custom_name; } else { $seur_10ef_custom_name =  'SEUR 10 Frío';                    }
        if ( ! empty ( $seur_13e_custom_name ) )  { $seur_13e_custom_name  = $seur_13e_custom_name;  } else { $seur_13e_custom_name  =  'SEUR 13:30 Estándar';             }
        if ( ! empty ( $seur_13f_custom_name ) )  { $seur_13f_custom_name  = $seur_13f_custom_name;  } else { $seur_13f_custom_name  =  'SEUR 13:30 Frío';                 }
        if ( ! empty ( $seur_48h_custom_name ) )  { $seur_48h_custom_name  = $seur_48h_custom_name;  } else { $seur_48h_custom_name  =  'SEUR 48H Estándar';               }
        if ( ! empty ( $seur_72h_custom_name ) )  { $seur_72h_custom_name  = $seur_72h_custom_name;  } else { $seur_72h_custom_name  =  'SEUR 72H Estándar';               }
        if ( ! empty ( $seur_cit_custom_name ) )  { $seur_cit_custom_name  = $seur_cit_custom_name;  } else { $seur_cit_custom_name  =  'Classic Internacional Terrestre'; }
        if ( ! empty ( $seur_2SHOP_custom_name ) )  { $seur_2SHOP_custom_name  = $seur_2SHOP_custom_name;  } else { $seur_2SHOP_custom_name  =  'SEUR 2SHOP'; }

        if ( ! empty ( $seur_courier_int_aereo_paqueteria_custom_name ) )  { $seur_courier_int_aereo_paqueteria_custom_name  = $seur_courier_int_aereo_paqueteria_custom_name;  } else { $seur_courier_int_aereo_paqueteria_custom_name  =  'COURIER INT AEREO PAQUETERIA'; }
        if ( ! empty ( $seur_courier_int_aereo_documentos_custom_name ) )  { $seur_courier_int_aereo_documentos_custom_name  = $seur_courier_int_aereo_documentos_custom_name;  } else { $seur_courier_int_aereo_documentos_custom_name  =  'COURIER INT AEREO DOCUMENTOS'; }
        if ( ! empty ( $seur_netexpress_int_terrestre_custom_name ) )  { $seur_netexpress_int_terrestre_custom_name  = $seur_netexpress_int_terrestre_custom_name;  } else { $seur_netexpress_int_terrestre_custom_name  =  'NETEXPRESS INT TERRESTRE'; }

        $seur_shipments = array(
            $seur_bc2_custom_name,
            $seur_10e_custom_name,
            $seur_10ef_custom_name,
            $seur_13e_custom_name,
            $seur_13f_custom_name,
            $seur_48h_custom_name,
            $seur_72h_custom_name,
            $seur_cit_custom_name,
            $seur_2SHOP_custom_name,
            $seur_courier_int_aereo_paqueteria_custom_name,
            $seur_courier_int_aereo_documentos_custom_name,
            $seur_netexpress_int_terrestre_custom_name,
        );

        foreach ( $seur_shipments as $shippment ) {
            if ( sanitize_title( $shippment ) == $_GET['_shop_order_seur_shipping_method'] ) {
                //$shop_order_seur_shipping_method = $shippment;
                $vars['meta_key']   = '_seur_shipping_method_service';
                $vars['meta_value'] = sanitize_title( $shippment );
            } elseif ( 'seur' == $_GET['_shop_order_seur_shipping_method'] ){
                $vars['meta_key']   = '_seur_shipping';
                $vars['meta_value'] = 'seur';
            }
        }
    }
    return $vars;
}
add_filter( 'request', 'seur_filter_orders_by_shipping_method_query' );
