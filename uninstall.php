<?php
/**
 * Remove
 *
 * Remove File.
 *
 * @package SEUR
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! current_user_can( 'activate_plugins' ) ) {
	exit();
}

global $wpdb, $wp_filesystem;

// Removing SEUR folders and downloader file.

$seur_uploads = get_option( 'seur_uploads_dir' );
$wp_filesystem->rmdir( $seur_uploads, true );
$seur_download_file = get_site_option( 'seur_download_file_path' );
wp_delete_file( $seur_download_file );

// remove options added by SEUR Plugin.

$options = array(
	'seur_after_get_label_field',
	'seur_preaviso_notificar_field',
	'seur_reparto_notificar_field',
	'seur_tipo_notificacion_field',
	'seur_tipo_etiqueta_field',
	'seur_aduana_origen_field',
	'seur_aduana_destino_field',
	'seur_tipo_mercancia_field',
	'seur_id_mercancia_field',
	'seur_descripcion_field',
	'seur_nif_field',
	'seur_empresa_field',
	'seur_viatipo_field',
	'seur_vianombre_field',
	'seur_vianumero_field',
	'seur_escalera_field',
	'seur_piso_field',
	'seur_puerta_field',
	'seur_postal_field',
	'seur_poblacion_field',
	'seur_provincia_field',
	'seur_pais_field',
	'seur_telefono_field',
	'seur_email_field',
	'seur_contacto_nombre_field',
	'seur_contacto_apellidos_field',
	'seur_cit_codigo_field',
	'seur_cit_usuario_field',
	'seur_cit_contra_field',
	'seur_ccc_field',
	'seur_franquicia_field',
	'seur_seurcom_usuario_field',
	'seur_seurcom_contra_field',
	'seur-official-version',
	'seur_db_version',
	'seur_table_version',
	'seur_uploads_dir',
	'seur_uploads_url',
	'seur_uploads_dir_labels',
	'seur_uploads_dir_manifest',
	'seur_uploads_url_labels',
	'seur_uploads_url_manifest',
	'seur_add_advanced_settings_field_pre',
	'seur_download_file_url',
	'seur_download_file_path',
	'seur_pass_for_download',
	'seur_date_localizador',
	'seur_num_localizador',
	'seur_rates_type_field',
	'seur_bc2_max_price_field',
	'seur_10e_max_price_field',
	'seur_10ef_max_price_field',
	'seur_13e_max_price_field',
	'seur_13f_max_price_field',
	'seur_48h_max_price_field',
	'seur_72h_max_price_field',
	'seur_cit_max_price_field',
	'seur_2SHOP_max_price_field',
	'seur_bc2_custom_name_field',
	'seur_10e_custom_name_field',
	'seur_10ef_custom_name_field',
	'seur_13e_custom_name_field',
	'seur_13f_custom_name_field',
	'seur_48h_custom_name_field',
	'seur_72h_custom_name_field',
	'seur_cit_custom_name_field',
	'seur_2SHOP_custom_name_field',
	'seur_activate_free_shipping_field',
	'seur_deactivate_free_shipping_field',
	'seur_courier_int_aereo_paqueteria_custom_name_field',
	'seur_courier_int_aereo_documentos_custom_name_field',
	'seur_netexpress_int_terrestre_custom_name_field',
	'seur_courier_int_aereo_paqueteria_max_price_field',
	'seur_courier_int_aereo_documentos_max_price_field',
	'seur_netexpress_int_terrestre_max_price_field',
);

foreach ( $options as $option ) {
	delete_option( $option );
}

// Drop tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}seur_reco" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}seur_ecb" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}seur_svpr" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}seur_custom_rates" );

// remove seur_labels post type.
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ('seur_labels');" );
$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => 'labels-product' ) );
// Delete orphan relationships.
$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

// Delete orphan terms.
$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

// Delete orphan term meta.
if ( ! empty( $wpdb->termmeta ) ) {
	$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
}
