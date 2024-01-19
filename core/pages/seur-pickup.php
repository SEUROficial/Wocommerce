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

	?>
	<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Collection', 'seur' ); ?></h1>
	<hr class="wp-header-end">
	<?php esc_html_e( 'Generate an order for us to pick up your customers orders.', 'seur' ); ?>
	<form method="post"  name="formulario" width="100%">
		<?php
		if ( ! isset( $_POST['type'] ) ) {
			wp_nonce_field( 'seur_pickup_action', 'seur_pickup_nonce_field' );
		} else {
			if ( ! isset( $_POST['seur_pickup_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_pickup_nonce_field'] ) ), 'seur_pickup_action' ) ) {
				print 'Sorry, your nonce did not verify.';
				exit;
			} else {

				$tipo            = sanitize_text_field( wp_unslash( $_POST['type'] ) );
				$md              = sanitize_text_field( wp_unslash( $_POST['Md'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$mh              = sanitize_text_field( wp_unslash( $_POST['Mh'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$td              = sanitize_text_field( wp_unslash( $_POST['Td'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$th              = sanitize_text_field( wp_unslash( $_POST['Th'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
				$now             = date( 'Y-m-d' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				$ref             = $tipo . seur()->get_option( 'seur_accountnumber_field' ) . gmdate( 'ymdHis' ); // . gmdate( 'm' ) . gmdate( 'd' ) . gmdate( 'H' ) . gmdate( 'i' ) . gmdate( 's' );
				$data            = array();
				$data['type']    = $tipo; // cold, normal.
				$data['date']    = $now; // '2021-09-08-12:00:00.000'.
				$data['mfrom']   = $md; // '09:00:00'
				$data['mto']     = $mh; // '13:00:00'
				$data['efrom']   = $td; // '16:00:00'
				$data['eto']     = $th; // '19:00:00'
				$data['comment'] = ''; // 'ENVIO DE PRUEBA'
				$data['ref']     = $ref;
				$result          = seur_collections( $data );
				if ( $data ) {
					$collectionref = $result['data']['collectionRef'];
					$reference     = $result['data']['reference'];
					if ( strpos( $reference, 'normal' ) !== false ) {
						seur()->slog( 'Es recogida normal' );
						seur()->save_collection( $collectionref, 'normal' );
						seur()->save_reference( $reference, 'normal' );
						seur()->save_date_normal( seur()->today() );

					}
					if ( strpos( $reference, 'cold' ) !== false ) {
						seur()->slog( 'Es recogida frio' );
						seur()->slog( 'Es recogida normal' );
						seur()->save_collection( $collectionref, 'cold' );
						seur()->save_reference( $reference, 'cold' );
						seur()->save_date_cold( seur()->today() );
					}
					if ( seur()->log_is_acive() ) {
						seur()->slog( '$result: ' . print_r( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
						seur()->slog( '$collectionRef: ' . $collectionref );
						seur()->slog( '$reference: ' . $reference );
					}
				} else {
					if ( seur()->log_is_acive() ) {
						seur()->slog( '$result: Ha habido un problema' );
					}
				}
			}
		}

		/**
		 * Comprobar si tiene una recogida para hoy y mostrar sus situaciones
		 */

		$date              = seur()->today();
		$collection_normal = seur()->get_collection( 'normal' );
		$collection_cold   = seur()->get_collection( 'cold' );
		$reference_normal  = seur()->get_reference( 'normal' );
		$reference_cold    = seur()->get_reference( 'cold' );
		$date_normal       = seur()->get_date_normal();
		$date_cold         = seur()->get_date_cold();
		$normal            = false;
		$cold              = false;

		if ( $date === $date_normal ) {

			echo "<div style='color:#e53920;font-weight:bold; font-size:12px;'>";
			echo esc_html__( 'You have a collection today', 'seur' );
			echo '<br>';
			echo esc_html__( 'Reference: ', 'seur' ) . esc_html( $reference_normal );
			echo '<br>';
			echo esc_html__( 'Collection: ', 'seur' ) . esc_html( $collection_normal );
			echo '</div>';
			$normal = true;
		}
		if ( $date === $date_cold ) {

			echo "<div style='color:#e53920;font-weight:bold; font-size:12px;'>";
			echo esc_html__( 'You have a Cold collection today', 'seur' );
			echo '<br>';
			echo esc_html__( 'Cold Reference: ', 'seur' ) . esc_html( $reference_cold );
			echo '<br>';
			echo esc_html__( 'Cold Collection: ', 'seur' ) . esc_html( $collection_cold );
			echo '</div>';
			$cold = true;
		}

		if ( $date === $date_normal && $date === $date_cold ) {
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
							<td colspan="2"><?php esc_html_e( 'COLLECTION', 'seur' ); ?></div><hr></td>
						</tr>
						<tr>
							<td colspan="2"><?php esc_html_e( 'Select type.', 'seur' ); ?></div></td></tr>
						</tr>
						<tr>
							<td>
								<?php esc_html_e( 'Type:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
								<select id="manana_desde_type" name="type">
								<?php
								if ( ! $cold ) {
									?>
									<option value="cold"><?php esc_html_e( 'Cold Shipping', 'seur' ); ?></option>
									<?php
								}
								if ( ! $normal ) {
									?>
									<option value="normal"><?php esc_html_e( 'Normal Shipping', 'seur' ); ?></option>
									<?php
								}
								?>
								</select>
							</td>
						</td>
					</tr>
					<tr>
				<td colslpan="2"><br><?php esc_html_e( 'Enter a schedule for Collection', 'seur' ); ?><br />
				<?php esc_html_e( 'The minimum margin between each schedule has to be 2 hours.', 'seur' ); ?></div></td></tr>
			<tr>
			<td>
				<?php esc_html_e( 'Morning from:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
				<select id="manana_desde_type" name="Md">
				<option value="none"<?php if ( 'none' === $md ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>><?php esc_html_e( 'None', 'seur' ); ?></option>
				<option value="09:00:00"<?php if ( '09:00:00' === $md ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>09:00</option> 
				<option value="10:01:00"<?php if ( '10:01:00' === $md ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>10:00</option>
				<option value="11:02:00"<?php if ( '11:02:00' === $md ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>11:00</option>
				<option value="12:03:00"<?php if ( '12:03:00' === $md ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>12:00</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			<?php esc_html_e( 'to', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
			<select id="manana_hasta_type" name="Mh">
				<option value="none"<?php if ( 'none' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>><?php esc_html_e( 'None', 'seur' ); ?></option>
				<option value="11:00:00"<?php if ( '11:00:00' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>11:00</option>
				<option value="12:01:00"<?php if ( '12:01:00' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>12:00</option>
				<option value="13:02:00"<?php if ( '13:02:00' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>13:00</option>
				<option value="14:03:00"<?php if ( '14:03:00' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>14:00</option>
				<option value="15:04:00"<?php if ( '15:04:00' === $mh ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>15:00</option>
			</select>
			</td></tr><tr>
			<td><?php esc_html_e( 'Afternoon From:', 'seur' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select id="tarde_desde_type" name="Td">
				<option value="none"<?php if ( 'none' === $td ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>><?php esc_html_e( 'None', 'seur' ); ?></option>
				<option value="15:00:00"<?php if ( '15:00:00' === $td ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>15:00</option>
				<option value="16:01:00"<?php if ( '16:01:00' === $td ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>16:00</option>
				<option value="17:02:00"<?php if ( '17:02:00' === $td ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>17:00</option>
				<option value="18:03:00"<?php if ( '18:03:00' === $td ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>18:00</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			<?php esc_html_e( 'to', 'seur' ); ?>&nbsp;&nbsp;&nbsp;
			<select id="tarde_hasta_type" name="Th">
				<option value="none"<?php if ( 'none' === $th ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>><?php esc_html_e( 'None', 'seur' ); ?></option>
				<option value="17:00:00"<?php if ( '17:00:00' === $th ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>17:00</option>
				<option value="18:01:00"<?php if ( '18:01:00' === $th ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>18:00</option>
				<option value="19:02:00"<?php if ( '19:02:00' === $th ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>19:00</option>
				<option value="20:03:00"<?php if ( '20:03:00' === $th ) { echo ' selected'; } // phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>>20:00</option>
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
	if ( ! isset( $_POST['type'] ) ) {
		submit_button( __( 'Request', 'seur' ) );
		return;
	}
	?>
	</div> 
	<?php
}
