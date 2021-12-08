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
				if ( 'bc2' === $ps ) {
					$producto = '2';
					$servicio = '31';
				} elseif ( 'seur10e' === $ps ) {
					$producto = '2';
					$servicio = '3';
				} elseif ( 'seur10f' === $ps ) {
					$producto = '18';
					$servicio = '3';
				} elseif ( 'seur1330e' === $ps ) {
					$producto = '2';
					$servicio = '9';
				} elseif ( 'seur1330f' === $ps ) {
					$producto = '18';
					$servicio = '9';
				} elseif ( 'seur48' === $ps ) {
					$producto = '2';
					$servicio = '15';
				} elseif ( 'seur72' === $ps ) {
					$producto = '2';
					$servicio = '13';
				} elseif ( 'seurint' === $ps ) {
					$producto = '70';
					$servicio = '77';
				} elseif ( 'seur2shop' === $ps ) {
					$producto = '48';
					$servicio = '1';
				} elseif ( 'seurintaepaq' === $ps ) {
					$producto = '108';
					$servicio = '7';
				} elseif ( 'seurintaedoc' === $ps ) {
					$producto = '54';
					$servicio = '7';
				} elseif ( 'seurintaeter' === $ps ) {
					$producto = '19';
					$servicio = '70';
				}
			}
		}
	}
	?>
	<div class="wp-filter">
		<label>
			<span class="screen-reader-text"><?php _e( 'Postalcode', 'seur' ); ?></span>
			<input type='text' name='postal' class="calculate-rates" placeholder="<?php _e( 'Postalcode', 'seur' ); ?>" value='<?php if ( ! empty( $postal ) ) { echo esc_html( $postal ); } ?>'>
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'City', 'seur' ); ?></span>
			<input type='text' name='poblacion' class="calculate-rates" placeholder="<?php esc_html_e( 'City', 'seur' ); ?>" value='<?php if ( ! empty( $poblacion ) ) { echo esc_html( $poblacion ); } ?>' size="12">
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Country', 'seur' ); ?></span>
			<select class="select country" id="country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="pais">
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
			echo '<option value="NULL">' . esc_html__( 'Select', 'seur' ) . '</option>';
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
				<option 
				<?php
				if ( isset( $ps ) && ( 'bc2' === $ps ) ) {
					echo ' selected';}
				?>
				value="bc2">B2C Estándar</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur10e' === $ps ) ) {
					echo ' selected';
				}
				?>
				value="seur10e">SEUR 10 Estándar</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur10f' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur10f">SEUR 10 Frío</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur1330e' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur1330e">SEUR 13:30 Estándar</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur1330f' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur1330f">SEUR 13:30 Frío</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur48' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur48">SEUR 48H Estándar</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur72' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur72">SEUR 72H Estándar</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seurint' === $ps ) ) {
					echo ' selected';}
				?>
				value="seurint">Classic Internacional Terrestre</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seur2shop' === $ps ) ) {
					echo ' selected';}
				?>
				value="seur2shop">SEUR 2SHOP</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seurintaepaq' === $ps ) ) {
					echo ' selected';}
				?>
				value="seurintaepaq">COURIER INT AEREO PAQUETERIA</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seurintaedoc' === $ps ) ) {
					echo ' selected';}
				?>
				value="seurintaedoc">COURIER INT AEREO DOCUMENTOS</option>
				<option 
				<?php
				if ( isset( $ps ) && ( 'seurintaeter' === $ps ) ) {
					echo ' selected';}
				?>
				value="seurintaeter">NETEXPRESS INT TERRESTRE</option>
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
		$usuario_cit       = $useroptions[0]['cit_usuario'];
		$contra_cit        = $useroptions[0]['cit_contra'];
		$usuarioseurcom    = $useroptions[0]['seurcom_usuario'];
		$contrasenaseurcom = $useroptions[0]['seurcom_contra'];
		$ccc               = $useroptions[0]['ccc'];
		$int_ccc           = $useroptions[0]['int_ccc'];
		$franquicia        = $useroptions[0]['franquicia'];

		if ( ( 'ES' === $pais ) || ( 'PT' === $pais ) || ( 'AD' === $pais ) ) {
			$datos = array(
				0 => $usuario_cit,
				$contra_cit,
				$poblacion,
				$postal,
			);
			if ( 'ERROR' === seur_check_city( $datos ) ) {
				echo "<hr><b><font color='#e53920'>";
				echo '<br>Código Postal y Población no se han encontrado en Nomenclator de SEUR.<br>Consulte Nomenclator y ajuste Población y Postal.<br></font>';
				echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . esc_html( $postal ) . ' - ' . esc_html( $poblacion );
				return;
			}
		} else {
			$postal    = '08023';
			$poblacion = 'BARCELONA';
		}
		$registros   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}seur_svpr WHERE ser= %d and pro= %d", $servicio, $producto ) );
		$descripcion = '';
		foreach ( $registros as $registro_dato ) {
			$mensaje .= '<br>' . $registro_dato->descripcion;
		}
		if ( strlen( $mensaje ) < 1 ) {
			$mensaje .= '<br>Servicio Producto sin IDENTIFICAR';
		}
		$consulta   = '<REG><USUARIO>' . $usuarioseurcom . '</USUARIO>' .
			'<PASSWORD>' . $contrasenaseurcom . '</PASSWORD>' .
			'<NOM_POBLA_DEST>' . $poblacion . '</NOM_POBLA_DEST>' .
			'<Peso>' . $kilos . '</Peso>' .
			'<CODIGO_POSTAL_DEST>' . $postal . '</CODIGO_POSTAL_DEST>' .
			'<CodContableRemitente>' . $ccc . '-' . $franquicia . '</CodContableRemitente>' .
			'<PesoVolumen>0</PesoVolumen>' .
			'<Bultos>' . $bultos . '</Bultos>' .
			'<CodServ>' . $servicio . '</CodServ>' .
			'<CodProd>' . $producto . '</CodProd>' .
			'<COD_IDIOMA>ES</COD_IDIOMA>' .
			'<CodPaisIso>' . $pais . '</CodPaisIso>' .
			'</REG>';
		$sc_options = array(
			'connection_timeout' => 30,
		);
		$cliente    = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );
		$parametros = array(
			'in0' => $consulta,
		);
		$respuesta  = $cliente->tarificacionPrivadaStr( $parametros );

		if ( empty( $respuesta->out ) || ( isset( $respuesta->error ) && ! empty( $respuesta->error ) ) ) {
			echo '<div class="error notice"><p>';
			esc_html_e( 'There was an error getting rate.', 'seur' );
			echo '</p>';
			echo '</div>';
			return;
		} else {
			$xml         = simplexml_load_string( $respuesta->out );
			$lineasantes = ( $xml->attributes()->NUM );
			$lineas      = (int) $lineasantes - 1;
			$total       = 0;

			/*
				Para debug
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
		}
	}
	?>
</form>
