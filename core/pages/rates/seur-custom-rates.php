<?php
/**
 * SEUR Custom Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$rates_type = get_option( 'seur_rates_type_field' );

if ( isset( $_GET['action'] ) && $_GET['action'] === 'download_seur_rates_csv' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'seur_custom_rates';

	$rates = $wpdb->get_results( "SELECT * FROM {$table_name}", ARRAY_A );

	if ( empty( $rates ) ) {
		wp_die( 'No hay tarifas para exportar.' );
	}

	// Limpiar el buffer de salida para evitar HTML no deseado
	ob_clean();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=seur_tarifas_actuales.csv' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );

	// Abrir salida para CSV
	$output = fopen( 'php://output', 'w' );

	// Eliminar las columnas "created_at" y "updated_at"
	foreach ( $rates as &$row ) {
		unset( $row['created_at'], $row['updated_at'] );
	}
	unset($row); // Para evitar referencias inesperadas

	// Escribir encabezados sin las columnas eliminadas
	fputcsv( $output, array_keys( $rates[0] ) );

	// Escribir filas sin las columnas eliminadas
	foreach ( $rates as $row ) {
		fputcsv( $output, $row );
	}

	// Cerrar salida
	fclose( $output );

	// Detener la ejecución de WordPress
	exit;
}
?>
<div class="container">
	<br />
	<p><?php esc_html_e( 'Include the rates of the transport options that your customers can choose', 'seur' ); ?></p>
	<p><?php echo esc_html__( 'Your rates are based on', 'seur' ) . ' <b>' . esc_html( $rates_type ) . '</b> '; ?></p>
	<p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Please, set based rates price here', 'seur' ); ?></a></p>
	<br />
	<button class="button" type="button" id="btn-add"><?php esc_html_e( 'Add Custom Rate', 'seur' ); ?></button>
	<button class="button" type="button" id="btn-view"><?php esc_html_e( 'View Custom Rates', 'seur' ); ?></button>
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=seur_rates_prices&tab=custom_rates&action=download_seur_rates_csv' ) ); ?>" class="button button-secondary">
        <?php esc_html_e( 'Download Current Rates in CSV', 'seur' ); ?>
    </a>
    <hr>
	<div class="content-loader">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<th class="manage-column"><?php esc_html_e( 'ID', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
                    <?php // translators: %s is the type of rate (e.g., weight, price). ?>
                    <th class="manage-column"><?php printf( esc_html__( 'Min %s', 'seur' ), esc_html( $rates_type ) ); ?></th>
                    <?php // translators: %s is the type of rate (e.g., weight, price). ?>
                    <th class="manage-column"><?php printf( esc_html__( 'Max %s', 'seur' ), esc_html( $rates_type ) ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$getrates    = seur_get_custom_rates( 'OBJECT', $rates_type );
				if ( ! $getrates ) {
					echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . esc_html__( 'No custom rates found, please add your Custom Rates', 'seur' ) . '</b></center><br /></td></tr>';
				} else {
					foreach ( $getrates as $getrate ) { ?>
                        <tr>
                        <?php
                        $min_value = ($getrate->type=='price'? $getrate->minprice: $getrate->minweight);
                        $max_value = ($getrate->type=='price'? $getrate->maxprice: $getrate->maxweight);
                        $country = $getrate->country;
                        if ( 'ALL' === $getrate->country ) {
                            $country = __( 'ALL', 'seur' );
                        }
                        if ( 'REST' === $getrate->country ) {
                            $country = __( 'REST', 'seur' );
                        }
                        $rateprice = $getrate->rateprice;
                        if ( '0' === $getrate->rateprice ) {
                            $rateprice = __( 'FREE', 'seur' );
                        }
                        $maxratevalue = $max_value;
                        if ( '9999999' === $max_value ) {
                            $maxratevalue = __( 'No limit', 'seur' );
                        }
                        ?>
                        <td><?php echo esc_html( $getrate->ID ); ?></td>
                        <td><?php echo esc_html( $getrate->rate ); ?></td>
                        <td><?php echo esc_html( $country ); ?></td>
                        <td><?php echo esc_html( $getrate->state ); ?></td>
                        <td><?php echo esc_html( $getrate->postcode ); ?></td>
                        <td><?php echo esc_html( $min_value ); ?></td>
                        <td><?php echo esc_html( $maxratevalue ); ?></td>
                        <td><?php echo esc_html( $rateprice ); ?></td>
                        <td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="edit-link icon-pencil" href="#"></a></td>
                        <td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="delete-link icon-cross" href="#"></a></td>
                        </tr>
                        <?php
                    }
				}
				?>
				<thead>
					<tr>
						<th class="manage-column"><?php esc_html_e( 'ID', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
                        <?php /* translators: %s is the type of rate (e.g., weight, price) */ ?>
                        <th class="manage-column"><?php printf( esc_html__( 'Min %s', 'seur' ), esc_html( $rates_type ) ); ?></th>
						<?php /* translators: %s is the type of rate (e.g., weight, price) */ ?>
                        <th class="manage-column"><?php printf( esc_html__( 'Max %s', 'seur' ), esc_html( $rates_type ) ); ?></th>
                        <th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
					</tr>
				</thead>
			</tbody>
		</table>
	</div>
</div>
