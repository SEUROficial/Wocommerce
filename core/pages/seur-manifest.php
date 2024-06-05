<?php
/**
 * SEUR Manifest
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR download data
 *
 * @param WP_Post $post Post dats sent.
 */
function seur_donwload_data( $post )
{
    // Deprecated since v2.2.8
    // Manifest is now generated in the Shipments page ?>
    <div class="wrap">
        <h1><?php echo esc_html__( 'SEUR Manifest', 'seur' ); ?></h1>
        <p>Please use the <a href="edit.php?post_type=seur_labels">Shipments</a> page to generate the manifest.</p>
        <p>Check the orders you want to include in the manifest and select the <strong>Generate Manifest</strong> option in Bulk actions selector.</p>
    </div>
    <?php
}
