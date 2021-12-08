<?php
/**
 * CPT Labels
 *
 * @package SEUR.
 */

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

/**
 * SEUR get order tracking.
 *
 * @param int $order_id get order tracking by Order ID.
 */
function seur_get_order_tracking( $order_id ) {

	$order_tracking = get_post_meta( $order_id, '_seur_shipping_tracking_state', true );

	if ( ! empty( $order_tracking ) ) {
		$order_tracking_unse = maybe_unserialize( $order_tracking );

		foreach ( $order_tracking_unse as $state => $value ) {

			$latest_state = $value['descripcion_cliente'];
			$date         = $value['fecha_situacion'];
		}
		echo esc_html( $date );
		echo '<br />';
		echo esc_html( $latest_state );
	} else {
		esc_html_e( 'Waiting Collection', 'seur' );
	}
}
add_filter( 'manage_seur_labels_posts_columns', 'seur_set_custom_label_columns' );

/**
 * SEUR get order tracking.
 *
 * @param string $column Colum name.
 * @param int    $post_id Post ID.
 */
function seur_custom_label_column_data( $column, $post_id ) {
	global $woocommerce;

	$seur_shipping_method = get_post_meta( $post_id, '_seur_shipping_method', true );
	$weight               = get_post_meta( $post_id, '_seur_shipping_weight', true );
	$num_packages         = get_post_meta( $post_id, '_seur_shipping_packages', true );
	$order_id             = get_post_meta( $post_id, '_seur_shipping_order_id', true );
	$customer_name        = get_post_meta( $post_id, '_seur_label_customer_name', true );
	$order_comments       = get_post_meta( $post_id, '_seur_shipping_order_customer_comments', true );
	$label_file_name      = get_post_meta( $post_id, '_seur_shipping_order_label_file_name', true );
	$label_path           = get_post_meta( $post_id, '_seur_shipping_order_label_path_name', true );
	$label_url            = get_post_meta( $post_id, '_seur_shipping_order_label_url_name', true );
	$url_to_file_down     = get_site_option( 'seur_download_file_url' );
	$file_downlo_pass     = get_site_option( 'seur_pass_for_download' );
	$label_path           = str_replace( '\\', '/', $label_path );
	$file_type            = get_post_meta( $post_id, '_seur_label_type', true );
	$url_upload_dir       = get_site_option( 'seur_uploads_url_labels' );

	if ( ! empty( $order_tracking ) ) {
		$order_tracking = $order_tracking;
	} else {
		$order_tracking = __( 'Waiting Shipping', 'seur' );
	}

	$order        = new WC_Order( $order_id );
	$product_list = '';
	$order_item   = $order->get_items();
	foreach ( $order_item as $product ) {
				$product_name[] = '<li>' . $product['name'] . ' x ' . $product['qty'] . '</li>';

	}
	$product_list = implode( '', $product_name );

	switch ( $column ) {

		case 'order_id':
			$link = admin_url( 'post.php?post=' . $order_id . '&action=edit' );
			echo '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html( $order_id ) . '</a>';
			break;
		case 'product':
			echo '<ul>';
			echo wp_kses( $product_list, 'data' );
			echo '</ul>';
			break;
		case 'customer_name':
			echo esc_html( $customer_name );
			break;
		case 'customer_comments':
			echo esc_html( $order_comments );
			break;

		case 'seur-tracking':
			echo esc_html( seur_get_order_tracking( $post_id ) );
			break;

		case 'weight':
			echo esc_html( $weight );
			break;

		case 'print':
			// echo '<a href="' . $label_url . '" onClick="window.print();return false">​​​​​​​​​​​​​​​​​print pdf</a>';.
			echo '<a href="' . $url_upload_dir . '/' . $label_file_name . '" class="button" download>' . esc_html__( ' Open ', 'seur' ) . '</a>';
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
function seur_metabox_label_callback( $post ) {

	$weight               = get_post_meta( $post->ID, '_seur_shipping_weight', true );
	$order_id             = get_post_meta( $post->ID, '_seur_shipping_order_id', true );
	$product              = get_post_meta( $post->ID, '_seur_product', true );
	$customer_name        = get_post_meta( $post->ID, '_seur_label_customer_name', true );
	$order_data           = seur_get_order_data( $order_id );
	$mobile_shipping      = get_post_meta( $order_id, '_shipping_mobile_phone', true );
	$mobile_billing       = get_post_meta( $order_id, '_billing_mobile_phone', true );
	$seur_shipping_method = seur_get_shipping_method( $order_id );

	$customer_country = $order_data[0]['country'];
	$customercity     = seur_clean_data( $order_data[0]['city'] );
	$customerpostcode = $order_data[0]['postcode'];
	$customer_weight  = $order_data[0]['weight'];

	if ( ! $customer_weight ) {
		$customer_weight = $weight;
	}

	$customer_address_1   = seur_clean_data( $order_data[0]['address_1'] );
	$customer_address_2   = seur_clean_data( $order_data[0]['address_2'] );
	$customer_email       = seur_clean_data( $order_data[0]['email'] );
	$customer_phone       = $order_data[0]['phone'];
	$customer_order_notes = seur_clean_data( $order_data[0]['order_notes'] );
	$customer_order_total = $order_data[0]['order_total'];

	$billing_last_name    = get_post_meta( $order_id, '_billing_last_name', true );
	$billing_name         = get_post_meta( $order_id, '_billing_first_name', true );
	$billing_email        = get_post_meta( $order_id, '_billing_email', true );
	$billing_country      = get_post_meta( $order_id, '_billing_country', true );
	$billing_addr_1       = get_post_meta( $order_id, '_billing_address_1', true );
	$billing_addr_2       = get_post_meta( $order_id, '_billing_address_2', true );
	$billing_postcode     = get_post_meta( $order_id, '_billing_postcode', true );
	$billing_city         = get_post_meta( $order_id, '_billing_city', true );
	$billing_state        = get_post_meta( $order_id, '_billing_state', true );
	$billing_mobile_phone = get_post_meta( $order_id, '_billing_mobile_phone', true );
	$billing_phone        = get_post_meta( $order_id, '_billing_phone', true );

	if ( $customer_order_notes ) {
		$customer_order_notes = $customer_order_notes;
	} else {
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
							<?php echo esc_html( $customerpostcode ) . ' ' . esc_html( $customercity ) . '<br>'; ?>
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

// add_filter( 'display_post_states', 'seur_custom_post_states' );

/**
 * SEUR Custom Post States
 *
 * @param string $states Post Status.
 */
function seur_custom_post_states( $states ) {
	global $post;
	if ( ( 'seur_label' === get_post_type( $post->ID ) ) ) {
		$states[] = '__return_false';
	}
}

/**
 * Seur Bulk Actions Handler
 *
 * @param array $bulk_actions PBulk actions.
 */
function seur_bulk_actions_labels_screen( $bulk_actions ) {

	$bulk_actions['download_seur_label']  = __( 'Download  SEUR Labels', 'download_seur_label' );
	$bulk_actions['update_seur_tracking'] = __( 'Update SEUR Tracking', 'update_seur_tracking' );
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
function seur_bulk_actions_handler( $redirect_to, $doaction, $post_ids ) {
	if ( 'download_seur_label' !== $doaction && 'update_seur_tracking' !== $doaction ) {
		return $redirect_to;
	}
	if ( 'download_seur_label' === $doaction ) {

		$date = date( 'd-m-Y-H-i-s' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

		$bulk_label_name = 'label_bulk_' . $date . '.txt';

		foreach ( $post_ids as $post_id ) {

			$label_type = get_post_meta( $post_id, '_seur_label_type', true );

			if ( 'termica' === $label_type ) {

				$label_file_name = get_post_meta( $post_id, '_seur_shipping_order_label_file_name', true );
				$label_path      = get_post_meta( $post_id, '_seur_shipping_order_label_path_name', true );
				$upload_dir      = seur_upload_dir( 'labels' );
				$upload_path     = $upload_dir . '/' . $bulk_label_name;
				$label_text      = "\n" . file_get_contents( $label_path, true );
				$bulk_file_exist = fopen( $upload_path, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen
				if ( $bulk_file_exist ) {
					file_put_contents( $upload_path, $label_text, FILE_APPEND | LOCK_EX ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
				} else {
					file_put_contents( $upload_path, $label_text ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
				}
			}
		}
		set_transient( get_current_user_id() . '_seur_label_bulk_download', $bulk_label_name );
		$redirect_to = add_query_arg( 'bulk_download_seur_label', count( $post_ids ), $redirect_to );
		return $redirect_to;

	} elseif ( 'update_seur_tracking' === $doaction ) {

		foreach ( $post_ids as $post_id ) {

			$tracking_number = get_post_meta( $post_id, '_seur_shipping_id_number', true );

			seur_get_tracking_shipment( $post_id, $tracking_number );

		}
		set_transient( get_current_user_id() . '_seur_label_bulk_tracking', true );
		$redirect_to = add_query_arg( 'bulk_tracking_seur', count( $post_ids ), $redirect_to );
		return $redirect_to;
	}
}
add_filter( 'handle_bulk_actions-edit-seur_labels', 'seur_bulk_actions_handler', 10, 3 );

/**
 * Seur Bulk Actions Success.
 */
function seur_bulk_actions_success() {

	$screen           = get_current_screen();
	$file_name        = get_transient( get_current_user_id() . '_seur_label_bulk_download' );
	$file_downlo_pass = get_site_option( 'seur_pass_for_download' );
	$url_to_dir       = seur_upload_url( 'labels' );
	$upload_dir       = seur_upload_dir( 'labels' );
	$url_to_txt       = get_site_option( 'seur_download_file_url' );
	$path_to_txt      = $upload_dir . '/' . $file_name;
	$label_path_fix   = str_replace( '\\', '/', $path_to_txt );

	if ( $file_name && 'seur_labels' === $screen->post_type ) {
		?>
	<div class="notice notice-success is-dismissible">
		<p><?php echo esc_html__( 'Bulk Print ready, please press Download Bulk Labels button for download the txt file. ' ) . '<a href="' . esc_url( $url_to_txt ) . '?label=' . esc_html( $label_path_fix ) . '&label_name=' . esc_html( $file_name ) . '&pass=' . esc_html( $file_downlo_pass ) . '&file_type=termica" class="button" target="_blank">' . esc_html__( ' Download Bulk Labels ', 'seur' ) . '</a>'; ?></p>
	</div>
		<?php
	}
	delete_transient( get_current_user_id() . '_seur_label_bulk_download' );
}
add_action( 'admin_notices', 'seur_bulk_actions_success' );

/**
 * Seur Bulk actions success tracking.
 */
function seur_bulk_actions_success_tracking() {

	$screen    = get_current_screen();
	$file_name = get_transient( get_current_user_id() . '_seur_label_bulk_tracking' );
	if ( $file_name && 'seur_labels' === $screen->post_type ) {
		?>
	<div class="notice notice-success is-dismissible">
		<p><?php esc_html_e( 'All tracking updated', 'seur' ); ?></p>
	</div>
		<?php
	}
	delete_transient( get_current_user_id() . '_seur_label_bulk_tracking' );
}
add_action( 'admin_notices', 'seur_bulk_actions_success_tracking' );
