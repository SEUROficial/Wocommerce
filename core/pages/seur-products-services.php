<?php
/**
 * SEUR Proucts Service
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Products service
 *
 * @param WP_Post $post Post Data.
 */
function seur_products_services( $post ) {
	global $wpdb;
	?>
<div class="wrap">
	<h2><?php echo esc_html__( 'Products / Services', 'seur' ); ?></h2>

	<h2 class="screen-reader-text"><?php esc_html_e( 'Products / Services List', 'seur' ); ?></h2>
	<p><?php esc_html_e( 'These services and products might not be available in your commercial proposal. Please verify with your SEUR sales contact that you have all combinations enabled.', 'seur' ); ?></p>
	<p><?php esc_html_e( 'List of combinations of SEUR Services and Products available in the plugin.', 'seur' ); ?></p>
	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<th scope="col" id="author" class="manage-column column-author column-primary"><?php esc_html_e( 'ID', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author column-primary"><?php esc_html_e( 'Service', 'seur' ); ?></th>
				<th scope="col" id="comment" class="manage-column column-author"><?php esc_html_e( 'Product', 'seur' ); ?></th>
				<th scope="col" id="response" class="manage-column column-author"><?php esc_html_e( 'Description', 'seur' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php
				$tabla     = $wpdb->prefix . SEUR_PLUGIN_SVPR;
				$sql       = "SELECT * FROM $tabla";
				$registros = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}SEUR_PLUGIN_SVPR" );

			foreach ( $registros as $valor ) {
				echo '<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">';
				echo '<td class="author column-author" data-colname="ID">' . esc_html( $valor->ID ) . '</td>';
				echo '<td class="author column-author" data-colname="Service">' . esc_html( $valor->ser ) . '</td>';
				echo '<td class="author column-author" data-colname="Product">' . esc_html( $valor->pro ) . '</td>';
				echo '<td class="author column-author" data-colname="Description">' . esc_html( $valor->descripcion ) . '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th scope="col" id="author" class="manage-column column-author column-primary"><?php esc_html_e( 'ID', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author column-primary"><?php esc_html_e( 'Service', 'seur' ); ?></th>
				<th scope="col" id="comment" class="manage-column column-author"><?php esc_html_e( 'Product', 'seur' ); ?></th>
				<th scope="col" id="response" class="manage-column column-author"><?php esc_html_e( 'Description', 'seur' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div><?php
	// return.
}
?>
