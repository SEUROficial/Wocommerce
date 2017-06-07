<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WC_Shipping_SEUR class.
 *
 * @version 1.0.0
 * @since 1.0.0
 * @extends WC_Shipping_Method
 */
class WC_Shipping_SEUR extends WC_Shipping_Method {

    private $services = array(
        // Domestic
        "1" => array(
                "servicode" => "1",
                "product"   => "2",
                "name"      => "SEUR 24H ESTANDAR"
            ),
        "2" => array(
                "servicode" => "13",
                "product"   => "2" ,
                "name"      => "SEUR 72H ESTANDAR"
            ),
        "3" => array(
                "servicode" => "15",
                "product"   => "2",
                "name"      => "SEUR 48H ESTANDAR"
            ),
        "4" => array(
                "servicode" => "17",
                "product"   => "2",
                "name"      => "SEUR MARITIMO ESTANDAR"
            ),
        "5" => array(
                "servicode" => "3",
                "product"   => "4",
                "name"      => "SEUR 10 ESTANDAR"
            ),
        "6" => array(
                "servicode" => "31",
                "product"   => "2",
                "name"      => "PARTICULARES 24H ESTANDAR"
            ),
        "7" => array(
                "servicode" => "83",
                "product"   => "2",
                "name"      => "SEUR 8:30 ESTANDAR"
            ),
        "8" => array(
                "servicode" => "9",
                "product"   => "2",
                "name"      => "SEUR 13:30 ESTANDAR"
            ),

        // International Shipping

        "9" => array(
                "servicode" => "7",
                "product"   => "108",
                "name"      => "COURIER INT AEREO PAQUETERIA"
            ),
        "10" => array(
                "servicode" => "7",
                "product"   => "54",
                "name"      => "COURIER INT AEREO DOCUMENTOS"
            ),
        "11" => array(
                "servicode" => "77",
                "product"   => "70",
                "name"      => "CLASSIC INT TERRESTRE"
            ),
        "12" => array(
                "servicode" => "19",
                "product"   => "70",
                "name"      => "NETEXPRESS INT TERRESTRE"
            ),
        );


    private $pt_array = array('PT');

    // Shipments Originating in the European Union
    private $ptservices = array(
        // Domestic
        "1" => array(
                "servicode" => "1",
                "product"   => "2",
                "name"      => "SEUR PORTO 24H ESTANDAR"
            ),
        "2" => array(
                "servicode" => "13",
                "product"   => "2" ,
                "name"      => "SEUR PORTO 72H ESTANDAR"
            ),
        "3" => array(
                "servicode" => "15",
                "product"   => "2",
                "name"      => "SEUR PORTO 48H ESTANDAR"
            ),
        "4" => array(
                "servicode" => "17",
                "product"   => "2",
                "name"      => "SEUR PORTO MARITIMO ESTANDAR"
            ),
        "5" => array(
                "servicode" => "3",
                "product"   => "4",
                "name"      => "SEUR PORTO 10 ESTANDAR"
            ),
        "6" => array(
                "servicode" => "31",
                "product"   => "2",
                "name"      => "PARTICULARES PORTO 24H ESTANDAR"
            ),
        "7" => array(
                "servicode" => "83",
                "product"   => "2",
                "name"      => "SEUR PORTO 8:30 ESTANDAR"
            ),
        "8" => array(
                "servicode" => "9",
                "product"   => "2",
                "name"      => "SEUR PORTO 13:30 ESTANDAR"
            ),

        // International Shipping

        "9" => array(
                "servicode" => "7",
                "product"   => "108",
                "name"      => "COURIER PORTO INT AEREO PAQUETERIA"
            ),
        "10" => array(
                "servicode" => "7",
                "product"   => "54",
                "name"      => "COURIER PORTO INT AEREO DOCUMENTOS"
            ),
        "11" => array(
                "servicode" => "77",
                "product"   => "70",
                "name"      => "CLASSIC PORTO INT TERRESTRE"
            ),
        "12" => array(
                "servicode" => "19",
                "product"   => "70",
                "name"      => "NETEXPRESS PORTO INT TERRESTRE"
            ),
        );

