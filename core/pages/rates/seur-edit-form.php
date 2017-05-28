<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_edit_rate(){


	if( isset( $_GET[ 'edit_id' ] ) ) {
		global $wpdb;

		$id = sanitize_text_field ( $_GET[ 'edit_id' ] );

		$table = $wpdb->base_prefix . 'seur_custom_rates';

		$getrate = $wpdb->get_row( "SELECT * FROM " . $table . " WHERE ID = " . $id );

		$max_price = $getrate->maxprice;

		if ( $max_price == '9999999' ) {
			 $max_price = '*';
		} else {
			$max_price = $max_price;
		}

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
            <td><?php _e('Rate', 'seur-oficial' ); ?></td>

	        <td>
	            <select class="select rate" id="rate" title="<?php _e('Select Rate to apply', 'seur-oficial' ); ?>" name="rate">
				    <?php
						$tabla = $wpdb->prefix . SEUR_PLUGIN_SVPR;
						$sql   = "SELECT * FROM $tabla";
						$regs  = $wpdb->get_results( $sql );

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
            <td><?php _e('Country', 'seur-oficial' ); ?></td>

            <td id="countryid">
	            <select class="select country" value="Select" id="country" title="<?php _e('Select Country', 'seur-oficial' ); ?>" name="country">
				    <?php

					    $countries = seur_get_countries();
					    if ( $getrate->country == 'ES' || $getrate->country == 'PT' || $getrate->country == 'AD' ) {

						    if ( $getrate->country == 'ES' ){
									echo '<option value="ES" selected="selected">' . __('Spain', 'seur-oficial' ) . '</option>';
								} else {
									echo '<option value="ES">' . __('Spain', 'seur-oficial' ) . '</option>';
									}
							if ( $getrate->country == 'PT' ){
									echo '<option value="PT" selected="selected">' . __('Portugal', 'seur-oficial' ) . '</option>';
								} else {
									echo '<option value="PT">' . __('Portugal', 'seur-oficial' ) . '</option>';
									}
							if ( $getrate->country == 'AD' ){
									echo '<option value="AD" selected="selected">' . __('Andorra', 'seur-oficial' ) . '</option>';
								} else {
									echo '<option value="AD">' . __('Andorra', 'seur-oficial' ) . '</option>';
									}

						    } else {
								echo '<option value="*">' . __( 'All Countries', 'seur-oficial' ) . '</option>';
								$countries = seur_get_countries();
								foreach ($countries as $country => $value ) {

									if ( $getrate->country == $country ){
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
            <td><?php _e('State', 'seur-oficial' ); ?></td>

             <td id="states">
			 	<?php

				 	$country = $getrate->country;

					if( $country && $country != '* '){
						$states = seur_get_countries_states( $country );
					} else {
						$states = false;
					}

					if( $states && $states != '*') {

						echo '<select value="Select" title="' . __('Select State', 'seur-oficial' ) . '" name="state">';
						echo '<option value="*">' . __( 'All States', 'seur-oficial' ) . '</option>';
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
					if ( $country == '*' ) {
						//campo
						echo '<input title="' . __( 'No needed', 'seur-oficial' ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', 'seur-oficial' ) . '" value="*" readonly>';
					} ?>
             </td>

        </tr>

        <tr>
            <td><?php _e('Postcode', 'seur-oficial' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type='text' name='postcode' value='<?php echo $getrate->postcode ?>' class='form-control' placeholder='EX : 08023' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Min Price', 'seur-oficial' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type='text' name='minprice' value='<?php echo $getrate->minprice ?>' class='form-control' placeholder='EX : 0' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Max Price', 'seur-oficial' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type='text' name='maxprice' value='<?php echo $max_price ?>' class='form-control' placeholder='EX : 100' required=""></td>
        </tr>

        <tr>
            <td><?php _e('Rate Price', 'seur-oficial' ); ?></td>

            <td><input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type='text' name='rateprice' value='<?php echo $getrate->rateprice ?>' class='form-control' placeholder='EX : 5' required=""></td>
        </tr>

        <?php wp_nonce_field( 'edit_seur_rate', 'edit_rate_nonce_field' ); ?>

        <tr>
            <td colspan="2"><button type="submit" class="button button-primary btn btn-primary" name="btn-save" id="btn-save">Save this Record</button></td>
        </tr>

    </table>
</form>

<?php } ?>
