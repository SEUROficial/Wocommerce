<?php
/**
 * Class seur local shipping method.
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Seur_Local_Shipping_Method class.
 *
 * @class Seur_Local_Shipping_Method
 */
class Seur_Local_Shipping_Method extends WC_Shipping_Method {

	/**
	 * Constructor. The instance ID is passed to this.
	 *
	 * @param int $instance_id Intance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                   = 'seurlocal';
		$this->instance_id          = absint( $instance_id );
		$this->method_title         = __( 'SEUR Local Pickup', 'seur' );
		$this->method_description   = __( 'SEUR Local Pickup Shipping Method, Please configure SEUR data in <code>SEUR -> Settings</code>', 'seur' );
		$this->supports             = array(
			'shipping-zones',
			'instance-settings',
		);
		$this->instance_form_fields = array(
			'title' => array(
				'title'       => __( 'Method Title' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.' ),
				'default'     => __( 'SEUR Local Pickup' ),
				'desc_tip'    => true,
			),
		);
		$this->title                = $this->get_option( 'title' );
		$this->log                  = new WC_Logger();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Calculate Rate
	 *
	 * @param array $package Array package.
	 */
	public function calculate_shipping( $package = array() ) {
		global $woocommerce, $postcode_seur;

		$rates                 = array();
		$seur_response         = array();
		$rate_requests         = array();
		$rates_type            = get_option( 'seur_rates_type_field' );
		$localpickup_is_active = get_option( 'seur_activate_local_pickup_field' );
		$this->log->add( 'seur', 'calculate_shipping( $package = array() ): PROBANDO' );
		$this->log->add( 'seur', 'calculate_shipping( $package = array() ): ' . print_r( $package, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		// Only return rates if the package has a destination including country.
		if ( '' === $package['destination']['country'] ) {
			return;
		}
		if ( '1' !== $localpickup_is_active ) {
			return;
		}
		if ( 'price' === $rates_type ) {
			$price = $package['cart_subtotal'];
		} else {
			$weight        = 0;
			$cost          = 0;
			$country       = $package['destination']['country'];
			$package_price = $package['contents_cost'];

			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
				$weight   = $weight + $_product->get_weight() * $values['quantity'];
			}
					$price = wc_get_weight( $weight, 'kg' );
		}

		$this->log->add( 'seur', '$country: ' . $country );
		$this->log->add( 'seur', '$state: ' . $state );
		$this->log->add( 'seur', '$postcode_seur: ' . $postcode_seur );
		$this->log->add( 'seur', '$price: ' . $price );
		$country       = $package['destination']['country'];
		$state         = $package['destination']['state'];
		$postcode_seur = $package['destination']['postcode'];
		$rate_requests = seur_show_availables_rates( $country, $state, $postcode_seur, $price );

		if ( $rate_requests ) {
			// parse the results
			foreach ( $rate_requests as $rate ) {
				$idrate        = $rate['ID'];
				$countryrate   = $rate['country'];
				$staterate     = $rate['state'];
				$postcoderate  = $rate['postcode'];
				$raterate      = $rate['rate'];
				$ratepricerate = $rate['rateprice'];
				if ( $rate && 'SEUR 2SHOP' === $raterate ) {
					$sort = 999;
					if ( 'price' === $rates_type ) {
						$ratepricerate = $ratepricerate;
					} else {
						$ratepricerate = seur_filter_price_rate_weight( $package_price, $raterate, $ratepricerate, $countryrate );
					}
					$rate_name        = seur_get_custom_rate_name( $raterate );
					$rates[ $idrate ] = array(
						'id'    => $idrate,
						'label' => $rate_name,
						'cost'  => $ratepricerate,
						'sort'  => $sort,
					);
				}
			}
		} //foreach ( $package_requests )
		// Add rates
		if ( $rates ) {
				uasort( $rates, array( $this, 'sort_rates' ) );
			foreach ( $rates as $key => $rate ) {
					$this->add_rate( $rate );
			}
			// Fallback
		}
	}

	public function sort_rates( $a, $b ) {
		if ( $a['sort'] === $b['sort'] ) {
			return 0;
		}
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}
function seur_local_validate_order( $posted ) {
	$packages       = WC()->shipping->get_packages();
	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

	if ( is_array( $chosen_methods ) && in_array( 'seurlocal', $chosen_methods, true ) ) {
		foreach ( $packages as $i => $package ) {
			if ( 'seurlocal' !== $chosen_methods[ $i ] ) {
				continue;
			}
			$seur_local_shipping_method = new Seur_Local_Shipping_Method();
			$weightlimit                = (int) 20;
			$weight                     = 0;
			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
				$weight   = $weight + $_product->get_weight() * $values['quantity'];
			}
			$weight = wc_get_weight( $weight, 'kg' );
			if ( $weight > $weightlimit ) {
				$message     = sprintf( __( 'Sorry, %1$d kg exceeds the maximum weight of %2$d kg for %3$s', 'seur' ), $weight, $weightlimit, $seur_local_shipping_method->method_title );
				$messagetype = 'error';
				if ( ! wc_has_notice( $message, $messagetype ) ) {
					wc_add_notice( $message, $messagetype );
				}
			}
		}
	}
}

function seur_map_checkout_load_js() {
	if ( is_checkout() ) {
		$seur_gmap_api = get_option( 'seur_google_maps_api_field' );
		if ( empty( $seur_gmap_api ) ) {
			return;
		}
		wp_enqueue_script( 'seur-gmap', 'https://maps.google.com/maps/api/js?libraries=geometry&v=3&key=' . $seur_gmap_api, array(), SEUR_OFFICIAL_VERSION, false );
		wp_enqueue_script( 'seur-map', SEUR_PLUGIN_URL . 'assets/js/maplace.min.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION, false );
	}
}

function seur_get_local_pickups( $country, $city, $postcode ) {

	if ( 'ES' === $country || 'PT' === $country || 'AR' === $country ) {
		$user_data   = seur_get_user_settings();
		$usercom     = $user_data[0]['seurcom_usuario'];
		$passcom     = $user_data[0]['seurcom_contra'];
		$sc_options  = array(
			'connection_timeout' => 30,
		);
		$soap_client = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );
		$xml         = '
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
		$data        = array( 'in0' => strtoupper( $xml ) );
		$response    = $soap_client->puntosDeVentaStr( $data );
		$xml         = simplexml_load_string( utf8_decode( $response->out ) );
		$centro      = array();
		$num         = (int) $xml->attributes()->NUM[0];

		for ( $i = 1; $i <= $num; $i++ ) {
			$name     = 'REG' . $i;
			$centro[] = array(
				'id'        => $i,
				'company'   => (string) $xml->$name->NOM_CENTRO_SEUR, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'codCentro' => (string) $xml->$name->COD_CENTRO_SEUR, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'city'      => (string) $xml->$name->NOM_POBLACION, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'post_code' => (string) $xml->$name->CODIGO_POSTAL, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'phone'     => (string) $xml->$name->TELEFONO_1, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'tipovia'   => (string) $xml->$name->COD_TIPO_VIA, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'nomcorto'  => (string) $xml->$name->NOM_CORTO, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'numvia'    => (string) $xml->$name->NUM_VIA, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'nompoblac' => (string) $xml->$name->NOM_POBLACION, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'lat'       => (float) $xml->$name->LATITUD, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'lng'       => (float) $xml->$name->LONGITUD, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'timetable' => (string) $xml->$name->HORARIO, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			);
		}
		return $centro;
	} else {
		$user_data   = seur_get_user_settings();
		$usercom     = $user_data[0]['seurcom_usuario'];
		$passcom     = $user_data[0]['seurcom_contra'];
		$sc_options  = array(
			'connection_timeout' => 30,
		);
		$soap_client = new SoapClient( 'http://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicosImpl?wsdl', $sc_options );
		$data        = array(
			'address'             => '',
			'countrycode'         => '',
			'requestID'           => '',
			'date_from'           => '',
			'max_pudo_number'     => '',
			'max_distance_search' => '',
			'weight'              => '',
			'category'            => '',
			'holiday_tolerant'    => '',
			'zipCode'             => strtoupper( $postcode ),
			'city'                => strtoupper( $city ),
			'userLDAP'            => strtoupper( $usercom ),
			'passLDAP'            => strtoupper( $passcom ),
		);
		$response    = $soap_client->getPudoListStr( $data );
		$xml         = simplexml_load_string( utf8_decode( $response->out ) );
		$xmljs       = json_decode( wp_json_encode( $xml ), 1 );
		$centro      = array();
		$i           = 0;
		foreach ( $xml as  $element ) {
			$num = $element->count();
			++$i;
		}
		for ( $i = 0; $i + 1 <= $num; $i++ ) {
			$centro[] = array(
				'id'        => $i,
				'company'   => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['NAME'],
				'codCentro' => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['PUDO_ID'],
				'city'      => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['CITY'],
				'post_code' => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['ZIPCODE'],
				'phone'     => '',
				'tipovia'   => '',
				'nomcorto'  => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['ADDRESS1'],
				'numvia'    => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['STREETNUM'],
				'nompoblac' => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['CITY'],
				'lat'       => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['LATITUDE'],
				'lng'       => $xmljs['PUDO_ITEMS']['PUDO_ITEM'][ $i ]['LONGITUDE'],
				'timetable' => '',
			);
		}
		return $centro;
	}
}

function seur_after_seur_2shop_shipping_rate( $method, $index ) {
	global $postcode_seur;

	$custom_name_seur_2shop = get_option( 'seur_2SHOP_custom_name_field' );
	$chosen_methods         = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping        = $chosen_methods[0];

	if ( isset( $_POST['post_data'] ) ) {
		parse_str( $_POST['post_data'], $post_data );
	} else {
		$post_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	if ( isset( $post_data['shipping_postcode'] ) && '' !== $post_data['shipping_postcode'] ) {
		$postcode_seur = $post_data['shipping_postcode'];
	} else {
		$postcode_seur = $post_data['billing_postcode'];
	}

	if ( isset( $post_data['shipping_country'] ) && '' !== $post_data['shipping_country'] ) {
		$country_seur = $post_data['shipping_country'];
	} else {
		$country_seur = $post_data['billing_country'];
	}

	if ( isset( $post_data['shipping_city'] ) && '' !== $post_data['shipping_city'] ) {
		$city = $post_data['shipping_city'];
	} else {
		$city = $post_data['billing_city'];
	}

	if ( ! empty( $custom_name_seur_2shop ) ) {
		$custom_name_seur_2shop = $custom_name_seur_2shop;
	} else {
		$custom_name_seur_2shop = 'SEUR 2SHOP';
	}

	if ( isset( $post_data['seur_pickup'] ) ) {
		if ( 'all' === $post_data['seur_pickup'] ) {
			$option_selected = '500000';
		} else {
			$option_selected = $post_data['seur_pickup'];
		}
	} else {
		$option_selected = '0';
	}

	if ( ! empty( $country_seur ) && ! empty( $city ) && ! empty( $postcode_seur ) ) {

		if ( ( $method->label === $custom_name_seur_2shop ) && ( $method->id === $chosen_shipping ) && ! is_checkout() ) {
			echo '<br />';
			esc_html_e( 'You will have to select a location in the next step', 'seur' );
		}
		if ( ( $method->label === $custom_name_seur_2shop ) && ( $method->id === $chosen_shipping ) && is_checkout() ) {
			$local_pickups_array = seur_get_local_pickups( $country_seur, $city, $postcode_seur );
			for ( $i = 0; $i < count( $local_pickups_array ); $i++ ) {
				if ( 0 === $i ) {
					$print_js = '{';
				} else {
					$print_js .= '{';
				}
				$print_js .= 'lat: ' . addslashes( $local_pickups_array[ $i ]['lat'] ) . ',';
				$print_js .= 'lon: ' . addslashes( $local_pickups_array[ $i ]['lng'] ) . ',';
				$print_js .= "title: '" . addslashes( $local_pickups_array[ $i ]['company'] ) . "',";
				$print_js .= "codCentro: '" . addslashes( $local_pickups_array[ $i ]['codCentro'] ) . "',";
				$print_js .= "address: '" . addslashes( $local_pickups_array[ $i ]['nomcorto'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['numvia'] ) . "',";
				$print_js .= "city: '" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['city'] ) . "',";
				$print_js .= "city_only: '" . addslashes( $local_pickups_array[ $i ]['city'] ) . "',";
				$print_js .= "post_code: '" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . "',";
				$print_js .= "timetable: '" . addslashes( $local_pickups_array[ $i ]['timetable'] ) . "',";
				$print_js .= "option: '" . $option_selected . "',";
				$print_js .= 'html: [';
				$print_js .= "'<h3>" . addslashes( $local_pickups_array[ $i ]['company'] ) . "</h3>',";
				$print_js .= "'<p>" . addslashes( $local_pickups_array[ $i ]['nomcorto'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['numvia'] ) . "<br />',";
				$print_js .= "'" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['city'] ) . "</p>',";
				$print_js .= "'<p>" . __( 'Timetable: ', 'seur' ) . addslashes( $local_pickups_array[ $i ]['timetable'] ) . "</p>'";
				$print_js .= "].join(''),";
				$print_js .= 'zoom: 15';
				$print_js .= '},';
			}
			echo '<br />';
			esc_html_e( 'Choose a location:', 'seur' );
			echo '<div id="controls"></div>';
			echo '<div id="seur-gmap" style="with:300px;height:250px;"></div>';
			echo "<script type='text/javascript'>
			jQuery(document).ready(function( $ ){
				var html_seurdropdown = {
					activateCurrent: function(index) {
						this.html_element.find('select.seur-pickup-select2').val(index);
					},
					getHtml: function() {
						var self = this,
						html = '',
						title,
						a;
						if (this.ln > 1) {
							html += '<select name=\"seur_pickup\" class=\"seur-pickup-select2' + this.o.controls_cssclass + '\">';
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
					esc_js( $print_js )
					. "
				];
				var maplace = new Maplace();
				maplace.AddControl('seurdropdown', html_seurdropdown);
				maplace.Load({
					locations: SeurPickupsLocs,
					map_div: '#seur-gmap',
					start: '" . esc_html( $option_selected ) . "',
					controls_on_map: false,
					controls_type: 'seurdropdown'
				});
			});
			</script>";
		}
	}
}

/**
 * SEUR add map type Select2
 */
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

/**
 * SEUR Validate 2shop
 */
function seur_validation_2shop_fields() {

	$seur_pickup     = '';
	$seur_cod_centro = '';
	$seur_pickup     = sanitize_text_field( wp_unslash( $_POST['seur_pickup'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
	$seur_mobi_phone = sanitize_text_field( wp_unslash( $_POST['billing_mobile_phone'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing

	if ( ! empty( $seur_pickup ) && 'all' === $seur_pickup ) {
		wc_add_notice( __( 'You need to select a Local Pickup.', 'seur' ), 'error' );
	}
	if ( ! empty( $seur_pickup ) && empty( $seur_mobi_phone ) ) {
		wc_add_notice( __( 'Mobile phone for selected shipping method is needed.', 'seur' ), 'error' );
	}
}

/**
 * SEUR add 2shop data to order
 *
 * @param int $order_id Order ID.
 */
function seur_add_2shop_data_to_order( $order_id ) {

	if ( ! empty( $_POST['seur_pickup'] ) ) {
		$id              = $_POST['seur_pickup'];
		$seur_title      = 'seur_title_' . $id;
		$seur_cod_centro = 'seur_codCentro_' . $id;
		$seur_address    = 'seur_address_' . $id;
		$seur_city       = 'seur_city_' . $id;
		$seur_postcode   = 'seur_postcode_' . $id;
		$seur_lat        = 'seur_lat_' . $id;
		$seur_lon        = 'seur_lon_' . $id;
		$seur_timetable  = 'seur_timetable_' . $id;
		$title           = sanitize_text_field( $_POST[ $seur_title ] );
		$codcentro       = sanitize_text_field( $_POST[ $seur_cod_centro ] );
		$address         = sanitize_text_field( $_POST[ $seur_address ] );
		$city            = sanitize_text_field( $_POST[ $seur_city ] );
		$postcode        = sanitize_text_field( $_POST[ $seur_postcode ] );
		$lat             = sanitize_text_field( $_POST[ $seur_lat ] );
		$lon             = sanitize_text_field( $_POST[ $seur_lon ] );
		$timetable       = sanitize_text_field( $_POST[ $seur_timetable ] );

		update_post_meta( $order_id, '_seur_2shop_title', $title );
		update_post_meta( $order_id, '_seur_2shop_codCentro', $codcentro );
		update_post_meta( $order_id, '_seur_2shop_address', $address );
		update_post_meta( $order_id, '_seur_2shop_city', $city );
		update_post_meta( $order_id, '_seur_2shop_postcode', $postcode );
		update_post_meta( $order_id, '_seur_2shop_lat', $lat );
		update_post_meta( $order_id, '_seur_2shop_lon', $lon );
		update_post_meta( $order_id, '_seur_2shop_timetable', $timetable );
	}
}
$localpickup_is_active = get_option( 'seur_activate_local_pickup_field' );

if ( '1' === $localpickup_is_active ) {

	add_action( 'woocommerce_review_order_before_cart_contents', 'seur_local_validate_order', 10 );
	add_action( 'woocommerce_after_checkout_validation', 'seur_local_validate_order', 10 );
	add_action( 'woocommerce_after_shipping_rate', 'seur_after_seur_2shop_shipping_rate', 1, 2 );
	// add_action( 'woocommerce_review_order_before_order_total', 'seur_after_seur_2shop_shipping_rate');.
	add_action( 'wp_enqueue_scripts', 'seur_map_checkout_load_js' );
	add_action( 'wp_footer', 'seur_add_map_type_select2' );
	add_action( 'woocommerce_checkout_update_order_meta', 'seur_add_2shop_data_to_order' );
	add_action( 'woocommerce_after_checkout_validation', 'seur_validation_2shop_fields' );
}
