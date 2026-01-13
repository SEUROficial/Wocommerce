<?php

namespace Seur\Blocks;

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;

defined('ABSPATH') || exit;

/**
 * Clase Seur_Blocks_Integration.
 */
class Seur_Blocks_Integration implements IntegrationInterface
{
    /**
     * El nombre de la integración (usado como clave de extensión en la Store API).
     *
     * @return string
     */
    public function get_name()
    {
        return 'seur';
    }

    /**
     * Invoca cualquier inicialización/configuración para la integración
     */
    public function initialize()
    {
        /* Compatibilidad declarada en el loader principal del plugin */
        // 1. Registrar el script principal que contiene el componente React (index.js).
        $this->register_main_integration_script();

        // 2. Registrar los datos en la Store API
        $this->register_store_api_data();

        // 3. Registrar endpoint para guardar datos en sesión
        $this->register_session_endpoint();

        // 4. Guardar los datos en el pedido -> en class-seur_local_shipping_method.php
        //$this->register_order_meta_save();

        // 5. Limpiar datos al completar el pedido
        $this->register_order_cleanup_hooks();
    }

    /**
     * Registra el archivo JS principal requerido para Slot/Fills
     */
    private function register_main_integration_script()
    {
        $script_path = '/build/index.js';

        // Calcula la URL y la ruta local.
        $script_url        = plugins_url($script_path, __FILE__);
        $script_asset_path = dirname(__FILE__) . '/build/index.asset.php';

        // Obtiene las dependencias y la versión de Webpack.
        $script_asset      = file_exists($script_asset_path)
            ? require $script_asset_path
            : [
                'dependencies' => [],
                'version'      => defined('SEUR_OFFICIAL_VERSION') ? SEUR_OFFICIAL_VERSION : '1.0.0',
            ];

        $handle = 'seur-blocks-integration';
        wp_register_script(
            $handle,
            $script_url,
            $script_asset['dependencies'],
            $script_asset['version'],
            true // Cargar en el footer
        );

        wp_localize_script(
            $handle,
            'SEUR_HAS_GMAP_API_KEY',
            [
                'hasGmapApi'   => ! empty( get_option( 'seur_google_maps_api_field' ) ),
            ]
        );

        wp_set_script_translations(
            'seur-blocks-integration',
            'seur',
            dirname(__FILE__) . '/languages'
        );
    }

    /**
     * Registra los datos de extensión en la Store API
     */
    private function register_store_api_data()
    {
        // Usar el método correcto de ExtendSchema
        if ( class_exists( '\Automattic\WooCommerce\StoreApi\StoreApi' ) ) {
            try {
                $extend = \Automattic\WooCommerce\StoreApi\StoreApi::container()->get(
                    \Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema::class
                );
                
                if ( is_callable( [ $extend, 'register_endpoint_data' ] ) ) {
                    $extend->register_endpoint_data(
                        [
                            'endpoint'        => CheckoutSchema::IDENTIFIER,
                            'namespace'       => $this->get_name(),
                            'schema_callback' => [ $this, 'schema_callback' ],
                            'schema_type'     => ARRAY_A,
                        ]
                    );
                    return;
                }
            } catch ( \Exception $e ) {
                // Continuar con el método legacy si falla
            }
        }
        
        // Fallback al método legacy
        if ( function_exists( 'woocommerce_store_api_register_endpoint_data' ) ) {
            woocommerce_store_api_register_endpoint_data(
                [
                    'endpoint'        => CheckoutSchema::IDENTIFIER,
                    'namespace'       => $this->get_name(),
                    'data_callback'   => [ $this, 'data_callback' ],
                    'schema_callback' => [ $this, 'schema_callback' ],
                    'schema_type'     => ARRAY_A,
                ]
            );
        }
    }

    /**
     * Registra un endpoint REST API para guardar datos SEUR en la sesión
     */
    private function register_session_endpoint()
    {
        add_action( 'rest_api_init', function() {
            register_rest_route( 'seur/v1', '/save-pickup', [
                'methods'             => 'POST',
                'callback'            => [ $this, 'save_pickup_to_session' ],
                'permission_callback' => [ $this, 'check_pickup_permission' ],
            ] );
        } );
    }

