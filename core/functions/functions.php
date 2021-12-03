<?php
/**
 * SEUR Functions
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

// Function for check URL's.

/**
 * SEUR Check URL Existe
 *
 * @param string $url URL to check.
 */
function seur_check_url_exists( $url ) {

	// check, if a valid url is provided.
	$timeout = 10;
	$ch      = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	$http_respond = curl_exec( $ch );
	$http_respond = trim( strip_tags( $http_respond ) );
	$http_code    = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

	if ( ( '200' === $http_code ) || ( '302' === $http_code ) ) {
		curl_close( $ch );
		return true;
	} else {
		// return $http_code;, possible too.
		curl_close( $ch );
		return false;
	}
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

	$seur_bc2_custom_name_field                    = '';
	$seur_10e_custom_name_field                    = '';
	$seur_10ef_custom_name_field                   = '';
	$seur_13e_custom_name_field                    = '';
	$seur_13f_custom_name_field                    = '';
	$seur_48h_custom_name_field                    = '';
	$seur_72h_custom_name_field                    = '';
	$seur_cit_custom_name_field                    = '';
	$seur_2shop_custom_name_field                  = '';
	$seur_courier_int_aereo_paqueteria_custom_name = '';
	$seur_courier_int_aereo_documentos_custom_name = '';
	$seur_netexpress_int_terrestre_custom_name     = '';
	$seur_bc2_custom_name_field                    = get_option( 'seur_bc2_custom_name_field' );
	$seur_10e_custom_name_field                    = get_option( 'seur_10e_custom_name_field' );
	$seur_10ef_custom_name_field                   = get_option( 'seur_10ef_custom_name_field' );
	$seur_13e_custom_name_field                    = get_option( 'seur_13e_custom_name_field' );
	$seur_13f_custom_name_field                    = get_option( 'seur_13f_custom_name_field' );
	$seur_48h_custom_name_field                    = get_option( 'seur_48h_custom_name_field' );
	$seur_72h_custom_name_field                    = get_option( 'seur_72h_custom_name_field' );
	$seur_cit_custom_name_field                    = get_option( 'seur_cit_custom_name_field' );
	$seur_2shop_custom_name_field                  = get_option( 'seur_2SHOP_custom_name_field' );
	$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
	$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
	$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

	if ( ! empty( $seur_bc2_custom_name_field ) && $rate_name == $seur_bc2_custom_name_field ) {
		$real_name = 'B2C Estándar';
	} elseif ( ! empty( $seur_10e_custom_name_field ) && $rate_name == $seur_10e_custom_name_field ) {
		$real_name = 'SEUR 10 Estándar';
	} elseif ( ! empty( $seur_10ef_custom_name_field ) && $rate_name == $seur_10ef_custom_name_field ) {
		$real_name = 'SEUR 10 Frío';
	} elseif ( ! empty( $seur_13e_custom_name_field ) && $rate_name == $seur_13e_custom_name_field ) {
		$real_name = 'SEUR 13:30 Estándar';
	} elseif ( ! empty( $seur_13f_custom_name_field ) && $rate_name == $seur_13f_custom_name_field ) {
		$real_name = 'SEUR 13:30 Frío';
	} elseif ( ! empty( $seur_48h_custom_name_field ) && $rate_name == $seur_48h_custom_name_field ) {
		$real_name = 'SEUR 48H Estándar';
	} elseif ( ! empty( $seur_72h_custom_name_field ) && $rate_name == $seur_72h_custom_name_field ) {
		$real_name = 'SEUR 72H Estándar';
	} elseif ( ! empty( $seur_cit_custom_name_field ) && $rate_name == $seur_cit_custom_name_field ) {
		$real_name = 'Classic Internacional Terrestre';
	} elseif ( ! empty( $seur_2shop_custom_name_field ) && $rate_name == $seur_2shop_custom_name_field ) {
		$real_name = 'SEUR 2SHOP';
	} elseif ( ! empty( $seur_courier_int_aereo_paqueteria_custom_name ) && $rate_name == $seur_courier_int_aereo_paqueteria_custom_name ) {
		$real_name = 'COURIER INT AEREO PAQUETERIA';
	} elseif ( ! empty( $seur_courier_int_aereo_documentos_custom_name ) && $rate_name == $seur_courier_int_aereo_documentos_custom_name ) {
		$real_name = 'COURIER INT AEREO DOCUMENTOS';
	} elseif ( ! empty( $seur_netexpress_int_terrestre_custom_name ) && $rate_name == $seur_netexpress_int_terrestre_custom_name ) {
		$real_name = 'NETEXPRESS INT TERRESTRE';
	} else {
		$real_name = $rate_name;
	}
	return $real_name;
}

/**
 * SEUR get custom reate name
 *
 * @param string $rate_name Rate name.
 */
function seur_get_custom_rate_name( $rate_name ) {

	$seur_bc2_custom_name_field                    = '';
	$seur_10e_custom_name_field                    = '';
	$seur_10ef_custom_name_field                   = '';
	$seur_13e_custom_name_field                    = '';
	$seur_13f_custom_name_field                    = '';
	$seur_48h_custom_name_field                    = '';
	$seur_72h_custom_name_field                    = '';
	$seur_cit_custom_name_field                    = '';
	$seur_2shop_custom_name_field                  = '';
	$seur_courier_int_aereo_paqueteria_custom_name = '';
	$seur_courier_int_aereo_documentos_custom_name = '';
	$seur_netexpress_int_terrestre_custom_name     = '';
	$seur_bc2_custom_name_field                    = get_option( 'seur_bc2_custom_name_field' );
	$seur_10e_custom_name_field                    = get_option( 'seur_10e_custom_name_field' );
	$seur_10ef_custom_name_field                   = get_option( 'seur_10ef_custom_name_field' );
	$seur_13e_custom_name_field                    = get_option( 'seur_13e_custom_name_field' );
	$seur_13f_custom_name_field                    = get_option( 'seur_13f_custom_name_field' );
	$seur_48h_custom_name_field                    = get_option( 'seur_48h_custom_name_field' );
	$seur_72h_custom_name_field                    = get_option( 'seur_72h_custom_name_field' );
	$seur_cit_custom_name_field                    = get_option( 'seur_cit_custom_name_field' );
	$seur_2shop_custom_name_field                  = get_option( 'seur_2SHOP_custom_name_field' );
	$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
	$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
	$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

	if ( ! empty( $seur_bc2_custom_name_field ) && 'B2C Estándar' === $rate_name ) {
		$custom_name = $seur_bc2_custom_name_field;
	} elseif ( ! empty( $seur_10e_custom_name_field ) && 'SEUR 10 Estándar' === $rate_name ) {
		$custom_name = $seur_10e_custom_name_field;
	} elseif ( ! empty( $seur_10ef_custom_name_field ) && 'SEUR 10 Frío' === $rate_name ) {
		$custom_name = $seur_10ef_custom_name_field;
	} elseif ( ! empty( $seur_13e_custom_name_field ) && 'SEUR 13:30 Estándar' === $rate_name ) {
		$custom_name = $seur_13e_custom_name_field;
	} elseif ( ! empty( $seur_13f_custom_name_field ) && 'SEUR 13:30 Frío' === $rate_name ) {
		$custom_name = $seur_13f_custom_name_field;
	} elseif ( ! empty( $seur_48h_custom_name_field ) && 'SEUR 48H Estándar' === $rate_name ) {
		$custom_name = $seur_48h_custom_name_field;
	} elseif ( ! empty( $seur_72h_custom_name_field ) && 'SEUR 72H Estándar' === $rate_name ) {
		$custom_name = $seur_72h_custom_name_field;
	} elseif ( ! empty( $seur_cit_custom_name_field ) && 'Classic Internacional Terrestre' === $rate_name ) {
		$custom_name = $seur_cit_custom_name_field;
	} elseif ( ! empty( $seur_2shop_custom_name_field ) && 'SEUR 2SHOP' === $rate_name ) {
		$custom_name = $seur_2shop_custom_name_field;
	} elseif ( ! empty( $seur_courier_int_aereo_paqueteria_custom_name ) && 'COURIER INT AEREO PAQUETERIA' === $rate_name ) {
		$custom_name = $seur_courier_int_aereo_paqueteria_custom_name;
	} elseif ( ! empty( $seur_courier_int_aereo_documentos_custom_name ) && 'COURIER INT AEREO DOCUMENTOS' === $rate_name ) {
		$custom_name = $seur_courier_int_aereo_documentos_custom_name;
	} elseif ( ! empty( $seur_netexpress_int_terrestre_custom_name ) && 'NETEXPRESS INT TERRESTRE' === $rate_name ) {
		$custom_name = $seur_netexpress_int_terrestre_custom_name;
	} else {
		$custom_name = $rate_name;
	}
	return $custom_name;
}

