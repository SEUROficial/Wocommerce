<?php
/**
 * SEUR Functions
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;
require_once WP_PLUGIN_DIR . '/woocommerce/includes/admin/wc-admin-functions.php';

/**
 * SEUR Debug notice
 */
function seur_debug_mode_notice() {
	$class   = 'notice notice-warning';
	$message = __( 'SEUR_DEBUG is set to TRUE, please set it to false.', 'seur' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

// Some action in debug mode.

if ( defined( 'SEUR_DEBUG' ) && true === SEUR_DEBUG ) {
	// add a notice in WordPress admin.
	add_action( 'admin_notices', 'seur_debug_mode_notice' );
}

add_action( 'admin_notices', 'seur_admin_notices' );

/**
 * SEUR admin Notice
 */
function seur_admin_notices() {
	$message = get_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice' );
	if ( $message ) {
		delete_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible seur_woo_bulk_action_pending_notice', esc_html( $message ) );
	}
}

// Function for check SOAP URL's.

// Function for check API URL's.

/**
 * SEUR Check URL Api Exists
 *
 * @param string $url URL to check.
 */
function seur_api_check_url_exists( $url ) {
    /*
    $response = wp_remote_head( $url );
    if ( is_wp_error( $response ) ) {
        return false;
    }
    $status_code = wp_remote_retrieve_response_code( $response );
    return ( $status_code >= 200 && $status_code < 400 );
    */
    return true;
}

/**
 * SEUR Search Number Message Result
 *
 * @param int $howmany numbers.
 */
function seur_search_number_message_result( $howmany ) {

	if ( $howmany <= 0 ) {
		$message = esc_html_e( 'No Matches Found', 'seur' );
		return $message;
	}

	if ( 1 === $howmany ) {
		$message = esc_html_e( '1 Result Found', 'seur' );
		return $message;
	}

	if ( $howmany > 1 ) {
		// translators: Number of results.
		$message = printf( esc_html__( 'Found %s Results.', 'seur' ), esc_html( $howmany ) );
		return $message;
	}
}

/**
 * SEUR Get REal Rate Name
 *
 * @param string $rate_name Rate Name.
 */
function seur_get_real_rate_name( $rate_name ) {
    $products = seur()->get_products();
    foreach ( $products as $code => $product ) {
        $custom_name = get_option($product['field'] . '_custom_name_field');
        if ($rate_name === $custom_name) {
            return $code;
        }
    }
    return $rate_name;
}

/**
 * SEUR get custom reate name
 *
 * @param string $rate_name Rate name.
 */
function seur_get_custom_rate_name( $rate_name ) {
    $products = seur()->get_products();
    foreach ( $products as $code => $product ) {
        $custom_name = get_option($product['field'] . '_custom_name_field');
        if ($rate_name === $code && !empty($custom_name)) {
            return $custom_name;
        }
    }
    return $rate_name;
}

/**
 * SEUR Custom Rates Load JS.
 */
function seur_custom_rates_load_js() {

	wp_enqueue_script( 'custom-rates-seur', SEUR_PLUGIN_URL . 'assets/js/custom-rates.js', array(), SEUR_OFFICIAL_VERSION, true);
	wp_enqueue_script( 'jquery-datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION, true );
	wp_enqueue_script( 'jqueryui-datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/dataTables.jqueryui.min.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION, true );
	wp_enqueue_script( 'datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/datatables.min.js', array( 'jquery-datattables-seur-rates' ), SEUR_OFFICIAL_VERSION, true );
	wp_enqueue_script( 'custom-table-seur-rates', SEUR_PLUGIN_URL . 'assets/js/seur-custom-rates.js', array( 'datattables-seur-rates', 'jquery-ui-autocomplete' ), SEUR_OFFICIAL_VERSION, true );
	$seurratesphpfiles = array(
		'pathtorates' => SEUR_PLUGIN_URL . 'core/pages/rates/',
	);
	wp_localize_script( 'custom-table-seur-rates', 'custom_table_seur_rates', $seurratesphpfiles );
}

/**
 * SEUR select2 load js
 */
function seur_select2_load_js() {
	wp_enqueue_script( 'seur-select2', SEUR_PLUGIN_URL . 'assets/js/select2.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR Settings Load JS.
 */
function seur_settings_load_js() {
	wp_enqueue_script( 'seur-tooltip', SEUR_PLUGIN_URL . 'assets/js/tooltip.js', array( 'jquery-ui-tooltip' ), SEUR_OFFICIAL_VERSION, true );
	wp_enqueue_script( 'seur-switchery', SEUR_PLUGIN_URL . 'assets/js/switchery.min.js', array(), SEUR_OFFICIAL_VERSION, false );
    wp_enqueue_script( 'seur-admin', SEUR_PLUGIN_URL . 'assets/js/seur-advanced-settings.js', array(), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR select2 custom load JS.
 */
function seur_select2_custom_load_js() {
	wp_enqueue_script( 'seur-select2custom', SEUR_PLUGIN_URL . 'assets/js/select2custom.js', array( 'seur-select2' ), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR sutom country state JS.
 */
function seur_auto_country_state_js() {
	wp_enqueue_script( 'seur-country-state', SEUR_PLUGIN_URL . 'assets/js/seur-country-state.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR data picker JS.
 */
function seur_datepicker_js() {
	wp_enqueue_script( 'seur-datepicker', SEUR_PLUGIN_URL . 'assets/js/seur-datepicker.js', array( 'jquery', 'jquery-ui-datepicker' ), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR Status JS.
 */
function seur_status_js() {
	wp_enqueue_script( 'seur-status', SEUR_PLUGIN_URL . 'assets/js/seur-report.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION, true );
}

/**
 * SEUR label view PDF JS.
 */
function seur_labels_view_pdf_js() {
	global $post_type;

	if ( 'seur' == $post_type ) {
		wp_enqueue_script( 'seur-lavels-script_compatibility', SEUR_PLUGIN_URL . 'assets/js/pdf/compatibility.js', array(), SEUR_OFFICIAL_VERSION, true);
		wp_enqueue_script( 'seur-lavels-script_l10n', SEUR_PLUGIN_URL . 'assets/js/pdf/l10n.js', array(), SEUR_OFFICIAL_VERSION, true );
		wp_enqueue_script( 'seur-lavels-script_pdf', SEUR_PLUGIN_URL . 'assets/js/pdf/pdf.js', array(), SEUR_OFFICIAL_VERSION, true );
		wp_enqueue_script( 'seur-lavels-script_viewer', SEUR_PLUGIN_URL . 'assets/js/pdf/viewer.js', array(), SEUR_OFFICIAL_VERSION , true);
		$translation_array = array(
			'path_js_pdf' => SEUR_PLUGIN_URL . 'assets/js/pdf',
		);
		wp_localize_script( 'seur-lavels-script_pdf', 'seur_js', $translation_array );
		wp_localize_script( 'seur-lavels-script_viewer', 'seur_js', $translation_array );
		wp_enqueue_script( 'seur-lavels-script_pdf' );
	}
}
add_action( 'admin_print_scripts-post.php', 'seur_labels_view_pdf_js', 11 );

/**
 * SEUR Style CSS
 *
 * @param string $hook page.
 */
function seur_styles_css( $hook ) {
	global $seuraddform, $seurrates, $seurcreaterate, $seurdeleterate, $seurupdatecustomrate, $seureditcustomrate, $seur_status;

	if ( $seuraddform !== $hook && $seurrates !== $hook && $seurcreaterate !== $hook && $seurdeleterate !== $hook && $seurupdatecustomrate !== $hook && $seureditcustomrate !== $hook && $seur_status !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurCSS', SEUR_PLUGIN_URL . 'assets/css/seur-addform-rates.css', array(), SEUR_OFFICIAL_VERSION );
		wp_register_style( 'seurSelect2', SEUR_PLUGIN_URL . 'assets/css/select2.css', array(), SEUR_OFFICIAL_VERSION );
		wp_register_style( 'seurSelect2Custom', SEUR_PLUGIN_URL . 'assets/css/select2custom.css', array(), SEUR_OFFICIAL_VERSION );
		wp_register_style( 'seurStatus', SEUR_PLUGIN_URL . 'assets/css/status.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurCSS' );
		wp_enqueue_style( 'seurSelect2' );
		wp_enqueue_style( 'seurSelect2Custom' );
		wp_enqueue_style( 'seurStatus' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_styles_css' );

/**
 * SEUR rates CSS
 *
 * @param string $hook page.
 */
function seur_rates_css( $hook ) {
	global $seurrates;

	if ( $seurrates !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurratescss', SEUR_PLUGIN_URL . 'assets/css/seur-rates.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurratescss' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_rates_css' );

/**
 * SEUR Date picker CSS
 *
 * @param string $hook page.
 */
function seur_datepicker_css( $hook ) {
	global $seurmanifest;

	if ( $seurmanifest !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurdatepickercss', SEUR_PLUGIN_URL . 'assets/css/jquery-ui.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurdatepickercss' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_datepicker_css' );

/**
 * SEUR CSS PDF Viewr
 */
function seur_css_pdf_viewer() {
    global $post_type;

    if (seur_is_order_page($post_type)) {
		wp_register_style( 'seurfontswo', SEUR_PLUGIN_URL . 'assets/css/seur-woo.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurfontswo' );
	}

}
add_action( 'admin_enqueue_scripts', 'seur_css_pdf_viewer' );

/**
 * SEUR CSS Custom Post Type view
 */
function seur_css_cpt_label_view() {
	global $post_type;

	if ( 'seur_labels' === $post_type ) {
		wp_register_style( 'seurcptlabelsview', SEUR_PLUGIN_URL . 'assets/css/cpt-labels.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurcptlabelsview' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_css_cpt_label_view' );

/**
 * SEUR Settings Style CSS.
 *
 * @param string $hook page.
 */
function seur_settings_styles_css( $hook ) {
	global $seurconfig, $seuraddform, $seurrates;

	if ( $seurconfig !== $hook && $seuraddform !== $hook && $seurrates !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurSettingsCSS', SEUR_PLUGIN_URL . 'assets/css/seur-setting.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurSettingsCSS' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_settings_styles_css' );

/**
 * SEUR Auto State Country Style CSS
 *
 * @param string $hook page.
 */
function seur_auto_state_country_styles_css( $hook ) {
	global $seuraddform, $seureditcustomrate;

	if ( $seuraddform !== $hook && $seureditcustomrate !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurAutoStateCountryCSS', SEUR_PLUGIN_URL . 'assets/css/seur-auto-state-country.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurAutoStateCountryCSS' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_auto_state_country_styles_css' );

/**
 * SEUR get Label page Style CSS
 *
 * @param string $hook page.
 */
function seur_get_labels_page_styles_css( $hook ) {
	global $seur_get_labels;

	if ( $seur_get_labels !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurGetLabelsCSS', SEUR_PLUGIN_URL . 'assets/css/get-labels.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurGetLabelsCSS' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_get_labels_page_styles_css' );

/**
 * SEUR nomenclator Style CSS
 *
 * @param string $hook page.
 */
function seur_nomenclator_styles_css( $hook ) {
	global $seurnomenclator, $seurmanifest;

	if ( $seurnomenclator !== $hook && $seurmanifest !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurNomenclatorCSS', SEUR_PLUGIN_URL . 'assets/css/seur-nomenclator.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurNomenclatorCSS' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_nomenclator_styles_css' );

/**
 * SEUR about Style CSS
 *
 * @param string $hook page.
 */
function seur_about_styles_css( $hook ) {
	global $seurabout;

	if ( $seurabout !== $hook ) {
		return;
	} else {
		wp_register_style( 'seurAboutCSS', SEUR_PLUGIN_URL . 'assets/css/seur-about.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seurAboutCSS' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_about_styles_css' );

add_filter( 'custom_menu_order', '__return_true' );

/**
 * SEUR remove menu Items
 */
function seur_remove_menu_items() {
	global $submenu;
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $submenu['seur'] ) ) {
			// Remove 'Seur' submenu items.
			// unset( $submenu['seur'][0]  ); // SEUR submenu (same as SEUR settings).
			unset( $submenu['seur'][6] ); // Add Form.
			unset( $submenu['seur'][7] ); // Create Rate.
			unset( $submenu['seur'][8] ); // Delete Rate.
			unset( $submenu['seur'][9] ); // Update Rate.
			unset( $submenu['seur'][10] ); // Edit Rate.
			unset( $submenu['seur'][11] ); // Process Country State.
			unset( $submenu['seur'][12] ); // Get Label.
			unset( $submenu['seur'][15] ); // Get labels from order.
		}
	} else {
		// remove menus is is shop_manager.
		unset( $submenu['seur'][1] ); // Gel Label.
		unset( $submenu['seur'][4] ); // Get labels from order.
	}
}
add_action( 'admin_head', 'seur_remove_menu_items' );

// The next add_action is only for print the Array menu, for developing purpose.

/**
 * SEUR looks URL
 */
function seur_look_url() {
	global $menu;

	foreach ( $menu as $item ) {
		// Get name of menu item.
		$name = $item[0];
		// Get dashboard item file.
		$file = $item[2];
		// Get URL for item.
		$url = get_admin_menu_item_url( $file );
		echo esc_html( $name ) . ': ' . esc_url( $url ) . '<br />';
	}
}

// Add notices.

/**
 * SEUR Check Curl Admin Notice Error
 */
function seur_check_curl_admin_notice__error() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'CURL is needed by SEUR Plugin, please ask for CURL to your hosting provider', 'seur' ); ?></p>
	</div>
	<?php
}
if ( ! function_exists( 'curl_version' ) ) {
	add_action( 'admin_notices', 'seur_check_curl_admin_notice__error' );
}

/**
 * SEUR Check XML Admin Notice Error
 */
function seur_check_xml_admin_notice__error() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'XML (simplexml_load_string) is needed by SEUR Plugin, please ask for XML to your hosting provider', 'seur' ); ?></p>
	</div>
	<?php
}
if ( ! function_exists( 'simplexml_load_string' ) ) {
	add_action( 'admin_notices', 'seur_check_xml_admin_notice__error' );
}

/**
 * SEUR Sanitize Post Code.
 *
 * @param string $postcode Post code.
 * @param string $country Country.
 */
function seur_sanitize_postcode( $postcode, $country = null ) {

	$unsafe_zipcode    = '';
	$unsafe_zipcode    = $postcode;
	$safe_zipcode_trim = trim( $unsafe_zipcode );
	$safe_zipcode      = sanitize_text_field( $safe_zipcode_trim );
	return $safe_zipcode;
}

/**
 * SEUR get countries.
 */
function seur_get_countries() {
	$countries = array();
	if ( ! $countries ) {
		$countries = include_once SEUR_PLUGIN_PATH . 'core/places/countries.php';
	}
	asort( $countries );
	return $countries;
}

/**
 * SEUR get countries.
 */
function seur_get_countries_around_ES() {
    $countries = array(
        'ES' => 'Spain',
        'AD' => 'Andorra',
        'FR' => 'France',
        'PT' =>  'Portugal'
    );
    asort( $countries );
    return $countries;
}

function seur_get_countries_EU() {
    $countries = [
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BG' => 'Bulgaria',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DE' => 'Germany',
        'DK' => 'Denmark',
        'EE' => 'Estonia',
        'ES' => 'Spain',
        'FI' => 'Finland',
        'FR' => 'France',
        'GR' => 'Greece',
        'HR' => 'Croatia',
        'HU' => 'Hungary',
        'IE' => 'Republic of Ireland',
        'IT' => 'Italy',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'LV' => 'Latvia',
        'MT' => 'Malta',
        'NL' => 'Netherlands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RO' => 'Romania',
        'SE' => 'Sweden',
        'SI' => 'Slovenia',
        'SK' => 'Slovakia'
    ];
    asort( $countries );
    return $countries;
}

function seur_get_countries_OUT_EU() {

    $allCountries = seur_get_countries();
    $countries_EU = seur_get_countries_EU();
    $countries = array_diff($allCountries, $countries_EU);
    asort( $countries );
    return $countries;
}

/**
 * SEUR Sget countries states.
 *
 * @param string $country Country.
 */
function seur_get_countries_states( $country ) {

    if (!preg_match('/^[A-Z]{2}$/', $country)) {
        return false;
    }

	$states      = array();
	$states_file = SEUR_PLUGIN_PATH . 'core/places/states/' . $country . '.php';

	if ( ! $states && file_exists( $states_file ) ) {
		$states = include_once SEUR_PLUGIN_PATH . 'core/places/states/' . $country . '.php';
		asort( $states );
	} else {
		$states = false;
	}
	return $states;
}

/**
 * SEUR get custom reates.
 *
 * @param object $output_type Object.
 * @param string $type Based price.
 */
function seur_get_custom_rates( $output_type = 'OBJECT', $type = 'price' ) {
    global $wpdb;
    $table = $wpdb->prefix . SEUR_TBL_SCR;

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table lookup, no caching applicable
    return $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table WHERE type = %s ORDER BY ID ASC", $type) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safe and hardcoded
        , $output_type
    );
}

/**
 * SEUR Show Available Rates
 *
 * @param string $country County.
 * @param string $state State.
 * @param string $postcode Post Code.
 * @param string $price Price.
 */
function seur_show_availables_rates( $country = null, $state = null, $postcode = null, $price_weight = null ) {

    $country = $country?? '*';
    $state = $state?? '*';
    $postcode = ( '00000' === $postcode )?'*':$postcode;
    $price_weight = $price_weight?? '0';

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $log = new WC_Logger();
        $log->add('seur', ' ARRAIVE TO seur_show_availables_rates( $country = NULL, $state = NULL, $postcode = NULL, $price = NULL )');
        $log->add('seur', '$country:' . $country);
        $log->add('seur', '$state:' . $state);
        $log->add('seur', '$postcode:' . $postcode);
        $log->add('seur', '$price_weight:' . $price_weight);
    }
    $ratestoscreen = seur_search_availables_rates( $country, $state, $postcode, $price_weight );

    return $ratestoscreen;
}

function seur_search_availables_rates( $country = '*', $state = '*', $postcode = '*', $price_weight = 0 ) {
    global $wpdb;

    $type = get_option( 'seur_rates_type_field' ); // 'price' o 'weight', definido en la configuración de SEUR
    $table = $wpdb->prefix . SEUR_TBL_SCR;

    $valid_types = [ 'price', 'weight' ];
    if ( ! in_array( $type, $valid_types, true ) ) {
        return []; // Tipo no válido
    }

    $min_col = 'min' . $type;
    $max_col = 'max' . $type;

    $sql = "SELECT *
            FROM $table
            WHERE type = %s
              AND country = COALESCE(
                  (SELECT country
                   FROM $table
                   WHERE type = %s
                     AND country = %s
                     AND (state = %s OR state = '*')
                     AND ($min_col <= %f AND $max_col > %f)
                   LIMIT 1),
                  '*'
              )
              AND state = COALESCE(
                  (SELECT state
                   FROM $table
                   WHERE type = %s
                     AND (country = %s OR country = '*')
                     AND state = %s
                     AND ($min_col <= %f AND $max_col > %f)
                   LIMIT 1),
                  '*'
              )
              AND $min_col <= %f
              AND $max_col > %f
            ORDER BY ID ASC;";
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared -- Custom table lookup, no caching applicable, Table name safe and hardcoded and Query prepared in the line above
    $results = $wpdb->get_results(

        $wpdb->prepare(
            $sql // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query prepared in the line above
            ,$type,
            $type, $country, $state, $price_weight, $price_weight,
            $type, $country, $state, $price_weight, $price_weight,
            $price_weight, $price_weight
        ), ARRAY_A );

    // Filtrar por código postal
    $filteredResults = array_filter( $results, function ( $row ) use ( $postcode ) {
        return matchPostcode( $postcode, $row['postcode'] );
    });

    // Registrar en el log si está activado
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $log = new WC_Logger();
        $log->add( 'seur', 'Tarifas disponibles encontradas: ' . print_r( $filteredResults, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
    }

    return $filteredResults;
}

function str_ends_with_php7($haystack, $needle) {
    $length = strlen($needle);
    return $length > 0 ? substr($haystack, -$length) === $needle : false;
}

function matchPostcode($postcode, $rules) {
    $rules = explode("\n", $rules); // Separar líneas en un array

    foreach ($rules as $rule) {
        $rule = trim($rule);

        if ($rule == '*') {
            return true;
        }

        // Comprobación por prefijo (ejemplo: 00*)
        if (str_ends_with_php7($rule, '*')) {
            $prefix = rtrim($rule, '*');
            if ($prefix && strpos($postcode, $prefix) === 0) {
                return true; // Coincidencia con prefijo
            }
        }

        // Comprobación por rango (ejemplo: 11001..11012)
        if (strpos($rule, '..') !== false) {
            [$start, $end] = explode('..', $rule);
            if ($postcode >= $start && $postcode <= $end) {
                return true; // Coincidencia con el rango
            }
        }

        // Coincidencia exacta
        if ($postcode === $rule) {
            return true;
        }
    }

    return false; // No hay coincidencia
}
/**
 * SEUR Get User Settings
 */
function seur_get_user_settings() {

	$seur_user_settings = array();

	if ( get_option( 'seur_nif_field' ) ) {
		$seur_nif_field = get_option( 'seur_nif_field' );
	} else {
		$seur_nif_field = '';
	}

	if ( get_option( 'seur_empresa_field' ) ) {
		$seur_empresa_field = get_option( 'seur_empresa_field' );
	} else {
		$seur_empresa_field = '';
	}

	if ( get_option( 'seur_viatipo_field' ) ) {
		$seur_viatipo_field = get_option( 'seur_viatipo_field' );
	} else {
		$seur_viatipo_field = '';
	}
	if ( get_option( 'seur_vianombre_field' ) ) {
		$seur_vianombre_field = get_option( 'seur_vianombre_field' );
	} else {
		$seur_vianombre_field = '';
	}
	if ( get_option( 'seur_vianumero_field' ) ) {
		$seur_vianumero_field = get_option( 'seur_vianumero_field' );
	} else {
		$seur_vianumero_field = '';
	}
	if ( get_option( 'seur_escalera_field' ) ) {
		$seur_escalera_field = get_option( 'seur_escalera_field' );
	} else {
		$seur_escalera_field = '';
	}
	if ( get_option( 'seur_piso_field' ) ) {
		$seur_piso_field = get_option( 'seur_piso_field' );
	} else {
		$seur_piso_field = '';
	}
	if ( get_option( 'seur_puerta_field' ) ) {
		$seur_puerta_field = get_option( 'seur_puerta_field' );
	} else {
		$seur_puerta_field = '';
	}
	if ( get_option( 'seur_postal_field' ) ) {
		$seur_postal_field = get_option( 'seur_postal_field' );
	} else {
		$seur_postal_field = '';
	}
	if ( get_option( 'seur_poblacion_field' ) ) {
		$seur_poblacion_field = get_option( 'seur_poblacion_field' );
	} else {
		$seur_poblacion_field = '';
	}
	if ( get_option( 'seur_provincia_field' ) ) {
		$seur_provincia_field = get_option( 'seur_provincia_field' );
	} else {
		$seur_provincia_field = '';
	}
	if ( get_option( 'seur_pais_field' ) ) {
		$seur_pais_field = get_option( 'seur_pais_field' );
	} else {
		$seur_pais_field = '';
	}
	if ( get_option( 'seur_telefono_field' ) ) {
		$seur_telefono_field = cleanPhone(get_option( 'seur_telefono_field' ));
	} else {
		$seur_telefono_field = '';
	}
	if ( get_option( 'seur_email_field' ) ) {
		$seur_email_field = get_option( 'seur_email_field' );
	} else {
		$seur_email_field = '';
	}
	if ( get_option( 'seur_contacto_nombre_field' ) ) {
		$seur_contacto_nombre_field = get_option( 'seur_contacto_nombre_field' );
	} else {
		$seur_contacto_nombre_field = '';
	}
	if ( get_option( 'seur_contacto_apellidos_field' ) ) {
		$seur_contacto_apellidos_field = get_option( 'seur_contacto_apellidos_field' );
	} else {
		$seur_contacto_apellidos_field = '';
	}
	if ( get_option( 'seur_ccc_field' ) ) {
		$seur_ccc_field = get_option( 'seur_ccc_field' );
	} else {
		$seur_ccc_field = '';
	}
	if ( get_option( 'seur_int_ccc_field' ) ) {
		$seur_int_ccc_field = get_option( 'seur_int_ccc_field' );
	} else {
		$seur_int_ccc_field = '';
	}
	if ( get_option( 'seur_franquicia_field' ) ) {
		$seur_franquicia_field = get_option( 'seur_franquicia_field' );
	} else {
		$seur_franquicia_field = '';
	}
	if ( $seur_pais_field ) {
		if ( 'ES' === $seur_pais_field ) {
			'España' === $seur_pais_field;
		}
		if ( 'PT' === $seur_pais_field ) {
			$seur_pais_field = 'Portugal';
		}
		if ( 'AD' === $seur_pais_field ) {
			$seur_pais_field = 'Andorra';
		}
	}

	$option = array(
		'nif',
		'empresa',
		'viatipo',
		'vianombre',
		'vianumero',
		'escalera',
		'piso',
		'puerta',
		'postalcode',
		'poblacion',
		'provincia',
		'pais',
		'telefono',
		'email',
		'contacto_nombre',
		'contacto_apellidos',
		'ccc',
		'int_ccc',
		'franquicia',
	);

	$value                = array(
		$seur_nif_field,
		$seur_empresa_field,
		$seur_viatipo_field,
		$seur_vianombre_field,
		$seur_vianumero_field,
		$seur_escalera_field,
		$seur_piso_field,
		$seur_puerta_field,
		$seur_postal_field,
		$seur_poblacion_field,
		$seur_provincia_field,
		$seur_pais_field,
		$seur_telefono_field,
		$seur_email_field,
		$seur_contacto_nombre_field,
		$seur_contacto_apellidos_field,
		$seur_ccc_field,
		$seur_int_ccc_field,
		$seur_franquicia_field,
	);
	$seur_user_settings[] = array_combine( $option, $value );

	return $seur_user_settings;
}

/**
 * SEUR Get Asvanced Settings
 */
function seur_get_advanced_settings() {

	$seur_advanced_settings = array();

	if ( get_option( 'seur_preaviso_notificar_field' ) ) {
		$seur_preaviso_notificar_field = get_option( 'seur_preaviso_notificar_field' );
	} else {
		$seur_preaviso_notificar_field = ''; }
	if ( get_option( 'seur_reparto_notificar_field' ) ) {
		$seur_reparto_notificar_field = get_option( 'seur_reparto_notificar_field' );
	} else {
		$seur_reparto_notificar_field = ''; }
	if ( get_option( 'seur_tipo_notificacion_field' ) ) {
		$seur_tipo_notificacion_field = get_option( 'seur_tipo_notificacion_field' );
	} else {
		$seur_tipo_notificacion_field = ''; }
	if ( get_option( 'seur_tipo_etiqueta_field' ) ) {
		$seur_tipo_etiqueta_field = get_option( 'seur_tipo_etiqueta_field' );
	} else {
		$seur_tipo_etiqueta_field = ''; }
	if ( get_option( 'seur_aduana_origen_field' ) ) {
		$seur_aduana_origen_field = get_option( 'seur_aduana_origen_field' );
	} else {
		$seur_aduana_origen_field = ''; }
	if ( get_option( 'seur_aduana_destino_field' ) ) {
		$seur_aduana_destino_field = get_option( 'seur_aduana_destino_field' );
	} else {
		$seur_aduana_destino_field = ''; }
	if ( get_option( 'seur_tipo_mercancia_field' ) ) {
		$seur_tipo_mercancia_field = get_option( 'seur_tipo_mercancia_field' );
	} else {
		$seur_tipo_mercancia_field = ''; }
	if ( get_option( 'seur_id_mercancia_field' ) ) {
		$seur_id_mercancia_field = get_option( 'seur_id_mercancia_field' );
	} else {
		$seur_id_mercancia_field = ''; }
	if ( get_option( 'seur_descripcion_field' ) ) {
		$seur_descripcion_field = get_option( 'seur_descripcion_field' );
	} else {
		$seur_descripcion_field = ''; }
	if ( get_option( 'seur_activate_geolabel_field' ) ) {
		$seur_activate_geolabel_field = get_option( 'seur_activate_geolabel_field' );
	} else {
		$seur_activate_geolabel_field = ''; }

    $option = array(
		'preaviso_notificar',
		'reparto_notificar',
		'tipo_notificacion',
		'tipo_etiqueta',
		'aduana_origen',
		'aduana_destino',
		'tipo_mercancia',
		'id_mercancia',
		'descripcion',
		'geolabel',
	);
	$value = array(
		$seur_preaviso_notificar_field,
		$seur_reparto_notificar_field,
		$seur_tipo_notificacion_field,
		$seur_tipo_etiqueta_field,
		$seur_aduana_origen_field,
		$seur_aduana_destino_field,
		$seur_tipo_mercancia_field,
		$seur_id_mercancia_field,
		$seur_descripcion_field,
		$seur_activate_geolabel_field,
	);

	$seur_advanced_settings[] = array_combine( $option, $value );

	return $seur_advanced_settings;

}

/**
 * SEUR Get Order Data
 *
 * @param int $post_id Post ID.
 */
function seur_get_order_data( $post_id ) {

    $order = wc_get_order( $post_id );
	$seur_order_data = array();

	if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ) {

		$title            = 'Order '.$order->get_id(); //$post->post_title;
        $weight           = seur_get_order_weight($order);
		$country          = $order->get_shipping_country();
		$first_name       = $order->get_shipping_first_name();
		$last_name        = $order->get_shipping_last_name();
		$company          = $order->get_shipping_company();
		$address_1        = $order->get_shipping_address_1();
		$address_2        = $order->get_shipping_address_2();
		$city             = $order->get_shipping_city();
		$postcode         = $order->get_shipping_postcode();
		$email            = $order->get_billing_email();
		$phone            = cleanPhone($order->get_billing_phone());
		$order_total      = $order->get_total();
		$order_pay_method = $order->get_payment_method();

		// SEUR 2SHOP shipping.
		$address_2hop      = $order->get_meta('_seur_2shop_address', true );
		$postcode_2shop    = $order->get_meta('_seur_2shop_postcode', true );
		$city_2shop        = $order->get_meta('_seur_2shop_city', true );
		$code_centro_2shop = $order->get_meta('_seur_2shop_codCentro', true );
        $pudoId_2shop      = $order->get_meta('_seur_2shop_pudo_id', true );

        $order_notes = $order->get_customer_note();

		$option = array(
			'title',
			'weight',
			'country',
			'first_name',
			'last_name',
			'company',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'email',
			'phone',
			'order_notes',
			'order_total',
			'order_pay_method',
			'address_2hop',
			'postcode_2shop',
			'city_2shop',
			'code_centro_2shop',
            'pudoId_2shop'
		);
		$value  = array(
			$title,
			$weight,
			$country,
			$first_name,
			$last_name,
			$company,
			$address_1,
			$address_2,
			$city,
			$postcode,
			$email,
			$phone,
			$order_notes,
			$order_total,
			$order_pay_method,
			$address_2hop,
			$postcode_2shop,
			$city_2shop,
			$code_centro_2shop,
            $pudoId_2shop
		);

		$seur_order_data[] = array_combine( $option, $value );
	}

	return $seur_order_data;
}

/**
 * SEUR Get All Shipping Products
 */
function seur_get_all_shipping_products() {
	return seur()->get_products();
}

/**
 * SEUR Get Service Product Shipping Product
 *
 * @param int    $method_id Method ID.
 * @param string $customer_country Country.
 */
function seur_get_service_product_shipping_product( $method, $customer_country = null ) {

	$log = new WC_Logger();

	$log->add( 'seur', '$method: ' . $method );
	$log->add( 'seur', '$customer_country: ' . $customer_country );

	$service_product = array();

	$products = seur_get_all_shipping_products();

	$shipping_frio = 'SEUR 13:30 Frío';

	foreach ( $products as $description => $product1 ) {
		if ( $description == $method ) {

			$service = $product1['service'];
			$product = $product1['product'];
			$type = $product1['tipo'];

			if ( $description === $shipping_frio && 'FR' === $customer_country ) {
				$service = '77';
				$product = '114';
			}
			break;
		}
	}
	$option = array(
		'service',
		'product',
		'type'
	);
	$value  = array(
		$service,
		$product,
		$type
	);

	$service_product[] = array_combine( $option, $value );

	$log->add( 'seur', '$service_product[]: ' . print_r( $service_product, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

	return $service_product;
}

/**
 * SEUR Get Service Method
 *
 * @param int $order_id Order ID.
 */
function seur_get_shipping_method( $order_id ) {

	$order = seur_get_order($order_id);
	$array = $order->get_items('shipping');
	$shipping_method      = @array_shift($array);
	$shipping_method_name = $shipping_method['name'];

	return $shipping_method_name;
}

/**
 * SEUR upload dir
 *
 * @param string $dir_name string.
 */
function seur_upload_dir( $dir_name = null ) {

	// $dir_name can be, NULL, labels or manifests

	if ( $dir_name ) {
		$new_dir_name = '_' . $dir_name;
	} else {
		$new_dir_name = '';
	}
	$seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );
	if (! $seur_upload_dir || ! file_exists( $seur_upload_dir ) ) {
		seur_create_upload_folder_hook();
		$seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );
	}
	return $seur_upload_dir;
}

/**
 * SEUR upload URL
 *
 * @param string $dir_name string.
 */
function seur_upload_url( $dir_name = null ) {

	if ( $dir_name ) {
		$new_dir_name = '_' . $dir_name;
	} else {
		$new_dir_name = '';
	}
	$seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );
	if ( ! $seur_upload_url ) {
		seur_create_upload_folder_hook();
		$seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );
	}
	return $seur_upload_url;
}

/**
 * SEUR Clean Data
 *
 * @param string $out string to clean.
 */
function seur_clean_data( $out ) {

    // Detecta y convierte a UTF-8 si es necesario
    $encoding = mb_detect_encoding($out, ["UTF-8", "ISO-8859-1", "Windows-1252"], true);
    if ($encoding !== "UTF-8") {
        $out = mb_convert_encoding($out, "UTF-8", $encoding);
    }

    // Normaliza los caracteres especiales para evitar errores de codificación
    $out = iconv('UTF-8', 'ASCII//TRANSLIT', $out);

    // Sustituciones de caracteres específicos
    $out = str_replace(['Á', 'À', 'Ä'], 'A', $out);
    $out = str_replace(['É', 'È', 'Ë'], 'E', $out);
    $out = str_replace(['Í', 'Ì', 'Ï'], 'I', $out);
    $out = str_replace(['Ó', 'Ò', 'Ö'], 'O', $out);
    $out = str_replace(['Ú', 'Ù', 'Ü'], 'U', $out);
    $out = str_replace(['á', 'à', 'ä'], 'a', $out);
    $out = str_replace(['é', 'è', 'ë'], 'e', $out);
    $out = str_replace(['í', 'ì', 'ï'], 'i', $out);
    $out = str_replace(['ó', 'ò', 'ö'], 'o', $out);
    $out = str_replace(['ú', 'ù', 'ü'], 'u', $out);
    $out = str_replace(['&', '<', '>', '/', '"', "'", '?', '¿'], ' ', $out);

    return trim($out);
}

/**
 * SEUR Alyways KG
 *
 * @param string $weight Order Weight.
 */
function seur_always_kg( $weight ) {

	$weight_unit = get_option( 'woocommerce_weight_unit' );
	if ( 'kg' === $weight_unit ) {
		$weight_kg = $weight;
	}
	if ( 'g' === $weight_unit ) {
		$weight_kg = (string) ( number_format( $weight / 1000, 3, '.', '' ) );
	}
	return $weight_kg;
}

/**
 * SEUR Create Randon Shipping ID
 */
function seur_create_random_shippping_id() {

	$characters           = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string               = '';
	$max                  = strlen( $characters ) - 1;
	$random_string_length = 5;
	for ( $i = 0; $i < $random_string_length; $i++ ) {
		$string .= $characters[ wp_rand( 0, $max ) ];
	}
	return $string;
}

/**
 * SEUR From Termica to PDF.
 *
 * @param string $trama SEUR get trama.
 */
function seur_from_terminca_to_pdf( $trama ) {

	$log = new WC_Logger();
	$log->add( 'seur', __( 'Asking to convert from termica to PDF using Labelary API', 'seur' ) );
	$trama_pdf = wp_remote_post(
		'http://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/',
		array(
			'method'      => 'POST',
			'headers'     => array( 'Accept' => 'application/pdf' ),
			'httpversion' => '1.0',
			'sslverify'   => false,
			'body'        => $trama,
		)
	);
	$body      = wp_remote_retrieve_body( $trama_pdf );
	return $body;
}

function seur_api_get_label( $order_id, $numpackages = '1', $weight = '1', $post_weight = false, $changeService = false) {

	global $error;

	try {
		$labelData = seur_api_preprare_label_data($order_id, $numpackages, $weight, $post_weight);
        $labelData['changeService'] = $changeService;
        $order = seur_get_order($order_id);
        $expeditionCode = $order->get_meta('_seur_label_expeditionCode', true);
		$ecbs           = $order->get_meta('_seur_label_ecbs', true);
		$parcelNumbers  = $order->get_meta('_seur_label_parcelNumbers', true);

        $shipmentData = seur()->prepareDataShipment($order_id, $labelData, $preparedData);

        if (!$shipmentData) return false;

		if (!$expeditionCode) {
			$response = seur()->addShipment($shipmentData);
			if (!$response['status']) {
				return [ 'status'=> false,
					'message' => $response['message']
				];
			}
		} else {
			$response['data']['shipmentCode'] = $expeditionCode;
			$response['data']['ecbs'] = $ecbs;
			$response['data']['parcelNumbers'] = $parcelNumbers;
			//$response = json_decode(json_encode($response));
		}

		$is_pdf = seur()->isPdf();
		$result = seur()->getLabel($response, $is_pdf, $labelData, $order_id);
		if (!$result['status']) {
			return [ 'status'=> false,
				'message' => $result['message']
			];
		}

		$trackingNumber = (seur()->isInternationalShipping($preparedData['paisgl']) ?
            $result['parcelNumbers'][0] :
            $result['ecbs'][0]);

        $order->update_meta_data('_seur_label_expeditionCode', $result['expeditionCode'] );
        $order->update_meta_data('_seur_label_ecbs', $result['ecbs'] );
        $order->update_meta_data('_seur_label_parcelNumbers', $result['parcelNumbers']);
        $order->update_meta_data('_seur_label_files', $result['label_files']);
        $order->update_meta_data('_seur_label_trackingNumber', $trackingNumber );
        $order->update_meta_data('_seur_label_id_number', $result['label_ids']);
        $order->update_meta_data('_seur_shipping_order_label_downloaded', 'yes');
        $order->update_meta_data('_seur_shipping_packages', $numpackages);
        $order->update_meta_data('_seur_shipping_weight', $labelData['customer_weight']);
        $order->update_meta_data('_seur_shipping_change_service', $changeService);
        $order->save_meta_data();

		seur()->createPickupIfAuto($shipmentData, $order_id);  //#TODO PENDIENTE DE LA MIGRACIÓN DE PICKUPS

	} catch (Exception $e) {
		$message = "Se ha producido una excepción de Prestashop seur_api_get_label: ".$e->getMessage();
		return [ 'status'=> false,
			'message' => $message
		];
	}
	return [ 'status'=> true,
		'result' => $result['seur_label']
	];
}

function seur_api_preprare_label_data($order_id, $numpackages = '1', $weight = '1', $post_weight = false) {
	$log = new WC_Logger();
    $order = seur_get_order($order_id);
    $preparedData = [];
	$pre_id_seur = seur_create_random_shippping_id();
	$preparedData['order_id_seur']            = $pre_id_seur . $order_id;
	$preparedData['seur_pdf_label']           = '';
	$preparedData['total_bultos']             = $numpackages;
	$preparedData['pdf']                      = '';
	$preparedData['aduanas_sw']               = '';
	$preparedData['internacional_sw']         = '';
	$preparedData['b2csw']                    = '';
	$preparedData['complete_xml']             = '';
	$preparedData['upload_dir']               = seur_upload_dir( 'labels' );
	$preparedData['upload_url']               = seur_upload_url( 'labels' );
	$seur_shipping_method_tmp 				  = seur_get_shipping_method( $order_id );
	$seur_shipping_method     				  = seur_get_real_rate_name( $seur_shipping_method_tmp );
	//$seur_shipping_method_id  				  = seur_return_shipping_product_id( $seur_shipping_method );
	$preparedData['seur_shipping_method']     = $seur_shipping_method;
	//$preparedData['seur_shipping_method_id']  = $seur_shipping_method_id;
	$preparedData['date']                     = gmdate( 'd-m-Y' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$preparedData['mobile_shipping']          = cleanPhone($order->get_meta('_shipping_mobile_phone', true ));
	$preparedData['mobile_billing']           = cleanPhone($order->get_meta('_billing_mobile_phone', true ));
	$preparedData['log']                      = new WC_Logger();

	// All needed Data return Array.
	$order_data       = seur_get_order_data( $order_id );
	$user_data        = seur_get_user_settings();
	$advanced_data    = seur_get_advanced_settings();
	$customer_country = $order_data[0]['country'];
	$preparedData['customer_country'] = $customer_country;
	$product_service_seur = seur_get_service_product_shipping_product( $seur_shipping_method, $customer_country );

	$preparedData['order_data']               = $order_data;
	$preparedData['user_data']                = $user_data;
	$preparedData['advanced_data']            = $advanced_data;
	$preparedData['product_service_seur']     = $product_service_seur;

	// User settings.
	$preparedData['empresa']            = $user_data[0]['empresa'];
	$preparedData['viatipo']            = $user_data[0]['viatipo'];
	$preparedData['vianombre']          = $user_data[0]['vianombre'];
	$preparedData['vianumero']          = $user_data[0]['vianumero'];
	$preparedData['escalera']           = $user_data[0]['escalera'];
	$preparedData['piso']               = $user_data[0]['piso'];
	$preparedData['puerta']             = $user_data[0]['puerta'];
	$preparedData['postalcode']         = $user_data[0]['postalcode'];
	$preparedData['poblacion']          = $user_data[0]['poblacion'];
	$preparedData['provincia']          = $user_data[0]['provincia'];
	$preparedData['telefono']           = $user_data[0]['telefono'];
	$preparedData['email']              = $user_data[0]['email'];
	$preparedData['contacto_nombre']    = $user_data[0]['contacto_nombre'];
	$preparedData['contacto_apellidos'] = $user_data[0]['contacto_apellidos'];
	$preparedData['nif']                = $user_data[0]['nif'];
	$preparedData['franquicia']         = $user_data[0]['franquicia'];
	$preparedData['nat_ccc']            = $user_data[0]['ccc'];
	$preparedData['int_ccc']            = $user_data[0]['int_ccc'];

	$paisgl  = $user_data[0]['pais'];
	if ( 'España' === $paisgl ) {
		$paisgl = 'ES';
	} elseif ( 'Portugal' === $paisgl ) {
		$paisgl = 'PT';
	} elseif ( 'Andorra' === $paisgl ) {
		$paisgl = 'AD';
	}
	$preparedData['paisgl'] = $paisgl;

	// Advanced User Settings.
	$preparedData['geolabel']           = $advanced_data[0]['geolabel'];
	$preparedData['aduana_origen']      = $advanced_data[0]['aduana_origen'];
	$preparedData['aduana_destino']     = $advanced_data[0]['aduana_destino'];
	$preparedData['tipo_mercancia']     = $advanced_data[0]['tipo_mercancia'];
	$preparedData['id_mercancia']       = $advanced_data[0]['id_mercancia'];
	$preparedData['descripcion']        = $advanced_data[0]['descripcion'];
	$preparedData['tipo_etiqueta'] 		= $advanced_data[0]['tipo_etiqueta'];

	$preaviso_notificar = $advanced_data[0]['preaviso_notificar']==='1'?'S':'N';
	$reparto_notificar = $advanced_data[0]['reparto_notificar']==='1'?'S':'N';
	$tipo_aviso = $advanced_data[0]['tipo_notificacion'];

	$preaviso_sms = 'N';
	if ( 'SMS' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_sms = 'S';
	}
	$reparto_sms = 'N';
	if ( 'SMS' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_sms = 'S';
	}
	$preaviso_email = 'N';
	if ( 'EMAIL' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_email = 'S';
	}
	$reparto_email = 'N';
	if ( 'EMAIL' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_email = 'S';
	}
	if ( 'both' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_email = 'S';
		$preaviso_sms   = 'S';
	} else {
		$preaviso_email = 'N';
		$preaviso_sms   = 'N';
	}
	if ( 'both' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_email = 'S';
		$reparto_sms   = 'S';
	} else {
		$reparto_email = 'N';
		$reparto_sms   = 'N';
	}
	$preparedData['preaviso_notificar'] = $preaviso_notificar;
	$preparedData['reparto_notificar'] = $reparto_notificar;
	$preparedData['tipo_aviso'] = $tipo_aviso;
	$preparedData['preaviso_sms'] = $preaviso_sms;
	$preparedData['preaviso_email'] = $preaviso_email;
	$preparedData['reparto_email'] = $reparto_email;
	$preparedData['reparto_sms'] = $reparto_sms;

	// Customer/Order Data.
	$preparedData['customer_country'] 	  = $order_data[0]['country'];
	$preparedData['customer_city']     	  = seur_clean_data( $order_data[0]['city'] );
	$preparedData['customer_postcode'] 	  = $order_data[0]['postcode'];
	$preparedData['customer_weight'] 	  = $order_data[0]['weight'] ?: $weight;
	$preparedData['customer_first_name']  = seur_clean_data( $order_data[0]['first_name'] );
	$preparedData['customer_last_name']   = seur_clean_data( $order_data[0]['last_name'] );
	$preparedData['customer_company']     = $order_data[0]['company'];
	$preparedData['customer_address_1']   = seur_clean_data( $order_data[0]['address_1'] );
	$preparedData['customer_address_2']   = seur_clean_data( $order_data[0]['address_2'] );
	$preparedData['customer_email']       = seur_clean_data( $order_data[0]['email'] );
	$preparedData['customer_phone']       = $order_data[0]['phone'];
	$preparedData['customer_order_notes'] = seur_clean_data( $order_data[0]['order_notes'] );
	$preparedData['customer_order_total'] = str_replace( ',', '.', $order_data[0]['order_total'] );
	$preparedData['order_pay_method']     = seur_clean_data( $order_data[0]['order_pay_method'] );

	if ( 'ES' === $customer_country || 'AD' === $customer_country || 'PT' === $customer_country ) {
		$preparedData['ccc'] = $preparedData['nat_ccc'];
	} else {
		$preparedData['ccc'] = $preparedData['int_ccc'];
	}

	if ( $post_weight ) {
		$preparedData['customer_weight_kg'] = seur_always_kg( $weight );
	} else {
		$preparedData['customer_weight_kg'] = seur_always_kg( (float)$preparedData['customer_weight'] );
	}

	if ( $order_data[0]['address_2hop'] ) {
		$preparedData['customer_address_1'] = seur_clean_data( $order_data[0]['address_2hop'] );
	}

	if ( $order_data[0]['city_2shop'] ) {
		$preparedData['customer_city'] = seur_clean_data( $order_data[0]['city_2shop'] );
	}

	if ( $order_data[0]['postcode_2shop'] ) {
		$preparedData['customer_postcode'] = $order_data[0]['postcode_2shop'];
	}

	$preparedData['cod_centro'] = '';
	if ( $order_data[0]['code_centro_2shop'] ) {
		$preparedData['cod_centro'] = $order_data[0]['code_centro_2shop'];
        $preparedData['pudoId'] = $order_data[0]['pudoId_2shop'];
		$preparedData['cod_tipo_centro'] = 'E,F,S,K,V,U';
		$preparedData['c_recogeran'] = 'S';
	}

	$preparedData['valorReembolso'] = '';
	if ( 'cod' === $preparedData['order_pay_method']) {
		$preparedData['valorReembolso'] = $preparedData['customer_order_total'];
	}

	// SEUR service and Product.
	$preparedData['seur_service'] = $product_service_seur[0]['service'];
	$preparedData['seur_product'] = $product_service_seur[0]['product'];
	$preparedData['seur_type'] = $product_service_seur[0]['type'];

	if ( 'ES' === $customer_country || 'PT' === $customer_country || 'AD' === $customer_country ) {
		$shipping_class = SHIPPING_CLASS_NACIONAL;
		$data = [
			'countryCode' => $customer_country,
			'postalCode' => $preparedData['customer_postcode']
		];

		$fran = seur()->seur_api_check_city( $data );
		if ( ! $fran ) {
			return 'Error 1: postcode not found';
		} else { // postalCode and country exist.
            $fran = $fran[0]['depot'];
			if ( '74' === $fran || '77' === $fran || '56' === $fran || '35' === $fran || '38' === $fran || '52' === $fran || '60' === $fran || '70' === $fran ) {
				$shipping_class = SHIPPING_CLASS_NACIONAL_FRANQUICIAS;
			}
		}

	} else { // shipping is not to ES, PT or AD.
		$shipping_class = SHIPPING_CLASS_INTERNACIONAL;
	}

	$preparedData['seur_weight_by_label'] = ( (float)$preparedData['customer_weight_kg'] / $numpackages );
	$preparedData['portes'] = 'F';

	$preparedData['seur_saturday_shipping'] = '';
	if ( 0 === (int) $shipping_class && 'Friday' === gmdate( 'l' ) ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		if (( 'ES' === $customer_country || 'AD' === $customer_country || 'PT' === $customer_country ) &&
			( '3' === $preparedData['seur_service'] || '9' === $preparedData['seur_service'] ) ) {
			$preparedData['seur_saturday_shipping'] = 'S';
		}
	}

	$preaviso_notificar_geo = 'N';
	if ( 'S' === $preaviso_notificar ) {
		$preaviso_notificar_geo = 'S';
	}
	$reparto_notificar_geo = 'N';
	if ( 'S' === $reparto_notificar ) {
		$reparto_notificar_geo = 'S';
	}
	$seur_tcheck_geo = '';
	$seur_sms = 'N';
	if ( 'S' === $preaviso_sms || 'S' === $reparto_sms ) {
		$seur_tcheck_geo = '1';
		$seur_sms = 'S';
	}
	$seur_email_geo = '';
	if ( 'S' === $preaviso_email || 'S' === $reparto_email ) {
		$seur_email_geo = '3';
	}

	if ( '1' === $seur_tcheck_geo && '3' === $seur_email_geo ) {
		$seur_email_geo  = '4';
		$seur_tcheck_geo = '';
	}

	$mobile_billing = $preparedData['mobile_billing'];
	$mobile_shipping = $preparedData['mobile_shipping'];
	if ( ( $mobile_shipping || $mobile_billing ) ) {
		$seur_sms_mobile_geo = $mobile_billing;
		if ( $mobile_shipping ) {
			$seur_sms_mobile_geo = $mobile_shipping;
		}
	} else {
		$seur_tcheck_geo     = 'N';
		$seur_sms_mobile_geo = '';
		$seur_sms            = 'N';
		$seur_sms_mobile     = '';
	}

	if ( $order_data[0]['code_centro_2shop'] ) {
		$reparto_notificar  = 'N';
		$preaviso_notificar = 'N';
		$preparedData['seur_email']        = 'N';
		$preparedData['customer_phone']    = $mobile_billing;
	}
	$preparedData['preaviso_notificar_geo'] = $preaviso_notificar_geo;
	$preparedData['reparto_notificar_geo'] = $reparto_notificar_geo;
	$preparedData['seur_tcheck_geo'] = $seur_tcheck_geo;
	$preparedData['seur_email_geo'] = $seur_email_geo;
	$preparedData['seur_sms_mobile_geo'] = $seur_sms_mobile_geo;
	$preparedData['seur_sms'] = $seur_sms;
	$preparedData['seur_sms_mobile'] = $seur_sms_mobile??$seur_sms_mobile_geo;
	$preparedData['reparto_notificar'] = $reparto_notificar;
	$preparedData['preaviso_notificar'] = $preaviso_notificar;

	$log->add( 'seur', 'preparedData: ' . print_r( $preparedData, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

	return $preparedData;
}

function seur_api_set_label_result($order_id, $label, $new_status) {
    $labels = seur_api_get_label_ids($label);
	if (  $label['status'] && (!empty($labels)) ) {
		$order = seur_get_order($order_id);
		$order->update_status($new_status, __('Label have been created:', 'seur'));
        $order->update_meta_data( '_seur_shipping_order_label_downloaded', 'yes');
        $order->update_meta_data( '_seur_label_id_number', $labels);
		$order->add_order_note('The Label for Order #' . $order_id . ' have been downloaded', 0, true);
        $order->save_meta_data();
	}
}

function seur_api_get_label_ids($label_result, $formatted = false): string
{
	$labels = '';
    if (isset($label_result['result'])) {
        foreach ($label_result['result'] as $label) {
            if ($label['result']) {
                $labels .= (empty($labels) ? '' : ($formatted ? ', ' : ',')) . $label['labelID'];
            }
        }
        if ($formatted and count($label_result['result']) > 1) {
            return '(' . $labels . ')';
        }
    }
    return $labels;
}

function seur_get_file_type($type) {
    return ($type == 'ZPL' ? 'TERMICA' : $type);
}

function seur_get_file_type_extension($type) {
    return ($type == 'ZPL' || $type == 'TERMICA') ? '.txt' : '.pdf';
}

function seur_get_labels_ids($order_id) {
    $order = wc_get_order($order_id);
    $label_ids = $order->get_meta(  '_seur_label_id_number' );
    $label_ids = empty($label_ids)?[]:$label_ids;
    if (!is_array($label_ids)) {
        if (strpos($label_ids, ',')!==false) {
            $label_ids = explode(',',$label_ids);
        } else {
            $label_ids = [$label_ids];
        }
    }
    return $label_ids;
}

/**
 * SEUR Get WC_Order
 *
 * @param WC_Order|WP_Post|int $post_order_int
 * @return WC_Order
 */
function seur_get_order($post_order_int) {
    if ($post_order_int instanceof WC_Order) {
        return $post_order_int;
    }
    return ($post_order_int instanceof WP_Post ) ? wc_get_order( $post_order_int->ID ) : wc_get_order( $post_order_int );
}
/**
 * Check if HPOS enabled.
 */
function seur_is_wc_order_hpos_enabled() {
    return class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' )
        && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();
}

function seur_get_order_screen() {
    return seur_is_wc_order_hpos_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
}

function seur_get_admin_url() {
    return seur_is_wc_order_hpos_enabled() ? 'admin.php?page=wc-order' :'edit.php?post_type=shop_order';
}

function seur_get_current_screen() {
    global $current_screen;
    return $current_screen??null;
}

function seur_is_order_page($post_type) {
    return seur_is_wc_order_hpos_enabled() ? (seur_get_order_screen() === (seur_get_current_screen()->id ?? false)) : $post_type === 'shop_order';
}

function cleanPhone($phone) {
    $phone = preg_replace('/[\s+\-\.\(\)\/]/', '', $phone);
    $phone = preg_replace('/^0+/', '', $phone);
    return $phone;
}

add_action('wp_ajax_seur_country_state_process', 'seur_country_state_process');
add_action('wp_ajax_nopriv_seur_country_state_process', 'seur_country_state_process');

remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
add_action( 'shutdown', function() {
    while ( @ob_end_flush() );
} );


function seur_api_update_shipment($order_id, $new_shipping_address)
{
    $order = seur_get_order($order_id);
    $expeditionCode = $order->get_meta('_seur_label_expeditionCode', true);

    if (!$expeditionCode) {
        // only save changes, shipment not created yet
        return true;
    }
    try {
        $receiver = [
            "name" => $new_shipping_address['first_name'] . ' ' . $new_shipping_address['last_name'],
            "street" => $new_shipping_address['address_1'] . ' ' . $new_shipping_address['address_2'],
            "phone" => cleanPhone(!empty($new_shipping_address['phone']) ?
                $new_shipping_address['phone'] :
                $order->get_meta('_shipping_mobile_phone', true)),
            "mail" => $order->get_billing_email(),
            "contact" => $new_shipping_address['first_name'] . ' ' . $new_shipping_address['last_name']
        ];

        $shipmentData = [
            "shipmentCode" => $expeditionCode,
            "receiver" => $receiver,
            "observations" => $new_shipping_address['customer_note']
        ];

        if (!seur()->updateShipment($shipmentData)) {
            wp_redirect(wp_get_raw_referer());
            exit;
        }
        return true;
    } catch (Exception $e) {
        $message = "Error: Se ha producido una excepción de Prestashop seur_api_update_shipment: " . $e->getMessage();
        set_transient('updateShipment_notice', $message);
        wp_redirect(wp_get_raw_referer());
        exit;
    }
}

function seur_update_order_address($order_id, $new_shipping_address)
{
    $order = seur_get_order($order_id);
    $order->set_address($new_shipping_address, 'shipping');
    $order->save();
}

add_action('woocommerce_process_shop_order_meta', function ($order_id) {

    if (!seur()->is_seur_order($order_id)) {
        return;
    }

    $order = seur_get_order($order_id);
    $old_shipping_address = $order->get_address('shipping');

    // Obtener los nuevos valores de los campos desde $_POST
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['first_name'] = isset($_POST['_shipping_first_name']) ? sanitize_text_field(wp_unslash($_POST['_shipping_first_name'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['last_name']  = isset($_POST['_shipping_last_name']) ? sanitize_text_field(wp_unslash($_POST['_shipping_last_name'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['company']    = isset($_POST['_shipping_company']) ? sanitize_text_field(wp_unslash($_POST['_shipping_company'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['address_1']  = isset($_POST['_shipping_address_1']) ? sanitize_text_field(wp_unslash($_POST['_shipping_address_1'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['address_2']  = isset($_POST['_shipping_address_2']) ? sanitize_text_field(wp_unslash($_POST['_shipping_address_2'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['city']       = isset($_POST['_shipping_city']) ? sanitize_text_field(wp_unslash($_POST['_shipping_city'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['state']      = isset($_POST['_shipping_state']) ? sanitize_text_field(wp_unslash($_POST['_shipping_state'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['postcode']   = isset($_POST['_shipping_postcode']) ? sanitize_text_field(wp_unslash($_POST['_shipping_postcode'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['country']    = isset($_POST['_shipping_country']) ? sanitize_text_field(wp_unslash($_POST['_shipping_country'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['phone']      = isset($_POST['_shipping_phone']) ? sanitize_text_field(wp_unslash($_POST['_shipping_phone'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
    $new_shipping_address['customer_note'] = isset($_POST['_customer_note']) ? sanitize_text_field(wp_unslash($_POST['_customer_note'])) : '';

    // Validaciones
    $errores = [];

    // si cambia alguno de estos campos 'city', 'state', 'postcode', 'country'
    // y la etiqueta ha sido generada, mostrar mensaje de error
    if (seur()->has_label($order_id)) {
        $campos_a_verificar = [
            'city'     => 'ciudad',
            'state'    => 'provincia',
            'postcode' => 'código postal',
            'country'  => 'país'
        ];

        foreach ($campos_a_verificar as $campo => $nombre) {
            if ($old_shipping_address[$campo] !== $new_shipping_address[$campo]) {
                $errores[] = $nombre;
            }
        }
    }

    // Si hay errores, detenemos el guardado y mostramos un aviso
    if (!empty($errores)) {
        $message= "Error: La etiqueta ya ha sido generada y no se puede cambiar el valor de estos campos de la dirección de envío: ". implode(', ', $errores);
        set_transient('updateShipment_notice', $message);
        wp_redirect(wp_get_raw_referer());
        exit;
    }

    // si hay cambios
    if ($old_shipping_address !== $new_shipping_address) {
        // Comprobamos si se puede actualizar el envío en SEUR
        if (seur_api_update_shipment($order_id, $new_shipping_address)) {
            // Crear dirección de envío con estos datos y asignarla al pedido
            seur_update_order_address($order_id, $new_shipping_address);
        }
    }
}, 10, 1);

add_action('admin_notices', function () {
    $mensaje = get_transient('updateShipment_notice'); // Obtener el mensaje guardado

    if ($mensaje) {
        $error = strpos(strtolower($mensaje), 'error') !== false;
        echo '<div class="notice '.($error?'notice-error':'notice-success').' is-dismissible">
            <p><strong>Seur:</strong> ' . esc_html($mensaje) . '</p></div>';
        delete_transient('updateShipment_notice'); // Eliminar el mensaje después de mostrarlo
    }
});

// Para manener el peso actualizado en la orden
function seur_get_order_weight($order) {
    $label_ids = seur_get_labels_ids($order->get_id());

    $weight = $order->get_meta('_seur_shipping_weight') ?:
        isset($label_ids[0]) && get_post_meta($label_ids[0], '_seur_shipping_weight', true) ?:
        $order->get_meta('_seur_cart_weight', true);
    if ($weight) {
        $order->update_meta_data('_seur_shipping_weight', $weight);
        $order->update_meta_data('_seur_cart_weight', $weight);
        $order->save_meta_data();
        if ($label_ids) {
            update_post_meta($label_ids[0], '_seur_shipping_weight', $weight);
        }
    }
    return $weight;
}

function seur_regenerate_upload_dir_callback() {
    // Asegurar que WordPress está cargado
    if (!defined('ABSPATH')) {
        exit;
    }

    // Incluir el archivo PHP que realiza la acción
    $file_path = SEUR_PLUGIN_PATH . 'core/pages/regenerate-upload-dir.php';

    if (file_exists($file_path)) {
        ob_start();
        include $file_path;
        $data = include $file_path;
        echo esc_html($data);
    } else {
        echo "Error: No se encontró el archivo de procesamiento.";
    }

    wp_die(); // Importante para que WordPress finalice correctamente el AJAX
}
add_action('wp_ajax_seur_regenerate_upload_dir', 'seur_regenerate_upload_dir_callback');


function validatePostcodeFormat($postcode) {
    /*
    Un asterisco solo
    Prefijo alfanumérico seguido de *
    Dos valores alfanuméricos separados por ..
    Un valor alfanumérico sin * ni ..
    */
    $pattern = '/^(\*|[A-Z0-9]+\*|[A-Z0-9]+\.\.[A-Z0-9]+|[A-Z0-9]+)$/i';
    return preg_match($pattern, $postcode);
}

function validatePostcodes($postcodes) {
    $lines = explode("\n", $postcodes);

    foreach ($lines as $line) {
        $line = trim($line);
        if (!validatePostcodeFormat($line)) {
            return false;
        }
    }
    return true;
}