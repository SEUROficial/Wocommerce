<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$rates_type = get_option( 'seur_rates_type_field' );
?>
<div class="container">
	<br />
	<p><?php _e( 'Include the rates of the transport options that your customers can choose', 'seur' ); ?></p>
	<p><?php echo __( 'Your rates are based on', 'seur' ) . ' <b>' . $rates_type . '</b> '; ?></p>
	<p><a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php  _e( 'Please, set based rates price here', 'seur' ); ?></a></p>
	<br />
	<button class="button" type="button" id="btn-add"><?php _e('Add Custom Rate', 'seur' ); ?></button>
	<button class="button" type="button" id="btn-view"><?php _e('View Custom Rates', 'seur' ); ?></button>
	<hr>
	<?php
	if ( $rates_type == 'price') { ?>
	<div class="content-loader">
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<!-- <th class="manage-column">ID</th> -->
					<th class="manage-column"><?php _e('Rate', 		 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Country', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('State', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Postcode', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Min Price',  'seur' ); ?></th>
					<th class="manage-column"><?php _e('Max Price',  'seur' ); ?></th>
					<th class="manage-column"><?php _e('Rate Price', 'seur' ); ?></th>
					<th class="manage-column"><?php _e('edit', 		 'seur' ); ?></th>
					<th class="manage-column"><?php _e('delete', 	 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$output_type = 'OBJECT';
					$type 		 = 'price';
					$getrates 	 = seur_get_custom_rates( $output_type, $type );
					if ( ! $getrates ) {
						echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . __('No custom rates found, please add your Custom Rates', 'seur' ) . '</b></center><br /></td></tr>';
					} else {
						if ( $getrates ) {
							foreach ( $getrates as $getrate ) { ?>
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
										} ?>
										<!-- <td><?php echo $getrate->ID; ?></td> -->
									<td><?php echo $getrate->rate; ?></td>
									<td><?php echo $country; ?></td>
									<td><?php echo $getrate->state; ?></td>
									<td><?php echo $getrate->postcode; ?></td>
									<td><?php echo $getrate->minprice; ?></td>
									<td><?php echo $maxrateprice; ?></td>
									<td><?php echo $rateprice; ?></td>
									<td><a id="<?php echo $getrate->ID; ?>" class="edit-link icon-pencil" href="#"></a></td>
									<td><a id="<?php echo $getrate->ID; ?>" class="delete-link icon-cross" href="#"></a></td
								</tr>
						<?php }
						}
					}?>
				<thead>
					<tr>
						<!-- <th class="manage-column">ID</th> -->
						<th class="manage-column"><?php _e('Rate', 		 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Country', 	 'seur' ); ?></th>
						<th class="manage-column"><?php _e('State', 	 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Postcode',   'seur' ); ?></th>
						<th class="manage-column"><?php _e('Min Price',  'seur' ); ?></th>
						<th class="manage-column"><?php _e('Max Price',	 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php _e('edit', 		 'seur' ); ?></th>
						<th class="manage-column"><?php _e('delete', 	 'seur' ); ?></th>
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
					<th class="manage-column"><?php _e('Rate', 		 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Country', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('State', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Postcode', 	 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Min Weight', 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Max Weight', 'seur' ); ?></th>
					<th class="manage-column"><?php _e('Rate Price', 'seur' ); ?></th>
					<th class="manage-column"><?php _e('edit', 		 'seur' ); ?></th>
					<th class="manage-column"><?php _e('delete', 	 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$output_type = 'OBJECT';
				$type 		 = 'weight';
				$getrates 	 = seur_get_custom_rates( $output_type, $type );
				if ( ! $getrates ) {
					echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . __('No custom rates found, please add your Custom Rates', 'seur' ) . '</b></center><br /></td></tr>';
				} else {
					if ( $getrates ) {
						foreach ( $getrates as $getrate ) { ?>
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
								} ?>
								<!-- <td><?php echo $getrate->ID; ?></td> -->
								<td><?php echo $getrate->rate; ?></td>
								<td><?php echo $country; ?></td>
								<td><?php echo $getrate->state; ?></td>
								<td><?php echo $getrate->postcode; ?></td>
								<td><?php echo $getrate->minprice; ?></td>
								<td><?php echo $maxrateprice; ?></td>
								<td><?php echo $rateprice; ?></td>
								<td><a id="<?php echo $getrate->ID; ?>" class="edit-link icon-pencil" href="#"></a></td>
								<td><a id="<?php echo $getrate->ID; ?>" class="delete-link icon-cross" href="#"></a></td>
							</tr>
						<?php
						}
					}
				}?>
				<thead>
					<tr>
						<!-- <th class="manage-column">ID</th> -->
						<th class="manage-column"><?php _e('Rate', 		 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Country', 	 'seur' ); ?></th>
						<th class="manage-column"><?php _e('State', 	 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Postcode',   'seur' ); ?></th>
						<th class="manage-column"><?php _e('Min Weight', 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Max Weight', 'seur' ); ?></th>
						<th class="manage-column"><?php _e('Rate Price', 'seur' ); ?></th>
						<th class="manage-column"><?php _e('edit', 		 'seur' ); ?></th>
						<th class="manage-column"><?php _e('delete', 	 'seur' ); ?></th>
					</tr>
				</thead>
			</tbody>
		</table>
	</div>
	<?php
	}
	
	if ( defined('SEUR_DEBUG') && SEUR_DEBUG === true ) {
		echo 'COUNTRY ES<br />';
		$countryids = seur_search_allowed_rates_by_country( 'ES' );
		echo '<pre>';
		var_dump( $countryids );
		echo '</pre>';
		echo 'STATE B <br />';
		$states = seur_seach_allowed_states_filtered_by_countries( 'B', $countryids );
		echo '<pre>';
		var_dump( $states );
		echo '</pre>';
		echo 'POSTCODES *<br />';
		$postcodes = seur_seach_allowed_postcodes_filtered_by_states( '*', $states );
		echo '<pre>';
		var_dump( $postcodes );
		echo '</pre>';
		echo 'PRICE 10<br />';
		$prices = seur_seach_allowed_prices_filtered_by_postcode( '10', $postcodes );
		echo '<pre>';
		var_dump( $prices );
		echo '</pre>';
		echo 'COUNTRY 50, STATE B, POSTCODE 08023, PRICE 50<br />';
		// seur_show_availables_rates( $country, $state = NULL, $postcode = NULL, $price = NULL )
		$prueba = seur_show_availables_rates( 'ES', 'B','08023' , '50');
		echo '<pre>';
		var_dump( $prueba );
		echo '</pre>';
	}
	?>
</div>