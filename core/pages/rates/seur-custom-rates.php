<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="container">
	<br />
    <button class="button" type="button" id="btn-add"><?php _e('Add Custom Rate', SEUR_TEXTDOMAIN ); ?></button>
    <button class="button" type="button" id="btn-view"><?php _e('View Custom Rates', SEUR_TEXTDOMAIN ); ?></button>
	<hr>
    <div class="content-loader">
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <!-- <th class="manage-column">ID</th> -->

                    <th class="manage-column"><?php _e('Rate', 		 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Country', 	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('State', 	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Postcode', 	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Min Price',  SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Max Price',  SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Rate Price', SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('edit', 		 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('delete', 	 SEUR_TEXTDOMAIN ); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php

					$getrates = seur_get_custom_rates();

					 if ( ! $getrates ) {

					 	echo '<tr class="no-items"><td class="colspanchange" colspan="9"><br /><center><b>' . __('No custom rates found, please add your Custom Rates', SEUR_TEXTDOMAIN ) . '</b></center><br /></td></tr>';

						} else {

							if ( $getrates ) {
								foreach ( $getrates as $getrate ){ ?>

							<tr>
							<?php
								if ( $getrate->country == 'ALL' ) {
									$country = __( 'ALL', SEUR_TEXTDOMAIN );
								} else {
									$country = $getrate->country;
								}
								if ( $getrate->country == 'REST' ) {
									$country = __( 'REST', SEUR_TEXTDOMAIN );
								} else {
									$country = $getrate->country;
								}

								if ( $getrate->rateprice == '0' ) {
									$rateprice = __( 'FREE', SEUR_TEXTDOMAIN );
								} else {
									$rateprice = $getrate->rateprice;
								}
								if ( $getrate->maxprice == '9999999' ) {
									$maxrateprice = __( 'No limit', SEUR_TEXTDOMAIN );
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

			                    <td><a id="<?php echo $getrate->ID; ?>" class="edit-link icon-pencil" href="#" title="Edit"></a></td>

			                    <td><a id="<?php echo $getrate->ID; ?>" class="delete-link icon-cross" href="#" title="Delete"></a></td>
			                </tr>
							<?php }
							}
						}?>
			<thead>
                <tr>
                    <!-- <th class="manage-column">ID</th> -->

                    <th class="manage-column"><?php _e('Rate', 		 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Country', 	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('State', 	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Postcode',   SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Min Price',  SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Max Price',	 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('Rate Price', SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('edit', 		 SEUR_TEXTDOMAIN ); ?></th>

                    <th class="manage-column"><?php _e('delete', 	 SEUR_TEXTDOMAIN ); ?></th>
                </tr>
            </thead>

            </tbody>
        </table>
    </div>

    <?php
	    if ( defined('SEUR_DEBUG') && SEUR_DEBUG == true ) {

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