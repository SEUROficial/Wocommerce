<?php
/**
 * SEUR Advanced Settings.
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR active geolabel
 */
function seur_activate_geolabel_field() {
	?>
	<input type="checkbox" class="js-switch-geolabel" title="<?php esc_html_e( 'Activate GeoLabel', 'seur' ); ?>" name="seur_activate_geolabel_field" value="1" <?php checked( 1, seur()->get_option( 'seur_activate_geolabel_field' ), true ); ?>/>
	<?php
}

/**
 * SEUR active local pickup
 */
function seur_activate_local_pickup_field() {
	?>
	<input type="checkbox" class="js-switch-pickup" title="<?php esc_html_e( 'Activate Local Pickup', 'seur' ); ?>" name="seur_activate_local_pickup_field" value="1" <?php checked( 1, seur()->get_option( 'seur_activate_local_pickup_field' ), true ); ?>/>
	<?php
}

/**
 * SEUR active free shipping
 */
function seur_activate_free_shipping_field() {
	?>
	<input type="checkbox" class="js-switch-free-shipping" title="<?php esc_html_e( 'Show WooCommerce Free Shipping at Checkout', 'seur' ); ?>" name="seur_activate_free_shipping_field" value="1" <?php checked( 1, seur()->get_option( 'seur_activate_free_shipping_field' ), true ); ?>/>
	<?php
}

/**
 * SEUR Google maps API
 */
function seur_google_maps_api_field() {
	?>
	<input title="<?php esc_html_e( 'Google Maps API Key', 'seur' ); ?>" type="text" name="seur_google_maps_api_field" value="<?php echo esc_html( seur()->get_option( 'seur_google_maps_api_field' ) ); ?>" size="40" />
	<?php
}

/**
 * SEUR after get label
 */
function seur_after_get_label_field() {
	$option = seur()->get_option( 'seur_after_get_label_field' );
	?>
	<select id="notification_type" name="seur_after_get_label_field">
		<option value="shipping"
		<?php
		if ( 'shipping' === $option ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'Mark as Shipping', 'seur' ); ?></option>
		<option value="complete"
		<?php
		if ( 'complete' === $option ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'Mark as Complete', 'seur' ); ?></option>
	</select>
	<?php
}

/**
 * SEUR preaviso notificar
 */
function seur_preaviso_notificar_field() {
	?>
	<input type="checkbox" class="js-switch-preavisonotificar" title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" name="seur_preaviso_notificar_field" value="1" <?php checked( 1, seur()->get_option( 'seur_preaviso_notificar_field' ), true ); ?>/>
	<?php
}

/**
 * SEUR reparto notificar
 */
function seur_reparto_notificar_field() {
	?>
	<input type="checkbox" class="js-switch-repartonotificar" title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" name="seur_reparto_notificar_field" value="1" <?php checked( 1, seur()->get_option( 'seur_reparto_notificar_field' ), true ); ?>/>
	<?php
}

/**
 * SEUR tipo notificacion
 */
function seur_tipo_notificacion_field() {

	$option = seur()->get_option( 'seur_tipo_notificacion_field' );
	?>
	<select id="notification_type" name="seur_tipo_notificacion_field">
		<option value="SMS"
		<?php
		if ( 'SMS' === $option ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'SMS (this option has an extra cost)', 'seur' ); ?></option>
		<option value="EMAIL"
		<?php
		if ( 'EMAIL' === $option ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'Email', 'seur' ); ?></option>
		<option value="both"
		<?php
		if ( 'both' === $option ) {
			echo ' selected';}
		?>
		><?php esc_html_e( 'Both (this option has an extra cost)', 'seur' ); ?></option>
	</select>
	<?php
}

/**
 * SEUR tipo etiquetas
 */
function seur_tipo_etiqueta_field() {

	$option = seur()->get_option( 'seur_tipo_etiqueta_field' );
	?>
	<select id="label_type" name="seur_tipo_etiqueta_field">
		<option value="PDF"
		<?php
		if ( 'PDF' === $option ) {
			echo ' selected';}
		?>
		>PDF</option>
		<option value="TERMICA"
		<?php
		if ( 'TERMICA' === $option ) {
			echo ' selected';}
		?>
		>TERMICA</option>
	</select>
	<?php
}

/**
 * SEUR aaduana origen
 */
function seur_aduana_origen_field() {

	$option = seur()->get_option( 'seur_aduana_origen_field' );
	?>
	<select id="seur_aduana_origen_type" name="seur_aduana_origen_field">
		<option value="D"
		<?php
		if ( 'D' === $option ) {
			echo ' selected';}
		?>
		>D</option>
		<option value="F"
		<?php
		if ( 'F' === $option ) {
			echo ' selected';}
		?>
		>F</option>

	</select>
	<?php
}

