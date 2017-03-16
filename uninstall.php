<?php

//if uninstall not called from WordPress exit
if (!defined('WP_UNINSTALL_PLUGIN') || !current_user_can('activate_plugins')) exit();

global $wpdb;
/*require_once dirname(__FILE__).'/config.php';

Desinstalar();

function Desinstalar(){
		global $wpdb;

		// Eliminamos toda la tabla de configuracion de la tienda
		$tabla = $wpdb->prefix . SEUR_PLUGIN_TABLA;
		$wpdb->query("DROP TABLE IF EXISTS $tabla");

		// eliminamos la tabla de las recogidas
		$tabla = $wpdb->prefix . SEUR_PLUGIN_RECO;
		$wpdb->query("DROP TABLE IF EXISTS $tabla");

		// eliminamos la tabla de las etiquetas impresas
		$tabla = $wpdb->prefix . SEUR_PLUGIN_ECB;
		$wpdb->query("DROP TABLE IF EXISTS $tabla");

			// eliminamos la tabla de servicios productos
		$tabla = $wpdb->prefix . SEUR_PLUGIN_SVPR;
		$wpdb->query("DROP TABLE IF EXISTS $tabla");

		$tabla = $wpdb->prefix . SEUR_PLUGIN_SVPR;
		// Si existe la carpeta de descargas la borramos
		if (is_dir(SEUR_DESCARGAS)) rmdir(SEUR_DESCARGAS);
*/
	}

