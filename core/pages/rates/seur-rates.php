<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$texto="TARIFAS<BR>Consultar el coste de un envio según su tarifa";

?>
    <form id="calculate-rates" method="post" name="formulario" width="100%">
	   <p>
        <?php
	    _e('Calcule la tarifa que le aplicará SEUR para una Población o CP concreto.', 'seur-oficial');
// Si está establecido, buscamos los datos
if( isset( $_POST['postal'] ) )
{
    $bloquear = "readonly";

    if( isset( $_POST['postal'] ) ){
        $unsafepostal  = trim( $_POST["postal"] );
        $zipcode_interval = intval( $unsafepostal );
        $postal    = str_pad( $zipcode_interval, 5, '0', STR_PAD_LEFT );
    } else {
        $postal = '';
    }
    if( isset( $_POST['poblacion'] ) ){
        $poblacion = sanitize_text_field( trim( $_POST['poblacion'] ) );
    } else {
        $poblacion = '';
    }
    if( isset( $_POST['bultos'] ) ){
        $bultos = intval( trim( $_POST['bultos'] ) );
    } else {
        $bultos = '';
    }
    if( isset( $_POST['kilos'] ) ){
        $kilos = intval( trim( $_POST['kilos'] ) );
    } else {
        $kilos = '';
    }
    if( isset( $_POST['pais'] ) ){
        $pais = sanitize_text_field ( trim( $_POST['pais'] ) );
    } else {
        $pais = '';
    }
    $reembolso = sanitize_text_field ( trim( $_POST["reembolso"] ) );
    $texto  = "TARIFAS";

}

