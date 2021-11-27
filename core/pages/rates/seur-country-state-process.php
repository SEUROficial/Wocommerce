<?php
function seur_country_state_process() {

	if ( isset( $_POST['rate'] ) ) {

		$rate = $_POST['rate'];

		if ( $rate === 'B2C Estándar' || $rate === 'SEUR 13:30 Estándar' || $rate === 'SEUR 10 Estándar' || $rate === 'SEUR 10 Frío' || $rate === 'SEUR 2SHOP' ) {
			echo '<select class="select country" id="country" title="' . esc_html__( 'Select Country', 'seur' ) . '" name="country">';
			echo '<option value="NULL">' . esc_html__( 'Select', 'seur' ) . '</option>';
			echo '<option value="AD">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
			echo '<option value="PT">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
			echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
			echo '</select>';

		} elseif ( $rate === 'SEUR 13:30 Frío' ) {
			echo '<select class="select country" id="country" title="' . esc_html__( 'Select Country', 'seur' ) . '" name="country">';
			echo '<option value="NULL">' . esc_html__( 'Select', 'seur' ) . '</option>';
			echo '<option value="AD">' . esc_html__( 'Andorra', 'seur' ) . '</option>';
			echo '<option value="FR">' . esc_html__( 'France', 'seur' ) . '</option>';
			echo '<option value="PT">' . esc_html__( 'Portugal', 'seur' ) . '</option>';
			echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
			echo '</select>';

		} elseif ( $rate === 'SEUR 72H Estándar' || $rate == 'SEUR 48H Estándar' ) {

				echo '<select class="select country" id="country" title="' . esc_html__( 'Select Country', 'seur' ) . '" name="country">';
				echo '<option value="NULL">' . esc_html__( 'Select Country', 'seur' ) . '</option>';
				echo '<option value="ES">' . esc_html__( 'Spain', 'seur' ) . '</option>';
				echo '</select>';

		} else { ?>
				<select class="select country" id="country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="country">
					<?php
					echo '<option value="NULL">' . esc_html__( 'Select', 'seur' ) . '</option>';
					echo '<option value="*">' . esc_html__( 'All Countries', 'seur' ) . '</option>';
					$countries = seur_get_countries();
					foreach ( $countries as $countrie => $value ) {
							echo '<option value="' . esc_html( $countrie ) . '">' . esc_html( $value ) . '</option>';
					}
					?>
				</select>
				<?php

		}
			set_transient( get_current_user_id() . '_seur_rate', $rate );

	}

	if ( isset( $_POST['country'] ) ) {
		// Capture selected country.
		$country = $_POST['country'];
		$rate    = get_transient( get_current_user_id() . '_seur_rate' );

		// Define country and city array.
		$countryArr = seur_get_countries_states( $country );
		if ( $rate == 'B2C Estándar' || $rate == 'SEUR 13:30 Estándar' || $rate == 'SEUR 10 Estándar' || $rate == 'SEUR 10 Frío' || $rate == 'SEUR 13:30 Frío' ) {

			if ( $countryArr ) {
				// Display city dropdown based on country name.
				if ( $country !== 'Select' && $country !== 'NULL' ) {
					echo '<select title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
					echo '<option value="*">' . esc_html__( 'All States', 'seur' ) . '</option>';
					foreach ( $countryArr as $state => $value ) {
						echo '<option value="' . esc_html( $state ) . '">' . esc_html( $value ) . '</option>';
					}
					echo '</select>';
				}
			} else {
				echo '<input title="' . esc_html__( 'Type State', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . esc_html__( 'EX : State', 'seur' ) . '" required="">';
			}
		}
		if ( $rate == 'SEUR 48H Estándar' ) {
			echo '<select title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
			echo '<option value="PM">' . esc_html__( 'Baleares', 'seur' ) . '</option>';
			echo '<option value="CE">' . esc_html__( 'Ceuta', 'seur' ) . '</option>';
			echo '<option value="ML">' . esc_html__( 'Melilla', 'seur' ) . '</option>';
			echo '</select>';
		}

		if ( $rate == 'SEUR 72H Estándar' ) {
			echo '<select title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
			echo '<option value="TF">' . esc_html__( 'Las Palmas', 'seur' ) . '</option>';
			echo '<option value="GC">' . esc_html__( 'Santa Cruz de Tenerife', 'seur' ) . '</option>';
			echo '</select>';
		}

		if ( $rate == 'Classic Internacional Terrestre' || $rate == 'COURIER INT AEREO PAQUETERIA' || $rate == 'COURIER INT AEREO DOCUMENTOS' || $rate == 'NETEXPRESS INT TERRESTRE' || $rate == 'SEUR 2SHOP' ) {
			if ( $countryArr ) {

				// Display city dropdown based on country name.
				if ( $country !== 'Select' && $country !== 'NULL' ) {
					echo '<select title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
					echo '<option value="*">' . esc_html__( 'All States', 'seur' ) . '</option>';
					foreach ( $countryArr as $state => $value ) {
						echo '<option value="' . esc_html( $state ) . '">' . esc_html( $value ) . '</option>';
					}
					echo '</select>';
				}
			}
			if ( $country == '*' && ! $countryArr ) {
				// campo.
				echo '<input title="' . esc_html__( 'No needed', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . esc_html__( 'No needed', 'seur' ) . '" value="*" readonly>';
			}
			if ( $country !== '*' && ! $countryArr ) {
				echo '<input title="' . esc_html__( 'SEUR field description', 'seur' ) . '" type="text" name="state" class="form-control" placeholder="' . esc_html__( 'EX : State', 'seur' ) . '" required="">';
			}
		}
		unset( $country );
		// delete_transient( get_current_user_id() . '_seur_rate' );.
	}
}
?>
