<?php
/**
 * SEUR Proucts.
 *
 * @package SEUR Logistica
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * SEUR Products array.
 */
function get_seur_product() {
	/**
     * SEUR MARITIMO -->        Servicio = 17, Producto 2   --> ES/PT         -> all -> ESTANDAR
     * B2C Estándar -->         Servicio = 31, Producto 2   --> AD/ES/PT      -> all -> ESTANDAR
     * SEUR 10 Estándar ->      Servicio = 3,  Producto 2   --> AD/ES/PT      -> all -> ESTANDAR
     * SEUR 10 Frío ->          Servicio = 3,  Producto 18  --> AD/ES/PT      -> all -> FRIO
     * SEUR 13:30 Estándar ->   Servicio = 9,  Producto 2   --> AD/ES/PT      -> all -> ESTANDAR
     * SEUR 13:30 Frío ->       Servicio = 9,  Producto 18  --> AD/ES/FR/PT   -> all -> FRIO
     * SEUR 24H Estándar -->    Servicio = 1,  Producto 2   --> AD/ES/PT      -> all -> ESTANDAR
     * SEUR 48H Estándar ->     Servicio = 15, Producto 2   --> ES            -> all -> ESTANDAR
     * SEUR 48 Frío -->         Servicio = 15, Producto 18  --> AD/ES/PT      -> PM  -> FRIO
     * SEUR 72H Estándar ->     Servicio = 13, Producto 2   --> ES            -> all -> ESTANDAR
     * SEUR 2SHOP ->            Servicio = 1,  Producto 48  --> AD/ES/PT      -> all -> ESTANDAR
     *
     * Internacional
     * COURIER INT AEREO PAQUETERIA -->     Servicio = 7,  Producto 108 --> INTERNATIONAL -> all -> ESTANDAR
     * COURIER INT AEREO DOCUMENTOS -->     Servicio = 7,  Producto 54  --> INTERNATIONAL -> all -> ESTANDAR
     * Classic Internacional Terrestre -->  Servicio = 77, Producto 70  --> INTERNATIONAL -> all -> ESTANDAR
     * NETEXPRESS INT TERRESTRE -->         Servicio = 19, Producto 70  --> INTERNATIONAL -> all -> ESTANDAR
     * CLASSIC FRESH -->                    Servicio = 77, Producto 114 --> INTERNATIONAL -> all -> FRIO
     * CLASSIC 2SHOP -->                    Servicio = 77, Producto 48  --> INTERNATIONAL -> all -> ESTANDAR
     * CLASSIC ECONOMY -->                  Servicio = 77, Producto 226 --> INTERNATIONAL -> all -> ESTANDAR
     * CLASSIC CROSSBORDER -->              Servicio = 77, Producto 104 --> INTERNATIONAL -> all -> ESTANDAR
     * Classic Murtiparcel -->              Servicio = 77, Producto 116 --> OUT-EU        -> all -> ESTANDAR
	 */
	return array(
		'SEUR MARITIMO'                   => array(
			'service'   => '17',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'ES',
				'PT',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_mar'
		),
		'B2C Estándar'                    => array(
			'service'   => '31',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_bc2'
		),
		'SEUR 10 Estándar'                => array(
			'service'   => '3',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_10e'
		),
		'SEUR 10 Frío'                    => array(
			'service'   => '3',
			'product'   => '18',
			'tipo'      => 'FRIO',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_10ef'
		),
		'SEUR 13:30 Estándar'             => array(
			'service'   => '9',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_13e'
		),
		'SEUR 13:30 Frío'                 => array(
			'service'   => '9',
			'product'   => '18',
			'tipo'      => 'FRIO',
			'pais'      => array(
                'AD',
                'ES',
                'FR',
                'PT'
            ),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_13f'
		),
		'SEUR 24H Estándar'               => array(
			'service'   => '1',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_24h'
		),
        'SEUR 48H Estándar'               => array(
            'service'   => '15',
            'product'   => '2',
            'tipo'      => 'ESTANDAR',
            'pais'      => array(
                'ES',
            ),
            'provincia' => array(
                'all',
            ),
            'field' => 'seur_48h'
        ),
        'SEUR 48 Frío'                    => array(
            'service'   => '15',
            'product'   => '18',
            'tipo'      => 'FRIO',
            'pais'      => array(
                'AD',
                'ES',
                'PT'
            ),
            'provincia' => array(
                'PM',
                'CE',
                'ML'
            ),
            'field' => 'seur_48f'
        ),
        'SEUR 72H Estándar'               => array(
			'service'   => '13',
			'product'   => '2',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'ES',
			),
			'provincia' => array(
				'TF',
                'GC'
			),
            'field' => 'seur_72h'
		),
		'SEUR 2SHOP'                      => array(
			'service'   => '1',
			'product'   => '48',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
                'AD',
                'ES',
                'PT'
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_2shop'
		),
		'COURIER INT AEREO PAQUETERIA'    => array(
			'service'   => '7',
			'product'   => '108',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_courier_int_aereo_paqueteria'
		),
		'COURIER INT AEREO DOCUMENTOS'    => array(
			'service'   => '7',
			'product'   => '54',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_courier_int_aereo_documentos'
		),
		'Classic Internacional Terrestre' => array(
			'service'   => '77',
			'product'   => '70',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_cit'
		),
		'NETEXPRESS INT TERRESTRE'        => array(
			'service'   => '19',
			'product'   => '70',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_netexpress_int_terrestre'
		),
		'CLASSIC FRESH'                   => array(
			'service'   => '77',
			'product'   => '114',
			'tipo'      => 'FRIO',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_classic_fresh'
		),
		'CLASSIC 2SHOP'                   => array(
			'service'   => '77',
			'product'   => '48',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_classic_int_2shop'
		),
		'CLASSIC ECONOMY'                 => array(
			'service'   => '77',
			'product'   => '226',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_classic_eco'
		),
		'CLASSIC CROSSBORDER'             => array(
			'service'   => '77',
			'product'   => '104',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'INTERNATIONAL',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_classic_cross'
		),
		'Classic Murtiparcel'             => array(
			'service'   => '77',
			'product'   => '116',
			'tipo'      => 'ESTANDAR',
			'pais'      => array(
				'OUT-EU',
			),
			'provincia' => array(
				'all',
			),
            'field' => 'seur_classic_mp'
		),
	);
}
/**
 * Changes made in this array, needs to be added in:
 * /core/pages/rates/custom-names-rates.php (Custom Names)
 * /core/pages/rates/seur-country-state-process.php (Logic Use).
 */
