<?php
/**
 * SEUR Country State Process
 *
 * @package SEUR
 */

/**
 * SEUR Country State Process
 */
function seur_country_state_process() {

    $products = seur()->get_products();
	if ( isset( $_POST['rate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$rate = sanitize_text_field( wp_unslash( $_POST['rate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

        $countries = $products[$rate]['pais'];
        $options = seur()->getCountries($countries);
        echo '<select class="select country" id="country" title="' . esc_html__( 'Select Country', 'seur' ) . '" name="country">';
        echo '<option value="NULL">' . esc_html__( 'Select', 'seur' ) . '</option>';
        if (count($countries)==1 && $countries[0] !=='ES') {
            echo '<option value="*">' . esc_html__( 'All Countries', 'seur' ) . '</option>';
        }
        foreach ($options as $code => $country) {
            echo '<option value="'.$code.'">' . esc_html__( $country, 'seur' ) . '</option>';
        }
        echo '</select>';
		//set_transient( get_current_user_id() . '_seur_rate', $rate );
	}
	if ( isset( $_POST['country'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		// Capture selected country.
		$country = sanitize_text_field( wp_unslash( $_POST['country'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$rate    = get_transient( get_current_user_id() . '_seur_rate' );

        $states = $products[$rate]['provincia'];
        $options = seur()->getStates($country, $states);
        if ($options) {
            echo '<select title="' . esc_html__( 'Select State', 'seur' ) . '" name="state">';
            if (count($states)==1 && $states[0] === 'all' && $country !=='Select' && $country !== 'NULL' ) {
                echo '<option value="*">' . esc_html__( 'All States', 'seur' ) . '</option>';
            }
            foreach ( $options as $state => $value ) {
                echo '<option value="' . esc_html( $state ) . '">' . esc_html( $value ) . '</option>';
            }
            echo '</select>';
        } else {
            $title = 'Type State';
            $placeholder = 'EX : State';
            $value = 'required=""';
            if ( '*' === $country) {
                $title = 'No needed';
                $placeholder = 'No needed';
                $value = 'value="*" readonly';
            }
            echo '<input title="' . esc_html__( $title, 'seur' ) . '" type="text" name="state" 
                 class="form-control" placeholder="' . esc_html__( $placeholder , 'seur' ) . '" '.$value.'
                 >';
        }
		unset( $country );
	}
    die;
}
