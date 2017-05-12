<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_after_get_label(){
	$seur_do = get_option('seur_after_get_label_field');

	if ( $seur_do == 'shipping' ) {
	$return = 'seur-shipment';

	} else {
		$return = 'completed';
	}

	return $return;
}
// Store cart weight in the database
add_action('woocommerce_checkout_update_order_meta', 'seur_add_cart_weight');
function seur_add_cart_weight( $order_id ) {
    global $woocommerce;

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
        'label_count'   => _n_noop( __('Awaiting SEUR shipment <span class="count">(%s)</span>', ''), __('Awaiting SEUR shipment <span class="count">(%s)</span>', '') )
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

    register_post_status( 'wc-seur-label', array(
        'label'     => 'Awaiting SEUR Labels',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting SEUR Label <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ), __('Awaiting SEUR Label <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ) )
    ) );

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
    $actions['wc_custom_order_action'] = __( 'Mark as printed for packaging', SEUR_TEXTDOMAIN );
    return $actions;
}
add_action( 'woocommerce_order_actions', 'seur_add_order_meta_box_action' );

function seur_register_awaiting_shipment_status_list() {

    register_post_status( 'wc-seur-shipment', array(
        'label'     => 'Awaiting SEUR Shipment',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting SEUR Shipment <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ), __('Awaiting SEUR Shipment <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ) )
    ) );

}
add_action( 'init', 'seur_register_awaiting_shipment_status_list' );

function seur_process_order_meta_box_action( $order ) {

    // add the order note
    // translators: Placeholders: %s is a user's display name
    $message = sprintf( __( 'Order information printed by %s for packaging.', SEUR_TEXTDOMAIN ), wp_get_current_user()->display_name );
    $order->add_order_note( $message );

    // add the flag
    update_post_meta( $order->id, '_wc_order_marked_printed_for_packaging', 'yes' );
}
add_action( 'woocommerce_order_action_wc_custom_order_action', 'seur_process_order_meta_box_action' );

add_action('admin_footer-edit.php', 'seur_custom_bulk_admin_footer');

function seur_custom_bulk_admin_footer() {

  global $post_type;

  if($post_type == 'shop_order') {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('mark_seur-label').text('<?php _e('Mark Awaiting SEUR Label', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action']");
        jQuery('<option>').val('mark_seur-shipment').text('<?php _e('Mark Awaiting SEUR Shipment', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action']");
        jQuery('<option>').val('mark_seur-label').text('<?php _e('Mark Awaiting SEUR Label', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action2']");
        jQuery('<option>').val('mark_seur-shipment').text('<?php _e('Mark Awaiting SEUR Shipment', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action2']");
        jQuery('<option>').val('seur-createlabel').text('<?php _e('Create SEUR Label (Only 1 label per order)', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action']");
        jQuery('<option>').val('seur-createlabel').text('<?php _e('Create SEUR Label (Only 1 label per order)', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action2']");
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
                    $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
                    add_post_meta( $post_id,'_seur_shipping_order_label_downloaded',  'yes', true );
                    add_post_meta( $post_id,'_seur_shipping_label_id',  $label_id, true );
                    $order->add_order_note( 'The Label for Order #' . $order_id . ' have been downloaded', 0, true);
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

    $has_label = get_post_meta( $the_order->id, '_seur_shipping_order_label_downloaded', true );
    if ( $has_label != 'yes' ) { // if order has not label
        $actions['cancel'] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=seur_get_label&order_id=' . $the_order->id ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Get SEUR Label (Only 1 label per order)', SEUR_TEXTDOMAIN ),
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
	                $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
	                add_post_meta( $order_id,'_seur_shipping_order_label_downloaded',  'yes', true );
	                add_post_meta( $order_id,'_seur_shipping_label_id',  $label_id, true );
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
    'label'       => __('Billing Mobile Phone', SEUR_TEXTDOMAIN ),  // Add custom field label
    'placeholder' => _x('Billing Mobile Phone', 'placeholder', SEUR_TEXTDOMAIN ),  // Add custom field placeholder
    'required'    => false,             // if field is required or not
    'class'       => array('billing-mobile-phone-field'),      // add class name
    'autocomplete' => 'mobile',
    'clear'     => true
    );

 return $fields;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'seur_billing_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_billing_mobil_phone_fields_display_admin_order_meta( $order ){
    echo '<p><strong>'.__('Billing Mobile Phone').':</strong> ' . get_post_meta( $order->id, '_billing_mobile_phone', true ) . '</p>';
}

add_filter( 'woocommerce_checkout_fields', 'seur_shipping_mobil_phone_fields' );

function seur_shipping_mobil_phone_fields( $fields ) {

   $fields['shipping']['shipping_mobile_phone'] = array(
    'label'       => __('Shipping Mobile Phone', SEUR_TEXTDOMAIN ),  // Add custom field label
    'placeholder' => _x('Shipping Mobile Phone', 'placeholder', SEUR_TEXTDOMAIN ),  // Add custom field placeholder
    'required'    => false,             // if field is required or not
    'class'       => array('shipping-mobile-phone-field'),      // add class name
    'autocomplete' => 'mobile',
    'clear'     => true
    );

 return $fields;
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'seur_shipping_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_shipping_mobil_phone_fields_display_admin_order_meta( $order ){
    echo '<p><strong>'.__('Shipping Mobile Phone').':</strong> ' . get_post_meta( $order->id, '_shipping_mobile_phone', true ) . '</p>';
}