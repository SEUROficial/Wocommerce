<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_create_tables_hook(){
    global $wpdb;

    $charset_collate = '';

    $seur_db_version_saved = '';
    $seur_db_version_saved = get_option('seur_db_version');

    if ( !$seur_db_version_saved || ( version_compare( SEUR_DB_VERSION, $seur_db_version_saved, '>' ) ) ) {

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->base_prefix . "seur_reco";
        $sql = "CREATE TABLE " . $table_name . " (
            fecha varchar(6) NOT NULL,
            id varchar(15) NOT NULL,
            UNIQUE KEY `Clave` (`fecha`,`id`)
        ) $charset_collate;";

        dbDelta( $sql );


        $table_name = $wpdb->base_prefix . "seur_ecb";
        $sql = "CREATE TABLE " . $table_name . " (
            pedido varchar(15) NOT NULL,
            extension int(1) NOT NULL,
            fecha varchar(6) NOT NULL,
            PRIMARY KEY (`pedido`)
        ) $charset_collate;";

        dbDelta($sql);

        $table_name = $wpdb->base_prefix . "seur_svpr";

        $sql = "CREATE TABLE " . $table_name . " (
            ID bigint(20) unsigned NOT NULL auto_increment,
            ser varchar(3) NOT NULL,
            pro varchar(3) NOT NULL,
            descripcion varchar(50) NOT NULL,
            PRIMARY KEY (ID)
        ) $charset_collate;";

        dbDelta($sql);

        $table_name = $wpdb->base_prefix . "seur_custom_rates";

        $sql = "CREATE TABLE " . $table_name . " (
            ID bigint(20) unsigned NOT NULL auto_increment,
            country varchar(50) NOT NULL default '',
            state varchar(200) NOT NULL default '',
            postcode varchar(7) NOT NULL default '00000',
            minprice bigint(20) unsigned NOT NULL default '0',
            maxprice bigint(20) unsigned NOT NULL default '0',
            minweight bigint(20) unsigned NOT NULL default '0',
            maxweight bigint(20) unsigned NOT NULL default '0',
            rate varchar(200) NOT NULL default '',
            rateprice bigint(20) unsigned NOT NULL default '0',
            PRIMARY KEY (ID)
        ) $charset_collate;";

        dbDelta($sql);

        update_option('seur_db_version', SEUR_DB_VERSION );
    }
}

function seur_add_data_to_tables_hook(){
    global $wpdb;

    $seur_table_version_saved = '';
    $seur_table_version_saved = get_option('seur_table_version');

    if ( !$seur_table_version_saved || ( version_compare( SEUR_TABLE_VERSION, $seur_table_version_saved, '>' ) ) ) {
        $table_name = $wpdb->prefix . 'seur_svpr';

        $wpdb->insert(
            $table_name,
            array(
                'ser' => '1',
                'pro' => '2',
                'descripcion' => 'SEUR 24H ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '3',
                'pro' => '4',
                'descripcion' => 'SEUR 10 ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '9',
                'pro' => '2',
                'descripcion' => 'SEUR 13:30 ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '83',
                'pro' => '2',
                'descripcion' => 'SEUR 8:30 ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '31',
                'pro' => '2',
                'descripcion' => 'PARTICULARES 24H ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '13',
                'pro' => '2',
                'descripcion' => 'SEUR 72H ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '15',
                'pro' => '2',
                'descripcion' => 'SEUR 48H ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '17',
                'pro' => '2',
                'descripcion' => 'SEUR MARITIMO ESTANDAR',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '77',
                'pro' => '70',
                'descripcion' => 'CLASSIC INT TERRESTRE',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '19',
                'pro' => '70',
                'descripcion' => 'NETEXPRESS INT TERRESTRE',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '7',
                'pro' => '54',
                'descripcion' => 'COURIER INT AEREO DOCUMENTOS',
            )
        );
        $wpdb->insert(
            $table_name,
            array(
                'ser' => '7',
                'pro' => '108',
                'descripcion' => 'COURIER INT AEREO PAQUETERIA',
            )
        );

        $table_name = $wpdb->prefix . 'seur_custom_rates';

        $wpdb->insert(
            $table_name,
            array(
                'country'   => 'ES',
                'state'     => '*',
                'postcode'  => '*',
                'minprice'  => '0',
                'maxprice'  => '60',
                'minweight' => '0',
                'maxweight' => '1000',
                'rate'      => 'PARTICULARES 24H ESTANDAR',
                'rateprice' => '10'
                )
        );
        $wpdb->insert(
            $table_name,
            array(
                'country'   => 'ES',
                'state'     => '*',
                'postcode'  => '*',
                'minprice'  => '60',
                'maxprice'  => '9999999',
                'minweight' => '0',
                'maxweight' => '1000',
                'rate'      => 'PARTICULARES 24H ESTANDAR',
                'rateprice' => '0'
                )
        );

        update_option('seur_table_version', SEUR_TABLE_VERSION );
    }
}

function seur_create_random_string(){

    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $string = '';
    $max = strlen($characters) - 1;
    $random_string_length = 10;
    for ($i = 0; $i < $random_string_length; $i++) {
        $string .= $characters[mt_rand(0, $max)];
    }

    return $string;
 }

function seur_create_upload_folder_hook(){

    $seur_upload_dir = get_option( 'seur_uploads_dir' );

    if ( $seur_upload_dir && file_exists( $seur_upload_dir ) ){
        return;
    } else {
        $upload_dir                 = wp_upload_dir();
        $random_string              = seur_create_random_string();
        $seur_uploads_name_prefix   = 'seur_uploads_';
        $seur_uploads_name          = $seur_uploads_name_prefix . $random_string;
        $seur_upload_dir            = $upload_dir['basedir'] . '/' . $seur_uploads_name;
        $seur_upload_dir_labels     = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/labels';
        $seur_upload_dir_manifest   = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/manifests';
        $seur_upload_url            = $upload_dir['baseurl'] . '/' . $seur_uploads_name;
        $seur_upload_url_labels     = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/labels';
        $seur_upload_url_manifest   = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/manifests';

        if ( !file_exists( $seur_upload_dir ) ) {
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

function seur_add_avanced_settings_preset(){

    $seur_add = get_option( 'seur_add_advanced_settings_field_pre' );

    if ( $seur_add != '1' ) {
        update_option( 'seur_after_get_label_field',            'shipping'              );
        update_option( 'seur_preaviso_notificar_field',         NULL                    );
        update_option( 'seur_reparto_notificar_field',          NULL                    );
        update_option( 'seur_tipo_notificacion_field',          'EMAIL'                 );
        update_option( 'seur_manana_desde_field',               '09:00'                 );
        update_option( 'seur_manana_hasta_field',               '14:00'                 );
        update_option( 'seur_tarde_desde_field',                '16:00'                 );
        update_option( 'seur_tarde_hasta_field',                '19:00'                 );
        update_option( 'seur_tipo_etiqueta_field',              'PDF'                   );
        update_option( 'seur_aduana_origen_field',              'D'                     );
        update_option( 'seur_aduana_destino_field',             'D'                     );
        update_option( 'seur_tipo_mercancia_field',             'C'                     );
        update_option( 'seur_valor_declarado_field',            '50'                    );
        update_option( 'seur_id_mercancia_field',               '400'                   );
        update_option( 'seur_descripcion_field',                'MANUFACTURAS DIVERSAS' );
        update_option( 'seur_add_advanced_settings_field_pre',  '1'                     );
    }
}