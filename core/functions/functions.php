<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// SEUR Get Parent Page
function seur_get_parent_page(){
    $seur_parent = basename( $_SERVER['SCRIPT_NAME'] );
    return $seur_parent;
}

// SEUR Redirect to Welcome/About Page
function seur_welcome_splash(){
    $seur_parent = seur_get_parent_page();

    if ( get_option( 'seur-official-version' ) == SEUR_OFFICIAL_VERSION ) {
        return;
    }
    elseif ( $seur_parent == 'update.php' ) {
        return;
    }
    elseif ( $seur_parent == 'update-core.php' ) {
        return;
    }
    else {
        update_option( 'seur-official-version', SEUR_OFFICIAL_VERSION );
        $seurredirect = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_about_page' ), 'admin.php' ) ) );
        wp_redirect( $seurredirect ); exit;
    }
}
add_action( 'admin_init', 'seur_welcome_splash', 1 );

function seur_debug_mode_notice() {
    $class   = 'notice notice-warning';
    $message = __( 'SEUR_DEBUG is set to TRUE, please set it to false.', 'seur' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

// Some action in debug mode

if ( defined( 'SEUR_DEBUG' ) && SEUR_DEBUG == true ) {
    // add a notice in WordPress admin
    add_action( 'admin_notices', 'seur_debug_mode_notice' );
}

add_action( 'admin_notices', 'seur_admin_notices' );

function seur_admin_notices() {
    $message = get_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice' );

    if ( $message ) {
        delete_transient( get_current_user_id() . '_seur_woo_bulk_action_pending_notice' );

        printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible seur_woo_bulk_action_pending_notice', $message );
    }
}

// Function for check URL's

function seur_check_url_exists( $url ) {

   //check, if a valid url is provided
  $timeout = 10;
  $ch      = curl_init();

  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );

  $http_respond = curl_exec($ch);
  $http_respond = trim( strip_tags( $http_respond ) );
  $http_code    = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

  if ( ( $http_code == "200" ) || ( $http_code == "302" ) ) {
    curl_close( $ch );
    return true;
  } else {
    // return $http_code;, possible too
    curl_close( $ch );
    return false;
  }
}

function seur_search_number_message_result( $howmany ){

    if ( $howmany <= 0 ) {

        $message = _e( 'No Matches Found', 'seur' );
        return $message;
    }
    if ( $howmany == 1 ) {
        $message =  _e( '1 Result Found', 'seur' );
        return $message;
    }
    if( $howmany > 1 ) {
        $message = printf( esc_html__( 'Found %s Results.', 'seur' ), $howmany );
        return $message;
    }
}

function seur_get_real_rate_name( $rate_name ){

    $seur_bc2_custom_name_field  = '';
    $seur_10e_custom_name_field  = '';
    $seur_10ef_custom_name_field = '';
    $seur_13e_custom_name_field  = '';
    $seur_13f_custom_name_field  = '';
    $seur_48h_custom_name_field  = '';
    $seur_72h_custom_name_field  = '';
    $seur_cit_custom_name_field  = '';
    $seur_2SHOP_custom_name_field = '';

    $seur_bc2_custom_name_field  = get_option( 'seur_bc2_custom_name_field'  );
    $seur_10e_custom_name_field  = get_option( 'seur_10e_custom_name_field'  );
    $seur_10ef_custom_name_field = get_option( 'seur_10ef_custom_name_field' );
    $seur_13e_custom_name_field  = get_option( 'seur_13e_custom_name_field'  );
    $seur_13f_custom_name_field  = get_option( 'seur_13f_custom_name_field'  );
    $seur_48h_custom_name_field  = get_option( 'seur_48h_custom_name_field'  );
    $seur_72h_custom_name_field  = get_option( 'seur_72h_custom_name_field'  );
    $seur_cit_custom_name_field  = get_option( 'seur_cit_custom_name_field'  );
    $seur_2SHOP_custom_name_field = get_option( 'seur_2SHOP_custom_name_field');

    if ( ! empty( $seur_bc2_custom_name_field ) && $rate_name ==  $seur_bc2_custom_name_field ) {
        $real_name = 'B2C Estándar';
    } elseif ( ! empty( $seur_10e_custom_name_field ) && $rate_name ==  $seur_10e_custom_name_field ) {
        $real_name = 'SEUR 10 Estándar';
    } elseif ( ! empty( $seur_10ef_custom_name_field ) && $rate_name ==  $seur_10ef_custom_name_field ) {
        $real_name = 'SEUR 10 Frío';
    } elseif ( ! empty( $seur_13e_custom_name_field ) && $rate_name ==  $seur_13e_custom_name_field ) {
        $real_name = 'SEUR 13:30 Estándar';
    } elseif ( ! empty( $seur_13f_custom_name_field ) && $rate_name ==  $seur_13f_custom_name_field ) {
        $real_name = 'SEUR 13:30 Frío';
    } elseif ( ! empty( $seur_48h_custom_name_field ) && $rate_name ==  $seur_48h_custom_name_field ) {
        $real_name = 'SEUR 48H Estándar';
    } elseif ( ! empty( $seur_72h_custom_name_field ) && $rate_name ==  $seur_72h_custom_name_field ) {
        $real_name = 'SEUR 72H Estándar';
    } elseif ( ! empty( $seur_cit_custom_name_field ) && $rate_name ==  $seur_cit_custom_name_field ) {
        $real_name = 'Classic Internacional Terrestre';
    } elseif ( ! empty( $seur_2SHOP_custom_name_field ) && $rate_name ==  $seur_2SHOP_custom_name_field ) {
        $real_name = 'SEUR 2SHOP';
    } else {
        $real_name = $rate_name;
    }

    return $real_name;
}

function seur_get_custom_rate_name( $rate_name ){

    $seur_bc2_custom_name_field  = '';
    $seur_10e_custom_name_field  = '';
    $seur_10ef_custom_name_field = '';
    $seur_13e_custom_name_field  = '';
    $seur_13f_custom_name_field  = '';
    $seur_48h_custom_name_field  = '';
    $seur_72h_custom_name_field  = '';
    $seur_cit_custom_name_field  = '';
    $seur_2SHOP_custom_name_field = '';

    $seur_bc2_custom_name_field  = get_option( 'seur_bc2_custom_name_field'  );
    $seur_10e_custom_name_field  = get_option( 'seur_10e_custom_name_field'  );
    $seur_10ef_custom_name_field = get_option( 'seur_10ef_custom_name_field' );
    $seur_13e_custom_name_field  = get_option( 'seur_13e_custom_name_field'  );
    $seur_13f_custom_name_field  = get_option( 'seur_13f_custom_name_field'  );
    $seur_48h_custom_name_field  = get_option( 'seur_48h_custom_name_field'  );
    $seur_72h_custom_name_field  = get_option( 'seur_72h_custom_name_field'  );
    $seur_cit_custom_name_field  = get_option( 'seur_cit_custom_name_field'  );
    $seur_2SHOP_custom_name_field = get_option( 'seur_2SHOP_custom_name_field');

    if ( ! empty( $seur_bc2_custom_name_field ) && $rate_name ==  'B2C Estándar' ) {
        $custom_name = $seur_bc2_custom_name_field;
    } elseif ( ! empty( $seur_10e_custom_name_field ) && $rate_name ==  'SEUR 10 Estándar' ) {
        $custom_name = $seur_10e_custom_name_field;
    } elseif ( ! empty( $seur_10ef_custom_name_field ) && $rate_name ==  'SEUR 10 Frío' ) {
        $custom_name = $seur_10ef_custom_name_field;
    } elseif ( ! empty( $seur_13e_custom_name_field ) && $rate_name ==  'SEUR 13:30 Estándar' ) {
        $custom_name = $seur_13e_custom_name_field;
    } elseif ( ! empty( $seur_13f_custom_name_field ) && $rate_name ==  'SEUR 13:30 Frío' ) {
        $custom_name = $seur_13f_custom_name_field;
    } elseif ( ! empty( $seur_48h_custom_name_field ) && $rate_name ==  'SEUR 48H Estándar' ) {
        $custom_name = $seur_48h_custom_name_field;
    } elseif ( ! empty( $seur_72h_custom_name_field ) && $rate_name ==  'SEUR 72H Estándar' ) {
        $custom_name = $seur_72h_custom_name_field;
    } elseif ( ! empty( $seur_cit_custom_name_field ) && $rate_name ==  'Classic Internacional Terrestre' ) {
        $custom_name = $seur_cit_custom_name_field;
    } elseif ( ! empty( $seur_2SHOP_custom_name_field ) && $rate_name ==  'SEUR 2SHOP' ) {
        $custom_name = $seur_2SHOP_custom_name_field;
    } else {
        $custom_name = $rate_name;
    }

    return $custom_name;
}

function SeurCheckCity( $datos ){

    $url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
    if ( ! seur_check_url_exists( $url ) ) die( __('We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );
    $sc_options = array(
                'connection_timeout' => 30
            );

        $soap_client = new SoapClient('https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options);

        $parametros = array(
        'in0'=>"",
        'in1'=>$datos[2],
        'in2'=>$datos[3],
        'in3'=>"",
        'in4'=>"",
        'in5'=>$datos[0],
        'in6'=>$datos[1]);

    $respuesta  = $soap_client->infoPoblacionesCortoStr($parametros);
    $string_xml = htmlspecialchars_decode($respuesta->out);
    $strXml     = iconv("UTF-8","ISO-8859-1",$string_xml);
    $xml         = simplexml_load_string($strXml);

    $cuantos = $xml->attributes()->NUM;

    if ( $cuantos !=1 )
        return "ERROR";
    else
     return $xml->REG1->COD_UNIDAD_ADMIN;
}

function seur_custom_rates_load_js(){
    wp_enqueue_script(  'custom-rates-seur',                SEUR_PLUGIN_URL . 'assets/js/custom-rates.js',              array(),                                    SEUR_OFFICIAL_VERSION );
    wp_enqueue_script(  'jquery-datattables-seur-rates',    SEUR_PLUGIN_URL . 'assets/js/jquery.dataTables.min.js',     array( 'jquery','jquery-ui-core'        ),  SEUR_OFFICIAL_VERSION );
    wp_enqueue_script(  'jqueryui-datattables-seur-rates',  SEUR_PLUGIN_URL . 'assets/js/dataTables.jqueryui.min.js',   array( 'jquery','jquery-ui-core'        ),  SEUR_OFFICIAL_VERSION );
    wp_enqueue_script(  'datattables-seur-rates',           SEUR_PLUGIN_URL . 'assets/js/datatables.min.js',            array( 'jquery-datattables-seur-rates'  ),  SEUR_OFFICIAL_VERSION );
    wp_enqueue_script(  'custom-table-seur-rates',          SEUR_PLUGIN_URL . 'assets/js/seur-custom-rates.js',         array( 'datattables-seur-rates', 'jquery-ui-autocomplete' ),  SEUR_OFFICIAL_VERSION );
    $seurratesphpfiles = array( 'pathtorates' => SEUR_PLUGIN_URL . 'core/pages/rates/' ) ;
    wp_localize_script( 'custom-table-seur-rates', 'custom_table_seur_rates', $seurratesphpfiles );
}

function seur_select2_load_js(){
    wp_enqueue_script( 'seur-select2', SEUR_PLUGIN_URL . 'assets/js/select2.js', array('jquery','jquery-ui-core'), SEUR_OFFICIAL_VERSION );
}

function seur_settings_load_js(){
    wp_enqueue_script( 'seur-tooltip', SEUR_PLUGIN_URL . 'assets/js/tooltip.js', array('jquery-ui-tooltip'), SEUR_OFFICIAL_VERSION );
    wp_enqueue_script( 'seur-switchery',  SEUR_PLUGIN_URL . 'assets/js/switchery.min.js',  array(), SEUR_OFFICIAL_VERSION );
}

function seur_select2_custom_load_js(){
    wp_enqueue_script( 'seur-select2custom', SEUR_PLUGIN_URL . 'assets/js/select2custom.js', array('seur-select2'), SEUR_OFFICIAL_VERSION );
}

function seur_auto_country_state_js(){
    wp_enqueue_script( 'seur-country-state', SEUR_PLUGIN_URL . 'assets/js/seur-country-state.js', array( 'jquery'), SEUR_OFFICIAL_VERSION );
}

function seur_datepicker_js(){
    wp_enqueue_script( 'seur-datepicker', SEUR_PLUGIN_URL . 'assets/js/seur-datepicker.js', array( 'jquery', 'jquery-ui-datepicker'), SEUR_OFFICIAL_VERSION );
}

function seur_labels_view_pdf_js() {
    global $post_type;
    if( 'seur' == $post_type ){
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

function seur_styles_css($hook){
    global $seuraddform, $seurrates, $seurcreaterate, $seurdeleterate, $seurupdatecustomrate, $seureditcustomrate;

    if( $seuraddform != $hook && $seurrates != $hook && $seurcreaterate != $hook && $seurdeleterate != $hook && $seurupdatecustomrate != $hook && $seureditcustomrate != $hook ) {
        return; } else {
        wp_register_style( 'seurCSS',           SEUR_PLUGIN_URL . 'assets/css/seur-addform-rates.css',  array(), SEUR_OFFICIAL_VERSION );
        wp_register_style( 'seurSelect2',       SEUR_PLUGIN_URL . 'assets/css/select2.css',             array(), SEUR_OFFICIAL_VERSION );
        wp_register_style( 'seurSelect2Custom', SEUR_PLUGIN_URL . 'assets/css/select2custom.css',       array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurCSS' );
        wp_enqueue_style( 'seurSelect2' );
        wp_enqueue_style( 'seurSelect2Custom' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_styles_css' );

function seur_rates_css($hook){
    global $seurrates;

    if( $seurrates != $hook ) {
        return;
        } else {
            wp_register_style( 'seurratescss',           SEUR_PLUGIN_URL . 'assets/css/seur-rates.css',  array(), SEUR_OFFICIAL_VERSION );
            wp_enqueue_style( 'seurratescss' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_rates_css' );

function seur_datepicker_css($hook){
    global $seurmanifest;

    if( $seurmanifest != $hook ) {
        return;
        } else {
            wp_register_style( 'seurdatepickercss',           SEUR_PLUGIN_URL . 'assets/css/jquery-ui.css',  array(), SEUR_OFFICIAL_VERSION );
            wp_enqueue_style( 'seurdatepickercss' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_datepicker_css' );

function seur_css_pdf_viewer(){
    global $post_type;
     if( 'shop_order' == $post_type ){
          wp_register_style( 'seurfontswo', SEUR_PLUGIN_URL . 'assets/css/seur-woo.css',       array(), SEUR_OFFICIAL_VERSION );
          wp_enqueue_style( 'seurfontswo' );
         }

}
add_action( 'admin_enqueue_scripts', 'seur_css_pdf_viewer' );

function seur_css_cpt_label_view(){
    global $post_type;
     if( 'seur_labels' == $post_type ){
          wp_register_style( 'seurcptlabelsview', SEUR_PLUGIN_URL . 'assets/css/cpt-labels.css',  array(), SEUR_OFFICIAL_VERSION );
          wp_enqueue_style( 'seurcptlabelsview' );
         }

}
add_action( 'admin_enqueue_scripts', 'seur_css_cpt_label_view' );

function seur_settings_styles_css($hook){
    global $seurconfig, $seuraddform, $seurrates;

    if( $seurconfig != $hook && $seuraddform != $hook && $seurrates != $hook ) {
        return; } else {
        wp_register_style( 'seurSettingsCSS', SEUR_PLUGIN_URL . 'assets/css/seur-setting.css', array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurSettingsCSS' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_settings_styles_css' );

function seur_auto_state_country_styles_css($hook){
    global $seuraddform, $seureditcustomrate;

    if( $seuraddform != $hook && $seureditcustomrate != $hook ) {
        return; } else {
        wp_register_style( 'seurAutoStateCountryCSS', SEUR_PLUGIN_URL . 'assets/css/seur-auto-state-country.css', array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurAutoStateCountryCSS' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_auto_state_country_styles_css' );

function seur_get_labels_page_styles_css($hook){
    global $seur_get_labels;

    if( $seur_get_labels != $hook ) {
        return; } else {
        wp_register_style( 'seurGetLabelsCSS', SEUR_PLUGIN_URL . 'assets/css/get-labels.css', array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurGetLabelsCSS' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_get_labels_page_styles_css' );

function seur_nomenclator_styles_css( $hook ){
    global $seurnomenclator, $seurmanifest;

    if( $seurnomenclator != $hook && $seurmanifest != $hook ) {
        return; } else {
        wp_register_style( 'seurNomenclatorCSS', SEUR_PLUGIN_URL . 'assets/css/seur-nomenclator.css', array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurNomenclatorCSS' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_nomenclator_styles_css' );

function seur_about_styles_css( $hook ){
    global $seurabout;

    if( $seurabout != $hook ) {
        return; } else {
        wp_register_style( 'seurAboutCSS', SEUR_PLUGIN_URL . 'assets/css/seur-about.css', array(), SEUR_OFFICIAL_VERSION );
        wp_enqueue_style( 'seurAboutCSS' );
    }
}
add_action( 'admin_enqueue_scripts', 'seur_about_styles_css' );

add_filter( 'custom_menu_order', '__return_true' );

function seur_remove_menu_items(){
    global $submenu;

    if ( isset( $submenu['seur'] ) ) {
        // Remove 'Seur' submenu items
        //unset( $submenu['seur'][0]  ); // SEUR submenu (same as SEUR settings)
        unset( $submenu['seur'][6]  ); // Add Form
        unset( $submenu['seur'][7]  ); // Create Rate
        unset( $submenu['seur'][8]  ); // Delete Rate
        unset( $submenu['seur'][9]  ); // Update Rate
        unset( $submenu['seur'][10] ); // Edit Rate
        unset( $submenu['seur'][11] ); // Process Country State
        unset( $submenu['seur'][12] ); // Get Label
        unset( $submenu['seur'][15] ); // Get labels from order
    }
}
add_action( 'admin_head', 'seur_remove_menu_items' );



//The next add_action is only for print the Array menu, for developing purpose.
//add_action( 'admin_init', 'seur_screen_menu_submenus_array' );
function seur_screen_menu_submenus_array() {
    echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
    echo '<pre>' . print_r( $GLOBALS[ 'submenu' ], TRUE) . '</pre>';
}

function seur_look_url(){
    global $menu;

    foreach ( $menu as $item ) {

        // Get name of menu item
        $name = $item[0];

        // Get dashboard item file
        $file = $item[2];

        // Get URL for item
        $url = get_admin_menu_item_url( $file );

        echo "$name: $url<br />";

    }
}

// Add notices
function seur_check_curl_admin_notice__error() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'CURL is needed by SEUR Plugin, please ask for CURL to your hosting provider', 'seur' ); ?></p>
    </div>
    <?php
}
if ( ! function_exists('curl_version') ) {
    add_action( 'admin_notices', 'seur_check_curl_admin_notice__error' );
    }

function seur_check_soap_admin_notice__error() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'SOAP is needed by SEUR Plugin, please ask for SOAP to your hosting provider', 'seur' ); ?></p>
    </div>
    <?php
}
if ( ! class_exists( 'SoapClient') ) {
    add_action( 'admin_notices', 'seur_check_soap_admin_notice__error' );
    }

function seur_check_xml_admin_notice__error() {
    ?>
    <div class="notice notice-error">
        <p><?php _e( 'XML (simplexml_load_string) is needed by SEUR Plugin, please ask for XML to your hosting provider', 'seur' ); ?></p>
    </div>
    <?php
}
if ( ! function_exists ( 'simplexml_load_string' ) ) {
    add_action( 'admin_notices', 'seur_check_xml_admin_notice__error' );
    }

function seur_sanitize_postcode( $postcode, $country = NULL ) {

        $unsafe_zipcode = '';
        $unsafe_zipcode = $postcode;


        $safe_zipcode_trim = trim( $unsafe_zipcode );
        $safe_zipcode      = sanitize_text_field( $safe_zipcode_trim );


        return $safe_zipcode;

}

function seur_gets_all_orders() {
        global $wpdb;

        $tabla       = $wpdb->prefix . 'seur_labels';
        $sql         = "SELECT * FROM $tabla";
        $shop_orders = $wpdb->get_results($sql);

        return $shop_orders;
}

function seur_get_countries() {
    $countries = array();

    if ( ! $countries ) {
        $countries = include_once SEUR_PLUGIN_PATH . 'core/places/countries.php';
    }
    asort( $countries );
    return $countries;
}

function seur_get_countries_states( $country ) {
    $states = array();
    $states_file = SEUR_PLUGIN_PATH . 'core/places/states/' . $country . '.php';

    if ( ! $states &&  file_exists( $states_file) ) {
        $states = include_once SEUR_PLUGIN_PATH . 'core/places/states/' . $country . '.php';
        asort( $states );
    } else {
        $states = false;
    }
    return $states;
}

function seur_get_custom_rates( $output_type = 'OBJECT', $type = 'price' ){
    global $wpdb;

    $table      = $wpdb->prefix . SEUR_TBL_SCR;
    $getrates   = $wpdb->get_results( "SELECT * FROM $table WHERE type = '$type' ORDER BY ID ASC", $output_type );

    return $getrates;
}

function seur_search_allowed_rates_by_country( $allowedcountry ) {

    $filtered_rates_by_country = array();
    $rates_type = get_option( 'seur_rates_type_field' );
    $output_type = 'OBJECT';

    $getrates = seur_get_custom_rates( $output_type, $rates_type );

    foreach ( $getrates as $rate ) {

        $country = $rate->country;
        $rateid  = $rate->ID;

        if ( $allowedcountry == $country ) {

            $columns    = array(
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
                            'type'
                        );

            $valors     = array(
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
                            $rate->type
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

                if ( $country == '*' ) {

                    $columns    = array(
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
                                    'type'
                                );

                    $valors     = array(
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
                                    $rate->type
                                );

                    $filtered_rates_by_country[] = array_combine( $columns, $valors );

                }
            }
        }
        return $filtered_rates_by_country;
}

function seur_seach_allowed_states_filtered_by_countries( $allowedstate, $filtered_rates_by_country ){

        $filtered_rates_by_state = array();

        foreach ( $filtered_rates_by_country as $allowedrate ) {

            $state  = $allowedrate['state'];
            $rateid = $allowedrate['ID'];

            if ( $allowedstate == $state ) {

                $columns    = array(
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
                                'type'
                             );

                $valors     = array(
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
                                $allowedrate['type']
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

                    if ( $state == '*' ) {

                        $columns    = array(
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
                                        'type'
                                    );

                        $valors     = array(
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
                                        $allowedrate['type']
                                    );

                        $filtered_rates_by_state[] = array_combine( $columns, $valors );

                    }
                }
            }
            return $filtered_rates_by_state;
}

function seur_seach_allowed_postcodes_filtered_by_states( $allowedpostcode, $filtered_rates_by_state ){

        $filtered_rates_by_postcode = array();

        foreach ( $filtered_rates_by_state as $allowedrate ) {

            $postcode   = $allowedrate['postcode'];
            $rateid     = $allowedrate['ID'];

            if ( $allowedpostcode == $postcode ) {

                $columns    = array(
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
                                'type'
                            );

                $valors     = array(
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
                                $allowedrate['type']
                            );

                $filtered_rates_by_postcode[] = array_combine( $columns, $valors );

                }
        }
        if ( $filtered_rates_by_postcode ) {

            return $filtered_rates_by_postcode;

            } else {

                foreach ( $filtered_rates_by_state as $allowedrate ) {

                    $postcode   = $allowedrate['postcode'];
                    $rateid     = $allowedrate['ID'];

                    if ( $postcode == '*' ) {

                        $columns    = array(
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
                                        'type'
                                    );

                        $valors     = array(
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
                                        $allowedrate['type']
                                    );

                        $filtered_rates_by_postcode[] = array_combine( $columns, $valors );

                        }
                }

            }
            return $filtered_rates_by_postcode;
}

function seur_seach_allowed_prices_filtered_by_postcode( $allowedprice, $filtered_rates_by_postcode ){

        $filtered_rates_by_price = array();

        foreach ( $filtered_rates_by_postcode as $allowedrate ) {

            $minprice   = $allowedrate['minprice'];
            $maxprice   = $allowedrate['maxprice'];
            $rateid     = $allowedrate['ID'];

            if ( ( $minprice <= $allowedprice ) &&  ( $maxprice > $allowedprice ) ) {

                $columns    = array(
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
                                'type'
                            );

                $valors     = array(
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
                                $allowedrate['type']
                            );

                $filtered_rates_by_price[] = array_combine( $columns, $valors );

                }
        }
    return $filtered_rates_by_price;
}

function seur_show_availables_rates( $country = NULL, $state = NULL, $postcode = NULL, $price = NULL ){

    if ( ! $country )   $country           = '*';
    if ( ! $state )     $state             = '*';
    if ( ! $postcode )  $postcode          = '*';
    if ( $postcode == '00000' )  $postcode = '*';
    if ( ! $price  )    $price             = '0';

    $filtered_rates_by_country  = array();
    $filtered_rates_by_state    = array();
    $filtered_rates_by_postcode = array();
    $ratestoscreen              = array();

    $filtered_rates_by_country  = seur_search_allowed_rates_by_country( $country );
    $filtered_rates_by_state    = seur_seach_allowed_states_filtered_by_countries( $state, $filtered_rates_by_country  );
    $filtered_rates_by_postcode = seur_seach_allowed_postcodes_filtered_by_states( $postcode, $filtered_rates_by_state );
    $ratestoscreen              = seur_seach_allowed_prices_filtered_by_postcode( $price, $filtered_rates_by_postcode  );


    return $ratestoscreen;
}

function seur_get_user_settings() {

    $seur_user_settings = array();

    if ( get_option( 'seur_nif_field' ) )                   { $seur_nif_field                 = get_option( 'seur_nif_field'                  ); } else { $seur_nif_field                   = ''; }
    if ( get_option( 'seur_empresa_field' ) )               { $seur_empresa_field             = get_option( 'seur_empresa_field'              ); } else { $seur_empresa_field               = ''; }
    if ( get_option( 'seur_viatipo_field' ) )               { $seur_viatipo_field             = get_option( 'seur_viatipo_field'              ); } else { $seur_viatipo_field               = ''; }
    if ( get_option( 'seur_vianombre_field' ) )             { $seur_vianombre_field           = get_option( 'seur_vianombre_field'            ); } else { $seur_vianombre_field             = ''; }
    if ( get_option( 'seur_vianumero_field' ) )             { $seur_vianumero_field           = get_option( 'seur_vianumero_field'            ); } else { $seur_vianumero_field             = ''; }
    if ( get_option( 'seur_escalera_field' ) )              { $seur_escalera_field            = get_option( 'seur_escalera_field'             ); } else { $seur_escalera_field              = ''; }
    if ( get_option( 'seur_piso_field' ) )                  { $seur_piso_field                = get_option( 'seur_piso_field'                 ); } else { $seur_piso_field                  = ''; }
    if ( get_option( 'seur_puerta_field' ) )                { $seur_puerta_field              = get_option( 'seur_puerta_field'               ); } else { $seur_puerta_field                = ''; }
    if ( get_option( 'seur_postal_field' ) )                { $seur_postal_field              = get_option( 'seur_postal_field'               ); } else { $seur_postal_field                = ''; }
    if ( get_option( 'seur_poblacion_field' ) )             { $seur_poblacion_field           = get_option( 'seur_poblacion_field'            ); } else { $seur_poblacion_field             = ''; }
    if ( get_option( 'seur_provincia_field' ) )             { $seur_provincia_field           = get_option( 'seur_provincia_field'            ); } else { $seur_provincia_field             = ''; }
    if ( get_option( 'seur_pais_field' ) )                  { $seur_pais_field                = get_option( 'seur_pais_field'                 ); } else { $seur_pais_field                  = ''; }
    if ( get_option( 'seur_telefono_field' ) )              { $seur_telefono_field            = get_option( 'seur_telefono_field'             ); } else { $seur_telefono_field              = ''; }
    if ( get_option( 'seur_email_field' ) )                 { $seur_email_field               = get_option( 'seur_email_field'                ); } else { $seur_email_field                 = ''; }
    if ( get_option( 'seur_contacto_nombre_field' ) )       { $seur_contacto_nombre_field     = get_option( 'seur_contacto_nombre_field'      ); } else { $seur_contacto_nombre_field       = ''; }
    if ( get_option( 'seur_contacto_apellidos_field' )  )   { $seur_contacto_apellidos_field  = get_option( 'seur_contacto_apellidos_field'   ); } else { $seur_contacto_apellidos_field    = ''; }
    if ( get_option( 'seur_cit_codigo_field' ) )            { $seur_cit_codigo_field          = get_option( 'seur_cit_codigo_field'           ); } else { $seur_cit_codigo_field            = ''; }
    if ( get_option( 'seur_cit_usuario_field' ) )           { $seur_cit_usuario_field         = get_option( 'seur_cit_usuario_field'          ); } else { $seur_cit_usuario_field           = ''; }
    if ( get_option( 'seur_cit_contra_field' ) )            { $seur_cit_contra_field          = get_option( 'seur_cit_contra_field'           ); } else { $seur_cit_contra_field            = ''; }
    if ( get_option( 'seur_ccc_field' ) )                   { $seur_ccc_field                 = get_option( 'seur_ccc_field'                  ); } else { $seur_ccc_field                   = ''; }
    if ( get_option( 'seur_franquicia_field' ) )            { $seur_franquicia_field          = get_option( 'seur_franquicia_field'           ); } else { $seur_franquicia_field            = ''; }
    if ( get_option( 'seur_seurcom_usuario_field' ) )       { $seur_seurcom_usuario_field     = get_option( 'seur_seurcom_usuario_field'      ); } else { $seur_seurcom_usuario_field       = ''; }
    if ( get_option( 'seur_seurcom_contra_field' ) )        { $seur_seurcom_contra_field      = get_option( 'seur_seurcom_contra_field'       ); } else { $seur_seurcom_contra_field        = ''; }

    if ( $seur_pais_field ) {
        if ( $seur_pais_field == 'ES') {
            $seur_pais_field = 'España';
        }
        if ( $seur_pais_field == 'PT') {
            $seur_pais_field = 'Portugal';
        }
        if ( $seur_pais_field == 'AD') {
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
                'franquicia',
                'seurcom_usuario',
                'seurcom_contra'
                );

    $value = array(
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
                $seur_franquicia_field,
                $seur_seurcom_usuario_field,
                $seur_seurcom_contra_field
            );

    $seur_user_settings[] = array_combine( $option, $value );

    return $seur_user_settings;



}

function seur_get_advanced_settings() {

    $seur_advanced_settings = array();

    if ( get_option( 'seur_preaviso_notificar_field' ) )     { $seur_preaviso_notificar_field     = get_option( 'seur_preaviso_notificar_field'     ); } else { $seur_preaviso_notificar_field     = ''; }
    if ( get_option( 'seur_reparto_notificar_field' ) )      { $seur_reparto_notificar_field      = get_option( 'seur_reparto_notificar_field'      ); } else { $seur_reparto_notificar_field      = ''; }
    if ( get_option( 'seur_tipo_notificacion_field' ) )      { $seur_tipo_notificacion_field      = get_option( 'seur_tipo_notificacion_field'      ); } else { $seur_tipo_notificacion_field      = ''; }
    if ( get_option( 'seur_tipo_etiqueta_field' ) )          { $seur_tipo_etiqueta_field          = get_option( 'seur_tipo_etiqueta_field'          ); } else { $seur_tipo_etiqueta_field          = ''; }
    if ( get_option( 'seur_aduana_origen_field' ) )          { $seur_aduana_origen_field          = get_option( 'seur_aduana_origen_field'          ); } else { $seur_aduana_origen_field          = ''; }
    if ( get_option( 'seur_aduana_destino_field' ) )         { $seur_aduana_destino_field         = get_option( 'seur_aduana_destino_field'         ); } else { $seur_aduana_destino_field         = ''; }
    if ( get_option( 'seur_tipo_mercancia_field' ) )         { $seur_tipo_mercancia_field         = get_option( 'seur_tipo_mercancia_field'         ); } else { $seur_tipo_mercancia_field         = ''; }
    if ( get_option( 'seur_id_mercancia_field' ) )           { $seur_id_mercancia_field           = get_option( 'seur_id_mercancia_field'           ); } else { $seur_id_mercancia_field           = ''; }
    if ( get_option( 'seur_descripcion_field' ) )            { $seur_descripcion_field            = get_option( 'seur_descripcion_field'            ); } else { $seur_descripcion_field            = ''; }

    $option = array(
                'preaviso_notificar',
                'reparto_notificar',
                'tipo_notificacion',
                'tipo_etiqueta',
                'aduana_origen',
                'aduana_destino',
                'tipo_mercancia',
                'id_mercancia',
                'descripcion'
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
                $seur_descripcion_field
            );

    $seur_advanced_settings[] = array_combine( $option, $value );

    return $seur_advanced_settings;



}

function seur_get_order_data( $post_id ) {

    $post = get_post( $post_id );
    $seur_order_data = array();

    if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ){

        $title             = $post->post_title;
        $weight            = get_post_meta( $post_id, '_seur_cart_weight',      true );
        $country           = get_post_meta( $post_id, '_shipping_country',      true );
        $first_name        = get_post_meta( $post_id, '_shipping_first_name',   true );
        $last_name         = get_post_meta( $post_id, '_shipping_last_name',    true );
        $company           = get_post_meta( $post_id, '_shipping_company',      true );
        $address_1         = get_post_meta( $post_id, '_shipping_address_1',    true );
        $address_2         = get_post_meta( $post_id, '_shipping_address_2',    true );
        $city              = get_post_meta( $post_id, '_shipping_city',         true );
        $postcode          = get_post_meta( $post_id, '_shipping_postcode',     true );
        $email             = get_post_meta( $post_id, '_billing_email',         true );
        $phone             = get_post_meta( $post_id, '_billing_phone',         true );
        $order_total       = get_post_meta( $post_id, '_order_total',           true );
        $order_pay_method  = get_post_meta( $post_id, '_payment_method',        true );

        // SEUR 2SHOP shipping
        $address_2hop      = get_post_meta( $post_id, '_seur_2shop_address',    true );
        $postcode_2shop    = get_post_meta( $post_id, '_seur_2shop_postcode',   true );
        $city_2shop        = get_post_meta( $post_id, '_seur_2shop_city',       true );
        $code_centro_2shop = get_post_meta( $post_id, '_seur_2shop_codCentro',  true );

        $order_notes      = esc_html( $post->post_excerpt );

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
                    'code_centro_2shop'
                );

        $value = array(
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
                    $code_centro_2shop
                );

        $seur_order_data[] = array_combine( $option, $value );
    }

    return $seur_order_data;
}

function seur_get_all_shipping_products(){
    global $wpdb;

    $tabla     = $wpdb->prefix . SEUR_PLUGIN_SVPR;
    $sql       = "SELECT * FROM $tabla";
    $registros = $wpdb->get_results($sql);

    return $registros;
}

function seur_return_shipping_product_id( $shipping_product ){

    $products = seur_get_all_shipping_products();

    foreach ( $products as $product ){
        if ( $product->descripcion == $shipping_product ) {
            $product_id =  $product->ID;
        }
    }
    return $product_id;
}

function seur_get_service_product_shipping_product( $method_id ){

    $service_product = array();

    $products = seur_get_all_shipping_products();

    foreach ( $products as $product1 ){
        if ( $product1->ID == $method_id ) {

            $service = $product1->ser;
            $product = $product1->pro;
        }
    }
    $option = array(
                    'service',
                    'product'
                );
    $value = array(
                    $service,
                    $product
                );

    $service_product[] = array_combine( $option, $value );

    return $service_product;
}

function seur_get_shipping_method( $order_id ){

    $order = new WC_Order( $order_id );

    $shipping_method = @array_shift($order->get_items( 'shipping' ));
    $shipping_method_name = $shipping_method['name'];

    return $shipping_method_name;
}

function seur_upload_dir( $dir_name = NULL ){

    // $dir_name can be, NULL, labels or manifests

    if ( $dir_name ) {

        $new_dir_name = '_' . $dir_name;
        } else {
            $new_dir_name = '';
        }

    $seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );

    if( $seur_upload_dir ){
        $seur_upload_dir = $seur_upload_dir;

    } else {

        seur_create_upload_folder_hook();
        $seur_upload_dir = get_option( 'seur_uploads_dir' . $new_dir_name );

    }

    return $seur_upload_dir;
}

function seur_upload_url( $dir_name = NULL ){

    if ( $dir_name ) {

        $new_dir_name = '_' . $dir_name;
        } else {
            $new_dir_name = '';
        }

    $seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );

    if( $seur_upload_url ){
        $seur_upload_url = $seur_upload_url;

    } else {

        seur_create_upload_folder_hook();
        $seur_upload_url = get_option( 'seur_uploads_url' . $new_dir_name );

    }

    return $seur_upload_url;
}

function seur_clean_data( $out ){

    $out = str_replace ( "Á", "A", $out );
    $out = str_replace ( "À", "A", $out );
    $out = str_replace ( "Ä", "A", $out );
    $out = str_replace ( "É", "E", $out );
    $out = str_replace ( "È", "E", $out );
    $out = str_replace ( "Ë", "E", $out );
    $out = str_replace ( "Í", "I", $out );
    $out = str_replace ( "Ì", "I", $out );
    $out = str_replace ( "Ï", "I", $out );
    $out = str_replace ( "Ó", "O", $out );
    $out = str_replace ( "Ò", "O", $out );
    $out = str_replace ( "Ö", "O", $out );
    $out = str_replace ( "Ú", "U", $out );
    $out = str_replace ( "Ù", "U", $out );
    $out = str_replace ( "Ü", "U", $out );
    $out = str_replace ( "&", "-", $out );
    $out = str_replace ( "<", " ", $out );
    $out = str_replace ( ">", " ", $out );
    $out = str_replace ( "/", " ", $out );
    $out = str_replace ( "\"", " ", $out );
    $out = str_replace ( "'", " ", $out  );
    $out = str_replace ( "\"", " ", $out );
    $out = str_replace ( "?", " ", $out );
    $out = str_replace ( "¿", " ", $out );

    return $out;
}

function seur_always_kg( $weight ) {

    $weight_unit =  get_option('woocommerce_weight_unit');

    if ( $weight_unit == 'kg'){
        $weight_kg = $weight;
        }
    if ( $weight_unit == 'g') {
        $weight_kg = (string)( number_format( $weight/1000, 3, '.', '' ) );
        }

    return $weight_kg;
}

function seur_create_random_shippping_id(){

    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    $max = strlen($characters) - 1;
    $random_string_length = 5;
    for ($i = 0; $i < $random_string_length; $i++) {
        $string .= $characters[mt_rand(0, $max)];
    }

    return $string;
 }

function seur_get_label( $order_id, $numpackages = '1', $weight = '1', $post_weight = false ) {
    global $error;

    $pre_id_seur             = seur_create_random_shippping_id();
    $order_id_seur           = $pre_id_seur . $order_id;

    $seur_pdf_label          = '';
    $TotalBultos             = '';
    $pdf                     = '';
    $ADUANASSW               = '';
    $INTERNACIONALSW         = '';
    $B2CSW                   = '';
    $seur_saturday_shipping  = '';
    $complete_xml            = '';
    $upload_dir              = seur_upload_dir( 'labels' );
    $upload_url              = seur_upload_url( 'labels' );
    $order_data              = array();
    $user_data               = array();
    $advanced_data           = array();
    $product_service_seur    = array();
    $seur_shipping_method_tmp = seur_get_shipping_method( $order_id );
    $seur_shipping_method    = seur_get_real_rate_name( $seur_shipping_method_tmp );
    $seur_shipping_method_id = seur_return_shipping_product_id( $seur_shipping_method );
    $date                    = date('d-m-Y');
    $mobile_shipping         = get_post_meta( $order_id, '_shipping_mobile_phone', true );
    $mobile_billing          = get_post_meta( $order_id, '_billing_mobile_phone', true );

    // All needed Data return Array

    $order_data              = seur_get_order_data( $order_id );
    $user_data               = seur_get_user_settings();
    $advanced_data           = seur_get_advanced_settings();
    $product_service_seur    = seur_get_service_product_shipping_product( $seur_shipping_method_id );

    // User settings

    $cit_pass                = $user_data[0]['cit_codigo'];
    $cit_user                = $user_data[0]['cit_usuario'];
    $cit_contra              = $user_data[0]['cit_contra'];
    $nif                     = $user_data[0]['nif'];
    $franquicia              = $user_data[0]['franquicia'];
    $ccc                     = $user_data[0]['ccc'];
    $usercom                 = $user_data[0]['seurcom_usuario'];
    $passcom                 = $user_data[0]['seurcom_contra'];

    // Advanced User Settings

    $aduana_origen           = $advanced_data[0]['aduana_origen'];
    $aduana_destino          = $advanced_data[0]['aduana_destino'];
    $tipo_mercancia          = $advanced_data[0]['tipo_mercancia'];
    $id_mercancia            = $advanced_data[0]['id_mercancia'];
    $descripcion             = $advanced_data[0]['descripcion'];
    $preaviso_notificar      = $advanced_data[0]['preaviso_notificar'];
    if( $preaviso_notificar  == '1') { $preaviso_notificar = 'S'; } else { $preaviso_notificar = 'N'; }
    $reparto_notificar       = $advanced_data[0]['reparto_notificar'];
    if ( $reparto_notificar  == '1' ) { $reparto_notificar = 'S'; } else { $reparto_notificar = 'N'; }
    $tipo_aviso              = $advanced_data[0]['tipo_notificacion'];

    if ( $tipo_aviso == 'SMS' && $preaviso_notificar == 'S' ) {
        $preaviso_sms = 'S';
    } else {
        $preaviso_sms = 'N';
    }

    if ( $tipo_aviso == 'SMS' && $reparto_notificar == 'S' ) {
        $reparto_sms = 'S';
    } else {
        $reparto_sms = 'N';
    }

    if ( $tipo_aviso == 'EMAIL' && $preaviso_notificar == 'S' ){
        $preaviso_email = 'S';
    } else {
        $preaviso_email = 'N';
    }

    if ( $tipo_aviso == 'EMAIL' && $reparto_notificar == 'S' ) {
        $reparto_email = 'S';
    } else {
        $reparto_email = 'N';
    }
    if ( $tipo_aviso == 'both' && $preaviso_notificar == 'S' ){
        $preaviso_email = 'S';
        $preaviso_sms = 'S';
    } else {
        $preaviso_email = 'N';
        $preaviso_sms = 'N';
    }
    if ( $tipo_aviso == 'both' && $reparto_notificar == 'S' ){
        $reparto_email = 'S';
        $reparto_sms = 'S';
    } else {
        $reparto_email = 'N';
        $reparto_sms = 'N';
    }
    $tipo_etiqueta           = $advanced_data[0]['tipo_etiqueta'];

    // Customer/Order Data

    $customer_country        = $order_data[0]['country'];
    $customercity            = seur_clean_data( $order_data[0]['city'] );
    $customerpostcode        = $order_data[0]['postcode'];

    $customer_weight         = $order_data[0]['weight'];

    if ( $post_weight ) {
        $customer_weight_kg = seur_always_kg( $weight );
    } else {
        $customer_weight_kg = seur_always_kg( $customer_weight );
    }

    $customer_first_name     = seur_clean_data( $order_data[0]['first_name'] );
    $customer_last_name      = seur_clean_data( $order_data[0]['last_name'] );
    $customer_company        = $order_data[0]['company'];
    $customer_address_1      = seur_clean_data( $order_data[0]['address_1'] );
    $customer_address_2      = seur_clean_data( $order_data[0]['address_2'] );
    $customer_email          = seur_clean_data( $order_data[0]['email'] );
    $customer_phone          = $order_data[0]['phone'];
    $customer_order_notes    = seur_clean_data( $order_data[0]['order_notes'] );
    $customer_order_total    = str_replace (",", ".", $order_data[0]['order_total'] );
    $order_pay_method        = seur_clean_data( $order_data[0]['order_pay_method'] );


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
        $seur_reembolso =  '<claveReembolso>f</claveReembolso><valorReembolso>' . $customer_order_total . '</valorReembolso>';
    } else {
        $seur_reembolso = '';
    }

    // SEUR service and Product

    $seur_service            = $product_service_seur[0]['service'];
    $seur_product            = $product_service_seur[0]['product'];

    if ( $customer_country == 'ES' || $customer_country == 'PT' || $customer_country == 'AD') {

        // shipping is to ES, PT or AD, let's check customer data

        $shipping_class = 0;
        $data           = array( 0 => $cit_user, $cit_contra, $customercity, $customerpostcode );
        $fran           = SeurCheckCity( $data );

        if ( ! $fran ) {

            //echo "<br>Codigo Postal y Poblacion no se han encontrado en Nomenclator de SEUR.<br>Consulte Nomenclator y ajuste Poblacion y Postal.<br></font>";
            //echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . $postal ." - " . $poblacion;
            //echo "</div>";

            return 'error 1';
            } else { // city and postcode exist
                    if ( $fran == '74' || $fran == '77' || $fran == '56' || $fran == '35' || $fran == '38' || $fran == '52' || $fran == '60' || $fran == '70' ) $shipping_class = 2;
                }
        } else { // shipping is not to ES, PT or AD
            $shipping_class = 1;
    }

    /*****************************************************/
    /**** Temp data maybe changed in the next release ****/
    /*****************************************************/

    $seur_weight_by_label = ( $customer_weight_kg / $numpackages );

    /*if ( $seur_weight_by_label < 1)//1kg
            {
                $seur_weight_by_label = 1;
            }*/
    $portes                  = 'F';

    if ( $shipping_class == 0 && date( 'l' ) == 'Friday'){

        if( ( $customer_country == 'ES' || $customer_country == 'AD' || $customer_country == 'PT' ) && ( $seur_service == '3' || $seur_service == '9' ) ){

                $seur_saturday_shipping = '<entrega_sabado>S</entrega_sabado>';

            } else {

                $seur_saturday_shipping = '';

            }
        } else {

            $seur_saturday_shipping = '';
        }

    /*****************************************************/
    /** END Temp data maybe changed in the next release **/
    /*****************************************************/

    if ( $tipo_aviso == 'SMS' && $preaviso_notificar == 'S' ) {
    $preaviso_sms = 'S';
    } else {
        $preaviso_sms = 'N';
    }

    if ( $tipo_aviso == 'SMS' && $reparto_notificar == 'S' ) {
        $reparto_sms = 'S';
    } else {
        $reparto_sms = 'N';
    }

    if ( $tipo_aviso == 'EMAIL' && $preaviso_notificar == 'S' ){
        $preaviso_email = 'S';
    } else {
        $preaviso_email = 'N';
    }

    if ( $tipo_aviso == 'EMAIL' && $reparto_notificar == 'S' ) {
        $reparto_email = 'S';
    } else {
        $reparto_email = 'N';
    }

    if ( $preaviso_notificar == 'S' ){

        $preaviso_notificar = '<test_preaviso>S</test_preaviso>';

    } else {

        $preaviso_notificar = '<test_preaviso>N</test_preaviso>';

    }

    if ( $reparto_notificar == 'S' ){

        $reparto_notificar = '<test_reparto>S</test_reparto>';

    } else {

        $reparto_notificar = '<test_reparto>N</test_reparto>';

    }

    if ( $preaviso_sms == 'S' || $reparto_sms == 'S' ){

        $seur_sms = '<test_sms>S</test_sms>';

    } else {

        $seur_sms = '<test_sms>N</test_sms>';

    }

    if ( $preaviso_email == 'S' || $reparto_email == 'S' ){

        $seur_email = '<test_email>S</test_email>';

    } else {

        $seur_email = '<test_email>N</test_email>';

    }

    if ( ( $mobile_shipping || $mobile_billing ) ){

        if ( $mobile_shipping ) {
            $seur_sms_mobile = '<sms_consignatario>' . $mobile_shipping . '</sms_consignatario>';
        } else {
            $seur_sms_mobile = '<sms_consignatario>' . $mobile_billing . '</sms_consignatario>';
        }
    } else {

        $seur_sms = '<test_sms>N</test_sms>';
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

    $Encoding = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $DatosEnvioInicio = $Encoding . '<root><exp>';
    $DatosEnvioFin = '</exp></root>';

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

    $Datasend = $DatosEnvioInicio . $complete_xml . $DatosEnvioFin;

    if ( $tipo_etiqueta == 'PDF' ) {

    $params = array(
        'in0' => $cit_user,
        'in1' => $cit_contra,
        'in2' => $Datasend,
        'in3' => "seurwoocommerce.xml",
        'in4' => $nif,
        'in5' => $franquicia,
        'in6' => '-1',
        'in7' => "wooseuroficial"
        );

        $url = 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl';
        if ( ! seur_check_url_exists( $url ) ) die( __('We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );

        //pedimos las etiquetas
        $sc_options = array(
                        'connection_timeout' => 60
                        );

        $soap_client = new SoapClient('http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options );
        $response    = $soap_client->impresionIntegracionPDFConECBWS($params);

        //echo $response->out->mensaje;

        // var_dump($respuesta);
        // echo htmlspecialchars($DatosEnvio);

        if ( $response->out->mensaje == 'OK' ) {

            $pdf = base64_decode( $response->out->PDF );
            $seur_pdf_label ='label_order_id_' . $order_id . '_' . $date . '.pdf';
            $seur_label_type = 'pdf';

            $upload_path    = $upload_dir . '/' . $seur_pdf_label;
            $url_to_label   = $upload_url . '/' . $seur_pdf_label;

            file_put_contents( $upload_path, $pdf );

            $labelid = wp_insert_post(
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
                               'ID'           => $labelid,
                               'post_title'   => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
                            );
            wp_update_post( $update_post );

            add_post_meta( $labelid,  '_seur_shipping_id_number',               $order_id_seur,                                   true );
            add_post_meta( $order_id, '_seur_shipping_id_number',               $order_id_seur,                                   true );
            add_post_meta( $order_id, '_seur_label_id_number',                  $labelid,                                         true );
            add_post_meta( $labelid,  '_seur_shipping_method',                  $seur_shipping_method,                            true );
            add_post_meta( $labelid,  '_seur_shipping_weight',                  $customer_weight_kg,                              true );
            add_post_meta( $labelid,  '_seur_shipping_packages',                $numpackages,                                     true );
            add_post_meta( $labelid,  '_seur_shipping_order_id',                $order_id,                                        true );
            add_post_meta( $labelid,  '_seur_shipping_order_customer_comments', $customer_order_notes,                            true );
            add_post_meta( $labelid,  '_seur_shipping_order_label_file_name',   $seur_pdf_label,                                  true );
            add_post_meta( $labelid,  '_seur_shipping_order_label_path_name',   $upload_path,                                     true );
            add_post_meta( $labelid,  '_seur_shipping_order_label_url_name',    $url_to_label,                                    true );
            add_post_meta( $order_id, '_seur_shipping_order_label_url_name',    $url_to_label,                                    true );
            add_post_meta( $labelid,  '_seur_label_customer_name',              $customer_first_name . ' ' . $customer_last_name, true );
            add_post_meta( $labelid,  '_seur_label_type',                       $seur_label_type,                                 true );

            if ( $labelid ) {

                $result  = true;
                $message = 'OK';
                $label   = array(
                            'result',
                            'labelID',
                            'message'
                        );
                $has_label = array(
                        $result,
                        $labelid,
                        $message
                );

                $seur_label[] = array_combine( $label, $has_label );

                return $seur_label;

            } else {

                $result  = false;
                $message = $response->out->mensaje;
                $label   = array(
                            'result',
                            'labelID',
                            'message'
                        );
                $has_label = array(
                        $result,
                        $labelid,
                        $message
                );

                $seur_label[] = array_combine( $label, $has_label );

                return $seur_label;

              }



        } else {
            $message = $response->out->mensaje;
            $result  = false;
            $labelid = false;
            $label   = array(
                        'result',
                        'labelID',
                        'message'
                    );
            $has_label = array(
                    $result,
                    $labelid,
                    $message
            );

            $seur_label[] = array_combine( $label, $has_label );

            return $seur_label;
            }
        } else {

            $params = array(
                'in0' => $cit_user,
                'in1' => $cit_contra,
                'in2' => "ZEBRA",
                'in3' => "LP2844-Z",
                'in4' => "2C",
                'in5' => $Datasend,
                'in6' => "seurwoocommerce.xml",
                'in7' => $nif,
                'in8' => $franquicia,
                'in9' => '-1',
                'in10'=> "wooseuroficial"
                );

            $sc_options = array(
                        'connection_timeout' => 60
                        );

            $url = 'http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl';
            if ( ! seur_check_url_exists( $url ) ) die( __('We&apos;re sorry, SEUR API is down. Please try again in few minutes', 'seur' ) );

            $soap_client = new SoapClient('http://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options );
            $respuesta   = $soap_client->impresionIntegracionConECBWS($params);

            if ( $respuesta->out->mensaje == 'OK' ){

                $txtlabel = $respuesta->out->traza;
                $seur_txt_label ='label_order_id_' . $order_id . '_' . $date . '.txt';
                $seur_label_type = 'termica';

                $upload_path    = $upload_dir . '/' . $seur_txt_label;
                $url_to_label   = $upload_url . '/' . $seur_txt_label;

                file_put_contents( $upload_path, $txtlabel );

                $labelid = wp_insert_post(
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
                                 'ID'           => $labelid,
                                 'post_title'   => 'Label ' . $order_id_seur . '( ID #' . $labelid . ' )',
                              );
                wp_update_post( $update_post );

                add_post_meta( $labelid,  '_seur_shipping_id_number',               $order_id_seur,                                   true );
                add_post_meta( $order_id, '_seur_shipping_id_number',               $order_id_seur,                                   true );
                add_post_meta( $order_id, '_seur_label_id_number',                  $labelid,                                         true );
                add_post_meta( $labelid,  '_seur_shipping_method',                  $seur_shipping_method,                            true );
                add_post_meta( $labelid,  '_seur_shipping_weight',                  $customer_weight_kg,                              true );
                add_post_meta( $labelid,  '_seur_shipping_packages',                $numpackages,                                     true );
                add_post_meta( $labelid,  '_seur_shipping_order_id',                $order_id,                                        true );
                add_post_meta( $labelid,  '_seur_shipping_order_customer_comments', $customer_order_notes,                            true );
                add_post_meta( $labelid,  '_seur_shipping_order_label_file_name',   $seur_txt_label,                                  true );
                add_post_meta( $labelid,  '_seur_shipping_order_label_path_name',   $upload_path,                                     true );
                add_post_meta( $labelid,  '_seur_shipping_order_label_url_name',    $url_to_label,                                    true );
                add_post_meta( $order_id, '_seur_shipping_order_label_url_name',    $url_to_label,                                    true );
                add_post_meta( $labelid,  '_seur_label_customer_name',              $customer_first_name . ' ' . $customer_last_name, true );
                add_post_meta( $labelid,  '_seur_label_type',                       $seur_label_type,                                 true );

                if ( $labelid ) {

                    $result  = true;
                    $message = 'OK';
                    $label   = array(
                                'result',
                                'labelID',
                                'message'
                            );
                    $has_label = array(
                            $result,
                            $labelid,
                            $message
                    );

                    $seur_label[] = array_combine( $label, $has_label );

                    return $seur_label;

                } else {

                    $result  = false;
                    $message = $respuesta->out->mensaje;
                    $label   = array(
                                'result',
                                'labelID',
                                'message'
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
                echo '<div class="notice notice notice-error">' . $message . '</div>';

            }
    }
}