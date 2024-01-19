<?php
/**
 * Backwards compat.
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active_plugins = get_option( 'active_plugins', array() );
foreach ( $active_plugins as $key => $active_plugin ) {
	if ( strstr( $active_plugin, '/shipping-seur.php' ) ) {
		$active_plugins[ $key ] = str_replace( '/shipping-seur.php', '/seur-official.php', $active_plugin );
	}
}
update_option( 'active_plugins', $active_plugins );
