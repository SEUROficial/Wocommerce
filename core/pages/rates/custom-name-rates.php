<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset( $_POST['seur_custom_name_rates_nonce_field'] )  ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['seur_custom_name_rates_nonce_field'])), 'seur_custom_name_rates')
    ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    }
}

$products = seur()->get_products();
if (  isset( $_POST['seur_custom_name_rates_post'] ) ) {
    foreach ($products as $custom_name => $product) {
        $rate_name_value = '';

        $field_key = $product['field'] . '_custom_name_field';
        if ( isset( $_POST[ $field_key ] ) ) {
            $rate_name_value = sanitize_text_field( wp_unslash( $_POST[ $field_key ] ) );
        }
        update_option($product['field'] . '_custom_name_field', $rate_name_value);
    }
}
?>
<div class="container">
    <br>
    <p><?php esc_html_e( 'Custom Names for Seur Rates', 'seur' ); ?></p>
    <hr>
    <div class="content-loader">
        <form method="post" action="admin.php?page=seur_rates_prices&tab=custom_rates_name">

            <table class="form-table">
                <tbody>
                    <?php foreach ($products as $custom_name => $product) {
	                    echo '<tr>
                            <th scope="row">' . esc_html( $custom_name ) . '</th>
                            <td><input 
                                title="' . esc_attr( $custom_name ) . '" 
                                type="text" name="' . esc_attr( $product['field'] ) . '_custom_name_field" 
                                value="' . esc_attr( get_option( $product['field'] . '_custom_name_field' ) ?? '' ) . '" size="40"></td>
                          </tr>';

                    } ?>
                    <input type="hidden" name="seur_custom_name_rates_post" value="true" >
                    <?php wp_nonce_field( 'seur_custom_name_rates', 'seur_custom_name_rates_nonce_field' ); ?>
                </tbody>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Update Options', 'seur' ); ?>">
            </p>
        </form>
    </div>

</div>