<?php
/**
 * SEUR WooCommerce Functins
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
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

// Store cart weight in the database.
add_action( 'woocommerce_update_order', 'seur_add_cart_weight_hpos' );
function seur_add_cart_weight_hpos( $order_id )
{
    if (WC()->cart && WC()->cart->cart_contents_count > 0) {
        $order = new WC_Order($order_id);

		$product_name = '';
        $ship_methods = maybe_unserialize($order->get_shipping_methods());
        foreach ($ship_methods as $ship_method) {
            $product_name = $ship_method['name'];
        }

        $products = seur()->get_products();
        foreach ($products as $code => $product) {
            $custom_name = get_option($product['field'] . '_custom_name_field') ? get_option($product['field'] . '_custom_name_field') : $code;
            if ($custom_name == $product_name) {
                $order->update_meta_data('_seur_shipping', 'seur');
                $order->update_meta_data('_seur_shipping_method_service_real_name', $code);
                $order->update_meta_data('_seur_shipping_method_service', sanitize_title($product_name));
                break;
            }
        }

        $weight = WC()->cart->cart_contents_weight;
        $order->update_meta_data('_seur_cart_weight', $weight);
        $order->save_meta_data();
    }
}
add_action('woocommerce_checkout_update_order_meta', 'seur_add_cart_weight');
function seur_add_cart_weight( $order_id ) {
	global $woocommerce;
    if ( $woocommerce->cart->cart_contents_count > 0 ) {
        $weight = $woocommerce->cart->cart_contents_weight;
        update_post_meta($order_id, '_seur_cart_weight', $weight);
    }
}

// Add order new column in administration.
if (seur_is_wc_order_hpos_enabled()) {
    add_filter( 'manage_woocommerce_page_wc-orders_columns', 'seur_order_weight_column', 20 );
} else {
    add_filter( 'manage_edit-shop_order_columns', 'seur_order_weight_column', 20 );
}
function seur_order_weight_column( $columns ) {
	$offset          = 8;
	$updated_columns = array_slice( $columns, 0, $offset, true ) +
	array( 'total_weight' => esc_html__( 'Weight', 'seur' ) ) +
	array_slice( $columns, $offset, null, true );
	return $updated_columns;
}

// Populate weight column.
if (seur_is_wc_order_hpos_enabled()) {
    add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'seur_custom_order_weight_column_hpos',  2, 2 );
}
else {
    add_action( 'manage_shop_order_posts_custom_column', 'seur_custom_order_weight_column', 2 );
}
function seur_custom_order_weight_column( $column ) {
    global $post;
	if ( $column == 'total_weight' ) {
        $weight = get_post_meta( $post->ID, '_seur_cart_weight', true ) ?:
            get_post_meta($post->ID, '_seur_shipping_weight', true);
		if ( $weight > 0 ) {
			print esc_html( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
		} else {
			print 'N/A';
		}
	}
}
function seur_custom_order_weight_column_hpos( $column, $order) {
    if ( $column == 'total_weight' ) {
        $weight = $order->get_meta(  '_seur_cart_weight', true ) ?:
            $order->get_meta('_seur_shipping_weight', true);
        if ( $weight > 0 ) {
            print esc_html( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
        } else {
            print 'N/A';
        }
    }
}

/**
 * Register new status with ID "wc-seur-shipment" and label "Awaiting shipment"
 */
function seur_register_awaiting_shipment_status() {

	register_post_status(
		'wc-seur-shipment',
		array(
			'label'                     => 'Awaiting SEUR shipment',
			'public'                    => true,
			'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
            // translators: %s is the number of SEUR shipments awaiting processing.
			'label_count' => _n_noop(
				'Awaiting SEUR shipment <span class="count">(%s)</span>',  // Singular
				'Awaiting SEUR shipments <span class="count">(%s)</span>', // Plural
				'seur'
			),
		)
	);

}
add_action( 'init', 'seur_register_awaiting_shipment_status' );

/*
 * Add registered status to list of WC Order statuses
 * @param array $wc_statuses_arr Array of all order statuses on the website
 */
