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
function seur_donwload_data( $post ) {
	global $wpdb;	?>

    <div class="wrap">
	    <h1 class="wp-heading-inline"><?php esc_html_e( 'SEUR Manifest', 'seur' ); ?></h1>
	    <?php esc_html_e( 'Generate the cargo manifest of your deliveries and print two copies for the carrier.', 'seur' );
        if ( isset( $_POST['fechadesde'] ) && ( ! isset( $_POST['seur_manifest_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['seur_manifest_nonce_field'] ) ), 'seur_manifest_action' ) ) ) {
            die ('Sorry, your nonce did not verify.</div>');
        }

        $nif               = get_option( 'seur_nif_field' );
        $codigo_franquicia = get_option( 'seur_franquicia_field' );
        $accountId = get_option( 'seur_ccc_field' );
        $fecha_desde       = '';

        if ( isset( $_POST['fechadesde'] ) ) {

            $date = str_replace( '/', '', sanitize_text_field( wp_unslash( $_POST['fechadesde'] ) ) );

            $fromdate = str_replace('+00:00','', date('c', strtotime(date('Y-m-d')))) .'Z';
	        $todate = str_replace('+00:00','', date('c', strtotime(date('Y-m-dT23:59:59')))) .'Z';
            // Manifiesto con fecha.
            if ( $date > 0 ) {
                if ( $date < 8 || ( isset( $_POST['horadesde'] ) && strlen( sanitize_text_field( wp_unslash( $_POST['horadesde'] ) ) ) < 6 ) ) {
                    die ( 'Fecha/Hora no están en el formato adecuado</div>');
                }
                $fromdate = str_replace('+00:00','', date('c', strtotime($_POST['fechadesde']))) .'Z';
            }

            $urlws = seur()->get_api_addres() . SEUR_API_MANIFEST;

            if ( ! seur_api_check_url_exists( $urlws ) ) {
                die( 'We&apos;re sorry, SEUR API is down. Please try again in few minutes</div>');
            }

            $token = seur()->get_token_b();
            if (!$token) {
                die ( 'Invalid token access</div>');
            }

            $headers[] = "Accept: */*";
            $headers[] = "Content-Type: application/json";
            $headers[] = "Authorization: ".$token;

            $data = [
                'nif' => $nif,
                'franchise' => $codigo_franquicia,
                'dateFrom' => $fromdate,
                'dateTo' => $todate,
                'accountId' => $accountId
            ];

            $response = seur()->sendCurl($urlws, $headers, $data, "GET");

            if (isset($response->errors)) {
                seur()->slog('seur_api_manifest Error: '.$response->errors[0]->detail);
                die ('seur_api_manifest Error: '.$response->errors[0]->detail.'</div>');
            }

            if (!isset($response)) {
                die ('seur_api_manifest Error: No response</div>');
            }

            if (!empty($response)) {

	            $log = new WC_Logger();
	            $log->add( 'seur', 'seur_api_manifest response: ' . print_r( $response, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

	            $pdf       = base64_decode( $response ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
	            $file_name = 'manifiesto_' . date( 'd-m-Y' ) . '.pdf'; // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	            $path      = SEUR_UPLOADS_MANIFEST_PATH . '/' . $file_name;
	            file_put_contents( $path, $pdf ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

	            $download_file = SEUR_UPLOADS_MANIFEST_URL . '/' . $file_name;
	            echo '<a href="' . esc_url( $download_file ) . '" target="_blank" class="button" download>' . esc_html__( ' Open Manifest ', 'seur' ) . '</a><br />';
            } else {
	            echo '<p>' . esc_html__( ' Manifest does not include any delivery ', 'seur' ) . '<p/>';
            }
        } else { ?>
            <form method='post'  name='formulario' width='100%'>
                <div class="wp-filter">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'From Date', 'seur' ); ?></span>
                        <input id="datepicker" type='text' name='fechadesde' class="wp-filter-search" placeholder="<?php esc_html_e( 'From Date', 'seur' ); ?>" value=''>
                    </label>
                    <input type='hidden' name='horadesde' value='000000'>
                    <?php wp_nonce_field( 'seur_manifest_action', 'seur_manifest_nonce_field' ); ?>
                    <label>
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Submit', 'seur' ); ?>">
                    </label>
                </div>
                <p class="description"><?php esc_html_e( "If you don't have shipments created other days, you don't need to enter a date", 'seur' ); ?></p>
            </form>
            <?php
        }
        ?>
    </div>
	<?php
}
