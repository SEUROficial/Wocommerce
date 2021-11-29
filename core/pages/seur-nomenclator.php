<?php
/**
 * SEUR Nomenclator
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR search nomenclator.
 *
 * @param WP_Post $post Post data.
 */
function seur_search_nomenclator( $post ) { ?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Nomenclator', 'seur' ); ?></h1>
	<?php
	if ( isset( $_POST['codigo_postal'] ) ) {
		?>
		<a href="admin.php?page=seur_search_nomenclator" class="page-title-action"><?php esc_html_e( 'New Search', 'seur' ); ?></a>
		<?php
	}
	?>
	<hr class="wp-header-end">
	<?php
	$seur_user = get_option( 'seur_seurcom_usuario_field' );
	$seur_pass = get_option( 'seur_seurcom_contra_field' );

	?>
	<?php esc_html_e( 'Check ZIP or city associated to Seur system.', 'seur' ); ?>
		<form method="post" name="formulario" width="100%">
		<?php
		if ( isset( $_POST['codigo_postal'] ) ) {
			if ( ! isset( $_POST['nomenclator_seur_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nomenclator_seur_nonce_field'] ) ), 'nomenclator_seur' ) ) {
				print 'Sorry, your nonce did not verify.';
				exit;
			} else {
				$unsafe_zipcode = trim( sanitize_text_field( wp_unslash( $_POST['codigo_postal'] ) ) );
				if ( ! $unsafe_zipcode ) {
					$safe_zipcode = '';
				} else {
					$safe_zipcode = sanitize_text_field( $unsafe_zipcode );
				}

				// Getting needed user data.
				$seur_user        = get_option( 'seur_seurcom_usuario_field' );
				$seur_pass        = get_option( 'seur_seurcom_contra_field' );
				$nombre_poblacion = '';
				$codigo_postal    = '';
				if ( isset( $_POST['nombre_poblacion'] ) ) {
					$nombre_poblacion = sanitize_text_field( wp_unslash( $_POST['nombre_poblacion'] ) );
				} else {
					$nombre_poblacion = '';
				}
				$sc_options = array(
					'connection_timeout' => 30,
					'exceptions'         => 0,
				);
				$seur_url   = 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl';
				if ( ! seur_check_url_exists( $seur_url ) ) {
					esc_html_e( 'There is a problem connecting to SEUR. Please try again later.', 'seur' );
				} else {
					$soap_client = new SoapClient( 'https://ws.seur.com/WSEcatalogoPublicos/servlet/XFireServlet/WSServiciosWebPublicos?wsdl', $sc_options );
					$param       = array(
						'in0' => '',
						'in1' => $nombre_poblacion,
						'in2' => $safe_zipcode,
						'in3' => '',
						'in4' => '',
						'in5' => $seur_user,
						'in6' => $seur_pass,
					);
					$seurdata    = $soap_client->infoPoblacionesCortoStr( $param );
					if ( is_soap_fault( $seurdata ) ) {
						trigger_error( 'SOAP Fault: (faultcode: { $seurdata->faultcode }, faultstring: { $seurdata->faultstring } )', E_USER_ERROR ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
					} else {
						$string_xml     = htmlspecialchars_decode( $seurdata->out );
						$strxml         = iconv( 'UTF-8', 'ISO-8859-1', $string_xml );
						$xml            = simplexml_load_string( $strxml );
						$howmanyresults = $xml->attributes()->NUM;
						?>
						<ul class="subsubsub">
							<li class="all">
							<?php
							seur_search_number_message_result( $howmanyresults )
							?>
							</li>
						</ul>
						<table class="wp-list-table widefat fixed striped pages">
							<thead>
								<tr>
									<td class="manage-column">
									<?php
										esc_html_e( 'POSTCODE', 'seur' );
									?>
									<td class="manage-column">
									<?php
										esc_html_e( 'CITY', 'seur' );
									?>
									</td>
									<td class="manage-column">
									<?php
										esc_html_e( 'STATE/PROVINCE', 'seur' );
									?>
									</td>
									</tr>
								</thead>
								<?php
								for ( $ele = 1; $ele <= $howmanyresults; $ele++ ) {
									$registro = 'REG' . $ele;
									echo '<tr><td><a href="https://www.google.es/maps/search/' . esc_html( $xml->$registro->NOM_POBLACION ) . ',+' . esc_html( $xml->$registro->NOM_PROVINCIA ) . '+' . esc_html( $xml->$registro->CODIGO_POSTAL ) . '+seur" target="_blank">' . esc_html( $xml->$registro->CODIGO_POSTAL ) . '</a>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
									echo '</td>';
									echo '<td>' . esc_html( $xml->$registro->NOM_POBLACION ) . '</td>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
									echo '<td>' . esc_html( $xml->$registro->NOM_PROVINCIA ) . '</td></tr>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
								}
								?>

					<tfoot>
						<tr>
							<td class="manage-column"><?php esc_html_e( 'POSTCODE', 'seur' ); ?></td>
							<td class="manage-column"><?php esc_html_e( 'CITY', 'seur' ); ?></td>
							<td class="manage-column"><?php esc_html_e( 'STATE/PROVINCE', 'seur' ); ?></td>
						</tr>
					</tfoot>
			</table>
						<?php

					}
				}
				?>
	</form>
	</div>
				<?php
			}
		} else { // Aun no esta establecido.
			?>
	<div class="wp-filter">
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'City', 'seur' ); ?></span>
			<input type='text' name='nombre_poblacion' class="wp-filter-search" placeholder="<?php esc_html_e( 'City', 'seur' ); ?>" value=''>
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Postal code', 'seur' ); ?></span>
			<input type='text' name='codigo_postal' class="wp-filter-search" placeholder="<?php esc_html_e( 'Postalcode', 'seur' ); ?>" value='' size="12">
		</label>
		<label>
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
		</label>
	</div>
			<?php wp_nonce_field( 'nomenclator_seur', 'nomenclator_seur_nonce_field' ); ?>
			<?php
		}
}
