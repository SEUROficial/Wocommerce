<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_edit_rate(){


	if( isset( $_GET[ 'edit_id' ] ) ) {
		global $wpdb;

		$id = sanitize_text_field ( $_GET[ 'edit_id' ] );


		//$wpdb->show_errors();
		$getrate = $wpdb->get_row ( $wpdb->prepare( "SELECT * FROM {$wpdb->base_prefix}seur_custom_rates WHERE ID = %d", $id ) );
		//$wpdb->print_error();

		$max_price = $getrate->maxprice;

		if ( $max_price == '9999999' ) {
			 $max_price = '*';
		} else {
			$max_price = $max_price;
		}

	}

	$rates_type = get_option( 'seur_rates_type_field' );

	if( $rates_type == 'price' ){
		$min 	   = __('Min Price (=)', 'seur' );
		$title_min = __('The product price is equal or mayor of this field', 'seur' );
		$max 	   = __('Max Price (<)', 'seur' );
		$title_max = __('The product price is minor of this field', 'seur' );
	} else {
		$min       = __('Min Weight (=)', 'seur' );
		$title_min = __('The product Weight is equal or mayor of this field', 'seur' );
		$max       = __('Max Weight (<)', 'seur' );
		$title_max = __('The product Weight is minor of this field', 'seur' );
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
 		<input type='hidden' name='id' value='<?php echo $getrate->ID; ?>' />
        <tr>
            <td><?php _e('Rate', 'seur' ); ?></td>

	        <td>
	            <select class="select rate" id="rate" title="<?php _e('Select Rate to apply', 'seur' ); ?>" name="rate">
				    <?php
						$tabla = $wpdb->prefix . SEUR_PLUGIN_SVPR;
						$sql   = "SELECT * FROM $tabla";
						$regs  = $wpdb->get_results( $wpdb->prepare( $sql ) );

						foreach ( $regs as $valor ) {

							if ( $getrate->rate == $valor->descripcion ){
								$selected = ' selected="selected"';
							} else {
								$selected = '';
							}

							echo '<option value="' . $valor->descripcion . '"'  . $selected . '>' . $valor->descripcion . '</option>';
						}
					?>
				</select>
            </td>
        </tr>

        <tr>
            <td><?php _e('Country', 'seur' ); ?></td>

            <td id="countryid">
	            <select class="select country" value="Select" id="country" title="<?php _e('Select Country', 'seur' ); ?>" name="country">
				    <?php

					    $countries = seur_get_countries();
					    if ( $getrate->country == 'ES' || $getrate->country == 'PT' || $getrate->country == 'AD' ) {

						    if ( $getrate->country == 'ES' ){
									echo '<option value="ES" selected="selected">' . __('Spain', 'seur' ) . '</option>';
								} else {
									echo '<option value="ES">' . __('Spain', 'seur' ) . '</option>';
									}
							if ( $getrate->country == 'PT' ){
									echo '<option value="PT" selected="selected">' . __('Portugal', 'seur' ) . '</option>';
								} else {
									echo '<option value="PT">' . __('Portugal', 'seur' ) . '</option>';
									}
							if ( $getrate->country == 'AD' ){
									echo '<option value="AD" selected="selected">' . __('Andorra', 'seur' ) . '</option>';
								} else {
									echo '<option value="AD">' . __('Andorra', 'seur' ) . '</option>';
									}

						    } else {
								echo '<option value="*">' . __( 'All Countries', 'seur' ) . '</option>';
								$countries = seur_get_countries();
								foreach ($countries as $country => $value ) {

									if ( $getrate->country == $value ){
										$selected = ' selected="selected"';
									} else {
										$selected = '';
									}
								echo '<option value="' . $country  . '"' . $selected . '>' . $value . '</option>';
							}
						}
					?>
				</select>
            </td>
        </tr>

        <tr>
            <td><?php _e('State', 'seur' ); ?></td>

             <td id="states">
			 	<?php

				 	$country = $getrate->country;

					if( $country && $country != '* '){
						$states = seur_get_countries_states( $country );
					} else {
						$states = false;
					}

					if( $states && $states != '*') {

						echo '<select value="Select" title="' . __('Select State', 'seur' ) . '" name="state">';
						echo '<option value="*">' . __( 'All States', 'seur' ) . '</option>';
						$currentstate = $getrate->state;
						// Display city dropdown based on country name
						if( $currentstate && $currentstate != '*' ) {
							foreach( $states as $state => $value ){
								if ( $currentstate == $state ){
									$selected = ' selected="selected"';
								} else {
									$selected = '';
								}
								echo '<option value="' . $state . '"' . $selected . '>' . $value . '</option>';
							}
							echo "</select>";
						}
					}

					if ( ! $states ) {
						$currentstate = $getrate->state;
						echo '<input title="' . __( 'State', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'State', 'seur' ) . '" value="' . $currentstate . '">';
					}
					if ( $country == '*' ) {
						//campo
						echo '<input title="' . __( 'No needed', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', 'seur' ) . '" value="*" readonly>';
					} ?>
             </td>

        </tr>

        <tr>
            <td><?php _e('Postcode', 'seur' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur' ); ?>" type='text' name='postcode' value='<?php echo $getrate->postcode ?>' class='form-control' placeholder='EX : 08023' required=""></td>
        </tr>

        <tr>
            <td><?php echo $min; ?></td>

            <td><input title="<?php echo $title_min; ?>" type='text' name='minprice' value='<?php echo $getrate->minprice ?>' class='form-control' placeholder='EX : 0.50' required=""></td>
        </tr>

        <tr>
            <td><?php echo $max; ?></td>

            <td><input title="<?php echo $title_max; ?>" type='text' name='maxprice' value='<?php echo $max_price ?>' class='form-control' placeholder='EX : 100.34' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Rate Price', 'seur' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur' ); ?>" type='text' name='rateprice' value='<?php echo $getrate->rateprice ?>' class='form-control' placeholder='EX : 5.45' required=""></td>
        </tr>

        <input type="hidden" name="rate_type" value="<?php echo $rates_type; ?>">
        <?php wp_nonce_field( 'edit_seur_rate', 'edit_rate_nonce_field' ); ?>

        <tr>
            <td colspan="2"><button type="submit" class="button button-primary btn btn-primary" name="btn-save" id="btn-save">Save this Record</button></td>
        </tr>

    </table>
</form>

<?php } ?>
