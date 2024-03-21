<?php
/**
 * Add extra profile fields for users in admin
 *
 * @package  WooCommerce SEUR
 * @version  3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require 'class-seur-global.php';
require 'class-seur-scheduled-actions.php';
require 'class-seur-collections.php';
require 'class-seur-seguimiento.php';
require 'PrinterType.php';
require 'ProductType.php';
require 'PDFMerger.php';
