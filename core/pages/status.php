<?php
/**
 * SEUR Status
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Status Page
 */
function seur_status_page() {
	?>
	<div class="wrap">
		<?php
		if ( isset( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else {
			$active_tab = 'status_seur';
		}
		?>
		<h2 class="nav-tab-wrapper">
			<a href="admin.php?page=seur_status_page&tab=status_seur" class="nav-tab <?php echo 'status_seur' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Status Seur', 'seur' ); ?></a>
			<a href="admin.php?page=seur_status_page&&tab=file_seur" class="nav-tab <?php echo 'file_seur' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Code File & Scripts', 'seur' ); ?></a>
		</h2>
	</div>
	<?php
	if ( 'status_seur' === $active_tab ) {
		include_once 'status/status-check.php';
	} else {
		?>
		<p>
			<?php
			esc_html_e( 'Use this scripts for fix plugin settings and options', 'seur' );
			?>
		</p>
		<?php
		include_once 'status/status-scripts.php';
	}
}
