<?php
function seur_country_state_process() {

	if ( isset( $_POST[ "rate" ] ) ) {

		$rate = $_POST[ "rate" ];

		if ( $rate == 'B2C Estándar' || $rate == 'SEUR 13:30 Estándar' || $rate == 'SEUR 10 Estándar' || $rate == 'SEUR 10 Frío' || $rate == 'SEUR 13:30 Frío' ){
			echo '<select class="select country" id="country" title="' . __('Select Country', 'seur-oficial' ) . '" name="country">';
			echo '<option value="AD">' . __('Andorra', 'seur-oficial' ) . '</option>';
			echo '<option value="PT">' . __('Portugal', 'seur-oficial' ) . '</option>';
			echo '<option value="ES">' . __('Spain', 'seur-oficial' ) . '</option>';
			echo '</select>';

			} elseif ( $rate == 'SEUR 72H Estándar' || $rate == 'SEUR 48H Estándar' ) {

				echo '<select class="select country" id="country" title="' . __('Select Country', 'seur-oficial' ) . '" name="country">';
				echo '<option value="NULL">' . __('Select Country', 'seur-oficial' ) . '</option>';
				echo '<option value="ES">' . __('Spain', 'seur-oficial' ) . '</option>';
				echo '</select>';

			} else { ?>
				<select class="select country" id="country" title="<?php _e('Select Country', 'seur-oficial' ); ?>" name="country">
				    <?php
						echo '<option value="NULL">' . __( 'Select', 'seur-oficial' ) . '</option>';
						echo '<option value="*">' . __( 'All Countries', 'seur-oficial' ) . '</option>';
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
			        echo '<select title="' . __( 'Select State', 'seur-oficial' ) . '" name="state">';
			        echo '<option value="*">' . __('All States', 'seur-oficial' ) . '</option>';
			        foreach( $countryArr as $state => $value ){

			            echo '<option value="' . $state . '">' . $value . '</option>';
			        }
			        echo "</select>";
			   }
		   }
	    }

	    if ( $rate == 'SEUR 48H Estándar' ) {
		    echo '<select title="' . __( 'Select State', 'seur-oficial' ) . '" name="state">';
			echo '<option value="PM">' . __('Baleares', 'seur-oficial' ) . '</option>';
			echo '<option value="CE">' . __('Ceuta', 'seur-oficial' ) . '</option>';
			echo '<option value="ML">' . __('Melilla', 'seur-oficial' ) . '</option>';
			echo '</select>';
	    }

	    if ( $rate == 'SEUR 72H Estándar' ) {
		    echo '<select title="' . __( 'Select State', 'seur-oficial' ) . '" name="state">';
			echo '<option value="TF">' . __('Las Palmas', 'seur-oficial' ) . '</option>';
			echo '<option value="GC">' . __('Santa Cruz de Tenerife', 'seur-oficial' ) . '</option>';
			echo '</select>';
	    }

	    if ( $country == '*' && ! $countryArr ) {
		    //campo
		    echo '<input title="' . __( 'No needed', 'seur-oficial' ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', 'seur-oficial' ) . '" value="*" readonly>';
	    	}
	    elseif ( $country !== '*' && ! $countryArr ) {
		    echo '<input title="' . __('SEUR field description', 'seur-oficial' ) . '" type="text" name="state" class="form-control" placeholder="' . __('EX : State', 'seur-oficial' ) . '" required="">';
	    }
	    delete_transient( get_current_user_id() . '_seur_rate' );
	}
}
?>