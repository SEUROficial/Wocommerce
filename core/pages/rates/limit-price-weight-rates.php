<?php
/**
 * Limit Price Weight Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="container">
	<br>
	<p><?php esc_html_e( 'Max package price for apply rate price based on weight', 'seur' ); ?></p>
	<hr>
	<?php
    if ( isset( $_POST['seur_limit_price_weight_rates_post'] ) && ( ! isset( $_POST['seur_limit_price_weight_rates_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_limit_price_weight_rates_nonce_field'] ) ), 'seur_limit_price_weight_rates' ) ) ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    }
	?>
    <div class="content-loader">
        <form method="post" action="admin.php?page=seur_rates_prices&tab=limit_price_weight_rates">
            <table class="form-table">
                <tbody>
                    <?php
                    $products = seur()->get_products();
                    foreach ( $products as $code => $product ) {
                        if (isset( $_POST['seur_limit_price_weight_rates_post']) && isset( $_POST[$product['field'] . '_max_price_field'] ) ) {
                            $max_price_value = sanitize_text_field( wp_unslash( $_POST[$product['field'] . '_max_price_field'] ) );
                            update_option( $product['field'] . '_max_price_field', $max_price_value );
                        }

                        $max_price_field = get_option($product['field'] . '_max_price_field');
	                    echo '<tr>
                            <th scope="row">' . esc_html( $code ) . '</th>
                            <td><input title="' . esc_attr( $code ) . '" type="text" 
                                name="' . esc_attr( $product['field'] ) . '_max_price_field" 
                                value="' . ( $max_price_field ? esc_html( $max_price_field ) : '' ) . '" size="40">
                            </td>
                        </tr>';
                    }
                    ?>
                    <input type="hidden" name="seur_limit_price_weight_rates_post" value="true" >
                <?php wp_nonce_field( 'seur_limit_price_weight_rates', 'seur_limit_price_weight_rates_nonce_field' ); ?>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
        </form>
    </div>
</div>
