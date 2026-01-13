<?php
/**
 * SEUR Nomenclator
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR search nomenclator.
 *
 * @param WP_Post $post Post data.
 */
function seur_search_nomenclator( $post ) { ?>
    <form method="post" name="formulario" width="100%">
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Nomenclator', 'seur' ); ?></h1>
        <?php
        if ( isset( $_POST['codigo_postal'] ) ) {
            ?>
            <a href="admin.php?page=seur_search_nomenclator" class="page-title-action"><?php esc_html_e( 'New Search', 'seur' ); ?></a>
            <?php
        }
        ?>
        <hr class="wp-header-end">
        <p><?php esc_html_e( 'Check ZIP or city associated to Seur system.', 'seur' ); ?></p>

		<?php
		if ( isset( $_POST['codigo_postal'] ) ) {
			if ( ! isset( $_POST['nomenclator_seur_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nomenclator_seur_nonce_field'] ) ), 'nomenclator_seur' ) ) {
				print 'Sorry, your nonce did not verify.';
				exit;
			} else {
				$unsafe_zipcode = trim( sanitize_text_field( wp_unslash( $_POST['codigo_postal'] ) ) );
				if ( ! $unsafe_zipcode ) {
					$safe_zipcode = '';
				} else {
					$safe_zipcode = sanitize_text_field( $unsafe_zipcode );
				}

				$urlws = seur()->get_api_addres() . SEUR_API_CITIES;
				if ( ! seur_api_check_url_exists( $urlws ) ) {
					esc_html_e( 'There is a problem connecting to SEUR. Please try again later.', 'seur' );
				} else {
                    $pais = 'ES';
                    $datos = [
                        'countryCode' => $pais,
                        'postalCode' => $safe_zipcode
                    ];
                    $result = seur()->seur_api_check_city( $datos );
                    if ( $result === false ) {
                        echo "<hr><b><font color='#e53920'>";
                        echo '<br>Código postal y país no se han encontrado en Nomenclator de SEUR.<br></font>';
                        echo "<font color='#0074a2'><br>Par no Encontrado:<br>" . esc_html( $safe_zipcode ) . ' - ' . esc_html( $pais );
                        return;
                    }
                    ?>
                    <ul class="subsubsub">
                        <li class="all">
                            <?php seur_search_number_message_result( count($result) ); ?>
                        </li>
                    </ul>
                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                            <tr>
                                <td class="manage-column">
                                    <?php esc_html_e( 'POSTCODE', 'seur' ); ?>
                                <td class="manage-column">
                                    <?php esc_html_e( 'CITY', 'seur' ); ?>
                                </td>
                            </tr>
                        </thead>
                        <?php
                        foreach ( $result as $item) {
                            echo '<tr><td><a href="https://www.google.es/maps/search/' . esc_html( $item['cityName'] ) . '+' .
                                esc_html( $item['postalCode'] ) . '+seur" target="_blank">' . esc_html( $item['postalCode'] ) . '</a>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                            echo '</td>';
                            echo '<td>' . esc_html( $item['cityName'] ) . '</td>'; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
                        }
                        ?>
                        <tfoot>
                            <tr>
                                <td class="manage-column"><?php esc_html_e( 'POSTCODE', 'seur' ); ?></td>
                                <td class="manage-column"><?php esc_html_e( 'CITY', 'seur' ); ?></td>
                            </tr>
                        </tfoot>
                    </table>
					<?php
                }
            }
	    } ?>
    </div>
	<div class="wp-filter">
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'City', 'seur' ); ?></span>
			<input type='text' name='nombre_poblacion' class="wp-filter-search" placeholder="<?php esc_html_e( 'City', 'seur' ); ?>" value=''>
		</label>
		<label>
			<span class="screen-reader-text"><?php esc_html_e( 'Postal code', 'seur' ); ?></span>
			<input type='text' name='codigo_postal' class="wp-filter-search" placeholder="<?php esc_html_e( 'Postalcode', 'seur' ); ?>" value='' size="12">
		</label>
		<label>
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Search">
		</label>
	</div>
    <?php wp_nonce_field( 'nomenclator_seur', 'nomenclator_seur_nonce_field' ); ?>
    </form>
    <?php
}