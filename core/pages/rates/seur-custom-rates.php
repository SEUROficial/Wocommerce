<?php
/**
 * SEUR Custom Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$rates_type = get_option( 'seur_rates_type_field' ); ?>
<div class="container">
	<br />
	<p><?php esc_html_e( 'Include the rates of the transport options that your customers can choose', 'seur' ); ?></p>
	<p><?php echo esc_html__( 'Your rates are based on', 'seur' ) . ' <b>' . esc_html( $rates_type ) . '</b> '; ?></p>
	<p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Please, set based rates price here', 'seur' ); ?></a></p>
	<br />
	<button class="button" type="button" id="btn-add"><?php esc_html_e( 'Add Custom Rate', 'seur' ); ?></button>
	<button class="button" type="button" id="btn-view"><?php esc_html_e( 'View Custom Rates', 'seur' ); ?></button>
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
					<th class="manage-column"><?php esc_html_e( 'Min '. $rates_type, 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Max '.$rates_type, 'seur' ); ?></th>
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
						<th class="manage-column"><?php esc_html_e( 'Min '. $rates_type, 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Max '. $rates_type, 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
					</tr>
				</thead>
			</tbody>
		</table>
	</div>
</div>
