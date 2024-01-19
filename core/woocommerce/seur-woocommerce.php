<?php
/**
 * SEUR WooCommerce
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SEUR_WOOCOMMERCE_PART', '1.0.0' );
/**
 * Plugin activation check
 */
function wc_seur_activation_check() {
	if ( ! function_exists( 'simplexml_load_string' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( "Sorry, but you can't run this plugin, it requires the SimpleXML library installed on your server/hosting to function." );
	}
}
register_activation_hook( __FILE__, 'wc_seur_activation_check' );

require_once 'includes/seur-woo-functions.php';
require_once 'includes/metabox/seur-metabox.php';

/**
 * WC_Shipping_SEUR_Init Class
 */
class WC_Shipping_SEUR_Init {

	/**
	 * Plugin's version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $version = '1.0.0';

	/**
	 * Static Instance.
	 *
	 * @var object Class Instance
	 */
	private static $instance;

	/**
	 * Get the class instance
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self() ) : self::$instance;
	}

	/**
	 * Initialize the plugin's public actions
	 */
	public function __construct() {
		if ( class_exists( 'WC_Shipping_Method' ) ) {
			if ( seur()->log_is_acive() ) {
				// seur()->slog( 'WC_Shipping_SEUR_Init: Exists' );
			}
			add_action( 'admin_init', array( $this, 'maybe_install' ), 5 );
			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
			add_action( 'woocommerce_shipping_init', array( $this, 'includes' ) );
			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method_localseur' ) );
			add_action( 'admin_notices', array( $this, 'upgrade_notice' ) );
			add_action( 'wp_ajax_seur_dismiss_upgrade_notice', array( $this, 'dismiss_upgrade_notice' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'wc_deactivated' ) );
		}
	}

	/**
	 * Include needed files
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/includes/class-wc-shipping-seur.php';
		include_once dirname( __FILE__ ) . '/includes/class-seur_local_shipping_method.php';
	}

	/**
	 * Wc_seur_add_method function.
	 *
	 * @param mixed $methods methods.
	 */
	public function add_method( $methods ) {
			$methods['seur'] = 'WC_Shipping_SEUR';
		return $methods;
	}

	/**
	 * Add_method_localseur.
	 *
	 * @param array $methods methods.
	 */
	public function add_method_localseur( $methods ) {
			$methods['seurlocal'] = 'Seur_Local_Shipping_Method';
		return $methods;
	}

	/**
	 * Localisation
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'seur', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Plugin page links
	 *
	 * @param string $links Plugin Links.
	 */
	public function plugin_links( $links ) {
		$plugin_links = array(
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'seur' ) . '</a>',
			'<a href="http://wcdocs.woothemes.com/user-guide/seur/">' . __( 'Docs', 'seur' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	/**
	 * WooCommerce not installed notice
	 */
	public function wc_deactivated() {
		echo '<div class="error"><p>' . sprintf( esc_html__( 'WooCommerce SEUR Shipping requires %s to be installed and active.', 'seur' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}

	/**
	 * Checks the plugin version
	 *
	 * @return bool
	 */
	public function maybe_install() {
		// only need to do this for versions less than 3.2.0 to migrate
		// settings to shipping zone instance.
		$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		if ( ! $doing_ajax
			&& ! defined( 'IFRAME_REQUEST' )
			&& version_compare( WC_VERSION, '2.6.0', '>=' )
			&& version_compare( get_option( 'wc_seur_version' ), '3.2.0', '<' ) ) {

			$this->install();
		}
		return true;
	}

	/**
	 * Update/migration script
	 */
	public function install() {
		// get all saved settings and cache it.
		$seur_settings = get_option( 'woocommerce_seur_settings', false );

		// settings exists.
		if ( $seur_settings ) {
			global $wpdb;

			// unset un-needed settings.
			unset( $seur_settings['enabled'] );
			unset( $seur_settings['availability'] );
			unset( $seur_settings['countries'] );

			// first add it to the "rest of the world" zone when no seur.
			// instance.
			if ( ! $this->is_zone_has_seur( 0 ) ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}woocommerce_shipping_zone_methods ( zone_id, method_id, method_order, is_enabled ) VALUES ( %d, %s, %d, %d )", 0, 'seur', 1, 1 ) );
				// add settings to the newly created instance to options table.
				$instance = $wpdb->insert_id;
				add_option( 'woocommerce_seur_' . $instance . '_settings', $seur_settings );
			}
			update_option( 'woocommerce_seur_show_upgrade_notice', 'yes' );
		}
		update_option( 'wc_seur_version', self::$version );
	}

	/**
	 * Show the user a notice for plugin updates
	 *
	 * @since 3.2.0
	 */
	public function upgrade_notice() {
		$show_notice = get_option( 'woocommerce_seur_show_upgrade_notice' );

		if ( 'yes' !== $show_notice ) {
			return;
		}
		$query_args      = array(
			'page' => 'wc-settings',
			'tab'  => 'shipping',
		);
		$zones_admin_url = add_query_arg( $query_args, get_admin_url() . 'admin.php' );
		?>
		<div class="notice notice-success is-dismissible wc-seur-notice">
			<p><?php echo sprintf( esc_html__( 'SEUR now supports shipping zones. The zone settings were added to a new SEUR method on the "Rest of the World" Zone. See the zones %1$shere%2$s ', 'seur' ), '<a href="' . esc_url( $zones_admin_url ) . '">', '</a>' ); ?></p>
		</div>

		<script type="application/javascript">
			jQuery( '.notice.wc-seur-notice' ).on( 'click', '.notice-dismiss', function () {
				wp.ajax.post('seur_dismiss_upgrade_notice');
			});
		</script>
		<?php
	}

	/**
	 * Turn of the dismisable upgrade notice.
	 *
	 * @since 3.2.0
	 */
	public function dismiss_upgrade_notice() {
		update_option( 'woocommerce_seur_show_upgrade_notice', 'no' );
	}

	/**
	 * Helper method to check whether given zone_id has seur method instance.
	 *
	 * @since 3.2.0
	 *
	 * @param int $zone_id Zone ID.
	 *
	 * @return bool True if given zone_id has seur method instance.
	 */
	public function is_zone_has_seur( $zone_id ) {
		global $wpdb;

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(instance_id) FROM {$wpdb->prefix}woocommerce_shipping_zone_methods WHERE method_id = 'seur' AND zone_id = %d", $zone_id ) ) > 0;
	}
}
$shipping_seur = new WC_Shipping_SEUR_Init();
$shipping_seur::get_instance();
