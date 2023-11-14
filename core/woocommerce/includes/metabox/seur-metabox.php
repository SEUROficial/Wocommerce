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

    if (!seur()->is_seur_order($post->ID)) {
        remove_meta_box( 'seurmetabox','shop_order', 'side' );
        return '';
    }
	$has_label      = seur()->has_label($post->ID);
    ?>

    <div id="seur_content_metabox">
	<?php if ( !$has_label ) {
		$url             = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_get_labels_from_order' ), 'admin.php' ) ) );
		$arrayiframe     = array(
            '?TB_iframe' => 'true',
            'width'      => '400',
            'height'     => '300',
        );
        add_thickbox();
        echo '<img src="'. esc_url( SEUR_PLUGIN_URL ) .'assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />';
        for ($k=0;$k<=1;$k++) {
            $arrayurl = array(
                'order_id' => $post->ID,
                'change' => $k
            );
            $arrayurl = array_merge( $arrayurl, $arrayiframe );
            $final_get_label_url = esc_url( add_query_arg( $arrayurl, $url ) );
            $text = 'Get SEUR'.($k==1?' CHANGE':'').' Label'.($k==1?'s':'');
            ?>
            <a class='thickbox button btn-seur-label' title='<?php esc_html_e( $text, 'seur' ); ?>'
               alt='<?php esc_html_e( $text, 'seur' ); ?>'
               href='<?php echo esc_html( $final_get_label_url ); ?>'>
                <?php esc_html_e( $text, 'seur' ); ?>
            </a>
            <?php
        }
    } else { ?>
		<img src="<?php echo esc_url( SEUR_PLUGIN_URL ); ?>assets/img/icon-96x37.png" alt="SEUR Image" width="96" height="37" />
        <?php
        $url_upload_dir = get_site_option( 'seur_uploads_url_labels' );
        $label_ids = seur_get_labels_ids($post->ID);
        $cont           = 1;
        foreach($label_ids as $labelid) {
            $label_file_name  = get_post_meta( $labelid, '_seur_shipping_order_label_file_name', true );
            //$file_type        = get_post_meta( $labelid2, '_seur_label_type', true );
            $suffix = count($label_ids) > 1 ? (esc_html__('for package', 'seur') . "  {$cont} ") : '';
            $cont++;
            ?>
            <a href="<?= esc_url( $url_upload_dir ) . '/' . esc_html( $label_file_name ) ?> " class="button btn-seur-label" download> <?= esc_html__( ' See SEUR Label ', 'seur' ) . $suffix ?> </a>
            <?php
        }
    } ?>
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
    $changeService = isset($_POST['seur-change-service']);

	$has_label   = seur()->has_label($post_id);

	if ( 'yes' !== $has_label ) {
		$label = seur_api_get_label( $post_id, $numpackages, $weight, false, $changeService );
		$new_status  = 'seur-shipment';
		seur_api_set_label_result( $post_id, $label, $new_status);

		if (! $label['status'] ) {
			echo 'There was an error: ' . esc_html( $label['message'] );
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
