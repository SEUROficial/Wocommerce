<?php
/**
 * Plugin Name: SEUR Oficial
 * Plugin URI: http://www.seur.com/
 * Description: Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way
 * Version: 2.2.27
 * Author: SEUR Oficial
 * Author URI: http://www.seur.com/
 * Tested up to: 6.8
 * WC requires at least: 3.0
 * WC tested up to: 9.1.4
 * Text Domain: seur
 * Domain Path: /languages/
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Seur Official
 **/

use Automattic\WooCommerce\Utilities\FeaturesUtil;

define( 'SEUR_OFFICIAL_VERSION', '2.2.27' );
define( 'SEUR_DB_VERSION', '1.0.5' );
define( 'SEUR_TABLE_VERSION', '1.0.5' );

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
define( 'SEUR_API_SHIPMENT_UPDATE', 'pic/v1/shipments/update' );
define( 'SEUR_API_LABELS', 'pic/v1/labels' );
define( 'SEUR_API_PICKUPS', 'pic/v1/pickups' );
define( 'SEUR_API_MANIFEST', 'pic/v1/shipments/delivery-manifest' );
define( 'SEUR_API_ADD_PARCELS', 'pic/v1/shipments/addpack' );

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
    if ( class_exists( FeaturesUtil::class ) ) {
        FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
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
register_activation_hook( __FILE__, 'deleteSeurJobs' );
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
    deleteSeurJobs();
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
				<a class="woocommerce-message-close notice-dismiss" style="top:0;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'seur-hide-new-version', 'hide-new-version-seur' ), 'seur_hide_new_version_nonce', '_seur_hide_new_version_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'seur' ); ?></a>
				<p>
					<?php echo esc_html__( 'SEUR has been updated to version', 'seur' ) . ' ' . esc_html( SEUR_OFFICIAL_VERSION ); ?>
				</p>
				<p>
					<?php
					// translators: Link to SEUR website with new features.
					printf( wp_kses( __( 'Discover the improvements that have been made in this version, and how to take advantage of them <a href="%s" target="_blank">here</a>', 'seur' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( SEUR_POST_UPDATE_URL ) );
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
				<a class="woocommerce-message-close notice-dismiss" style="top:0;" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'seur-hide-new-v2', 'hide-new-v2-seur' ), 'seur_hide_new_v2_nonce', '_seur_hide_new_v2_nonce' ) ); ?>"><?php esc_html_e( 'Dismiss', 'seur' ); ?></a>
				<p>
					<?php echo esc_html__( 'WARNING', 'seur' ); ?>
				</p>
				<p>
					<?php
					esc_html_e( 'You need to contact to SEUR for new credentials. Call to +34913228380 or email to staci@seur.net', 'seur' );
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

/**
 * Check if the shipping method is compatible with Block Checkout.
 */
const TARGET_SHIPPING_METHOD = 'seurlocal';

/*******************************************************************************************************************
 * CHECKOUT BLOCKS or CLASSIC ?
 */
function seur_uses_block_checkout(): bool {

    $uses_blocks = false;
    // WooCommerce 8.3+ — use the official utility.
    if ( class_exists( '\Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils' ) ) {
        $uses_blocks = \Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils::is_checkout_block_default();
    } else {
        // Fallback — check if the checkout page contains the block.
        if ( function_exists( 'has_block' ) ) {
            $checkout_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'checkout' ) : 0;
            if ( $checkout_id && has_block( 'woocommerce/checkout', $checkout_id ) ) {
                $uses_blocks = true;

                // Block themes — check the Site Editor template.
            } elseif ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && function_exists( 'get_block_template' ) ) {
                $tpl = get_block_template( get_stylesheet() . '//page-checkout', 'wp_template' );
                if ( $tpl && ! empty( $tpl->content ) && has_block( 'woocommerce/checkout', $tpl->content ) ) {
                    $uses_blocks = true;
                }
            }
        }
    }
    return $uses_blocks;
}

/**
 * Is this method compatible with Blocks Checkout?
 *     By default, ONLY the target method is incompatible; others are compatible.
 *     You can override from outside via the `seur_is_method_blocks_compatible` filter.
 *
 * @param string $method_id   ID of the shipping method.
 * @param int    $instance_id Method instance ID.
 * @param int    $zone_id     Shipping zone ID.
 */
function seur_is_method_blocks_compatible( string $method_id, int $instance_id = 0, int $zone_id = 0 ): bool {
    $compatible = ( $method_id !== TARGET_SHIPPING_METHOD );
    return (bool) apply_filters( 'seur_is_method_blocks_compatible', $compatible, $method_id, $instance_id, $zone_id );
}

/** ---------- Helpers / Utilidades ---------- */

/**
 * Disable a shipping method instance and clear shipping caches.
 */
function seur_disable_zone_method_instance( int $instance_id ): void {
    global $wpdb;

    $wpdb->update(
        "{$wpdb->prefix}woocommerce_shipping_zone_methods",
        [ 'is_enabled' => 0 ],
        [ 'instance_id' => absint( $instance_id ) ]
    );

    // Bust shipping caches so the change is reflected in the admin UI.
    if ( class_exists( 'WC_Cache_Helper' ) ) {
        WC_Cache_Helper::get_transient_version( 'shipping', true );
    }
}

