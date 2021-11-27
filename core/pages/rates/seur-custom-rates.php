<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$rates_type = get_option( 'seur_rates_type_field' );
?>
<div class="container">
	<br />
	<p><?php esc_html_e( 'Include the rates of the transport options that your customers can choose', 'seur' ); ?></p>
	<p><?php echo esc_html__( 'Your rates are based on', 'seur' ) . ' <b>' . esc_html( $rates_type ) . '</b> '; ?></p>
	<p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Please, set based rates price here', 'seur' ); ?></a></p>
	<br />
	<button class="button" type="button" id="btn-add"><?php esc_html_e( 'Add Custom Rate', 'seur' ); ?></button>
	<button class="button" type="button" id="btn-view"><?php esc_html_e( 'View Custom Rates', 'seur' ); ?></button>
	<hr>
	<?php
	if ( $rates_type == 'price' ) {
		?>
	<div class="content-loader">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<!-- <th class="manage-column">ID</th> -->
					<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Min Price', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Max Price', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$output_type = 'OBJECT';
					$type        = 'price';
					$getrates    = seur_get_custom_rates( $output_type, $type );
				if ( ! $getrates ) {
					echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . esc_html__( 'No custom rates found, please add your Custom Rates', 'seur' ) . '</b></center><br /></td></tr>';
				} else {
					if ( $getrates ) {
						foreach ( $getrates as $getrate ) {
							?>
							<tr>
							<?php
							if ( $getrate->country == 'ALL' ) {
								$country = __( 'ALL', 'seur' );
							} else {
										$country = $getrate->country;
							}
							if ( $getrate->country == 'REST' ) {
								$country = __( 'REST', 'seur' );
							} else {
								$country = $getrate->country;
							}

							if ( $getrate->rateprice == '0' ) {
								$rateprice = __( 'FREE', 'seur' );
							} else {
								$rateprice = $getrate->rateprice;
							}
							if ( $getrate->maxprice == '9999999' ) {
								$maxrateprice = __( 'No limit', 'seur' );
							} else {
								$maxrateprice = $getrate->maxprice;
							}
							?>
							<!-- <td><?php echo esc_html( $getrate->ID ); ?></td> -->
							<td><?php echo esc_html( $getrate->rate ); ?></td>
							<td><?php echo esc_html( $country ); ?></td>
							<td><?php echo esc_html( $getrate->state ); ?></td>
							<td><?php echo esc_html( $getrate->postcode ); ?></td>
							<td><?php echo esc_html( $getrate->minprice ); ?></td>
							<td><?php echo esc_html( $maxrateprice ); ?></td>
							<td><?php echo esc_html( $rateprice ); ?></td>
							<td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="edit-link icon-pencil" href="#"></a></td>
							<td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="delete-link icon-cross" href="#"></a></td
								</tr>
							<?php
						}
					}
				}
				?>
				<thead>
					<tr>
						<!-- <th class="manage-column">ID</th> -->
						<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Min Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Max Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
					</tr>
				</thead>
			</tbody>
		</table>
	</div>
<?php } else { ?>
	<div class="content-loader">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<!-- <th class="manage-column">ID</th> -->
					<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Min Weight', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Max Weight', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
					<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$output_type = 'OBJECT';
				$type        = 'weight';
				$getrates    = seur_get_custom_rates( $output_type, $type );
			if ( ! $getrates ) {
				echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . esc_html__( 'No custom rates found, please add your Custom Rates', 'seur' ) . '</b></center><br /></td></tr>';
			} else {
				if ( $getrates ) {
					foreach ( $getrates as $getrate ) {
						?>
							<tr>
							<?php
							if ( $getrate->country == 'ALL' ) {
								$country = __( 'ALL', 'seur' );
							} else {
								$country = $getrate->country;
							}
							if ( $getrate->country == 'REST' ) {
								$country = __( 'REST', 'seur' );
							} else {
								$country = $getrate->country;
							}
							if ( $getrate->rateprice == '0' ) {
								$rateprice = __( 'FREE', 'seur' );
							} else {
								$rateprice = $getrate->rateprice;
							}
							if ( $getrate->maxprice == '9999999' ) {
								$maxrateprice = __( 'No limit', 'seur' );
							} else {
								$maxrateprice = $getrate->maxprice;
							}
							?>
								<!-- <td><?php echo esc_html( $getrate->ID ); ?></td> -->
								<td><?php echo esc_html( $getrate->rate ); ?></td>
								<td><?php echo esc_html( $country ); ?></td>
								<td><?php echo esc_html( $getrate->state ); ?></td>
								<td><?php echo esc_html( $getrate->postcode ); ?></td>
								<td><?php echo esc_html( $getrate->minprice ); ?></td>
								<td><?php echo esc_html( $maxrateprice ); ?></td>
								<td><?php echo esc_html( $rateprice ); ?></td>
								<td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="edit-link icon-pencil" href="#"></a></td>
								<td><a id="<?php echo esc_html( $getrate->ID ); ?>" class="delete-link icon-cross" href="#"></a></td>
							</tr>
						<?php
					}
				}
			}
			?>
				<thead>
					<tr>
						<!-- <th class="manage-column">ID</th> -->
						<th class="manage-column"><?php esc_html_e( 'Rate', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Country', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'State', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Postcode', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Min Weight', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Max Weight', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'edit', 'seur' ); ?></th>
						<th class="manage-column"><?php esc_html_e( 'delete', 'seur' ); ?></th>
					</tr>
				</thead>
			</tbody>
		</table>
	</div>
		<?php
}
?>
</div>
