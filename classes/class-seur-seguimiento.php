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

    const tracking_statuses_arr = [
        'wc-seur-in-transit' => 'EN TRÁNSITO',
        'wc-seur-return-in-progress' => 'DEVOLUCIÓN EN CURSO',
        'wc-seur-contribute-solution' => 'APORTAR SOLUCIÓN',
        'wc-seur-incidence' => 'INCIDENCIA',
        'wc-seur-available-in-store' => 'DISPONIBLE PARA RECOGER EN TIENDA',
        'wc-seur-delivered' => 'ENTREGADO',
        'wc-seur-corrected-doc' => 'DOCUMENTACIÓN RECTIFICADA',
    ];

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
		$this->seur_adr            = seur()->get_api_addres() . SEUR_API_TRACKING;
		$this->token               = seur()->get_token_b();
		$this->reftype             = 'REFERENCE';
		$this->full_account_number = seur()->get_option( 'seur_accountnumber_field' );
		$this->accoun_number       = substr( $this->full_account_number, 0, strpos( $this->full_account_number, '-' ) );
		$this->business_unit       = seur()->get_option( 'seur_franquicia_field' );
	}

	public function data_traking( $ref ) {

		$data = wp_json_encode(
			array(
				'ref'           => $ref,
				'refType'       => $this->reftype,
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
     * Call to SEUR API
     *
     * @param $label_id
     * @return string|void
     */
	public function tracking_remote_post( $label_id ) {
        $response = [
            'eventCode' => '',
            'description' => ''
        ];

        $response_body = '';
        $ref = get_post_meta( $label_id, '_seur_shipping_id_number', true);

        $url_call      = $this->seur_adr . '?ref=' . $ref . '&refType=REFERENCE&idNumber=' . $this->id_number .
            '&accountNumber=' . $this->accoun_number . '&businessUnit=' . $this->business_unit;

		if ( $ref ) {
            $content = array(
                'method' => 'GET',
                'timeout' => 45,
                'httpversion' => '1.0',
                'user-agent' => 'WooCommerce',
                'headers' => array(
                    'Content-Type' => 'application/json;charset=UTF-8',
                    'Accept' => 'application/json',
                    'Authorization' => seur()->get_token_b(),
                ),
            );
            if (seur()->log_is_acive()) {
                seur()->slog('$content: ' . print_r($content, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
            }

            $response_wp = wp_remote_get(
                $url_call,
                $content
            );

            $response_body = wp_remote_retrieve_body($response_wp);
            if (!empty($response_body)) {
                $result = json_decode($response_body, true);
                $response = $result['data'][0];
            }

        }
		if ( seur()->log_is_acive() ) {
            seur()->slog( 'SEUR URL C: ' . $url_call );
            seur()->slog( '$response_body: ' . print_r( $response_body, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			seur()->slog( '$result: ' . print_r( $result??'', true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			seur()->slog( 'description: ' . $response['description']??'' );
		}
		return $response;
	}
}
/**
 * SEUR pedidos salida
 *
 * @param string $label_id Label ID.
 */

function seur_tracking( $label_id ) {
	$tracking = new Seur_Logistica_Seguimiento();
	return $tracking->tracking_remote_post( $label_id );
}

function getStatusExpedition($eventCode) {
    global $wpdb;
    $tabla  = $wpdb->prefix . 'seur_status';
    $sql    = "SELECT * FROM ". $tabla ." WHERE cod_situ = '".$eventCode."'";
    $result = $wpdb->get_results($sql);
    return $result ? (array)$result[0] : [];
}

function updateShipmentStatus(
    int $label_id,
    string $shipmentStatus
) {
    update_post_meta( $label_id, '_seur_shipping_tracking_state', $shipmentStatus );
}

function updateExpeditionStatus(
    int $label_id,
    string $expeditionStatus
) {
    // update status in {prefix}_wc_order_stats
    global $wpdb;
    $order_id = get_post_meta( $label_id, '_seur_shipping_order_id', true);
    $tabla  = $wpdb->prefix . 'wc_order_stats';
    if ($expeditionStatusKey = getExpeditionStatusKey($expeditionStatus)) {
        $wpdb->query("UPDATE " . $tabla . " SET status='" . $expeditionStatusKey . "' WHERE order_id = '" . $order_id . "'");
        $order = wc_get_order($order_id);
        $order->update_status($expeditionStatusKey);
    }
    return false;
}

function getExpeditionStatusKey($expeditionStatus) {
    return array_search($expeditionStatus, Seur_Logistica_Seguimiento::tracking_statuses_arr);
}

