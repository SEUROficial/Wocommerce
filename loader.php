<?php
/**
 * Plugin Name: SEUR Oficial
 * Plugin URI: http://www.seur.com/
 * Description: Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way
 * Version: 1.6.0
 * Author: José Conti
 * Author URI: https://www.joseconti.com/
 * Tested up to: 5.5
 * WC requires at least: 3.0
 * WC tested up to: 4.3
 * Text Domain: seur
 * Domain Path: /languages/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Seur Official
 **/

define( 'SEUR_OFFICIAL_VERSION', '1.6.0' );
define( 'SEUR_DB_VERSION', '1.0.3' );
define( 'SEUR_TABLE_VERSION', '1.0.2' );
define( 'SEUR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEUR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SEUR_POST_UPDATE_URL', 'https://seur-woo.com/2020/08/18/nueva-version-seur-1-6-0/' );
// live.
define( 'SEUR_URL', 'https://api.seur.com/geolabel/api/shipment/addShipment' );
// test.
// define( 'SEUR_URL', 'https://apipre.seur.com/geolabel/api/shipment/addShipment' );.

/**
 * More defins here => /core/defines/defines-loader.php
 */


// Including Core and installer.

require_once SEUR_PLUGIN_PATH . 'core/loader-core.php';
require_once SEUR_PLUGIN_PATH . 'core/installer.php';

register_activation_hook( __FILE__, 'seur_create_tables_hook' );
register_activation_hook( __FILE__, 'seur_add_data_to_tables_hook' );
register_activation_hook( __FILE__, 'seur_create_upload_folder_hook' );
register_activation_hook( __FILE__, 'seur_add_avanced_settings_preset' );
register_activation_hook( __FILE__, 'seur_create_download_files' );

// SEUR Localization.

function seur_official_init() {
	load_plugin_textdomain( 'seur', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'seur_official_init' );

// SEUR Get Parent Page.
function seur_get_parent_page() {
	$seur_parent = basename( $_SERVER['SCRIPT_NAME'] );
	return $seur_parent;
}

// SEUR Redirect to Welcome/About Page.
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

function seur_add_notice_new_version() {

	$version = get_option( 'hide-new-version-seur-notice' );

	if ( $version !== SEUR_OFFICIAL_VERSION ) {
		if ( isset( $_REQUEST['seur-hide-new-version'] ) && 'hide-new-version-seur' === $_REQUEST['seur-hide-new-version'] ) {
			$nonce = $_REQUEST['_seur_hide_new_version_nonce'];
			if ( wp_verify_nonce( $nonce, 'seur_hide_new_version_nonce' ) ) {
				update_option( 'hide-new-version-seur-notice', SEUR_OFFICIAL_VERSION );
			}
		} else {
			?>
			<div id="message" class="updated woocommerce-message woocommerce-seur-messages">
				<a class="woocommerce-message-close notice-dismiss" style="top:0;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'seur-hide-new-version', 'hide-new-version-seur' ), 'seur_hide_new_version_nonce', '_seur_hide_new_version_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'woocommerce-seur' ); ?></a>
				<p>
					<?php echo esc_html__( 'SEUR has been updated to version', 'woocommerce-seur' ) . ' ' . esc_html( SEUR_OFFICIAL_VERSION ); ?>
				</p>
				<p>
					<?php printf( esc_html__( 'Discover the improvements that have been made in this version, and how to take advantage of them <a href="%s" target="_blank">here</a>', 'woocommerce-seur' ), esc_url( SEUR_POST_UPDATE_URL ) ); ?>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'seur_add_notice_new_version' );

function seur_notice_style() {
	wp_register_style( 'seur_notice_css', SEUR_PLUGIN_URL . 'assets/css/seur-notice.css', false, SEUR_OFFICIAL_VERSION );
	wp_enqueue_style( 'seur_notice_css' );
}
add_action( 'admin_enqueue_scripts', 'seur_notice_style' );
