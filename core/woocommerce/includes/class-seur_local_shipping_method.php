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
     * @var WC_Logger
     */
    private $log;

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

		// Only return rates if the package has a destination including country.
		if ( '' === $package['destination']['country'] ) {
			return;
		}
		if ( '1' !== $localpickup_is_active ) {
			return;
		}
        $package_price = $package['contents_cost'];
		if ( $rates_type != 'price' ) {
			$weight        = 0.0;

			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
				$weight   = (float)$weight + ((float)$_product->get_weight() * (float)$values['quantity']);
			}
            $package_price = wc_get_weight( $weight, 'kg' );
		}

		$country       = $package['destination']['country'];
		$state         = $package['destination']['state'];
		$postcode_seur = $package['destination']['postcode'];
		$rate_requests = seur_show_availables_rates( $country, $state, $postcode_seur, $package_price );
        $this->log->add( 'seur', '$country: ' . $country );
        $this->log->add( 'seur', '$state: ' . $state );
        $this->log->add( 'seur', '$postcode_seur: ' . $postcode_seur );
        $this->log->add( 'seur', '$price: ' . $package_price );

        if ( $rate_requests ) {
            // parse the results.
			foreach ( $rate_requests as $rate ) {
                $idrate        = $rate['ID'];
				$countryrate   = $rate['country'];
				$staterate     = $rate['state'];
				$postcoderate  = $rate['postcode'];
				$raterate      = $rate['rate'];
				$ratepricerate = $rate['rateprice'];
				if ( $rate && 'SEUR 2SHOP' === $raterate  ||  $rate && 'CLASSIC 2SHOP' === $raterate ) {
                    $sort = 999;
					if ( $rates_type != 'price') {
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
		}

        // Add rates.
		if ( $rates ) {
            uasort($rates, array($this, 'sort_rates'));
            foreach ($rates as $key => $rate) {
                $this->add_rate($rate);
            }
            // Fallback.
        }
	}

	/**
	 * Sort Rates
	 *
	 * @param array $a Order.
	 * @param array $b Order.
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] === $b['sort'] ) {
			return 0;
		}
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}
/**
 * SEUR Local Validate Order
 *
 * @param WP_Post $posted Post Data.
 */
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
			$weight                     = 0.0;
			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
				$weight   = (float)$weight + ((float)$_product->get_weight() * (float)$values['quantity']);
			}
			$weight = wc_get_weight( $weight, 'kg' );
			if ( $weight > $weightlimit ) {
				// translators: weight, weight limit and  Method name.
				$message     = sprintf( esc_html__( 'Sorry, %1$d kg exceeds the maximum weight of %2$d kg for %3$s', 'seur' ), $weight, $weightlimit, $seur_local_shipping_method->method_title );
				$messagetype = 'error';
				if ( ! wc_has_notice( $message, $messagetype ) ) {
					wc_add_notice( $message, $messagetype );
				}
			}
		}
	}
}

/**
 * SEUR Map Checkout Load JS.
 */
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

/**
 * SEUR Get Local Pickups.
 *
 * @param string $country Country name.
 * @param string $city City name.
 * @param string $postcode Postcode.
 */
