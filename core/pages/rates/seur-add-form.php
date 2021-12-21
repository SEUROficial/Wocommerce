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

	if ( 'price' === $rates_type ) {
		$min       = __( 'Min Price (=)', 'seur' );
		$title_min = __( 'The product price is equal or mayor of this field', 'seur' );
		$max       = __( 'Max Price (<)', 'seur' );
		$title_max = __( 'The product price is minor of this field', 'seur' );
	} else {
		$min       = __( 'Min Weight (=)', 'seur' );
		$title_min = __( 'The product Weight is equal or mayor of this field', 'seur' );
		$max       = __( 'Max Weight (<)', 'seur' );
		$title_max = __( 'The product Weight is minor of this field', 'seur' );
	}
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
							$tabla = $wpdb->prefix . SEUR_PLUGIN_SVPR;
							$sql   = "SELECT * FROM $tabla";
							$regs  = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}seur_svpr" );
						foreach ( $regs as $valor ) {
							echo '<option value="' . esc_html( $valor->descripcion ) . '">' . esc_html( $valor->descripcion ) . '</option>';
						}
						?>
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
				<td><input title="<?php echo esc_html( $title_min ); ?>" type='text' name='minprice' class='form-control' placeholder='EX : 0.50' required=""></td>
			</tr>
			<tr>
				<td><?php echo esc_html( $max ); ?></td>
				<td><input title="<?php echo esc_html( $title_max ); ?>" type='text' name='maxprice' class='form-control' placeholder='EX : 100.50' required=""></td>
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
	<?php
}
