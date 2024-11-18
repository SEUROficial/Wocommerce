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

class Seur_Collections {

    private string $seur_adr;
    /**
     * @var false|mixed
     */
    private mixed $client_secret;
    /**
     * @var false|mixed
     */
    private mixed $accountnumber;
    /**
     * @var false|mixed
     */
    private mixed $nif;
    /**
     * @var false|mixed
     */
    private mixed $phone;
    private string $name;
    /**
     * @var false|mixed
     */
    private mixed $email;
    private string $streetname;
    /**
     * @var false|mixed
     */
    private mixed $cityname;
    /**
     * @var false|mixed
     */
    private mixed $postalcode;
    /**
     * @var false|mixed
     */
    private mixed $country;
    private string $token;

    public function __construct() {
		$this->seur_adr      = seur()->get_api_addres() . SEUR_COLLECTIONS;
		$this->client_secret = seur()->client_secret();
		$this->accountnumber = seur()->get_option( 'seur_accountnumber_field' );
		$this->nif           = seur()->get_option( 'seur_nif_field' );
		$this->phone         = seur()->get_option( 'seur_telefono_field' );
		$this->name          = seur()->merchant_name();
		$this->email         = seur()->merchant_email();
		$this->streetname    = seur()->merchant_adress();
		$this->cityname      = seur()->get_option( 'seur_poblacion_field' );
		$this->postalcode    = seur()->get_option( 'seur_postal_field' );
		$this->country       = seur()->get_option( 'seur_pais_field' );
		$this->token         = seur()->get_token_b();
	}

