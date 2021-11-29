<?php
/**
 * SEUR Pickup
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Pickup
 *
 * @param WP_Post $post Post Data.
 */
function seur_pickup( $post ) {

	// Declarando $wpdb global y usarlo para ejecutar una sentencia de consulta SQL.
	global $wpdb;

	// yy/mm/dd.
	$date          = date( 'y' ) . date( 'm' ) . date( 'd' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$bloquear      = '';
	$bultos        = '';
	$kilos         = '';
	$identificador = '';

	?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Collection', 'seur' ); ?></h1>
	<hr class="wp-header-end">
	<?php esc_html_e( 'Generate an order for us to pick up your customers orders.', 'seur' ); ?>
	<form method="post"  name="formulario" width="100%">
	<?php

	// Si no está seteado bultos, no ha cargado el formulario aún o ha creado recogida en actual carga.
	if ( ! isset( $_POST['bultos'] ) ) {

		// *****************************************
		// ** RECUPERAR LOS DATOS DEL COMERCIANTE **
		// *****************************************

		$user_data     = array();
		$advanced_data = array();
		$user_data     = seur_get_user_settings();
		$advanced_data = seur_get_advanced_settings();

		// User settings.

		$cit_pass           = $user_data[0]['cit_codigo'];
		$cit_user           = $user_data[0]['cit_usuario'];
		$cit_contra         = $user_data[0]['cit_contra'];
		$nif                = $user_data[0]['nif'];
		$franquicia         = $user_data[0]['franquicia'];
		$ccc                = $user_data[0]['ccc'];
		$int_ccc            = $user_data[0]['int_ccc'];
		$usercom            = $user_data[0]['seurcom_usuario'];
		$passcom            = $user_data[0]['seurcom_contra'];
		$empresa            = $user_data[0]['empresa'];
		$viatipo            = $user_data[0]['viatipo'];
		$vianombre          = $user_data[0]['vianombre'];
		$vianumero          = $user_data[0]['vianumero'];
		$escalera           = $user_data[0]['escalera'];
		$piso               = $user_data[0]['piso'];
		$puerta             = $user_data[0]['puerta'];
		$postalcode         = $user_data[0]['postalcode'];
		$poblacion          = $user_data[0]['poblacion'];
		$provincia          = $user_data[0]['provincia'];
		$pais               = $user_data[0]['pais'];
		$telefono           = $user_data[0]['telefono'];
		$email              = $user_data[0]['email'];
		$contacto_nombre    = $user_data[0]['contacto_nombre'];
		$contacto_apellidos = $user_data[0]['contacto_apellidos'];

		// Advanced User Settings.

		$usuarioseurcom    = $usercom;
		$contrasenaseurcom = $passcom;
		$md                = $manana_desde;
		$mh                = $manana_hasta;
		$td                = $tarde_desde;
		$th                = $tarde_hasta;

		echo '<form>';
		echo "<input type='hidden' name='usuarioseurcom' value='" . esc_textarea( $usercom ) . "'>";
		echo "<input type='hidden' name='contrasenaseurcom' value='" . esc_textarea( $passcom ) . "'>";
		echo "<input type='hidden' name='nif' value='" . esc_textarea( $nif ) . "'>";
		echo "<input type='hidden' name='ccc' value='" . esc_textarea( $ccc ) . "'>";
		echo "<input type='hidden' name='franquicia' value='" . esc_textarea( $franquicia ) . "'>";
		echo "<input type='hidden' name='empresa' value='" . esc_textarea( $empresa ) . "'>";
		echo "<input type='hidden' name='viatip' value='" . esc_textarea( $viatipo ) . "'>";
		echo "<input type='hidden' name='vianom' value='" . esc_textarea( $vianombre ) . "'>";
		echo "<input type='hidden' name='vianum' value='" . esc_textarea( $vianumero ) . "'>";
		echo "<input type='hidden' name='esc' value='" . esc_textarea( $escalera ) . "'>";
		echo "<input type='hidden' name='piso' value='" . esc_textarea( $piso ) . "'>";
		echo "<input type='hidden' name='puerta' value='" . esc_textarea( $puerta ) . "'>";
		echo "<input type='hidden' name='postal' value='" . esc_textarea( $postalcode ) . "'>";
		echo "<input type='hidden' name='poblacion' value='" . esc_textarea( $poblacion ) . "'>";
		echo "<input type='hidden' name='provincia' value='" . esc_textarea( $provincia ) . "'>";
		echo "<input type='hidden' name='pais' value='" . esc_textarea( $pais ) . "'>";
		echo "<input type='hidden' name='telefono' value='" . esc_textarea( $telefono ) . "'>";
		echo "<input type='hidden' name='email' value='" . esc_textarea( $email ) . "'>";
		echo "<input type='hidden' name='contacton' value='" . esc_textarea( $contacto_nombre ) . "'>";
		echo "<input type='hidden' name='contactoa' value='" . esc_textarea( $contacto_apellidos ) . "'>";
		echo "<input type='hidden' name='usuario' value='" . esc_textarea( $usercom ) . "'>";
		echo "<input type='hidden' name='contra' value='" . esc_textarea( $passcom ) . "'>";
		wp_nonce_field( 'seur_pickup_action', 'seur_pickup_nonce_field' );

	} else {

		if ( ! isset( $_POST['seur_pickup_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_pickup_nonce_field'] ) ), 'seur_pickup_action' ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;
		} else {

			$bultos            = sanitize_text_field( wp_unslash( $_POST['bultos'] ) );
			$kilos             = sanitize_text_field( wp_unslash( $_POST['kilos'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$md                = sanitize_text_field( wp_unslash( $_POST['Md'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$mh                = sanitize_text_field( wp_unslash( $_POST['Mh'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$td                = sanitize_text_field( wp_unslash( $_POST['Td'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$th                = sanitize_text_field( wp_unslash( $_POST['Th'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$bloquear          = 'readonly';
			$usuarioseurcom    = sanitize_text_field( wp_unslash( $_POST['usuarioseurcom'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$contrasenaseurcom = sanitize_text_field( wp_unslash( $_POST['contrasenaseurcom'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated

		}
	}

	/**
	 * Comprobar si tiene una recogida para hoy y mostrar sus situaciones
	 */
	$last_date     = get_option( 'seur_date_localizador' );
	$now           = date( 'y' ) . date( 'm' ) . date( 'd' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$identificador = get_option( 'seur_num_localizador' );

	if ( $last_date === $now ) {

		echo "<div style='color:#e53920;font-weight:bold; font-size:12px;'>";
		echo esc_html__( 'You have a collection for today', 'seur' );
		echo '<br>';
		echo esc_html__( 'IDENTIFIER: ', 'seur' ) . esc_html( $identificador );
		echo '</div>';

		// situaciones de la recogida.

		$sc_options = array(
			'connection_timeout' => 30,
		);

		$params = array(
			'in0' => $identificador,
			'in1' => '',
			'in2' => $usuarioseurcom,
			'in3' => $contrasenaseurcom,
		);

		// pedimos las etiquetas.
		$cliente   = new SoapClient( 'https://ws.seur.com/webseur/services/WSConsultaRecogidas?wsdl', $sc_options );
		$respuesta = $cliente->consultaDetallesRecogidasStr( $params );
		$xml       = simplexml_load_string( $respuesta->out );
		echo "<div style='color:#0074a2;font-weight:bold; font-size:12px;'>";
		echo '<HR>RECOGER EN:<BR>';
		echo esc_html( $xml->RECOGIDA->DONDE_NOMBRE ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		echo '<br>';
		echo esc_html( $xml->RECOGIDA->DONDE_VIA_NOMBRE ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		echo '<br>';
		echo esc_html( $xml->RECOGIDA->DONDE_POBLACION ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		echo '<br>';
		echo esc_html( $xml->RECOGIDA->DONDE_PROVINCIA ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		echo '<hr>';
		foreach ( $xml->RECOGIDA->SITUACIONES->SITUACION as $contenido ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			echo esc_html( $contenido->FECHA_SITUACION ) . ' '; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			echo esc_html( $contenido->DESCRIPCION_CLIENTE ) . '<br>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		}
		echo '</div>';
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
		<table width='50%'>
			<tr>
				<td colspan="2"><?php esc_html_e( 'COLLECTION', 'seur' ); ?></div><hr></td></tr>
		<tr><td colspan="2"><?php esc_html_e( 'Enter an approximate value for Bulk and Kilos.', 'seur' ); ?></div></td></tr>
		</tr>
	<tr>
	<td><?php esc_html_e( 'Bulk:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
	<?php
	if ( strlen( $bultos ) < 1 ) {
		$bultos = 1;
	}  if ( strlen( $kilos ) < 1 ) {
		$kilos = 1;}
	?>
	<input style="text-align:right" type="text" name="bultos" value="<?php echo esc_html( $bultos ); ?>" size="1" maxlength="3"  <?php echo esc_html( $bloquear ); ?> >
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php esc_html_e( 'Kilos:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
	<input style="text-align:right" type="text" name="kilos" value="<?php echo esc_html( $kilos ); ?>" size="1" maxlength="4"   <?php echo esc_html( $bloquear ); ?>>
	</td>
	</tr>
	<tr><td colslpan="2"><br><?php esc_html_e( 'Enter a schedule for Collection', 'seur' ); ?><br />
	<?php esc_html_e( 'The minimum margin between each schedule has to be 2 hours.', 'seur' ); ?></div></td></tr>
	<tr>
	<td><?php esc_html_e( 'Morning from:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
	<select id="manana_desde_type" name="Md">
		<option value="" 
		<?php
		if ( '' === $md ) {
			echo ' selected';
		}
		?>
		>
		<?php
		esc_html_e( 'None', 'seur' );
		?>
		</option>
		<option value="09:00" 
		<?php
		if ( '09:00' === $md ) {
			echo ' selected';
		}
		?>
		>09:00</option>
		<option value="10:00" 
		<?php
		if ( '10:00' === $md ) {
			echo ' selected';}
		?>
		>10:00</option>
		<option value="11:00" 
		<?php
		if ( '11:00' === $md ) {
			echo ' selected';}
		?>
		>11:00</option>
		<option value="12:00" 
		<?php
		if ( '12:00' === $md ) {
			echo ' selected';}
		?>
		>12:00</option>
	</select>
	&nbsp;&nbsp;&nbsp;
	<?php esc_html_e( 'to', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
	<select id="manana_hasta_type" name="Mh">
		<option value="" 
		<?php
		if ( '' === $mh ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'None', 'seur' ); ?></option>
		<option value="11:00" 
		<?php
		if ( '11:00' === $mh ) {
			echo ' selected';}
		?>
		>11:00</option>
		<option value="12:00" 
		<?php
		if ( '12:00' === $mh ) {
			echo ' selected';}
		?>
		>12:00</option>
		<option value="13:00" 
		<?php
		if ( '13:00' === $mh ) {
			echo ' selected';}
		?>
		>13:00</option>
		<option value="14:00" 
		<?php
		if ( '14:00' === $mh ) {
			echo ' selected';}
		?>
		>14:00</option>
	</select>
	</td></tr><tr>
	<td><?php esc_html_e( 'Afternoon From:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<select id="tarde_desde_type" name="Td">
		<option value="" 
		<?php
		if ( '' === $td ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'None', 'seur' ); ?></option>
		<option value="15:00" 
		<?php
		if ( '15:00' === $td ) {
			echo ' selected';}
		?>
		>15:00</option>
		<option value="16:00" 
		<?php
		if ( '16:00' === $td ) {
			echo ' selected';}
		?>
		>16:00</option>
		<option value="17:00" 
		<?php
		if ( '17:00' === $td ) {
			echo ' selected';}
		?>
		>17:00</option>
		<option value="18:00" 
		<?php
		if ( '18:00' === $td ) {
			echo ' selected';}
		?>
		>18:00</option>
	</select>
	&nbsp;&nbsp;&nbsp;
	<?php esc_html_e( 'to', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
	<select id="tarde_hasta_type" name="Th">
		<option value="" 
		<?php
		if ( '' === $th ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'None', 'seur' ); ?></option>
		<option value="17:00" 
		<?php
		if ( '17:00' === $th ) {
			echo ' selected';}
		?>
		>17:00</option>
		<option value="18:00" 
		<?php
		if ( '18:00' === $th ) {
			echo ' selected';}
		?>
		>18:00</option>
		<option value="19:00" 
		<?php
		if ( '19:00' === $th ) {
			echo ' selected';}
		?>
		>19:00</option>
		<option value="20:00" 
		<?php
		if ( '20:00' === $th ) {
			echo ' selected';}
		?>
		>20:00</option>
	</select>
	</td>
	</tr>
	<tr><td colslpan=2><br><?php esc_html_e( 'If the schedule is only of mornings, leave the afternoon schedules with null.', 'seur' ); ?>
		<br><?php esc_html_e( 'If schedule is only of afternoon leave the morning hours with null.', 'seur' ); ?>
		</div></td></tr>

	</tr>

	</table>
	</form>


	<?php

	// Si no está setado, boton Solicitar.
	if ( ! isset( $_POST['bultos'] ) ) {
		submit_button( __( 'Request', 'seur' ) );
		return;
	}

	// Retorno del boton solicitar.
	// Formamos la recogida.
	$trama_kilos = '1;1;1;' . sanitize_text_field( wp_unslash( $_POST['kilos'] ) ) . ';0'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated

	$datos_recogida = '' .
		'<recogida>' .
		'<usuario>' . sanitize_text_field( wp_unslash( $_POST['usuario'] ) ) . '</usuario>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<password>' . sanitize_text_field( wp_unslash( $_POST['contra'] ) ) . '</password>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<razonSocial>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['empresa'] ) ) ) . '</razonSocial>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<nombreEmpresa>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['empresa'] ) ) ) . '</nombreEmpresa>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<nombreContactoOrdenante>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contacton'] ) ) ) . '</nombreContactoOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<apellidosContactoOrdenante>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contactoa'] ) ) ) . '</apellidosContactoOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<prefijoTelefonoOrdenante>34</prefijoTelefonoOrdenante>' .
		'<telefonoOrdenante>' . sanitize_text_field( wp_unslash( $_POST['telefono'] ) ) . '</telefonoOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<prefijoFaxOrdenante></prefijoFaxOrdenante>' .
		'<faxOrdenante></faxOrdenante>' .
		'<nifOrdenante>' . sanitize_text_field( wp_unslash( $_POST['nif'] ) ) . '</nifOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<paisNifOrdenante>ES</paisNifOrdenante>' .
		'<mailOrdenante>' . sanitize_text_field( wp_unslash( $_POST['email'] ) ) . '</mailOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoViaOrdenante>' . sanitize_text_field( wp_unslash( $_POST['viatip'] ) ) . '</tipoViaOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<calleOrdenante>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['vianom'] ) ) ) . '</calleOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoNumeroOrdenante>N.</tipoNumeroOrdenante>' .
		'<numeroOrdenante>' . sanitize_text_field( wp_unslash( $_POST['vianum'] ) ) . '</numeroOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<escaleraOrdenante>' . sanitize_text_field( wp_unslash( $_POST['escalera'] ) ) . '</escaleraOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<pisoOrdenante>' . sanitize_text_field( wp_unslash( $_POST['piso'] ) ) . '</pisoOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<puertaOrdenante>' . sanitize_text_field( wp_unslash( $_POST['puerta'] ) ) . '</puertaOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<codigoPostalOrdenante>' . sanitize_text_field( wp_unslash( $_POST['postal'] ) ) . '</codigoPostalOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<poblacionOrdenante>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['poblacion'] ) ) ) . '</poblacionOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<provinciaOrdenante>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['provincia'] ) ) ) . '</provinciaOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<paisOrdenante>ES</paisOrdenante>' .

		'<diaRecogida>' . date( 'd' ) . '</diaRecogida>' . // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		'<mesRecogida>' . date( 'm' ) . '</mesRecogida>' . // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		'<anioRecogida>' . date( 'Y' ) . '</anioRecogida>' . // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		'<servicio>1</servicio>' .
		'<horaMananaDe>' . sanitize_text_field( wp_unslash( $_POST['Md'] ) ) . '</horaMananaDe>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<horaMananaA>' . sanitize_text_field( wp_unslash( $_POST['Mh'] ) ) . '</horaMananaA>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<numeroBultos>' . sanitize_text_field( wp_unslash( $_POST['bultos'] ) ) . '</numeroBultos>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<mercancia>2</mercancia>' .
		'<horaTardeDe>' . sanitize_text_field( wp_unslash( $_POST['Td'] ) ) . '</horaTardeDe>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<horaTardeA>' . sanitize_text_field( wp_unslash( $_POST['Th'] ) ) . '</horaTardeA>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoPorte>P</tipoPorte>' .
		'<observaciones></observaciones>' .
		'<tipoAviso>EMAIL</tipoAviso>' .
		'<idiomaContactoOrdenante>ES</idiomaContactoOrdenante>' .

		'<razonSocialDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['empresa'] ) ) ) . '</razonSocialDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<nombreContactoDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contacton'] ) ) ) . '</nombreContactoDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<apellidosContactoDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contactoa'] ) ) ) . '</apellidosContactoDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<telefonoDestino>' . sanitize_text_field( wp_unslash( $_POST['telefono'] ) ) . '</telefonoDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoViaDestino>' . sanitize_text_field( wp_unslash( $_POST['viatip'] ) ) . '</tipoViaDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<calleDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['vianom'] ) ) ) . '</calleDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoNumeroDestino>N.</tipoNumeroDestino>' .
		'<numeroDestino>' . sanitize_text_field( wp_unslash( $_POST['vianum'] ) ) . '</numeroDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<escaleraDestino>' . sanitize_text_field( wp_unslash( $_POST['escalera'] ) ) . '</escaleraDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<pisoDestino>' . sanitize_text_field( wp_unslash( $_POST['piso'] ) ) . '</pisoDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<puertaDestino>' . sanitize_text_field( wp_unslash( $_POST['puerta'] ) ) . '</puertaDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<codigoPostalDestino>' . sanitize_text_field( wp_unslash( $_POST['postal'] ) ) . '</codigoPostalDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<poblacionDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['poblacion'] ) ) ) . '</poblacionDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<provinciaDestino>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['provincia'] ) ) ) . '</provinciaDestino>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<paisDestino>ES</paisDestino>' .
		'<prefijoTelefonoDestino>34</prefijoTelefonoDestino>' .

		'<razonSocialOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['empresa'] ) ) ) . '</razonSocialOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<nombreContactoOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contacton'] ) ) ) . '</nombreContactoOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<apellidosContactoOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['contactoa'] ) ) ) . '</apellidosContactoOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<telefonoRecogidaOrigen>' . sanitize_text_field( wp_unslash( $_POST['telefono'] ) ) . '</telefonoRecogidaOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoViaOrigen>' . sanitize_text_field( wp_unslash( $_POST['viatip'] ) ) . '</tipoViaOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<calleOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['vianom'] ) ) ) . '</calleOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<tipoNumeroOrigen>N.</tipoNumeroOrigen>' .
		'<numeroOrigen>' . sanitize_text_field( wp_unslash( $_POST['vianum'] ) ) . '</numeroOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<escaleraOrigen>' . sanitize_text_field( wp_unslash( $_POST['escalera'] ) ) . '</escaleraOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<pisoOrigen>' . sanitize_text_field( wp_unslash( $_POST['piso'] ) ) . '</pisoOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<puertaOrigen>' . sanitize_text_field( wp_unslash( $_POST['puerta'] ) ) . '</puertaOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<codigoPostalOrigen>' . sanitize_text_field( wp_unslash( $_POST['postal'] ) ) . '</codigoPostalOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<poblacionOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['poblacion'] ) ) ) . '</poblacionOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<provinciaOrigen>' . seur_clean_data( sanitize_text_field( wp_unslash( $_POST['provincia'] ) ) ) . '</provinciaOrigen>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<paisOrigen>ES</paisOrigen>' .
		'<prefijoTelefonoOrigen>34</prefijoTelefonoOrigen>' .

		'<producto>2</producto>' .
		'<entregaSabado>N</entregaSabado>' .
		'<entregaNave>N</entregaNave>' .
		'<tipoEnvio>N</tipoEnvio>' .
		'<valorDeclarado>0</valorDeclarado>' .
		// "<listaBultos>1;1;1;1;1/</listaBultos>".

		'<listaBultos>' . esc_html( $trama_kilos ) . '/</listaBultos>' .
		'<cccOrdenante>' . sanitize_text_field( wp_unslash( $_POST['ccc'] ) ) . '-' . sanitize_text_field( wp_unslash( $_POST['franquicia'] ) ) . '</cccOrdenante>' . // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		'<numeroReferencia></numeroReferencia>' .
		'<ultimaRecogidaDia></ultimaRecogidaDia>' .
		'<nifOrigen></nifOrigen>' .
		'<paisNifOrigen></paisNifOrigen>' .
		'<aviso>N</aviso>' .
		'<cccDonde></cccDonde>' .
		'<cccAdonde></cccAdonde>' .
		'<tipoRecogida></tipoRecogida>' .
		'</recogida>';

		$sc_options = array(
			'connection_timeout' => 30,
		);

		$soap_client = new SoapClient( 'https://ws.seur.com/webseur/services/WSCrearRecogida?wsdl', $sc_options );
		$parametros  = array( 'in0' => $datos_recogida );
		$respuesta   = $soap_client->crearRecogida( $parametros );
		$xml         = simplexml_load_string( $respuesta->out );
		$codigo      = '';
		$codigo      = $xml->CODIGO; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		if ( strlen( $codigo ) > 1 ) {
			echo esc_html__( 'AN ERROR HAS OCCURRED', 'seur' ) . '</div>';
			echo esc_html( $xml->DESCRIPCION ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			echo "<hr><a href='javascript:javascript:history.go(-1)'>";
			echo "<img src='" . esc_html( SEUR_IMAGENES ) . '/volver.jpg' . "'></img>";
			echo '</a>';

			if ( defined( 'SEUR_DEBUG' ) && SEUR_DEBUG === true ) {
				?>

			<textarea rows="20" cols="40" style="border:none;">
					<?php
					echo esc_html( $datos_recogida );
					?>
			</textarea>

				<?php
			}

			return;
		} else {
			$locali_num = (string) $xml->LOCALIZADOR; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			echo esc_html__( 'The Collection have been created', 'seur' );
			echo '<br />';
			echo esc_html__( 'Identifier: ' ) . esc_html( $locali_num ) . '</div>';

			// Destruirmos la variable para que no pueda crear mas recogidas en esta vista actual.
			unset( $_POST['bultos'] );
			// Grabamos que se ha creado la recogida para accesos del día muestre situaciones.
			update_option( 'seur_date_localizador', $date );
			update_option( 'seur_num_localizador', $locali_num );
			if ( 1 !== $resultado ) {
				echo '</div>';
			}
			return;
		}

		?>
		</div> 
	<?php
}
