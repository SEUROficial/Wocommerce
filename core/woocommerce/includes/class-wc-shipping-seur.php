<?php
/**
 * Class WC Shipping SEUR
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WC_Shipping_SEUR class.
 *
 * @version 1.0.0
 * @since 1.0.0
 * @extends WC_Shipping_Method
 */
class WC_Shipping_SEUR extends WC_Shipping_Method {

	/**
	 * __construct function.
	 *
	 * @param int $instance_id Instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                 = 'seur';
		$this->instance_id        = absint( $instance_id );
		$this->method_title       = __( 'SEUR', 'seur' );
		$this->method_description = __( '<p>You need to add shipping prices at Seur > Rates.</p><p>Please, configure SEUR data in <code>SEUR -> Settings</code></p>', 'seur' );
		$this->supports           = array(
			'shipping-zones',
			'instance-settings',
			'settings',
		);
		$this->log                = new WC_Logger();
		$this->init();
	}

	/**
	 * Is_available function.
	 *
	 * @param array $package Array Package.
	 */
	public function is_available( $package ) {
		if ( empty( $package['destination']['country'] ) ) {
			return false;
		}
		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
	}

	/**
	 * Output a message or error
	 *
	 * @param string $message Message.
	 * @param string $type Type.
	 */
	public function debug( $message, $type = 'notice' ) {
		if ( $this->debug || ( current_user_can( 'edit_shop_orders' ) && 'error' === $type ) ) {
			wc_add_notice( $message, $type );
		}
	}

