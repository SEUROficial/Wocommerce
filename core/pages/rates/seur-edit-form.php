<?php if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function seur_edit_rate() {

	if ( isset( $_GET['edit_id'] ) ) {
		global $wpdb;

		$id        = sanitize_text_field( $_GET['edit_id'] );
		$getrate   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}seur_custom_rates WHERE ID = %d", $id ) );
		$max_price = $getrate->maxprice;

		if ( $max_price == '9999999' ) {
			$max_price = '*';
		} else {
			$max_price = $max_price;
		}
	}

	$rates_type = get_option( 'seur_rates_type_field' );

	if ( $rates_type == 'price' ) {
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
						$regs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}seur_svpr" );

					foreach ( $regs as $valor ) {

						if ( $getrate->rate == $valor->descripcion ) {
							$selected = ' selected="selected"';
						} else {
							$selected = '';
						}

						echo '<option value="' . esc_html( $valor->descripcion ) . '"' . esc_html( $selected ) . '>' . esc_html( $valor->descripcion ) . '</option>';
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
					if ( $getrate->country == 'ES' || $getrate->country == 'PT' || $getrate->country == 'AD' ) {

						if ( $getrate->country == 'ES' ) {
								echo '<option value="ES" selected="selected">' . esc_html__( 'Spain', 'seur' ) . '</option>';
						} else {
							echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
						}
						if ( $getrate->country == 'PT' ) {
								echo '<option value="PT" selected="selected">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
						} else {
							echo '<option value="PT">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
						}
						if ( $getrate->country == 'AD' ) {
								echo '<option value="AD" selected="selected">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
						} else {
							echo '<option value="AD">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
						}
					} else {
						$countries = array();
						$countries = include_once SEUR_PLUGIN_PATH . 'core/places/countries.php';
						$countries = asort( $countries );
						// print_r($countries);
						echo '<option value="*">' . esc_html__( 'All Countries', 'seur' ) . '</option>';
						foreach ( $countries as $country => $value ) {

							if ( $getrate->country == $value ) {
								$selected = ' selected="selected"';
							} else {
								$selected = '';
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

				if ( $country && $country != '* ' ) {
					$states = seur_get_countries_states( $country );
				} else {
					$states = false;
				}

				if ( $states && $states != '*' ) {

					echo '<select value="Select" title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
					echo '<option value="*">' . esc_html__( 'All States', 'seur' ) . '</option>';
					$currentstate = $getrate->state;
					// Display city dropdown based on country name.
					if ( $currentstate && $currentstate != '*' ) {
						foreach ( $states as $state => $value ) {
							if ( $currentstate == $state ) {
								$selected = ' selected="selected"';
							} else {
								$selected = '';
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
				if ( $country == '*' ) {
					// campo.
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

			<td><input title="<?php echo esc_html( $title_min ); ?>" type='text' name='minprice' value='<?php echo esc_html( $getrate->minprice ); ?>' class='form-control' placeholder='EX : 0.50' required=""></td>
		</tr>

		<tr>
			<td><?php echo esc_html( $max ); ?></td>

			<td><input title="<?php echo esc_html( $title_max ); ?>" type='text' name='maxprice' value='<?php echo esc_html( $max_price ); ?>' class='form-control' placeholder='EX : 100.34' required=""></td>
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

<?php } ?>