	public function data_collection( $data ) {

		if (
			$this->seur_adr &&
			$this->client_secret &&
			$this->accountnumber &&
			$this->nif &&
			$this->phone &&
			$this->name &&
			$this->email &&
			$this->streetname &&
			$this->cityname &&
			$this->postalcode &&
			$this->country &&
			$this->token
		) {
			$type    = $data['type']; // cold, normal.
			$date    = $data['date']; // '2021-09-08',
			$mfrom   = $data['mfrom']; // '09:00:00'
			$mto     = $data['mto']; // '13:00:00'
			$efrom   = $data['efrom']; // '16:00:00'
			$eto     = $data['eto']; // '19:00:00'
			$comment = $data['comment']; // 'ENVIO DE PRUEBA'
			$ref     = $data['ref'];

			if ( 'none' !== $mfrom && 'none' !== $mto && 'none' !== $efrom && 'none' !== $eto ) {
				$restrictions = array(
					'scheduleMorningTimeSlotFrom' => $mfrom,
					'scheduleMorningTimeSlotTo'   => $mto,
					'scheduleEveningTimeSlotFrom' => $efrom,
					'scheduleEveningTimeSlotTo'   => $eto,
				);
			}
			if ( ( 'none' === $mfrom || 'none' === $mto ) && ( 'none' !== $efrom && 'none' !== $eto ) ) {
				$restrictions = array(
					'scheduleEveningTimeSlotFrom' => $efrom,
					'scheduleEveningTimeSlotTo'   => $eto,
				);
			}
			if ( ( 'none' !== $mfrom && 'none' !== $mto ) && ( 'none' === $efrom && 'none' === $eto ) ) {
				$restrictions = array(
					'scheduleMorningTimeSlotFrom' => $mfrom,
					'scheduleMorningTimeSlotTo'   => $mto,
				);
			}
			if ( seur()->log_is_acive() ) {
				seur()->slog( '$type: ' . $type );
				seur()->slog( '$date: ' . $date );
				seur()->slog( '$mfrom: ' . $mfrom );
				seur()->slog( '$mto: ' . $mto );
				seur()->slog( '$efrom: ' . $efrom );
				seur()->slog( '$eto: ' . $eto );
				seur()->slog( '$comment: ' . $comment );
				seur()->slog( '$ref: ' . $ref );
				seur()->slog( 'streetName: ' . seur()->clean( seur()->merchant_adress() ) );
			}

			if ( 'normal' === $type ) {
				$service_code = 1;
				$product_code = 2;
			} else {
				$service_code = 9;
				$product_code = 18;
			}

			$data = wp_json_encode(
				array(
					'serviceCode'         => $service_code,
					'productCode'         => $product_code,
					'taric'               => 1,
					'incoTerms'           => 1,
					'customsGoodsCode'    => 'C',
					'driverLocation'      => true,
					'ref'                 => $ref,
					'collectionDate'      => $date,
					'label'               => false,
					'payer'               => 'ORD',
					'customer'            => array(
						'accountNumber' => seur()->get_option( 'seur_accountnumber_field' ),
						'name'          => seur()->clean( seur()->merchant_name() ), // 'John Doe',
						'idNumber'      => seur()->get_option( 'seur_nif_field' ),
						'phone'         => seur()->get_option( 'seur_telefono_field' ), // '645875428',
						'email'         => seur()->merchant_email(), // 'j.conti@joseconti.com',
					),
					'sender'              => array(
						'name'        => seur()->clean( seur()->merchant_name() ), // 'John Doe',
						'email'       => seur()->merchant_email(), // 'j.conti@joseconti.com',
						'contactName' => seur()->clean( seur()->merchant_name() ), // 'John Doe',
						'address'     => array(
							'streetName' => seur()->clean( seur()->merchant_adress() ), // 'Nicolas Salmeron',
							'cityName'   => seur()->clean( seur()->get_option( 'seur_poblacion_field' ) ), // 'Valladolid',
							'postalCode' => seur()->get_option( 'seur_postal_field' ), // '47004',
							'country'    => seur()->get_option( 'seur_pais_field' ), // 'ES',
						),
					),
					'observations'        => $comment,
					'restrictions'        => array(
						$restrictions,
					),
					'insuredValue'        => '0',
					'cashOnDeliveryValue' => '0',
				)
			);
			if ( seur()->log_is_acive() ) {
				seur()->slog( '$data: ' . print_r( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
			return $data;
		} else {
			return false;
		}
	}

	public function collection_remote_post( $data ) {

		$body = self::data_collection( $data );
		$date = seur()->today();

		if ( $body ) {

			$content = array(
				'method'      => 'POST',
				'timeout'     => 45,
				'httpversion' => '1.0',
				'user-agent'  => 'WooCommerce - Seur '.SEUR_OFFICIAL_VERSION,
				'headers'     => array(
					'Content-Type'  => 'application/json;charset=UTF-8',
					'Accept'        => 'application/json',
					'Authorization' => seur()->get_token_b(),
				),
				'body'        => $body,
			);
			if ( seur()->log_is_acive() ) {
				seur()->slog( '$content: ' . print_r( $content, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}

			$response      = wp_remote_post(
				seur()->get_api_addres() . SEUR_COLLECTIONS,
				$content
			);
			$response_body = wp_remote_retrieve_body( $response );
			$result        = json_decode( wp_json_encode( $response_body ), true );
		} else {
			$response_body = array( false );
			$result        = false;
		}
		if ( seur()->log_is_acive() ) {
			seur()->slog( '$response_body: ' . print_r( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		}
		return json_decode( $result, true );
	}

	/**
	 * Cancelar una recogida
	 *
	 * @param string $reference - La referencia de la recogida a cancelar
	 * @return mixed|false
	 */
	public function cancel_collection( $reference ) {
		// URL de la API para cancelar la recogida
		$url_cancel = $this->seur_adr . '/cancel';

		// Preparar los datos de la solicitud de cancelación
		$data = wp_json_encode( array( 'codes' => array( $reference ) ) );

		// Encabezados de la solicitud
		$headers = array(
			'Content-Type'  => 'application/json;charset=UTF-8',
			'Accept'        => 'application/json',
			'Authorization' => seur()->get_token_b(),
		);

		if ( seur()->log_is_acive() ) {
			seur()->slog( 'Cancelando recogida con referencia: ' . $reference );
			seur()->slog( 'Data enviada: ' . print_r( $data, true ) );
		}

		// Configuración de la solicitud
		$args = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'httpversion' => '1.0',
            'user-agent'  => 'WooCommerce - Seur '.SEUR_OFFICIAL_VERSION,
			'headers'     => $headers,
			'body'        => $data,
		);

		// Enviar la solicitud a la API
		$response      = wp_remote_post(
			$url_cancel,
			$args
		);
		$response_body = wp_remote_retrieve_body( $response );
		$result        = json_decode( wp_json_encode( $response_body ), true );
		return json_decode( $result, true );

	}
}
function seur_collections( $data ) {
	return ( new Seur_Collections() )->collection_remote_post( $data );
}

function seur_cancel_collection( $reference ) {
	return ( new Seur_Collections() )->cancel_collection( $reference );
}

