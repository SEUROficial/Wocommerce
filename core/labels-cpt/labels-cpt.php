<?php
/**
 * CPT Labels
 *
 * @package SEUR.
 */

use PDFMerger\PDFMerger;
use PDFGenerator\PDFGeneratorCore;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register Custom Post Type.
 */
function seur_cpt_labels() {

	$labels = array(
		'name'                  => _x( 'Shipments', 'Post Type General Name', 'seur' ),
		'singular_name'         => _x( 'Shipment', 'Post Type Singular Name', 'seur' ),
		'menu_name'             => __( 'Shipments', 'seur' ),
		'name_admin_bar'        => __( 'Shipments', 'seur' ),
		'archives'              => __( 'Labels', 'seur' ),
		'attributes'            => __( 'Shipments Atributte', 'seur' ),
		'parent_item_colon'     => __( 'Parent Item:', 'seur' ),
		'all_items'             => __( 'All Labels', 'seur' ),
		'add_new_item'          => __( 'Add New Shipment', 'seur' ),
		'add_new'               => __( 'Add New', 'seur' ),
		'new_item'              => __( 'New Shipment', 'seur' ),
		'edit_item'             => __( 'Shipment Data', 'seur' ),
		'update_item'           => __( 'Update Shipment', 'seur' ),
		'view_item'             => __( 'View Shipment', 'seur' ),
		'view_items'            => __( 'View Shipment', 'seur' ),
		'search_items'          => __( 'Search Shipment', 'seur' ),
		'not_found'             => __( 'Not found', 'seur' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'seur' ),
		'featured_image'        => __( 'Featured Image', 'seur' ),
		'set_featured_image'    => __( 'Set featured image', 'seur' ),
		'remove_featured_image' => __( 'Remove featured image', 'seur' ),
		'use_featured_image'    => __( 'Use as featured image', 'seur' ),
		'insert_into_item'      => __( 'Insert into Label', 'seur' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Label', 'seur' ),
		'items_list'            => __( 'Labels list', 'seur' ),
		'items_list_navigation' => __( 'Labels list navigation', 'seur' ),
		'filter_items_list'     => __( 'Filter Labels list', 'seur' ),
	);
	$args   = array(
		'label'               => __( 'Shipment', 'seur' ),
		'description'         => __( 'Seur Shipments', 'seur' ),
		'labels'              => $labels,
		'supports'            => false,
		'taxonomies'          => array( 'labels-product' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'menu_position'       => 10,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'shop_order',
	);
	register_post_type( 'seur_labels', $args );

}
add_action( 'init', 'seur_cpt_labels', 0 );

// Adding columns to Labels List.


/**
 * SEUR set custom label columns.
 *
 * @param array $columns add new columns.
 */
function seur_set_custom_label_columns( $columns ) {

	unset( $columns['title'] );
	unset( $columns['date'] );
	unset( $columns['taxonomy-labels-product'] );
	$columns['title']                   = __( 'Shipment ID', 'seur' );
	$columns['order_id']                = __( 'Order ID', 'seur' );
	$columns['product']                 = __( 'Product', 'seur' );
	$columns['customer_name']           = __( 'Customer Name', 'seur' );
	$columns['customer_comments']       = __( 'Customer Comments', 'seur' );
	$columns['weight']                  = __( 'Weight', 'seur' );
	$columns['print']                   = __( 'Print/Download', 'seur' );
	$columns['taxonomy-labels-product'] = __( 'Serv/Prod', 'seur' );
	$columns['seur-tracking']           = __( 'Tracking', 'seur' );
	$columns['date']                    = __( 'Label Date', 'seur' );

	return $columns;
}
add_filter( 'manage_seur_labels_posts_columns', 'seur_set_custom_label_columns' );

/**
 * SEUR get order tracking.
 *
 * @param int $order_id get order tracking by Order ID.
 */
function seur_get_label_tracking( $label_id ) {

	if ( seur()->log_is_acive() ) {
		seur()->slog( 'seur_get_label_tracking( $label_id )' );
		seur()->slog( '$label_id: ' . $label_id ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
	}

    $label_tracking = get_post_meta( $label_id, '_seur_shipping_tracking_state', true );
    $label_tracking_name = get_post_meta( $label_id, '_seur_shipping_tracking_state_name', true );

	if ( seur()->log_is_acive() ) {
		seur()->slog( '$label_tracking: ' . $label_tracking );
	}

    if (! empty( $label_tracking_name ) ) {
        return $label_tracking_name;
    }
	if ( ! empty( $label_tracking ) ) {
        return $label_tracking;
    }
	return __( 'Waiting Collection', 'seur' );
}

/**
 * SEUR get order tracking.
 *
 * @param string $column Colum name.
 * @param int    $label_id Post ID.
 */
function seur_custom_label_column_data( $column, $label_id )
{
    $order_id = get_post_meta($label_id, '_seur_shipping_order_id', true);
    $order = seur_get_order($order_id);

	switch ( $column ) {
		case 'order_id':
			$link = admin_url( 'post.php?post=' . $order_id . '&action=edit' );
			echo '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $order_id ) . '</a>';
			break;
		case 'product':
            $order_item   = $order->get_items();
            $product_name = array();
            foreach ( $order_item as $product ) {
                $product_name[] = '<li>' . $product['name'] . ' x ' . $product['qty'] . '</li>';
            }
            $product_list = implode( '', $product_name );
			echo '<ul>';
			echo wp_kses( $product_list, 'data' );
			echo '</ul>';
			break;
		case 'customer_name':
			echo esc_html( get_post_meta($label_id, '_seur_label_customer_name', true ) );
			break;
		case 'customer_comments':
			echo esc_html( get_post_meta($label_id, '_seur_shipping_order_customer_comments', true ) );
			break;
		case 'seur-tracking':
			echo esc_html( seur_get_label_tracking( $label_id ) );
			break;
		case 'weight':
			echo esc_html( get_post_meta($label_id, '_seur_shipping_weight', true) );
			break;
		case 'print':
            $url_upload_dir  = get_site_option( 'seur_uploads_url_labels' );
            $label_file_name = get_post_meta($label_id, '_seur_shipping_order_label_file_name', true );
			echo '<a href="' . esc_url( $url_upload_dir . '/' . $label_file_name ) . '" class="button" download>' . esc_html__( ' Open ', 'seur' ) . '</a>';
			break;
	}
}
add_action( 'manage_seur_labels_posts_custom_column', 'seur_custom_label_column_data', 10, 2 );

/**
 * Register Custom Taxonomy.
 */
function seur_add_label_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Products', 'Taxonomy General Name', 'seur' ),
		'singular_name'              => _x( 'Product', 'Taxonomy Singular Name', 'seur' ),
		'menu_name'                  => __( 'Product', 'seur' ),
		'all_items'                  => __( 'All products', 'seur' ),
		'parent_item'                => __( 'Parent Item', 'seur' ),
		'parent_item_colon'          => __( 'Parent Item:', 'seur' ),
		'new_item_name'              => __( 'New Product', 'seur' ),
		'add_new_item'               => __( 'Add new product', 'seur' ),
		'edit_item'                  => __( 'Edit Product', 'seur' ),
		'update_item'                => __( 'Update Product', 'seur' ),
		'view_item'                  => __( 'View Product', 'seur' ),
		'separate_items_with_commas' => __( 'Separate Products with commas', 'seur' ),
		'add_or_remove_items'        => __( 'Add or remove Products', 'seur' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'seur' ),
		'popular_items'              => __( 'Popular Products', 'seur' ),
		'search_items'               => __( 'Search Products', 'seur' ),
		'not_found'                  => __( 'Not Found', 'seur' ),
		'no_terms'                   => __( 'No Products', 'seur' ),
		'items_list'                 => __( 'Product list', 'seur' ),
		'items_list_navigation'      => __( 'Products list navigation', 'seur' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud'     => false,
		'rewrite'           => false,
	);
	register_taxonomy( 'labels-product', array( 'seur_labels' ), $args );

}
add_action( 'init', 'seur_add_label_taxonomy', 0 );

/**
 * Metabox Labels SEUR
 */

/**
 * Register meta box(es).
 */
function seur_label_register_meta_box() {
	add_meta_box( 'seurmetaboxlabel', __( 'Data Label', 'seur' ), 'seur_metabox_label_callback', 'seur_labels', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'seur_label_register_meta_box' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_label_callback( $post )
{
	$order_id = get_post_meta($post->ID,'_seur_shipping_order_id', true );
    if (!$order_id) {
        return;
    }
    $order = seur_get_order($order_id);
    $weight               = get_post_meta($post->ID,'_seur_shipping_weight', true );
    $customer_name        = get_post_meta($post->ID,'_seur_label_customer_name', true );
    $order_data           = seur_get_order_data( $order_id );
    $mobile_shipping      = $order->get_meta('_shipping_mobile_phone', true );
	$seur_shipping_method = seur_get_shipping_method( $order_id );

	$customer_country = $order_data[0]['country'];
	$customer_city     = seur_clean_data( $order_data[0]['city'] );
	$customer_postcode = $order_data[0]['postcode'];
	$customer_weight  = $order_data[0]['weight'];

	if ( ! $customer_weight ) {
		$customer_weight = $weight;
	}

	$customer_address_1   = seur_clean_data( $order_data[0]['address_1'] );
	$customer_address_2   = seur_clean_data( $order_data[0]['address_2'] );
	$customer_order_notes = seur_clean_data( $order_data[0]['order_notes'] );

	$billing_last_name    = $order->get_billing_last_name();
	$billing_name         = $order->get_billing_first_name();
	$billing_email        = $order->get_billing_email();
	$billing_country      = $order->get_billing_country();
	$billing_addr_1       = $order->get_billing_address_1();
	$billing_addr_2       = $order->get_billing_address_2();
	$billing_postcode     = $order->get_billing_postcode();
	$billing_city         = $order->get_billing_city();
	$billing_state        = $order->get_billing_state();
	$billing_phone        = $order->get_billing_phone();
    $billing_mobile_phone = $order->get_meta('_billing_mobile_phone', true );

	if ( !$customer_order_notes ) {
		$customer_order_notes = __( "There aren't comments for this order", 'seur' );
	}

	?>
	<div id="seur_content_metabox">

		<div id="order_data" class="panel">

			<h2><?php echo esc_html__( 'Details for Shipment ID #', 'seur' ) . esc_html( $post->ID ); ?> </h2>

			<div class="order_data_column_container">

				<div class="order_data_column">
					<h3>
						<?php esc_html_e( 'Billing Details', 'seur' ); ?>
					</h3>
					<div class="address">
						<p>
							<strong><?php esc_html_e( 'Adress:  ', 'seur' ); ?></strong> <?php echo esc_html( $billing_name ) . ' ' . esc_html( $billing_last_name ); ?><br>
							<?php echo esc_html( $billing_name ) . ' ' . esc_html( $billing_last_name ); ?><br>
							<?php echo esc_html( $billing_addr_1 ); ?><br>
							<?php echo esc_html( $billing_addr_2 ) . '<br>'; ?>
							<?php echo esc_html( $billing_postcode ) . ' ' . esc_html( $billing_city ) . '<br>'; ?>
							<?php echo esc_html( $billing_state ) . '<br>'; ?>
							<?php echo esc_html( $billing_country ); ?>
						</p>
					</div>
					<p>
						<strong><?php esc_html_e( 'Email: ', 'seur' ); ?></strong><a href="mailto:<?php echo esc_html( $billing_email ); ?>"><?php echo esc_html( $billing_email ); ?></a>
					</p>
					<p>
						<strong><?php esc_html_e( 'Phone: ', 'seur' ); ?></strong> <?php echo esc_html( $billing_phone ); ?>
					</p>
					<p>
						<strong><?php esc_html_e( 'Billing Mobile Phone: ', 'seur' ); ?></strong><?php echo esc_html( $billing_mobile_phone ); ?>
					</p>
				</div>

				<div class="order_data_column">

					<h3>
						<?php esc_html_e( 'Shipping Details', 'seur' ); ?>
					</h3>
					<div class="address">
						<p>
							<strong><?php esc_html_e( 'Adress:  ', 'seur' ); ?></strong> <?php echo esc_html( $customer_name ); ?><br>
							<?php echo esc_html( $customer_name ); ?><br>
							<?php echo esc_html( $customer_address_1 ); ?><br>
							<?php echo esc_html( $customer_address_2 ) . '<br>'; ?>
							<?php echo esc_html( $customer_postcode ) . ' ' . esc_html( $customer_city ) . '<br>'; ?>
							<?php echo esc_html( $customer_country ); ?>
						</p>
					</div>
					<p>
						<strong><?php esc_html_e( 'Shipping Mobile Phone: ', 'seur' ); ?></strong><?php echo esc_html( $mobile_shipping ); ?>
					</p>
					<p>
						<strong><?php esc_html_e( 'Shipping Method: ', 'seur' ); ?></strong> <?php echo esc_html( $seur_shipping_method ); ?>
					</p>

				</div>

				<p>
					<strong><?php esc_html_e( 'Customer notes about Order: ', 'seur' ); ?></strong><br />
					<?php echo esc_html( $customer_order_notes ); ?>
				</p>

			</div>

			<div class="clear"></div>

		</div>

	</div>

	<?php
}

/**
 * Seur Bulk Actions Handler
 *
 * @param array $bulk_actions PBulk actions.
 */
function seur_bulk_actions_labels_screen( $bulk_actions ) {

	$bulk_actions['download_seur_label']  = __( 'Download  SEUR Labels', 'seur' );
	$bulk_actions['update_seur_tracking'] = __( 'Update SEUR Tracking', 'seur' );
    $bulk_actions['generate_seur_manifest'] = __( 'Generate Manifest', 'seur' );
	return $bulk_actions;
}
add_filter( 'bulk_actions-edit-seur_labels', 'seur_bulk_actions_labels_screen' );

/**
 * Seur Bulk Actions Handler
 *
 * @param string $redirect_to url.
 * @param string $doaction action to do.
 * @param array  $post_ids Post IDs.
 */
function seur_bulk_actions_handler( $redirect_to, $doaction, $labels_ids ) {

	if ( 'download_seur_label' !== $doaction && 'update_seur_tracking' !== $doaction && 'generate_seur_manifest' != $doaction ) {
		return $redirect_to;
	}
	if ( 'download_seur_label' === $doaction ) {

        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        WP_Filesystem();

        $date = gmdate( 'd-m-Y-H-i-s' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		$type = seur_get_file_type(seur()->get_option( 'seur_tipo_etiqueta_field' ));
        $bulk_label_name = 'label_bulk_' . $date . seur_get_file_type_extension($type);
        $upload_dir      = seur_upload_dir( 'labels' );
        $upload_path     = $upload_dir . '/' . $bulk_label_name;
        $fp = '';

        foreach ( $labels_ids as $label_id ) {
            if (!isset($pdf)) {
                $pdf = new PDFMerger;
            }
            $label_type      = seur_get_file_type(get_post_meta( $label_id, '_seur_label_type', true ));
            $label_file_name = get_post_meta( $label_id, '_seur_shipping_order_label_file_name', true );
            $label_path      = get_post_meta( $label_id, '_seur_shipping_order_label_path_name', true );

            if ( $label_type != $type || !file_exists($label_path) || empty($label_file_name)) {
                // creación de etiqueta si no existe el fichero o ha cambiado el tipo de impresora
                $order_id    = get_post_meta( $label_id, '_seur_shipping_order_id', true );
                if ($order_id == "") { // al procesar una etiqueta, si estaba asociada a un pedido del que ya se ha regenerado una etiqueta, y por tanto borrado las anteriores, se continua
                    continue;
                }
                $numpackages = get_post_meta( $label_id, '_seur_shipping_packages', true );
                $weigth      = get_post_meta( $label_id, '_seur_shipping_weight', true );
                $result = seur_api_get_label($order_id, $numpackages, $weigth, false);
                if (!isset($result['result'][0]['labelID'])) {
                    continue;
                }
                $label_id = $result['result'][0]['labelID'];
                $label_type      = seur_get_file_type(get_post_meta( $label_id, '_seur_label_type', true ));
                $label_file_name = get_post_meta( $label_id, '_seur_shipping_order_label_file_name', true );
                $label_path      = get_post_meta( $label_id, '_seur_shipping_order_label_path_name', true );
            }

            if ($label_type == $type) {
                if ($type == 'TERMICA') {
                    $fp .= "\n" . file_get_contents($label_path, true);
                } else {
                    $pdf->addPDF($upload_dir . '/' . $label_file_name);
                }
            }
		}

        if ($type=='TERMICA') {
	        $wp_filesystem->put_contents( $upload_path, $fp, FS_CHMOD_FILE);
        } else {
            $pdf->merge('file', $upload_path);
        }

		set_transient( get_current_user_id() . '_seur_label_bulk_download', $bulk_label_name );
		$redirect_to = add_query_arg( 'bulk_download_seur_label', count( $labels_ids ), $redirect_to );
		return $redirect_to;

	} elseif ( 'update_seur_tracking' === $doaction ) {

		foreach ( $labels_ids as $label_id ) {
			seur_get_tracking_shipment( $label_id );
		}
		set_transient( get_current_user_id() . '_seur_label_bulk_tracking', true );
		$redirect_to = add_query_arg( 'bulk_tracking_seur', count( $labels_ids ), $redirect_to );
		return $redirect_to;

	} elseif ('generate_seur_manifest' === $doaction) {

        $merchants  = array();

        foreach ( $labels_ids as $label_id ) {
            $id_orders[$label_id] = get_post_meta( $label_id, '_seur_shipping_order_id', true);
        }

        foreach($id_orders as $label_id => $id_order)
        {
            $order = wc_get_order( $id_order );

            $order_manifest = array();

            $order_manifest['id'] =  $id_order;
            $order_manifest['reference'] =  $order->get_meta('_seur_shipping_id_number');
            $order_manifest['consig_name'] =  $order->get_shipping_first_name()." ".$order->get_shipping_last_name();
            $order_manifest['consig_address'] =  $order->get_shipping_address_1();
            $order_manifest['consig_postalcode'] =  $order->get_shipping_postcode();
            $order_manifest['consig_phone'] =  $order->get_meta('shipping_mobile_phone');
            $states = WC()->countries->get_states($order->get_shipping_country());
            $order_manifest['state'] = $states[$order->get_shipping_state()]??$order->get_shipping_state();
            $countries = WC()->countries->get_countries();
            $order_manifest['country'] = $countries[$order->get_shipping_country()]??$order->get_shipping_country();

            $ecbs = $order->get_meta('_seur_label_ecbs', true);
            $order_manifest['ecbs'] = $ecbs;
            $order_manifest['producto'] =  get_post_meta( $label_id, '_seur_shipping_product', true );
            $order_manifest['servicio'] =  get_post_meta( $label_id, '_seur_shipping_service', true );
            $order_manifest['bultos'] =  get_post_meta( $label_id, '_seur_shipping_packages', true );
            $order_manifest['peso'] =  get_post_meta( $label_id, '_seur_shipping_weight', true );
            $order_manifest['otros'] = get_post_meta( $label_id, '_seur_shipping_order_customer_comments', true );
            $order_manifest['cashondelivery'] =  0;

            $ccc = get_post_meta( $label_id, '_seur_shipping_ccc', true );
            $merchants[$ccc][] = $order_manifest;

            update_post_meta( $label_id, '_seur_shipping_manifested', 1 );
        }

        foreach ($merchants as $ccc => $orders)
        {
            $manifest['ccc'] = $ccc;
            $manifest['company'] = get_option( 'seur_empresa_field' );
            $manifest['cif']= get_option( 'seur_nif_field' );
            $manifest['franchise'] = get_option( 'seur_franquicia_field' );
            $manifest['street_type'] = get_option( 'seur_viatipo_field' );
            $manifest['street_name'] = get_option( 'seur_vianombre_field' );
            $manifest['street_number'] = get_option( 'seur_vianumero_field' );
            $manifest['state'] = get_option( 'seur_provincia_field' );
            $manifest['postalcode'] = get_option( 'seur_postal_field' );
            $manifest['city'] = get_option( 'seur_poblacion_field' );
            $manifest['date'] = gmdate('d/m/Y');
            $manifest['hour'] = gmdate('H:i');

            $manifest_header = getManifestHeader($manifest);
            $manifest_content = getManifestContent($manifest, $orders);
            ob_end_clean();

            $pdf = new PDFGeneratorCore('P', 'mm', 'A4', true, 'UTF-8', false);
            //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetHeaderMargin(10);
            $pdf->SetPrintHeader(true);
            $pdf->setFont($pdf->font, '', 5, '', false);
            $pdf->setHeaderFont(array($pdf->font, '', 7, '', false));
            $pdf->setFooterFont(array($pdf->font, '', 8, '', false));
            $pdf->SetMargins(15, 30, 15);
            $pdf->createHeader($manifest_header);
            $pdf->AddPage('P', 'A4');
            $pdf->writeHTML($manifest_content, false, false, false, false, 'P');
            $pdf->Output("Manifiesto_".$ccc."_".gmdate('YmdHis').".pdf", 'I');
        }

        set_transient( get_current_user_id() . '_seur_label_bulk_manifest', true );

    }
}
add_filter( 'handle_bulk_actions-edit-seur_labels', 'seur_bulk_actions_handler', 10, 3 );

function getManifestHeader(array $manifest)
{
    return '
    <table>
        <tr>
            <td style="width:32%; text-align:left;">'.
                $manifest["company"].'<br>'.
                $manifest["street_type"] .' '. $manifest["street_name"] .' N '. $manifest["street_number"].'<br>'.
                $manifest["state"] .' '.  $manifest["postalcode"].'<br>'.
                $manifest["cif"].'
            </td>
            <td style="width:32%; text-align:center;">
                <br>
                <br>
                INFORME DE DETALLE<br>
                DE ENVIOS Y BULTOS<br>
                DELEGACION '.$manifest["franchise"].'
            </td>
            <td style="width:32%; text-align:right;">
                FECHA: '.$manifest["date"].'<br>
                HORA: '.$manifest["hour"].'<br>
                REMITENTE 1/1<br>
            </td>
        </tr>
    </table>';
}

function getManifestContent($manifest, $orders)
{
    $total_expedidiciones = 0;
    $total_bultos = 0;
    $total_kgs = 0;
    $total_reembolso = 0;
    $total_seguro = 0;
    $total_valor_rs = 0;

    foreach ($orders as $order) {
        $total_expedidiciones++;
        $total_bultos = $total_bultos + $order['bultos'];
        $total_kgs = $total_kgs + round((float) $order['peso'], 2);
        $total_reembolso = $total_reembolso + $order['cashondelivery'];
    }

    $content = '<table style="border:2px solid #000; padding:2px">
        <tr>
            <td>CCC: '.$manifest['ccc'].'</td>
            <td>CLIENTE: '.$manifest['company'].'</td>
            <td></td>
            <td>DIRECCIÓN: '.$manifest['street_type'].' '.$manifest['street_name']. ' N '. $manifest['street_number'].'</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>POBLACION: '.$manifest['city'].'</td>
            <td>C.P.: '.$manifest['postalcode'].'</td>
            <td>PROVINCIA: '.$manifest['state'].'</td>
            <td>CIF/NIF: '.$manifest['cif'].'</td>
        </tr>
        <tr>
            <td>TOTAL EXP: '.$total_expedidiciones.'</td>
            <td>TOTAL BULTOS: '.$total_bultos.'</td>
            <td>TOTAL KG. : '.$total_kgs.'</td>
            <td>TOTAL REEMBOLSO : '.$total_reembolso.' &euro;</td>
            <td></td>
        </tr>
    </table>
    <br>
    <br>';

    foreach ($orders as $order) {
        $content .= '<table style="padding:2px ">
            <tr>
                <td style="text-decoration:underline">REFERENCIA</td>
                <td style="text-decoration:underline">CONSIGNATARIO</td>
                <td style="text-decoration:underline">PRO</td>
                <td style="text-decoration:underline">SER</td>
                <td style="text-decoration:underline">BUL</td>
                <td style="text-decoration:underline">KGS</td>
                <td style="text-decoration:underline">POR</td>
                <td style="text-decoration:underline">REEMBOLSO</td>
                <td style="text-decoration:underline">SEGURO</td>
                <td style="text-decoration:underline">CDE</td>
            </tr>
        </table>
        <br>
        <table style="padding:2px; border:2px solid #000;">
            <tr>
                <td>'.$order['reference'].'</td>
                <td>'.$order['consig_name'].'</td>
                <td>'.$order['producto'].'</td>
                <td>'.$order['servicio'].'</td>
                <td>'.$order['bultos'].'</td>
                <td>'.$order['peso'].'</td>
                <td>F</td>
                <td>'.$order['cashondelivery'].'</td>
                <td>0.0</td>
                <td>-</td>
            </tr>
            <tr>
                <td></td>
                <td>'.$order['consig_address'].'</td>
                <td></td>
                <td>N 0000,</td>
                <td></td>
                <td>TLF: '.$order['consig_phone'].'</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">C.P.:'.$order['consig_postalcode'].'</td>
                <td>'.strtoupper($order['state']).'</td>
                <td colspan="2">PAIS DESTINO: '.strtoupper($order['country']).'</td>
                <td colspan="2">ATT: -</td>
                <td colspan="2">OBS: '.$order['otros'].'</td>
            </tr>
            <tr>
                <td colspan="10">Datos de consignatario: '.$order['consig_name'].'</td>
            </tr>
            <tr >
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">ECB</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">REF. BULTO</td>
                <td colspan="2" style="border-top:1px solid #000;border-bottom:1px solid #000;">OBSERVACIONES</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">VALOR RS</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">PESO</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">ALTO</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">ANCHO</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">LARGO</td>
                <td style="border-top:1px solid #000;border-bottom:1px solid #000;">VOLUMEN</td>
            </tr>';
            $ref = 1;
            foreach ($order['ecbs'] as $ecb) {
                $content .= '<tr>
                    <td>'.$ecb.'</td>
                    <td>'.str_pad($order['id'], 3, '0', STR_PAD_LEFT).str_pad($ref, 3, '0', STR_PAD_LEFT).'</td>
                    <td colspan="2">'.$order['otros'].'</td>
                    <td>0.0</td>
                    <td>'.$order['peso'].'</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>';
                $ref++;
            }
        $content .= '</table>
        <br>
        <br>';
    }

    $content .= '<br><br>
    <table style="padding:2px; border:1px solid #000;">
        <tr>
            <td colspan="2" style="border-bottom:1px solid #000;border-right:1px solid #000;">INFORME DE DETALLE DE ENVIOS Y BULTOS de '.gmdate('d/m/Y').'</td>
            <td style="border-bottom:1px solid #000;">TOTAL GENERAL </td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom:1px solid #000;border-right:1px solid #000;">CONFORME</td>
            <td style="border-bottom:1px solid #000;">TOTAL EXPEDICIONES <span style="float:right">'.$total_expedidiciones.'</span></td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #000;border-right:1px solid #000;">CLIENTE</td>
            <td style="border-bottom:1px solid #000;border-right:1px solid #000;">CONDUCTOR</td>
            <td style="border-bottom:1px solid #000;">TOTAL BULTOS <span style="float:right">'.$total_bultos.'</span></td>
        </tr>
        <tr>
            <td rowspan="5" style="border-bottom:1px solid #000;border-right:1px solid #000;"></td>
            <td rowspan="5" style="border-bottom:1px solid #000;border-right:1px solid #000;"></td>
            <td style="border-bottom:1px solid #000;">TOTAL KGS: <span style="float:right">'.$total_kgs.'</span></td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #000;">TOTAL REEMBOLSO: <span style="float:right">'.$total_reembolso.' &euro;</span></td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #000;">TOTAL SEGURO: <span style="float:right">'.$total_seguro.' &euro;</span></td>
        </tr>
        <tr>
            <td>TOTAL VALOR RS: <span style="float:right">'.$total_valor_rs.' &euro;</span></td>
        </tr>
    </table>
    <br>
    <span style="font-size: 8px">SEUR no reconoce el valor y contenido de la mercancia transportada, que se entrega cerrada y embalada, la responsabilidad de SEUR es la fijada en su carta de porte.</span>
    <br>
    <br>
    <span style="font-size: 8px">OBSERVACIONES</span>
    <br>
    <hr>';
    return $content;
}

/**
 * Seur Bulk Actions Success.
 */
function seur_bulk_actions_success()
{
	$screen           = get_current_screen();
	$file_name        = get_transient( get_current_user_id() . '_seur_label_bulk_download' );
	if ( $file_name && 'seur_labels' === $screen->post_type ) {
        $url_to_dir       = seur_upload_url( 'labels' ); ?>
        <div class="notice notice-success is-dismissible">
            <p>
		        <?php echo esc_html__( 'Bulk Print ready, please press Download Bulk Labels button for download the file.', 'seur' ) . ' <a href="' . esc_url( $url_to_dir . '/' . esc_html( $file_name ) ) . '" class="button" download>' . esc_html__( ' Download Bulk Labels ', 'seur' ) . '</a>'; ?>
            </p>
        </div>
		<?php
	}
	delete_transient( get_current_user_id() . '_seur_label_bulk_download' );
}
add_action( 'admin_notices', 'seur_bulk_actions_success' );

/**
 * Seur Bulk actions success tracking.
 */
function seur_bulk_actions_success_tracking()
{
	$screen    = get_current_screen();
	$file_name = get_transient( get_current_user_id() . '_seur_label_bulk_tracking' );
	if ( $file_name && 'seur_labels' === $screen->post_type ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'All tracking updated', 'seur' ); ?></p>
        </div>
		<?php
	}
	delete_transient( get_current_user_id() . '_seur_label_bulk_tracking' );
}
add_action( 'admin_notices', 'seur_bulk_actions_success_tracking' );
