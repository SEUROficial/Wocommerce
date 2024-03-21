<?php
/**
 * Seur Get Labels.
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Seur Get labels from Order
 *
 * @param array $post Data post.
 */
function seur_get_labels_from_order( $post ) {
	global $error;

	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		die( esc_html__( 'Cheatin&#8217; uh?', 'seur' ) );
	}

	$orderid2 = '';
	if ( isset( $_GET['order_id'] ) ) {
		$orderid2 = absint( sanitize_text_field( wp_unslash( $_GET['order_id'] ) ) );
	}
	$order_id = '';
	if ( isset( $_POST['order-id'] ) ) {
		$order_id = sanitize_text_field( wp_unslash( $_POST['order-id'] ) );
	}
    if ( ! $orderid2 && ! $order_id ) {
        exit;
    }
    $change_service = isset($_GET['change'])?$_GET['change']:'0';
	$weight_unit = get_option( 'woocommerce_weight_unit' );
    $wc_order2 = wc_get_order( $orderid2 );
	$weight      = $wc_order2->get_meta('_seur_cart_weight', true );
	$shop2       = $wc_order2->get_meta('_seur_2shop_codCentro', true );
	$disabled    = ' readonly';

    $value = (empty($shop2) ? '' : '1');
	?>
	<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Get Labels', 'seur' ); ?></h1>
	<hr class="wp-header-end">
	<?php
	if ( $orderid2 && ! $order_id ) {
		?>
		<form method="post" name="getlabels">
			<input type='hidden' name='order-id' class='form-control' value='<?php echo esc_html( $orderid2 ); ?>' />
			<label><?php esc_html_e( 'Packages Weight', 'seur' ); ?></label><br />
			<?php if ( 'kg' === $weight_unit ) { ?>
				<input title="<?php esc_html_e( 'Weight', 'seur' ); ?>" type='text' name='seur-weight' class='form-control' placeholder='<?php esc_html_e( 'EX: 0.300', 'seur' ); ?>' value='<?php if ( $weight ) { echo esc_html( $weight );} ?>' required='' /> <?php
			} elseif ( 'g' === $weight_unit ) { ?>
				<input title="<?php esc_html_e( 'Weight', 'seur' ); ?>" type='text' name='seur-weight' class='form-control' placeholder='<?php esc_html_e( 'EX: 300', 'seur' ); ?>' value='<?php if ( $weight ) { echo esc_html( $weight ); } ?>' required='' />
			<?php } ?><br />
			<label><?php esc_html_e( 'Number of Packages', 'seur' ); ?></label><br />
			<input title="<?php esc_html_e( 'Number of Packages', 'seur' ); ?>" type="text" name="seur-number-packages" class="form-control" placeholder="<?php esc_html_e( 'EX: 2', 'seur' ); ?>" value="<?php echo esc_html( $value ); ?>" required="" <?php if ( ! empty( $shop2 ) ) { echo esc_html( $disabled ); } ?> /><br />
            <input type="hidden" name="seur-change-service" value="<?php echo  $change_service; ?>"/>
			<?php wp_nonce_field( 'seur_get_label_action', 'seur_get_label_nonce_field' ); ?>
			<input type="submit" class="seur_label_submit button button-primary" value="<?php esc_html_e( 'Get labels', 'seur' ); ?>" />
		</form>
		<br />
		<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php esc_html_e( 'Close', 'seur' ); ?></a>
		<?php
	} elseif ( $order_id ) {
        $order_id    = '';
        $weight      = '';
        $numpackages = '';
        $changeService = false;
		if ( isset( $_POST['order-id'] ) && isset( $_POST['seur-weight'] ) && isset( $_POST['seur-number-packages'] ) ) {
			$order_id    = sanitize_text_field( wp_unslash( $_POST['order-id'] ) );
			$weight      = sanitize_text_field( wp_unslash( $_POST['seur-weight'] ) );
			$numpackages = sanitize_text_field( wp_unslash( $_POST['seur-number-packages'] ) );
            $changeService = sanitize_text_field( wp_unslash( $_POST['seur-change-service'] ) );
		}

		if ( ! empty( $shop2 ) && $weight > 20 ) {
			$message = __( 'Max Weight 20 Kg', 'seur' );
			die( esc_html( $message ) );
		}

		if ( empty( $weight ) ) {
			$message = __( 'Weight is needed', 'seur' );
			die( esc_html( $message ) );
		}

		if ( empty( $numpackages ) ) {
			$message = __( 'Package number is needed', 'seur' );
			die( esc_html( $message ) );
		}

		if ( ! isset( $_POST['seur_get_label_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_get_label_nonce_field'] ) ), 'seur_get_label_action' ) ) {
			exit;
		}

        $has_label  = seur()->has_label($order_id);

		if ( 'yes' !== $has_label ) {
            $label = seur_api_get_label( $order_id, $numpackages, $weight, true, $changeService );
			$new_status = seur_after_get_label();
			seur_api_set_label_result( $order_id, $label, $new_status);

			if ( $label['status'] ) {
				echo esc_html__( 'Label dowloaded, the Label ID is ', 'seur' ) . esc_html( seur_api_get_label_ids($label, true) );
				?>
				<br />
				<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()">
					<?php esc_html_e( 'Close', 'seur' ); ?>
				</a>
				<?php
			} else {
				echo 'There was an error: ' . esc_html( $label['message'] );
			}
		} else {
			esc_html_e( 'The Order already has a label', 'seur' );
			?>
			<br />
			<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php esc_html_e( 'Close', 'seur' ); ?></a>
			<?php
		}
	}
	?>
	</div>
	<?php
}