function seur_add_awaiting_shipment_status( $wc_statuses_arr ) {

	$new_statuses_arr = array();

	// add new order status after processing.
	foreach ( $wc_statuses_arr as $id => $label ) {
		$new_statuses_arr[ $id ] = $label;

		if ( 'wc-processing' === $id ) { // after "Completed" status.
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
            // translators: %s is the number of SEUR labels awaiting processing.
			'label_count' => _n_noop(
				'Awaiting SEUR Label <span class="count">(%s)</span>',  // Singular
				'Awaiting SEUR Labels <span class="count">(%s)</span>', // Plural
				'seur'
			),
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

	// add new order status after processing.
	foreach ( $wc_statuses_arr as $id => $label ) {
		$new_statuses_arr[ $id ] = $label;

		if ( 'wc-processing' === $id ) { // after "Completed" status.
			$new_statuses_arr['wc-seur-label'] = 'Awaiting SEUR Label';
		}
	}

	return $new_statuses_arr;
}
add_filter( 'wc_order_statuses', 'seur_add_awaiting_labels_status' );

function seur_add_order_meta_box_action( $actions ) {
	global $theorder;

	// add "mark printed" custom action.
	$actions['wc_custom_order_action'] = __( 'Mark as printed for packaging', 'seur' );
	return $actions;
}
add_action( 'woocommerce_order_actions', 'seur_add_order_meta_box_action' );

function seur_register_awaiting_shipment_status_list() {

	register_post_status(
		'wc-seur-shipment',
		array(
			'label'                     => 'Awaiting SEUR Shipment',
			'public'                    => true,
			'show_in_admin_status_list' => true, // show count All (12) , Completed (9) , Awaiting shipment (2) ...
            // translators: %s is the number of SEUR shipments awaiting processing.
			'label_count' => _n_noop(
				'Awaiting SEUR Shipment <span class="count">(%s)</span>',  // Singular
				'Awaiting SEUR Shipments <span class="count">(%s)</span>', // Plural
				'seur'
			),
		)
	);

}
add_action( 'init', 'seur_register_awaiting_shipment_status_list' );

function seur_process_order_meta_box_action( $order ) {

	// add the order note.
	// translators: Placeholders: %s is a user's display name.
	$message = sprintf( __( 'Order information printed by %s for packaging.', 'seur' ), wp_get_current_user()->display_name );
	$order->add_order_note( $message );

	// add the flag.
	//update_post_meta( $order->get_id(), '_wc_order_marked_printed_for_packaging', 'yes' );
    $order->update_meta_data('_wc_order_marked_printed_for_packaging', 'yes' );
    $order->save_meta_data();
}
add_action( 'woocommerce_order_action_wc_custom_order_action', 'seur_process_order_meta_box_action' );

add_action( 'admin_footer-edit.php', 'seur_custom_bulk_admin_footer' );

add_action( 'admin_footer-woocommerce_page_wc-orders', 'seur_custom_bulk_admin_footer' );

function seur_custom_bulk_admin_footer() {

    global $post_type;
    global $page;

	if ( $post_type == 'shop_order'  || get_current_screen()->id == 'wc-orders' || get_current_screen()->id == 'woocommerce_page_wc-orders') {
		?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('<option>').val('mark_seur-label').text('<?php esc_html_e( 'Mark Awaiting SEUR Label', 'seur' ); ?>').appendTo("select[name='action']");
			jQuery('<option>').val('mark_seur-shipment').text('<?php esc_html_e( 'Mark Awaiting SEUR Shipment', 'seur' ); ?>').appendTo("select[name='action']");
			jQuery('<option>').val('mark_seur-label').text('<?php esc_html_e( 'Mark Awaiting SEUR Label', 'seur' ); ?>').appendTo("select[name='action2']");
			jQuery('<option>').val('mark_seur-shipment').text('<?php esc_html_e( 'Mark Awaiting SEUR Shipment', 'seur' ); ?>').appendTo("select[name='action2']");
			jQuery('<option>').val('seur-createlabel').text('<?php esc_html_e( 'Create SEUR Label (Only 1 label per order)', 'seur' ); ?>').appendTo("select[name='action']");
			jQuery('<option>').val('seur-createlabel').text('<?php esc_html_e( 'Create SEUR Label (Only 1 label per order)', 'seur' ); ?>').appendTo("select[name='action2']");
		});
	</script>
		<?php
	}
}

