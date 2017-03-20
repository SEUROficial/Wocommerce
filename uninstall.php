<?php

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! current_user_can( 'activate_plugins' ) ) exit();

global $wpdb;

// remove options added by SEUR Plugin

$options = array(
    'seur_after_get_label_field',
    'seur_preaviso_notificar_field',
    'seur_reparto_notificar_field',
    'seur_tipo_notificacion_field',
    'seur_manana_desde_field',
    'seur_manana_hasta_field',
    'seur_tarde_desde_field',
    'seur_tarde_hasta_field',
    'seur_tipo_etiqueta_field',
    'seur_aduana_origen_field',
    'seur_aduana_destino_field',
    'seur_tipo_mercancia_field',
    'seur_valor_declarado_field',
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
    'seur_add_advanced_settings_field_pre'
    );

foreach ( $options as $option ){

    delete_option( $option );

}

