<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
    <div class="container">
        <br>

        <p><?php _e( 'Max package price for apply rate price based on weight', 'seur' ); ?></p>

        <hr>
        <?php
            if ( ! isset( $_POST['seur_limit_price_weight_rates_post'] ) ) {
	            $seur_bc2_max_price_field  = '';
				$seur_10e_max_price_field  = '';
				$seur_10ef_max_price_field = '';
				$seur_13e_max_price_field  = '';
				$seur_13f_max_price_field  = '';
				$seur_48h_max_price_field  = '';
				$seur_72h_max_price_field  = '';
				$seur_cit_max_price_field  = '';

				$seur_bc2_max_price_field  = get_option( 'seur_bc2_max_price_field'  );
				$seur_10e_max_price_field  = get_option( 'seur_10e_max_price_field'  );
				$seur_10ef_max_price_field = get_option( 'seur_10ef_max_price_field' );
				$seur_13e_max_price_field  = get_option( 'seur_13e_max_price_field'  );
				$seur_13f_max_price_field  = get_option( 'seur_13f_max_price_field'  );
				$seur_48h_max_price_field  = get_option( 'seur_48h_max_price_field'  );
				$seur_72h_max_price_field  = get_option( 'seur_72h_max_price_field'  );
				$seur_cit_max_price_field  = get_option( 'seur_cit_max_price_field'  );

            ?>


        <div class="content-loader">
            <form method="post" action="admin.php?page=seur_rates_prices&tab=limit_price_weight_rates">

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">B2C Estándar</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>B2C Estándar" type="text" name="seur_bc2_max_price_field" value="<?php if ( $seur_bc2_max_price_field ) echo $seur_bc2_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 10 Estándar</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 10 Estándar" type="text" name="seur_10e_max_price_field" value="<?php if ( $seur_10e_max_price_field ) echo $seur_10e_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 10 Frío</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 10 Frío" type="text" name="seur_10ef_max_price_field" value="<?php if ( $seur_10ef_max_price_field ) echo $seur_10ef_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 13:30 Estándar</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 13:30 Estándar" type="text" name="seur_13e_max_price_field" value="<?php if ( $seur_13e_max_price_field ) echo $seur_13e_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 13:30 Frío</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 13:30 Frío" type="text" name="seur_13f_max_price_field" value="<?php if ( $seur_13f_max_price_field ) echo $seur_13f_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 48H Estándar</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 48H Estándar" type="text" name="seur_48h_max_price_field" value="<?php if ( $seur_48h_max_price_field ) echo $seur_48h_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 72H Estándar</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>SEUR 72H Estándar" type="text" name="seur_72h_max_price_field" value="<?php if ( $seur_72h_max_price_field ) echo $seur_72h_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">Classic Internacional Terrestre</th>

                            <td><input title="<?php _e('Max package price for ', 'seur'); ?>Classic Internacional Terrestre" type="text" name="seur_cit_max_price_field" value="<?php if ( $seur_cit_max_price_field ) echo $seur_cit_max_price_field ?>" size="40"></td>
                        </tr>
                        <input type="hidden" name="seur_limit_price_weight_rates_post" value="true" >
                        <?php wp_nonce_field( 'seur_limit_price_weight_rates', 'seur_limit_price_weight_rates_nonce_field' ); ?>

                    </tbody>
                </table>

                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
            </form>
        </div>

        <?php } else {
	        if ( isset( $_POST['seur_limit_price_weight_rates_post'] ) && ( ! isset( $_POST['seur_limit_price_weight_rates_nonce_field'] )  || ! wp_verify_nonce( $_POST['seur_limit_price_weight_rates_nonce_field'], 'seur_limit_price_weight_rates' ) ) ) {
        print 'Sorry, your nonce did not verify.';
        exit;

    } else {
	    		$seur_bc2_max_price_field  = sanitize_text_field( $_POST[ 'seur_bc2_max_price_field'  ] );
				$seur_10e_max_price_field  = sanitize_text_field( $_POST[ 'seur_10e_max_price_field'  ] );
				$seur_10ef_max_price_field = sanitize_text_field( $_POST[ 'seur_10ef_max_price_field' ] );
				$seur_13e_max_price_field  = sanitize_text_field( $_POST[ 'seur_13e_max_price_field'  ] );
				$seur_13f_max_price_field  = sanitize_text_field( $_POST[ 'seur_13f_max_price_field'  ] );
				$seur_48h_max_price_field  = sanitize_text_field( $_POST[ 'seur_48h_max_price_field'  ] );
				$seur_72h_max_price_field  = sanitize_text_field( $_POST[ 'seur_72h_max_price_field'  ] );
				$seur_cit_max_price_field  = sanitize_text_field( $_POST[ 'seur_cit_max_price_field'  ] );

				update_option ( 'seur_bc2_max_price_field', $seur_bc2_max_price_field   );
				update_option ( 'seur_10e_max_price_field', $seur_10e_max_price_field   );
				update_option ( 'seur_10ef_max_price_field',$seur_10ef_max_price_field  );
				update_option ( 'seur_13e_max_price_field', $seur_13e_max_price_field   );
				update_option ( 'seur_13f_max_price_field', $seur_13f_max_price_field   );
				update_option ( 'seur_48h_max_price_field', $seur_48h_max_price_field   );
				update_option ( 'seur_72h_max_price_field', $seur_72h_max_price_field   );
				update_option ( 'seur_cit_max_price_field', $seur_cit_max_price_field   );

				$seur_bc2_max_price_field  = get_option( 'seur_bc2_max_price_field'  );
				$seur_10e_max_price_field  = get_option( 'seur_10e_max_price_field'  );
				$seur_10ef_max_price_field = get_option( 'seur_10ef_max_price_field' );
				$seur_13e_max_price_field  = get_option( 'seur_13e_max_price_field'  );
				$seur_13f_max_price_field  = get_option( 'seur_13f_max_price_field'  );
				$seur_48h_max_price_field  = get_option( 'seur_48h_max_price_field'  );
				$seur_72h_max_price_field  = get_option( 'seur_72h_max_price_field'  );
				$seur_cit_max_price_field  = get_option( 'seur_cit_max_price_field'  );
        ?>

         <div class="content-loader">
            <form method="post" action="admin.php?page=seur_rates_prices&tab=limit_price_weight_rates">

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">B2C Estándar</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_bc2_max_price_field" value="<?php if ( $seur_bc2_max_price_field ) echo $seur_bc2_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 10 Estándar</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_10e_max_price_field" value="<?php if ( $seur_10e_max_price_field ) echo $seur_10e_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 10 Frío</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_10ef_max_price_field" value="<?php if ( $seur_10ef_max_price_field ) echo $seur_10ef_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 13:30 Estándar</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_13e_max_price_field" value="<?php if ( $seur_13e_max_price_field ) echo $seur_13e_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 13:30 Frío</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_13f_max_price_field" value="<?php if ( $seur_13f_max_price_field ) echo $seur_13f_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 48H Estándar</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_48h_max_price_field" value="<?php if ( $seur_48h_max_price_field ) echo $seur_48h_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">SEUR 72H Estándar</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_72h_max_price_field" value="<?php if ( $seur_72h_max_price_field ) echo $seur_72h_max_price_field ?>" size="40"></td>
                        </tr>

                        <tr>
                            <th scope="row">Classic Internacional Terrestre</th>

                            <td><input title="NIF- NIF de la empresa" type="text" name="seur_cit_max_price_field" value="<?php if ( $seur_cit_max_price_field ) echo $seur_cit_max_price_field ?>" size="40"></td>
                        </tr>

                    </tbody>
                </table>

                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
            </form>
        </div><?php } }?>
    </div>
