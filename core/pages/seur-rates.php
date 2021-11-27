<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function seur_rates_prices( $post ) {
	// Declarando $wpdb global y usarlo para ejecutar una sentencia de consulta SQL.
	global $wpdb;
	$rates_type = get_option( 'seur_rates_type_field' );
	$bloquear   = '';
	?>

<div class="wrap">
		<h1><?php echo __( 'SEUR Rates', 'seur' ); ?></h1>

		<?php
		if ( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		} else {
			$active_tab = 'calculate_rates';
		}
		?>
<h2 class="nav-tab-wrapper">
	<a href="?page=seur_rates_prices&tab=calculate_rates" class="nav-tab <?php echo $active_tab == 'calculate_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Calculate Rates', 'seur' ); ?></a>
	<a href="?page=seur_rates_prices&tab=custom_rates" class="nav-tab <?php echo $active_tab == 'custom_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Custom Rates', 'seur' ); ?></a>
	<a href="?page=seur_rates_prices&tab=custom_rates_name" class="nav-tab <?php echo $active_tab == 'custom_rates_name' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Custom Name Rates', 'seur' ); ?></a>
	<?php if ( $rates_type == 'weight' ) { ?>
	<a href="?page=seur_rates_prices&tab=limit_price_weight_rates" class="nav-tab <?php echo $active_tab == 'limit_price_weight_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Rate Weight Settings', 'seur' ); ?></a>
	<?php } ?>
</h2>

	<?php

	if ( $active_tab == 'calculate_rates' ) {
		include_once 'rates/seur-rates.php';
	} elseif ( $active_tab == 'custom_rates' ) {
		include_once 'rates/seur-custom-rates.php';
	} elseif ( $active_tab == 'custom_rates_name' ) {
		include_once 'rates/custom-name-rates.php';
	} elseif ( $active_tab == 'limit_price_weight_rates' ) {
		include_once 'rates/limit-price-weight-rates.php';
	}
	?>
	</div>
	<?php
}
?>
