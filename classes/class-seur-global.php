<?php
/**
 * Add extra profile fields for users in admin
 *
 * @package  WooCommerce SEUR
 * @version  3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Seur_Global {

	public function __construct() {
		$this->log = new WC_Logger();
	}

	public function get_ownsetting() {

		if ( is_multisite() ) {

			$optionvalue = get_option( 'ownsetting' );

			if ( ! empty( $optionvalue ) ) {
				return $option_value;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function get_multisitesttings() {

		if ( is_multisite() ) {

			switch_to_blog( 1 );

			$$optionvalue = get_option( 'multisitesttings' );

			if ( ! empty( $optionvalue ) ) {
				restore_current_blog();
				return $option_value;
			} else {
				restore_current_blog();
				return false;
			}
		} else {
			restore_current_blog();
			return false;
		}
	}

	public function get_option( $option ) {

		if ( is_multisite() ) {
			if ( 'ownsetting' !== $option ) {
				if ( 'hideownsetting' === $option || 'multisitesttings' === $option ) {

					switch_to_blog( 1 );

					$option_value = get_option( $option );

					if ( ! empty( $option_value ) ) {
						return $option_value;
					} else {
						restore_current_blog();
						return false;
					}
				}
			}

			$multisitesttings = $this->get_multisitesttings();
			$ownsetting       = $this->get_ownsetting();

			if ( 'yes' !== $ownsetting && 'yes' === $multisitesttings ) {
				switch_to_blog( 1 );

				$option_value = get_option( $option );

				if ( ! empty( $option_value ) ) {
					restore_current_blog();
					return $option_value;
				} else {
					restore_current_blog();
					return false;
				}
			} else {
				$option_value = get_option( $option );

				if ( ! empty( $option_value ) ) {
					return $option_value;
				} else {
					return false;
				}
			}
		}

		$option_value = get_option( $option );

		if ( ! empty( $option_value ) ) {
			return $option_value;
		} else {
			return false;
		}
	}

	public function today() {
		return gmdate( 'Ymd' );
	}

	public function save_collection( $collectionref, $type ) {
		update_option( 'seur_save_collection_' . $type, $collectionref );
	}

	public function save_reference( $reference, $type ) {
		update_option( 'seur_save_reference_' . $type, $reference );
	}

	public function save_date_normal( $date ) {
		update_option( 'seur_save_date_normal', $date );
	}

	public function save_date_cold( $date ) {
		update_option( 'seur_save_date_cold', $date );
	}

	public function get_collection( $type ) {
		return get_option( 'seur_save_collection_' . $type );
	}

	public function get_reference( $type ) {
		return get_option( 'seur_save_reference_' . $type );
	}

	public function get_date_normal() {
		return get_option( 'seur_save_date_normal' );
	}

	public function get_date_cold() {
		return get_option( 'seur_save_date_cold' );
	}

	public function is_test() {
		$is_test = $this->get_option( 'seur_test_field' );
		if ( 1 === (int) $is_test ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_api_addres() {
		if ( $this->is_test() ) {
			return SEUR_TEST_API_ADDRESS;
		} else {
			return SEUR_LIVE_API_ADDRESS;
		}
	}

	public function get_token() {
		return $this->get_option( 'seur_api_token' );
	}

	public function get_token_b() {
		$token = 'Bearer ' . $this->get_token();
		return $token;
	}

	public function log_is_acive() {
		$log = $this->get_option( 'seur_log_field' );
		if ( 1 === (int) $log ) {
			return true;
		} else {
			return false;
		}
	}
	public function slog( $text ) {
		$this->log->add( 'seur', $text );
	}

	public function merchant_adress() {
		$adress = $this->get_option( 'seur_vianombre_field' ) . ' ' . $this->get_option( 'seur_vianumero_field' ) . ', ' . $this->get_option( 'seur_escalera_field' ) . ' ' . $this->get_option( 'seur_piso_field' ) . ' ' . $this->get_option( 'seur_puerta_field' );
		return $adress;
	}

	public function merchant_name() {
		$name = $this->get_option( 'seur_contacto_nombre_field' ) . ' ' . $this->get_option( 'seur_contacto_apellidos_field' );
		return $name;
	}

	public function merchant_email() {
		$email = $this->get_option( 'seur_email_field' );
		return $email;
	}

	public function client_secret() {
		$client_secret = $this->get_option( 'seur_client_secret_field' );
		return $client_secret;
	}

	public function client_user_name() {
		$client_user_name = $this->get_option( 'seur_user_field' );
		return $client_user_name;
	}

	public function client_id() {
		$client_id = $this->get_option( 'seur_client_id_field' );
		return $client_id;
	}

	public function client_user_password() {
		$user_password = $this->get_option( 'seur_password_field' );
		return $user_password;
	}
	public function clean( $out ) {
		$replace_map = array(
			'À' => 'A',
			'Ä' => 'A',
			'É' => 'E',
			'È' => 'E',
			'Ë' => 'E',
			'Í' => 'I',
			'Ì' => 'I',
			'Ï' => 'I',
			'Ó' => 'O',
			'Ò' => 'O',
			'Ö' => 'O',
			'Ú' => 'U',
			'Ù' => 'U',
			'Ü' => 'U',
			'á' => 'a',
			'à' => 'a',
			'ä' => 'a',
			'é' => 'e',
			'è' => 'e',
			'ë' => 'e',
			'í' => 'i',
			'ì' => 'i',
			'ï' => 'i',
			'ó' => 'o',
			'ò' => 'o',
			'ö' => 'o',
			'ú' => 'u',
			'ù' => 'u',
			'ü' => 'u',
			'&' => '-',
			'<' => ' ',
			'>' => ' ',
			'/' => ' ',
			'"' => ' ',
			"'" => ' ',
			'?' => ' ',
			'¿' => ' ',
		);
		return strtr( $out, $replace_map );
	}
	public function seur_date( $date ) {

		// in 09-22-2021.
		// out 2021-09-22-12:00:00.00.

		$date_2     = (string) str_replace( '/', '-', $date );
		$year       = (string) substr( $date_2, -4 );
		$month      = (string) substr( $date_2, -10, 2 );
		$day        = (string) substr( $date_2, -7, 2 );
		$final_date = (string) $year . '-' . $month . '-' . $day . '-12:00:00.000';

		if ( seur()->log_is_acive() ) {
			seur()->slog( '$date_2: ' . $date_2 );
			seur()->slog( '$month: ' . $month );
			seur()->slog( '$day: ' . $day );
			seur()->slog( '$year: ' . $year );
			seur()->slog( '$final_date: ' . $final_date );
		}
		return $final_date;
	}
	/**
	 * Get the products
	 *
	 * @return array
	 */
	public function get_products() {
		include_once SEUR_DATA_PATH . 'seur-products.php';
		return get_seur_product();
	}
	/**
	 * Get servicio
	 *
	 * @param string $real_name Service Name.
	 */
	public function get_servicio( $real_name ) {

		$registros = $this->get_products();

		foreach ( $registros as $description => $valor ) {
			if ( $real_name === $description ) {

				$data = array(
					'country' => $valor['pais'],
					'service' => $valor['service'],
					'product' => $valor['product'],
				);
				return $data;
			}
		}
		return false;
	}
}

function seur() {
	return new Seur_Global();
}