function seur_get_local_pickups( $country, $city, $postcode ) {

    // if ( 'ES' === $country || 'PT' === $country || 'AD' === $country ) {
        $seur_adr = seur()->get_api_addres() . SEUR_API_PICKUPS . '?countryCode=' . $country . '&postalCode=' . $postcode . '&cityName=' . $city;

        $data = array(
            'method' => 'GET',
            'timeout' => 45,
            'httpversion' => '1.0',
            'user-agent' => 'WooCommerce',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
                'Authorization' => seur()->get_token_b(),
            ),
        );
        seur()->slog('$seur_adr: ' . $seur_adr);
        seur()->slog('$data: ' . print_r($data, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        $response = wp_remote_post(
            $seur_adr,
            $data,
        );
        $body = json_decode(wp_remote_retrieve_body($response));
        seur()->slog('$body: ' . print_r($body, true)); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        $centro = array();
        $i = 1;
        foreach ($body->data as $data) {
            $centro[] = array(
                'id' => $i,
                'depot' => (string)(property_exists($data,'depot')?$data->depot:''), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'post_code' => $data->postalCode, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'codCentro' => (string)(property_exists($data,'code')?$data->code:''), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'company' => (string)$data->name, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'type' => (property_exists($data,'type')?$data->type:''),
                'address' => (string)$data->address, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'city' => (string)$data->cityName, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'pudoId' => (string)$data->pudoId,
                'lat' => (float)$data->latitude, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'lng' => (float)$data->longitude, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'tipovia' => (string)(property_exists($data,'streetType')?$data->streetType:''), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'numvia' => (string)trim($data->streetNumber??''), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'phone' => (string)'', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'nomcorto' => (string)'', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                'timetable' => (string)getTimeTable($data->openingTime), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            );
            $i++;
        }
        seur()->slog('$centro: ' . print_r($centro, true));
        return $centro;
    //}
}

function getTimeTable($timetable) {
    $tb = '';
    if (isset($timetable->weekDays)) {
        foreach ($timetable->weekDays as $day) {
            $tb .= $day->day .' '. $day->openingHours .'<br>';
        }
    }
    return $tb;
}
/**
 * SEUR After 2shop shipping rate.
 *
 * @param string $method Method name.
 * @param string $index City name.
 */
function seur_after_seur_2shop_shipping_rate( $method, $index ) {
	global $postcode_seur;

	$custom_name_seur_2shop = get_option( 'seur_2shop_custom_name_field' );
    $custom_name_classic_2shop = get_option( 'seur_classic_int_2shop_custom_name_field' );
	$chosen_methods         = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping        = $chosen_methods[0];

	if ( isset( $_POST['post_data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		parse_str( sanitize_text_field( wp_unslash( $_POST['post_data'] ) ), $post_data ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	} else {
		$post_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	}
    $postcode_seur = $post_data['billing_postcode']??'';
    $city = $post_data['billing_city']??'';
    $country_seur = $post_data['billing_country']??'';
    if ( isset($post_data['ship_to_different_address']) && $post_data['ship_to_different_address'] == 1 ) {
        $postcode_seur = $post_data['shipping_postcode'];
    	$city = $post_data['shipping_city'];
        $country_seur = $post_data['shipping_country'];
    }
	if ( empty( $custom_name_seur_2shop ) ) {
		$custom_name_seur_2shop = 'SEUR 2SHOP';
	}
    if ( empty( $custom_name_classic_2shop ) ) {
        $custom_name_classic_2shop = 'CLASSIC 2SHOP';
    }
    $option_selected = '0';
	if ( isset( $post_data['seur_pickup'] ) ) {
        $option_selected = $post_data['seur_pickup'];
		if ( 'all' === $post_data['seur_pickup'] ) {
			$option_selected = '500000';
		}
	}
	if ( ! empty( $country_seur ) && ! empty( $city ) && ! empty( $postcode_seur ) ) {
		if ( ( $method->label === $custom_name_seur_2shop || $method->label === $custom_name_classic_2shop )
              && ( $method->id === $chosen_shipping ) && ! is_checkout() ) {
			echo '<br />';
			esc_html_e( 'You will have to select a location in the next step', 'seur' );
		}
		if ( ( $method->label === $custom_name_seur_2shop || $method->label === $custom_name_classic_2shop )
              && ( $method->id === $chosen_shipping ) && is_checkout() ) {
			$local_pickups_array = seur_get_local_pickups( $country_seur, $city, $postcode_seur );
			for ( $i = 0; $i < count( $local_pickups_array ); $i++ ) { // phpcs:ignore Squiz.PHP.DisallowSizeFunctionsInLoops.Found,Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
				if ( 0 === $i ) {
					$print_js = '{';
				} else {
					$print_js .= '{';
				}

				$print_js .= "depot: '" . addslashes( $local_pickups_array[ $i ]['depot'] ) . "',";
				$print_js .= "post_code: '" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . "',";
				$print_js .= "codCentro: '" . addslashes( $local_pickups_array[ $i ]['codCentro'] ) . "',";
				$print_js .= "title: '" . addslashes( $local_pickups_array[ $i ]['company'] ) . "',";
				$print_js .= "type: '" . addslashes( $local_pickups_array[ $i ]['type'] ) . "',";
				$print_js .= "address2: '" . addslashes( $local_pickups_array[ $i ]['address'] ) . "',";
				$print_js .= "city_only: '" . addslashes( $local_pickups_array[ $i ]['city'] ) . "',";
				$print_js .= "pudoId: '" . addslashes( $local_pickups_array[ $i ]['pudoId'] ) . "',";
				$print_js .= 'lat: ' . addslashes( $local_pickups_array[ $i ]['lat'] ) . ',';
				$print_js .= 'lon: ' . addslashes( $local_pickups_array[ $i ]['lng'] ) . ',';
				$print_js .= "streettype: '" . addslashes( $local_pickups_array[ $i ]['tipovia'] ) . "',";
				$print_js .= "numvia: '" . addslashes( $local_pickups_array[ $i ]['numvia'] ) . "',";
				$print_js .= "address: '" . addslashes( $local_pickups_array[ $i ]['tipovia'] ) . ' '.  addslashes( $local_pickups_array[ $i ]['address'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['numvia'] ) . "',";
				$print_js .= "city: '" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['city'] ) . "',";
				$print_js .= "timetable: '" . addslashes( $local_pickups_array[ $i ]['timetable'] ) . "',";
				$print_js .= "option: '" . $option_selected . "',";
				$print_js .= 'html: [';
				$print_js .= "'<strong>" . addslashes( $local_pickups_array[ $i ]['company'] ) . "</strong>',";
				$print_js .= "'<p>" . addslashes( $local_pickups_array[ $i ]['tipovia'] ) . ' '.  addslashes( $local_pickups_array[ $i ]['address'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['numvia'] ) . "<br />',";
				$print_js .= "'" . addslashes( $local_pickups_array[ $i ]['post_code'] ) . ' ' . addslashes( $local_pickups_array[ $i ]['city'] ) . "</p>',";
				$print_js .= "'<p>" . addslashes( $local_pickups_array[ $i ]['timetable'] ) . "</p>'";
				$print_js .= "].join(''),";
				$print_js .= 'zoom: 15';
				$print_js .= '},';
				seur()->slog( '$print_js: ' . $print_js );
			}
			echo '<br />';
			esc_html_e( 'Choose a location:', 'woocommerce-seur' );
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
							html += '<select name=\"seur_pickup\" required=\"required\" class=\"seur-pickup-select2' + this.o.controls_cssclass + '\">';
							if (this.ShowOnMenu(this.view_all_key)) {
								html += '<option value=\"' + this.view_all_key + '\" selected=\"selected\">' + this.o.view_all_text + '</option>';
							}
							for (a = 0; a < this.ln; a += 1) {
								if (this.ShowOnMenu(a)) {
									html += '<option value=\"' + (a + 1) + '\">' + (this.o.locations[a].title || ('#' + (a + 1))) + '</option>';
								}
							}

							html += '</select>';
							for (a = 0; a < this.ln; a += 1) {
								if (this.ShowOnMenu(a)) {
									html += '<input type=\"hidden\" name=\"seur_depot_' + (a + 1) + '\" value=\"' + (this.o.locations[a].depot || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_postcode_' + (a + 1) + '\" value=\"' + (this.o.locations[a].post_code || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_codCentro_' + (a + 1) + '\" value=\"' + (this.o.locations[a].codCentro || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_title_' + (a + 1) + '\" value=\"' + (this.o.locations[a].title || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_type_' + (a + 1) + '\" value=\"' + (this.o.locations[a].type || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_address_' + (a + 1) + '\" value=\"' + (this.o.locations[a].address2 || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_city_' + (a + 1) + '\" value=\"' + (this.o.locations[a].city_only || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_pudo_id_' + (a + 1) + '\" value=\"' + (this.o.locations[a].pudoId || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_lat_' + (a + 1) + '\" value=\"' + (this.o.locations[a].lat || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_lon_' + (a + 1) + '\" value=\"' + (this.o.locations[a].lon || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_streettype_' + (a + 1) + '\" value=\"' + (this.o.locations[a].streettype || ('#' + (a + 1))) + '\">';
									html += '<input type=\"hidden\" name=\"seur_numvia_' + (a + 1) + '\" value=\"' + (this.o.locations[a].numvia || ('#' + (a + 1))) + '\">';
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
                    wp_kses( $print_js, ['br' => [],'p' => [],'strong' => []] ) . "
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

    $seur_cutom_rate_ID = $_POST['shipping_method'][0];
    if (seur()->is_seur_local_method($seur_cutom_rate_ID)) {
        $seur_pickup     = sanitize_text_field( wp_unslash( $_POST['seur_pickup'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
        $seur_mobi_phone = sanitize_text_field( wp_unslash( $_POST['billing_mobile_phone'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing

        if ((!isset($seur_pickup) || empty($seur_pickup)) || (!empty($seur_pickup) && 'all' === $seur_pickup)) {
            wc_add_notice(__('You need to select a Local Pickup.', 'seur'), 'error');
        }
        if (!empty($seur_pickup) && empty($seur_mobi_phone)) {
            wc_add_notice(__('Mobile phone for selected shipping method is needed.', 'seur'), 'error');
        }
    }
}

/**
 * SEUR add 2shop data to order
 *
 * @param int $order_id Order ID.
 */
function seur_add_2shop_data_to_order( $order_id ) {

	if ( ! empty( $_POST['seur_pickup'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		seur()->slog( '$_POST: ' . print_r( $_POST, true ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		seur()->slog( '$_POST["seur_pickup"]: ' . print_r( $_POST['seur_pickup'], true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		$id              = sanitize_text_field( wp_unslash( $_POST['seur_pickup'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$seur_depot      = 'seur_depot_' . $id;
		$seur_postcode   = 'seur_postcode_' . $id;
		$seur_cod_centro = 'seur_codCentro_' . $id;
		$seur_title      = 'seur_title_' . $id;
		$seur_type       = 'seur_type_' . $id;
		$seur_address    = 'seur_address_' . $id;
		$seur_city       = 'seur_city_' . $id;
		$seur_pudo_id    = 'seur_pudo_id_' . $id;
		$seur_lat        = 'seur_lat_' . $id;
		$seur_lon        = 'seur_lon_' . $id;
		$seur_streettype = 'seur_streettype_' . $id;
		$seur_numvia     = 'seur_numvia_' . $id;
		$seur_timetable  = 'seur_timetable_' . $id;

		$depot      = sanitize_text_field( wp_unslash( $_POST[ $seur_depot ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$postcode   = sanitize_text_field( wp_unslash( $_POST[ $seur_postcode ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$cod_centro = sanitize_text_field( wp_unslash( $_POST[ $seur_cod_centro ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$title      = sanitize_text_field( wp_unslash( $_POST[ $seur_title ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$type       = sanitize_text_field( wp_unslash( $_POST[ $seur_type ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$address    = sanitize_text_field( wp_unslash( $_POST[ $seur_address ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$city       = sanitize_text_field( wp_unslash( $_POST[ $seur_city ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
        $pudoId     = sanitize_text_field( wp_unslash( $_POST[ $seur_pudo_id ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$lat        = sanitize_text_field( wp_unslash( $_POST[ $seur_lat ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$lon        = sanitize_text_field( wp_unslash( $_POST[ $seur_lon ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$streettype = sanitize_text_field( wp_unslash( $_POST[ $seur_streettype ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$numvia     = sanitize_text_field( wp_unslash( $_POST[ $seur_numvia ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing
		$timetable  = sanitize_text_field( wp_unslash( $_POST[ $seur_timetable ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing

        $order = seur_get_order($order_id);
		$order->update_meta_data('_seur_2shop_depot', str_pad( $depot, 2, '0', STR_PAD_LEFT ) );
        $order->update_meta_data('_seur_2shop_postcode', $postcode );
        $order->update_meta_data('_seur_2shop_codCentro', $pudoId );
        $order->update_meta_data('_seur_2shop_title', $title );
        $order->update_meta_data('_seur_2shop_type', $type );
        $order->update_meta_data('_seur_2shop_address', $address );
        $order->update_meta_data('_seur_2shop_city', $city );
        $order->update_meta_data('_seur_2shop_pudo_id', $pudoId );
        $order->update_meta_data('_seur_2shop_lat', $lat );
        $order->update_meta_data('_seur_2shop_lon', $lon );
        $order->update_meta_data('_seur_2shop_streettype', $streettype );
        $order->update_meta_data('_seur_2shop_numvia', $numvia );
        $order->update_meta_data('_seur_2shop_timetable', $timetable );
        $order->save_meta_data();

        // Set order shipping address to pick-up location address
        $shipping_address = $order->get_address('shipping');
		$shipping_address['address_1'] = __( 'Pickup store', 'seur' ) . ": {$title} - {$pudoId}";
		$shipping_address['address_2'] = "{$streettype} {$address} {$numvia}";
		$shipping_address['city'] = $city;
		$shipping_address['postcode'] = $postcode;
		$order->set_address($shipping_address , 'shipping' );
		$order->save();
	}
}

$localpickup_is_active = get_option( 'seur_activate_local_pickup_field' );

if ( '1' === $localpickup_is_active ) {
	add_action( 'woocommerce_review_order_before_cart_contents', 'seur_local_validate_order', 10 );
	add_action( 'woocommerce_after_checkout_validation', 'seur_local_validate_order', 10 );
    add_action( 'woocommerce_after_checkout_validation', 'seur_validation_2shop_fields' );
	add_action( 'woocommerce_after_shipping_rate', 'seur_after_seur_2shop_shipping_rate', 1, 2 );
	add_action( 'wp_enqueue_scripts', 'seur_map_checkout_load_js' );
	add_action( 'wp_footer', 'seur_add_map_type_select2' );
	add_action( 'woocommerce_checkout_update_order_meta', 'seur_add_2shop_data_to_order' );
}
