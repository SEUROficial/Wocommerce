<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register Custom Post Type
function seur_cpt_labels() {

    $labels = array(
        'name'                  => _x( 'Labels', 'Post Type General Name',  SEUR_TEXTDOMAIN ),
        'singular_name'         => _x( 'Label', 'Post Type Singular Name',  SEUR_TEXTDOMAIN ),
        'menu_name'             => __( 'Labels',                            SEUR_TEXTDOMAIN ),
        'name_admin_bar'        => __( 'Labels',                            SEUR_TEXTDOMAIN ),
        'archives'              => __( 'Labels',                            SEUR_TEXTDOMAIN ),
        'attributes'            => __( 'Labels Atributte',                  SEUR_TEXTDOMAIN ),
        'parent_item_colon'     => __( 'Parent Item:',                      SEUR_TEXTDOMAIN ),
        'all_items'             => __( 'All Labels',                        SEUR_TEXTDOMAIN ),
        'add_new_item'          => __( 'Add New Label',                     SEUR_TEXTDOMAIN ),
        'add_new'               => __( 'Add New',                           SEUR_TEXTDOMAIN ),
        'new_item'              => __( 'New Label',                         SEUR_TEXTDOMAIN ),
        'edit_item'             => __( 'Edit Labels',                       SEUR_TEXTDOMAIN ),
        'update_item'           => __( 'Update Label',                      SEUR_TEXTDOMAIN ),
        'view_item'             => __( 'View Label',                        SEUR_TEXTDOMAIN ),
        'view_items'            => __( 'View Labels',                       SEUR_TEXTDOMAIN ),
        'search_items'          => __( 'Search Label',                      SEUR_TEXTDOMAIN ),
        'not_found'             => __( 'Not found',                         SEUR_TEXTDOMAIN ),
        'not_found_in_trash'    => __( 'Not found in Trash',                SEUR_TEXTDOMAIN ),
        'featured_image'        => __( 'Featured Image',                    SEUR_TEXTDOMAIN ),
        'set_featured_image'    => __( 'Set featured image',                SEUR_TEXTDOMAIN ),
        'remove_featured_image' => __( 'Remove featured image',             SEUR_TEXTDOMAIN ),
        'use_featured_image'    => __( 'Use as featured image',             SEUR_TEXTDOMAIN ),
        'insert_into_item'      => __( 'Insert into Label',                 SEUR_TEXTDOMAIN ),
        'uploaded_to_this_item' => __( 'Uploaded to this Label',            SEUR_TEXTDOMAIN ),
        'items_list'            => __( 'Labels list',                       SEUR_TEXTDOMAIN ),
        'items_list_navigation' => __( 'Labels list navigation',            SEUR_TEXTDOMAIN ),
        'filter_items_list'     => __( 'Filter Labels list',                SEUR_TEXTDOMAIN ),
    );
    $args = array(
        'label'                 => __( 'Label',         SEUR_TEXTDOMAIN ),
        'description'           => __( 'Seur Labels',   SEUR_TEXTDOMAIN ),
        'labels'                => $labels,
        'supports'              => false,
        'taxonomies'            => array( 'labels-product' ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => false,
        'menu_position'         => 10,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'rewrite'               => false,
        'capability_type'       => 'post',
        'capabilities' => array(
                        'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                        ),
        'map_meta_cap' => true,
    );
    register_post_type( 'seur_labels', $args );

}
add_action( 'init', 'seur_cpt_labels', 0 );

// Adding columns to Labels List

add_filter( 'manage_seur_labels_posts_columns', 'seur_set_custom_label_columns' );
function seur_set_custom_label_columns( $columns ) {

    unset( $columns['title'] );
    unset( $columns['date'] );
    unset( $columns['taxonomy-labels-product']);
    $columns['title']                   = __( 'Label ID',            SEUR_TEXTDOMAIN );
    $columns['order_id']                = __( 'Order ID',            SEUR_TEXTDOMAIN );
    $columns['product']                 = __( 'Product',             SEUR_TEXTDOMAIN );
    $columns['customer_name']           = __( 'Customer Name',       SEUR_TEXTDOMAIN );
    $columns['customer_comments']       = __( 'Customer Comments',   SEUR_TEXTDOMAIN );
    $columns['weight']                  = __( 'Weight',              SEUR_TEXTDOMAIN );
    $columns['taxonomy-labels-product'] = __( 'Service/Product',     SEUR_TEXTDOMAIN );
    $columns['print']                   = __( 'Print/Download',      SEUR_TEXTDOMAIN );
    $columns['date']                    = __( 'Label Date',          SEUR_TEXTDOMAIN );

    return $columns;
}

add_action( 'manage_seur_labels_posts_custom_column' , 'seur_custom_label_column_data', 10, 2 );

function seur_custom_label_column_data( $column, $post_id ) {
    global $woocommerce;

    $seur_shipping_method = get_post_meta( $post_id, '_seur_shipping_method',                   true );
    $weight               = get_post_meta( $post_id, '_seur_shipping_weight',                   true );
    $num_packages         = get_post_meta( $post_id, '_seur_shipping_packages',                 true );
    $order_id             = get_post_meta( $post_id, '_seur_shipping_order_id',                 true );
    $customer_name        = get_post_meta( $post_id, '_seur_label_customer_name',               true );
    $order_comments       = get_post_meta( $post_id, '_seur_shipping_order_customer_comments',  true );
    $label_file_name      = get_post_meta( $post_id, '_seur_shipping_order_label_file_name',    true );
    $label_path           = get_post_meta( $post_id, '_seur_shipping_order_label_path_name',    true );
    $label_url            = get_post_meta( $post_id, '_seur_shipping_order_label_url_name',     true );
    $url_to_file_down     = get_option('seur_download_file_url');
    $file_downlo_pass     = get_option('seur_pass_for_download');
    $label_path           = str_replace("\\", "/", $label_path );
    $file_type            = get_post_meta( $post_id, '_seur_label_type',                        true );

    $order        = new WC_Order( $order_id );
    $product_list = '';
    $order_item   = $order->get_items();
    foreach( $order_item as $product ) {
                $product_name[] = '<li>' . $product['name']." x ".$product['qty'] . '</li>';

            }
    $product_list = implode( '', $product_name );


    switch ( $column ) {

       case 'order_id' :
            $link = admin_url( 'post.php?post=' . $order_id . '&action=edit' );
            echo '<a href="' . $link . '" target="_blank">' . $order_id . '</a>';
            break;
        case 'product' :
            echo '<ul>';
            echo $product_list;
            echo '</ul>';
            break;
        case 'customer_name' :
            echo $customer_name;
            break;
        case 'customer_comments' :
            echo $order_comments;
            break;

        case 'weight' :
            echo $weight;
            break;

        case 'print' :
            //echo '<a href="' . $label_url . '" onClick="window.print();return false">​​​​​​​​​​​​​​​​​print pdf</a>';

            echo '<a href="' . $url_to_file_down . '?label=' . $label_path . '&label_name=' . $label_file_name . '&pass=' . $file_downlo_pass . '&file_type=' . $file_type . '" class="button" target="_blank">' . __( ' Open ', SEUR_TEXTDOMAIN ) . '</a>';
            break;
    }
}

// Register Custom Taxonomy
function seur_add_label_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Products', 'Taxonomy General Name', SEUR_TEXTDOMAIN ),
        'singular_name'              => _x( 'Product', 'Taxonomy Singular Name', SEUR_TEXTDOMAIN ),
        'menu_name'                  => __( 'Product',                           SEUR_TEXTDOMAIN ),
        'all_items'                  => __( 'All products',                      SEUR_TEXTDOMAIN ),
        'parent_item'                => __( 'Parent Item',                       SEUR_TEXTDOMAIN ),
        'parent_item_colon'          => __( 'Parent Item:',                      SEUR_TEXTDOMAIN ),
        'new_item_name'              => __( 'New Product',                       SEUR_TEXTDOMAIN ),
        'add_new_item'               => __( 'Add new product',                   SEUR_TEXTDOMAIN ),
        'edit_item'                  => __( 'Edit Product',                      SEUR_TEXTDOMAIN ),
        'update_item'                => __( 'Update Product',                    SEUR_TEXTDOMAIN ),
        'view_item'                  => __( 'View Product',                      SEUR_TEXTDOMAIN ),
        'separate_items_with_commas' => __( 'Separate Products with commas',     SEUR_TEXTDOMAIN ),
        'add_or_remove_items'        => __( 'Add or remove Products',            SEUR_TEXTDOMAIN ),
        'choose_from_most_used'      => __( 'Choose from the most used',         SEUR_TEXTDOMAIN ),
        'popular_items'              => __( 'Popular Products',                  SEUR_TEXTDOMAIN ),
        'search_items'               => __( 'Search Products',                   SEUR_TEXTDOMAIN ),
        'not_found'                  => __( 'Not Found',                         SEUR_TEXTDOMAIN ),
        'no_terms'                   => __( 'No Products',                       SEUR_TEXTDOMAIN ),
        'items_list'                 => __( 'Product list',                      SEUR_TEXTDOMAIN ),
        'items_list_navigation'      => __( 'Products list navigation',          SEUR_TEXTDOMAIN ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'rewrite'                    => false,
    );
    register_taxonomy( 'labels-product', array( 'seur_labels' ), $args );

}
add_action( 'init', 'seur_add_label_taxonomy', 0 );

/***************************************************/
/************* Metabox Labels SEUR *****************/
/***************************************************/

/**
 * Register meta box(es).
 */
function seur_label_register_meta_box() {
    add_meta_box( 'seurmetaboxlabel', __( 'Data Label', SEUR_TEXTDOMAIN ), 'seur_metabox_label_callback', 'seur_labels', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'seur_label_register_meta_box' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_label_callback( $post ) {

    $weight        			 = get_post_meta( $post->ID, '_seur_shipping_weight',    true );
    $order_id      			 = get_post_meta( $post->ID, '_seur_shipping_order_id',  true );
    $product       			 = get_post_meta( $post->ID, '_seur_product',             true );
    $customer_name 			 = get_post_meta( $post->ID, '_seur_label_customer_name', true );
    $order_data    			 = seur_get_order_data( $order_id );
    $mobile_shipping         = get_post_meta( $order_id, '_shipping_mobile_phone', true );
    $mobile_billing          = get_post_meta( $order_id, '_billing_mobile_phone', true );
    $seur_shipping_method    = seur_get_shipping_method( $order_id );

    $customer_country        = $order_data[0]['country'];
    $customercity            = seur_clean_data( $order_data[0]['city'] );
    $customerpostcode        = $order_data[0]['postcode'];
    $customer_weight         = $order_data[0]['weight'];

    if ( ! $customer_weight ) $customer_weight = $weight;

    $customer_address_1      = seur_clean_data( $order_data[0]['address_1'] );
    $customer_address_2      = seur_clean_data( $order_data[0]['address_2'] );
    $customer_email          = seur_clean_data( $order_data[0]['email'] );
    $customer_phone          = $order_data[0]['phone'];
    $customer_order_notes    = seur_clean_data( $order_data[0]['order_notes'] );
    $customer_order_total    = $order_data[0]['order_total'];

    $billing_last_name       = get_post_meta( $order_id, '_billing_last_name',     true );
    $billing_name            = get_post_meta( $order_id, '_billing_first_name',    true );
    $billing_email 			 = get_post_meta( $order_id, '_billing_email',    true );
    $billing_country 	     = get_post_meta( $order_id, '_billing_country',    true );
    $billing_addr_1 		 = get_post_meta( $order_id, '_billing_address_1',    true );
    $billing_addr_2 		 = get_post_meta( $order_id, '_billing_address_2',    true );
    $billing_postcode 		 = get_post_meta( $order_id, '_billing_postcode',    true );
    $billing_city			 = get_post_meta( $order_id, '_billing_city',    true );
    $billing_state			 = get_post_meta( $order_id, '_billing_state',    true );
    $billing_mobile_phone  	 = get_post_meta( $order_id, '_billing_mobile_phone',    true );
    $billing_phone			 = get_post_meta( $order_id, '_billing_phone',    true );

    if ( $customer_order_notes ) {
	    $customer_order_notes = $customer_order_notes;
    } else {
	    $customer_order_notes = __( "There aren't comments for this order", SEUR_TEXTDOMAIN );
    }

    ?>
    <div id="seur_content_metabox">

        <div id="order_data" class="panel">

			<h2><?php echo __('Details for Label ID #', SEUR_TEXTDOMAIN ) . $post->ID; ?> </h2>

			<div class="order_data_column_container">

				<div class="order_data_column">
					<h3>
						<?php _e('Billing Details', SEUR_TEXTDOMAIN ); ?>
					</h3>
					<div class="address">
						<p>
							<strong><?php _e('Adress:  ', SEUR_TEXTDOMAIN ); ?></strong> <?php echo $billing_name . ' ' . $billing_last_name; ?><br>
							<?php echo $billing_name . ' ' . $billing_last_name; ?><br>
							<?php echo $billing_addr_1; ?><br>
							<?php echo $billing_addr_2 . '<br>'; ?>
							<?php echo $billing_postcode . ' ' . $billing_city . '<br>'; ?>
							<?php echo $billing_state . '<br>'; ?>
							<?php echo $billing_country; ?>
						</p>
					</div>
					<p>
						<strong><?php _e('Email: ', SEUR_TEXTDOMAIN ); ?></strong><a href="mailto:<?php echo $billing_email; ?>"><?php echo $billing_email; ?></a>
					</p>
					<p>
						<strong><?php _e('Phone: ', SEUR_TEXTDOMAIN ); ?></strong> <?php echo $billing_phone; ?>
					</p>
					<p>
						<strong><?php _e('Billing Mobile Phone: ', SEUR_TEXTDOMAIN ); ?></strong><?php echo $billing_mobile_phone; ?>
					</p>
				</div>

				<div class="order_data_column">

					<h3>
						<?php _e('Shipping Details', SEUR_TEXTDOMAIN ); ?>
					</h3>
					<div class="address">
						<p>
							<strong><?php _e('Adress:  ', SEUR_TEXTDOMAIN ); ?></strong> <?php echo $customer_name; ?><br>
							<?php echo $customer_name; ?><br>
							<?php echo $customer_address_1; ?><br>
							<?php echo $customer_address_2 . '<br>'; ?>
							<?php echo $customerpostcode . ' ' . $customercity . '<br>'; ?>
							<?php echo $customer_country; ?>
						</p>
					</div>
					<p>
						<strong><?php _e('Shipping Mobile Phone: ', SEUR_TEXTDOMAIN ); ?></strong><?php echo $mobile_shipping; ?>
					</p>
					<p>
						<strong><?php _e('Shipping Method: ', SEUR_TEXTDOMAIN ); ?></strong> <?php echo $seur_shipping_method; ?>
					</p>

				</div>

				<p>
					<strong><?php _e('Customer notes about Order: ', SEUR_TEXTDOMAIN ); ?></strong><br />
					<?php echo $customer_order_notes; ?>
				</p>

			</div>

			<div class="clear"></div>

		</div>

	</div>

<?php }

//add_filter('display_post_states', 'wpsites_custom_post_states');

function wpsites_custom_post_states($states) {
    global $post;
    if( ( 'seur_label' == get_post_type( $post->ID ) ) ){
        $states[] = '__return_false';
    }
}

add_action('admin_footer-edit.php', 'seur_custom_bulk_admin_footer_labels');

function seur_custom_bulk_admin_footer_labels() {

  global $post_type;


  $advanced_data  = seur_get_advanced_settings();
  $label_type     = $advanced_data[0]['tipo_etiqueta'];

  if( $post_type == 'seur_labels' && $label_type != 'PDF' ) {
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function() {
        jQuery('<option>').val('download-seur-label').text('<?php _e('Download SEUR Label', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action']");
        jQuery('<option>').val('download-seur-label').text('<?php _e('Download SEUR Label', SEUR_TEXTDOMAIN )?>').appendTo("select[name='action2']");
      });
    </script>
    <?php
  }
}

function seur_labels_bulk_action() {
    $added = '';

  // 1. get the action
  $wp_list_table = _get_list_table('WP_Posts_List_Table');
  $action = $wp_list_table->current_action();

  if ( ! isset($_REQUEST['post']) ) return;

  //check_admin_referer('bulk-posts');

  $post_ids = array_map( 'absint', (array) $_REQUEST['post'] );

  switch( $action ) {
    // 3. Perform the action
    case 'download-seur-label':

      $added = 0;

      foreach( $post_ids as $post_id ) {

            $has_label  = get_post_meta( $post_id, '_seur_shipping_order_label_downloaded', true );
            $label_type = get_post_meta( $post_id, '_seur_label_type', true );

            if ( $has_label == 'yes' && $label_type == 'termica' ) {

              // TODO Action

            }

                $added++;
      }

      // build the redirect url
      $sendback = add_query_arg( array( 'post_type' => 'seur_labels', $report_action => true, 'changed' => $added, 'ids' => join( ',', $post_ids ) ), '' );

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
// Deactivated in 1.0, activate in future version // add_action( 'load-edit.php', 'seur_labels_bulk_action' );