/**
 * Seur Check City
 *
 * @param array $datos Data Array.
 */
function seur_check_city( $datos ) {

	$url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
	if ( ! seur_check_url_exists( $url ) ) {
		die( esc_html__( 'We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );
	}

	$sc_options = array(
		'connection_timeout' => 30,
	);

	$soap_client = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );

	$parametros = array(
		'in0' => '',
		'in1' => $datos[2],
		'in2' => $datos[3],
		'in3' => '',
		'in4' => '',
		'in5' => $datos[0],
		'in6' => $datos[1],
	);

	$respuesta  = $soap_client->infoPoblacionesCortoStr( $parametros );
	$string_xml = htmlspecialchars_decode( $respuesta->out );
	$strxml     = iconv( 'UTF-8', 'ISO-8859-1', $string_xml );
	$xml        = simplexml_load_string( $strxml );
	$cuantos    = $xml->attributes()->NUM;

	if ( 1 !== $cuantos ) {
		return 'ERROR';
	} else {
		return $xml->REG1->COD_UNIDAD_ADMIN; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
}

/**
 * SEUR Custom Rates Load JS.
 */
function seur_custom_rates_load_js() {

	wp_enqueue_script( 'custom-rates-seur', SEUR_PLUGIN_URL . 'assets/js/custom-rates.js', array(), SEUR_OFFICIAL_VERSION );
	wp_enqueue_script( 'jquery-datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/jquery.dataTables.min.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION );
	wp_enqueue_script( 'jqueryui-datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/dataTables.jqueryui.min.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION );
	wp_enqueue_script( 'datattables-seur-rates', SEUR_PLUGIN_URL . 'assets/js/datatables.min.js', array( 'jquery-datattables-seur-rates' ), SEUR_OFFICIAL_VERSION );
	wp_enqueue_script( 'custom-table-seur-rates', SEUR_PLUGIN_URL . 'assets/js/seur-custom-rates.js', array( 'datattables-seur-rates', 'jquery-ui-autocomplete' ), SEUR_OFFICIAL_VERSION );
	$seurratesphpfiles = array(
		'pathtorates' => SEUR_PLUGIN_URL . 'core/pages/rates/',
	);
	wp_localize_script( 'custom-table-seur-rates', 'custom_table_seur_rates', $seurratesphpfiles );
}

/**
 * SEUR select2 load js
 */
function seur_select2_load_js() {
	wp_enqueue_script( 'seur-select2', SEUR_PLUGIN_URL . 'assets/js/select2.js', array( 'jquery', 'jquery-ui-core' ), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR Settings Load JS.
 */
function seur_settings_load_js() {
	wp_enqueue_script( 'seur-tooltip', SEUR_PLUGIN_URL . 'assets/js/tooltip.js', array( 'jquery-ui-tooltip' ), SEUR_OFFICIAL_VERSION );
	wp_enqueue_script( 'seur-switchery', SEUR_PLUGIN_URL . 'assets/js/switchery.min.js', array(), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR select2 custom load JS.
 */
function seur_select2_custom_load_js() {
	wp_enqueue_script( 'seur-select2custom', SEUR_PLUGIN_URL . 'assets/js/select2custom.js', array( 'seur-select2' ), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR sutom country state JS.
 */
function seur_auto_country_state_js() {
	wp_enqueue_script( 'seur-country-state', SEUR_PLUGIN_URL . 'assets/js/seur-country-state.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR data picker JS.
 */
function seur_datepicker_js() {
	wp_enqueue_script( 'seur-datepicker', SEUR_PLUGIN_URL . 'assets/js/seur-datepicker.js', array( 'jquery', 'jquery-ui-datepicker' ), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR Status JS.
 */
function seur_status_js() {
	wp_enqueue_script( 'seur-status', SEUR_PLUGIN_URL . 'assets/js/seur-report.js', array( 'jquery' ), SEUR_OFFICIAL_VERSION );
}

/**
 * SEUR label view PDF JS.
 */
function seur_labels_view_pdf_js() {
	global $post_type;

	if ( 'seur' == $post_type ) {
		wp_enqueue_script( 'seur-lavels-script_compatibility', SEUR_PLUGIN_URL . 'assets/js/pdf/compatibility.js', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_script( 'seur-lavels-script_l10n', SEUR_PLUGIN_URL . 'assets/js/pdf/l10n.js', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_script( 'seur-lavels-script_pdf', SEUR_PLUGIN_URL . 'assets/js/pdf/pdf.js', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_script( 'seur-lavels-script_viewer', SEUR_PLUGIN_URL . 'assets/js/pdf/viewer.js', array(), SEUR_OFFICIAL_VERSION );
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

	if ( $seuraddform != $hook && $seurrates != $hook && $seurcreaterate != $hook && $seurdeleterate != $hook && $seurupdatecustomrate != $hook && $seureditcustomrate != $hook && $seur_status != $hook ) {
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

	if ( 'shop_order' === $post_type ) {
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

	if ( $seurconfig != $hook && $seuraddform != $hook && $seurrates != $hook ) {
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

	if ( $seuraddform != $hook && $seureditcustomrate != $hook ) {
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

	if ( $seur_get_labels != $hook ) {
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

	if ( $seurnomenclator != $hook && $seurmanifest != $hook ) {
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

	if ( $seurabout != $hook ) {
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

	if ( isset( $submenu['seur'] ) ) {
		// Remove 'Seur' submenu items
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
 * SEUR Check SOAP Admin Notice Error
 */
function seur_check_soap_admin_notice__error() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'SOAP is needed by SEUR Plugin, please ask for SOAP to your hosting provider', 'seur' ); ?></p>
	</div>
	<?php
}
if ( ! class_exists( 'SoapClient' ) ) {
	add_action( 'admin_notices', 'seur_check_soap_admin_notice__error' );
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
 * SEUR gets all orders.
 */
function seur_gets_all_orders() {
	global $wpdb;

	$tabla       = $wpdb->prefix . 'seur_labels';
	$sql         = "SELECT * FROM $tabla";
	$shop_orders = $wpdb->get_results( $wpdb->prepare( $sql ) );

	return $shop_orders;
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
 * SEUR Sget countries states.
 *
 * @param string $country Country.
 */
function seur_get_countries_states( $country ) {

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

	$table    = $wpdb->prefix . SEUR_TBL_SCR;
	$getrates = $wpdb->get_results( "SELECT * FROM $table WHERE type = '$type' ORDER BY ID ASC", $output_type );
	return $getrates;
}

/**
 * SEUR Seach Allowed Rates by Country.
 *
 * @param string $allowedcountry Alloweb Country.
 */
function seur_search_allowed_rates_by_country( $allowedcountry ) {

	$filtered_rates_by_country = array();
	$rates_type                = get_option( 'seur_rates_type_field' );
	$output_type               = 'OBJECT';
	$getrates                  = seur_get_custom_rates( $output_type, $rates_type );

	foreach ( $getrates as $rate ) {
		$country = $rate->country;
		$rateid  = $rate->ID;

		if ( $allowedcountry === $country ) {
			$columns                     = array(
				'ID',
				'country',
				'state',
				'postcode',
				'minprice',
				'maxprice',
				'minweight',
				'maxweight',
				'rate',
				'rateprice',
				'type',
			);
			$valors                      = array(
				$rate->ID,
				$rate->country,
				$rate->state,
				$rate->postcode,
				$rate->minprice,
				$rate->maxprice,
				$rate->minweight,
				$rate->maxweight,
				$rate->rate,
				$rate->rateprice,
				$rate->type,
			);
			$filtered_rates_by_country[] = array_combine( $columns, $valors );
		}
	}

	if ( $filtered_rates_by_country ) {
		return $filtered_rates_by_country;
	} else {
		foreach ( $getrates as $rate ) {
			$country = $rate->country;
			$rateid  = $rate->ID;
			if ( '*' === $country ) {
				$columns = array(
					'ID',
					'country',
					'state',
					'postcode',
					'minprice',
					'maxprice',
					'minweight',
					'maxweight',
					'rate',
					'rateprice',
					'type',
				);

				$valors                      = array(
					$rate->ID,
					$rate->country,
					$rate->state,
					$rate->postcode,
					$rate->minprice,
					$rate->maxprice,
					$rate->minweight,
					$rate->maxweight,
					$rate->rate,
					$rate->rateprice,
					$rate->type,
				);
				$filtered_rates_by_country[] = array_combine( $columns, $valors );
			}
		}
	}
	return $filtered_rates_by_country;
}

/**
 * SEUR Search Allowed States Filtered by Country
 *
 * @param string $allowedstate state.
 * @param array  $filtered_rates_by_country countries array.
 */
function seur_seach_allowed_states_filtered_by_countries( $allowedstate, $filtered_rates_by_country ) {

	$filtered_rates_by_state = array();

	foreach ( $filtered_rates_by_country as $allowedrate ) {
		$state  = $allowedrate['state'];
		$rateid = $allowedrate['ID'];

		if ( $allowedstate === $state ) {
			$columns = array(
				'ID',
				'country',
				'state',
				'postcode',
				'minprice',
				'maxprice',
				'minweight',
				'maxweight',
				'rate',
				'rateprice',
				'type',
			);

			$valors                    = array(
				$allowedrate['ID'],
				$allowedrate['country'],
				$allowedrate['state'],
				$allowedrate['postcode'],
				$allowedrate['minprice'],
				$allowedrate['maxprice'],
				$allowedrate['minweight'],
				$allowedrate['maxweight'],
				$allowedrate['rate'],
				$allowedrate['rateprice'],
				$allowedrate['type'],
			);
			$filtered_rates_by_state[] = array_combine( $columns, $valors );
		}
	}

	if ( $filtered_rates_by_state ) {
		return $filtered_rates_by_state;
	} else {
		foreach ( $filtered_rates_by_country as $allowedrate ) {
			$state  = $allowedrate['state'];
			$rateid = $allowedrate['ID'];

			if ( '*' === $state ) {
				$columns = array(
					'ID',
					'country',
					'state',
					'postcode',
					'minprice',
					'maxprice',
					'minweight',
					'maxweight',
					'rate',
					'rateprice',
					'type',
				);

				$valors                    = array(
					$allowedrate['ID'],
					$allowedrate['country'],
					$allowedrate['state'],
					$allowedrate['postcode'],
					$allowedrate['minprice'],
					$allowedrate['maxprice'],
					$allowedrate['minweight'],
					$allowedrate['maxweight'],
					$allowedrate['rate'],
					$allowedrate['rateprice'],
					$allowedrate['type'],
				);
				$filtered_rates_by_state[] = array_combine( $columns, $valors );
			}
		}
	}
	return $filtered_rates_by_state;
}

/**
 * SEUR Search Allowed postcodes filtered by country
 *
 * @param string $allowedpostcode Postcode.
 * @param array  $filtered_rates_by_state States.
 */
function seur_seach_allowed_postcodes_filtered_by_states( $allowedpostcode, $filtered_rates_by_state ) {

	$filtered_rates_by_postcode = array();
	foreach ( $filtered_rates_by_state as $allowedrate ) {
		$postcode = $allowedrate['postcode'];
		$rateid   = $allowedrate['ID'];

		if ( $allowedpostcode === $postcode ) {
			$columns                      = array(
				'ID',
				'country',
				'state',
				'postcode',
				'minprice',
				'maxprice',
				'minweight',
				'maxweight',
				'rate',
				'rateprice',
				'type',
			);
			$valors                       = array(
				$allowedrate['ID'],
				$allowedrate['country'],
				$allowedrate['state'],
				$allowedrate['postcode'],
				$allowedrate['minprice'],
				$allowedrate['maxprice'],
				$allowedrate['minweight'],
				$allowedrate['maxweight'],
				$allowedrate['rate'],
				$allowedrate['rateprice'],
				$allowedrate['type'],
			);
			$filtered_rates_by_postcode[] = array_combine( $columns, $valors );
		}
	}
	if ( $filtered_rates_by_postcode ) {
		return $filtered_rates_by_postcode;
	} else {
		foreach ( $filtered_rates_by_state as $allowedrate ) {
			$postcode = $allowedrate['postcode'];
			$rateid   = $allowedrate['ID'];
			if ( '*' === $postcode ) {
				$columns                      = array(
					'ID',
					'country',
					'state',
					'postcode',
					'minprice',
					'maxprice',
					'minweight',
					'maxweight',
					'rate',
					'rateprice',
					'type',
				);
				$valors                       = array(
					$allowedrate['ID'],
					$allowedrate['country'],
					$allowedrate['state'],
					$allowedrate['postcode'],
					$allowedrate['minprice'],
					$allowedrate['maxprice'],
					$allowedrate['minweight'],
					$allowedrate['maxweight'],
					$allowedrate['rate'],
					$allowedrate['rateprice'],
					$allowedrate['type'],
				);
				$filtered_rates_by_postcode[] = array_combine( $columns, $valors );
			}
		}
	}
	return $filtered_rates_by_postcode;
}

/**
 * SEUR Search Allowed postcodes filtered by country
 *
 * @param string $allowedprice Price.
 * @param array  $filtered_rates_by_postcode Postcodes.
 */
function seur_seach_allowed_prices_filtered_by_postcode( $allowedprice, $filtered_rates_by_postcode ) {
	$filtered_rates_by_price = array();

	foreach ( $filtered_rates_by_postcode as $allowedrate ) {
		$minprice = $allowedrate['minprice'];
		$maxprice = $allowedrate['maxprice'];
		$rateid   = $allowedrate['ID'];
		if ( ( $minprice <= $allowedprice ) && ( $maxprice > $allowedprice ) ) {
			$columns                   = array(
				'ID',
				'country',
				'state',
				'postcode',
				'minprice',
				'maxprice',
				'minweight',
				'maxweight',
				'rate',
				'rateprice',
				'type',
			);
			$valors                    = array(
				$allowedrate['ID'],
				$allowedrate['country'],
				$allowedrate['state'],
				$allowedrate['postcode'],
				$allowedrate['minprice'],
				$allowedrate['maxprice'],
				$allowedrate['minweight'],
				$allowedrate['maxweight'],
				$allowedrate['rate'],
				$allowedrate['rateprice'],
				$allowedrate['type'],
			);
			$filtered_rates_by_price[] = array_combine( $columns, $valors );
		}
	}
	return $filtered_rates_by_price;
}

/**
 * SEUR Show Available Rates
 *
 * @param string $country County.
 * @param string $state State.
 * @param string $postcode Post Code.
 * @param string $price Price.
 */
function seur_show_availables_rates( $country = null, $state = null, $postcode = null, $price = null ) {

	$log = new WC_Logger();
	$log->add( 'seur', ' ARRAIVE TO seur_show_availables_rates( $country = NULL, $state = NULL, $postcode = NULL, $price = NULL )' );
	if ( ! $country ) {
		$country = '*';
	}
	if ( ! $state ) {
		$state = '*';
	}
	if ( ! $postcode ) {
		$postcode = '*';
	}
	if ( '00000' === $postcode ) {
		$postcode = '*';
	}
	if ( ! $price ) {
		$price = '0';
	}

	$filtered_rates_by_country  = array();
	$filtered_rates_by_state    = array();
	$filtered_rates_by_postcode = array();
	$ratestoscreen              = array();

	$filtered_rates_by_country  = seur_search_allowed_rates_by_country( $country );
	$filtered_rates_by_state    = seur_seach_allowed_states_filtered_by_countries( $state, $filtered_rates_by_country );
	$filtered_rates_by_postcode = seur_seach_allowed_postcodes_filtered_by_states( $postcode, $filtered_rates_by_state );
	$ratestoscreen              = seur_seach_allowed_prices_filtered_by_postcode( $price, $filtered_rates_by_postcode );

	return $ratestoscreen;
}

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
		$seur_telefono_field = get_option( 'seur_telefono_field' );
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
	if ( get_option( 'seur_cit_codigo_field' ) ) {
		$seur_cit_codigo_field = get_option( 'seur_cit_codigo_field' );
	} else {
		$seur_cit_codigo_field = '';
	}
	if ( get_option( 'seur_cit_usuario_field' ) ) {
		$seur_cit_usuario_field = get_option( 'seur_cit_usuario_field' );
	} else {
		$seur_cit_usuario_field = '';
	}
	if ( get_option( 'seur_cit_contra_field' ) ) {
		$seur_cit_contra_field = get_option( 'seur_cit_contra_field' );
	} else {
		$seur_cit_contra_field = '';
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
	if ( get_option( 'seur_seurcom_usuario_field' ) ) {
		$seur_seurcom_usuario_field = get_option( 'seur_seurcom_usuario_field' );
	} else {
		$seur_seurcom_usuario_field = '';
	}
	if ( get_option( 'seur_seurcom_contra_field' ) ) {
		$seur_seurcom_contra_field = get_option( 'seur_seurcom_contra_field' );
	} else {
		$seur_seurcom_contra_field = '';
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
		'cit_codigo',
		'cit_usuario',
		'cit_contra',
		'ccc',
		'int_ccc',
		'franquicia',
		'seurcom_usuario',
		'seurcom_contra',
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
		$seur_cit_codigo_field,
		$seur_cit_usuario_field,
		$seur_cit_contra_field,
		$seur_ccc_field,
		$seur_int_ccc_field,
		$seur_franquicia_field,
		$seur_seurcom_usuario_field,
		$seur_seurcom_contra_field,
	);
	$seur_user_settings[] = array_combine( $option, $value );

	return $seur_user_settings;
}

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

function seur_get_order_data( $post_id ) {

	$post            = get_post( $post_id );
	$seur_order_data = array();

	if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ) {

		$title            = $post->post_title;
		$weight           = get_post_meta( $post_id, '_seur_cart_weight', true );
		$country          = get_post_meta( $post_id, '_shipping_country', true );
		$first_name       = get_post_meta( $post_id, '_shipping_first_name', true );
		$last_name        = get_post_meta( $post_id, '_shipping_last_name', true );
		$company          = get_post_meta( $post_id, '_shipping_company', true );
		$address_1        = get_post_meta( $post_id, '_shipping_address_1', true );
		$address_2        = get_post_meta( $post_id, '_shipping_address_2', true );
		$city             = get_post_meta( $post_id, '_shipping_city', true );
		$postcode         = get_post_meta( $post_id, '_shipping_postcode', true );
		$email            = get_post_meta( $post_id, '_billing_email', true );
		$phone            = get_post_meta( $post_id, '_billing_phone', true );
		$order_total      = get_post_meta( $post_id, '_order_total', true );
		$order_pay_method = get_post_meta( $post_id, '_payment_method', true );

		// SEUR 2SHOP shipping.
		$address_2hop      = get_post_meta( $post_id, '_seur_2shop_address', true );
		$postcode_2shop    = get_post_meta( $post_id, '_seur_2shop_postcode', true );
		$city_2shop        = get_post_meta( $post_id, '_seur_2shop_city', true );
		$code_centro_2shop = get_post_meta( $post_id, '_seur_2shop_codCentro', true );

		$order_notes = esc_html( $post->post_excerpt );

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
		);

		$seur_order_data[] = array_combine( $option, $value );
	}

	return $seur_order_data;
}

function seur_get_all_shipping_products() {
	global $wpdb;

	$tabla     = $wpdb->prefix . SEUR_PLUGIN_SVPR;
	$sql       = "SELECT * FROM $tabla";
	$registros = $wpdb->get_results( $sql );

	return $registros;
}

function seur_return_shipping_product_id( $shipping_product ) {

	$products = seur_get_all_shipping_products();

	foreach ( $products as $product ) {
		if ( $product->descripcion === $shipping_product ) {
			$product_id = $product->ID;
		}
	}
	return $product_id;
}

function seur_get_service_product_shipping_product( $method_id, $customer_country = null ) {

	$log = new WC_Logger();

	$log->add( 'seur', '$method_id: ' . $method_id );
	$log->add( 'seur', '$customer_country: ' . $customer_country );

	$service_product = array();

	$products = seur_get_all_shipping_products();

	$shipping_product = 'SEUR 13:30 Frío';
	$frio_id          = seur_return_shipping_product_id( $shipping_product );

	foreach ( $products as $product1 ) {
		if ( $product1->ID == $method_id ) {

			$service = $product1->ser;
			$product = $product1->pro;

			if ( $product1->ID === $frio_id && $customer_country == 'FR' ) {
				$service = '77';
				$product = '114';
			}
		}
	}
	$option = array(
		'service',
		'product',
	);
	$value  = array(
		$service,
		$product,
	);

	$service_product[] = array_combine( $option, $value );

	$log->add( 'seur', '$service_product[]: ' . print_r( $service_product, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

	return $service_product;
}

function seur_get_shipping_method( $order_id ) {

	$order = new WC_Order( $order_id );

	$shipping_method      = @array_shift( $order->get_items( 'shipping' ) );
	$shipping_method_name = $shipping_method['name'];

	return $shipping_method_name;
}

function seur_upload_dir( $dir_name = null ) {

	// $dir_name can be, NULL, labels or manifests

	if ( $dir_name ) {
		$new_dir_name = '_' . $dir_name;
	} else {
		$new_dir_name = '';
	}
	$seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );
	if ( $seur_upload_dir ) {
		$seur_upload_dir = $seur_upload_dir;
	} else {
		seur_create_upload_folder_hook();
		$seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );
	}
	return $seur_upload_dir;
}

function seur_upload_url( $dir_name = null ) {

	if ( $dir_name ) {
		$new_dir_name = '_' . $dir_name;
	} else {
		$new_dir_name = '';
	}
	$seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );
	if ( $seur_upload_url ) {
		$seur_upload_url = $seur_upload_url;
	} else {
		seur_create_upload_folder_hook();
		$seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );
	}
	return $seur_upload_url;
}

function seur_clean_data( $out ) {

	$out = str_replace( 'Á', 'A', $out );
	$out = str_replace( 'À', 'A', $out );
	$out = str_replace( 'Ä', 'A', $out );
	$out = str_replace( 'É', 'E', $out );
	$out = str_replace( 'È', 'E', $out );
	$out = str_replace( 'Ë', 'E', $out );
	$out = str_replace( 'Í', 'I', $out );
	$out = str_replace( 'Ì', 'I', $out );
	$out = str_replace( 'Ï', 'I', $out );
	$out = str_replace( 'Ó', 'O', $out );
	$out = str_replace( 'Ò', 'O', $out );
	$out = str_replace( 'Ö', 'O', $out );
	$out = str_replace( 'Ú', 'U', $out );
	$out = str_replace( 'Ù', 'U', $out );
	$out = str_replace( 'Ü', 'U', $out );
	$out = str_replace( '&', '-', $out );
	$out = str_replace( '<', ' ', $out );
	$out = str_replace( '>', ' ', $out );
	$out = str_replace( '/', ' ', $out );
	$out = str_replace( '"', ' ', $out );
	$out = str_replace( "'", ' ', $out );
	$out = str_replace( '"', ' ', $out );
	$out = str_replace( '?', ' ', $out );
	$out = str_replace( '¿', ' ', $out );

	return $out;
}

function seur_always_kg( $weight ) {

	$weight_unit = get_option( 'woocommerce_weight_unit' );
	if ( $weight_unit === 'kg' ) {
		$weight_kg = $weight;
	}
	if ( $weight_unit === 'g' ) {
		$weight_kg = (string) ( number_format( $weight / 1000, 3, '.', '' ) );
	}
	return $weight_kg;
}

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

function seur_from_terminca_to_pdf( $trama ) {

	$log = new WC_Logger();
	$log->add( 'seur', __( 'Asking to convert from termica to PDF using Labelary API', 'seur' ) );

	$curl = curl_init();
	curl_setopt( $curl, CURLOPT_URL, 'http://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/' );
	curl_setopt( $curl, CURLOPT_POST, true );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $trama );
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Accept: application/pdf' ) );
	$trama_pdf = curl_exec( $curl );

	$log->add( 'seur', 'This is the result ' . $trama_pdf, 'seur' );

	return $trama_pdf;
}

function seur_get_label( $order_id, $numpackages = '1', $weight = '1', $post_weight = false ) {
	global $error;

	$pre_id_seur              = seur_create_random_shippping_id();
	$order_id_seur            = $pre_id_seur . $order_id;
	$seur_pdf_label           = '';
	$total_bultos             = '';
	$pdf                      = '';
	$aduanas_sw               = '';
	$internacional_sw         = '';
	$b2csw                    = '';
	$seur_saturday_shipping   = '';
	$complete_xml             = '';
	$upload_dir               = seur_upload_dir( 'labels' );
	$upload_url               = seur_upload_url( 'labels' );
	$order_data               = array();
	$user_data                = array();
	$advanced_data            = array();
	$product_service_seur     = array();
	$seur_shipping_method_tmp = seur_get_shipping_method( $order_id );
	$seur_shipping_method     = seur_get_real_rate_name( $seur_shipping_method_tmp );
	$seur_shipping_method_id  = seur_return_shipping_product_id( $seur_shipping_method );
	$date                     = date( 'd-m-Y' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$mobile_shipping          = get_post_meta( $order_id, '_shipping_mobile_phone', true );
	$mobile_billing           = get_post_meta( $order_id, '_billing_mobile_phone', true );
	$log                      = new WC_Logger();

	$log->add( 'seur', '$order_id: ' . $order_id );
	$log->add( 'seur', '$numpackages: ' . $numpackages );
	$log->add( 'seur', '$weight: ' . $weight );
	$log->add( 'seur', '$post_weight: ' . $post_weight );

	// All needed Data return Array.

	$order_data       = seur_get_order_data( $order_id );
	$user_data        = seur_get_user_settings();
	$advanced_data    = seur_get_advanced_settings();
	$customer_country = $order_data[0]['country'];
	$log->add( 'seur', '$seur_shipping_method_id: ' . $seur_shipping_method_id );
	$log->add( 'seur', '$pre_id_seur: ' . $pre_id_seur );
	$log->add( 'seur', '$customer_country: ' . $customer_country );
	$product_service_seur = seur_get_service_product_shipping_product( $seur_shipping_method_id, $customer_country );
	$log->add( 'seur', '$product_service_seur: ' . print_r( $product_service_seur, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

	// User settings.

	$empresa            = $user_data[0]['empresa'];
	$viatipo            = $user_data[0]['viatipo'];
	$vianombre          = $user_data[0]['vianombre'];
	$vianumero          = $user_data[0]['vianumero'];
	$escalera           = $user_data[0]['escalera'];
	$piso               = $user_data[0]['piso'];
	$puerta             = $user_data[0]['puerta'];
	$postalcode         = $user_data[0]['postalcode'];
	$poblacion          = $user_data[0]['poblacion'];
	$provincia          = $user_data[0]['provincia'];
	$pais               = $user_data[0]['pais'];
	$telefono           = $user_data[0]['telefono'];
	$email              = $user_data[0]['email'];
	$contacto_nombre    = $user_data[0]['contacto_nombre'];
	$contacto_apellidos = $user_data[0]['contacto_apellidos'];
	$cit_pass           = $user_data[0]['cit_codigo'];
	$cit_user           = $user_data[0]['cit_usuario'];
	$cit_contra         = $user_data[0]['cit_contra'];
	$nif                = $user_data[0]['nif'];
	$franquicia         = $user_data[0]['franquicia'];
	$nat_ccc            = $user_data[0]['ccc'];
	$int_ccc            = $user_data[0]['int_ccc'];
	$usercom            = $user_data[0]['seurcom_usuario'];
	$passcom            = $user_data[0]['seurcom_contra'];

	if ( $pais ) {
		if ( 'España' === $pais ) {
			$paisgl = 'ES';
		}
		if ( 'Portugal' === $pais ) {
			$paisgl = 'PT';
		}
		if ( 'Andorra' === $pais ) {
			$$paisgl = 'AD';
		}
	}

	// Advanced User Settings.

	$geolabel           = $advanced_data[0]['geolabel'];
	$aduana_origen      = $advanced_data[0]['aduana_origen'];
	$aduana_destino     = $advanced_data[0]['aduana_destino'];
	$tipo_mercancia     = $advanced_data[0]['tipo_mercancia'];
	$id_mercancia       = $advanced_data[0]['id_mercancia'];
	$descripcion        = $advanced_data[0]['descripcion'];
	$preaviso_notificar = $advanced_data[0]['preaviso_notificar'];

	if ( '1' === $preaviso_notificar ) {
		$preaviso_notificar = 'S';
	} else {
		$preaviso_notificar = 'N';
	}
	$reparto_notificar = $advanced_data[0]['reparto_notificar'];

	if ( '1' === $reparto_notificar ) {
		$reparto_notificar = 'S';
	} else {
		$reparto_notificar = 'N'; }
	$tipo_aviso = $advanced_data[0]['tipo_notificacion'];

	if ( 'SMS' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_sms = 'S';
	} else {
		$preaviso_sms = 'N';
	}

	if ( 'SMS' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_sms = 'S';
	} else {
		$reparto_sms = 'N';
	}

	if ( 'EMAIL' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_email = 'S';
	} else {
		$preaviso_email = 'N';
	}

	if ( 'EMAIL' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_email = 'S';
	} else {
		$reparto_email = 'N';
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
	$tipo_etiqueta = $advanced_data[0]['tipo_etiqueta'];

	// Customer/Order Data.

	$customercity     = seur_clean_data( $order_data[0]['city'] );
	$customerpostcode = $order_data[0]['postcode'];

	$customer_weight = $order_data[0]['weight'];

	if ( 'ES' === $customer_country || 'AD' === $customer_country || 'PT' === $customer_country ) {
		$ccc = $nat_ccc;
	} else {
		$ccc = $int_ccc;
	}

	if ( $post_weight ) {
		$customer_weight_kg = seur_always_kg( $weight );
	} else {
		$customer_weight_kg = seur_always_kg( $customer_weight );
	}

	$customer_first_name  = seur_clean_data( $order_data[0]['first_name'] );
	$customer_last_name   = seur_clean_data( $order_data[0]['last_name'] );
	$customer_company     = $order_data[0]['company'];
	$customer_address_1   = seur_clean_data( $order_data[0]['address_1'] );
	$customer_address_2   = seur_clean_data( $order_data[0]['address_2'] );
	$customer_email       = seur_clean_data( $order_data[0]['email'] );
	$customer_phone       = $order_data[0]['phone'];
	$customer_order_notes = seur_clean_data( $order_data[0]['order_notes'] );
	$customer_order_total = str_replace( ',', '.', $order_data[0]['order_total'] );
	$order_pay_method     = seur_clean_data( $order_data[0]['order_pay_method'] );

	if ( $order_data[0]['address_2hop'] ) {
		$customer_address_1 = seur_clean_data( $order_data[0]['address_2hop'] );
	}

	if ( $order_data[0]['city_2shop'] ) {
		$customercity = seur_clean_data( $order_data[0]['city_2shop'] );
	}

	if ( $order_data[0]['postcode_2shop'] ) {
		$customerpostcode = $order_data[0]['postcode_2shop'];
	}

	if ( $order_data[0]['code_centro_2shop'] ) {
		$shop2localcode = '<cod_centro>' . $order_data[0]['code_centro_2shop'] . '</cod_centro>' .
						'<cod_tipo_centro>E,F,S,K,V,U</cod_tipo_centro>' .
						'<c_recogeran>S</c_recogeran>';
	} else {
		$shop2localcode = '';
	}

	if ( 'cod' == $order_pay_method ) {
		$seur_reembolso = '<claveReembolso>f</claveReembolso><valorReembolso>' . $customer_order_total . '</valorReembolso>';
	} else {
		$seur_reembolso = '';
	}

	// SEUR service and Product.

	$seur_service = $product_service_seur[0]['service'];
	$seur_product = $product_service_seur[0]['product'];

	$log->add( 'seur', '$seur_service: ' . $seur_service );
	$log->add( 'seur', '$seur_product: ' . $seur_product );

	if ( 'ES' === $customer_country || 'PT' === $customer_country || 'AD' === $customer_country ) {

		// shipping is to ES, PT or AD, let's check customer data.

		$shipping_class = 0;
		$data           = array(
			0 => $cit_user,
			$cit_contra,
			$customercity,
			$customerpostcode,
		);

		$log->add( 'seur', '$data: ' . print_r( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		$fran = seur_check_city( $data );
		$log->add( 'seur', '$fran: ' . $fran );

		if ( ! $fran ) {
			return 'error 1';
		} else { // city and postcode exist.
			if ( '74' === $fran || '77' === $fran || '56' === $fran || '35' === $fran || '38' === $fran || '52' === $fran || '60' === $fran || '70' === $fran ) {
				$shipping_class = 2;
				$log->add( 'seur', '$shipping_class: ' . $shipping_class );
			}
		}
	} else { // shipping is not to ES, PT or AD.
		$shipping_class = 1;
		$log->add( 'seur', '$shipping_class: ' . $shipping_class );
	}

	$seur_weight_by_label = ( $customer_weight_kg / $numpackages );

	$portes = 'F';

	if ( 0 === (int) $shipping_class && 'Friday' === date( 'l' ) ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		if ( ( 'ES' === $customer_country || 'AD' === $customer_country || 'PT' === $customer_country ) && ( '3' === $seur_service || '9' === $seur_service ) ) {
			$seur_saturday_shipping = '<entrega_sabado>S</entrega_sabado>';
		} else {
			$seur_saturday_shipping = '';
		}
	} else {
		$seur_saturday_shipping = '';
	}

	$log->add( 'seur', '$seur_saturday_shipping: ' . $seur_saturday_shipping );

	if ( 'SMS' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_sms = 'S';
	} else {
		$preaviso_sms = 'N';
	}

	if ( 'SMS' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_sms = 'S';
	} else {
		$reparto_sms = 'N';
	}

	if ( 'EMAIL' === $tipo_aviso && 'S' === $preaviso_notificar ) {
		$preaviso_email = 'S';
	} else {
		$preaviso_email = 'N';
	}

	if ( 'EMAIL' === $tipo_aviso && 'S' === $reparto_notificar ) {
		$reparto_email = 'S';
	} else {
		$reparto_email = 'N';
	}

	if ( 'S' === $preaviso_notificar ) {
			$preaviso_notificar_geo = 'S';
			$preaviso_notificar     = '<test_preaviso>S</test_preaviso>';
	} else {
			$preaviso_notificar_geo = 'N';
			$preaviso_notificar     = '<test_preaviso>N</test_preaviso>';
	}

	if ( 'S' === $reparto_notificar ) {
			$reparto_notificar_geo = 'S';
			$reparto_notificar     = '<test_reparto>S</test_reparto>';
	} else {
			$reparto_notificar_geo = 'N';
			$reparto_notificar     = '<test_reparto>N</test_reparto>';
	}

	if ( 'S' === $preaviso_sms || 'S' === $reparto_sms ) {
			$seur_tcheck_geo = '1';
			$seur_sms        = '<test_sms>S</test_sms>';
	} else {
			$seur_tcheck_geo = '';
			$seur_sms        = '<test_sms>N</test_sms>';
	}

	if ( 'S' === $preaviso_email || 'S' === $reparto_email ) {
			$seur_email_geo = '3';
			$seur_email     = '<test_email>S</test_email>';
	} else {
			$seur_email_geo = '';
			$seur_email     = '<test_email>N</test_email>';
	}

	if ( '1' === $seur_tcheck_geo && '3' === $seur_email_geo ) {
		$seur_email_geo  = '4';
		$seur_tcheck_geo = '';
	}

	if ( ( $mobile_shipping || $mobile_billing ) ) {

		if ( $mobile_shipping ) {
			$seur_sms_mobile_geo = $mobile_shipping;
			$seur_sms_mobile     = '<sms_consignatario>' . $mobile_shipping . '</sms_consignatario>';
		} else {
			$seur_sms_mobile_geo = $mobile_billing;
			$seur_sms_mobile     = '<sms_consignatario>' . $mobile_billing . '</sms_consignatario>';
		}
	} else {
			$seur_tcheck_geo     = 'N';
			$seur_sms_mobile_geo = '';
			$seur_sms            = '<test_sms>N</test_sms>';
			$seur_sms_mobile     = '';
	}

	if ( $order_data[0]['code_centro_2shop'] ) {
		$shop2localcode     = '<cod_centro>' . $order_data[0]['code_centro_2shop'] . '</cod_centro>' .
							'<cod_tipo_centro>E,F,S,K,V,U</cod_tipo_centro>' .
							'<c_recogeran>S</c_recogeran>';
		$reparto_notificar  = '<test_reparto>N</test_reparto>';
		$preaviso_notificar = '<test_preaviso>N</test_preaviso>';
		$seur_email         = '<test_email>N</test_email>';
		$customer_phone     = $mobile_billing;
	}

	$encoding           = '<?xml version="1.0" encoding="ISO-8859-1"?>';
	$datos_envio_inicio = $encoding . '<root><exp>';
	$DatosEnvioFin      = '</exp></root>';

	$xml = '<bulto>
		<ci>' . $cit_pass . '</ci>
		<nif>' . $nif . '</nif>
		<ccc>' . $ccc . '</ccc>
		<servicio>' . $seur_service . '</servicio>
		<producto>' . $seur_product . '</producto>
		<total_bultos>' . $numpackages . '</total_bultos>
		<total_kilos>' . $customer_weight_kg . '</total_kilos>
		<pesoBulto>' . $seur_weight_by_label . '</pesoBulto>
		<observaciones>' . $customer_order_notes . '</observaciones>
		<referencia_expedicion>' . $order_id_seur . '</referencia_expedicion>
		<clavePortes>' . $portes . '</clavePortes>
		<clavePod></clavePod>' .
		$shop2localcode .
		'<tipo_mercancia>' . $tipo_mercancia . '</tipo_mercancia>
		<valor_declarado>' . $customer_order_total . '</valor_declarado>
		<aduana_origen>' . $aduana_origen . '</aduana_origen>
		<aduana_destino>' . $aduana_destino . '</aduana_destino>' .
		$seur_reembolso .
		'<id_mercancia>' . $id_mercancia . '</id_mercancia>
		<descripcion_mercancia>' . $descripcion . '</descripcion_mercancia>
		<codigo_pais_destino>' . $customer_country . '</codigo_pais_destino>
		<libroControl></libroControl>
		<nombre_consignatario>' . $customer_first_name . ' ' . $customer_last_name . '</nombre_consignatario>";
		<direccion_consignatario>' . $customer_address_1 . ' ' . $customer_address_2 . '</direccion_consignatario>
		<tipoVia_consignatario>CL</tipoVia_consignatario>
		<tNumVia_consignatario>N</tNumVia_consignatario>
		<numVia_consignatario>.</numVia_consignatario>
		<escalera_consignatario>.</escalera_consignatario>
		<piso_consignatario>.</piso_consignatario>
		<puerta_consignatario>.</puerta_consignatario>
		<poblacion_consignatario>' . $customercity . '</poblacion_consignatario>
		<codPostal_consignatario>' . $customerpostcode . '</codPostal_consignatario>
		<pais_consignatario>' . $customer_country . '</pais_consignatario>' .
		$preaviso_notificar .
		$reparto_notificar .
		$seur_sms .
		$seur_email .
		$seur_sms_mobile .
		'<email_consignatario>' . $customer_email . '</email_consignatario>' .
		$seur_saturday_shipping .
		'<telefono_consignatario>' . $customer_phone . '</telefono_consignatario>
		<id_mercancia>' . $id_mercancia . '</id_mercancia>
		<atencion_de>' . $customer_first_name . ' ' . $customer_last_name . '</atencion_de>
		<eci>N</eci>
		<et>N</et>
	</bulto>';

	$numero_de_bultos = 1;

	while ( $numero_de_bultos <= $numpackages ) {
		$numero_de_bultos++;
		$complete_xml .= $xml;
	}

	$data_send = $datos_envio_inicio . $complete_xml . $DatosEnvioFin;

	if ( '1' === $geolabel ) {
		// Se usa GeoLabel.
		if ( 'ES' !== $customer_country && 'AD' !== $customer_country && 'PT' !== $customer_country ) {
			// Se utiliza Geolabel, es envío internacional, y es Térmica

			$request_geolabel =
				'{
				"customerBussinesUnit": ' . $franquicia . ',
				"customerCode": ' . $int_ccc . ',
				"customerReference": "' . $order_id_seur . '",
				"service": ' . $seur_service . ',
				"product": ' . $seur_product . ',
				"senderNIF": "' . $nif . '", 
				"senderName": "' . $contacto_nombre . ' ' . $contacto_apellidos . '",
				"senderStreet": "' . $vianombre . '",
				"senderStreetType": "' . $viatipo . '",
				"senderStreetNumType": "N",
				"senderStreetNumber": "' . $vianumero . '",
				"senderDoorway": "' . $puerta . '",
				"senderFloor": "' . $piso . '",
				"senderGate": "",
				"senderStair": "",
				"senderCity": "' . $poblacion . '",
				"senderPostcode": "' . $postalcode . '",
				"senderCountry": "' . $paisgl . '",
				"senderPhone": "' . $telefono . '",
				"consigneeName": "' . $customer_first_name . ' ' . $customer_last_name . '",
				"consigneeStreet": "' . $customer_address_1 . ' ' . $customer_address_2 . '",
				"consigneeStreetType": "",
				"consigneeStreetNumType": "N",
				"consigneeStreetNumber": "",
				"consigneeDoorway": "",
				"consigneeFloor": "",
				"consgineeGate": "",
				"consigneeStair": "",
				"consigneeCity": "' . $customercity . '",
				"consigneePostcode": "' . $customerpostcode . '",
				"consigneeCountry": "' . $customer_country . '",
				"consigneePhone": "' . $customer_phone . '",
				"consigneeContact": "' . $customer_first_name . ' ' . $customer_last_name . '",
				"chargesKey": "",
				"proofReceiptKey": "",
				"cargoType": "",
				"refundKey": "",
				"refundAmount": 0,
				"insuranceKey": "",
				"insuranceAmount": 0,
				"declaredValues": 0,
				"declaredValueCurrency": "",
				"recordBookCheck": "",
				"exchangeKey": "",
				"saturdayCheck": "",
				"coments": "' . $customer_order_notes . '",
				"proposedDate": "",
				"notificationCheck": "' . $preaviso_notificar_geo . '",
				"distributionCheck": "' . $reparto_notificar_geo . '",
				"comunicationTypeCheck": "' . $seur_tcheck_geo . $seur_email_geo . '",
				"mobilePhone": "' . $seur_sms_mobile_geo . '",
				"email": "' . $customer_email . '",
				"pudoID": "",
				"createShipment": "Y",
				"collection": "N",
				"createShipmentReturn": "N",
				"printLabels": "Y",
				"integratedCustomer": "string",
				"parcels": [
					{
					"senderReference": "' . $order_id_seur . '",
					"partnerReference": "",
					"parcelNumber": "",
					"ecb": "",
					"parcelWeight": 0,
					"parcelLength": 0,
					"parcelWidth": 0,
					"parcelHeight": 0,
					"coments": "" 
					}
				],
				"consigneePhone2": "",
				"consigneeIndustrialPark": "",
				"consigneeComents": "" 
				}';

			$log->add( 'seur', $request_geolabel );

			$url = SEUR_URL; // URL produccion.
			$log->add( 'seur', 'calling to ' . $url );
			$response = wp_remote_post(
				$url,
				array(
					'method'       => 'POST',
					'timeout'      => 45,
					'Content-Type' => 'application/json',
					'redirection'  => 5,
					'httpversion'  => '1.0',
					'blocking'     => true,
					'headers'      => array(
						'Content-Type' => 'application/json',
						'Accept'       => 'application/json',
						'user'         => $usercom,
						'password'     => $passcom,
					),
					'body'         => $request_geolabel,
				)
			);

			$body = wp_remote_retrieve_body( $response );
			$log->add( 'seur', '$body: ' . $body );
			$data = json_decode( $body );
			$log->add( 'seur', '$data: ' . print_r( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

			$log->add( 'seur', '$data->status: ' . $data->status );

			if ( 'OK' === $data->status ) {
				$cont = 0;
				$log->add( 'seur', '$data->status: ' . $data->status );
				foreach ( $data->parcels[ $cont ] as $parcel ) {
					if ( ! empty( $data->parcels[ $cont ]->parcelNumber ) ) {

						$txtlabel = utf8_encode( base64_decode( $data->parcels[ $cont ]->label ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
						$log->add( 'seur', '$txtlabel: ' . $txtlabel );

						if ( 'PDF' !== $tipo_etiqueta ) {
							// Se utiliza Geolabel, es envío internacional, y es termica.
							$seur_txt_label  = 'label_order_id_' . $order_id . '_' . $date . '.txt';
							$seur_label_type = 'termica';
						} else {
							// Se utiliza Geolabel, es envío internacional, y es PDF.
							$txtlabel        = seur_from_terminca_to_pdf( $txtlabel );
							$seur_txt_label  = 'label_order_id_' . $order_id . '_' . $date . '.pdf';
							$seur_label_type = 'pdf';
						}

						$upload_path  = $upload_dir . '/' . $seur_txt_label;
						$url_to_label = $upload_url . '/' . $seur_txt_label;

						file_put_contents( $upload_path, $txtlabel ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

						$labelid     = wp_insert_post(
							array(
								'post_title'     => 'Label Order ID ' . $order_id,
								'post_type'      => 'seur_labels',
								'post_status'    => 'publish',
								'ping_status'    => 'closed',
								'comment_status' => 'closed',
								'tax_input'      => array(
									'labels-product' => $seur_shipping_method,
								),
							)
						);
						$update_post = array(
							'ID'         => $labelid,
							'post_title' => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
						);
						wp_update_post( $update_post );

						add_post_meta( $labelid, '_seur_shipping_id_number', $order_id_seur, true );
						add_post_meta( $order_id, '_seur_shipping_id_number', $order_id_seur, true );
						add_post_meta( $order_id, '_seur_label_id_number', $labelid, true );
						add_post_meta( $labelid, '_seur_shipping_method', $seur_shipping_method, true );
						add_post_meta( $labelid, '_seur_shipping_weight', $customer_weight_kg, true );
						add_post_meta( $labelid, '_seur_shipping_packages', $numpackages, true );
						add_post_meta( $labelid, '_seur_shipping_order_id', $order_id, true );
						add_post_meta( $labelid, '_seur_shipping_order_customer_comments', $customer_order_notes, true );
						add_post_meta( $labelid, '_seur_shipping_order_label_file_name', $seur_txt_label, true );
						add_post_meta( $labelid, '_seur_shipping_order_label_path_name', $upload_path, true );
						add_post_meta( $labelid, '_seur_shipping_order_label_url_name', $url_to_label, true );
						add_post_meta( $order_id, '_seur_shipping_order_label_url_name', $url_to_label, true );
						add_post_meta( $labelid, '_seur_label_customer_name', $customer_first_name . ' ' . $customer_last_name, true );
						add_post_meta( $labelid, '_seur_label_type', $seur_label_type, true );

						if ( $labelid ) {

							$result    = true;
							$message   = 'OK';
							$label     = array(
								'result',
								'labelID',
								'message',
							);
							$has_label = array(
								$result,
								$labelid,
								$message,
							);

							$seur_label[] = array_combine( $label, $has_label );

							return $seur_label;
						} else {

							$result    = false;
							$message   = $respuesta->out->mensaje;
							$label     = array(
								'result',
								'labelID',
								'message',
							);
							$has_label = array(
								$result,
								$labelid,
								$message,
							);

							$seur_label[] = array_combine( $label, $has_label );

							$cont++;

							return $seur_label;
						}
					}
				}
			}
		} else {
			$params = array(
				'in0'  => $cit_user,
				'in1'  => $cit_contra,
				'in2'  => 'ZEBRA',
				'in3'  => 'LP2844-Z',
				'in4'  => 'GL',
				'in5'  => $data_send,
				'in6'  => 'seurwoocommerce.xml',
				'in7'  => $nif,
				'in8'  => $franquicia,
				'in9'  => '-1',
				'in10' => 'wooseuroficial',
			);

			$log->add( 'seur', 'Envio a: España, Portugal o Andora' );
			$log->add( 'seur', '$params: ' . print_r( $params, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

			$sc_options = array(
				'connection_timeout' => 60,
			);

			$url = 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl';
			$log->add( 'seur', '$url: ' . $url );

			if ( ! seur_check_url_exists( $url ) ) {
				die( esc_html__( 'We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );
			}

			$soap_client = new SoapClient( 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options );
			$respuesta   = $soap_client->impresionIntegracionConECBWS( $params );

			$log->add( 'seur', '$respuesta: ' . print_r( $respuesta, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

			if ( 'OK' === $respuesta->out->mensaje ) {
				$txtlabel = $respuesta->out->traza;
				if ( 'PDF' !== $tipo_etiqueta ) {
					// Se utiliza Geolabel, es envío nacional, y es termica.
					$seur_txt_label  = 'label_order_id_' . $order_id . '_' . $date . '.txt';
					$seur_label_type = 'termica';
				} else {
					// Se utiliza Geolabel, es envío nacional, y es PDF.
					$txtlabel        = seur_from_terminca_to_pdf( $txtlabel );
					$seur_txt_label  = 'label_order_id_' . $order_id . '_' . $date . '.pdf';
					$seur_label_type = 'pdf';
				}
				$upload_path  = $upload_dir . '/' . $seur_txt_label;
				$url_to_label = $upload_url . '/' . $seur_txt_label;

				file_put_contents( $upload_path, $txtlabel ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

				$labelid     = wp_insert_post(
					array(
						'post_title'     => 'Label Order ID ' . $order_id,
						'post_type'      => 'seur_labels',
						'post_status'    => 'publish',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
						'tax_input'      => array(
							'labels-product' => $seur_shipping_method,
						),
					)
				);
				$update_post = array(
					'ID'         => $labelid,
					'post_title' => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
				);

				wp_update_post( $update_post );

				add_post_meta( $labelid, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_label_id_number', $labelid, true );
				add_post_meta( $labelid, '_seur_shipping_method', $seur_shipping_method, true );
				add_post_meta( $labelid, '_seur_shipping_weight', $customer_weight_kg, true );
				add_post_meta( $labelid, '_seur_shipping_packages', $numpackages, true );
				add_post_meta( $labelid, '_seur_shipping_order_id', $order_id, true );
				add_post_meta( $labelid, '_seur_shipping_order_customer_comments', $customer_order_notes, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_file_name', $seur_txt_label, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_path_name', $upload_path, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $order_id, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $labelid, '_seur_label_customer_name', $customer_first_name . ' ' . $customer_last_name, true );
				add_post_meta( $labelid, '_seur_label_type', $seur_label_type, true );

				if ( $labelid ) {
					$result    = true;
					$message   = 'OK';
					$label     = array(
						'result',
						'labelID',
						'message',
					);
					$has_label = array(
						$result,
						$labelid,
						$message,
					);

					$seur_label[] = array_combine( $label, $has_label );

					return $seur_label;
				} else {
					$result    = false;
					$message   = $respuesta->out->mensaje;
					$label     = array(
						'result',
						'labelID',
						'message',
					);
					$has_label = array(
						$result,
						$labelid,
						$message,
					);

					$seur_label[] = array_combine( $label, $has_label );

					return $seur_label;
				}
			} else {
				$message = $respuesta->out->mensaje;
				echo '<div class="notice notice notice-error">' . esc_html( $message ) . '</div>';
			}
		}
	} else {

		// No se utiliza GeoLabel.

		if ( 'PDF' === $tipo_etiqueta ) {
			$params = array(
				'in0' => $cit_user,
				'in1' => $cit_contra,
				'in2' => $data_send,
				'in3' => 'seurwoocommerce.xml',
				'in4' => $nif,
				'in5' => $franquicia,
				'in6' => '-1',
				'in7' => 'wooseuroficial',
			);

			$url = 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl';

			if ( ! seur_check_url_exists( $url ) ) {
				die( esc_html__( 'We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );
			}

			// pedimos las etiquetas.
			$sc_options = array(
				'connection_timeout' => 60,
				'trace'              => 1,
			);
			$log        = new WC_Logger();
			try {
				$soap_client = new SoapClient( 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options );
				$response    = $soap_client->impresionIntegracionPDFConECBWS( $params );
			} catch ( SoapFault $ex ) {
				$log->add( 'seur', $ex->getMessage() );
				$log->add( 'seur', $response->out->mensaje );
			}

			// echo $response->out->mensaje;.phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// var_dump($respuesta);.
			// echo htmlspecialchars($DatosEnvio);.

			if ( 'OK' === $response->out->mensaje ) {
				$pdf             = base64_decode( $response->out->PDF ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
				$seur_pdf_label  = 'label_order_id_' . $order_id . '_' . $date . '.pdf';
				$seur_label_type = 'pdf';
				$upload_path     = $upload_dir . '/' . $seur_pdf_label;
				$url_to_label    = $upload_url . '/' . $seur_pdf_label;

				file_put_contents( $upload_path, $pdf ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

				$labelid     = wp_insert_post(
					array(
						'post_title'     => 'Label Order ID ' . $order_id,
						'post_type'      => 'seur_labels',
						'post_status'    => 'publish',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
						'tax_input'      => array(
							'labels-product' => $seur_shipping_method,
						),
					)
				);
				$update_post = array(
					'ID'         => $labelid,
					'post_title' => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
				);

				wp_update_post( $update_post );
				add_post_meta( $labelid, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_label_id_number', $labelid, true );
				add_post_meta( $labelid, '_seur_shipping_method', $seur_shipping_method, true );
				add_post_meta( $labelid, '_seur_shipping_weight', $customer_weight_kg, true );
				add_post_meta( $labelid, '_seur_shipping_packages', $numpackages, true );
				add_post_meta( $labelid, '_seur_shipping_order_id', $order_id, true );
				add_post_meta( $labelid, '_seur_shipping_order_customer_comments', $customer_order_notes, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_file_name', $seur_pdf_label, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_path_name', $upload_path, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $order_id, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $labelid, '_seur_label_customer_name', $customer_first_name . ' ' . $customer_last_name, true );
				add_post_meta( $labelid, '_seur_label_type', $seur_label_type, true );

				if ( $labelid ) {
					$result       = true;
					$message      = 'OK';
					$label        = array(
						'result',
						'labelID',
						'message',
					);
					$has_label    = array(
						$result,
						$labelid,
						$message,
					);
					$seur_label[] = array_combine( $label, $has_label );
					return $seur_label;
				} else {
					$result       = false;
					$message      = $response->out->mensaje;
					$label        = array(
						'result',
						'labelID',
						'message',
					);
					$has_label    = array(
						$result,
						$labelid,
						$message,
					);
					$seur_label[] = array_combine( $label, $has_label );
					return $seur_label;
				}
			} else {
				$message      = $response->out->mensaje;
				$result       = false;
				$labelid      = false;
				$label        = array(
					'result',
					'labelID',
					'message',
				);
				$has_label    = array(
					$result,
					$labelid,
					$message,
				);
				$seur_label[] = array_combine( $label, $has_label );
				return $seur_label;
			}
		} else {
			$params = array(
				'in0'  => $cit_user,
				'in1'  => $cit_contra,
				'in2'  => 'ZEBRA',
				'in3'  => 'LP2844-Z',
				'in4'  => '2c',
				'in5'  => $data_send,
				'in6'  => 'seurwoocommerce.xml',
				'in7'  => $nif,
				'in8'  => $franquicia,
				'in9'  => '-1',
				'in10' => 'wooseuroficial',
			);

			$sc_options = array(
				'connection_timeout' => 60,
			);

			$url = 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl';

			if ( ! seur_check_url_exists( $url ) ) {
				die( esc_html__( 'We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );
			}

			$soap_client = new SoapClient( 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options );
			$respuesta   = $soap_client->impresionIntegracionConECBWS( $params );

			if ( 'OK' === $respuesta->out->mensaje ) {
				$txtlabel        = $respuesta->out->traza;
				$seur_txt_label  = 'label_order_id_' . $order_id . '_' . $date . '.txt';
				$seur_label_type = 'termica';
				$upload_path     = $upload_dir . '/' . $seur_txt_label;
				$url_to_label    = $upload_url . '/' . $seur_txt_label;

				file_put_contents( $upload_path, $txtlabel ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

				$labelid     = wp_insert_post(
					array(
						'post_title'     => 'Label Order ID ' . $order_id,
						'post_type'      => 'seur_labels',
						'post_status'    => 'publish',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
						'tax_input'      => array(
							'labels-product' => $seur_shipping_method,
						),
					)
				);
				$update_post = array(
					'ID'         => $labelid,
					'post_title' => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
				);

				wp_update_post( $update_post );

				add_post_meta( $labelid, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_shipping_id_number', $order_id_seur, true );
				add_post_meta( $order_id, '_seur_label_id_number', $labelid, true );
				add_post_meta( $labelid, '_seur_shipping_method', $seur_shipping_method, true );
				add_post_meta( $labelid, '_seur_shipping_weight', $customer_weight_kg, true );
				add_post_meta( $labelid, '_seur_shipping_packages', $numpackages, true );
				add_post_meta( $labelid, '_seur_shipping_order_id', $order_id, true );
				add_post_meta( $labelid, '_seur_shipping_order_customer_comments', $customer_order_notes, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_file_name', $seur_txt_label, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_path_name', $upload_path, true );
				add_post_meta( $labelid, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $order_id, '_seur_shipping_order_label_url_name', $url_to_label, true );
				add_post_meta( $labelid, '_seur_label_customer_name', $customer_first_name . ' ' . $customer_last_name, true );
				add_post_meta( $labelid, '_seur_label_type', $seur_label_type, true );

				if ( $labelid ) {
					$result       = true;
					$message      = 'OK';
					$label        = array(
						'result',
						'labelID',
						'message',
					);
					$has_label    = array(
						$result,
						$labelid,
						$message,
					);
					$seur_label[] = array_combine( $label, $has_label );
					return $seur_label;
				} else {
					$result       = false;
					$message      = $respuesta->out->mensaje;
					$label        = array(
						'result',
						'labelID',
						'message',
					);
					$has_label    = array(
						$result,
						$labelid,
						$message,
					);
					$seur_label[] = array_combine( $label, $has_label );
					return $seur_label;
				}
			} else {
				$message = $respuesta->out->mensaje;
				echo '<div class="notice notice notice-error">' . esc_html( $message ) . '</div>';
			}
		}
	}
}