    private $packaging = array(
        "01" => array(
                    "name"   => "SEUR Letter",
                    "length" => "12.5",
                    "width"  => "9.5",
                    "height" => "0.25",
                    "weight" => "0.5"
                ),
        "03" => array(
                    "name"   => "Tube",
                    "length" => "38",
                    "width"  => "6",
                    "height" => "6",
                    "weight" => "100" // no limit, but use 100
                ),
        "24" => array(
                    "name"   => "25KG Box",
                    "length" => "19.375",
                    "width"  => "17.375",
                    "height" => "14",
                    "weight" => "55.1156"
                ),
        "25" => array(
                    "name"   => "10KG Box",
                    "length" => "16.5",
                    "width"  => "13.25",
                    "height" => "10.75",
                    "weight" => "22.0462"
                ),
        "2a" => array(
                    "name"   => "Small Express Box",
                    "length" => "13",
                    "width"  => "11",
                    "height" => "2",
                    "weight" => "100" // no limit, but use 100
                ),
        "2b" => array(
                    "name"   => "Medium Express Box",
                    "length" => "15",
                    "width"  => "11",
                    "height" => "3",
                    "weight" => "100" // no limit, but use 100
                ),
        "2c" => array(
                    "name"   => "Large Express Box",
                    "length" => "18",
                    "width"  => "13",
                    "height" => "3",
                    "weight" => "30"
                )
    );

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct( $instance_id = 0 ) {
        $this->id                 = 'seur';
        $this->instance_id        = absint( $instance_id );
        $this->method_title       = __( 'SEUR', 'seur' );
        $this->method_description = __( '<p>The SEUR extension obtains rates dynamically from the SEUR API during cart/checkout.</p><p>Please, configure SEUR data in <code>SEUR -> Settings</code></p>', 'seur' );
        $this->supports           = array(
            'shipping-zones',
            'instance-settings',
            'settings',
        );
        $this->init();
    }

    /**
     * is_available function.
     *
     * @param array $package
     * @return bool
     */
    public function is_available( $package ) {
        if ( empty( $package['destination']['country'] ) ) {
            return false;
        }

        return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
    }

    /**
     * Output a message or error
     * @param  string $message
     * @param  string $type
     */
    public function debug( $message, $type = 'notice' ) {

        if ( $this->debug || ( current_user_can( 'manage_options' ) && 'error' == $type ) ) {
            wc_add_notice( $message, $type );
        }

    }

    /**
     * Initialize settings
     *
     * @version 3.2.0
     * @since 3.2.0
     * @return bool
     */
    private function set_settings() {
        // Define user set variables
        $this->title                = $this->get_option( 'title', $this->method_title );
        $this->simple_advanced      = $this->get_option( 'simple_advanced', 'simple' );

        // API Settings
        $this->user_id              = $this->get_option( 'user_id' );
        $this->password             = $this->get_option( 'password' );
        $this->access_key           = $this->get_option( 'access_key' );
        $this->shipper_number       = $this->get_option( 'shipper_number' );
        $this->origin_addressline   = ( ( $bool = $this->get_option( 'origin_addressline' ) ) && $bool === 'yes' );
        $this->origin_city          = $this->get_option( 'origin_city' );
        $this->origin_postcode      = $this->get_option( 'origin_postcode' );
        $this->origin_country_state = $this->get_option( 'origin_country_state' );
        $this->debug                = ( ( $bool = $this->get_option( 'debug' ) ) && $bool === 'yes' );

        // Pickup and Destination
        $this->pickup               = $this->get_option( 'pickup', '01' );
        $this->residential          = ( ( $bool = $this->get_option( 'residential' ) ) && $bool === 'yes' );

        // Services and Packaging
        $this->offer_rates          = $this->get_option( 'offer_rates', 'all' );
        $this->fallback             = $this->get_option( 'fallback' );
        $this->packing_method       = $this->get_option( 'packing_method', 'per_item' );
        $this->seur_packaging       = $this->get_option( 'seur_packaging', array() );
        $this->custom_services      = $this->get_option( 'services', array() );
        $this->boxes                = $this->get_option( 'boxes', array() );
        $this->insuredvalue         = ( ( $bool = $this->get_option( 'insuredvalue' ) ) && $bool === 'yes' );

        // Units
        $this->units                = $this->get_option( 'units', false );
        $this->weight_unit          = 'KGS';
        $this->dim_unit             = 'CM';

        /**
         * If no origin country / state saved / exists, set it to store base country:
         */

        if ( ! $this->origin_country_state ) {
            $origin               = wc_get_base_location();
            $this->origin_country = $origin['country'];
            $this->origin_state   = $origin['state'];
        } else {
            $this->split_country_state( $this->origin_country_state );
        }

        return true;
    }

