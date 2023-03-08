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

	public function seur_get_token() {

		$seur_adr      = seur()->get_api_addres() . SEUR_TOKEN;
		$grant_type    = 'password';
		$client_id     = seur()->client_id();
		$client_secret = seur()->client_secret();
		$username      = seur()->client_user_name();
		$password      = seur()->client_user_password();
		if ( seur()->log_is_acive() ) {
			seur()->slog( '$seur_adr: ' . $seur_adr );
			seur()->slog( '$grant_type: ' . $grant_type );
			seur()->slog( '$client_id: ' . $client_id );
			seur()->slog( '$client_secret: ' . $client_secret );
			seur()->slog( '$username: ' . $username );
			seur()->slog( '$password: ' . $password );
		}
		$response      = wp_remote_post(
			$seur_adr,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'httpversion' => '1.0',
				'user-agent'  => 'WooCommerce',
				'headers'     => array(
					'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
				),
				'body'        => array(
					'grant_type'    => $grant_type,
					'client_id'     => $client_id,
					'client_secret' => $client_secret,
					'username'      => $username,
					'password'      => $password,
				),
			)
		);
		$response_body = wp_remote_retrieve_body( $response );
		$result        = json_decode( $response_body );
		$token         = $result->access_token;

		update_option( 'seur_api_token', $token );

		if ( seur()->log_is_acive() ) {
			seur()->slog( '$result: ' . print_r( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			seur()->slog( 'Token: ' . $token );
			seur()->slog( '/*****************************/' );
			seur()->slog( 'sending Token confirmation mail' );
			seur()->slog( '/*****************************/' );
			$to      = get_bloginfo( 'admin_email' );
			$subject = 'API Seur Guardada correctamente';
			$body    = 'Se ha generado de forma correcta un nuevo Token. El nuevo token es: ' . $token;
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $to, $subject, $body, $headers );
		}
	}
}
return new Seur_Scheduled_Actions();
