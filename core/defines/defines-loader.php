<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $seur_uploads_dir           = get_option( 'seur_uploads_dir'          );
    $seur_uploads_url           = get_option( 'seur_uploads_url'          );
    $seur_uploads_dir_labels    = get_option( 'seur_uploads_dir_labels'   );
    $seur_uploads_dir_manifest  = get_option( 'seur_uploads_dir_manifest' );
    $seur_uploads_url_labels    = get_option( 'seur_uploads_url_labels'   );
    $seur_uploads_url__manifest = get_option( 'seur_uploads_url_manifest' );

    define( 'SEUR_UPLOADS_PATH',            $seur_uploads_dir                   );
    define( 'SEUR_UPLOADS_URL',             $seur_uploads_url                   );
    define( 'SEUR_UPLOADS_LABELS_PATH',     $seur_uploads_dir_labels            );
    define( 'SEUR_UPLOADS_LABELS_URL',      $seur_uploads_url_labels            );
    define( 'SEUR_UPLOADS_MANIFEST_PATH',   $seur_uploads_dir_manifest          );
    define( 'SEUR_UPLOADS_MANIFEST_URL',    $seur_uploads_url__manifest         );
    define( 'SEUR_PLUGIN_RECO',             'seur_reco'                         );
    define( 'SEUR_PLUGIN_ECB',              'seur_ecb'                          );
    define( 'SEUR_PLUGIN_SVPR',             'seur_svpr'                         );
    define( 'SEUR_TBL_SCR',                 'seur_custom_rates'                 );
    define( 'SEUR_CLASSES',                 SEUR_PLUGIN_PATH . 'core/classes/'  );

    if ( ! defined('SEUR_DEBUG') ) define ('SEUR_DEBUG', false);

    /*** Another defin [ defined( 'SEUR_WOOCOMMERCE_PART' ) ] defined here => /core/woocommerce/seur-woocommerce.php ***/