// ********************************************
// ** PARAMETROS DE ENTRADA **
// ********************************************
?>
    </p>
    <div class="wp-filter">

        <label>
            <span class="screen-reader-text"><?php _e( 'Postalcode', 'seur-oficial' ) ?></span>
            <input type='text' name='postal' class="calculate-rates" placeholder="<?php _e( 'Postalcode', 'seur-oficial' ) ?>" value='<?php if( isset( $postal ) ) echo $postal; ?>'>
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'City', 'seur-oficial' ) ?></span>
            <input type='text' name='poblacion' class="calculate-rates" placeholder="<?php _e( 'City', 'seur-oficial' ) ?>" value='<?php if( isset( $poblacion ) ) echo $poblacion; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Country', 'seur-oficial' ) ?></span>
            <input type='text' name='pais' class="calculate-rates" placeholder="<?php _e( 'Country', 'seur-oficial' ) ?>" value='<?php if( isset( $pais ) ) echo $pais; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Packages', 'seur-oficial' ) ?></span>
            <input type='text' name='bultos' class="calculate-rates" placeholder="<?php _e( 'Packages', 'seur-oficial' ) ?>" value='<?php if( isset( $bultos ) ) echo $bultos; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Weight', 'seur-oficial' ) ?></span>
            <input type='text' name='kilos' class="calculate-rates" placeholder="<?php _e( 'Weight', 'seur-oficial' ) ?>" value='<?php if( isset( $kilos ) ) echo $kilos; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Reimbursement Value', 'seur-oficial' ) ?></span>
            <input type='text' name='reembolso' class="calculate-rates" placeholder="<?php _e( 'Reimbursement Value', 'seur-oficial' ) ?>" value='<?php if( isset( $reembolso ) ) echo $reembolso; ?>' size="12">
        </label>
        <?php if( ! isset( $_POST["postal"] ) ) { ?>
        <label>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
        </label>
        <?php } ?>
        <?php if( isset( $_POST["postal"] ) ) { ?>
        <label>
            <a href="admin.php?page=seur_rates_prices&tab=calculate_rates" class="page-title-action"><?php _e('New Search', 'seur-oficial'); ?> </a>
        </label>
        <?php } ?>

    </div>
    <?php
        if( isset( $_POST['pais'] ) ) {
            if ( strlen( $pais )  < 2 ) $pais  = 'ES';
            }
        if( isset( $_POST['bultos'] ) ) {
            if ( strlen( $bultos ) < 1 ) $bultos = '1';
            }
        if( isset( $_POST['kilos'] ) ) {
            if ( strlen( $kilos )  < 1 ) $kilos = '1';
            }
        ?>


        <p class="description" id="text-seur-rates"><?php _e('El tipo de servicio producto considerado sera el establecido en los datos de la configuracion y segun el destino.<br>
            Si el envio es contrarrembolso, introduzca el valor SOLO si desea considerar que los gastos de gestión de reembolso son a cargo del remitente.<br>
            Si el envío es a Canarias, se considerarán las Aduanas y tipo de envio conforme a los datos de configuración establecidos, lo mismo para envíos a Andorra, Ceuta y Melilla.<br>', 'seur-oficial' ); ?></p>

                <?php

//Si no está establecida, volvemos aqui
if(!isset($_POST["postal"]) ) {
    return;
}


// *****************************************
// ** RECUPERAR LOS DATOS DEL COMERCIANTE **
// *****************************************
$mensaje="";

$useroptions     = seur_get_user_settings();
$advancedoptions = seur_get_advanced_settings();

$usuarioCIT         = $useroptions[0]['cit_usuario'];
$contraCIT          = $useroptions[0]['cit_contra'];
$usuarioseurcom     = $useroptions[0]['seurcom_usuario'];
$contrasenaseurcom  = $useroptions[0]['seurcom_contra'];
$ccc                = $useroptions[0]['ccc'];
$franquicia         = $useroptions[0]['franquicia'];
$p_nacional         = $advancedoptions[0]['nal_producto'];
$s_nacional         = $advancedoptions[0]['nal_servicio'];
$p_canarias         = $advancedoptions[0]['canarias_producto'];
$s_canarias         = $advancedoptions[0]['canarias_servicio'];
$aduana_origen      = $advancedoptions[0]['aduana_origen'];
$aduana_destino     = $advancedoptions[0]['aduana_destino'];

if ( $aduana_origen == 'F')
    $aduanaO = 'P';
else
    $aduanaO = 'D';

if ( $aduana_destino == 'F')
    $aduanaD = 'P';
else
    $aduanaD = 'D';

$tipomercancia   = get_option( 'seur_tipo_mercancia_field'         );
$p_internacional = get_option( 'seur_internacional_producto_field' );
$s_internacional = get_option( 'seur_internacional_servicio_field' );

// ************************************************************************
// Comprobar, es es ESPAÑA, que el el par poblacion y postal son validos
// ************************************************************************


if ( ( $pais == 'ES' ) || ( $pais == 'PT' ) || ( $pais == 'AD' ) )
    // Si el Pais es ES, PT o AD hay que comprobar la población
    {

    $producto = $p_nacional;
    $servicio = $s_nacional;
    $datos    = array( 0 => $usuarioCIT, $contraCIT, $poblacion, $postal );


    //VerificarPoblacion
    if ( SeurCheckCity( $datos ) == "ERROR" )
        { echo "<hr><b><font color='#e53920'>";
        echo "<br>Código Postal y Población no se han encontrado en Nomenclator de SEUR.<br>Consulte Nomenclator y ajuste Población y Postal.<br></font>";
        echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . $postal ." - " . $poblacion;
        echo "<hr><a href='javascript:javascript:history.go(-1)'>";
        echo 'Back';
        echo "</a>";
        return;
    }
    else
    {
        // Guardamos la franquicia de destino para luego asignar valores al envío adecuados como sv-pr,aduanas, etc
        $franq = SeurCheckCity( $datos );
        if ($franq=="74" or $franq=="77"  or $franq=="56" or
            $franq=="35" or $franq=="38" or $franq=="52"  or
            $franq=="60" or $franq=="70")
        {

            if ($aduanaO=="P")
                $mensaje.="<br>Aduana de Salida Pagada por el remitente.";

            if ($aduanaD=="P")
                $mensaje.="<br>Aduana de Entrada Pagada por el remitente.";

            if ($franq=="74" or $franq=="77" or $franq=="56")
            {
                $producto=$p_nacional;
                $servicio=$s_nacional;
            }
            else
            {
                $producto=$p_canarias;
                $servicio=$s_canarias;
            }
        }
        else {$tipomercancia=""; $aduanaO=""; $aduanaD="";}

    }
}
else
{
    if (strlen($reembolso)>0)
    {

        $mensaje.="<br>Internacional no admite envio contrarrembolso.";
        $reembolso="";
    }
    $producto=$p_internacional;
    $servicio=$s_internacional;
    $postal="08001";
    $poblacion="BARCELONA";
    $tipomercancia=""; $aduanaO=""; $aduanaD="";
}


// Mostramos el servicio producto

$tablaSP     = $wpdb->prefix . SEUR_PLUGIN_SVPR;
$sqlbusqueda = "SELECT * FROM $tablaSP WHERE ser='" . $servicio . "' and pro='" . $producto . "'";
$registros   = $wpdb->get_results( $sqlbusqueda );
$descripcion = "";

foreach ( $registros as $registro_dato ) {

    $mensaje .= "<br>" . $registro_dato->descripcion;

}

if ( strlen( $mensaje ) < 1 ) {

    $mensaje .= "<br>Servicio Producto sin IDENTIFICAR";

}


// ****************************************************
//Asignamos el valor de reembolso si lo ha introducido
//*****************************************************
if ( strlen( $reembolso ) > 0 )
{
    $complementarios="30;P;".$reembolso;
    $mensaje.="<br>Gastos de reembolso Pagados por el remitente.";
}
else
    $complementarios="";

//***********************************************************************

$consulta="<REG><USUARIO>" .$usuarioseurcom . "</USUARIO>".
    "<PASSWORD>" .$contrasenaseurcom . "</PASSWORD>".
    "<NOM_POBLA_DEST>". $poblacion ."</NOM_POBLA_DEST>".
    "<Peso>". $kilos. "</Peso>".
    "<CODIGO_POSTAL_DEST>". $postal . "</CODIGO_POSTAL_DEST>".
    "<CodContableRemitente>" . $ccc . "-" . $franquicia ."</CodContableRemitente>".
    "<PesoVolumen>" .$kilos. "</PesoVolumen>".
    "<Bultos>" . $bultos ."</Bultos>".
    "<CodServ>" .$servicio. "</CodServ>".
    "<CodProd>" . $producto . "</CodProd>".
    "<TipoEnvioAduanero>". $tipomercancia ."</TipoEnvioAduanero>".
    "<ValDeclarado></ValDeclarado>" .
    "<TpDespAduanaEntrada>". $aduanaD. "</TpDespAduanaEntrada>" .
    "<TpDespAduanaSalida>" .$aduanaO . "</TpDespAduanaSalida>" .
    "<FechaVigenciaTasacion>" . date("Y").date("m") .date("d"). "</FechaVigenciaTasacion>" .
    "<SERVICIOS_COMPLEMENTARIOS>" .$complementarios ."</SERVICIOS_COMPLEMENTARIOS>" .
    "<COD_IDIOMA>ES</COD_IDIOMA>" .
    "<CodPaisIso>" . $pais.  "</CodPaisIso>" .
    "</REG>";

//var_dump(htmlspecialchars($consulta));

$sc_options = array(
                'connection_timeout' => 30
                );

$cliente = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );

$parametros = array(
                'in0' => $consulta
                );

$respuesta = $cliente->tarificacionPrivadaStr( $parametros );

if ( empty( $respuesta->out ) || ( isset( $respuesta->error ) && !empty( $respuesta->error ) ) ) {

    echo "<hr>";
    echo __('La tasación no se ha realizado por algún error', 'seur-oficial' );
    echo "</div>";
    echo "<hr><a href='javascript:javascript:history.go(-1)'>";
    echo 'RETURN';
    echo "</a>";

    return;
} else {

    $xml         = simplexml_load_string( $respuesta->out );
    $lineasantes = ($xml->attributes()->NUM);
    $lineas      = (int)$lineasantes-1;
    $total       = 0;

    if ( defined('SEUR_DEBUG') && SEUR_DEBUG == true ) {
?>
        <pre>
        <?php
        echo $lineas . '<br />';
        print_r($xml); ?>

</pre><?php } ?>

        <table width='25%' style='color:ed734d;font-weight:bold; font-size:14px;'>
            <tr>
                <td colspan='2'>
                    <hr>
                </td>
            </tr><?php

    while ( $lineas != -1 )
    {
        $nom_concept_imp = (string) $xml->REG[$lineas]->NOM_CONCEPTO_IMP;
        $valor = $xml->REG[$lineas]->VALOR;
        $total = $total + (float)$xml->REG[$lineas]->VALOR;

        echo "<tr>";
        echo "<td>" . utf8_decode( $nom_concept_imp ) . "</td>";
        echo "<td style='text-align:right'>". $valor . "</td>";
        //$economico = $xml->REG[$lineas]->VALOR;
        //$total = $total + (float)$xml->REG[$lineas]->VALOR;
        $lineas = $lineas-1;
        echo "</tr>";
    }

    echo "<tr><td colspan=2><hr></td><tr><td>Total</td><td style='text-align:right;color:red;font-weight:bold; font-size:20px;'>" . $total ."</td></tr></table>";
    echo "<br>" . $mensaje . "<br><br>";
    return;

}

?>
        </table>
    </form>