function seur_woo_bulk_action() {
	$changed = '';

	// 1. get the action
	$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
	$action        = $wp_list_table->current_action();

    // 2. check the ID
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_REQUEST['id'])) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $post_ids      = array_map( 'absint', (array) $_REQUEST['id'] );
    }
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (isset($_REQUEST['post'])) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $post_ids      = array_map( 'absint', (array) $_REQUEST['post'] );
    }
    if (!isset($post_ids)) {
        return;
    }

	$report_action = 'marked_seur-createlabel';

	switch ( $action ) {
		// 3. Perform the action.
		case 'seur-createlabel':
			$new_status = seur_after_get_label();
			$exported   = 0;
            $exported_labels = [];

			foreach ( $post_ids as $post_id ) {

				$has_label = seur()->has_label($post_id);

                if (!$has_label) {
                    if (!seur()->is_seur_order($post_id)) {
                        set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice',
                            'The order ID ' . $post_id . ' NOT is a SEUR shipment');
                        continue;
                    }
					$label = seur_api_get_label( $post_id );
					seur_api_set_label_result( $post_id, $label, $new_status);

					if (! $label['status'] ) {
						set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice',
							'The order ID ' . $post_id . ' has an Error: ' . $label['message'] );
					}
				}
				$exported++;
                $exported_labels[] = $post_id;
			}

            if ($exported) {
                set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice',
                    'Generated ' . $exported . ' labels for orders: ' . implode(',', $exported_labels) );
            }

			// build the redirect url.
			$sendback = add_query_arg(
				array(
					'page'         => 'wc-orders',
					$report_action => true,
					'changed'      => $changed,
					'ids'          => join(
						',',
						$post_ids
					),
				),
				''
			);
			break;
		default:
			return;
	}

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['post_status'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $sendback = add_query_arg( 'post_status', sanitize_text_field(wp_unslash($_GET['post_status'])), $sendback );
	}

	// 4. Redirect client.
	wp_redirect( esc_url_raw( $sendback ) );

	exit();
}
add_action( 'load-edit.php', 'seur_woo_bulk_action' );
add_action( 'load-woocommerce_page_wc-orders', 'seur_woo_bulk_action' ); // HPOS.

/**
 * This snippet will add get label to orders screen.
 */
