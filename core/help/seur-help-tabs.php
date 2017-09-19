<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_settings_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_user_settings_help_tab',
        'title'	=> __('Users Settings', 'seur' ),
        'content'	=> '<p>' . __( 'Desde esta pantalla podrás configurar todos los datos identificativos de tu negocio.', 'seur'  ) . '</p>
					    <p>' . __( 'Si tienes alguna duda o necesitas añadir algún dato más puedes ponerte en contacto con tu Asesor Comercial para que te ayude.', 'seur'  ) . '</p>',
    ) );
    $screen->add_help_tab( array(
        'id'	=> 'seur_advanced_settings_help_tab',
        'title'	=> __('Advanced Settings', 'seur' ),
        'content'	=> '<p>' . __( 'Desde esta pantalla podrás puedes configurar opciones avanzadas como el tipo de notificación a tus clientes, tipo de etiqueta de transporte generada y datos el del control aduanero e internacional.', 'seur'  ) . '</p>',
    ) );
}

function seur_rates_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_calculate_rates_help_tab',
        'title'	=> __('Calculate Rates', 'seur' ),
        'content'	=> '<p>' . __( 'Calcula las tarifa que tienes acordada con SEUR para un destino en concreto.', 'seur'  ) . '</p>
					    <p>' . __( 'Simplemente especifica el CP, población, país, número de bultos y número de kilos y te facilitaremos la tarifa correspondiente que tienes acordada', 'seur'  ) . '</p>',
    ) );
    $screen->add_help_tab( array(
        'id'	=> 'seur_custom_rates_help_tab',
        'title'	=> __('Custom Rates', 'seur' ),
        'content'	=> '<p>' . __( 'Con el dato de la tarifa asociada de SEUR del menú Calculate Rates, ya tienes una mejor idea de qué tarifas plantear a tus clientes. En esta pantalla, podrás puedes crear  las tarifas que tus clientes pueden seleccionar para realizar sus envíos.', 'seur'  ) . '</p>
					    <p>' . __( 'Para crear una tarifa pulsa en Add Custom Rate, selecciona el tipo de Servicio / Producto, el País, Provincia y si lo deseas CP (pon * para que aplique cualquiera). A continuación indica en que qué rango de precios de carrito debe aplicar la tarifa y la cantidad en euros que deberán abonar tus clientes en el campo Rate Price.', 'seur'  ) . '</p>
					    <p>' . __( 'Recuerda que puedes editar o eliminar tarifas previamente creadas desde la pantalla de principal de Custom Rates.', 'seur'  ) . '</p>',
    ) );
}

function seur_manifests_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_manifest_help_tab',
        'title'	=> __('Manifest'),
        'content'	=> '<p>' . __( 'Descarga el listado de bultos con el contenido de envíos comunicados a SEUR desde la fecha que tú elijas.', 'seur'  ) . '</p>
					    <p>' . __( 'Si debes entregarlo al transportista, recuerda imprimir dos copias: una para ti y otra para él', 'seur'  ) . '</p>',
    ) );
}

function seur_nomenclator_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_nomenclator_help_tab',
        'title'	=> __('Nomenclator', 'seur' ),
        'content'	=> '<p>' . __( 'Consulta los CP y Poblaciones de la base de datos de SEUR.', 'seur'  ) . '</p>',
    ) );
}

function seur_product_service_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_product_service_help_tab',
        'title'	=> __('Product/Service', 'seur' ),
        'content'	=> '<p>' . __( 'Consulta las combinaciones de Servicios y Productos de SEUR disponibles en WooCommerce', 'seur'  ) . '</p>',
    ) );
}

function seur_pickup_add_help_tab () {
    $screen = get_current_screen();

    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'seur_pickupe_help_tab',
        'title'	=> __('Collection', 'seur' ),
        'content'	=> '<p>' . __( 'Solicita que pasemos a recoger siempre que lo necesites.', 'seur'  ) . '</p>
					    <p>' . __( 'Recuerda especificarnos el número de bultos y kilos que nos vas a entregar, y al seleccionar el horario, danos un margen de dos horas hasta el inicio de la recogida.', 'seur'  ) . '</p>
					    <p>' . __( 'Por ejemplo si son las 15:00 podrás solicitar que pasemos a recoger de 17:00 a 19:00', 'seur'  ) . '</p>',
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
        'content'	=> '<p>' . __( 'Desde esta pantalla puedes recuperar las etiquetas de los pedidos previamente etiquetados en el menú WooCommerce > Pedidos.', 'seur'  ) . '</p>',
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
        'title'	=> __('SEUR Options', 'seur' ),
        'content'	=> '<p>' . __( 'Help about WooCommerce SEUR Options.', 'seur'  ) . '</p>',
    ) );
    }
}