    /**
     * Verifica permisos para el endpoint de guardar pickup
     * Permite acceso a usuarios con sesión de WooCommerce activa (carrito)
     *
     * @return bool
     */
    public function check_pickup_permission()
    {
        // Permitir si hay una sesión de WooCommerce activa (usuario con carrito)
        if ( function_exists( 'WC' ) && WC()->session ) {
            return true;
        }

        // Permitir a usuarios autenticados
        if ( is_user_logged_in() ) {
            return true;
        }

        // Verificar nonce para peticiones del checkout
        $nonce = isset( $_SERVER['HTTP_X_WP_NONCE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_WP_NONCE'] ) ) : '';
        if ( ! empty( $nonce ) && wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Registra hooks para limpiar datos del punto de recogida al completar el pedido
     */
    private function register_order_cleanup_hooks()
    {
        // Limpiar datos cuando se muestra la página de agradecimiento
        add_action( 'woocommerce_thankyou', [ $this, 'cleanup_pickup_data_after_order' ], 10, 1 );
        
        // También limpiar cuando se completa el pago (para métodos de pago externos)
        add_action( 'woocommerce_payment_complete', [ $this, 'cleanup_pickup_data_after_order' ], 10, 1 );
    }

    /**
     * Limpia los datos del punto de recogida de la sesión después de completar el pedido
     *
     * @param int $order_id ID del pedido completado
     */
    public function cleanup_pickup_data_after_order( $order_id )
    {
        // Limpiar datos de la sesión de WooCommerce
        if ( function_exists( 'WC' ) && WC()->session ) {
            WC()->session->set( 'seur_pickup_data', null );
        }
        
        // Inyectar script para limpiar localStorage en el frontend
        add_action( 'wp_footer', [ $this, 'inject_cleanup_script' ], 999 );
    }

    /**
     * Inyecta un script para limpiar localStorage en el frontend
     */
    public function inject_cleanup_script()
    {
        ?>
        <script type="text/javascript">
        (function() {
            try {
                localStorage.removeItem('seur_pickup_data');
                console.log('SEUR: Datos de punto de recogida limpiados después de completar el pedido');
            } catch(e) {
                console.error('SEUR: Error limpiando localStorage:', e);
            }
        })();
        </script>
        <?php
    }

    /**
     * Guarda los datos del punto de recogida en la sesión de WooCommerce
     *
     * @param \WP_REST_Request $request Objeto de la petición REST.
     * @return array|\WP_Error
     */
    public function save_pickup_to_session( $request )
    {
        $data = $request->get_json_params();

        if ( empty( $data ) || ! isset( $data['seur_pickup'] ) ) {
            return new \WP_Error(
                'invalid_data',
                __( 'Datos inválidos', 'seur' ),
                [ 'status' => 400 ]
            );
        }

        // Sanitizar todos los datos de entrada
        $sanitized_data = $this->sanitize_pickup_data( $data );

        // Asegurarse de que WooCommerce está cargado
        if ( ! function_exists( 'WC' ) ) {
            return new \WP_Error(
                'woocommerce_not_loaded',
                __( 'WooCommerce no está disponible', 'seur' ),
                [ 'status' => 500 ]
            );
        }

        // Inicializar la sesión si no existe
        if ( ! WC()->session ) {
            // Intentar inicializar la sesión manualmente
            if ( class_exists( 'WC_Session_Handler' ) ) {
                WC()->session = new \WC_Session_Handler();
                WC()->session->init();
            }
        }

        // Guardar en la sesión de WooCommerce
        if ( WC()->session ) {
            WC()->session->set( 'seur_pickup_data', $sanitized_data );

            return [
                'success'    => true,
                'message'    => __( 'Datos guardados en sesión WooCommerce', 'seur' ),
                'data'       => $sanitized_data,
                'session_id' => WC()->session->get_customer_id(),
            ];
        }

        // Si la sesión no está disponible, devolver error
        return new \WP_Error(
            'session_unavailable',
            __( 'No se pudo inicializar la sesión de WooCommerce', 'seur' ),
            [ 'status' => 500 ]
        );
    }

    /**
     * Sanitiza los datos del punto de recogida
     *
     * @param array $data Datos sin sanitizar.
     * @return array Datos sanitizados.
     */
    private function sanitize_pickup_data( $data )
    {
        $allowed_keys = [
            'seur_pickup',
            'seur_depot',
            'seur_postcode',
            'seur_codCentro',
            'seur_title',
            'seur_type',
            'seur_address',
            'seur_city',
            'seur_pudo_id',
            'seur_lat',
            'seur_lng',
            'seur_streettype',
            'seur_numvia',
            'seur_timetable',
        ];

        $sanitized = [];

        foreach ( $allowed_keys as $key ) {
            if ( isset( $data[ $key ] ) ) {
                // Sanitizar según el tipo de dato esperado
                if ( in_array( $key, [ 'seur_lat', 'seur_lng' ], true ) ) {
                    // Coordenadas: permitir números y punto decimal
                    $sanitized[ $key ] = preg_replace( '/[^0-9.\-]/', '', $data[ $key ] );
                } elseif ( in_array( $key, [ 'seur_postcode', 'seur_pickup', 'seur_depot', 'seur_codCentro', 'seur_pudo_id', 'seur_numvia' ], true ) ) {
                    // IDs y códigos postales: solo alfanuméricos
                    $sanitized[ $key ] = sanitize_text_field( $data[ $key ] );
                } else {
                    // Texto general: sanitizar como texto
                    $sanitized[ $key ] = sanitize_text_field( $data[ $key ] );
                }
            } else {
                $sanitized[ $key ] = '';
            }
        }

        return $sanitized;
    }

    /**
     * Callback que devuelve los datos almacenados
     *
     * @return array
     */
    public function data_callback()
    {
        $seur_data = null;

        // Verificar que WooCommerce y la sesión están disponibles
        if ( function_exists( 'WC' ) && WC()->session ) {
            $seur_data = WC()->session->get( 'seur_pickup_data' );
        }

        // Si no hay datos, devolver valores vacíos
        if ( empty( $seur_data ) || ! is_array( $seur_data ) ) {
            return $this->get_empty_pickup_data();
        }

        // Devolver los datos encontrados
        return $seur_data;
    }

    /**
     * Devuelve un array con los datos de pickup vacíos
     *
     * @return array
     */
    private function get_empty_pickup_data()
    {
        return [
            'seur_pickup'      => '',
            'seur_depot'       => '',
            'seur_postcode'    => '',
            'seur_codCentro'   => '',
            'seur_title'       => '',
            'seur_type'        => '',
            'seur_address'     => '',
            'seur_city'        => '',
            'seur_pudo_id'     => '',
            'seur_lat'         => '',
            'seur_lng'         => '',
            'seur_streettype'  => '',
            'seur_numvia'      => '',
            'seur_timetable'   => '',
        ];
    }

    /**
     * Schema para validar los datos
     */
    public function schema_callback()
    {
        return [
            'seur_pickup'      => [
                'description' => __( 'ID del punto de recogida SEUR seleccionado', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_depot'       => [
                'description' => __( 'Depot del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_postcode'    => [
                'description' => __( 'Código postal del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_codCentro'   => [
                'description' => __( 'Código de centro', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_title'       => [
                'description' => __( 'Nombre del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_type'        => [
                'description' => __( 'Tipo de punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_address'     => [
                'description' => __( 'Dirección del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_city'        => [
                'description' => __( 'Ciudad del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_pudo_id'     => [
                'description' => __( 'ID PUDO del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_lat'         => [
                'description' => __( 'Latitud del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_lng'         => [
                'description' => __( 'Longitud del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_streettype'  => [
                'description' => __( 'Tipo de vía', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_numvia'      => [
                'description' => __( 'Número de vía', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
            'seur_timetable'   => [
                'description' => __( 'Horario del punto de recogida', 'seur' ),
                'type'        => 'string',
                'context'     => [ 'view', 'edit' ],
                'required'    => false,
            ],
        ];
    }

   /**
     * Devuelve un array de handles de scripts para encolar en el contexto de frontend
     *
     * @return string[]
     */
    public function get_script_handles()
    {
        // Solo encolamos el script principal de la integración.
        return ['seur-blocks-integration'];
    }

    /**
     * Devuelve un array de handles de scripts para encolar en el contexto del editor
     *
     * @return string[]
     */
    public function get_editor_script_handles()
    {
        return [];
    }

    /**
     * Un array de datos clave-valor disponibles para el bloque en el lado del cliente
     *
     * @return array
     */
    public function get_script_data()
    {
        return [
            'hasGmapApi' => ! empty( get_option( 'seur_google_maps_api_field' ) ),
        ];
    }
}
