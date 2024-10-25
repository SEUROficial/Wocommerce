<?php
/**
 * SEUR Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$texto = __( 'RATES', 'seur' ) . '<br />' . __( 'Calculate rate that SEUR apply to you', 'seur' );

?>
<form id="calculate-rates" method="post" name="formulario" width="100%">
	<p><?php esc_html_e( 'Calculate the rate that SEUR will apply for a specific town or postcode.', 'seur' ); ?> </p>
	<?php
	if ( isset( $_POST['poblacion'] ) ) {
		if ( ! isset( $_POST['seur_rates_seur_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_rates_seur_nonce_field'] ) ), 'seur_rates_seur' ) ) {
			print esc_html__( 'Sorry, your nonce did not verify.', 'seur' );
			exit;
		} else {
			if ( isset( $_POST['poblacion'] ) ) {
				$poblacion = trim( sanitize_text_field( wp_unslash( $_POST['poblacion'] ) ) );
			} else {
				$poblacion = '';
			}
			if ( isset( $_POST['bultos'] ) ) {
				$bultos = intval( trim( sanitize_text_field( wp_unslash( $_POST['bultos'] ) ) ) );
			} else {
				$bultos = '';
			}
			if ( isset( $_POST['kilos'] ) ) {
				$kilos = floatval( wp_unslash( $_POST['kilos'] ) );
			} else {
				$kilos = '';
			}
			if ( isset( $_POST['pais'] ) ) {
				$pais = trim( sanitize_text_field( wp_unslash( $_POST['pais'] ) ) );
			} else {
				$pais = '';
			}
			if ( isset( $_POST['postal'] ) ) {
				$unsafepostal = trim( sanitize_text_field( wp_unslash( $_POST['postal'] ) ) );
				$postal       = sanitize_text_field( $unsafepostal );
			} else {
				$postal = '';
			}
			if ( isset( $_POST['productservice'] ) ) {
                $ps = sanitize_text_field( wp_unslash( $_POST['productservice'] ) );
                $products = seur()->get_products();
                foreach ( $products as $code => $product ) {
                    if ($ps == $product['field']) {
                        $producto = $product['product'];
                        $servicio = $product['service'];
                        break;
                    }
                }
			}
		}
	}
	?>
	<div class="wp-filter">
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Postalcode', 'seur' ); ?></span>
			<input type='text' name='postal' class="calculate-rates" placeholder="<?php esc_html_e( 'Postalcode', 'seur' ); ?>" value='<?php if ( ! empty( $postal ) ) { echo esc_html( $postal ); } ?>'>
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'City', 'seur' ); ?></span>
			<input type='text' name='poblacion' class="calculate-rates" placeholder="<?php esc_html_e( 'City', 'seur' ); ?>" value='<?php if ( ! empty( $poblacion ) ) { echo esc_html( $poblacion ); } ?>' size="12">
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Country', 'seur' ); ?></span>
			<select class="select country" id="country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="pais" required>
			<?php
			if ( ! empty( $pais ) && 'ES' === $pais ) {
				$selectedes = 'selected';
				$selectedpt = '';
				$selectedad = '';
			}
			if ( ! empty( $pais ) && 'PT' === $pais ) {
				$selectedpt = 'selected';
				$selectedes = '';
				$selectedad = '';
			}
			if ( ! empty( $pais ) && 'AD' === $pais ) {
				$selectedad = 'selected';
				$selectedes = '';
				$selectedpt = '';
			}
			if ( empty( $pais ) ) {
				$selectedad = '';
				$selectedes = '';
				$selectedpt = '';
			}
			echo '<option value="">' . esc_html__( 'Select', 'seur' ) . '</option>';
			echo '<option value="ES" ' . esc_html( $selectedes ) . '>' . esc_html__( 'Spain', 'seur' ) . '</option>';
			echo '<option value="PT" ' . esc_html( $selectedpt ) . '>' . esc_html__( 'Portugal', 'seur' ) . '</option>';
			echo '<option value="AD" ' . esc_html( $selectedad ) . '>' . esc_html__( 'Andorra', 'seur' ) . '</option>';
			$countries = seur_get_countries();
			foreach ( $countries as $countrie => $value ) {
				if ( ! empty( $pais ) && $pais === $countrie ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				echo '<option value="' . esc_html( $countrie ) . '"' . esc_html( $selected ) . '>' . esc_html( $value ) . '</option>';
			}
			?>
			</select>
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Packages', 'seur' ); ?></span>
			<input type='text' name='bultos' class="calculate-rates" placeholder="<?php esc_html_e( 'Packages', 'seur' ); ?>" value='<?php
			if ( ! empty( $bultos ) ) {
				echo esc_html( $bultos );
			}
			?>' size="12">
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Weight', 'seur' ); ?></span>
			<input type='text' name='kilos' class="calculate-rates" placeholder="<?php esc_html_e( 'Weight Kg, eg 0.1', 'seur' ); ?>" value='<?php
			if ( ! empty( $kilos ) ) {
				echo esc_html( $kilos );
			}
			?>' size="12">
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Product/Service', 'seur' ); ?></span>
			<select name="productservice" id="productservice">
				<?php
                $products = seur()->get_products();
                foreach ( $products as $code => $product ) {
                    $identifier = $product['field'];
                    echo '<option ';
                    if (isset( $ps ) && ( $identifier === $ps )) {
                        echo ' selected';
                    }
	                echo 'value="' . esc_attr( $identifier ) . '">' . esc_html( $code ) . '</option>';
                } ?>
			</select>
		</label>
		<?php wp_nonce_field( 'seur_rates_seur', 'seur_rates_seur_nonce_field' ); ?>
		<label>
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
		</label>
	</div>
	<?php
	if ( isset( $_POST['seur_rates_seur_nonce_field'] ) ) {
		if ( empty( $pais ) ) {
			echo '<div class="error notice"><p>';
			esc_html_e( 'Please fill in the Country field.', 'seur' );
			echo '</p>';
			echo '</div>';
		}
		if ( empty( $bultos ) ) {
			echo '<div class="error notice"><p>';
			esc_html_e( 'Please fill in the Packages field.', 'seur' );
			echo '</p>';
			echo '</div>';
		}
		if ( empty( $kilos ) ) {
			echo '<div class="error notice"><p>';
			esc_html_e( 'Please fill in the kilos field.', 'seur' );
			echo '</p>';
			echo '</div>';
		}
		if ( empty( $pais ) || empty( $bultos ) || empty( $kilos ) ) {
			exit;
		}
	}

	if ( isset( $_POST['seur_rates_seur_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_rates_seur_nonce_field'] ) ), 'seur_rates_seur' ) ) {
		// *****************************************
		// ** RECUPERAR LOS DATOS DEL COMERCIANTE **
		// *****************************************
		$useroptions       = seur_get_user_settings();
		$advancedoptions   = seur_get_advanced_settings();
		$ccc               = $useroptions[0]['ccc'];
		$int_ccc           = $useroptions[0]['int_ccc'];
		$franquicia        = $useroptions[0]['franquicia'];

		if ( ( 'ES' === $pais ) || ( 'PT' === $pais ) || ( 'AD' === $pais ) ) {
			$datos = [
                'countryCode' => $pais,
                'postalCode' => $postal
            ];
			if ( false === seur()->seur_api_check_city( $datos ) ) {
				echo "<hr><b><font color='#e53920'>";
				echo '<br>Código postal y país no se han encontrado en Nomenclator de SEUR.<br>Consulte Nomenclator y ajuste ambos parámetros.<br></font>';
				echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . esc_html( $postal ) . ' - ' . esc_html( $pais );
				return;
			}
		} else {
			$postal    = '08023';
			$poblacion = 'BARCELONA';
		}
        $mensaje = '';
        $products = seur()->get_products();
        foreach ( $products as $code => $product ) {
            if ($product['product']==$producto && $product['service']==$servicio) {
                $mensaje .= '<br>' . $code;
                break;
            }
        }
		if ( strlen( $mensaje ) < 1 ) {
			$mensaje .= '<br>Servicio Producto sin IDENTIFICAR';
		}

        /*
        $consulta = '';
		$sc_options = array(
			'connection_timeout' => 30,
		);
		$cliente    = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );
		$parametros = array(
			'in0' => $consulta,
		);
		$respuesta  = $cliente->tarificacionPrivadaStr( $parametros );

		if ( empty( $respuesta->out ) || ( isset( $respuesta->error ) && ! empty( $respuesta->error ) ) ) {
        */
			echo '<div class="error notice"><p>';
			esc_html_e( 'There was an error getting rate.', 'seur' );
            echo '<br>'; esc_html_e( 'Deprecated SOAP Service.', 'seur' );
			echo '</p>';
			echo '</div>';
			return;
		/* } else {
			$xml         = simplexml_load_string( $respuesta->out );
			$lineasantes = ( $xml->attributes()->NUM );
			$lineas      = (int) $lineasantes - 1;
			$total       = 0;

			?>
				<table width='25%' style='color:ed734d;font-weight:bold; font-size:14px;'>
					<tr>
						<td colspan='2'>
							<hr>
						</td>
					</tr>
			<?php
			while ( -1 !== $lineas ) {
				$nom_concept_imp = (string) $xml->REG[ $lineas ]->NOM_CONCEPTO_IMP; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$valor           = $xml->REG[ $lineas ]->VALOR; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$total           = $total + (float) $xml->REG[ $lineas ]->VALOR; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				echo '<tr>';
				echo '<td>' . utf8_decode( $nom_concept_imp ) . '</td>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo "<td style='text-align:right'>" . esc_html( $valor ) . '</td>';
				--$lineas;
				echo '</tr>';
			}
			echo "<tr><td colspan=2><hr></td><tr><td>Total</td><td style='text-align:right;color:red;font-weight:bold; font-size:20px;'>" . esc_html( $total ) . '</td></tr></table>';
			echo '<br>' . esc_html( $mensaje ) . '<br><br>';
			?>
			</table>
			<?php
			return;
		} */
	}
	?>
</form>
