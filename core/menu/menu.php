<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-settings.php'				);
include_once( SEUR_PLUGIN_PATH . 'core/pages/about.php'						);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-nomenclator.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-rates.php'				);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-products-services.php'	);
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-manifest.php'		        );
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-pickup.php'		        );
include_once( SEUR_PLUGIN_PATH . 'core/pages/seur-get-labels.php'		    );
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-add-form.php'		);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-create-rate.php'	);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-delete.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-update.php'			);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-edit-form.php'		);
include_once( SEUR_PLUGIN_PATH . 'core/pages/rates/seur-country-state-process.php'	);
include_once( SEUR_PLUGIN_PATH . 'core/help/seur-help-tabs.php'	);
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
	global $seurrates, $seurmanifest, $seurnomenclator, $seurproductsservices, $seurconfig, $seurabout, $seuraddform, $seurcreaterate, $seurdeleterate, $seurupdatecustomrate, $seureditcustomrate, $seuraddlabelwoocommerce, $seur_get_labels;

	$nif				= get_option( 'seur_nif_field' );
	$page_title			=	__( 'SEUR', 'seur-oficial');
	$menu_title			=	'SEUR';
	$capability			=	'manage_options';
	$menu_slug			=	'seur';
	$function			=	'seur_settings';
	$icon_url			=	NULL;
	$position			=	NULL;

	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	if( $nif ){
		$seurconfig				= add_submenu_page( $menu_slug, __( 'Settings', 			 'seur-oficial' ), __( 'Settings', 				'seur-oficial' ), $capability, $menu_slug );
		$seurrates				= add_submenu_page( $menu_slug, __( 'Rates',				 'seur-oficial' ), __( 'Rates', 					'seur-oficial' ), $capability, 'seur_rates_prices', 		 'seur_rates_prices' );
		$seurmanifest			= add_submenu_page( $menu_slug, __( 'Manifest', 			 'seur-oficial' ), __( 'Manifest', 				'seur-oficial' ), $capability, 'seur_donwload_data', 		 'seur_donwload_data' );
		$seurnomenclator		= add_submenu_page( $menu_slug, __( 'Nomenclator', 			 'seur-oficial' ), __( 'Nomenclator', 				'seur-oficial' ), $capability, 'seur_search_nomenclator', 	 'seur_search_nomenclator' );
		$seurproductsservices	= add_submenu_page( $menu_slug, __( 'Products & Services', 	 'seur-oficial' ), __( 'Products & Services', 		'seur-oficial' ), $capability, 'seur_products_services', 	 'seur_products_services' );
		$seurabout				= add_submenu_page( $menu_slug, __( 'About', 				 'seur-oficial' ), __( 'About', 					'seur-oficial' ), $capability, 'seur_about_page', 			  'seur_about_page' );
		$seuraddform			= add_submenu_page( $menu_slug, __( 'Add Form', 			 'seur-oficial' ), __( 'Add Form', 				'seur-oficial' ), $capability, 'seur_add_form', 			  'seur_add_form' );
		$seurcreaterate			= add_submenu_page( $menu_slug, __( 'Create Rate', 			 'seur-oficial' ), __( 'Create Rate', 				'seur-oficial' ), $capability, 'seur_create_custom_rate', 	  'seur_create_custom_rate' );
		$seurdeleterate			= add_submenu_page( $menu_slug, __( 'Delete Rate', 			 'seur-oficial' ), __( 'Delete Rate', 				'seur-oficial' ), $capability, 'seur_delete_rate', 		  'seur_delete_rate' );
		$seurupdatecustomrate	= add_submenu_page( $menu_slug, __( 'Update Rate', 			 'seur-oficial' ), __( 'Update Rate', 				'seur-oficial' ), $capability, 'seur_update_custom_rate', 	  'seur_update_custom_rate' );
		$seureditcustomrate	    = add_submenu_page( $menu_slug, __( 'Edit Rate', 			 'seur-oficial' ), __( 'Edit Rate', 				'seur-oficial' ), $capability, 'seur_edit_rate', 			  'seur_edit_rate' );
		$seurcountrystateprocess= add_submenu_page( $menu_slug, __( 'Process Country State', 'seur-oficial' ), __( 'Process Country State',	'seur-oficial' ), $capability, 'seur_country_state_process', 'seur_country_state_process' );
		if ( defined( 'SEUR_WOOCOMMERCE_PART' ) ) {
			$seuraddlabelwoocommerce	= add_submenu_page( $menu_slug, __( 'Get Label', 'seur-oficial' ), __( 'Get Label', 'seur-oficial' ), $capability, 'seur_process_label_woocommerce', 'seur_process_label_woocommerce' );
		}
		$seurlabelslist 		= add_submenu_page( $menu_slug, __( 'Labels', 'seur-oficial' ), __( 'Labels',	'seur-oficial' ), 'edit_posts', 'edit.php?post_type=seur_labels');
		$seur_pickup	    	= add_submenu_page( $menu_slug, __( 'Pickup', 			 	'seur-oficial' ), __( 'Pickup', 					'seur-oficial' ), $capability, 'seur_pickup', 	  'seur_pickup' );
		$seur_get_labels	    = add_submenu_page( $menu_slug, __( 'Get labels from order', 'seur-oficial' ), __( 'Get labels from order', 	'seur-oficial' ), $capability, 'seur_get_labels_from_order', 	  'seur_get_labels_from_order' );

		//add_action for add scripts to different screens

		add_action("admin_print_scripts-$seurrates",			'seur_custom_rates_load_js'		);
		add_action("admin_print_scripts-$seurrates",			'seur_settings_load_js'			);
		add_action("admin_print_scripts-$seuraddform",			'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seureditcustomrate",	'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seurcountrystateprocess",	'seur_auto_country_state_js'	);

		add_action("admin_print_scripts-$seurcountrystateprocess",			'seur_select2_load_js'          );
		add_action("admin_print_scripts-$seurcountrystateprocess",			'seur_select2_custom_load_js'   );

		add_action("admin_print_scripts-$seuraddform",			'seur_select2_load_js'          );
		add_action("admin_print_scripts-$seuraddform",			'seur_select2_custom_load_js'   );


		add_action("admin_print_scripts-$seurmanifest",			'seur_datepicker_js'			);
		add_action("admin_print_scripts-$seureditcustomrate",	'seur_auto_country_state_js'	);
		add_action("admin_print_scripts-$seurconfig",			'seur_settings_load_js'			);
		add_action("admin_print_scripts-$seurproductsservices",			'seur_settings_load_js'			);

		// admin help tabs

		add_action( 'load-' . $seurconfig, 'seur_settings_add_help_tab');
		add_action( 'load-' . $seurrates, 'seur_rates_add_help_tab');
		add_action( 'load-' . $seurmanifest, 'seur_manifests_add_help_tab');
		add_action( 'load-' . $seurnomenclator, 'seur_nomenclator_add_help_tab');
		add_action( 'load-' . $seurproductsservices, 'seur_product_service_add_help_tab');
		add_action( 'load-' . $seur_pickup, 'seur_pickup_add_help_tab');


	} else {
		$seurconfig				= add_submenu_page( $menu_slug, __( 'Settings', 'seur-oficial' ), __( 'Settings', 'seur-oficial' ), 'manage_options', $menu_slug );
		add_action("admin_print_scripts-$seurconfig", 'seur_settings_load_js');
		$seurabout				= add_submenu_page( $menu_slug, __( 'About', 'seur-oficial' ), __( 'About', 'seur-oficial' ), 'manage_options', 'seur_about_page', 'seur_about_page' );

		// admin help tabs

		add_action( 'load-' . $seurconfig, 'seur_settings_add_help_tab');
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