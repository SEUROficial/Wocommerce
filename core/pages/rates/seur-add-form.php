<?php
/**
 * SEUR Add Form
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Add Form
 */
function seur_add_form() {
	global $wpdb;
	$rates_type = get_option( 'seur_rates_type_field' );

	$min       = __( 'Min '.$rates_type.' (=)', 'seur' );
	$title_min = __( 'The product '.$rates_type.' is equal or mayor of this field', 'seur' );
	$max       = __( 'Max '.$rates_type.' (<)', 'seur' );
	$title_max = __( 'The product '.$rates_type.' is minor of this field', 'seur' );
	?>
    <style type="text/css">
        #dis{
            display:none;
        }
        #adminmenuback{
            display:none !important;
        }
    </style>
    <div id="dis">
        <!-- here message will be displayed -->
    </div>

    <form method='post' id='emp-SaveForm' action="#">
		<?php esc_html_e( 'Include the rates of the transport options that your customers can choose', 'seur' ); ?>
        <table class='table table-bordered'>
            <tr>
                <td><?php esc_html_e( 'Rate', 'seur' ); ?></td>
                <td>
                    <select class="select rate" id="rate"  title="<?php esc_html_e( 'Select Rate to apply', 'seur' ); ?>" name="rate">
                        <option value="Select"><?php esc_html_e( 'Select a Rate', 'seur' ); ?></option>
						<?php
						$registros = seur()->get_products();
						foreach ( $registros as $description => $valor ) {
							echo '<option value="' . esc_html( $description ) . '">' . esc_html( $description ) . '</option>';
						} ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'Country', 'seur' ); ?></td>
                <td id="countryid">
                    <select class="select country" id="country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="country">
						<?php
						echo '<option value="Select">' . esc_html__( 'Select a Country', 'seur' ) . '</option>';
						echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
						?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'State', 'seur' ); ?></td>
                <td id="states">
                    <input title="<?php esc_html_e( 'Select a Country', 'seur' ); ?>" type="text" name="" class="form-control" placeholder="<?php esc_html_e( 'Select a Country', 'seur' ); ?>" value="" readonly>
                </td>
            </tr>
            <tr>
                <td id="postcode"><?php esc_html_e( 'Postcode', 'seur' ); ?></td>
                <td><input title="<?php esc_html_e( 'Type a Postcode', 'seur' ); ?>" type='text' name='postcode' class='form-control' placeholder='EX : 08023' required=""></td>
            </tr>
            <tr>
                <td><?php echo esc_html( $min ); ?></td>
                <td><input title="<?php echo esc_html( $title_min ); ?>" type='text' name='min<?php echo $rates_type; ?>' class='form-control' placeholder='EX : 0.50' required=""></td>
            </tr>
            <tr>
                <td><?php echo esc_html( $max ); ?></td>
                <td><input title="<?php echo esc_html( $title_max ); ?>" type='text' name='max<?php echo $rates_type; ?>' class='form-control' placeholder='EX : 100.50' required=""></td>
            </tr>
            <tr>
                <td><?php esc_html_e( 'Rate Price', 'seur' ); ?></td>
                <td><input title="<?php esc_html_e( 'Apply this price to the rate', 'seur' ); ?>" type='text' name='rateprice' class='form-control' placeholder='EX : 5.63' required=""></td>
            </tr>
            <input type="hidden" name="rate_type" value="<?php echo esc_html( $rates_type ); ?>">
			<?php wp_nonce_field( 'add_new_seur_rate', 'new_seur_rate_nonce_field' ); ?>
            <tr>
                <td colspan="2"><button type="submit" class="button button-primary btn btn-primary" name="btn-save" id="btn-save"><?php esc_html_e( 'Save this Record', 'seur' ); ?></button></td>
            </tr>
        </table>
    </form>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Remover la clase select2-hidden-accessible y el contenedor de Select2
            function removeSelect2() {
                var countryElement = document.getElementById('country');
                countryElement.classList.remove('select2-hidden-accessible');

                var nextElement = countryElement.nextElementSibling;
                if (nextElement && nextElement.classList.contains('select2-container')) {
                    nextElement.remove();
                }
            }

            // Ejecutar la función al cargar la página
            removeSelect2();

            // Observar cambios en el DOM para volver a ejecutar la función si es necesario
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    removeSelect2();
                });
            });

            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>

	<?php
}

