<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_pickup( $post ) {

	// Declarando $wpdb global y usarlo para ejecutar una sentencia de consulta SQL
	global $wpdb;

	//$fecha 		   = date("y"). date("m") .date("d"); //yy/mm/dd
	$fecha 		   = '2018' . date("m") .date("d"); //yy/mm/dd
	$bloquear	   = '';
	$bultos 	   = '';
	$kilos 		   = '';
	$identificador = '';

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Pickup', SEUR_TEXTDOMAIN ) ?></h1>
	<hr class="wp-header-end">
	<form method="post"  name="formulario" width="100%">
<?php


	// Si no está seteado bultos, no ha cargado el formulario aún o ha creado recogida en actual carga
	if ( ! isset( $_POST["bultos"] ) ) {

	// *****************************************
	// ** RECUPERAR LOS DATOS DEL COMERCIANTE **
	// *****************************************

    $user_data              = array();
    $advanced_data          = array();
    $user_data              = seur_get_user_settings();
    $advanced_data          = seur_get_advanced_settings();

    // User settings

    $cit_pass               = $user_data[0]['cit_codigo'];
    $cit_user               = $user_data[0]['cit_usuario'];
    $cit_contra             = $user_data[0]['cit_contra'];
    $nif                    = $user_data[0]['nif'];
    $franquicia             = $user_data[0]['franquicia'];
    $ccc                    = $user_data[0]['ccc'];
    $usercom                = $user_data[0]['seurcom_usuario'];
    $passcom                = $user_data[0]['seurcom_contra'];
    $empresa 				= $user_data[0]['empresa'];
    $viatipo 				= $user_data[0]['viatipo'];
    $vianombre 				= $user_data[0]['vianombre'];
    $vianumero 				= $user_data[0]['vianumero'];
    $escalera 				= $user_data[0]['escalera'];
    $piso 					= $user_data[0]['piso'];
    $puerta 				= $user_data[0]['puerta'];
    $postalcode 			= $user_data[0]['postalcode'];
    $poblacion 				= $user_data[0]['poblacion'];
    $provincia 				= $user_data[0]['provincia'];
    $pais 					= $user_data[0]['pais'];
    $telefono 				= $user_data[0]['telefono'];
    $email 					= $user_data[0]['email'];
    $contacto_nombre 		= $user_data[0]['contacto_nombre'];
    $contacto_apellidos 	= $user_data[0]['contacto_apellidos'];

    // Advanced User Settings

    $manana_desde            = $advanced_data[0]['manana_desde'];
    $manana_hasta            = $advanced_data[0]['manana_hasta'];
    $tarde_desde             = $advanced_data[0]['tarde_desde'];
    $tarde_hasta             = $advanced_data[0]['tarde_hasta'];

	$usuarioseurcom			 = $usercom;
	$contrasenaseurcom		 = $passcom;
	$Md						 = $manana_desde;
	$Mh						 = $manana_hasta;
	$Td						 = $tarde_desde;
	$Th						 = $tarde_hasta;

	echo "<form>";
	echo "<input type='hidden' name='usuarioseurcom' value='" . $usercom . "'>";
	echo "<input type='hidden' name='contrasenaseurcom' value='" . $passcom. "'>";
	echo "<input type='hidden' name='nif' value='" . $nif . "'>";
	echo "<input type='hidden' name='ccc' value='" . $ccc . "'>";
	echo "<input type='hidden' name='franquicia' value='" . $franquicia . "'>";
	echo "<input type='hidden' name='Md' value='" . $manana_desde . "'>";
	echo "<input type='hidden' name='Mh' value='" . $manana_hasta . "'>";
	echo "<input type='hidden' name='Td' value='" . $tarde_desde . "'>";
	echo "<input type='hidden' name='Th' value='" . $tarde_hasta . "'>";
	echo "<input type='hidden' name='empresa' value='" . $empresa . "'>";
	echo "<input type='hidden' name='viatip' value='" . $viatipo . "'>";
	echo "<input type='hidden' name='vianom' value='" . $vianombre . "'>";
	echo "<input type='hidden' name='vianum' value='" . $vianumero . "'>";
	echo "<input type='hidden' name='esc' value='" . $escalera . "'>";
	echo "<input type='hidden' name='piso' value='" . $piso . "'>";
	echo "<input type='hidden' name='puerta' value='" . $puerta . "'>";
	echo "<input type='hidden' name='postal' value='" . $postalcode . "'>";
	echo "<input type='hidden' name='poblacion' value='" . $poblacion . "'>";
	echo "<input type='hidden' name='provincia' value='" . $provincia . "'>";
	echo "<input type='hidden' name='pais' value='" . $pais . "'>";
	echo "<input type='hidden' name='telefono' value='" . $telefono . "'>";
	echo "<input type='hidden' name='email' value='" . $email . "'>";
	echo "<input type='hidden' name='contacton' value='" . $contacto_nombre . "'>";
	echo "<input type='hidden' name='contactoa' value='" . $contacto_apellidos . "'>";
	echo "<input type='hidden' name='usuario' value='" . $usercom . "'>";
	echo "<input type='hidden' name='contra' value='" . $passcom. "'>";


} else {

		$bultos				= $_POST["bultos"];
		$kilos				= $_POST["kilos"];
		$Md					= $_POST["Md"];
		$Mh					= $_POST["Mh"];
		$Td					= $_POST["Td"];
		$Th					= $_POST["Th"];
		$bloquear			= "readonly";
		$usuarioseurcom		= $_POST["usuarioseurcom"];
		$contrasenaseurcom	= $_POST["contrasenaseurcom"];

	}



//************************************************************************
// Comprobar si tiene una recogida para hoy y mostrar sus situaciones
//*************************************************************************

 /*$sqlRECO= "SELECT id FROM $tablaRECO WHERE fecha='".$fecha. "'";
 $datos = $wpdb->get_results($sqlRECO);
 foreach ($datos as $valor )
 $identificador=$valor->id;*/

 if ( $identificador != "" )
	{
	echo "<div style='color:#e53920;font-weight:bold; font-size:12px;'>";
	echo "YA TIENE UNA RECOGIDA PARA HOY<br>IDENTIFICADOR: " . $identificador ."</div>";
	// situaciones de la recogida

	$sc_options = array(
				'connection_timeout' => 30
			);

			$params = array(
			'in0'=>$identificador,
			'in1'=>"",
			'in2'=>$usuarioseurcom,
			'in3'=>$contrasenaseurcom);

			//pedimos las etiquetas
			$cliente = new SoapClient('https://ws.seur.com/webseur/services/WSConsultaRecogidas?wsdl', $sc_options);
			$respuesta= $cliente->consultaDetallesRecogidasStr($params);
			$xml = simplexml_load_string($respuesta->out);
			echo "<div style='color:#0074a2;font-weight:bold; font-size:12px;'>";
			echo "<HR>RECOGER EN:<BR>" ;
			echo $xml->RECOGIDA->DONDE_NOMBRE;
			echo "<br>";
			echo $xml->RECOGIDA->DONDE_VIA_NOMBRE;
			echo "<br>";
			echo $xml->RECOGIDA->DONDE_POBLACION;
			echo "<br>";
			echo $xml->RECOGIDA->DONDE_PROVINCIA;
			echo "<hr>";
			foreach($xml->RECOGIDA->SITUACIONES->SITUACION as $contenido)
				{
				echo $contenido->FECHA_SITUACION. " ";
				echo $contenido->DESCRIPCION_CLIENTE ."<br>";
				}
			echo "</div>";
			return;
	}

// -------------------------------------------------
// NO TIENE RECOGIDA -> CONTINUA EL PROCESO
// -------------------------------------------------




// ********************************************
// ** PARAMETROS INFORMATIVOS DE LA RECOGIDA **
// ********************************************
?>
<table width='100%' style='color:ed734d;font-weight:bold; font-size:12px;'>

	<tr>
	<td>
	<table  width='50%'>
	<tr><td colspan="2">RECOGIDA</div><hr></td></tr>
	<tr><td colspan="2">Introduzca un valor aproximado para Bultos y Kilos.</div></td></tr>
	</tr>
	<tr>
	<td>Bultos:&nbsp;&nbsp;&nbsp;
	<?php if (strlen($bultos)<1) $bultos=1;  if (strlen($kilos)<1) $kilos=1;?>
	<input style=text-align:right type=text name=bultos value="<?php echo $bultos;?>" size=1 maxlength=3  <?php echo $bloquear; ?> >
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kilos:&nbsp;&nbsp;&nbsp;
	<input style=text-align:right type=text name=kilos value="<?php echo $kilos;?>" size=1 maxlength=4   <?php echo $bloquear; ?>>
	</td>
	</tr>
	<tr><td colslpan=2><br><Introduzca un horario para la Recogida, formato HH:MM<BR>El margen minimo entre cada horario es de 2 horas.</div></td></tr>
	<tr>
	<td>Mañana Desde:&nbsp;&nbsp;&nbsp;
	<input type='text' name='Md' value="<?php echo $Md;?>" size=4  <?php echo $bloquear; ?>>
	&nbsp;&nbsp;&nbsp;
	Hasta&nbsp;&nbsp;&nbsp;
	<input type='text' name='Mh' value="<?php echo $Mh;?>" size=4 <?php echo $bloquear; ?>>
	</td></tr><tr>
	<td>Tarde Desde:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type='text' name='Td' value="<?php echo $Td;?>" size=4 <?php echo $bloquear; ?>>
	&nbsp;&nbsp;&nbsp;
	Hasta&nbsp;&nbsp;&nbsp;
	<input type='text' name='Th' value="<?php echo $Th;?>" size=4 <?php echo $bloquear; ?>>
	</td>
	</tr>
	<tr><td colslpan=2><br>Si el horario es sólo de Mañanas deje vacios los horarios de Tarde.<br>Si el horario es sólo de Tardes deje vacios los horarios de Mañana.</div></td></tr>

	</tr>

	</table>
	</form>


<?php

// Si no está setado, boton Solicitar
if( !isset($_POST["bultos"]) )
	{
	submit_button("Solicitar");
	return;
	}

 // Retorno del boton solicitar
 // Formamos la recogida
 $TRAMA_KILOS="1;1;1;".$_POST["kilos"].";0";



  $DatosRecogida= "".
		"<recogida>".
		"<usuario>" .$_POST["usuario"]. "</usuario>" .
		"<password>" .$_POST["contra"] ."</password>" .
		"<razonSocial>" . seur_clean_data($_POST["empresa"]) . "</razonSocial>" .
		"<nombreEmpresa>" .seur_clean_data($_POST["empresa"]) ."</nombreEmpresa>" .
		"<nombreContactoOrdenante>" . seur_clean_data($_POST["contacton"]) . "</nombreContactoOrdenante>" .
		"<apellidosContactoOrdenante>" .seur_clean_data($_POST["contactoa"]). "</apellidosContactoOrdenante>" .
		"<prefijoTelefonoOrdenante>34</prefijoTelefonoOrdenante>".
		"<telefonoOrdenante>" . $_POST["telefono"] . "</telefonoOrdenante>" .
		"<prefijoFaxOrdenante />".
		"<faxOrdenante />".
		"<nifOrdenante>" . $_POST["nif"] . "</nifOrdenante>".
		"<paisNifOrdenante>ES</paisNifOrdenante>".
		"<mailOrdenante>" .$_POST["email"] . "</mailOrdenante>".
		"<tipoViaOrdenante>" . $_POST["viatip"] . "</tipoViaOrdenante>".
		"<calleOrdenante>" . seur_clean_data($_POST["vianom"]) . "</calleOrdenante>".
		"<tipoNumeroOrdenante>N.</tipoNumeroOrdenante>".
		"<numeroOrdenante>" . $_POST["vianum"] . "</numeroOrdenante>".
		"<escaleraOrdenante>". $_POST["escalera"]. "</escaleraOrdenante>" .
		"<pisoOrdenante>" . $_POST["piso"]."</pisoOrdenante>" .
		"<puertaOrdenante>" . $_POST["puerta"] . "</puertaOrdenante>".
		"<codigoPostalOrdenante>" . $_POST["postal"] . "</codigoPostalOrdenante>".
		"<poblacionOrdenante>" . seur_clean_data($_POST["poblacion"]) . "</poblacionOrdenante>".
		"<provinciaOrdenante>" . seur_clean_data($_POST["provincia"]) . "</provinciaOrdenante>".
		"<paisOrdenante>ES</paisOrdenante>" .



		"<diaRecogida>" . date("d") . "</diaRecogida>".
		"<mesRecogida>" . date("m") . "</mesRecogida>".
		"<anioRecogida>" . date("Y") . "</anioRecogida>".
		"<servicio>1</servicio>".
		"<horaMananaDe>" . $_POST["Md"] ."</horaMananaDe>".
		"<horaMananaA>" . $_POST["Mh"] ."</horaMananaA>".
		"<numeroBultos>". $_POST["bultos"] ."</numeroBultos>".
		"<mercancia>2</mercancia>".
		"<horaTardeDe>" . $_POST["Td"]. "</horaTardeDe>".
		"<horaTardeA>" . $_POST["Th"] . "</horaTardeA>".
		"<tipoPorte>P</tipoPorte>".
		"<observaciones></observaciones>".
		"<tipoAviso>EMAIL</tipoAviso>".
		"<idiomaContactoOrdenante>ES</idiomaContactoOrdenante>".


		"<razonSocialDestino>" . seur_clean_data($_POST["empresa"]) . "</razonSocialDestino>".
		"<nombreContactoDestino>" . seur_clean_data($_POST["contacton"]) . "</nombreContactoDestino>".
		"<apellidosContactoDestino>" . seur_clean_data($_POST["contactoa"]) . "</apellidosContactoDestino>".
		"<telefonoDestino>" . $_POST["telefono"] . "</telefonoDestino>".
		"<tipoViaDestino>" . $_POST["viatip"]. "</tipoViaDestino>".
		"<calleDestino>" . seur_clean_data($_POST["vianom"]) . "</calleDestino>".
		"<tipoNumeroDestino>N.</tipoNumeroDestino>".
		"<numeroDestino>" . $_POST["vianum"]. "</numeroDestino>".
		"<escaleraDestino>". $_POST["escalera"] . "</escaleraDestino>" .
		"<pisoDestino>".$_POST["piso"] ."</pisoDestino>".
		"<puertaDestino>".$_POST["puerta"] . "</puertaDestino>".
		"<codigoPostalDestino>" . $_POST["postal"] . "</codigoPostalDestino>".
		"<poblacionDestino>" . seur_clean_data($_POST["poblacion"]) . "</poblacionDestino>".
		"<provinciaDestino>" . seur_clean_data($_POST["provincia"]) . "</provinciaDestino>".
		"<paisDestino>ES</paisDestino>".
		"<prefijoTelefonoDestino>34</prefijoTelefonoDestino>".

		"<razonSocialOrigen>" . seur_clean_data($_POST["empresa"]) . "</razonSocialOrigen>".
		"<nombreContactoOrigen>" . seur_clean_data($_POST["contacton"]) . "</nombreContactoOrigen>".
		"<apellidosContactoOrigen>" .seur_clean_data($_POST["contactoa"]) . "</apellidosContactoOrigen>".
		"<telefonoRecogidaOrigen>" . $_POST["telefono"] . "</telefonoRecogidaOrigen>".
		"<tipoViaOrigen>" . $_POST["viatip"]. "</tipoViaOrigen>".
		"<calleOrigen>" .seur_clean_data($_POST["vianom"]) . "</calleOrigen>".
		"<tipoNumeroOrigen>N.</tipoNumeroOrigen>".
		"<numeroOrigen>" . $_POST["vianum"]. "</numeroOrigen>".
		"<escaleraOrigen>". $_POST["escalera"] . "</escaleraOrigen>" .
		"<pisoOrigen>".$_POST["piso"] . "</pisoOrigen>".
		"<puertaOrigen>".$_POST["puerta"]."</puertaOrigen>".
		"<codigoPostalOrigen>" . $_POST["postal"] . "</codigoPostalOrigen>".
		"<poblacionOrigen>" . seur_clean_data($_POST["poblacion"]) . "</poblacionOrigen>".
		"<provinciaOrigen>" . seur_clean_data($_POST["provincia"]) . "</provinciaOrigen>".
		"<paisOrigen>ES</paisOrigen>".
		"<prefijoTelefonoOrigen>34</prefijoTelefonoOrigen>".


		"<producto>2</producto>".
		"<entregaSabado>N</entregaSabado>".
		"<entregaNave>N</entregaNave>".
		"<tipoEnvio>N</tipoEnvio>".
		"<valorDeclarado>0</valorDeclarado>".
		//"<listaBultos>1;1;1;1;1/</listaBultos>".

		"<listaBultos>". $TRAMA_KILOS."/</listaBultos>".
		"<cccOrdenante>". $_POST["ccc"] . "-" . $_POST["franquicia"]. "</cccOrdenante>".
		"<numeroReferencia></numeroReferencia>".
		"<ultimaRecogidaDia />".
		"<nifOrigen></nifOrigen>".
		"<paisNifOrigen></paisNifOrigen>".
		"<aviso>N</aviso>".
		"<cccDonde />".
		"<cccAdonde></cccAdonde>".
		"<tipoRecogida></tipoRecogida>".
		"</recogida>";


		$sc_options = array(
				'connection_timeout' => 30
			);

		$soap_client = new SoapClient('https://ws.seur.com/webseur/services/WSCrearRecogida?wsdl', $sc_options);
		$parametros = array('in0'=>$DatosRecogida);
		$respuesta = $soap_client->crearRecogida($parametros);
		$xml = simplexml_load_string($respuesta->out);
		$codigo="";
		$codigo=$xml->CODIGO;
		if (strlen($codigo)>1 )
		{
			echo errores();
			echo "SE HA PRODUCIDO UN ERROR</div>";
			echo $xml->DESCRIPCION;
			echo "<hr><a href='javascript:javascript:history.go(-1)'>";
			echo "<img src='". SEUR_IMAGENES. "/volver.jpg" . "'></img>";
			echo "</a>";
			return;
		}
		else
		{
		echo ok();
		echo "Se ha creado la recogida.<br>Localizador: " . $xml->LOCALIZADOR . "</div>";

		// Destruirmos la variable para que no pueda crear mas recogidas en esta vista actual
		 unset($_POST["bultos"]);
		// Grabamos que se ha creado la recogida para accesos del día muestre situaciones
		 $sql = "INSERT INTO $tablaRECO (fecha,id) VALUES ($fecha,$xml->LOCALIZADOR)";
		 $resultado=$wpdb->query($sql);
		 if ($resultado!=1)
				echo errores()."</div>";
		return;
		}


?> </div> <?php
}