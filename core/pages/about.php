<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function seur_about_page(){ ?>
		<div class="wrap">
	<?php

		/*echo '<pre>';
		var_dump( seur_get_countries() );
		echo '</pre>';
*/
		echo '<pre>';
		$country = 'ES';
		//var_dump( seur_get_countries_states( $country ) );
		var_dump( seur_get_user_settings() );
		echo '</pre>';

	$seursetting = array();
	$seursetting = seur_get_user_settings();

	$post_id		= '29';
	$order_data	      = seur_get_order_data( $post_id );
	$user_data	      = seur_get_user_settings();
	$advanced_data	  = seur_get_advanced_settings();

	$customer_country = $order_data[0]['country'];
	$cit_user	      = $user_data[0]['cit_usuario'];
	$cit_pass		  = $user_data[0]['cit_codigo'];
	$customercity     = $order_data[0]['city'];
	$customerpostcode = $order_data[0]['postcode'];

	echo 'EL DNI de ' . $seursetting[0]['empresa'] . ' es ' . $seursetting[0]['nif'] . '<br />';
	echo 'EL directorio de subida es: ' . seur_upload_dir();

	echo '<pre>';
		//var_dump( seur_get_countries_states( $country ) );
	var_dump( seur_get_advanced_settings() );
	echo '</pre>';

	echo '<pre>';
	var_dump( seur_get_order_data( '29' ) );
	echo '</pre>';
	?>
		</div>
	<?php
		get_order_details($post_id);


		echo '<pre>';
		//var_dump( seur_get_countries_states( $country ) );
	var_dump( seur_get_all_shipping_products() );
	echo '</pre>';

	echo 'El ID de PARTICULARES 24H ESTANDAR debería ser ID 7 = ' . seur_return_shipping_product_id( 'PARTICULARES 24H ESTANDAR' );

	echo '<pre>';
	var_dump( seur_get_service_product_shipping_product( '7' ) );
	echo '</pre>';

	echo seur_get_shipping_method( '18' ) . '<br />';

	//$customer_weight = '6';
	$numpackages = '2';

	echo $seur_weight_by_label;

	$order = new WC_Order( '18' );

    //$the_order = wc_get_order( $order_id );

    $shipping_method = @array_shift($order->get_items( 'shipping' ));
    $shipping_method_name = $shipping_method['name'];

    echo $shipping_method_name;



	$order_id = '65';
	$product_id = '65';
	$seur_pdf_label          = '';
    $TotalBultos             = '';
    $pdf                     = '';
    $ADUANASSW				 = false;
    $INTERNACIONALSW		 = false;
    $B2CSW					 = false;
    $seur_saturday_shipping	 = false;
    $upload_dir              = seur_upload_dir();
    $upload_url              = seur_upload_url();
    $order_data              = array();
    $user_data               = array();
    $advanced_data           = array();
    $product_service_seur    = array();
    $seur_shipping_method    = seur_get_shipping_method( $order_id );
    $seur_shipping_method_id = seur_return_shipping_product_id( $seur_shipping_method );

    // All needed Data return Array

    $order_data              = seur_get_order_data( $order_id );
    $user_data               = seur_get_user_settings();
    $advanced_data           = seur_get_advanced_settings();
    $product_service_seur    = seur_get_service_product_shipping_product( $seur_shipping_method_id );
    $product_shipping		 = $product_service_seur[0]['product'];
    $service_shipping		 = $product_service_seur[0]['service'];

    // User settings

    $cit_pass                = $user_data[0]['cit_codigo'];
    $cit_user                = $user_data[0]['cit_usuario'];
    $cit_contra              = $user_data[0]['cit_contra'];
    $nif                     = $user_data[0]['nif'];
    $franquicia              = $user_data[0]['franquicia'];
    $ccc                     = $user_data[0]['ccc'];
    $usercom				 = $user_data[0]['seurcom_usuario'];
    $passcom				 = $user_data[0]['seurcom_contra'];

    echo '<br />' . $cit_pass . '<br />';
    echo $cit_user . '<br />';
    echo $cit_contra . '<br />';
    echo $nif . '<br />';
    echo $franquicia . '<br />';
    echo $ccc . '<br />';
    echo $usercom . '<br />';
    echo $passcom . '<br />';

    // Advanced User Settings

    $nal_producto            = $advanced_data[0]['nal_producto'];
    $nal_servicio            = $advanced_data[0]['nal_servicio'];
    $aduana_origen           = $advanced_data[0]['aduana_origen'];
    $aduana_destino          = $advanced_data[0]['aduana_destino'];
    $valor_declarado         = str_replace (",", ".", $advanced_data[0]['valor_declarado'] );
    $canarias_producto       = $advanced_data[0]['canarias_producto'];
    $canarias_servicio       = $advanced_data[0]['canarias_servicio'];
    $tipo_mercancia          = $advanced_data[0]['tipo_mercancia'];
    $internacional_producto  = $advanced_data[0]['internacional_producto'];
    $internacional_servicio  = $advanced_data[0]['internacional_servicio'];
    $id_mercancia            = $advanced_data[0]['id_mercancia'];
    $descripcion             = $advanced_data[0]['descripcion'];
    $preaviso_notificar      = $advanced_data[0]['preaviso_notificar'];
    $preaviso_sms            = $advanced_data[0]['preaviso_sms'];
    $preaviso_email          = $advanced_data[0]['preaviso_email'];
    $reparto_notificar       = $advanced_data[0]['reparto_notificar'];
    $reparto_sms             = $advanced_data[0]['reparto_sms'];
    $reparto_email           = $advanced_data[0]['reparto_email'];
    $tipo_etiqueta           = $advanced_data[0]['tipo_etiqueta'];

    // Customer/Order Data

    $customer_country        = $order_data[0]['country'];
    $customercity            = seur_clean_data( $order_data[0]['city'] );
    $customerpostcode        = $order_data[0]['postcode'];
    $customer_weight         = $order_data[0]['weight'];
    $customer_first_name     = seur_clean_data( $order_data[0]['first_name'] );
    $customer_last_name      = seur_clean_data( $order_data[0]['last_name'] );
    $customer_company        = $order_data[0]['company'];
    $customer_address_1      = seur_clean_data( $order_data[0]['address_1'] );
    $customer_address_2      = seur_clean_data( $order_data[0]['address_2'] );
    $customer_email          = seur_clean_data( $order_data[0]['email'] );
    $customer_phone          = $order_data[0]['phone'];
    $customer_order_notes    = seur_clean_data( $order_data[0]['order_notes'] );
    $customer_order_total    = $order_data[0]['order_total'];

    // SEUR service and Product

    $seur_service            = $product_service_seur[0]['service'];
    $seur_product            = $product_service_seur[0]['product'];

    if ( $customer_country == 'ES' || $customer_country == 'PT' || $customer_country == 'AD') {

        // shipping is to ES, PT or AD, let's check customer data

        $shipping_class = 0;
        $data           = array( 0 => $cit_user, $cit_pass, $customercity, $customerpostcode );
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

   $seur_weight_by_label    = ( $customer_weight / $numpackages );

    $gastosR                 = 'F';
    $portes                  = 'F';
    $valorreembolso          = '';

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

   if ( ! $fran ) {

        $INTERNACIONALSW = "<id_mercancia>". $id_mercancia . "</id_mercancia>
					        <descripcion_mercancia>" . $descripcion . "</descripcion_mercancia>
					        <codigo_pais_destino>" . $customer_country . "</codigo_pais_destino>";
        /********** lo dejo de momento como referencia para el siguiente código **/
        /**** $envio_producto = $_POST["internacional_producto_sw"]; ********/
        /**** $envio_servicio = $_POST["internacional_servicio_sw"];  *********/
       } else {

            // 74-CEUTA 77-ANDORRA 56-MELILLA
            if ( $fran == '74' || $fran == '77'  || $fran == '56' || $fran == '35' || $fran == '38' || $fran == '52' || $fran == '60' || $fran == '70' ) {

                        $ADUANASSW = "<tipo_mercancia>" . $tipo_mercancia . "</tipo_mercancia>
			                        <valor_declarado>" . $valor_declarado . "</valor_declarado>
			                        <aduana_origen>" . $aduana_origen . "</aduana_origen>
			                        <aduana_destino>" . $aduana_destino . "</aduana_destino>";

                        if ( $fran == '74' || $fran == '77'|| $fran == '56' ) {

                            $envio_producto = $nal_producto;
                            $envio_servicio = $nal_servicio;

                        } else {

                            $envio_producto = $canarias_producto;
                            $envio_servicio = $canarias_servicio;

                            }

                } else {

                    $envio_producto = $nal_producto;
                    $envio_servicio = $nal_servicio;
                }


        }

        $Encoding = "<?xml version='1.0' encoding='ISO-8859-1'?>";
        $DatosEnvioInicio = $Encoding . "<root><exp>";
        $DatosEnvioFin = "</exp></root>";
        $xml	 = '<bulto>
			            <ci>' . $cit_pass . '</ci>  <!--CLIENTE INTEGRADO  lo que recuperes-->
						<nif>' . $nif . '</nif> <!--NIF lo que recuperes-->
						<ccc>' . $ccc . '</ccc> <!--Código cuenta cliente (fijo)-->
						<servicio>' . $service_shipping . '</servicio> <!--31 B2C. Fijo para envíos nacionales 24h-->
						<producto>' . $product_shipping . '</producto> <!--2 Estándar. Fijo para envíos nacionales 24h-->
						<total_bultos>' . $numpackages . '</total_bultos>
						<total_kilos>' . $customer_weight . '</total_kilos>
						<pesoBulto>' . $seur_weight_by_label . '</pesoBulto>
						<observaciones>' . $customer_order_notes . '</observaciones>
						<referencia_expedicion>' . $order_id . '</referencia_expedicion>
						<clavePortes>' . $portes . '</clavePortes>' .
			            $ADUANASSW .
			            '<claveReembolso>' . $gastosR . '</claveReembolso> <!--Comision del reembolso. F pagados / D debidos-->
						<valorReembolso>' . $valorreembolso . '</valorReembolso>
						<nombre_consignatario>' . $customer_first_name . ' ' . $customer_last_name . '</nombre_consignatario>
						<direccion_consignatario>' . $customer_address_1 . ' ' . $customer_address_2 . '</direccion_consignatario>
						<tipoVia_consignatario>CL</tipoVia_consignatario>
						<tNumVia_consignatario>N</tNumVia_consignatario>
						<numVia_consignatario>.</numVia_consignatario>
						<escalera_consignatario></escalera_consignatario>
						<piso_consignatario></piso_consignatario>
						<puerta_consignatario></puerta_consignatario>
						<poblacion_consignatario>' . $customercity . '</poblacion_consignatario>
						<codPostal_consignatario>' . $customerpostcode . '</codPostal_consignatario>
						<pais_consignatario>' . $customer_country . '</pais_consignatario>
						<telefono_consignatario>' . $customer_phone . '</telefono_consignatario>
						<sms_consignatario>' . $customer_phone . '</sms_consignatario>  <!--Móvil para avisos SMS-->
						<email_consignatario>' . $customer_email . '</email_consignatario>
						<atencion_de>' . $customer_first_name . ' ' . $customer_last_name . '</atencion_de>
						<id_mercancia/> <!-- lo que recuperes-->
			            <test_preaviso>' . $preaviso_notificar . '</test_preaviso> <!--Enviar aviso preaviso? S / N, lo que recuperes-->
			            <test_reparto>' . $reparto_notificar  . '</test_reparto>
			            <test_email>' . $preaviso_email . '</test_email>
			            <test_sms>' . $preaviso_sms . '</test_sms>'
			            . $seur_saturday_shipping .
					'</bulto>';

        $numero_de_bultos = 1;

        while ( $numero_de_bultos <= $numpackages ) {

            $numero_de_bultos++;
            $complete_xml .= $xml;

        }
        $Datasend = $DatosEnvioInicio . $complete_xml . $DatosEnvioFin;

        echo '<pre>';
        echo $Datasend;
        echo '</pre>';


        $params = array(
            'in0'=> 'wsecomm3183',
            'in1'=> 'ws3183ecomm',
            'in2'=> $Datasend,
            'in3'=> 'prueba.xml',
            'in4'=> 'A61345849',
            'in5'=> '8',
            'in6'=> '-1',
            'in7'=> "woocommerce"
            );

			$sc_options = array(
							'connection_timeout' => 120
							);

            $soap_client   = new SoapClient('https://cit.seur.com/CIT-war/services/ImprimirECBWebService?wsdl', $sc_options);

			$response = $soap_client->impresionIntegracionPDFConECBWS( $params );

			echo $response->out->mensaje;

			echo '<pre>';
			var_dump( $params );
			echo '</pre>';

			echo 'Tipo de envio: ' . $seur_shipping_method;
}
