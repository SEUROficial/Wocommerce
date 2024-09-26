<?php
/**
 * SEUR Settings
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Setings.
 */
function seur_settings() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'SEUR Settings', 'seur' ); ?></h1>
		<?php
		if ( isset( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$active_tab = 'user_settings';
		}
		?>
		<h2 class="nav-tab-wrapper">
			<a href="admin.php?page=seur&tab=user_settings" class="nav-tab <?php echo 'user_settings' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'User Settings', 'seur' ); ?></a>
			<a href="admin.php?page=seur&&tab=advanced_settings" class="nav-tab <?php echo 'advanced_settings' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Advanced Settings', 'seur' ); ?></a>
		</h2>
		<form method="post" action="options.php">
			<?php
			if ( 'user_settings' === $active_tab ) {
				$link = esc_url( admin_url( add_query_arg( array( 'import' => 'seur' ), 'admin.php' ) ) );

				?>
				<p>
				<?php
				esc_html_e( 'Configuration data. Contact SEUR if you do not have them.', 'seur' );
				?>
				</p>
				<?php
				settings_fields( 'seur-user-settings-section' );
				do_settings_sections( 'seur-user-settings-options' );
				echo wp_kses_post( __( '(<sup>*</sup>) This data is provided by SEUR', 'seur' ) );
			} else {
				?>
				<p>
				<?php
				esc_html_e( 'Please specify if you want Notifications, time for pickups, type of labels generated and customs information.', 'seur' );
				?>
				</p>
				<?php
				settings_fields( 'seur-advanced-settings-section' );
				do_settings_sections( 'seur-advanced-settings-options' );
			}
			submit_button();
			?>
		</form>
		<script type="text/javascript">
			var test = document.querySelector( '.js-switch-test' );
			if ( test ) {
				var switchery = new Switchery(test, { size: 'small' } );
			}
			var log = document.querySelector( '.js-switch-log' );
			if ( log ) {
				var switchery = new Switchery(log, { size: 'small' } );
			}
			var geolabel = document.querySelector( '.js-switch-geolabel' );
			if ( geolabel ) {
				var switchery = new Switchery(geolabel, { size: 'small' } );
			}
			var preavisonotificar = document.querySelector( '.js-switch-preavisonotificar' );
			if ( preavisonotificar ) {
				var switchery = new Switchery(preavisonotificar, { size: 'small' } );
			}
			var repartonotificar = document.querySelector( '.js-switch-repartonotificar' );
			if ( repartonotificar ) {
				var switchery = new Switchery(repartonotificar, { size: 'small' } );
			}
			var localpickup = document.querySelector( '.js-switch-pickup' );
			if ( localpickup ) {
				var switchery = new Switchery(localpickup, { size: 'small' } );
			}
			var freeshipping = document.querySelector( '.js-switch-free-shipping' );
			if ( localpickup ) {
				var switchery = new Switchery(freeshipping, { size: 'small' } );
			}
		</script>
	</div>
	<?php
}

/**
 * SEUR Settings Load CSS
 *
 * @param string $hook Hook page.
 */
function seur_settings_load_css( $hook ) {
	global $seurconfig;

	if ( $seurconfig !== $hook ) {
		return;
	} else {
		wp_register_style( 'seur_switchery_css', SEUR_PLUGIN_URL . 'assets/css/switchery.css', array(), SEUR_OFFICIAL_VERSION );
		wp_enqueue_style( 'seur_switchery_css' );
	}
}
add_action( 'admin_enqueue_scripts', 'seur_settings_load_css' );

// Include all options.
require_once 'setting-options/user-settings.php';
require_once 'setting-options/advanced-settings.php';
