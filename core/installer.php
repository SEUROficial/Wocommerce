<?php
/**
 * Seur Installer
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Seur Create Tables Hook
 */
function seur_create_tables_hook() {
	global $wpdb;

	$charset_collate       = '';
	$seur_db_version_saved = '';
	$seur_db_version_saved = get_option( 'seur_db_version' );

	if ( $seur_db_version_saved && '1.0.3' !== $seur_db_version_saved && ( '1.0.3' === SEUR_DB_VERSION ) ) {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'seur_custom_rates';
		$sql        = 'CREATE TABLE ' . $table_name . " (
			ID bigint(20) unsigned NOT NULL auto_increment,
			type varchar(50) NOT NULL default 'price',
			country varchar(50) NOT NULL default '',
			state varchar(200) NOT NULL default '',
			postcode varchar(7) NOT NULL default '00000',
			minprice decimal(20,2) unsigned NOT NULL default '0.00',
			maxprice decimal(20,2) unsigned NOT NULL default '0.00',
			minweight decimal(20,2) unsigned NOT NULL default '0.00',
			maxweight decimal(20,2) unsigned NOT NULL default '0.00',
			rate varchar(200) NOT NULL default '',
			rateprice decimal(20,2) unsigned NOT NULL default '0.00',
			PRIMARY KEY (ID)
		) $charset_collate;";
				dbDelta( $sql );
				update_option( 'seur_db_version', SEUR_DB_VERSION );
	}

	if ( ! $seur_db_version_saved ) {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'seur_svpr';

		$sql = 'CREATE TABLE ' . $table_name . " (
			ID bigint(20) unsigned NOT NULL auto_increment,
			ser varchar(3) NOT NULL,
			pro varchar(3) NOT NULL,
			descripcion varchar(50) NOT NULL,
			tipo varchar(50) NOT NULL,
			PRIMARY KEY (ID)
		) $charset_collate;";

		dbDelta( $sql );

		$table_name = $wpdb->prefix . 'seur_custom_rates';

		$sql = 'CREATE TABLE ' . $table_name . " (
			ID bigint(20) unsigned NOT NULL auto_increment,
			type varchar(50) NOT NULL default 'price',
			country varchar(50) NOT NULL default '',
			state varchar(200) NOT NULL default '',
			postcode varchar(7) NOT NULL default '00000',
			minprice decimal(20,2) unsigned NOT NULL default '0.00',
			maxprice decimal(20,2) unsigned NOT NULL default '0.00',
			minweight decimal(20,2) unsigned NOT NULL default '0.00',
			maxweight decimal(20,2) unsigned NOT NULL default '0.00',
			rate varchar(200) NOT NULL default '',
			rateprice decimal(20,2) unsigned NOT NULL default '0.00',
			PRIMARY KEY (ID)
		) $charset_collate;";

		dbDelta( $sql );

		update_option( 'seur_db_version', SEUR_DB_VERSION );
	}
}

/**
 * Seur Add Date to Table Hook.
 */
function seur_add_data_to_tables_hook() {
	global $wpdb;

	$seur_table_version_saved = '';
	$seur_table_version_saved = get_option( 'seur_table_version' );

	if ( ! empty( $seur_table_version_saved ) && '1.0.1' === $seur_table_version_saved ) {

		$table_name = $wpdb->prefix . 'seur_svpr';

		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '108',
				'descripcion' => 'COURIER INT AEREO PAQUETERIA',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '54',
				'descripcion' => 'COURIER INT AEREO DOCUMENTOS',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '19',
				'pro'         => '70',
				'descripcion' => 'NETEXPRESS INT TERRESTRE',
				'tipo'        => 'ESTANDAR',
			)
		);

		update_option( 'seur_table_version', SEUR_TABLE_VERSION );
	}

	if ( ! empty( $seur_table_version_saved ) && '1.0.0' === $seur_table_version_saved ) {

		$table_name = $wpdb->prefix . 'seur_svpr';

		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '1',
				'pro'         => '48',
				'descripcion' => 'SEUR 2SHOP',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '108',
				'descripcion' => 'COURIER INT AEREO PAQUETERIA',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '54',
				'descripcion' => 'COURIER INT AEREO DOCUMENTOS',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '19',
				'pro'         => '70',
				'descripcion' => 'NETEXPRESS INT TERRESTRE',
				'tipo'        => 'ESTANDAR',
			)
		);

		update_option( 'seur_table_version', SEUR_TABLE_VERSION );
	}

	if ( ! $seur_table_version_saved || '' === $seur_table_version_saved ) {

		$table_name = $wpdb->prefix . 'seur_svpr';

		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '31',
				'pro'         => '2',
				'descripcion' => 'B2C Estándar',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '3',
				'pro'         => '2',
				'descripcion' => 'SEUR 10 Estándar',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '3',
				'pro'         => '18',
				'descripcion' => 'SEUR 10 Frío',
				'tipo'        => 'FRIO',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '9',
				'pro'         => '2',
				'descripcion' => 'SEUR 13:30 Estándar',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '9',
				'pro'         => '18',
				'descripcion' => 'SEUR 13:30 Frío',
				'tipo'        => 'FRIO',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '15',
				'pro'         => '2',
				'descripcion' => 'SEUR 48H Estándar',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '13',
				'pro'         => '2',
				'descripcion' => 'SEUR 72H Estándar',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '77',
				'pro'         => '70',
				'descripcion' => 'Classic Internacional Terrestre',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '1',
				'pro'         => '48',
				'descripcion' => 'SEUR 2SHOP',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '108',
				'descripcion' => 'COURIER INT AEREO PAQUETERIA',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '7',
				'pro'         => '54',
				'descripcion' => 'COURIER INT AEREO DOCUMENTOS',
				'tipo'        => 'ESTANDAR',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'ser'         => '19',
				'pro'         => '70',
				'descripcion' => 'NETEXPRESS INT TERRESTRE',
				'tipo'        => 'ESTANDAR',
			)
		);

		$table_name = $wpdb->prefix . 'seur_custom_rates';

		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '60',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '10',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '60',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'PT',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '60',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '10',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'PT',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '60',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'AD',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '60',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '10',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'AD',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '60',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'PM',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '100',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '15',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'PM',
				'postcode'  => '*',
				'minprice'  => '100',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'GC',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '200',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '35',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'GC',
				'postcode'  => '*',
				'minprice'  => '200',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'CE',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '300',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '40',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'CE',
				'postcode'  => '*',
				'minprice'  => '300',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'ML',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '300',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '40',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => 'ES',
				'state'     => 'ML',
				'postcode'  => '*',
				'minprice'  => '300',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'B2C Estándar',
				'rateprice' => '0',
			)
		);
		$wpdb->insert(
			$table_name,
			array(
				'type'      => 'price',
				'country'   => '*',
				'state'     => '*',
				'postcode'  => '*',
				'minprice'  => '0',
				'maxprice'  => '9999999',
				'minweight' => '0',
				'maxweight' => '1000',
				'rate'      => 'Classic Internacional Terrestre',
				'rateprice' => '15',
			)
		);

		update_option( 'seur_table_version', SEUR_TABLE_VERSION );
	}
}

