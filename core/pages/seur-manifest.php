<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_donwload_data( $post ) {
	global $wpdb;
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('SEUR Manifest', SEUR_TEXTDOMAIN ); ?></h1>
	<form method='post'  name='formulario' width='100%'>

<?php
	if ( isset( $_POST['fechadesde'] ) && ( ! isset( $_POST['seur_manifest_nonce_field'] )  || ! wp_verify_nonce( $_POST['seur_manifest_nonce_field'], 'seur_manifest_action' ) ) ) {
		print 'Sorry, your nonce did not verify.';
		exit;

	} else {

		$usuario   		   = get_option( 'seur_cit_usuario_field' );
		$clave    		   = get_option( 'seur_cit_contra_field'  );
		$nif    		   = get_option( 'seur_nif_field'         );
		$codigo_franquicia = get_option( 'seur_franquicia_field'  );
		$fecha_desde	   = '';
		$hora_desde		   = '';


		if( isset( $_POST['fechadesde'] ) )
		{

			$date = str_replace("/", "", $_POST['fechadesde'] );
			$sc_options  = array( 'connection_timeout' => 30 );
			$soap_client = new SoapClient('http://cit.seur.com/CIT-war/services/DetalleBultoPDFWebService?wsdl', $sc_options);

			//Manifiesto con fecha
			if ( $date > 0 )
			{

				// Si los datos no tienen la longitud esperada, error
				if ( $date < 8 or strlen( $_POST['horadesde'] ) < 6 )
				{
					echo "Fecha/Hora no están en el formato adecuado</div>";
					return;
				}

				$fecha =  substr( $date, 4, 4 ) . '-' . substr( $date, 2, 2 ) . '-' . substr( $date, 0, 2 ) . 'T' . substr( $_POST['horadesde'], 0, 2 ) . ':' . substr( $_POST['horadesde'], 2, 2 ) . ':' . substr( $_POST['horadesde'], 4, 2 ) . ':000Z';

				$parametros = array(
					'in0' => $nif,
					'in1' => $codigo_franquicia,
					'in2' => $fecha,
					'in3' => $usuario,
					'in4' => $clave
				);
				$respuesta = $soap_client->generacionPDFDetallePorFecha($parametros);
			}

			else
			{

				// Manifiesto sin fecha
				$parametros = array(
					'in0' => $nif,
					'in1' =>$codigo_franquicia,
					'in2' => 'S',// Separar por CCC
					'in3' => $usuario,
					'in4' => $clave
				);
				$respuesta = $soap_client->generacionPDFDetalleNoFechaSepCCC($parametros);
			}

			// Respuesta del servicio
			$error = strpos( $respuesta->out, 'USUARIO' );
			if ($error!=0)
			{
				echo $respuesta->out;
				echo '</div>';
				return;
			}

			$nohaydatos = strpos( $respuesta->out, 'RECUPERAR' );
			if ( $nohaydatos !=0 )
			{
				echo $respuesta->out;
				echo '</div>';
				return;
			}

			$pdf  		= base64_decode( $respuesta->out );
			$file_name	= 'manifiesto_' . date('d-m-Y') . '.pdf';
			$path  		= SEUR_UPLOADS_MANIFEST_PATH . '/' . $file_name;
			file_put_contents( $path, $pdf );

			$download_file = SEUR_UPLOADS_MANIFEST_URL . '/' . $file_name;
			echo '<a href="' . $download_file . '" target="_blank">' . __('Open Manifest', SEUR_TEXTDOMAIN ) . '</a>';


		}
		else
		{
?>
		<div class="wp-filter">
			<label>
				<span class="screen-reader-text"><?php _e( 'From Date', SEUR_TEXTDOMAIN ); ?></span>
				<input id="datepicker" type='text' name='fechadesde' class="wp-filter-search" placeholder="<?php _e( 'From Date', SEUR_TEXTDOMAIN ); ?>" value=''>
			</label>
			<input type='hidden' name='horadesde' value='000000'>
			<?php wp_nonce_field( 'seur_manifest_action', 'seur_manifest_nonce_field' ); ?>
			<label>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Submit', SEUR_TEXTDOMAIN ); ?>">
			</label>
		</div>
		<p class="description"><?php _e("If you don't have shipments created other days, you don't need to enter a date", SEUR_TEXTDOMAIN ); ?></p>
 <?php } ?>
</div>
<?php	}

}
?>