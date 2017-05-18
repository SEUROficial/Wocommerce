<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function seur_add_form() {
	global $wpdb;
?>
<style type="text/css">
#dis{
display:none;
#adminmenuback{
	display:none !important;
}
}
</style>
<div id="dis">
    <!-- here message will be displayed -->
</div>

<form method='post' id='emp-SaveForm' action="#">
    <table class='table table-bordered'>

        <tr>
            <td><?php _e('Rate', SEUR_TEXTDOMAIN ); ?></td>

	        <td>
	            <select class="select rate" id="rate"  title="<?php _e('Select Rate to apply', SEUR_TEXTDOMAIN ); ?>" name="rate">
		            <option value="Select"><?php _e('Select a Rate', SEUR_TEXTDOMAIN ) ?></option>
				    <?php
					    $tabla	= $wpdb->prefix . SEUR_PLUGIN_SVPR;
	                    $sql	= "SELECT * FROM $tabla";
	                    $regs	= $wpdb->get_results( $sql );

						foreach ( $regs as $valor ) {

								echo '<option value="' . $valor->descripcion  . '">' . $valor->descripcion . '</option>';
							}
					?>
				</select>
            </td>
        </tr>

        <tr>
            <td><?php _e('Country', SEUR_TEXTDOMAIN ); ?></td>

            <td id="countryid">
	            <select class="select country" id="country" title="<?php _e('Select Country', SEUR_TEXTDOMAIN ); ?>" name="country">
				    <?php
						echo '<option value="Select">' . __('Select a Country', SEUR_TEXTDOMAIN ) . '</option>';
						echo '<option value="ES">' . __('Spain', SEUR_TEXTDOMAIN ) . '</option>';
					?>
				</select>
            </td>
        </tr>

        <tr>
            <td><?php _e('State', SEUR_TEXTDOMAIN ); ?></td>

            <td id="states">
	            <input title="<?php _e( 'Select a Country', SEUR_TEXTDOMAIN ); ?>" type="text" name="" class="form-control" placeholder="<?php _e( 'Select a Country', SEUR_TEXTDOMAIN ); ?>" value="" readonly>
            </td>
        </tr>

        <tr>
            <td id="postcode"><?php _e('Postcode', SEUR_TEXTDOMAIN ); ?></td>

            <td><input title="<?php _e('Type a Postcode', SEUR_TEXTDOMAIN ); ?>" type='text' name='postcode' class='form-control' placeholder='EX : 08023' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Min Price (=)', SEUR_TEXTDOMAIN ); ?></td>

            <td><input title="<?php _e('The product price is equal or mayor of this field', SEUR_TEXTDOMAIN ); ?>" type='text' name='minprice' class='form-control' placeholder='EX : 0' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Max Price (<)', SEUR_TEXTDOMAIN ); ?></td>

            <td><input title="<?php _e('The product price is minor of this field', SEUR_TEXTDOMAIN ); ?>" type='text' name='maxprice' class='form-control' placeholder='EX : 100' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Rate Price', SEUR_TEXTDOMAIN ); ?></td>

            <td><input title="<?php _e('Apply this price to the rate', SEUR_TEXTDOMAIN ); ?>" type='text' name='rateprice' class='form-control' placeholder='EX : 5' required=""></td>
        </tr>

        <?php wp_nonce_field( 'add_new_seur_rate', 'new_seur_rate_nonce_field' ); ?>

        <tr>
            <td colspan="2"><button type="submit" class="button button-primary btn btn-primary" name="btn-save" id="btn-save">Save this Record</button></td>
        </tr>
    </table>
</form>
<?php } ?>