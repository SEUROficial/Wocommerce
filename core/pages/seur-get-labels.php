<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    function seur_get_labels_from_order( $post ){
        global $error;

        if ( ! current_user_can('edit_shop_orders') )
        die( __( 'Cheatin&#8217; uh?', 'seur' ) );

        $orderID     = '';
        $orderID     = absint( sanitize_text_field( $_GET["order_id"] ) );
        $order_id    = '';
        $order_id    = sanitize_text_field( $_POST['order-id'] );
        $weight_unit = '';
        $weight_unit =  get_option('woocommerce_weight_unit');
        $weight      = '';
        $order_data  = seur_get_order_data( $orderID );
        $weight      = $order_data[0]['weight'];
        $shop2       = get_post_meta( $orderID, 'seur_2shop_codCentro', true );
        $packages    = '';
        $disabled    = ' disabled="disabled"';

        if ( ! empty( $shop2 ) ) {
            $value = ' value="1" ';
        } else {
            $value = ' value="" ';
        }

        if( ! $orderID && ! $order_id ) exit;
        ?>

        <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e( 'Get Labels', 'seur' ); ?></h1>
        <hr class="wp-header-end">
        <?php

        $url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
        if ( ! seur_check_url_exists( $url ) ) die( __('We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );

        if( $orderID &&  ! $order_id ){ ?>

                <form method="post" name="getlabels">
                <input type='hidden' name='order-id' class='form-control' value='<?php echo $orderID; ?>' />

                <label><?php _e( 'Packages Weight', 'seur'); ?></label><br />
                <?php if ( $weight_unit == 'kg' ) { ?>
                <input title="<?php _e('Weight', 'seur' ); ?>" type='text' name='seur-weight' class='form-control' placeholder='<?php _e( 'EX: 0.300', 'seur' ); ?>' value='<?php if ( $weight ) echo $weight; ?>' required='' /> <?php } elseif ( $weight_unit == 'g' ) { ?>
                <input title="<?php _e('Weight', 'seur' ); ?>" type='text' name='seur-weight' class='form-control' placeholder='<?php _e( 'EX: 300', 'seur' ); ?>' value='<?php if ( $weight ) echo $weight; ?>' required='' /> <?php } ?>
                <br />
                <label><?php _e( 'Number of Packages', 'seur'); ?></label><br />
                <input title="<?php _e('Number of Packages', 'seur' ); ?>" type='text' name='seur-number-packages' class='form-control' placeholder='<?php _e( 'EX: 2', 'seur' ); ?>' <?php echo $value; ?> required="" <?php if ( ! empty( $shop2 ) ) { echo $disabled; } ?>/><br />
                <?php wp_nonce_field( 'seur_get_label_action', 'seur_get_label_nonce_field' ); ?>
                <input type="submit" class="seur_label_submit button button-primary" value="<?php _e( 'Get labels', 'seur' ); ?>" />
            </form>
            <br />
            <a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', 'seur'); ?></a>

        <?php   } elseif ( $order_id ) {

                    $order_id    = $_POST['order-id'];
                    $weight      = $_POST['seur-weight'];
                    $numpackages = $_POST['seur-number-packages'];
                    $has_label   = '';
                    $label_id    = '';

                    if ( ! empty( $shop2 ) && $weight > 20 ) {
	                    $message = __( 'Max Weight 20 Kg', 'seur' );
                        die( $message );
                    }

                    if ( ! $weight ) {
                        $message = __( 'Weight is needed', 'seur' );
                        die( $message );
                        }

                    if ( ! $numpackages ) {
                        $message = __( 'Package number is needed', 'seur' );
                        die( $message );
                        }

                    if ( ! isset( $_POST['seur_get_label_nonce_field'] ) || ! wp_verify_nonce( $_POST['seur_get_label_nonce_field'], 'seur_get_label_action' ) ) {
                        exit;
                        }

                    $new_status  = seur_after_get_label();
                    $has_label   = get_post_meta( $order_id, '_seur_shipping_order_label_downloaded', true );

                    if ( $has_label != 'yes' ) {

                        $label  = seur_get_label( $order_id, $numpackages, $weight, true );

                        $label_result  = $label[0]['result'];
                        $labelID       = $label[0]['labelID'];
                        $label_message = $label[0]['message'];

                        if( $label_result ){

                            $order = wc_get_order( $order_id );
                            $order->update_status( $new_status, __( 'Label have been created:', 'seur' ), true );
                            add_post_meta( $order_id,'_seur_shipping_order_label_downloaded',  'yes', true );
                            add_post_meta( $order_id,'_seur_shipping_label_id',  $labelID, true );
                            $order->add_order_note( 'The Label for Order #' . $order_id . ' have been downloaded', 0, true);
                            echo __('Label dowloaded, the Label ID is ', 'seur' ) . $labelID; ?>
                            <br />
                            <a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', 'seur'); ?></a>
                            <?php
                        } else {
                            echo 'There was an error: ' . $label_message;
                        }
                    } else {
                        _e('The Order already has a label', 'seur' ); ?>
                        <br />
                        <a class="button" href="#" onclick="self.parent.tb_remove(); self.parent.location.reload()"><?php _e('Close', 'seur'); ?></a>
                   <?php }
        } ?>
        </div>
<?php   }