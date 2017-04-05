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
        'label'     => 'Awaiting shipment',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting shipment <span class="count">(%s)</span>', ''), __('Awaiting shipment <span class="count">(%s)</span>', '') )
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
            $new_statuses_arr['wc-seur-shipment'] = 'Awaiting shipment';
        }
    }

    return $new_statuses_arr;

}
add_filter( 'wc_order_statuses', 'seur_add_awaiting_shipment_status' );

function seur_register_awaiting_labels_status() {

    register_post_status( 'wc-seur-label', array(
        'label'     => 'Awaiting Labels',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting Label <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ), __('Awaiting Label <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ) )
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
            $new_statuses_arr['wc-seur-label'] = 'Awaiting Label';
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
        'label'     => 'Awaiting Shipment',
        'public'    => true,
        'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
        'label_count'   => _n_noop( __('Awaiting Shipment <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ), __('Awaiting Shipment <span class="count">(%s)</span>', SEUR_TEXTDOMAIN ) )
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

                $label_id = seur_get_label( $post_id, '1' );
                if( $label_id ){

                    $order = wc_get_order( $post_id );
                    $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
                    add_post_meta( $post_id,'_seur_shipping_order_label_downloaded',  'yes', true );
                    add_post_meta( $post_id,'_seur_shipping_label_id',  $label_id, true );
                    $order->add_order_note( 'The Label for Order #' . $order_id . ' have been downloaded', 0, true);
                    //do_action( 'woocommerce_order_edit_status', $post_id, $new_status );

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
 * This snippet will add gat label to orders screen.
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

                $label_id   = seur_get_label( $order_id, '1' );
                $new_status = seur_after_get_label();

                 if ( $label_id ) {
	                $order = wc_get_order( $order_id );
	                $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
	                add_post_meta( $order_id,'_seur_shipping_order_label_downloaded',  'yes', true );
	                add_post_meta( $order_id,'_seur_shipping_label_id',  $label_id, true );
	                $order->add_order_note( 'The Label for Order #' . $order_id . ' have been downloaded', 0, true);
	                //add_action( 'woocommerce_order_edit_status', $order_id, $new_status );
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


/******* TEMP FUNCTION **************/

function get_order_details( $order_id ){

    // 1) Get the Order object
    $order = wc_get_order( $order_id );

    // OUTPUT
    echo '<h3>RAW OUTPUT OF THE ORDER OBJECT: </h3>';
    print_r($order);
    echo '<br><br>';
    echo '<h3>THE ORDER OBJECT (Using the object syntax notation):</h3>';
    echo '$order->order_type: ' . $order->order_type . '<br>';
    echo '$order->id: ' . $order->id . '<br>';
    echo '<h4>THE POST OBJECT:</h4>';
    echo '$order->post->ID: ' . $order->post->ID . '<br>';
    echo '$order->post->post_author: ' . $order->post->post_author . '<br>';
    echo '$order->post->post_date: ' . $order->post->post_date . '<br>';
    echo '$order->post->post_date_gmt: ' . $order->post->post_date_gmt . '<br>';
    echo '$order->post->post_content: ' . $order->post->post_content . '<br>';
    echo '$order->post->post_title: ' . $order->post->post_title . '<br>';
    echo '$order->post->post_excerpt: ' . $order->post->post_excerpt . '<br>';
    echo '$order->post->post_status: ' . $order->post->post_status . '<br>';
    echo '$order->post->comment_status: ' . $order->post->comment_status . '<br>';
    echo '$order->post->ping_status: ' . $order->post->ping_status . '<br>';
    echo '$order->post->post_password: ' . $order->post->post_password . '<br>';
    echo '$order->post->post_name: ' . $order->post->post_name . '<br>';
    echo '$order->post->to_ping: ' . $order->post->to_ping . '<br>';
    echo '$order->post->pinged: ' . $order->post->pinged . '<br>';
    echo '$order->post->post_modified: ' . $order->post->post_modified . '<br>';
    echo '$order->post->post_modified_gtm: ' . $order->post->post_modified_gtm . '<br>';
    echo '$order->post->post_content_filtered: ' . $order->post->post_content_filtered . '<br>';
    echo '$order->post->post_parent: ' . $order->post->post_parent . '<br>';
    echo '$order->post->guid: ' . $order->post->guid . '<br>';
    echo '$order->post->menu_order: ' . $order->post->menu_order . '<br>';
    echo '$order->post->post_type: ' . $order->post->post_type . '<br>';
    echo '$order->post->post_mime_type: ' . $order->post->post_mime_type . '<br>';
    echo '$order->post->comment_count: ' . $order->post->comment_count . '<br>';
    echo '$order->post->filter: ' . $order->post->filter . '<br>';
    echo '<h4>THE ORDER OBJECT (again):</h4>';
    echo '$order->order_date: ' . $order->order_date . '<br>';
    echo '$order->modified_date: ' . $order->modified_date . '<br>';
    echo '$order->customer_message: ' . $order->customer_message . '<br>';
    echo '$order->customer_note: ' . $order->customer_note . '<br>';
    echo '$order->post_status: ' . $order->post_status . '<br>';
    echo '$order->prices_include_tax: ' . $order->prices_include_tax . '<br>';
    echo '$order->tax_display_cart: ' . $order->tax_display_cart . '<br>';
    echo '$order->display_totals_ex_tax: ' . $order->display_totals_ex_tax . '<br>';
    echo '$order->display_cart_ex_tax: ' . $order->display_cart_ex_tax . '<br>';
    echo '$order->formatted_billing_address->protected: ' . $order->formatted_billing_address->protected . '<br>';
    echo '$order->formatted_shipping_address->protected: ' . $order->formatted_shipping_address->protected . '<br><br>';
    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

    // 2) Get the Order meta data
    $order_meta = get_post_meta($order_id);

    echo '<h3>RAW OUTPUT OF THE ORDER META DATA (ARRAY): </h3>';
    //echo '<pre>';
    print_r($order_meta);
    //echo '</pre>';
    echo '<br><br>';
    echo '<h3>THE ORDER META DATA (Using the array syntax notation):</h3>';
    echo '$order_meta[_order_key][0]: ' . $order_meta['_order_key'][0] . '<br>';
    echo '$order_meta[_order_currency][0]: ' . $order_meta['_order_currency'][0] . '<br>';
    echo '$order_meta[_prices_include_tax][0]: ' . $order_meta['_prices_include_tax'][0] . '<br>';
    echo '$order_meta[_customer_user][0]: ' . $order_meta['_customer_user'][0] . '<br>';
    echo '$order_meta[_billing_first_name][0]: ' . $order_meta['_billing_first_name'][0] . '<br><br>';
    echo 'And so on ……… <br><br>';
    echo '- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - <br><br>';

    // 3) Get the order items
    $items = $order->get_items();

    echo '<h3>RAW OUTPUT OF THE ORDER ITEMS DATA (ARRAY): </h3>';

    foreach ( $items as $item_id => $item_data ) {

        echo '<h4>RAW OUTPUT OF THE ORDER ITEM NUMBER: '. $item_id .'): </h4>';
        echo '<pre>';
        print_r($item_data);
        echo '</pre>';
        echo '<br><br>';
        echo 'Item ID: ' . $item_id. '<br>';
        echo '$item["product_id"] <i>(product ID)</i>: ' . $item_data['product_id'] . '<br>';
         echo 'producto url: <a href="' . get_home_url() . '/?p=' . $item_data['product_id'] . '" target="_blank">' . $item_data['name'] . '</a><br>';
        echo '$item["name"] <i>(product Name)</i>: ' . $item_data['name'] . '<br>';

        // Using get_item_meta() method
        echo 'Item quantity <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_qty', true) . '<br><br>';
        echo 'Item line total <i>(product quantity)</i>: ' . $order->get_item_meta($item_id, '_line_total', true) . '<br><br>';
        echo 'And so on ……… <br><br>';
        echo '- - - - - - - - - - - - - <br><br>';
    }
    echo '- - - - - - E N D - - - - - <br><br>';
}