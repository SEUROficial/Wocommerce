<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	function seur_get_labels_from_order( $post ){
		global $error;

		if ( ! current_user_can('level_10') )
		die( __( 'Cheatin&#8217; uh?', SEUR_TEXTDOMAIN ) );
		$orderID   = '';
		$orderID   = absint( $_GET["order_id"] );
		$order_id  = '';
		$order_id  = $_POST['order-id'];
		$weight    = '';
		$weight    = get_post_meta( $orderID, '_seur_cart_weight', true );
		$packages  = '';
		if( ! $orderID && ! $order_id ) exit;
		?>
		<div class="wrap">
		<h1 class="wp-heading-inline"><?php _e( 'Get Labels', SEUR_TEXTDOMAIN ); ?></h1>
		<hr class="wp-header-end">
		<?php

		$url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
		if ( ! seur_check_url_exists( $url ) )
		    die('API dont work in this moment');

		if( $orderID &&  ! $order_id ){ ?>

				<form method="post" name="getlabels">
				<input type='hidden' name='order-id' class='form-control' value='<?php echo $orderID; ?>' />

				<label><?php _e( 'Packages Weight', SEUR_TEXTDOMAIN); ?></label><br />
	            <input title="<?php _e('Weight', SEUR_TEXTDOMAIN ); ?>" type='text' name='seur-weight' class='form-control' placeholder='<?php _e( 'EX: 0.300', SEUR_TEXTDOMAIN ); ?>' value='<?php if ( $weight ) echo $weight; ?>' required='' /><br />
	            <label><?php _e( 'Number of Packages', SEUR_TEXTDOMAIN); ?></label><br />
	            <input title="<?php _e('Number of Packages', SEUR_TEXTDOMAIN ); ?>" type='text' name='seur-number-packages' class='form-control' placeholder='<?php _e( 'EX: 2', SEUR_TEXTDOMAIN ); ?>' value='' required="" /><br />
	            <?php wp_nonce_field( 'seur_get_label_action', 'seur_get_label_nonce_field' ); ?>
	            <input type="submit" class="seur_label_submit button button-primary" value="<?php _e( 'Get labels', SEUR_TEXTDOMAIN ); ?>" />
			</form>
			<br />
			<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', SEUR_TEXTDOMAIN); ?></a>

		<?php	} elseif ( $order_id ) {

					$order_id    = $_POST['order-id'];
					$weight      = $_POST['seur-weight'];
					$numpackages = $_POST['seur-number-packages'];
					$has_label   = '';
					$label_id    = '';

				    if ( ! $weight ) {
					    $message = __( 'Weight is needed', SEUR_TEXTDOMAIN );
					    die( $message );
					    }

					if ( ! $numpackages ) {
					    $message = __( 'Package number is needed', SEUR_TEXTDOMAIN );
					    die( $message );
					    }

				    if ( ! isset( $_POST['seur_get_label_nonce_field'] ) || ! wp_verify_nonce( $_POST['seur_get_label_nonce_field'], 'seur_get_label_action' ) ) {
					    exit;
					    }

				    $new_status  = seur_after_get_label();
				    $has_label   = get_post_meta( $order_id, '_seur_shipping_order_label_downloaded', true );

				    if ( $has_label != 'yes' ) {

				        $label  = seur_get_label( $order_id, $numpackages, $weight );

				        $label_result  = $label[0]['result'];
				        $labelID       = $label[0]['labelID'];
				        $label_message = $label[0]['message'];

				        if( $label_result ){

				            $order = wc_get_order( $order_id );
				            $order->update_status( $new_status, __( 'Label have been created:', SEUR_TEXTDOMAIN ), true );
				            add_post_meta( $order_id,'_seur_shipping_order_label_downloaded',  'yes', true );
				            add_post_meta( $order_id,'_seur_shipping_label_id',  $labelID, true );
				            $order->add_order_note( 'The Label for Order #' . $post_id . ' have been downloaded', 0, true);
				            echo __('Label dowloaded, the Label ID is ', SEUR_TEXTDOMAIN ) . $labelID; ?>
				            <br />
							<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', SEUR_TEXTDOMAIN); ?></a>
							<?php
				        } else {
					        echo 'There was an error: ' . $label_message;
				        }
				    } else {
					    _e('The Order already has a label', SEUR_TEXTDOMAIN ); ?>
					    <br />
						<a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', SEUR_TEXTDOMAIN); ?></a>
				   <?php }
    	} ?>
		</div>
<?php	}