/**
 * Seur Create Random String.
 */
function seur_create_random_string() {

	$characters           = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$string               = '';
	$max                  = strlen( $characters ) - 1;
	$random_string_length = 10;
	for ( $i = 0; $i < $random_string_length; $i++ ) {
		$string .= $characters[ wp_rand( 0, $max ) ];
	}
	return $string;
}

/**
 * Seur Create upload flder Hook.
 */
function seur_create_upload_folder_hook() {

	$seur_upload_dir = get_option( 'seur_uploads_dir' );

	if ( $seur_upload_dir && file_exists( $seur_upload_dir ) ) {
		return;
	} else {
		$upload_dir               = wp_upload_dir();
		$random_string            = seur_create_random_string();
		$seur_uploads_name_prefix = 'seur_uploads_';
		$seur_uploads_name        = $seur_uploads_name_prefix . $random_string;
		$seur_upload_dir          = $upload_dir['basedir'] . '/' . $seur_uploads_name;
		$seur_upload_dir_labels   = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/labels';
		$seur_upload_dir_manifest = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/manifests';
		$seur_upload_url          = $upload_dir['baseurl'] . '/' . $seur_uploads_name;
		$seur_upload_url_labels   = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/labels';
		$seur_upload_url_manifest = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/manifests';

		if ( ! file_exists( $seur_upload_dir ) ) {
			wp_mkdir_p( $seur_upload_dir );
			wp_mkdir_p( $seur_upload_dir_labels );
			wp_mkdir_p( $seur_upload_dir_manifest );
			update_option( 'seur_uploads_dir', $seur_upload_dir );
			update_option( 'seur_uploads_url', $seur_upload_url );
			update_option( 'seur_uploads_dir_labels', $seur_upload_dir_labels );
			update_option( 'seur_uploads_dir_manifest', $seur_upload_dir_manifest );
			update_option( 'seur_uploads_url_labels', $seur_upload_url_labels );
			update_option( 'seur_uploads_url_manifest', $seur_upload_url_manifest );
		}
	}
}

/**
 * Seur add avanced settings preset.
 */
function seur_add_avanced_settings_preset() {

	$seur_add = get_option( 'seur_add_advanced_settings_field_pre' );

	if ( '1' === $seur_add ) {
		update_option( 'seur_add_advanced_settings_field_pre', '2' );
	}
	if ( ! $seur_add ) {
		update_option( 'seur_after_get_label_field', 'shipping' );
		update_option( 'seur_preaviso_notificar_field', null );
		update_option( 'seur_reparto_notificar_field', null );
		update_option( 'seur_tipo_notificacion_field', 'EMAIL' );
		update_option( 'seur_tipo_etiqueta_field', 'PDF' );
		update_option( 'seur_aduana_origen_field', 'D' );
		update_option( 'seur_aduana_destino_field', 'D' );
		update_option( 'seur_tipo_mercancia_field', 'C' );
		update_option( 'seur_id_mercancia_field', '400' );
		update_option( 'seur_descripcion_field', 'MANUFACTURAS DIVERSAS' );
		update_option( 'seur_add_advanced_settings_field_pre', '1' );
	}
}
