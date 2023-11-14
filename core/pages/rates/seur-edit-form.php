<?php
/**
 * SEUR Edit Form
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Edit Rate
 */
function seur_edit_rate() {

	if ( isset( $_GET['edit_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		global $wpdb;

		$id        = sanitize_text_field( wp_unslash( $_GET['edit_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$getrate   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}seur_custom_rates WHERE ID = %d", $id ) );
		$min_value = ($getrate->type=='price'? $getrate->minprice: $getrate->minweight);
        $max_value = ($getrate->type=='price'? $getrate->maxprice: $getrate->maxweight);
        if ( '9999999' === $max_value ) {
            $max_value = '*';
		}
	}
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
	</style>
    <div id="dis">
    </div>
    <form method='post' id='emp-UpdateForm' action='#'>
		<table class='table table-bordered'>
			<input type='hidden' name='id' value='<?php echo esc_html( $getrate->ID ); ?>' />
			<tr>
				<td><?php esc_html_e( 'Rate', 'seur' ); ?></td>
				<td>
					<select class="select rate" id="rate" title="<?php esc_html_e( 'Select Rate to apply', 'seur' ); ?>" name="rate">
						<?php
						$registros = seur()->get_products();
						foreach ( $registros as $description => $valor ) {
                            $selected = '';
							if ( $description === $getrate->rate ) {
								$selected = ' selected="selected"';
							}
							echo '<option value="' . esc_html( $description ) . '"' . esc_html( $selected ) . '>' . esc_html( $description ) . '</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Country', 'seur' ); ?></td>
				<td id="countryid">
					<select class="select country" value="Select" id="country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="country">
						<?php
						if ( 'ES' === $getrate->country || 'PT' === $getrate->country || 'AD' === $getrate->country ) {

							if ( 'ES' === $getrate->country ) {
									echo '<option value="ES" selected="selected">' . esc_html__( 'Spain', 'seur' ) . '</option>';
							} else {
								echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
							}
							if ( 'PT' === $getrate->country ) {
									echo '<option value="PT" selected="selected">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
							} else {
								echo '<option value="PT">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
							}
							if ( 'AD' === $getrate->country ) {
									echo '<option value="AD" selected="selected">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
							} else {
								echo '<option value="AD">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
							}
						} else {
							$countries = array();
							$countries = include_once SEUR_PLUGIN_PATH . 'core/places/countries.php';
							$countries = asort( $countries );
							echo '<option value="*">' . esc_html__( 'All Countries', 'seur' ) . '</option>';
							foreach ( $countries as $country => $value ) {
                                $selected = '';
								if ( $getrate->country === $value ) {
									$selected = ' selected="selected"';
								}
								echo '<option value="' . esc_html( $country ) . '"' . esc_html( $selected ) . '>' . esc_html( $value ) . '</option>';
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'State', 'seur' ); ?></td>
				<td id="states">
					<?php
                    $country = $getrate->country;
                    $states = false;
					if ( $country && '*' !== $country ) {
						$states = seur_get_countries_states( $country );
					}
					if ( $states && '*' !== $states ) {
						echo '<select value="Select" title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
						echo '<option value="*">' . esc_html__( 'All States', 'seur' ) . '</option>';
						$currentstate = $getrate->state;
						// Display city dropdown based on country name.
						if ( $currentstate && '*' !== $currentstate ) {
							foreach ( $states as $state => $value ) {
                                $selected = '';
								if ( $currentstate === $state ) {
									$selected = ' selected="selected"';
								}
								echo '<option value="' . esc_html( $state ) . '"' . esc_html( $selected ) . '>' . esc_html( $value ) . '</option>';
							}
							echo '</select>';
						}
					}
					if ( ! $states ) {
						$currentstate = $getrate->state;
						echo '<input title="' . esc_html__( 'State', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . esc_html__( 'State', 'seur' ) . '" value="' . esc_html( $currentstate ) . '">';
					}
					if ( '*' === $country ) {
						echo '<input title="' . esc_html__( 'No needed', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . esc_html__( 'No needed', 'seur' ) . '" value="*" readonly>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Postcode', 'seur' ); ?></td>
				<td><input title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" type='text' name='postcode' value='<?php echo esc_html( $getrate->postcode ); ?>' class='form-control' placeholder='EX : 08023' required=""></td>
			</tr>
			<tr>
				<td><?php echo esc_html( $min ); ?></td>
				<td><input title="<?php echo esc_html( $title_min ); ?>" type='text' name='min<?php echo $rates_type; ?>' value='<?php echo esc_html( $min_value ); ?>' class='form-control' placeholder='EX : 0.50' required=""></td>
			</tr>
			<tr>
				<td><?php echo esc_html( $max ); ?></td>
				<td><input title="<?php echo esc_html( $title_max ); ?>" type='text' name='max<?php echo $rates_type; ?>' value='<?php echo esc_html( $max_value ); ?>' class='form-control' placeholder='EX : 100.34' required=""></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Rate Price', 'seur' ); ?></td>
				<td><input title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" type='text' name='rateprice' value='<?php echo esc_html( $getrate->rateprice ); ?>' class='form-control' placeholder='EX : 5.45' required=""></td>
			</tr>
			<input type="hidden" name="rate_type" value="<?php echo esc_html( $rates_type ); ?>">
			<?php wp_nonce_field( 'edit_seur_rate', 'edit_rate_nonce_field' ); ?>
			<tr>
				<td colspan="2"><button type="submit" class="button button-primary btn btn-primary" name="btn-save" id="btn-save">Save this Record</button></td>
			</tr>
		</table>
	</form>
	<?php
}