/**
 * Show an admin notice indicating the method is not compatible with blocks.
 */
function seur_admin_notice_blocks_incompatible( string $method_id, int $instance_id ): void {
    if ( class_exists( 'WC_Admin_Notices' ) ) {
        seur_add_error_admin_notice_once(
            'seur_method_blocks_incompatible_' . $instance_id,
            __( 'El método de envío <strong>SEUR Local Pickup</strong> no es compatible con el Checkout de bloques y no se pintará el selector de puntos pickup. Puedes usar el checkout clásico mientras trabajamos en la compatibilidad.', 'seur' )
        );
    }
}

/** ---------- Hooks ---------- */

/**
 * Status toggle (enable/disable) from Shipping Zones.
 *     If enabling and it’s not blocks-compatible, keep it disabled and show a notice.
 */
function seur_woocommerce_shipping_zone_method_status_toggled( int $instance_id, string $method_id, int $zone_id, int $is_enabled ): void {
    // Only care when enabling.
    if ( 1 !== (int) $is_enabled ) {
        return;
    }
    // If Blocks Checkout isn’t used, don’t block anything.
    if ( ! seur_uses_block_checkout() ) {
        return;
    }
    // If NOT compatible, disable and notify.
    if ( ! seur_is_method_blocks_compatible( $method_id, $instance_id, $zone_id ) ) {
        seur_disable_zone_method_instance( $instance_id );
        seur_admin_notice_blocks_incompatible( $method_id, $instance_id );
    }
}
add_action( 'woocommerce_shipping_zone_method_status_toggled', 'seur_woocommerce_shipping_zone_method_status_toggled', 10, 4 );

/**
 * “Add method” case.
 *     If it’s not blocks-compatible, it’s created but immediately disabled and a notice is shown.
 */
function seur_woocommerce_shipping_zone_method_added( int $instance_id, string $method_id, int $zone_id ): void {
    // If Blocks Checkout isn’t used, do nothing.
    if ( ! seur_uses_block_checkout() ) {
        return;
    }
    // If NOT compatible, disable and notify.
    if ( ! seur_is_method_blocks_compatible( $method_id, $instance_id, $zone_id ) ) {
        seur_disable_zone_method_instance( $instance_id );
        seur_admin_notice_blocks_incompatible( $method_id, $instance_id );
    }
}
add_action( 'woocommerce_shipping_zone_method_added', 'seur_woocommerce_shipping_zone_method_added', 10, 3 );

/*
 * Audit: disable all enabled, blocks-incompatible instances across all zones.
 */
function seur_audit_and_disable_incompatible_methods(): void {
    if ( ! is_admin() || ! seur_uses_block_checkout() ) {
        return;
    }

    global $wpdb;
    // Find all enabled instances of your target method.
    $instance_ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT instance_id FROM {$wpdb->prefix}woocommerce_shipping_zone_methods
			 WHERE method_id = %s AND is_enabled = 1",
            TARGET_SHIPPING_METHOD
        )
    );

    if ( empty( $instance_ids ) ) {
        return;
    }

    foreach ( $instance_ids as $instance_id ) {
        // In case you later expand per-instance/zone compatibility logic.
        if ( ! seur_is_method_blocks_compatible( TARGET_SHIPPING_METHOD, (int) $instance_id, 0 ) ) {
            seur_disable_zone_method_instance( (int) $instance_id );
        }
    }

    // Single admin notice to avoid noise.
    if ( class_exists( 'WC_Admin_Notices' ) ) {
        seur_add_error_admin_notice_once(
            'seur_bulk_blocks_incompatible',
            __( 'Se han desactivado instancias incompatibles con el Checkout de bloques para el método "SEUR Local Pickup".', 'seur' )
        );
    }
}
add_action( 'admin_init', 'seur_audit_and_disable_incompatible_methods' );


/**
 * Show a red (error) admin notice once (persisted across page loads).
 */
function seur_add_error_admin_notice_once(string $key, string $html): void
{
    $stack = get_option('seur_admin_error_notices', array());
    $stack[$key] = wp_kses_post($html);
    update_option('seur_admin_error_notices', $stack);
}

/**
 * Output stored notices and delete them (global hook).
 */
function seur_output_error_admin_notices(): void
{
    $stack = get_option('seur_admin_error_notices', array());
    if (empty($stack)) {
        return;
    }

    // Opcional: limítalo a pantallas de WooCommerce.
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ($screen && !in_array($screen->id, function_exists('wc_get_screen_ids') ? wc_get_screen_ids() : array(), true)) {
        return;
    }

    foreach ($stack as $key => $html) {
        // notice-error => red border; is-dismissible => close button.
        printf(
            '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
            $html
        );
        unset($stack[$key]); // Mostrar solo una vez / Only once.
    }
    update_option('seur_admin_error_notices', $stack);
}
add_action('admin_notices', 'seur_output_error_admin_notices');
