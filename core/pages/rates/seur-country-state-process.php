<?php
function seur_country_state_process() {

	if( isset( $_POST[ "rate" ] ) ) {

		$rate = $_POST[ "rate" ];

		if ( $rate == 'PARTICULARES 24H ESTANDAR' ){
			echo '<select class="select country" id="country" title="' . __('Select Country', SEUR_TEXTDOMAIN ) . '" name="country">';
			echo '<option value="Select">' . __('Select a Country', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="ES">Spain</option>';
			echo '</select>';

			} else { ?>
				<select class="select country" id="country" title="<?php _e('Select Country', SEUR_TEXTDOMAIN ); ?>" name="country">
				    <?php
						echo '<option value="Select">' . __('Select a Country', SEUR_TEXTDOMAIN ) . '</option>';
						echo '<option value="*">' . __( 'All Countries', SEUR_TEXTDOMAIN ) . '</option>';
	                    $countries = seur_get_countries();
						foreach ($countries as $countrie => $value )
							{
								echo '<option value="' . $countrie  . '">' . $value . '</option>';
							}
						echo '<option value="REST">' . __( 'Rest of the World', SEUR_TEXTDOMAIN ) . '</option>';
					?>
				</select>
				<?php

			}

		}


	/*

	<select class="select country" id="country" title="<?php _e('Select Country', SEUR_TEXTDOMAIN ); ?>" name="country">
				    <?php
						echo '<option value="Select">' . __('Select a Country', SEUR_TEXTDOMAIN ) . '</option>';
						echo '<option value="*">' . __( 'All Countries', SEUR_TEXTDOMAIN ) . '</option>';
	                    $countries = seur_get_countries();
						foreach ($countries as $countrie => $value )
							{
								echo '<option value="' . $countrie  . '">' . $value . '</option>';
							}
						echo '<option value="REST">' . __( 'Rest of the World', SEUR_TEXTDOMAIN ) . '</option>';
					?>
				</select>

	*/

	if( isset( $_POST[ "country" ] ) ) {
	    // Capture selected country
	    $country = $_POST[ "country" ];

	    // Define country and city array
	    $countryArr = seur_get_countries_states( $country );

	    if( $countryArr ){
	    // Display city dropdown based on country name
		    if( $country !== 'Select' ) {
		        echo '<select title="' . __( 'Select State', SEUR_TEXTDOMAIN ) . '" name="state">';
		        echo '<option value="*">' . __('All States', SEUR_TEXTDOMAIN ) . '</option>';
		        foreach( $countryArr as $state => $value ){

		            echo '<option value="' . $state . '">' . $value . '</option>';
		        }
		        echo "</select>";
		    }
	    }
	    if ( $country == '*' && ! $countryArr ) {
		    //campo
		    echo '<input title="' . __( 'No needed', SEUR_TEXTDOMAIN ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', SEUR_TEXTDOMAIN ) . '" value="*" readonly>';
	    	}
	    elseif ( $country !== '*' && ! $countryArr ) {
		    echo '<input title="' . __('SEUR field description', SEUR_TEXTDOMAIN ) . '" type="text" name="state" class="form-control" placeholder="' . __('EX : State', SEUR_TEXTDOMAIN ) . '" required="">';
	    }
	}
}
?>