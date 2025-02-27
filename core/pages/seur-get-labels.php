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

	$modify_packages = false;
	if ( isset( $_GET['modify_packages'] ) ) {
		$modify_packages = sanitize_text_field( wp_unslash( $_GET['modify_packages'] ) );
	}

    if($modify_packages){
        seur_modify_packages();
        return;
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
    $change_service = false;
    if ( isset( $_GET['change'] ) ) {
        $change_service = sanitize_text_field( wp_unslash( $_GET['change'] ) );
        $change_service = ($change_service == 1);
    }

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
            <?php if ($change_service) {
                echo '<input type="hidden" name="seur-change-service" value="1"/>';
            }
            wp_nonce_field( 'seur_get_label_action', 'seur_get_label_nonce_field' ); ?>
			<input type="submit" class="seur_label_submit button button-primary" value="<?php esc_html_e( 'Get labels', 'seur' ); ?>" />
		</form>
		<br />
		<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php esc_html_e( 'Close', 'seur' ); ?></a>
		<?php
	} elseif ( $order_id ) {
        $order_id    = '';
        $weight      = '';
        $numpackages = '';
        $changeService = isset($_POST['seur-change-service']);
		if ( isset( $_POST['order-id'] ) && isset( $_POST['seur-weight'] ) && isset( $_POST['seur-number-packages'] ) ) {
			$order_id    = sanitize_text_field( wp_unslash( $_POST['order-id'] ) );
			$weight      = sanitize_text_field( wp_unslash( $_POST['seur-weight'] ) );
			$numpackages = sanitize_text_field( wp_unslash( $_POST['seur-number-packages'] ) );
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

        if (!$has_label) {
            $label = seur_api_get_label( $order_id, $numpackages, $weight, true, $changeService );
			if ( $label['status'] ) {
                $new_status = seur_after_get_label();
                seur_api_set_label_result( $order_id, $label, $new_status);
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

function seur_modify_packages() {
	if ( ! isset( $_GET['order_id'] ) ) {
		return;
	}

	$order_id = absint( sanitize_text_field( wp_unslash( $_GET['order_id'] ) ) );
	$label_ids = seur_get_labels_ids( $order_id );
	$label_shipping_packages = (int) get_post_meta( $label_ids[0], '_seur_shipping_packages', true );
	$label_shipping_weight = (float) get_post_meta( $label_ids[0], '_seur_shipping_weight', true );

	?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Modify Packages', 'seur' ); ?></h1>
        <p><?php esc_html_e( 'Here you can modify the number of packages and the total weight for the order.', 'seur' ); ?></p>
        <form method="post" action="">
            <input type="hidden" name="order-id" value="<?php echo esc_attr( $order_id ); ?>">

            <label for="seur-number-packages">
				<?php esc_html_e( 'Current Number of Packages:', 'seur' ); ?>
            </label><br>
            <input title="<?php esc_attr_e( 'Number of Packages', 'seur' ); ?>"
                   type="number"
                   name="seur-number-packages"
                   class="form-control"
                   value="<?php echo esc_attr( $label_shipping_packages ); ?>"
                   required>
            <br><br>

            <label for="seur-shipping-weight">
				<?php esc_html_e( 'Current Weight (kg):', 'seur' ); ?>
            </label><br>
            <input title="<?php esc_attr_e( 'Total Weight', 'seur' ); ?>"
                   type="number"
                   step="0.01"
                   name="seur-shipping-weight"
                   class="form-control"
                   value="<?php echo esc_attr( $label_shipping_weight ); ?>"
                   required>
            <br><br>

			<?php wp_nonce_field( 'seur_modify_packages_action', 'seur_modify_packages_nonce_field' ); ?>

            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save', 'seur' ); ?>">
            <a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()">
				<?php esc_html_e( 'Close', 'seur' ); ?>
            </a>
        </form>
    </div>
	<?php

	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['seur-number-packages'] ) && isset( $_POST['seur-shipping-weight'] ) ) {
		if ( ! isset( $_POST['seur_modify_packages_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_modify_packages_nonce_field'] ) ), 'seur_modify_packages_action' ) ) {
			exit;
		}

		$new_packages = absint( sanitize_text_field( wp_unslash( $_POST['seur-number-packages'] ) ) );
		$new_weight = floatval( sanitize_text_field( wp_unslash( $_POST['seur-shipping-weight'] ) ) );

		if ( $new_packages > $label_shipping_packages || $new_weight != $label_shipping_weight ) {
			$order = seur_get_order($order_id);
			$expeditionCode = $order->get_meta('_seur_label_expeditionCode', true);

            $new_packages -= $label_shipping_packages;
			$response     = seur()->addParcelsToShipment( $expeditionCode, $new_weight, $new_packages );
			if (isset($response['ecbs']) && isset($response['parcelNumbers'])) {
				$ecbs_serialized = maybe_serialize($response['ecbs']);
				$parcelNumbers_serialized = maybe_serialize($response['parcelNumbers']);

				update_post_meta( $label_ids[0], '_seur_shipping_packages', $new_packages );
				update_post_meta( $label_ids[0], '_seur_shipping_weight', $new_weight );
				$order->update_meta_data('_seur_label_ecbs', $ecbs_serialized);
				$order->update_meta_data('_seur_label_parcelNumbers', $parcelNumbers_serialized);
				$order->save_meta_data();

				echo '<p>' . esc_html__( 'The number of packages and/or weight has been updated successfully.', 'seur' ) . '</p>';
			} else {
				echo '<p>' . esc_html__( 'Error updating packages: ' . $response['errors'][0]['detail'], 'seur' ) . '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'The new number of packages must be greater than the current number or the weight must be changed.', 'seur' ) . '</p>';
		}

	}
}