/**
 * SEUR aduana destino
 */
function seur_aduana_destino_field() {

	$option = seur()->get_option( 'seur_aduana_destino_field' );
	?>
	<select id="seur_aduana_destino_type" name="seur_aduana_destino_field">
		<option value="D" <?php if ( $option === 'D') echo ' selected'; ?>>D</option>
		<option value="F" <?php if ( $option === 'F') echo ' selected'; ?>>F</option>
	</select>
	<?php
}

/**
 * SEUR tipo mercancia
 */
function seur_tipo_mercancia_field() {

	$option = seur()->get_option( 'seur_tipo_mercancia_field' );
	?>
	<select id="mercancia_type" name="seur_tipo_mercancia_field">
	<option value="C" <?php if ( $option === 'C') {echo ' selected';} ?>><?php esc_html_e( 'C: Commercial', 'seur' ); ?></option>
		<option value="D" <?php if ( $option === 'D') echo ' selected'; ?>><?php esc_html_e( 'D: Documents', 'seur' ); ?></option>
		<option value="N" <?php if ( $option === 'N') echo ' selected'; ?>><?php esc_html_e( 'N: No Commercial', 'seur' ); ?></option>
		<option value="S" <?php if ( $option === 'S') echo ' selected'; ?>><?php esc_html_e( 'S: Envelopes', 'seur' ); ?></option>
	</select>
	<?php
}

/**
 * SEUR ID mercancia
 */
function seur_id_mercancia_field() {
	?>
	<input title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" type="text" name="seur_id_mercancia_field" value="<?php echo esc_html( seur()->get_option( 'seur_id_mercancia_field' ) ); ?>" size="40" />
	<?php
}

/**
 * SEUR descripción
 */
function seur_descripcion_field() {
	?>
	<input title="<?php esc_html_e( 'SEUR field description', 'seur' ); ?>" type="text" name="seur_descripcion_field" value="<?php echo esc_html( seur()->get_option( 'seur_descripcion_field' ) ); ?>" size="40" />
	<?php
}

/**
 * SEUR display advanced settings panel
 */
function display_seur_advanced_settings_panel_fields() {

	add_settings_section( 'seur-advanced-settings-section', null, null, 'seur-advanced-settings-options' );
	add_settings_field( 'seur_activate_geolabel_field', esc_html__( 'Activate GeoLabel', 'seur' ), 'seur_activate_geolabel_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_activate_free_shipping_field', esc_html__( 'Show WooCommerce Free Shipping at Checkout (by default SEUR hide the Free Shipping)', 'seur' ), 'seur_activate_free_shipping_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_after_get_label_field', esc_html__( 'What to do after get order label', 'seur' ), 'seur_after_get_label_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_activate_local_pickup_field', esc_html__( 'Activate Local Pickup', 'seur' ), 'seur_activate_local_pickup_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_google_maps_api_field', esc_html__( 'Google Maps API Key', 'seur' ), 'seur_google_maps_api_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_preaviso_notificar_field', esc_html__( 'Notify collection', 'seur' ), 'seur_preaviso_notificar_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_reparto_notificar_field', esc_html__( 'Notify distribution', 'seur' ), 'seur_reparto_notificar_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_tipo_notificacion_field', esc_html__( 'Notifications by SMS or Email', 'seur' ), 'seur_tipo_notificacion_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_tipo_etiqueta_field', esc_html__( 'Type of label', 'seur' ), 'seur_tipo_etiqueta_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_aduana_origen_field', esc_html__( 'Customs of origin', 'seur' ), 'seur_aduana_origen_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_aduana_destino_field', esc_html__( 'Customs of destination', 'seur' ), 'seur_aduana_destino_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_tipo_mercancia_field', esc_html__( 'Type of goods', 'seur' ), 'seur_tipo_mercancia_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_id_mercancia_field', esc_html__( 'ID of goods', 'seur' ), 'seur_id_mercancia_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
	add_settings_field( 'seur_descripcion_field', esc_html__( 'International description', 'seur' ), 'seur_descripcion_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );

	// register all setings.
	register_setting( 'seur-advanced-settings-section', 'seur_activate_geolabel_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_activate_free_shipping_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_preaviso_notificar_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_activate_local_pickup_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_google_maps_api_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_after_get_label_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_preaviso_notificar_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_reparto_notificar_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_tipo_notificacion_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_tipo_etiqueta_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_aduana_origen_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_aduana_destino_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_tipo_mercancia_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_id_mercancia_field' );
	register_setting( 'seur-advanced-settings-section', 'seur_descripcion_field' );

}
add_action( 'admin_init', 'display_seur_advanced_settings_panel_fields' );
