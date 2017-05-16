<?php
function seur_country_state_process() {

	if ( isset( $_POST[ "rate" ] ) ) {

		$rate = $_POST[ "rate" ];

		if ( $rate == 'B2C Estándar' || $rate == 'SEUR 13:30 Estándar' || $rate == 'SEUR 10 Estándar' || $rate == 'SEUR 10 Frío' || $rate == 'SEUR 13:30 Frío' ){
			echo '<select class="select country" id="country" title="' . __('Select Country', SEUR_TEXTDOMAIN ) . '" name="country">';
			echo '<option value="AD">' . __('Andorra', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="PT">' . __('Portugal', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="ES">' . __('Spain', SEUR_TEXTDOMAIN ) . '</option>';
			echo '</select>';

			} elseif ( $rate == 'SEUR 72H Estándar' || $rate == 'SEUR 48H Estándar' ) {

				echo '<select class="select country" id="country" title="' . __('Select Country', SEUR_TEXTDOMAIN ) . '" name="country">';
				echo '<option value="NULL">' . __('Select Country', SEUR_TEXTDOMAIN ) . '</option>';
				echo '<option value="ES">' . __('Spain', SEUR_TEXTDOMAIN ) . '</option>';
				echo '</select>';

			} else { ?>
				<select class="select country" id="country" title="<?php _e('Select Country', SEUR_TEXTDOMAIN ); ?>" name="country">
				    <?php
						echo '<option value="NULL">' . __( 'Select', SEUR_TEXTDOMAIN ) . '</option>';
						echo '<option value="*">' . __( 'All Countries', SEUR_TEXTDOMAIN ) . '</option>';
	                    $countries = seur_get_countries();
						foreach ($countries as $countrie => $value )
							{
								echo '<option value="' . $countrie  . '">' . $value . '</option>';
							}
					?>
				</select>
				<?php

			}
			set_transient( get_current_user_id() . '_seur_rate', $rate );

		}

	if ( isset( $_POST[ "country" ] ) ) {
	    // Capture selected country
	    $country = $_POST[ "country" ];
	    $rate = get_transient( get_current_user_id() . '_seur_rate' );

	    // Define country and city array
	    $countryArr = seur_get_countries_states( $country );

	    if ( $rate == 'B2C Estándar' || $rate == 'SEUR 13:30 Estándar' || $rate == 'SEUR 10 Estándar' || $rate == 'SEUR 10 Frío' || $rate == 'SEUR 13:30 Frío' ) {

		    if( $countryArr ){
		    // Display city dropdown based on country name
			   if( $country !== 'Select' && $country !== 'NULL' ) {
			        echo '<select title="' . __( 'Select State', SEUR_TEXTDOMAIN ) . '" name="state">';
			        echo '<option value="*">' . __('All States', SEUR_TEXTDOMAIN ) . '</option>';
			        foreach( $countryArr as $state => $value ){

			            echo '<option value="' . $state . '">' . $value . '</option>';
			        }
			        echo "</select>";
			   }
		   }
	    }

	    if ( $rate == 'SEUR 48H Estándar' ) {
		    echo '<select title="' . __( 'Select State', SEUR_TEXTDOMAIN ) . '" name="state">';
			echo '<option value="PM">' . __('Baleares', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="CE">' . __('Ceuta', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="ML">' . __('Melilla', SEUR_TEXTDOMAIN ) . '</option>';
			echo '</select>';
	    }

	    if ( $rate == 'SEUR 72H Estándar' ) {
		    echo '<select title="' . __( 'Select State', SEUR_TEXTDOMAIN ) . '" name="state">';
			echo '<option value="TF">' . __('Las Palmas', SEUR_TEXTDOMAIN ) . '</option>';
			echo '<option value="GC">' . __('Santa Cruz de Tenerife', SEUR_TEXTDOMAIN ) . '</option>';
			echo '</select>';
	    }

	    if ( $country == '*' && ! $countryArr ) {
		    //campo
		    echo '<input title="' . __( 'No needed', SEUR_TEXTDOMAIN ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', SEUR_TEXTDOMAIN ) . '" value="*" readonly>';
	    	}
	    elseif ( $country !== '*' && ! $countryArr ) {
		    echo '<input title="' . __('SEUR field description', SEUR_TEXTDOMAIN ) . '" type="text" name="state" class="form-control" placeholder="' . __('EX : State', SEUR_TEXTDOMAIN ) . '" required="">';
	    }
	    delete_transient( get_current_user_id() . '_seur_rate' );
	}
}
?>