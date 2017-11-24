<?php

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	function seur_order_details_tracking( $order_id ) { ?>

		<h2><?php _e( 'Where is my Order?', 'seur'); ?></h2>

		<?php }
	add_action( 'woocommerce_view_order', 'seur_order_details_tracking', 10 );