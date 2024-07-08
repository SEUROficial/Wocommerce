<?php
/**
 * SEUR reates
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Rates Prices
 *
 * @param WP_Post $post Post Data.
 */
function seur_rates_prices( $post ) {
	// Declarando $wpdb global y usarlo para ejecutar una sentencia de consulta SQL.
	global $wpdb;
	$rates_type = get_option( 'seur_rates_type_field' );
	$bloquear   = '';
	?>

<div class="wrap">
	<h1><?php echo esc_html__( 'SEUR Rates', 'seur' ); ?></h1>
	<?php
	if ( isset( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} else {
		$active_tab = 'calculate_rates';
	}
	?>
	<h2 class="nav-tab-wrapper">
		<a href="?page=seur_rates_prices&tab=calculate_rates" class="nav-tab <?php echo 'calculate_rates' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Calculate Rates', 'seur' ); ?></a>
		<a href="?page=seur_rates_prices&tab=custom_rates" class="nav-tab <?php echo 'custom_rates' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Custom Rates', 'seur' ); ?></a>
		<a href="?page=seur_rates_prices&tab=custom_rates_name" class="nav-tab <?php echo 'custom_rates_name' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Custom Name Rates', 'seur' ); ?></a>
        <a href="?page=seur_rates_prices&tab=import_custom_rates" class="nav-tab <?php echo 'import_custom_rates' === $active_tab ? 'nav-tab-active' : ''; ?>">Importar tarifas personalizadas</a>
        <?php if ( 'weight' === $rates_type ) { ?>
		<a href="?page=seur_rates_prices&tab=limit_price_weight_rates" class="nav-tab <?php echo 'limit_price_weight_rates' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Rate Weight Settings', 'seur' ); ?></a>
		<?php } ?>
	</h2>
	<?php
	if ( 'calculate_rates' === $active_tab ) {
		include_once 'rates/seur-rates.php';
	} elseif ( 'custom_rates' === $active_tab ) {
		include_once 'rates/seur-custom-rates.php';
	} elseif ( 'custom_rates_name' === $active_tab ) {
		include_once 'rates/custom-name-rates.php';
	} elseif ( 'limit_price_weight_rates' === $active_tab ) {
		include_once 'rates/limit-price-weight-rates.php';
	} elseif ( 'import_custom_rates' === $active_tab ) {
		include_once 'rates/seur-import-custom-rates.php';
	}
	?>
	</div>
	<?php
}
