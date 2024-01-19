<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="container">
    <br>

    <p><?php _e( 'Custom Names for Seur Rates', 'seur' ); ?></p>

    <hr>

    <?php
    if ( isset( $_POST['seur_custom_name_rates_post'] ) &&
        ( ! isset( $_POST['seur_custom_name_rates_nonce_field'] )  ||
            ! wp_verify_nonce( $_POST['seur_custom_name_rates_nonce_field'], 'seur_custom_name_rates' ) )
    ) {
        print 'Sorry, your nonce did not verify.';
        exit;
    }

    $products = seur()->get_products();
    if (  isset( $_POST['seur_custom_name_rates_post'] ) ) {
        foreach ($products as $custom_name => $product) {
            update_option ($product['field'].'_custom_name_field', sanitize_text_field($_POST[$product['field'].'_custom_name_field']));
        }
    }
    ?>

    <div class="content-loader">
        <form method="post" action="admin.php?page=seur_rates_prices&tab=custom_rates_name">

            <table class="form-table">
                <tbody>
                    <?php foreach ($products as $custom_name => $product) {
                        echo '<tr>
                        <th scope="row">' . $custom_name . '</th>
                        <td><input 
                            title="' . $custom_name . '" 
                            type="text" name="' . $product['field'] . '_custom_name_field" 
                            value="' . (get_option($product['field'].'_custom_name_field') ?? '') . '" size="40"></td>
                        </tr>';
                    } ?>
                    <input type="hidden" name="seur_custom_name_rates_post" value="true" >
                    <?php wp_nonce_field( 'seur_custom_name_rates', 'seur_custom_name_rates_nonce_field' ); ?>
                </tbody>
            </table>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Update Options', 'seur' ); ?>"></p>
        </form>
    </div>

</div>