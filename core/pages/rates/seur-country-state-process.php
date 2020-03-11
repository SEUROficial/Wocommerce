<?php
function seur_country_state_process() {

	if ( isset( $_POST[ "rate" ] ) ) {

		$rate = $_POST[ "rate" ];

		if ( $rate === 'B2C Estándar' || $rate === 'SEUR 13:30 Estándar' || $rate === 'SEUR 10 Estándar' || $rate === 'SEUR 10 Frío' ) {
			echo '<select class="select country" id="country" title="' . __('Select Country', 'seur' ) . '" name="country">';
			echo '<option value="NULL">' . __( 'Select', 'seur' ) . '</option>';
			echo '<option value="AD">' . __('Andorra', 'seur' ) . '</option>';
			echo '<option value="PT">' . __('Portugal', 'seur' ) . '</option>';
			echo '<option value="ES">' . __('Spain', 'seur' ) . '</option>';
			echo '</select>';

		} elseif ( $rate === 'SEUR 13:30 Frío' ) {
			echo '<select class="select country" id="country" title="' . __('Select Country', 'seur' ) . '" name="country">';
			echo '<option value="NULL">' . __( 'Select', 'seur' ) . '</option>';
			echo '<option value="AD">' . __('Andorra', 'seur' ) . '</option>';
			echo '<option value="FR">' . __('France', 'seur' ) . '</option>';
			echo '<option value="PT">' . __('Portugal', 'seur' ) . '</option>';
			echo '<option value="ES">' . __('Spain', 'seur' ) . '</option>';
			echo '</select>';

		} elseif ( $rate === 'SEUR 72H Estándar' || $rate == 'SEUR 48H Estándar' ) {

				echo '<select class="select country" id="country" title="' . __('Select Country', 'seur' ) . '" name="country">';
				echo '<option value="NULL">' . __('Select Country', 'seur' ) . '</option>';
				echo '<option value="ES">' . __('Spain', 'seur' ) . '</option>';
				echo '</select>';

			} else { ?>
				<select class="select country" id="country" title="<?php _e('Select Country', 'seur' ); ?>" name="country">
				    <?php
						echo '<option value="NULL">' . __( 'Select', 'seur' ) . '</option>';
						echo '<option value="*">' . __( 'All Countries', 'seur' ) . '</option>';
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
			
			if ( $countryArr ) {
				// Display city dropdown based on country name
				if ( $country !== 'Select' && $country !== 'NULL' ) {
					echo '<select title="' . __( 'Select State', 'seur' ) . '" name="state">';
					echo '<option value="*">' . __('All States', 'seur' ) . '</option>';
					foreach( $countryArr as $state => $value ){
						echo '<option value="' . $state . '">' . $value . '</option>';
					}
					echo "</select>";
				}
			} else {
				echo '<input title="' . __('Type State', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . __('EX : State', 'seur' ) . '" required="">';
			}
		}
		if ( $rate == 'SEUR 48H Estándar' ) {
			echo '<select title="' . __( 'Select State', 'seur' ) . '" name="state">';
			echo '<option value="PM">' . __('Baleares', 'seur' ) . '</option>';
			echo '<option value="CE">' . __('Ceuta', 'seur' ) . '</option>';
			echo '<option value="ML">' . __('Melilla', 'seur' ) . '</option>';
			echo '</select>';
		}
		
		if ( $rate == 'SEUR 72H Estándar' ) {
			echo '<select title="' . __( 'Select State', 'seur' ) . '" name="state">';
			echo '<option value="TF">' . __('Las Palmas', 'seur' ) . '</option>';
			echo '<option value="GC">' . __('Santa Cruz de Tenerife', 'seur' ) . '</option>';
			echo '</select>';
		}
		
		if ( $rate == 'Classic Internacional Terrestre'|| $rate == 'COURIER INT AEREO PAQUETERIA' || $rate == 'COURIER INT AEREO DOCUMENTOS' || $rate == 'NETEXPRESS INT TERRESTRE' || $rate == 'SEUR 2SHOP' ) {
			if ( $countryArr ) {
				
				// Display city dropdown based on country name
				if ( $country !== 'Select' && $country !== 'NULL' ) {
					echo '<select title="' . __( 'Select State', 'seur' ) . '" name="state">';
					echo '<option value="*">' . __('All States', 'seur' ) . '</option>';
					foreach( $countryArr as $state => $value ) {
						echo '<option value="' . $state . '">' . $value . '</option>';
					}
					echo "</select>";
				}
			}
			if ( $country == '*' && ! $countryArr ) {
				//campo
				echo '<input title="' . __( 'No needed', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . __( 'No needed', 'seur' ) . '" value="*" readonly>';
			}
			if ( $country !== '*' && ! $countryArr ) {
				echo '<input title="' . __('SEUR field description', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . __('EX : State', 'seur' ) . '" required="">';
			}
		}
		unset( $country );
		//delete_transient( get_current_user_id() . '_seur_rate' );
	}
}
?>