add_filter( 'woocommerce_admin_order_actions', 'seur_add_label_order_actions_button', PHP_INT_MAX, 2 );
function seur_add_label_order_actions_button( $actions, $the_order ) {

	$has_label = seur()->has_label($the_order->get_id());
	if (!$has_label) { // if order has not label.
		$actions['cancel'] = array(
			'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=seur_get_label&order_id=' . $the_order->get_id() ), 'woocommerce-mark-order-status' ),
			'name'   => __( 'Get SEUR Label (Only 1 label per order)', 'seur' ),
			'action' => 'view label', // setting "view" for proper button CSS.
		);
	}
	return $actions;
}
add_action( 'admin_head', 'seur_add_label_order_actions_button_css' );
function seur_add_label_order_actions_button_css() {
	echo '<style>
		.view.label::after {
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

/**
 * SEUR get label Ajax
 */
function seur_get_label_ajax() {

	$order_id  = absint(sanitize_text_field( wp_unslash( $_GET['order_id'])) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
	$has_label = seur()->has_label($order_id);

	if (!$has_label) {
		if ( current_user_can( 'edit_shop_orders' ) && check_admin_referer( 'woocommerce-mark-order-status' ) ) {
			$label = seur_api_get_label( $order_id );
			$new_status = seur_after_get_label();
			seur_api_set_label_result( $order_id, $label, $new_status);

			if (! $label['status'] ) {
				set_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice',
					'The order ID ' . $order_id . ' has an Error: ' . $label['message'] );
			}
		}
	}

	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( seur_get_admin_url() ) );
	die();
}

add_action( 'wp_ajax_seur_get_label', 'seur_get_label_ajax' );
add_filter( 'woocommerce_checkout_fields', 'seur_billing_mobil_phone_fields' );

function seur_billing_mobil_phone_fields( $fields ) {

	$fields['billing']['billing_mobile_phone'] = array(
		'label'        => __( 'Mobile Phone', 'seur' ),  // Add custom field label.
		'placeholder'  => _x( 'Mobile Phone', 'placeholder', 'seur' ),  // Add custom field placeholder.
		'required'     => false,             // if field is required or not.
		'class'        => array( 'billing-mobile-phone-field' ),      // add class name.
		'autocomplete' => 'mobile',
		'clear'        => true,
	);

	return $fields;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'seur_billing_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_billing_mobil_phone_fields_display_admin_order_meta( $order ) {
	echo '<p><strong>' . esc_html__( 'Billing Mobile Phone', 'seur' ) . ':</strong> ' . esc_html( $order->get_meta( '_billing_mobile_phone', true ) ) . '</p>';
}

add_filter( 'woocommerce_checkout_fields', 'seur_shipping_mobil_phone_fields' );

function seur_shipping_mobil_phone_fields( $fields ) {

	$fields['shipping']['shipping_mobile_phone'] = array(
		'label'        => __( 'Mobile Phone', 'seur' ),  // Add custom field label.
		'placeholder'  => _x( 'Mobile Phone', 'placeholder', 'seur' ),  // Add custom field placeholder.
		'required'     => false,             // if field is required or not.
		'class'        => array( 'shipping-mobile-phone-field' ),      // add class name.
		'autocomplete' => 'mobile',
		'clear'        => true,
	);

	return $fields;
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'seur_shipping_mobil_phone_fields_display_admin_order_meta', 10, 1 );

function seur_shipping_mobil_phone_fields_display_admin_order_meta($order)
{
    echo '<p class="form-field _shipping_mobile_phone_field"><strong>' .
        esc_html__('Shipping Mobile Phone', 'seur') . ':</strong> ' .
        esc_html($order->get_meta('_shipping_mobile_phone', true)) . '</p>';
}

function seur_filter_price_rate_weight( $package_price, $raterate, $ratepricerate, $countryrate ) {

	$raterate = seur_get_real_rate_name( $raterate );

    $products = seur()->get_products();
    foreach ( $products as $code => $product ) {
        $max_price_field = get_option($product['field'] . '_max_price_field');
        if ($max_price_field == '0' || !$max_price_field) {
            $max_price_field = '99999999999';
        }
        if ($raterate == $code) {
            if ( $package_price > $max_price_field ) {
                return '0';
            } else {
                return $ratepricerate;
            }
        }
    }
    return $ratepricerate;
}

// defining the filter that will be used to select posts by 'post formats'.
function seur_post_formats_filter_to_woo_order_administration() {
	global $post_type;

    if (seur_is_order_page($post_type)) { ?>
        <label for="dropdown_shop_order_seur_shipping_method" class="screen-reader-text"><?php esc_attr_e( 'Seur Shippments', 'seur' ); ?></label>
		<select name="_shop_order_seur_shipping_method" id="dropdown_shop_order_seur_shipping_method">
			<option value=""><?php esc_html_e( 'All', 'seur' ); ?></option>
			<option value="seur"
			<?php
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $_shop_order_seur_shipping_method = isset( $_GET['_shop_order_seur_shipping_method'] ) ? esc_attr( sanitize_text_field(wp_unslash($_GET['_shop_order_seur_shipping_method']))) : '';
			if ($_shop_order_seur_shipping_method == 'seur') {
				echo 'selected';
            }?>
			><?php esc_html_e( 'All Seur Shipping', 'seur' ); ?></option>
            <?php
            $products = seur()->get_products();

            foreach ( $products as $code => $product ) {
                $custom_name = get_option($product['field'].'_custom_name_field')?get_option($product['field'].'_custom_name_field'):$code;
                $shippment_sani = sanitize_title( $custom_name ); ?>
                <option value="<?php echo esc_attr( $shippment_sani ); ?>"
                    <?php echo esc_attr( $_shop_order_seur_shipping_method !='' ? selected( $shippment_sani,$_shop_order_seur_shipping_method, false ) : '' ); ?>>
                    <?php echo esc_html( !empty($custom_name) ? $custom_name : $code ); ?>
                </option>
            <?php } ?>
		</select>
		<?php
	}
}
if (seur_is_wc_order_hpos_enabled()) {
    add_action( 'woocommerce_order_list_table_restrict_manage_orders', 'seur_post_formats_filter_to_woo_order_administration' );
} else {
    add_action('restrict_manage_posts', 'seur_post_formats_filter_to_woo_order_administration');
}

function seur_filter_orders_by_shipping_method_query( $vars ) {
    global $typenow;

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (seur_is_order_page( $typenow ) && isset( $_GET['_shop_order_seur_shipping_method'] ) && ! empty( $_GET['_shop_order_seur_shipping_method'] )
    ) {
        $products = seur()->get_products();
        // Filtro por defecto
        $meta_query = [
            [
                'key'   => '_seur_shipping',
                'value' => 'seur',
            ]
        ];
        $user_input = sanitize_text_field( wp_unslash( $_GET['_shop_order_seur_shipping_method'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        foreach ( $products as $code => $product ) {
            $custom_name = get_option( $product['field'] . '_custom_name_field' ) ?: $code;
            $shippment_sani = sanitize_title( $custom_name );

            if ( $shippment_sani === $user_input ) {
                $meta_query = [
                    [
                        'key'   => '_seur_shipping_method_service_real_name',
                        'value' => $code,
                    ]
                ];
                break;
            }
        }
        $vars['meta_query'] = $meta_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
    }
    return $vars;
}
if (seur_is_wc_order_hpos_enabled()) {
    add_filter( 'woocommerce_order_query_args', 'seur_filter_orders_by_shipping_method_query' );
} else {
    add_filter( 'request', 'seur_filter_orders_by_shipping_method_query' );
}

/**
 * Register new status with ID "wc-seur-shipment" and label "Awaiting shipment"
 */
function seur_register_tracking_statuses() {
	foreach (Seur_Logistica_Seguimiento::tracking_statuses_arr as $tracking_status => $tracking_label) {
		$new_statuses_arr[$tracking_status] = $tracking_label;
		register_post_status(
			$tracking_status,
			array(
				'label'                     => $tracking_label,
				'public'                    => true,
				'show_in_admin_status_list'  => true, // show count All (12), Completed (9), Awaiting shipment (2) ...
				// translators: %s is the count of tracking labels.
				'label_count' => _n_noop(
					'Tracking Label <span class="count">(%s)</span>',  // Singular
					'Tracking Labels <span class="count">(%s)</span>',  // Plural
					'seur'  // Text domain
				),
			)
		);
	}
}

add_action( 'init', 'seur_register_tracking_statuses' );

/*
 * Add registered status to list of WC Order statuses
 * @param array $wc_statuses_arr Array of all order statuses on the website
 */
function seur_add_traking_statuses( $wc_statuses_arr ) {

    $new_statuses_arr = array();
// TODO AQUI $tracking_statuses_arr = getTrackingStatuses();
    // add new order status after processing.
    foreach ( $wc_statuses_arr as $id => $label ) {
        $new_statuses_arr[ $id ] = $label;

        if ( 'wc-processing' === $id ) { // after "Completed" status.
            $new_statuses_arr['wc-seur-shipment'] = 'Awaiting SEUR Shipment';
            foreach (Seur_Logistica_Seguimiento::tracking_statuses_arr as $tracking_status => $tracking_label) {
                $new_statuses_arr[$tracking_status] = $tracking_label;
            }
        }
    }

    return $new_statuses_arr;

}
add_filter( 'wc_order_statuses', 'seur_add_traking_statuses' );
