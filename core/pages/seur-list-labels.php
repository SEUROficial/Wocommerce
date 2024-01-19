<?php
/**
 * SEUR list labels
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR list labels page.
 */
function seur_list_labels_page() {

	include_once SEUR_CLASSES . 'seur-list-labels-class.php';
	echo esc_html( SEUR_CLASSES ) . 'seur-list-labels-class.php';
}
