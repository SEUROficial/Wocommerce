<?php
/**
 * Plugin Name: SEUR Oficial
 * Plugin URI: http://www.seur.com/
 * Description: Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way
 * Version: 2.2.9
 * Author: SEUR Oficial
 * Author URI: http://www.seur.com/
 * Tested up to: 6.2
 * WC requires at least: 3.0
 * WC tested up to: 7.4
 * Text Domain: seur
 * Domain Path: /languages/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Seur Official
 **/

define( 'SEUR_OFFICIAL_VERSION', '2.2.9' );
define( 'SEUR_DB_VERSION', '1.0.4' );
define( 'SEUR_TABLE_VERSION', '1.0.4' );

define( 'SEUR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEUR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SEUR_DATA_PATH', SEUR_PLUGIN_PATH . 'data/' );
define( 'SEUR_POST_UPDATE_URL', 'https://seur-woo.com/2023/01/12/nueva-version-seur-2-1-x/' );

define( 'SEUR_TEST_API_ADDRESS', 'https://servicios.apipre.seur.io/' );
define( 'SEUR_LIVE_API_ADDRESS', 'https://servicios.api.seur.io/' );

define( 'SEUR_TOKEN', 'pic_token' );
define( 'SEUR_COLLECTIONS', 'pic/v1/collections' );
define( 'SEUR_API_TRACKING', 'pic/v1/tracking-services/simplified' );

define( 'SEUR_API_CITIES', 'pic/v1/cities' );
define( 'SEUR_API_BREXIT_INV', 'pic/v1/brexit/invoices' );
define( 'SEUR_API_BREXIT_TARIF', 'pic/v1/brexit/tariff-item' );
define( 'SEUR_API_SHIPMENT', 'pic/v1/shipments' );
define( 'SEUR_API_LABELS', 'pic/v1/labels' );
define( 'SEUR_API_PICKUPS', 'pic/v1/pickups' );
define( 'SEUR_API_MANIFEST', 'pic/v1/shipments/delivery-manifest' );

define( 'SHIPMENT_STREETNAME_LENGTH', 70 );
define( 'SHIPMENT_COMMENT_LENGTH', 50 );
define( 'SHIPPING_CLASS_NACIONAL', 0); // shipping is to ES, PT or AD
define( 'SHIPPING_CLASS_INTERNACIONAL', 1); // shipping is NOT to ES, PT or AD
define( 'SHIPPING_CLASS_NACIONAL_FRANQUICIAS', 2); // shipping is to ES, PT or AD and franquicia is one in the condition

/**
 * More defins here => /core/defines/defines-loader.php
 */

/* Declare HPOS compatibility */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/**
 * SEUR Localization.
 */
function seur_official_init() {
	load_plugin_textdomain( 'seur', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'seur_official_init', 12 );

// Including Core and installer.
require_once SEUR_PLUGIN_PATH . 'core/installer.php';
register_activation_hook( __FILE__, 'seur_create_tables_hook' );
register_activation_hook( __FILE__, 'seur_add_data_to_tables_hook' );
register_activation_hook( __FILE__, 'seur_create_upload_folder_hook' );
register_activation_hook( __FILE__, 'seur_add_avanced_settings_preset' );

/**
 * SEUR Load Code.
 */
function seur_load_code() {
	// Including Core and installer.
	require_once SEUR_PLUGIN_PATH . 'classes/load-classes.php';
	require_once SEUR_PLUGIN_PATH . 'core/loader-core.php';

	$seur_db_version_saved = get_option( 'seur_db_version' );
	if ( $seur_db_version_saved != SEUR_DB_VERSION ) {
		seur_create_tables_hook();
	}

	$seur_table_version_saved = get_option( 'seur_table_version' );
	if ( $seur_table_version_saved != SEUR_TABLE_VERSION ) {
		seur_add_data_to_tables_hook();
	}
}
add_action( 'plugins_loaded', 'seur_load_code', 11 );

/**
 * SEUR Get Parent Page.
 */
function seur_get_parent_page() {
	if ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
		$seur_parent = basename( sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) );
		return $seur_parent;
	}
}

/**
 * SEUR Redirect to Welcome/About Page.
 */
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

/**
 * SEUR Add notice new version.
 */
function seur_add_notice_new_version() {

	$version = get_option( 'hide-new-version-seur-notice' );

	if ( SEUR_OFFICIAL_VERSION !== $version ) {
		if ( isset( $_REQUEST['_seur_hide_new_version_nonce'] ) && isset( $_REQUEST['seur-hide-new-version'] ) && 'hide-new-version-seur' === $_REQUEST['seur-hide-new-version'] ) {
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_seur_hide_new_version_nonce'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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
					<?php
					// translators: Link to SEUR website with new features.
					printf( wp_kses( __( 'Discover the improvements that have been made in this version, and how to take advantage of them <a href="%s" target="_blank">here</a>', 'woocommerce-seur' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( SEUR_POST_UPDATE_URL ) );
					?>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'seur_add_notice_new_version' );

/**
 * SEUR Add notice v2.
 */
function seur_add_notice_new_v2() {

	$version = get_option( 'hide-new-v2-seur-notice' );

	if ( SEUR_OFFICIAL_VERSION !== $version ) {
		if ( isset( $_REQUEST['_seur_hide_new_v2_nonce'] ) && isset( $_REQUEST['seur-hide-new-v2'] ) && 'hide-new-v2-seur' === $_REQUEST['seur-hide-new-v2'] ) {
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_seur_hide_new_v2_nonce'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( wp_verify_nonce( $nonce, 'seur_hide_new_v2_nonce' ) ) {
				update_option( 'hide-new-v2-seur-notice', SEUR_OFFICIAL_VERSION );
			}
		} else {
			?>
			<div id="message" class="updated woocommerce-message woocommerce-seur-messages">
				<a class="woocommerce-message-close notice-dismiss" style="top:0;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'seur-hide-new-v2', 'hide-new-v2-seur' ), 'seur_hide_new_v2_nonce', '_seur_hide_new_v2_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'woocommerce-seur' ); ?></a>
				<p>
					<?php echo esc_html__( 'WARNING', 'woocommerce-seur' ); ?>
				</p>
				<p>
					<?php
					esc_html_e( 'You need to contact to SEUR for new credentials. Call to +34913228380 or email to staci@seur.net', 'woocommerce-seur' );
					?>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'seur_add_notice_new_v2' );

/**
 * SEUR Notice Style.
 */
function seur_notice_style() {
	wp_register_style( 'seur_notice_css', SEUR_PLUGIN_URL . 'assets/css/seur-notice.css', false, SEUR_OFFICIAL_VERSION );
	wp_enqueue_style( 'seur_notice_css' );
}
add_action( 'admin_enqueue_scripts', 'seur_notice_style' );
