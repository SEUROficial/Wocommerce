<?php
/*
Plugin Name: SEUR Oficial
Plugin URI: http://www.seur.com/
Description: Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way
Version: 1.0.0
Author: José Conti
Author URI: https://www.joseconti.com/
Tested up to: 4.9.4
WC requires at least: 3.0
WC tested up to: 3.3
Text Domain: seur
Domain Path: /languages/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

define( 'SEUR_OFFICIAL_VERSION',    '1.0.0'                 );
define( 'SEUR_DB_VERSION',          '1.0.3'                     );
define( 'SEUR_TABLE_VERSION',       '1.0.0'                     );
define( 'SEUR_PLUGIN_PATH',         plugin_dir_path( __FILE__ ) );
define( 'SEUR_PLUGIN_URL',          plugin_dir_url( __FILE__ )  );

 /**************************************************************/
 /**** More defins here => /core/defines/defines-loader.php ****/
 /**************************************************************/


// Including Core and installer

require_once( SEUR_PLUGIN_PATH . 'core/loader-core.php'					);
require_once( SEUR_PLUGIN_PATH . 'core/installer.php'					);
require_once( SEUR_PLUGIN_PATH . 'include/TGMPA/plugin-install.php'	);

register_activation_hook( __FILE__, 'seur_create_tables_hook'           );
register_activation_hook( __FILE__, 'seur_add_data_to_tables_hook'      );
register_activation_hook( __FILE__, 'seur_create_upload_folder_hook'    );
register_activation_hook( __FILE__, 'seur_add_avanced_settings_preset'  );
register_activation_hook( __FILE__, 'seur_create_download_files'        );

// SEUR Localization

function seur_official_init() {
    load_plugin_textdomain( 'seur', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('init', 'seur_official_init');