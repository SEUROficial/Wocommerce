<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-settings.php'				);
include_once( SEUR_PLUGIN_PATH . 'core/pages/about.php'						);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-nomenclator.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-rates.php'				);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-products-services.php'	);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-manifest.php'		        );
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-pickup.php'		        );
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-add-form.php'		);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-create-rate.php'	);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-delete.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-update.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-edit-form.php'		);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-country-state-process.php'	);
if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ) {
	include_once( SEUR_PLUGIN_PATH . 'core/woocommerce/includes/pages/seur-process-label.php');
	}

function seur_load_custom_icon_styles() {
	wp_register_style(	'seur_dashicons', SEUR_PLUGIN_URL . 'assets/css/menu.css', false, SEUR_OFFICIAL_VERSION );
	wp_enqueue_style(	'seur_dashicons' );
	}
add_action( 'admin_enqueue_scripts', 'seur_load_custom_icon_styles' );

	// Adding custom menu for WordPress

function seur_menu() {
	global $seurrates, $seurmanifest, $seurnomenclator, $seurproductsservices, $seurconfig, $seurabout, $seuraddform, $seurcreaterate, $seurdeleterate, $seurupdatecustomrate, $seureditcustomrate, $seuraddlabelwoocommerce;

	$nif				= get_option( 'seur_nif_field' );
	$page_title			=	__( 'SEUR', SEUR_TEXTDOMAIN);
	$menu_title			=	'SEUR';
	$capability			=	'manage_options';
	$menu_slug			=	'seur';
	$function			=	'seur_settings';
	$icon_url			=	NULL;
	$position			=	NULL;

	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	if( $nif ){
		$seurconfig				= add_submenu_page( $menu_slug, __( 'Settings', 			 SEUR_TEXTDOMAIN ), __( 'Settings', 				SEUR_TEXTDOMAIN ), $capability, $menu_slug );
		$seurrates				= add_submenu_page( $menu_slug, __( 'Rates',				 SEUR_TEXTDOMAIN ), __( 'Rates', 					SEUR_TEXTDOMAIN ), $capability, 'seur_rates_prices', 		 'seur_rates_prices' );
		$seurmanifest			= add_submenu_page( $menu_slug, __( 'Manifest', 			 SEUR_TEXTDOMAIN ), __( 'Manifest', 				SEUR_TEXTDOMAIN ), $capability, 'seur_donwload_data', 		 'seur_donwload_data' );
		$seurnomenclator		= add_submenu_page( $menu_slug, __( 'Nomenclator', 			 SEUR_TEXTDOMAIN ), __( 'Nomenclator', 				SEUR_TEXTDOMAIN ), $capability, 'seur_search_nomenclator', 	 'seur_search_nomenclator' );
		$seurproductsservices	= add_submenu_page( $menu_slug, __( 'Products & Services', 	 SEUR_TEXTDOMAIN ), __( 'Products & Services', 		SEUR_TEXTDOMAIN ), $capability, 'seur_products_services', 	 'seur_products_services' );
		$seurabout				= add_submenu_page( $menu_slug, __( 'About', 				 SEUR_TEXTDOMAIN ), __( 'About', 					SEUR_TEXTDOMAIN ), $capability, 'seur_about_page', 			  'seur_about_page' );
		$seuraddform			= add_submenu_page( $menu_slug, __( 'Add Form', 			 SEUR_TEXTDOMAIN ), __( 'Add Form', 				SEUR_TEXTDOMAIN ), $capability, 'seur_add_form', 			  'seur_add_form' );
		$seurcreaterate			= add_submenu_page( $menu_slug, __( 'Create Rate', 			 SEUR_TEXTDOMAIN ), __( 'Create Rate', 				SEUR_TEXTDOMAIN ), $capability, 'seur_create_custom_rate', 	  'seur_create_custom_rate' );
		$seurdeleterate			= add_submenu_page( $menu_slug, __( 'Delete Rate', 			 SEUR_TEXTDOMAIN ), __( 'Delete Rate', 				SEUR_TEXTDOMAIN ), $capability, 'seur_delete_rate', 		  'seur_delete_rate' );
		$seurupdatecustomrate	= add_submenu_page( $menu_slug, __( 'Update Rate', 			 SEUR_TEXTDOMAIN ), __( 'Update Rate', 				SEUR_TEXTDOMAIN ), $capability, 'seur_update_custom_rate', 	  'seur_update_custom_rate' );
		$seureditcustomrate	    = add_submenu_page( $menu_slug, __( 'Edit Rate', 			 SEUR_TEXTDOMAIN ), __( 'Edit Rate', 				SEUR_TEXTDOMAIN ), $capability, 'seur_edit_rate', 			  'seur_edit_rate' );
		$seurcountrystateprocess= add_submenu_page( $menu_slug, __( 'Process Country State', SEUR_TEXTDOMAIN ), __( 'Process Country State',	SEUR_TEXTDOMAIN ), $capability, 'seur_country_state_process', 'seur_country_state_process' );
		if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ) {
			$seuraddlabelwoocommerce	= add_submenu_page( $menu_slug, __( 'Get Label', SEUR_TEXTDOMAIN ), __( 'Get Label', SEUR_TEXTDOMAIN ), $capability, 'seur_process_label_woocommerce', 'seur_process_label_woocommerce' );
		}
		$seurlabelslist 		= add_submenu_page( $menu_slug, __( 'Labels', SEUR_TEXTDOMAIN ), __( 'Labels',	SEUR_TEXTDOMAIN ), 'edit_posts', 'edit.php?post_type=seur_labels');
		$seur_pickup	    	= add_submenu_page( $menu_slug, __( 'Pickup', 			 	SEUR_TEXTDOMAIN ), __( 'Pickup', 					SEUR_TEXTDOMAIN ), $capability, 'seur_pickup', 	  'seur_pickup' );

		//add_action for add scripts to different screens

		add_action("admin_print_scripts-$seurrates",			'seur_custom_rates_load_js'		);
		add_action("admin_print_scripts-$seurrates",			'seur_settings_load_js'			);
		add_action("admin_print_scripts-$seuraddform",			'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seureditcustomrate",	'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seuraddform",			'seur_select2_load_js'          );
		add_action("admin_print_scripts-$seuraddform",			'seur_select2_custom_load_js'   );
		add_action("admin_print_scripts-$seurmanifest",			'seur_datepicker_js'			);

		add_action("admin_print_scripts-$seureditcustomrate",	'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seurconfig",			'seur_settings_load_js'			);
	} else {
		$seurconfig				= add_submenu_page( $menu_slug, __( 'Settings', SEUR_TEXTDOMAIN ), __( 'Settings', SEUR_TEXTDOMAIN ), 'manage_options', $menu_slug );
		add_action("admin_print_scripts-$seurconfig", 'seur_settings_load_js');
		$seurabout				= add_submenu_page( $menu_slug, __( 'About', SEUR_TEXTDOMAIN ), __( 'About', SEUR_TEXTDOMAIN ), 'manage_options', 'seur_about_page', 'seur_about_page' );
	}
}
add_action('admin_menu', 'seur_menu');

function seur_menu_hierarchy_correction( $parent_file ) {

	global $current_screen, $parent_file, $self;

	$current = $current_screen->post_type;


	if ( 'seur_labels' == $current_screen->post_type  ) {
		// Do something in the edit screen of this post type
		$parent_file = 'seur';
		//$parent_file = 'seur_settings';
	}

	// return $parent_file;
	return $parent_file;

}
add_filter( 'parent_file', 'seur_menu_hierarchy_correction' );