	/**
	 * Initialize settings
	 */
	private function set_settings() {
		// Define user set variables.
		$this->title           = $this->get_option( 'title', $this->method_title );
		$this->simple_advanced = $this->get_option( 'simple_advanced', 'simple' );
		// API Settings.
		$this->user_id              = $this->get_option( 'user_id' );
		$this->password             = $this->get_option( 'password' );
		$this->access_key           = $this->get_option( 'access_key' );
		$this->shipper_number       = $this->get_option( 'shipper_number' );
		$this->origin_addressline   = ( ( $bool = $this->get_option( 'origin_addressline' ) ) && 'yes' === $bool ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		$this->origin_city          = $this->get_option( 'origin_city' );
		$this->origin_postcode      = $this->get_option( 'origin_postcode' );
		$this->origin_country_state = $this->get_option( 'origin_country_state' );
		$this->debug                = ( ( $bool = $this->get_option( 'debug' ) ) && 'yes' === $bool ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		// Pickup and Destination.
		$this->pickup      = $this->get_option( 'pickup', '01' );
		$this->residential = ( ( $bool = $this->get_option( 'residential' ) ) && 'yes' === $bool ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		// Services and Packaging.
		$this->offer_rates     = $this->get_option( 'offer_rates', 'all' );
		$this->fallback        = $this->get_option( 'fallback' );
		$this->packing_method  = $this->get_option( 'packing_method', 'per_item' );
		$this->seur_packaging  = $this->get_option( 'seur_packaging', array() );
		$this->custom_services = $this->get_option( 'services', array() );
		$this->boxes           = $this->get_option( 'boxes', array() );
		$this->insuredvalue    = ( ( $bool = $this->get_option( 'insuredvalue' ) ) && 'yes' === $bool ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		// Units.
		$this->units       = $this->get_option( 'units', false );
		$this->weight_unit = 'KGS';
		$this->dim_unit    = 'CM';

		return true;
	}

	/**
	 * Init function.
	 *
	 * @return void
	 */
	private function init() {

		// Load the settings.
		$this->init_form_fields();
		$this->set_settings();
		// Enqueue SEUR Scripts.
		wp_enqueue_script( 'seur-admin-js' );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'clear_transients' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'option_woocommerce_cod_settings', array( $this, 'seur_option_woocommerce_cod_settings' ) );
	}

	/**
	 * Process settings on save
	 */
	public function process_admin_options() {
		parent::process_admin_options();
		$this->set_settings();
	}

	/**
	 * Helper method to split the country/state and set them.
	 *
	 * @param string $country_state Country / State.
	 */
	public function split_country_state( $country_state ) {

		if ( strstr( $country_state, ':' ) ) {
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
		wp_register_style( 'seur-admin-css', SEUR_PLUGIN_URL . 'core/woocommerce/assets/css/seur-admin.css', '', WC_Shipping_SEUR_Init::$version );
		wp_register_script( 'seur-admin-js', SEUR_PLUGIN_URL . 'core/woocommerce/assets/js/seur-admin.js', array( 'jquery' ), WC_Shipping_SEUR_Init::$version, true );
		wp_enqueue_style( 'seur-admin-css' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		$vars = array(
			'dim_unit'    => $this->dim_unit,
			'weight_unit' => $this->weight_unit,
		);
		wp_localize_script( 'seur-admin-js', 'wcseur', $vars );
	}

	/**
	 * Admin_options function.
	 */
	public function admin_options() { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		// Show settings.
		parent::admin_options();
	}

	/**
	 * Generate_single_select_country_html function.
	 */
	public function generate_single_select_country_html() {

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="origin_country"><?php esc_html_e( 'Origin Country', 'seur' ); ?></label>
			</th>
			<td class="forminp">
				<select name="woocommerce_seur_origin_country_state" id="woocommerce_seur_origin_country_state" style="width: 250px;" data-placeholder="<?php esc_html_e( 'Choose a country&hellip;', 'woocommerce' ); ?>" title="Country" class="chosen_select">
				<?php echo WC()->countries->country_dropdown_options( $this->origin_country, $this->origin_state ? $this->origin_state : '*' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</select>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate_services_html function.
	 */
	public function generate_services_html() {
		ob_start();

		return ob_get_clean();
	}

	/**
	 * Validate_single_select_country_field function.
	 *
	 * @param string $key Key.
	 */
	public function validate_single_select_country_field( $key ) {

		if ( isset( $_POST['woocommerce_seur_origin_country_state'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return sanitize_text_field( wp_unslash( $_POST['woocommerce_seur_origin_country_state'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		} else {
			return '';
		}
	}

	/**
	 * Validate_services_field function.
	 *
	 * @param string $key Key.
	 */
	public function validate_services_field( $key ) {
		$services        = array();
		$posted_services = sanitize_text_field( wp_unslash( $_POST['seur_service'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated,WordPress.Security.NonceVerification.Missing

		foreach ( $posted_services as $code => $settings ) {

			$services[ $code ] = array(
				'name'               => wc_clean( $settings['name'] ),
				'order'              => wc_clean( $settings['order'] ),
				'enabled'            => isset( $settings['enabled'] ) ? true : false,
				'adjustment'         => wc_clean( $settings['adjustment'] ),
				'adjustment_percent' => str_replace( '%', '', wc_clean( $settings['adjustment_percent'] ) ),
			);
		}
		return $services;
	}

	/**
	 * Clear_transients function.
	 */
	public function clear_transients() {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_seur_quote_%') OR `option_name` LIKE ('_transient_timeout_seur_quote_%')" );
	}

	/**
	 * Init_form_fields function.
	 */
	public function init_form_fields() {
		$this->instance_form_fields = array(
			'core'        => array(
				'title'       => __( 'Method', 'seur' ),
				'type'        => 'title',
				'description' => '',
				'class'       => 'seur-section-title',
			),
			'title'       => array(
				'title'       => __( 'Method Title', 'seur' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'seur' ),
				'default'     => __( 'SEUR', 'seur' ),
				'desc_tip'    => true,
			),
			'offer_rates' => array(
				'title'       => __( 'Offer Rates', 'seur' ),
				'type'        => 'select',
				'description' => '',
				'default'     => 'expensive',
				'options'     => array(
					'all'       => __( 'Offer the customer all returned rates', 'seur' ),
					'cheapest'  => __( 'Offer the customer the cheapest rate only', 'seur' ),
					'expensive' => __( 'Offer the customer the expensive rate only', 'seur' ),
				),
			),
		);
		$this->form_fields          = array();
	}

	/**
	 * SEUR Option WooCommerce COD Settings
	 *
	 * @param array $value Array.
	 */
	public function seur_option_woocommerce_cod_settings( $value ) {
		if ( is_checkout() ) {
			if (
				! empty( $value )
				&& is_array( $value )
				&& 'yes' === $value['enabled']
				&& ! empty( $value['enable_for_methods'] )
				&& is_array( $value['enable_for_methods'] )
				) {
				foreach ( $value['enable_for_methods'] as $method ) {
					if ( 'seur' === $method ) {
						$seur_rates = seur_get_custom_rates();
						foreach ( $seur_rates as $seur_rate ) {
							$value['enable_for_methods'][] = $seur_rate->ID;
						}
						break;
					}
				}
			}
		}
		return $value;
	}

	/**
	 * Calculate_shipping function.
	 *
	 * @param mixed $package Array.
	 */
	public function calculate_shipping( $package = array() ) {
		global $woocommerce;

		$rates         = array();
		$seur_response = array();
		$rate_requests = array();
		$rates_type    = get_option( 'seur_rates_type_field' );
		$this->log->add( 'seur', 'calculate_shipping( $package = array() ): PROBANDO' );
		$this->log->add( 'seur', 'calculate_shipping( $package = array() ): ' . print_r( $package, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

		// Only return rates if the package has a destination including country.
		if ( '' === $package['destination']['country'] ) {
			$this->debug( __( 'SEUR: Country not supplied. Rates not requested.', 'seur' ) );
			return;
		}

		if ( 'withouttax' === get_option( 'seur_rates_tax_field' ) || ! get_option( 'seur_rates_tax_field' ) ) {
			$price = $package['contents_cost'];
		} else {
			$price = $package['cart_subtotal'];
		}

		if ( $rates_type != 'price' ) {
			$weight        = 0.0;
			$package_price = $price;
			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
				$weight   = (float) $weight + ((float) $_product->get_weight() * (float) $values['quantity']);
			}
			$price = wc_get_weight( $weight, 'kg' );
		}
		$country       = $package['destination']['country'];
		$state         = $package['destination']['state'];
		$postcode      = $package['destination']['postcode'];
		$rate_requests = seur_show_availables_rates( $country, $state, $postcode, $price );

		if ( ! $rate_requests ) {
			$this->debug( __( 'SEUR: No Services are enabled in admin panel.', 'seur' ) );
		}

		if ( $rate_requests ) {
			// parse the results.
			foreach ( $rate_requests as $rate ) {
				$idrate        = $rate['ID'];
				$countryrate   = $rate['country'];
				$staterate     = $rate['state'];
				$postcoderate  = $rate['postcode'];
				$raterate      = $rate['rate'];
				$ratepricerate = $rate['rateprice'];

				if ( $rate && ('SEUR 2SHOP' !== $raterate ||  'CLASSIC 2SHOP' !== $raterate )) {
                	$sort = 999;
                    $ratepricerate = $ratepricerate;
					if ( 'price'  !== $rates_type ) {
						$ratepricerate = seur_filter_price_rate_weight( $package_price, $raterate, $ratepricerate, $countryrate );
					}
					$rate_name = seur_get_custom_rate_name( $raterate );
					$rates[ $idrate ] = array(
						'id'    => $idrate,
						'label' => $rate_name,
						'cost'  => $ratepricerate,
						'sort'  => $sort,
					);
				}
			}
		} // foreach ( $package_requests )
		// Add rates.
		if ( $rates ) {
			if ( 'all' === $this->offer_rates ) {
				uasort( $rates, array( $this, 'sort_rates' ) );
				foreach ( $rates as $key => $rate ) {
					$this->add_rate( $rate );
				}
			} elseif ( 'cheapest' === $this->offer_rates ) {
				$cheapest_rate = '';
				foreach ( $rates as $key => $rate ) {
					if ( ! $cheapest_rate || $cheapest_rate['cost'] >= $rate['cost'] ) {
						$cheapest_rate = $rate;
					}
				}
				$this->add_rate( $cheapest_rate );
			} elseif ( 'expensive' === $this->offer_rates ) {
				$expensive_rate = '';
				foreach ( $rates as $key => $rate ) {
					if ( ! $expensive_rate || $expensive_rate['cost'] <= $rate['cost'] ) {
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
			// Fallback.
		} elseif ( $this->fallback ) {
			$this->add_rate(
				array(
					'id'    => $this->id . '_fallback',
					'label' => $this->title,
					'cost'  => $this->fallback,
					'sort'  => 0,
				)
			);
			$this->debug( __( 'SEUR: Using Fallback setting.', 'seur' ) );
		}
	}

	/**
	 * Sort_rates function.
	 *
	 * @param mixed $a A.
	 * @param mixed $b B.
	 */
	public function sort_rates( $a, $b ) {
		if ( $a['sort'] === $b['sort'] ) {
			return 0;
		}
		return ( $a['sort'] < $b['sort'] ) ? -1 : 1;
	}
}

/**
 * SEUR Coupon Free Shipping
 *
 * @param array  $rates Array.
 * @param string $package Package Name.
 */
function seur_coupon_free_shipping( $rates, $package ) {
	$has_free_shipping = false;
	$applied_coupons   = WC()->cart->get_applied_coupons();
	foreach ( $applied_coupons as $coupon_code ) {
		$coupon = new WC_Coupon( $coupon_code );
		if ( $coupon->get_free_shipping() ) {
			$has_free_shipping = true;
			break;
		}
	}

	foreach ( $rates as $rate_key => $rate ) {
		if ( $has_free_shipping ) {
			// For "free shipping" method (enabled), remove it.
			if ( 'free_shipping' === $rate->method_id ) {
				$free_shipping = get_option( 'seur_activate_free_shipping_field' );
				if ( '1' !== $free_shipping ) {
					unset( $rates[ $rate_key ] );
				}
			} else { // For other shipping methods.
				if ( 'seur' === $rate->method_id || 'seurlocal' === $rate->method_id ) {
					// Append rate label titles (free)
					// $rates[$rate_key]->label .= ' ' . __(' (free)', 'seur');
					// Set rate cost.
					$rates[ $rate_key ]->cost = 0;
					// Set taxes rate cost (if enabled).
					$taxes = array();
					foreach ( $rates[ $rate_key ]->taxes as $key => $tax ) {
						if ( $rates[ $rate_key ]->taxes[ $key ] > 0 ) {
							$taxes[ $key ] = 0;
						}
					}
					$rates[ $rate_key ]->taxes = $taxes;
				}
			}
		}
	}
	return $rates;
}
add_filter( 'woocommerce_package_rates', 'seur_coupon_free_shipping', 20, 2 );


/**
 *
 * @param string $label Current label
 * @param WC_Shipping_Rate $method Transport method details
 *
 * @return string
 */
function seur_add_0_to_shipping_label(string $label, WC_Shipping_Rate $method): string
{
    // if shipping rate is 0, concatenate ": $0.00" to the label
	if ( floatval($method->cost) <= 0  ) {
		$label .= ': ' . wc_price(0) . ' - ' . __( 'Free shipping', 'woocommerce' );
	}

    // return original or edited shipping label
	return $label;
}
add_filter( 'woocommerce_cart_shipping_method_full_label', 'seur_add_0_to_shipping_label', 10, 2 );
