<?php
/**
 * Custom Name Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="container">
	<br>
	<p><?php esc_html_e( 'Custom Names for Seur Rates', 'seur' ); ?></p>
	<hr>
	<?php
	if ( ! isset( $_POST['seur_custom_name_rates_post'] ) ) {
		$seur_bc2_custom_name                          = '';
		$seur_10e_custom_name                          = '';
		$seur_10ef_custom_name                         = '';
		$seur_13e_custom_name                          = '';
		$seur_13f_custom_name                          = '';
		$seur_48h_custom_name                          = '';
		$seur_72h_custom_name                          = '';
		$seur_cit_custom_name                          = '';
		$seur_2shop_custom_name                        = '';
		$seur_courier_int_aereo_paqueteria             = '';
		$seur_courier_int_aereo_documentos             = '';
		$seur_netexpress_int_terrestre                 = '';
		$seur_bc2_custom_name                          = get_option( 'seur_bc2_custom_name_field' );
		$seur_10e_custom_name                          = get_option( 'seur_10e_custom_name_field' );
		$seur_10ef_custom_name                         = get_option( 'seur_10ef_custom_name_field' );
		$seur_13e_custom_name                          = get_option( 'seur_13e_custom_name_field' );
		$seur_13f_custom_name                          = get_option( 'seur_13f_custom_name_field' );
		$seur_48h_custom_name                          = get_option( 'seur_48h_custom_name_field' );
		$seur_72h_custom_name                          = get_option( 'seur_72h_custom_name_field' );
		$seur_cit_custom_name                          = get_option( 'seur_cit_custom_name_field' );
		$seur_2shop_custom_name                        = get_option( 'seur_2SHOP_custom_name_field' );
		$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
		$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
		$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );

		?>

		<div class="content-loader">
			<form method="post" action="admin.php?page=seur_rates_prices&tab=custom_rates_name">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">B2C Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>B2C Estándar" type="text" name="seur_bc2_custom_name_field" value="
								<?php
								if ( $seur_bc2_custom_name ) {
									echo esc_html( $seur_bc2_custom_name );
								}
								?>
							" size="40"></td>
						</tr>

						<tr>
							<th scope="row">SEUR 10 Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 10 Estándar" type="text" name="seur_10e_custom_name_field" value="
								<?php
								if ( $seur_10e_custom_name ) {
									echo esc_html( $seur_10e_custom_name );
								}
								?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 10 Frío</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 10 Frío" type="text" name="seur_10ef_custom_name_field" value="
							<?php
							if ( $seur_10ef_custom_name ) {
								echo esc_html( $seur_10ef_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 13:30 Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 13:30 Estándar" type="text" name="seur_13e_custom_name_field" value="
							<?php
							if ( $seur_13e_custom_name ) {
								echo esc_html( $seur_13e_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 13:30 Frío</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 13:30 Frío" type="text" name="seur_13f_custom_name_field" value="
							<?php
							if ( $seur_13f_custom_name ) {
								echo esc_html( $seur_13f_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 48H Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 48H Estándar" type="text" name="seur_48h_custom_name_field" value="
							<?php
							if ( $seur_48h_custom_name ) {
								echo esc_html( $seur_48h_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 72H Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 72H Estándar" type="text" name="seur_72h_custom_name_field" value="
							<?php
							if ( $seur_72h_custom_name ) {
								echo esc_html( $seur_72h_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">Classic Internacional Terrestre</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>Classic Internacional Terrestre" type="text" name="seur_cit_custom_name_field" value="
							<?php
							if ( $seur_cit_custom_name ) {
								echo esc_html( $seur_cit_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 2SHOP</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 2SHOP" type="text" name="seur_2SHOP_custom_name_field" value="
							<?php
							if ( $seur_2shop_custom_name ) {
								echo esc_html( $seur_2shop_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">COURIER INT AEREO PAQUETERIA</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>COURIER INT AEREO PAQUETERIA" type="text" name="seur_courier_int_aereo_paqueteria_custom_name_field" value="
							<?php
							if ( $seur_courier_int_aereo_paqueteria_custom_name ) {
								echo esc_html( $seur_courier_int_aereo_paqueteria_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">COURIER INT AEREO DOCUMENTOS</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>COURIER INT AEREO DOCUMENTOS" type="text" name="seur_courier_int_aereo_documentos_custom_name_field" value="
							<?php
							if ( $seur_courier_int_aereo_documentos_custom_name ) {
								echo esc_html( $seur_courier_int_aereo_documentos_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">NETEXPRESS INT TERRESTRE</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>NETEXPRESS INT TERRESTRE" type="text" name="seur_netexpress_int_terrestre_custom_name_field" value="
							<?php
							if ( $seur_netexpress_int_terrestre_custom_name ) {
								echo esc_html( $seur_netexpress_int_terrestre_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<input type="hidden" name="seur_custom_name_rates_post" value="true" >
					<?php wp_nonce_field( 'seur_custom_name_rates', 'seur_custom_name_rates_nonce_field' ); ?>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Update Options', 'seur' ); ?>"></p>
			</form>
		</div>
			<?php
	} else {
		if ( isset( $_POST['seur_custom_name_rates_post'] ) && ( ! isset( $_POST['seur_custom_name_rates_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_custom_name_rates_nonce_field'] ) ), 'seur_custom_name_rates' ) ) ) {
			print 'Sorry, your nonce did not verify.';
			exit;

		} else {
			$seur_bc2_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_bc2_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_10e_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_10e_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_10ef_custom_name                         = sanitize_text_field( wp_unslash( $_POST['seur_10ef_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_13e_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_13e_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_13f_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_13f_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_48h_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_48h_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_72h_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_72h_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_cit_custom_name                          = sanitize_text_field( wp_unslash( $_POST['seur_cit_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_2shop_custom_name                        = sanitize_text_field( wp_unslash( $_POST['seur_2SHOP_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_courier_int_aereo_paqueteria_custom_name = sanitize_text_field( wp_unslash( $_POST['seur_courier_int_aereo_paqueteria_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_courier_int_aereo_documentos_custom_name = sanitize_text_field( wp_unslash( $_POST['seur_courier_int_aereo_documentos_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$seur_netexpress_int_terrestre_custom_name     = sanitize_text_field( wp_unslash( $_POST['seur_netexpress_int_terrestre_custom_name_field'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			update_option( 'seur_bc2_custom_name_field', $seur_bc2_custom_name );
			update_option( 'seur_10e_custom_name_field', $seur_10e_custom_name );
			update_option( 'seur_10ef_custom_name_field', $seur_10ef_custom_name );
			update_option( 'seur_13e_custom_name_field', $seur_13e_custom_name );
			update_option( 'seur_13f_custom_name_field', $seur_13f_custom_name );
			update_option( 'seur_48h_custom_name_field', $seur_48h_custom_name );
			update_option( 'seur_72h_custom_name_field', $seur_72h_custom_name );
			update_option( 'seur_cit_custom_name_field', $seur_cit_custom_name );
			update_option( 'seur_2SHOP_custom_name_field', $seur_2shop_custom_name );
			update_option( 'seur_courier_int_aereo_paqueteria_custom_name_field', $seur_courier_int_aereo_paqueteria_custom_name );
			update_option( 'seur_courier_int_aereo_documentos_custom_name_field', $seur_courier_int_aereo_documentos_custom_name );
			update_option( 'seur_netexpress_int_terrestre_custom_name_field', $seur_netexpress_int_terrestre_custom_name );
			$seur_bc2_custom_name                          = get_option( 'seur_bc2_custom_name_field' );
			$seur_10e_custom_name                          = get_option( 'seur_10e_custom_name_field' );
			$seur_10ef_custom_name                         = get_option( 'seur_10ef_custom_name_field' );
			$seur_13e_custom_name                          = get_option( 'seur_13e_custom_name_field' );
			$seur_13f_custom_name                          = get_option( 'seur_13f_custom_name_field' );
			$seur_48h_custom_name                          = get_option( 'seur_48h_custom_name_field' );
			$seur_72h_custom_name                          = get_option( 'seur_72h_custom_name_field' );
			$seur_cit_custom_name                          = get_option( 'seur_cit_custom_name_field' );
			$seur_2shop_custom_name                        = get_option( 'seur_2SHOP_custom_name_field' );
			$seur_courier_int_aereo_paqueteria_custom_name = get_option( 'seur_courier_int_aereo_paqueteria_custom_name_field' );
			$seur_courier_int_aereo_documentos_custom_name = get_option( 'seur_courier_int_aereo_documentos_custom_name_field' );
			$seur_netexpress_int_terrestre_custom_name     = get_option( 'seur_netexpress_int_terrestre_custom_name_field' );
			?>

		<div class="content-loader">
			<form method="post" action="admin.php?page=seur_rates_prices&tab=custom_rates_name">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">B2C Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> B2C Estándar" type="text" name="seur_bc2_custom_name_field" value="
						<?php
						if ( $seur_bc2_custom_name ) {
							echo esc_html( $seur_bc2_custom_name );
						}
						?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 10 Estándar</th>

							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 10 Estándar" type="text" name="seur_10e_custom_name_field" value="
							<?php
							if ( $seur_10e_custom_name ) {
								echo esc_html( $seur_10e_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 10 Frío</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 10 Frío" type="text" name="seur_10ef_custom_name_field" value="
							<?php
							if ( $seur_10ef_custom_name ) {
								echo esc_html( $seur_10ef_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 13:30 Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 13:30 Estándar" type="text" name="seur_13e_custom_name_field" value="
							<?php
							if ( $seur_13e_custom_name ) {
								echo esc_html( $seur_13e_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 13:30 Frío</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 13:30 Frío" type="text" name="seur_13f_custom_name_field" value="
							<?php
							if ( $seur_13f_custom_name ) {
								echo esc_html( $seur_13f_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 48H Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 48H Estándar" type="text" name="seur_48h_custom_name_field" value="
							<?php
							if ( $seur_48h_custom_name ) {
								echo esc_html( $seur_48h_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 72H Estándar</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> SEUR 72H Estándar" type="text" name="seur_72h_custom_name_field" value="
							<?php
							if ( $seur_72h_custom_name ) {
								echo esc_html( $seur_72h_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">Classic Internacional Terrestre</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?> Classic Internacional Terrestre" type="text" name="seur_cit_custom_name_field" value="
							<?php
							if ( $seur_cit_custom_name ) {
								echo esc_html( $seur_cit_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">SEUR 2SHOP</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>SEUR 2SHOP" type="text" name="seur_2SHOP_custom_name_field" value="
							<?php
							if ( $seur_2shop_custom_name ) {
								echo esc_html( $seur_2shop_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">COURIER INT AEREO PAQUETERIA</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>COURIER INT AEREO PAQUETERIA" type="text" name="seur_courier_int_aereo_paqueteria_custom_name_field" value="
							<?php
							if ( $seur_courier_int_aereo_paqueteria_custom_name ) {
								echo esc_html( $seur_courier_int_aereo_paqueteria_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">COURIER INT AEREO DOCUMENTOS</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>COURIER INT AEREO DOCUMENTOS" type="text" name="seur_courier_int_aereo_documentos_custom_name_field" value="
							<?php
							if ( $seur_courier_int_aereo_documentos_custom_name ) {
								echo esc_html( $seur_courier_int_aereo_documentos_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<tr>
							<th scope="row">NETEXPRESS INT TERRESTRE</th>
							<td><input title="<?php esc_html_e( 'Custom Name for ', 'seur' ); ?>NETEXPRESS INT TERRESTRE" type="text" name="seur_netexpress_int_terrestre_custom_name_field" value="
							<?php
							if ( $seur_netexpress_int_terrestre_custom_name ) {
								echo esc_html( $seur_netexpress_int_terrestre_custom_name );
							}
							?>
							" size="40"></td>
						</tr>
						<input type="hidden" name="seur_custom_name_rates_post" value="true" >
						<?php wp_nonce_field( 'seur_custom_name_rates', 'seur_custom_name_rates_nonce_field' ); ?>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Update Options', 'seur' ); ?>"></p>
			</form>
		</div>
				<?php
		}
	}
	?>
	</div>
