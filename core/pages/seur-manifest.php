<?php
/**
 * SEUR Manifest
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR download data
 *
 * @param WP_Post $post Post dats sent.
 */
function seur_donwload_data( $post ) {
	global $wpdb;
	?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'SEUR Manifest', 'seur' ); ?></h1>
	<?php esc_html_e( 'Generate the cargo manifest of your deliveries and print two copies for the carrier.', 'seur' ); ?>
	<form method='post'  name='formulario' width='100%'>

	<?php
	if ( isset( $_POST['fechadesde'] ) && ( ! isset( $_POST['seur_manifest_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_manifest_nonce_field'] ) ), 'seur_manifest_action' ) ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;

	} else {
		$usuario           = get_option( 'seur_cit_usuario_field' );
		$clave             = get_option( 'seur_cit_contra_field' );
		$nif               = get_option( 'seur_nif_field' );
		$codigo_franquicia = get_option( 'seur_franquicia_field' );
		$fecha_desde       = '';
		$hora_desde        = '';

		if ( isset( $_POST['fechadesde'] ) ) {

			$date        = str_replace( '/', '', sanitize_text_field( wp_unslash( $_POST['fechadesde'] ) ) );
			$sc_options  = array( 'connection_timeout' => 30 );
			$soap_client = new SoapClient( 'http://cit.seur.com/CIT-war/services/DetalleBultoPDFWebService?wsdl', $sc_options );

			// Manifiesto con fecha.
			if ( $date > 0 ) {

				// Si los datos no tienen la longitud esperada, error.
				if ( $date < 8 || ( isset( $_POST['horadesde'] ) && strlen( sanitize_text_field( wp_unslash( $_POST['horadesde'] ) ) ) < 6 ) ) {
					echo 'Fecha/Hora no están en el formato adecuado</div>';
					return;
				}

				$fecha = substr( $date, 4, 4 ) . '-' . substr( $date, 0, 2 ) . '-' . substr( $date, 2, 2 ) . 'T' . substr( sanitize_text_field( wp_unslash( $_POST['horadesde'] ) ), 0, 2 ) . ':' . substr( sanitize_text_field( wp_unslash( $_POST['horadesde'] ) ), 2, 2 ) . ':' . substr( sanitize_text_field( wp_unslash( $_POST['horadesde'] ) ), 4, 2 ) . ':000Z';

				$parametros = array(
					'in0' => $nif,
					'in1' => $codigo_franquicia,
					'in2' => $fecha,
					'in3' => $usuario,
					'in4' => $clave,
				);
				$respuesta  = $soap_client->generacionPDFDetallePorFecha( $parametros );
			} else {

				// Manifiesto sin fecha.
				$parametros = array(
					'in0' => $nif,
					'in1' => $codigo_franquicia,
					'in2' => 'S', // Separar por CCC.
					'in3' => $usuario,
					'in4' => $clave,
				);
				$respuesta  = $soap_client->generacionPDFDetalleNoFechaSepCCC( $parametros );
			}

			$log = new WC_Logger();
			$log->add( 'seur', '$respuesta:' . print_r( $respuesta, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

			$pdf       = base64_decode( $respuesta->out ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			$file_name = 'manifiesto_' . date( 'd-m-Y' ) . '.pdf'; // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			$path      = SEUR_UPLOADS_MANIFEST_PATH . '/' . $file_name;
			file_put_contents( $path, $pdf ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

			$download_file = SEUR_UPLOADS_MANIFEST_URL . '/' . $file_name;
			echo '<a href="' . esc_url( $download_file ) . '" target="_blank" class="button" download>' . esc_html__( ' Open Manifest ', 'seur' ) . '</a><br />';
			// echo '$date: ' . esc_html( $date ) . '<br />';
			// echo '$fecha: ' . esc_html( $fecha ) . '<br />';
		} else {
			?>
		<div class="wp-filter">
			<label>
				<span class="screen-reader-text"><?php esc_html_e( 'From Date', 'seur' ); ?></span>
				<input id="datepicker" type='text' name='fechadesde' class="wp-filter-search" placeholder="<?php esc_html_e( 'From Date', 'seur' ); ?>" value=''>
			</label>
			<input type='hidden' name='horadesde' value='000000'>
			<?php wp_nonce_field( 'seur_manifest_action', 'seur_manifest_nonce_field' ); ?>
			<label>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Submit', 'seur' ); ?>">
			</label>
		</div>
		<p class="description"><?php esc_html_e( "If you don't have shipments created other days, you don't need to enter a date", 'seur' ); ?></p
			<?php
		}
		?>
</div>
		<?php
	}

}
