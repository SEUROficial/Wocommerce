<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_search_nomenclator( $post ) { ?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Nomenclator', 'seur' ) ?></h1>
     <?php if( isset($_POST['codigo_postal']) ) { ?><a href="admin.php?page=seur_search_nomenclator" class="page-title-action"><?php _e('New Search', 'seur');?></a> <?php } ?>
    <hr class="wp-header-end">
    <?php $seur_user = get_option( 'seur_seurcom_usuario_field' );
        $seur_pass = get_option( 'seur_seurcom_contra_field' );

        if ( defined('SEUR_DEBUG') && SEUR_DEBUG == true ) {
            echo '============<br />';
            echo __('Debug info', 'seur' ) . '<br />';
            echo '============<br />';
            $safe_zipcode = '';
            echo 'SEUR User: ' . $seur_user . '<br />';
            echo 'SEUR Pasword: ' . $seur_pass . '<br />';
            echo 'Zipcode: ' . $safe_zipcode . '<br />';
            if ( isset( $_POST['codigo_postal'] ) ) { $zipcode_test = $_POST['codigo_postal']; } else { $zipcode_test = ''; }
            echo 'Zipcode Postal: ' . $zipcode_test . '<br />';
            echo 'Sanitized Zipcode: ' . intval( $zipcode_test ) . '<br />';
            $zipcode_interval = intval( $zipcode_test );
            echo 'Sanitized Zipcode with zeros: ' . str_pad( $zipcode_interval, 5, '0', STR_PAD_LEFT ) . '<br />';
            $seur_url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
            $seur_url_result = seur_check_url_exists( $seur_url );
            if ( ( $seur_url_result == 200 ) || ( $seur_url_result == 302 ) ) {
                echo 'Resultado comprobación URL: 200 Connection established<br />';
            }
            if ( $seur_url_result != 200 && $seur_url_result != 0 && $seur_url_result != 302 ) {
                echo 'Resultado comprobación URL: Error ' . $seur_url_result . '<br />';
            }
            if ( $seur_url_result == 0 ) echo 'Error: No connection to SEUR<br />';
            echo '===============================================<br />';
            echo '===============================================<br />';
        } ?>
    <?php _e( 'Check ZIP or city associated to Seur system.', 'seur' ); ?>
    <form method="post" name="formulario" width="100%">
        <?php

            if( isset($_POST['codigo_postal']) )
            {
                if ( ! isset( $_POST['nomenclator_seur_nonce_field'] ) || ! wp_verify_nonce( $_POST['nomenclator_seur_nonce_field'], 'nomenclator_seur' ) ) {


                    print 'Sorry, your nonce did not verify.';
                    exit;

                } else {
                    $unsafe_zipcode = trim( $_POST['codigo_postal'] );
                    if ( ! $unsafe_zipcode ) {
                        $safe_zipcode = '';
                    } else {
                        $safe_zipcode  = sanitize_text_field( $unsafe_zipcode );
                    }
                    //Getting needed user data

                    $seur_user = get_option( 'seur_seurcom_usuario_field' );
                    $seur_pass = get_option( 'seur_seurcom_contra_field' );

                    $nombre_poblacion = '';
                    $codigo_postal  = '';

                    if ( isset( $_POST['nombre_poblacion'] ) ) { $nombre_poblacion = sanitize_text_field ( $_POST['nombre_poblacion'] ); } else { $nombre_poblacion = ''; }


                    $sc_options = array(
                        'connection_timeout' => 30,
                        'exceptions' => 0
                    );

                    $seur_url = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
                    if ( filter_var( $seur_url, FILTER_VALIDATE_URL) === FALSE ) {
                        _e('There is a problem connecting to SEUR. Please try again later.', 'seur');
                    } else {
                        $soap_client = new SoapClient('https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options);

                        $param = array(
                            'in0' => "",
                            'in1' => $nombre_poblacion,
                            'in2' => $safe_zipcode,
                            'in3' => "",
                            'in4' => "",
                            'in5' => $seur_user,
                            'in6' => $seur_pass
                        );


                        $seurdata = $soap_client->infoPoblacionesCortoStr( $param );

                        if ( is_soap_fault( $seurdata ) ) {
                            trigger_error("SOAP Fault: (faultcode: { $seurdata->faultcode }, faultstring: { $seurdata->faultstring } )", E_USER_ERROR);
                        } else {

                            $string_xml = htmlspecialchars_decode( $seurdata->out );
                            $strXml  = iconv( "UTF-8","ISO-8859-1", $string_xml );
                            $xml  = simplexml_load_string( $strXml );

                            $howmanyresults = $xml->attributes()->NUM;
        ?>
        <ul class="subsubsub">
            <li class="all"><?php seur_search_number_message_result( $howmanyresults ) ?></li>
        </ul>

        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <td class="manage-column"><?php _e('POSTCODE', 'seur' ); ?></td>

                    <td class="manage-column"><?php _e('CITY', 'seur' ); ?></td>

                    <td class="manage-column"><?php _e('STATE/PROVINCE', 'seur' ); ?></td>
                </tr>
            </thead><?php
                                for ( $ele = 1; $ele <= $howmanyresults; $ele++ ) {

                                    $registro="REG" . $ele;

                                    echo '<tr><td><a href="https://www.google.es/maps/search/' . $xml->$registro->NOM_POBLACION . ',+' . $xml->$registro->NOM_PROVINCIA. '+' . $xml->$registro->CODIGO_POSTAL . '+seur" target="_blank">' . $xml->$registro->CODIGO_POSTAL . '</a>';
                                    echo "</td>";
                                    echo "<td>" . $xml->$registro->NOM_POBLACION . "</td>";
                                    echo "<td>" . $xml->$registro->NOM_PROVINCIA . "</td></tr>";
                                } ?>

            <tfoot>
                <tr>
                    <td class="manage-column"><?php _e('POSTCODE', 'seur' ); ?></td>

                    <td class="manage-column"><?php _e('CITY', 'seur' ); ?></td>

                    <td class="manage-column"><?php _e('STATE/PROVINCE', 'seur' ); ?></td>
                </tr>
            </tfoot>
        </table><?php

                        }
                    } ?>
    </form>
</div><?php
        }
    }
    else // Aun no esta establecido
        {
    ?>
    <div class="wp-filter">

        <label>
            <span class="screen-reader-text"><?php _e( 'City', 'seur' ) ?></span>
            <input type='text' name='nombre_poblacion' class="wp-filter-search" placeholder="<?php _e( 'City', 'seur' ) ?>" value=''>
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Postal code', 'seur' ) ?></span>
            <input type='text' name='codigo_postal' class="wp-filter-search" placeholder="<?php _e( 'Postalcode', 'seur' ) ?>" value='' size="12">
        </label>
        <label>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
        </label>
    </div>
        <?php  wp_nonce_field( 'nomenclator_seur', 'nomenclator_seur_nonce_field' ); ?>
    <?php }

}