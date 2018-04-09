<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $localpickup_is_active = get_option( 'seur_activate_local_pickup_field' );

    function seur_local_shipping_method() {
        if ( ! class_exists( 'Seur_Local_Shipping_Method' ) ) {
            class Seur_Local_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'seurlocal';
                    $this->method_title       = __( 'SEUR Local Pickup', 'seur' );
                    $this->method_description = __( 'SEUR Local Pickup Shipping Method, Please configure SEUR data in <code>SEUR -> Settings</code>', 'seur' );
                    $this->offer_rates        = $this->get_option( 'offer_rates', 'all' );
                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'ES',
                        'AD',
                        'PT'
                        );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = array() ) {

                    global $woocommerce, $postcode_seur;
                    $rates                     = array();
                    $seur_response             = array();
                    $rate_requests             = array();
                    $rates_type                = get_option( 'seur_rates_type_field' );
                    $localpickup_is_active     = get_option( 'seur_activate_local_pickup_field' );

                    // Only return rates if the package has a destination including country
                    if ( '' === $package['destination']['country'] ) {
                        $this->debug( __( 'SEUR: Country not supplied. Rates not requested.', 'seur' ) );
                        return;
                    }
                    if ( $localpickup_is_active != 1 ) {
                        return;
                    }

                    if ( $rates_type == 'price' ) {
                        $price = $package['contents_cost'];
                    } else {

                       $weight        = 0;
                       $cost          = 0;
                       $country       = $package["destination"]["country"];
                       $package_price = $package['contents_cost'];

                       foreach ( $package['contents'] as $item_id => $values ) {
                           $_product = $values['data'];
                           $weight   = $weight + $_product->get_weight() * $values['quantity'];
                       }

                       $price = wc_get_weight( $weight, 'kg' );
                    }
                    //$price      = $package['contents_cost'];
                    $country    = $package['destination']['country'];
                    $state      = $package['destination']['state'];
                    $postcode_seur   = $package['destination']['postcode'];

                    $rate_requests = seur_show_availables_rates( $country, $state, $postcode_seur, $price );

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

                            if ( $rate && $raterate == 'SEUR 2SHOP' ) {

                                $sort = 999;

                                if ( $rates_type == 'price' ) {
                                        $ratepricerate = $ratepricerate;
                                    } else {
                                        $ratepricerate = seur_filter_price_rate_weight( $package_price, $raterate, $ratepricerate );
                                    }

                                $rate_name = seur_get_custom_rate_name( $raterate );

                                $rates[ $idrate ] = array(
                                    'id'    => $idrate,
                                    'label' => $rate_name,
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
                    }
                }
                public function sort_rates( $a, $b ) {
                    if ( $a['sort'] == $b['sort'] ) return 0;
                    return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
                }
            }
        }
    }

    function add_seur_local_shipping_method( $methods ) {
        $methods[] = 'seur_local_shipping_method';
        return $methods;
    }

    function seur_local_validate_order( $posted )   {

        $packages = WC()->shipping->get_packages();

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        if( is_array( $chosen_methods ) && in_array( 'seurlocal', $chosen_methods ) ) {

            foreach ( $packages as $i => $package ) {

                if ( $chosen_methods[ $i ] != "seurlocal" ) {

                    continue;

                }

                $Seur_Local_Shipping_Method = new Seur_Local_Shipping_Method();
                $weightLimit = (int)20;
                $weight = 0;

                foreach ( $package['contents'] as $item_id => $values ) {
                    $_product = $values['data'];
                    $weight   = $weight + $_product->get_weight() * $values['quantity'];
                }

                $weight = wc_get_weight( $weight, 'kg' );

                if ( $weight > $weightLimit ) {

                        $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'seur' ), $weight, $weightLimit, $Seur_Local_Shipping_Method->method_title );

                        $messageType = "error";

                        if( ! wc_has_notice( $message, $messageType ) ) {

                            wc_add_notice( $message, $messageType );

                        }
                }
            }
        }
    }

    function seur_map_checkout_load_js(){
        if ( is_checkout() ) {
            $seur_gmap_api = get_option( 'seur_google_maps_api_field' );
            if ( empty( $seur_gmap_api ) ) {
                return;
            }
            wp_enqueue_script( 'seur-gmap', 'https://maps.google.com/maps/api/js?libraries=geometry&v=3&key=' . $seur_gmap_api );
            wp_enqueue_script( 'seur-map', SEUR_PLUGIN_URL . 'assets/js/maplace.min.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION );
        }
    }

    function seur_get_local_pickups( $postcode ) {

        $user_data = seur_get_user_settings();
        $usercom   = $user_data[0]['seurcom_usuario'];
        $passcom   = $user_data[0]['seurcom_contra'];

        $sc_options = array(
            'connection_timeout' => 30
        );

        $soap_client = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options);
        $xml = '
            <CAMPOS>
                <CODIGO_POSTAL>' . $postcode . '</CODIGO_POSTAL>
                <NOM_CORTO></NOM_CORTO>
                <LATITUD></LATITUD>
                <LONGITUD></LONGITUD>
                <NOM_POBLACION></NOM_POBLACION>
                <COD_SERVICIO></COD_SERVICIO>
                <COD_PRODUCTO></COD_PRODUCTO>
                <USUARIO>' . $usercom . '</USUARIO>
                <PASSWORD>' . $passcom . '</PASSWORD>
            </CAMPOS>';

        $data     = array( 'in0' => strtoupper( $xml ) );
        $response = $soap_client->puntosDeVentaStr( $data );
        $xml      = simplexml_load_string( utf8_decode( $response->out ) );
        $centro   = array();
        $num      = (int)$xml->attributes()->NUM[0];

        for ( $i = 1; $i <= $num; $i++ ) {
            $name     = 'REG' . $i;
            $centro[] = array(
                'id'        => $i,
                'company'   => (string)$xml->$name->NOM_CENTRO_SEUR,
                'codCentro' => (string)$xml->$name->COD_CENTRO_SEUR,
                'city'      => (string)$xml->$name->NOM_POBLACION,
                'post_code' => (string)$xml->$name->CODIGO_POSTAL,
                'phone'     => (string)$xml->$name->TELEFONO_1,
                'tipovia'   => (string)$xml->$name->COD_TIPO_VIA,
                'nomcorto'  => (string)$xml->$name->NOM_CORTO,
                'numvia'    => (string)$xml->$name->NUM_VIA,
                'nompoblac' => (string)$xml->$name->NOM_POBLACION,
                'lat'       => (float)$xml->$name->LATITUD,
                'lng'       => (float)$xml->$name->LONGITUD,
                'timetable' => (string)$xml->$name->HORARIO
            );
        }
        return $centro;
    }

    function seur_after_seur_2shop_shipping_rate( $method, $index ) {
        global $postcode_seur;

        $custom_name_seur_2shop = get_option( 'seur_2SHOP_custom_name_field');
        $chosen_methods         = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping        = $chosen_methods[0];

        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST; // fallback for final checkout (non-ajax)
        }
        if ( isset( $post_data['billing_postcode'] ) && ! $postcode_seur ) {
                $postcode_seur = $post_data['billing_postcode'];
            }
        if ( isset( $post_data['shipping_postcode'] )  && ! $postcode_seur ) {
                $postcode_seur = $post_data['shipping_postcode'];
            }

        if ( ! empty( $custom_name_seur_2shop ) ) {
            $custom_name_seur_2shop = $custom_name_seur_2shop;
        } else {
            $custom_name_seur_2shop = 'SEUR 2SHOP';
        }
        if ( ( $method->label == $custom_name_seur_2shop ) && ( $method->id == $chosen_shipping ) && ! is_checkout() ) {
            echo '<br />';
            _e( 'You will have to select a location in the next step', 'seur');
            }

        if ( ( $method->label == $custom_name_seur_2shop ) && ( $method->id == $chosen_shipping ) && is_checkout() ) {

            ob_start();
            $local_pickups_array = seur_get_local_pickups( $postcode_seur );

            for( $i=0; $i < count( $local_pickups_array ); $i++ ){

                /*'company'   => (string)$xml->$name->NOM_CENTRO_SEUR,
                'codCentro' => (string)$xml->$name->COD_CENTRO_SEUR,
                'city'      => (string)$xml->$name->NOM_POBLACION,
                'post_code' => (string)$xml->$name->CODIGO_POSTAL,
                'phone'     => (string)$xml->$name->TELEFONO_1,
                'tipovia'   => (string)$xml->$name->COD_TIPO_VIA,
                'nomcorto'  => (string)$xml->$name->NOM_CORTO,
                'numvia'    => (string)$xml->$name->NUM_VIA,
                'nompoblac' => (string)$xml->$name->NOM_POBLACION,
                'lat'       => (float)$xml->$name->LATITUD,
                'lng'       => (float)$xml->$name->LONGITUD,
                'timetable' => (string)$xml->$name->HORARIO
                */
                if ( $i == 0 ) {
                    $print_js = "{";
                } else {
                    $print_js .= "{";
                }
                $print_js .=        "lat: " . addslashes( $local_pickups_array[$i]['lat'] ) . ",";
                $print_js .=        "lon: " . addslashes( $local_pickups_array[$i]['lng'] ) . ",";
                $print_js .=        "title: '" . addslashes( $local_pickups_array[$i]['company'] ) . "',";
                $print_js .=        "codCentro: '" . addslashes( $local_pickups_array[$i]['codCentro'] ) . "',";
                $print_js .=        "address: '" . addslashes( $local_pickups_array[$i]['nomcorto'] ) . " " . addslashes( $local_pickups_array[$i]['numvia'] ) . "',";
                $print_js .=        "city: '". addslashes( $local_pickups_array[$i]['post_code'] ) . " " . addslashes( $local_pickups_array[$i]['city'] ) ."',";
                $print_js .=        "city_only: '". addslashes( $local_pickups_array[$i]['city'] ) ."',";
                $print_js .=        "post_code: '" . addslashes( $local_pickups_array[$i]['post_code'] ) . "',";
                $print_js .=        "timetable: '" . addslashes( $local_pickups_array[$i]['timetable'] ) . "',";
                $print_js .=        "html: [";
                $print_js .=        "'<h3>" . addslashes( $local_pickups_array[$i]['company'] ) . "</h3>',";
                $print_js .=        "'<p>" . addslashes( $local_pickups_array[$i]['nomcorto'] ) . " " . addslashes( $local_pickups_array[$i]['numvia'] ) . "<br />',";
                $print_js .=        "'" . addslashes( $local_pickups_array[$i]['post_code'] ) . " " . addslashes( $local_pickups_array[$i]['city'] ) . "</p>',";
                $print_js .=        "'<p>" . __('Timetable: ', 'seur' ) . addslashes( $local_pickups_array[$i]['timetable'] ) . "</p>'";
                $print_js .=        "].join(''),";
                $print_js .=        "zoom: 15";
                $print_js .=  "},";
            }
            echo '<br />';
            _e( 'Choose a location:', 'seur' );
            echo '<div id="controls"></div>';
            echo '<tr id="seur-map">';
            echo '<td colspan="2">';
            echo '<div id="seur-gmap" style="with:300px;height:250px;"></div>';
            echo '</td>';
            echo '</tr>';
            echo "<script type='text/javascript'>
                    jQuery(document).ready(function( $ ){

                    var html_seurdropdown = {
                        activateCurrent: function(index) {
                            this.html_element.find('select').val(index);
                        },

                        getHtml: function() {
                            var self = this,
                                html = '',
                                title,
                                a;

                            if (this.ln > 1) {
                                html += '<select name=\"seur_pickup\" class=\"select dropdown controls seur-pickup-select2' + this.o.controls_cssclass + '\">';

                                if (this.ShowOnMenu(this.view_all_key)) {
                                    html += '<option value=\"' + this.view_all_key + '\">' + this.o.view_all_text + '</option>';
                                }

                                for (a = 0; a < this.ln; a += 1) {
                                    if (this.ShowOnMenu(a)) {
                                        html += '<option value=\"' + (a + 1) + '\">' + (this.o.locations[a].title || ('#' + (a + 1))) + '</option>';
                                    }
                                }
                                html += '</select>';

                                for (a = 0; a < this.ln; a += 1) {
                                    if (this.ShowOnMenu(a)) {
                                        html += '<input type=\"hidden\" name=\"seur_title_' + (a + 1) + '\" value=\"' + (this.o.locations[a].title || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_codCentro_' + (a + 1) + '\" value=\"' + (this.o.locations[a].codCentro || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_address_' + (a + 1) + '\" value=\"' + (this.o.locations[a].address || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_city_' + (a + 1) + '\" value=\"' + (this.o.locations[a].city_only || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_postcode_' + (a + 1) + '\" value=\"' + (this.o.locations[a].post_code || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_lat_' + (a + 1) + '\" value=\"' + (this.o.locations[a].lat || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_lon_' + (a + 1) + '\" value=\"' + (this.o.locations[a].lon || ('#' + (a + 1))) + '\">';
                                        html += '<input type=\"hidden\" name=\"seur_timetable_' + (a + 1) + '\" value=\"' + (this.o.locations[a].timetable || ('#' + (a + 1))) + '\">';
                                    }
                                }

                                html = $(html).bind('change', function() {
                                    self.ViewOnMap(this.value);
                                });
                            }

                            title = this.o.controls_title;
                            if (this.o.controls_title) {
                                title = $('<div class=\"controls_title\"></div>').css(this.o.controls_applycss ? {
                                    fontWeight: 'bold',
                                    fontSize: this.o.controls_on_map ? '12px' : 'inherit',
                                    padding: '3px 10px 5px 0'
                                } : {}).append(this.o.controls_title);
                            }

                            this.html_element = $('<div class=\"wrap_controls\"></div>').append(title).append(html);

                            return this.html_element;
                        }
                    };

                    var SeurPickupsLocs = [" .
                        $print_js
                        . "
                    ];

                    var maplace = new Maplace();
                    maplace.AddControl('seurdropdown', html_seurdropdown);
                    maplace.Load({
                    locations: SeurPickupsLocs,
                    map_div: '#seur-gmap',
                    controls_on_map: false,
                    controls_type: 'seurdropdown'
                });
                    });
                    </script>";
            ob_end_flush();
        }
    }

    function seur_add_map_type_select2() {

         if ( is_checkout() ) {
            ?>
            <script type="text/javascript">

            jQuery(document).ready(function($) {
                $('.seur-pickup-select2').select2();
            });
            </script>
            <?php
        }

    }

	function seur_add_2shop_data_to_order( $order_id ) {

	    if ( ! empty( $_POST['seur_pickup'] ) ) {

	        $id = $_POST['seur_pickup'];

	        $seur_title     = 'seur_title_'     . $id;
			$seur_codCentro = 'seur_codCentro_' . $id;
			$seur_address   = 'seur_address_'   . $id;
			$seur_city      = 'seur_city_'      . $id;
			$seur_postcode  = 'seur_postcode_'  . $id;
			$seur_lat       = 'seur_lat_'       . $id;
			$seur_lon       = 'seur_lon_'       . $id;
			$seur_timetable = 'seur_timetable_' . $id;

	        $title     = sanitize_text_field( $_POST[ $seur_title     ]);
            $codCentro = sanitize_text_field( $_POST[ $seur_codCentro ]);
            $address   = sanitize_text_field( $_POST[ $seur_address   ]);
            $city      = sanitize_text_field( $_POST[ $seur_city      ]);
            $postcode  = sanitize_text_field( $_POST[ $seur_postcode  ]);
            $lat       = sanitize_text_field( $_POST[ $seur_lat       ]);
            $lon       = sanitize_text_field( $_POST[ $seur_lon       ]);
            $timetable = sanitize_text_field( $_POST[ $seur_timetable ]);

	        update_post_meta( $order_id, 'seur_2shop_title',     $title     );
	        update_post_meta( $order_id, 'seur_2shop_codCentro', $codCentro );
	        update_post_meta( $order_id, 'seur_2shop_address',   $address   );
	        update_post_meta( $order_id, 'seur_2shop_city',      $city      );
	        update_post_meta( $order_id, 'seur_2shop_postcode',  $postcode  );
	        update_post_meta( $order_id, 'seur_2shop_lat',       $lat       );
	        update_post_meta( $order_id, 'seur_2shop_lon',       $lon       );
	        update_post_meta( $order_id, 'seur_2shop_timetable', $timetable );
	    }
	}
    if ( $localpickup_is_active == '1'  ) {

        add_filter( 'woocommerce_shipping_methods',                  'add_seur_local_shipping_method'             );
        add_action( 'woocommerce_shipping_init',                     'seur_local_shipping_method'                 );
        add_action( 'woocommerce_review_order_before_cart_contents', 'seur_local_validate_order' ,             10 );
        add_action( 'woocommerce_after_checkout_validation',         'seur_local_validate_order' ,             10 );
        add_action( 'wp_enqueue_scripts',                            'seur_map_checkout_load_js'                  );
        add_action( 'woocommerce_after_shipping_rate',               'seur_after_seur_2shop_shipping_rate', 10, 2 );
        add_action( 'wp_footer',                                     'seur_add_map_type_select2'                  );
        add_action( 'woocommerce_checkout_update_order_meta',        'seur_add_2shop_data_to_order'               );

        }