    /**
     * init function.
     *
     * @access public
     * @return void
     */
    private function init() {

        // Load the settings.
        $this->init_form_fields();
        $this->set_settings();

        // Enqueue SEUR Scripts
        wp_enqueue_script( 'seur-admin-js' );

        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
    }

    /**
     * Process settings on save
     *
     * @access public
     * @since 3.2.0
     * @version 3.2.0
     * @return void
     */
    public function process_admin_options() {
        parent::process_admin_options();

        $this->set_settings();
    }

    /**
     * Helper method to split the country/state and set them.
     */
    public function split_country_state( $country_state ) {
        if ( strstr( $country_state, ':') ) {
            $origin_country_state = explode( ':', $country_state );
            $this->origin_country = current( $origin_country_state );
            $this->origin_state   = end( $origin_country_state );
        } else {
            $this->origin_country = $country_state;
            $this->origin_state   = '';
        }
    }

    /**
     * Assets to enqueue in admin
     */

    public function assets() {
        wp_register_style( 'seur-admin-css', SEUR_PLUGIN_URL . '/core/woocommerce/assets/css/seur-admin.css', '', WC_Shipping_SEUR_Init::$version );
        wp_register_script( 'seur-admin-js', SEUR_PLUGIN_URL . '/core/woocommerce/assets/js/seur-admin.js', array( 'jquery' ), WC_Shipping_SEUR_Init::$version, true );

        wp_enqueue_style( 'seur-admin-css' );
        wp_enqueue_script( 'jquery-ui-sortable' );

        $vars = array(
            'dim_unit'    => $this->dim_unit,
            'weight_unit' => $this->weight_unit,
        );

        wp_localize_script( 'seur-admin-js', 'wcseur', $vars );

    }

    /**
     * environment_check function.
     *
     * @access public
     * @return void
     */
    private function environment_check() {
        $error_message = '';

        // Check environment only on shipping instance page.
        if ( 0 < $this->instance_id ) {
            // If user has selected to pack into boxes,
            // Check if at least one SEUR packaging is chosen, or a custom box is defined
            if ( 'box_packing' === $this->packing_method ) {
                if ( empty( $this->seur_packaging )  && empty( $this->boxes ) ) {
                    $error_message .= '<p>' . __( 'SEUR is enabled, and Parcel Packing Method is set to \'Pack into boxes\', but no SEUR Packaging is selected and there are no custom boxes defined. Items will be packed individually.', 'seur' ) . '</p>';
                }
            }

            // Check for at least one service enabled
            $ctr = 0;
            if ( isset($this->custom_services ) && is_array( $this->custom_services ) ) {
                foreach ( $this->custom_services as $key => $values ) {
                    if ( $values['enabled'] == 1 ) {
                        $ctr++;
                    }
                }
            }
            if ( $ctr == 0 ) {
                $error_message .= '<p>' . __( 'SEUR is enabled, but there are no services enabled.', 'seur' ) . '</p>';
            }
        }

        if ( ! $error_message == '' ) {
            echo '<div class="error">';
            echo $error_message;
            echo '</div>';
        }
    }

    /**
     * admin_options function.
     *
     * @access public
     * @return void
     */
    public function admin_options() {
        // Check users environment supports this method
        $this->environment_check();

        // Show settings
        parent::admin_options();
    }

