<?php
/*
Plugin Name: SEUR Oficial
Plugin URI: http://www.seur.com/
Description: Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way
Version: 1.2.0
Author: José Conti
Author URI: https://www.joseconti.com/
Tested up to: 5.0.1
WC requires at least: 3.0
WC tested up to: 3.5
Text Domain: seur
Domain Path: /languages/
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

define( 'SEUR_OFFICIAL_VERSION',    '1.2.0'                     );
define( 'SEUR_DB_VERSION',          '1.0.3'                     );
define( 'SEUR_TABLE_VERSION',       '1.0.2'                     );
define( 'SEUR_PLUGIN_PATH',         plugin_dir_path( __FILE__ ) );
define( 'SEUR_PLUGIN_URL',          plugin_dir_url( __FILE__ )  );

 /**************************************************************/
 /**** More defins here => /core/defines/defines-loader.php ****/
 /**************************************************************/


// Including Core and installer

require_once( SEUR_PLUGIN_PATH . 'core/loader-core.php'					);
require_once( SEUR_PLUGIN_PATH . 'core/installer.php'					);
require_once( SEUR_PLUGIN_PATH . 'include/TGMPA/plugin-install.php'	    );

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

// SEUR Get Parent Page
function seur_get_parent_page() {
	$seur_parent = basename( $_SERVER['SCRIPT_NAME'] );
	return $seur_parent;
}

// SEUR Redirect to Welcome/About Page
function seur_welcome_splash() {
	$seur_parent = seur_get_parent_page();
	if ( get_option( 'seur-official-version' ) === SEUR_OFFICIAL_VERSION ) {
		return;
	} elseif ( 'update.php' === $seur_parent ) {
		return;
	} elseif ( 'update-core.php' === $seur_parent ) {
		return;
	} else {
		update_option( 'seur-official-version', SEUR_OFFICIAL_VERSION );
		$seurredirect = esc_url( admin_url( add_query_arg( array( 'page' => 'seur_about_page' ), 'admin.php' ) ) );
		wp_safe_redirect( $seurredirect );
		exit;
	}
}
add_action( 'admin_init', 'seur_welcome_splash', 1 );
