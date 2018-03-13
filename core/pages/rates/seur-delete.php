<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_delete_rate(){
	if( $_POST['del_id'] ) {

		global $wpdb;

		$id = sanitize_text_field ( $_POST['del_id'] );

		$table = $wpdb->prefix . 'seur_custom_rates';

		$getrates = $wpdb->get_results("SELECT * FROM $table ORDER BY ID ASC");

		$wpdb->delete( $table, array( 'ID' => $id ), array( '%d' ) );
	}
}
?>