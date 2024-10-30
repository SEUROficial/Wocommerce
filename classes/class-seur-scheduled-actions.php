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
require_once ABSPATH . 'wp-content/plugins/woocommerce/packages/action-scheduler/action-scheduler.php';

class Seur_Scheduled_Actions {

	public function __construct() {

		add_action( 'init', array( $this, 'seur_token_schedule' ) );
		add_action( 'seur_get_token_hook', array( $this, 'seur_get_token' ) );
	}

	public function seur_token_schedule() {
		if ( false === as_next_scheduled_action( 'seur_get_token_hook' ) ) {
			as_schedule_recurring_action( strtotime( 'now' ), 1200, 'seur_get_token_hook' );
		}
	}

	public function seur_get_token()
    {
        return seur()->seur_get_token();
    }
}
return new Seur_Scheduled_Actions();
