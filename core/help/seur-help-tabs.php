<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_settings_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_user_settings_help_tab',
        'title'	=> __('Users Settings'),
        'content'	=> '<p>' . __( 'Help about User SEUR Settings.' ) . '</p>',
    ) );
    $screen->add_help_tab( array(
        'id'	=> 'seur_advanced_settings_help_tab',
        'title'	=> __('Advanced Settings'),
        'content'	=> '<p>' . __( 'Help about Advanced SEUR Settings.' ) . '</p>',
    ) );
}

function seur_rates_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_calculate_rates_help_tab',
        'title'	=> __('Calculate Rates'),
        'content'	=> '<p>' . __( 'Help about calculate Rates.' ) . '</p>',
    ) );
    $screen->add_help_tab( array(
        'id'	=> 'seur_custom_rates_help_tab',
        'title'	=> __('Custom Rates'),
        'content'	=> '<p>' . __( 'Help about Custom Rates.' ) . '</p>',
    ) );
}

function seur_manifests_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_manifest_help_tab',
        'title'	=> __('Manifest'),
        'content'	=> '<p>' . __( 'Help about Manifest.' ) . '</p>',
    ) );
}

function seur_nomenclator_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_nomenclator_help_tab',
        'title'	=> __('Nomenclator'),
        'content'	=> '<p>' . __( 'Help about Nomenclator.' ) . '</p>',
    ) );
}

function seur_product_service_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_product_service_help_tab',
        'title'	=> __('Product/Service'),
        'content'	=> '<p>' . __( 'Help about Product/Service.' ) . '</p>',
    ) );
}

function seur_pickup_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_pickupe_help_tab',
        'title'	=> __('Pickup'),
        'content'	=> '<p>' . __( 'Help about Pickup.' ) . '</p>',
    ) );
}

add_action('admin_head', 'seur_label_list_add_help_tab');
function seur_label_list_add_help_tab () {
	global $post_ID;
	$screen = get_current_screen();

	if ( isset($_GET['post_type']) ) {
		$post_type = $_GET['post_type'];
		} else {
			$post_type = get_post_type( $post_ID );
		}

    if ( $post_type == 'seur_labels' ) {

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_label_help_tab',
        'title'	=> __('Label List'),
        'content'	=> '<p>' . __( 'Help about Label List.' ) . '</p>',
    ) );
    }
}

add_action('admin_head', 'seur_woocommercel_order_list_add_help_tab');
function seur_woocommercel_order_list_add_help_tab () {
	global $post_ID;
	$screen = get_current_screen();

	if ( isset($_GET['post_type']) ) {
		$post_type = $_GET['post_type'];
		} else {
			$post_type = get_post_type( $post_ID );
		}

    if ( $post_type == 'shop_order' ) {

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_woocommerce_order_help_tab',
        'title'	=> __('SEUR Options'),
        'content'	=> '<p>' . __( 'Help about WooCommerce SEUR Options.' ) . '</p>',
    ) );
    }
}