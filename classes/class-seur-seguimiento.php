<?php
/**
 * Class Seur Pedidos Salida
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class Seur Pedidos Salida
 */
class Seur_Logistica_Seguimiento {

	public function __construct() {

		$this->client_secret       = seur()->client_secret();
		$this->accountnumber       = seur()->get_option( 'seur_accountnumber_field' );
		$this->nif                 = seur()->get_option( 'seur_nif_field' );
		$this->phone               = seur()->get_option( 'seur_telefono_field' );
		$this->name                = seur()->merchant_name();
		$this->email               = seur()->merchant_email();
		$this->streetname          = seur()->merchant_adress();
		$this->cityname            = seur()->get_option( 'seur_poblacion_field' );
		$this->postalcode          = seur()->get_option( 'seur_postal_field' );
		$this->country             = seur()->get_option( 'seur_pais_field' );
		$this->seur_adr            = seur()->get_api_addres() . 'pic/v1/tracking-services/simplified';
		$this->token               = seur()->get_token_b();
		$this->reftype             = 'REFERENCE';
		$this->id_number           = seur()->get_option( 'seur_nif_field' );
		$this->full_account_number = seur()->get_option( 'seur_accountnumber_field' );
		$this->accoun_number       = substr( $this->full_account_number, 0, strpos( $this->full_account_number, '-' ) );
		$this->business_unit       = seur()->get_option( 'seur_franquicia_field' );
	}

	public function data_traking( $ref ) {

		$data = wp_json_encode(
			array(
				'ref'           => $ref,
				'refType'       => $this->reftype,
				'idNumber'      => $this->id_number,
				'accountNumber' => $this->accoun_number,
				'businessUnit'  => $this->business_unit,
			)
		);
		if ( seur()->log_is_acive() ) {
			seur()->slog( '$data: ' . print_r( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
		return $data;
	}



	/**
	 * Save meta box content.
	 *
	 * @param string $order_id Order ID.
	 * @param string $tracking_id Tracking ID.
	 */
	public function tracking_remote_post( $ref ) {

		if ( $ref ) {

			$content = array(
				'method'      => 'GET',
				'timeout'     => 45,
				'httpversion' => '1.0',
				'user-agent'  => 'WooCommerce',
				'headers'     => array(
					'Content-Type'  => 'application/json;charset=UTF-8',
					'Accept'        => 'application/json',
					'Authorization' => seur()->get_token_b(),
				),
			);
			if ( seur()->log_is_acive() ) {
				seur()->slog( '$content: ' . print_r( $content, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
			$url_call      = $this->seur_adr . '?ref=' . $ref . '&refType=REFERENCE&idNumber=' . $this->id_number . '&accountNumber=' . $this->accoun_number . '&businessUnit=' . $this->business_unit;
			$response      = wp_remote_get(
				$url_call,
				$content
			);
			$response_body = wp_remote_retrieve_body( $response );
			$result        = json_decode( $response_body );
			$description   = $result->data[0]->description;
		} else {
			$response_body = array( false );
			$description        = false;
		}
		if ( seur()->log_is_acive() ) {
			seur()->slog( '$response_body: ' . print_r( $response_body, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			seur()->slog( '$result: ' . print_r( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			seur()->slog( 'SEUR URL C: ' . $url_call );
			seur()->slog( 'description: ' . $result->data[0]->description );
		}
		return $description;
	}
}
/**
 * SEUR pedidos salida
 *
 * @param string $order_id Order ID.
 */

function seur_tracking( $tracking_number ) {
	$tracking = new Seur_Logistica_Seguimiento();
	$result   = $tracking->tracking_remote_post( $tracking_number );
	return $result;
}
