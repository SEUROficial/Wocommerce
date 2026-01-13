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
				<th scope="col" id="response" class="manage-column column-author column-primary"><?php esc_html_e( 'Description', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Service', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Product', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Type', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'County', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'State', 'seur' ); ?></th>
			</tr>
		</thead>
		<tbody id="the-list">
			<?php
			$registros = seur()->get_products();

			foreach ( $registros as $description => $valor ) {
				$pais_p      = '';
				$provincia_p = '';
				foreach ( $valor['pais'] as $pais ) {
					$pais_p .= (strlen($pais_p)>0?', ':''). $pais;
				}
				foreach ( $valor['provincia'] as $provincia ) {
					$provincia_p .= (strlen($provincia_p)>0?', ':''). $provincia;
				}
				echo '<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">';
				echo '<td class="author column-author" data-colname="Description">' . esc_html( $description ) . '</td>';
				echo '<td class="author column-author" data-colname="Service">' . esc_html( $valor['service'] ) . '</td>';
				echo '<td class="author column-author" data-colname="Product">' . esc_html( $valor['product'] ) . '</td>';
				echo '<td class="author column-author" data-colname="Type">' . esc_html( $valor['tipo'] ) . '</td>';
				echo '<td class="author column-author" data-colname="Country">' . esc_html( $pais_p ) . '</td>';
				echo '<td class="author column-author" data-colname="State">' . esc_html( $provincia_p ) . '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th scope="col" id="response" class="manage-column column-author column-primary"><?php esc_html_e( 'Description', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Service', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Product', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Type', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'Zone', 'seur' ); ?></th>
				<th scope="col" id="author" class="manage-column column-author"><?php esc_html_e( 'State', 'seur' ); ?></th>
			</tr>
		</tfoot>
	</table>
</div>
	<?php
}
