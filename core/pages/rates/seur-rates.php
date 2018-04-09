<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$texto = __('RATES', 'seur' ) . '<br />' . __('Calculate rate that SEUR apply to you', 'seur' );

?>
<form id="calculate-rates" method="post" name="formulario" width="100%">
    <p><?php _e('Calculate the rate that SEUR will apply for a specific town or postcode.', 'seur'); ?> </p>
        <?php
        if ( isset( $_POST['poblacion'] ) ) {

            if ( ! isset( $_POST['seur_rates_seur_nonce_field'] ) || ! wp_verify_nonce( $_POST['seur_rates_seur_nonce_field'], 'seur_rates_seur' ) ) {

                            print __( 'Sorry, your nonce did not verify.', 'seur');
                            exit;
                } else {

                    if ( isset( $_POST['poblacion'] ) ) {
                        $poblacion = sanitize_text_field( trim( $_POST['poblacion'] ) );
                    } else {
                        $poblacion = '';
                    }

                    if ( isset( $_POST['bultos'] ) ) {
                        $bultos = intval( trim( $_POST['bultos'] ) );
                    } else {
                        $bultos = '';
                    }

                    if ( isset( $_POST['kilos'] ) ) {
                        $kilos = floatval( $_POST['kilos'] );
                    } else {
                        $kilos = '';
                    }

                    if ( isset( $_POST['pais'] ) ) {
                        $pais = sanitize_text_field ( trim( $_POST['pais'] ) );
                    } else {
                        $pais = '';
                    }

                    if ( isset( $_POST['postal'] ) ) {
                        $unsafepostal  = trim( $_POST["postal"] );
                        $postal        = sanitize_text_field ( $unsafepostal );
                    } else {
                        $postal = '';
                    }

                    if ( isset( $_POST['productservice'] ) ) {

                        $ps = sanitize_text_field ( $_POST['productservice'] );

                        if ( $ps == 'bc2' ) {
                            $producto = '2';
                            $servicio = '31';
                        }

                        elseif ( $ps == 'seur10e' ) {
                            $producto = '2';
                            $servicio = '3';
                        }

                        elseif ( $ps == 'seur10f' ) {
                            $producto = '18';
                            $servicio = '3';
                        }

                        elseif ( $ps == 'seur1330e' ) {
                            $producto = '2';
                            $servicio = '9';
                        }

                        elseif ( $ps == 'seur1330f' ) {
                            $producto = '18';
                            $servicio = '9';
                        }

                        elseif ( $ps == 'seur48' ) {
                            $producto = '2';
                            $servicio = '15';
                        }

                        elseif ( $ps == 'seur72' ) {
                            $producto = '2';
                            $servicio = '13';
                        }

                        elseif ( $ps == 'seurint' ){
                            $producto = '70';
                            $servicio = '77';
                        }

                        elseif ( $ps == 'seur2shop' ){
                            $producto = '48';
                            $servicio = '1';
                        }

                    } // if ( isset( $_POST['productservice'] ) )

            } // ! isset( $_POST['seur_rates_seur_nonce_field'] )

        } // if ( isset( $_POST['poblacion'] ) )
        ?>
    <div class="wp-filter">
        <label>
            <span class="screen-reader-text"><?php _e( 'Postalcode', 'seur' ) ?></span>
            <input type='text' name='postal' class="calculate-rates" placeholder="<?php _e( 'Postalcode', 'seur' ) ?>" value='<?php if ( ! empty( $postal ) ) echo $postal; ?>'>
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'City', 'seur' ) ?></span>
            <input type='text' name='poblacion' class="calculate-rates" placeholder="<?php _e( 'City', 'seur' ) ?>" value='<?php if ( ! empty( $poblacion ) ) echo $poblacion; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Country', 'seur' ) ?></span>
            <select class="select country" id="country" title="<?php _e('Select Country', 'seur' ); ?>" name="pais">
                    <?php
                        if ( ! empty( $pais ) && $pais == 'ES' ) { $selected = 'selected'; }
                        echo '<option value="NULL">' . __( 'Select', 'seur' ) . '</option>';
                        echo '<option value="ES" ' . $selected . '>' . __('Spain', 'seur' ) . '</option>';
                        $countries = seur_get_countries();
                        foreach ($countries as $countrie => $value ) {
                                if ( ! empty( $pais ) && $pais == $countrie ) { $selected = 'selected'; } else { $selected = ''; }
                                echo '<option value="' . $countrie  . '"' . $selected . '>' . $value . '</option>';
                            }
                    ?>
            </select>
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Packages', 'seur' ) ?></span>
            <input type='text' name='bultos' class="calculate-rates" placeholder="<?php _e( 'Packages', 'seur' ) ?>" value='<?php if ( ! empty( $bultos ) ) echo $bultos; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Weight', 'seur' ) ?></span>
            <input type='text' name='kilos' class="calculate-rates" placeholder="<?php _e( 'Weight Kg, eg 0.1', 'seur' ) ?>" value='<?php if ( ! empty( $kilos ) ) echo $kilos; ?>' size="12">
        </label>
        <label>
            <span class="screen-reader-text"><?php _e( 'Product/Service', 'seur' ) ?></span>
            <select name="productservice" id="productservice">
                <option <?php if( isset( $ps ) && ( $ps == 'bc2'       ) ) echo ' selected'; ?> value="bc2">B2C Estándar</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur10e'   ) ) echo ' selected'; ?> value="seur10e">SEUR 10 Estándar</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur10f'   ) ) echo ' selected'; ?> value="seur10f">SEUR 10 Frío</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur1330e' ) ) echo ' selected'; ?> value="seur1330e">SEUR 13:30 Estándar</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur1330f' ) ) echo ' selected'; ?> value="seur1330f">SEUR 13:30 Frío</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur48'    ) ) echo ' selected'; ?> value="seur48">SEUR 48H Estándar</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur72'    ) ) echo ' selected'; ?> value="seur72">SEUR 72H Estándar</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seurint'   ) ) echo ' selected'; ?> value="seurint">Classic Internacional Terrestre</option>
                <option <?php if( isset( $ps ) && ( $ps == 'seur2shop'   ) ) echo ' selected'; ?> value="seur2shop">SEUR 2SHOP</option>
            </select>
        <label>
        <?php  wp_nonce_field( 'seur_rates_seur', 'seur_rates_seur_nonce_field' ); ?>
        <label>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
        </label>
    </div>
    <?php
        if ( isset( $_POST['seur_rates_seur_nonce_field'] ) ) {
            if ( empty( $pais ) ) {
                echo '<div class="error notice"><p>';
                 _e( 'Please fill in the Country field.', 'seur' );
                 echo '</p>';
                 echo '</div>';
                }

            if ( empty( $bultos ) ) {
                echo '<div class="error notice"><p>';
                 _e( 'Please fill in the Packages field.', 'seur' );
                 echo '</p>';
                 echo '</div>';
                }

            if ( empty( $kilos ) ) {
                 echo '<div class="error notice"><p>';
                 _e( 'Please fill in the kilos field.', 'seur' );
                 echo '</p>';
                 echo '</div>';
                }
            if ( empty( $pais ) || empty( $bultos ) || empty( $kilos ) ) exit;

        } // if ( isset( $_POST['seur_rates_seur_nonce_field'] ) )

        if ( isset( $_POST['seur_rates_seur_nonce_field'] ) && wp_verify_nonce( $_POST['seur_rates_seur_nonce_field'], 'seur_rates_seur' ) ) {

            // *****************************************
            // ** RECUPERAR LOS DATOS DEL COMERCIANTE **
            // *****************************************
            $useroptions        = seur_get_user_settings();
            $advancedoptions    = seur_get_advanced_settings();

            $usuarioCIT         = $useroptions[0]['cit_usuario'];
            $contraCIT          = $useroptions[0]['cit_contra'];
            $usuarioseurcom     = $useroptions[0]['seurcom_usuario'];
            $contrasenaseurcom  = $useroptions[0]['seurcom_contra'];
            $ccc                = $useroptions[0]['ccc'];
            $franquicia         = $useroptions[0]['franquicia'];

            if ( ( $pais == 'ES' ) || ( $pais == 'PT' ) || ( $pais == 'AD' ) ) {
                $datos = array( 0 => $usuarioCIT, $contraCIT, $poblacion, $postal );
                if ( SeurCheckCity( $datos ) == "ERROR" ) {
                    echo "<hr><b><font color='#e53920'>";
                    echo "<br>Código Postal y Población no se han encontrado en Nomenclator de SEUR.<br>Consulte Nomenclator y ajuste Población y Postal.<br></font>";
                    echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . $postal ." - " . $poblacion;
                    return;
                } // if ( SeurCheckCity
            } else { // if ( ( $pais == 'ES' ) || ( $pais == 'PT' )
                $postal    = '08023';
                $poblacion = 'BARCELONA';
            }
            $registros   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}seur_svpr WHERE ser= %d and pro= %d", $servicio, $producto ) );
            $descripcion = "";
            foreach ( $registros as $registro_dato ) {
                $mensaje .= "<br>" . $registro_dato->descripcion;
            }

            if ( strlen( $mensaje ) < 1 ) {
                $mensaje .= "<br>Servicio Producto sin IDENTIFICAR";
            }

            $consulta   =   "<REG><USUARIO>" .$usuarioseurcom . "</USUARIO>".
                            "<PASSWORD>" .$contrasenaseurcom . "</PASSWORD>".
                            "<NOM_POBLA_DEST>". $poblacion ."</NOM_POBLA_DEST>".
                            "<Peso>". $kilos. "</Peso>".
                            "<CODIGO_POSTAL_DEST>". $postal . "</CODIGO_POSTAL_DEST>".
                            "<CodContableRemitente>" . $ccc . "-" . $franquicia ."</CodContableRemitente>".
                            "<PesoVolumen>0</PesoVolumen>".
                            "<Bultos>" . $bultos ."</Bultos>".
                            "<CodServ>" .$servicio. "</CodServ>".
                            "<CodProd>" . $producto . "</CodProd>".
                            "<COD_IDIOMA>ES</COD_IDIOMA>" .
                            "<CodPaisIso>" . $pais.  "</CodPaisIso>" .
                            "</REG>";
            $sc_options = array(
                'connection_timeout' => 30
                );

            $cliente = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );
            $parametros = array(
                            'in0' => $consulta
                            );
            $respuesta = $cliente->tarificacionPrivadaStr( $parametros );

            if ( empty( $respuesta->out ) || ( isset( $respuesta->error ) && !empty( $respuesta->error ) ) ) {

                echo '<div class="error notice"><p>';
                 _e( 'There was an error getting rate.', 'seur' );
                 echo '</p>';
                 echo '</div>';
                return;
            } else { // if ( empty( $respuesta->out ) || ( isset
                $xml         = simplexml_load_string( $respuesta->out );
                $lineasantes = ( $xml->attributes()->NUM) ;
                $lineas      = (int)$lineasantes - 1;
                $total       = 0;

                /* Para debug
	            echo '$usuarioseurcom: '    . $usuarioseurcom . '<br />';
                echo '$contrasenaseurcom: ' . $contrasenaseurcom . '<br />';
                echo '$poblacion: '         . $poblacion . '<br />';
                echo '$kilos: '             . $kilos . '<br />';
                echo '$postal: '            . $postal . '<br />';
                echo '$ccc: '               . $ccc . '<br />';
                echo '$franquicia: '        . $franquicia . '<br />';
                echo 'PesoVolumen: 0<br />';
                echo '$bultos: '            . $bultos . '<br />';
                echo '$servicio: '          . $servicio . '<br />';
                echo '$producto: '          . $producto . '<br />';
                echo 'COD_IDIOMA: ES<br />';
                echo '$pais: '              . $pais . '<br />';
                */

                ?>
                <table width='25%' style='color:ed734d;font-weight:bold; font-size:14px;'>
                    <tr>
                        <td colspan='2'>
                            <hr>
                        </td>
                    </tr><?php

                    while ( $lineas != -1 ) {

                        $nom_concept_imp = ( string ) $xml->REG[$lineas]->NOM_CONCEPTO_IMP;
                        $valor           = $xml->REG[$lineas]->VALOR;
                        $total           = $total + ( float )$xml->REG[$lineas]->VALOR;

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

                    ?> </table> <?php

                }

        } // if ( isset( $_POST['seur_rates_seur_nonce_field'] ) && wp_verify_nonce...
        ?>
</form>