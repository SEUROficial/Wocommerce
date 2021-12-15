<?php
/**
 * SEUR Metabox
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

	$weight           = '';
	$has_label        = '';
	$labelid2         = '';
	$has_label        = get_post_meta( $post->ID, '_seur_shipping_order_label_downloaded', true );
	$labelid2         = get_post_meta( $post->ID, '_seur_shipping_label_id', true );
	$url_to_file_down = get_site_option( 'seur_download_file_url' );
	$label_path       = get_post_meta( $labelid2, '_seur_shipping_order_label_path_name', true );
	$label_path       = str_replace( '\\', '/', $label_path );
	$label_file_name  = get_post_meta( $labelid2, '_seur_shipping_order_label_file_name', true );
	$file_downlo_pass = get_site_option( 'seur_pass_for_download' );
	$file_type        = get_post_meta( $labelid2, '_seur_label_type', true );
	$url_upload_dir   = get_site_option( 'seur_uploads_url_labels' );

	?> <div id="seur_content_metabox"> 
	<?php

	if ( ! $has_label ) {
		$url                 = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_get_labels_from_order' ), 'admin.php' ) ) );
		$arrayurl            = array(
			'order_id'   => $post->ID,
			'?TB_iframe' => 'true',
			'width'      => '400',
			'height'     => '300',
		);
		$final_get_label_url = esc_url( add_query_arg( $arrayurl, $url ) );
		add_thickbox();
		?>
		<img src="<?php echo esc_url( SEUR_PLUGIN_URL ); ?>assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />
		<a class='thickbox button' title='<?php esc_html_e( 'Get SEUR Label', 'seur' ); ?>' alt='<?php esc_html_e( 'Get SEUR Label', 'seur' ); ?>' href='<?php echo esc_html( $final_get_label_url ); ?>'><?php esc_html_e( 'Get SEUR Label', 'seur' ); ?></a>
		<?php
	} else {
		?>
		<img src="<?php echo esc_url( SEUR_PLUGIN_URL ); ?>assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />
		<?php
		echo '<a href="' . esc_url( $url_upload_dir ) . '/' . esc_html( $label_file_name ) . '" class="button" download>' . esc_html__( ' See SEUR Label ', 'seur' ) . '</a>';

	}
	?>
</div>
	<?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID.
 */
function seur_save_meta_box( $post_id ) {

	$numpackages = '';
	$weight      = '';
	$has_label   = '';
	$label_id    = '';

	if ( ! isset( $_POST['seur-weight'] ) || ! isset( $_POST['seur-number-packages'] ) ) {
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	if ( ! isset( $_POST['seur_get_label_metabox_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_get_label_metabox_nonce_field'] ) ), 'seur_get_label_metabox_action' ) ) {
		return $post_id;
	}
	$numpackages = esc_html( sanitize_text_field( wp_unslash( $_POST['seur-number-packages'] ) ) );
	$weight      = esc_html( sanitize_text_field( wp_unslash( $_POST['seur-weight'] ) ) );
	$new_status  = 'seur-shipment';
	$has_label   = get_post_meta( $post_id, '_seur_shipping_order_label_downloaded', true );

	if ( 'yes' !== $has_label ) {

		$label = seur_get_label( $post_id, $numpackages, $weight );

		$label_result  = $label[0]['result'];
		$labelid2      = $label[0]['labelID'];
		$label_message = $label[0]['message'];

		if ( $label_result ) {

			$order = wc_get_order( $post_id );
			$order->update_status( $new_status, __( 'Label have been created:', 'seur' ), true );
			add_post_meta( $post_id, '_seur_shipping_order_label_downloaded', 'yes', true );
			add_post_meta( $post_id, '_seur_shipping_label_id', $label_id, true );
			$order->add_order_note( 'The Label for Order #' . $post_id . ' have been downloaded', 0, true );

		} else {
			echo 'error.';
		}
	}
}
add_action( 'save_post_shop_order', 'seur_save_meta_box', 999 );

/**
 * SEUR metabox save buttom
 */
function seur_metabox_save_buttom() {
	global $post_type;

	if ( 'shop_order' === $post_type ) {
		wp_enqueue_script( 'seurshoporderssavemeta', SEUR_PLUGIN_URL . 'assets/js/seur-shop-orders-cpt.js', array(), SEUR_OFFICIAL_VERSION, true );
	}

}
add_action( 'admin_print_scripts-post.php', 'seur_metabox_save_buttom', 11 );