    /**
     *
     * generate_single_select_country_html function
     *
     * @access public
     * @return void
     */
    public function generate_single_select_country_html() {

        ob_start();
        ?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="origin_country"><?php _e( 'Origin Country', 'seur' ); ?></label>
            </th>
            <td class="forminp"><select name="woocommerce_seur_origin_country_state" id="woocommerce_seur_origin_country_state" style="width: 250px;" data-placeholder="<?php _e('Choose a country&hellip;', 'woocommerce'); ?>" title="Country" class="chosen_select">
                <?php echo WC()->countries->country_dropdown_options( $this->origin_country, $this->origin_state ? $this->origin_state : '*' ); ?>
            </select>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * generate_services_html function.
     *
     * @access public
     * @return void
     */
    public function generate_services_html() {
        ob_start();
        ?>
        <tr valign="top" id="service_options">
            <th scope="row" class="titledesc"><?php _e( 'Services', 'seur' ); ?></th>
            <td class="forminp">
                <table class="seur_services widefat">
                    <thead>
                        <th class="sort">&nbsp;</th>
                        <th><?php _e( 'Service Code', 'seur' ); ?></th>
                        <th><?php _e( 'Product', 'seur' ); ?></th>
                        <th><?php _e( 'Name', 'seur' ); ?></th>
                        <th><?php _e( 'Enabled', 'seur' ); ?></th>
                        <th><?php echo sprintf( __( 'Price Adjustment (%s)', 'seur' ), get_woocommerce_currency_symbol() ); ?></th>
                        <th><?php _e( 'Price Adjustment (%)', 'seur' ); ?></th>
                    </thead>
                    <tfoot>
                    <?php if( !$this->origin_country == 'PL' && !in_array( $this->origin_country, $this->pt_array ) ) : ?>
                        <tr>
                            <th colspan="6">
                                <small class="description"><?php _e( '<strong>Domestic Rates</strong>: Next Day Air, 2nd Day Air, Ground, 3 Day Select, Next Day Air Saver, Next Day Air Early AM, 2nd Day Air AM', 'seur' ); ?></small><br/>
                                <small class="description"><?php _e( '<strong>International Rates</strong>: Worldwide Express, Worldwide Expedited, Standard, Worldwide Express Plus, SEUR Saver', 'seur' ); ?></small>
                            </th>
                        </tr>
                    <?php endif ?>
                    </tfoot>
                    <tbody>
                        <?php
                            $sort = 0;
                            $this->ordered_services = array();

                            if ( in_array( $this->origin_country, $this->pt_array ) ) {
                                $use_services = $this->ptservices;
                            } else {
                                $use_services = $this->services;
                            }

                            foreach ( $use_services as $code => $product ) { // $this->seur_packaging as $key => $box_code

                                if ( isset( $this->custom_services[ $code ]['order'] ) ) {
                                    $sort = $this->custom_services[ $code ]['order'];
                                }

                                while ( isset( $this->ordered_services[ $sort ] ) ) {
                                    $sort++;
                                }
                                $servicod = $product['servicode'];
                                $productp = $product['product'];
                                $productn = $product['name'];
                                $this->ordered_services[ $sort ] = array( $code, $servicod, $productp, $productn );

                                $sort++;
                            }

                            ksort( $this->ordered_services );

                            foreach ( $this->ordered_services as $value ) {
                                $code = $value[0];
                                $servicod = $value[1];
                                $productp = $value[2];
                                $productn = $value[3];
                                ?>
                                <tr>
                                    <td class="sort"><input type="hidden" class="order" name="seur_service[<?php echo $code; ?>][order]" value="<?php echo isset( $this->custom_services[ $code ]['order'] ) ? $this->custom_services[ $code ]['order'] : ''; ?>" /></td>
                                    <td><strong><?php echo $servicod; ?></strong></td>
                                    <td><strong><?php echo $productp; ?></strong></td>
                                    <td><input type="text" name="seur_service[<?php echo $code; ?>][name]" placeholder="<?php echo $productn; ?> (<?php echo $this->title; ?>)" value="<?php echo $productn; ?>" size="50" /></td>

                                    <td><input type="checkbox" name="seur_service[<?php echo $code; ?>][enabled]" <?php checked( ( ! isset( $this->custom_services[ $code ]['enabled'] ) || ! empty( $this->custom_services[ $code ]['enabled'] ) ), true ); ?> /></td>
                                    <td><input type="text" name="seur_service[<?php echo $code; ?>][adjustment]" placeholder="N/A" value="<?php echo isset( $this->custom_services[ $code ]['adjustment'] ) ? $this->custom_services[ $code ]['adjustment'] : ''; ?>" size="4" /></td>
                                    <td><input type="text" name="seur_service[<?php echo $code; ?>][adjustment_percent]" placeholder="N/A" value="<?php echo isset( $this->custom_services[ $code ]['adjustment_percent'] ) ? $this->custom_services[ $code ]['adjustment_percent'] : ''; ?>" size="4" /></td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * validate_single_select_country_field function.
     *
     * @access public
     * @param mixed $key
     * @return void
     */
    public function validate_single_select_country_field( $key ) {

        if ( isset( $_POST['woocommerce_seur_origin_country_state'] ) ) {
            return $_POST['woocommerce_seur_origin_country_state'];
        } else {
            return '';
        }
    }

    /**
     * validate_services_field function.
     *
     * @access public
     * @param mixed $key
     * @return void
     */
    public function validate_services_field( $key ) {
        $services         = array();
        $posted_services  = $_POST['seur_service'];

        foreach ( $posted_services as $code => $settings ) {

            $services[ $code ] = array(
                'name'               => wc_clean( $settings['name'] ),
                'order'              => wc_clean( $settings['order'] ),
                'enabled'            => isset( $settings['enabled'] ) ? true : false,
                'adjustment'         => wc_clean( $settings['adjustment'] ),
                'adjustment_percent' => str_replace( '%', '', wc_clean( $settings['adjustment_percent'] ) )
            );

        }

        return $services;
    }

    /**
     * clear_transients function.
     *
     * @access public
     * @return void
     */
    public function clear_transients() {
        global $wpdb;

        $wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_seur_quote_%') OR `option_name` LIKE ('_transient_timeout_seur_quote_%')" );
    }

    /**
     * init_form_fields function.
     *
     * @access public
     * @return void
     */
    public function init_form_fields() {
        $this->instance_form_fields = array(
            'core'           => array(
                'title'           => __( 'Method & Origin Settings', 'seur' ),
                'type'            => 'title',
                'description'     => '',
                'class'           => 'seur-section-title',
            ),
            'title'            => array(
                'title'           => __( 'Method Title', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'This controls the title which the user sees during checkout.', 'seur' ),
                'default'         => __( 'SEUR', 'seur' ),
                'desc_tip' => true
            ),
            'origin_city'         => array(
                'title'           => __( 'Origin City', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'Enter the city for the <strong>sender</strong>.', 'seur' ),
                'default'         => '',
                'desc_tip' => true
            ),
            'origin_postcode'     => array(
                'title'           => __( 'Origin Postcode', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'Enter the zip/postcode for the <strong>sender</strong>.', 'seur' ),
                'default'         => '',
                'desc_tip' => true
            ),
            'origin_country_state' => array(
                'type'            => 'single_select_country',
            ),
            'services_packaging'  => array(
                'title'           => __( 'Services and Packaging', 'seur' ),
                'type'            => 'title',
                'description'     => __( 'Please enable all of the different services you\'d like to offer customers.', 'seur' ) . ' <em>' . __( 'By enabling a service, it doesn\'t gaurantee that it will be offered, as the plugin will only offer the available rates based on the package, the origin and the destination.', 'seur' ) . '</em>',
                'class'           => 'seur-section-title',
            ),
            'services'  => array(
                'type'            => 'services'
            ),
            'offer_rates'   => array(
                'title'           => __( 'Offer Rates', 'seur' ),
                'type'            => 'select',
                'description'     => '',
                'default'         => 'expensive',
                'options'         => array(
                    'all'         => __( 'Offer the customer all returned rates', 'seur' ),
                    'cheapest'    => __( 'Offer the customer the cheapest rate only', 'seur' ),
                    'expensive'   => __( 'Offer the customer the expensive rate only', 'seur' )
                ),
            ),
        );

        $this->form_fields = array(
        /*  'api'           => array(
                'title'           => __( 'API Settings', 'seur' ),
                'type'            => 'title',
                'description'     => sprintf( __( 'You need to obtain SEUR account credentials by registering on %svia their website%s.', 'seur' ), '<a href="https://www.seur.com/seurdeveloperkit">', '</a>' ),
                'class'           => 'seur-section-title seur-api-title',
            ),
            'user_id'           => array(
                'title'           => __( 'SEUR User ID', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'Obtained from SEUR after getting an account.', 'seur' ),
                'default'         => '',
                'class'           => 'seur-api-setting',
                'desc_tip' => true
            ),
            'password'            => array(
                'title'           => __( 'SEUR Password', 'seur' ),
                'type'            => 'password',
                'description'     => __( 'Obtained from SEUR after getting an account.', 'seur' ),
                'default'         => '',
                'class'           => 'seur-api-setting',
                'desc_tip' => true
            ),
            'access_key'          => array(
                'title'           => __( 'SEUR Access Key', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'Obtained from SEUR after getting an account.', 'seur' ),
                'default'         => '',
                'class'           => 'seur-api-setting',
                'desc_tip' => true
            ),
            'shipper_number'      => array(
                'title'           => __( 'SEUR Account Number', 'seur' ),
                'type'            => 'text',
                'description'     => __( 'Obtained from SEUR after getting an account.', 'seur' ),
                'default'         => '',
                'class'           => 'seur-api-setting',
                'desc_tip' => true
            ),
            'debug'  => array(
                'title'           => __( 'Debug Mode', 'seur' ),
                'label'           => __( 'Enable debug mode', 'seur' ),
                'type'            => 'checkbox',
                'default'         => 'no',
                'description'     => __( 'Enable debug mode to show debugging information on your cart/checkout.', 'seur' ),
                'desc_tip' => true
            ), */
        );
    }

    /**
     * calculate_shipping function.
     *
     * @access public
     * @param mixed $package
     * @return void
     */
    public function calculate_shipping( $package = array() ) {
        $rates        = array();
        $seur_response = array();
        $rate_requests = array();

        // Only return rates if the package has a destination including country
        if ( '' === $package['destination']['country'] ) {
            $this->debug( __( 'SEUR: Country not supplied. Rates not requested.', 'seur' ) );
            return;
        }

        // If no origin postcode set, throw an error and stop the calculation
        if ( ! $this->origin_postcode ) {
            $this->debug( sprintf( __( 'SEUR: No Origin Postcode has been set. Please %sadd one%s so rates can be calculated!', 'seur' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wc_shipping_seur' ) . '">', '</a>' ), 'error' );
            return;
        }

                $price      = $package['contents_cost'];
                $country    = $package['destination']['country'];
                $state      = $package['destination']['state'];
                $postcode   = $package['destination']['postcode'];


                $rate_requests = seur_show_availables_rates( $country, $state, $postcode, $price );

            if ( ! $rate_requests ) {
                $this->debug( __( 'SEUR: No Services are enabled in admin panel.', 'seur' ) );
            }
		if ( $rate_requests ){

            // parse the results
            foreach ( $rate_requests as $rate ) {

                $idrate         = $rate['ID'];
                $countryrate    = $rate['country'];
                $staterate      = $rate['state'];
                $postcoderate   = $rate['postcode'];
                $raterate       = $rate['rate'];
                $ratepricerate  = $rate['rateprice'];

                if ( $rate ) {

                    $sort = 999;

                    $rates[ $idrate ] = array(
                        'id'    => $idrate,
                        'label' => $raterate,
                        'cost'  => $ratepricerate,
                        'sort'  => $sort
                    );
                }

				}
        } // foreach ( $package_requests )

        // Add rates
        if ( $rates ) {

            if ( $this->offer_rates == 'all' ) {

                uasort( $rates, array( $this, 'sort_rates' ) );
                foreach ( $rates as $key => $rate ) {
                    $this->add_rate( $rate );
                }

            } elseif ( $this->offer_rates == 'cheapest' ) {

                $cheapest_rate = '';

                foreach ( $rates as $key => $rate ) {
                    if ( ! $cheapest_rate || $cheapest_rate['cost'] > $rate['cost'] ) {
                        $cheapest_rate = $rate;
                    }
                }

                $this->add_rate( $cheapest_rate );

            } elseif ( $this->offer_rates == 'expensive' ) {

	            $expensive_rate = '';

                foreach ( $rates as $key => $rate ) {
                    if ( ! $expensive_rate || $expensive_rate['cost'] < $rate['cost'] ) {
                        $expensive_rate = $rate;
                    }
                }

                $this->add_rate( $expensive_rate );


            } else {

	            uasort( $rates, array( $this, 'sort_rates' ) );
                foreach ( $rates as $key => $rate ) {
                    $this->add_rate( $rate );
                }
            }
        // Fallback
        } elseif ( $this->fallback ) {
            $this->add_rate( array(
                'id'    => $this->id . '_fallback',
                'label' => $this->title,
                'cost'  => $this->fallback,
                'sort'  => 0
            ) );
            $this->debug( __( 'SEUR: Using Fallback setting.', 'seur' ) );
        }
    }

    /**
     * sort_rates function.
     *
     * @access public
     * @param mixed $a
     * @param mixed $b
     * @return void
     */
    public function sort_rates( $a, $b ) {
        if ( $a['sort'] == $b['sort'] ) return 0;
        return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
    }

}