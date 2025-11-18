<?php
/**
 * SEUR Nomenclator
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include_once dirname( __FILE__ ) . '/../woocommerce/includes/class-seur_local_shipping_method.php';

/**
 * SEUR search pickup locations.
 */
function seur_search_pickup_locations() {
    $sanitized_data = seur_search_pickup_locations_sanitize_input();
    $valid_data = !(empty($sanitized_data['seur_country']) or empty($sanitized_data['nombre_poblacion']) or empty($sanitized_data['codigo_postal']));

    seur_search_pickup_locations_form(
        $sanitized_data,
        $valid_data,
        function() use ($valid_data, $sanitized_data) {
            if ($valid_data) {
                return seur_search_pickup_locations_process_request($sanitized_data);
            }
            return true;
        }
    );
}

function seur_search_pickup_locations_sanitize_input(): array
{
    $data = [];
    $inputs = [
        'nombre_poblacion',
        'codigo_postal',
        'seur_country',
    ];

    foreach ( $inputs as $input ) {
        if ( isset( $_POST[ $input ] ) ) {
            $data[ $input ] = trim( sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) );
        }
    }

    return $data;
}

function seur_search_pickup_locations_process_request(array $input): bool
{
    $nonce = $_POST['pickup_search_seur_nonce_field'];
    if ( ! isset( $nonce ) or
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $nonce ) ), 'pickup_search_seur' ) ) {
        esc_html_e('Sorry, your nonce did not verify.', 'seur');
        return false;
    }

    $result = seur_get_local_pickups( $input['seur_country'], $input['nombre_poblacion'], $input['codigo_postal'] );
    if ( $result === false ) {
        seur_search_pickup_locations_form_error( $input['codigo_postal'], $input['seur_country'], $input['nombre_poblacion'] );
        return false;
    } else {
        seur_search_pickup_locations_form_data( $result );
        return true;
    }
}

function seur_search_pickup_locations_translate_schedule(string $text): string
{
    $days = [
            'monday'    => __( 'Monday' ),
            'tuesday'   => __( 'Tuesday' ),
            'wednesday' => __( 'Wednesday' ),
            'thursday'  => __( 'Thursday' ),
            'friday'    => __( 'Friday' ),
            'saturday'  => __( 'Saturday' ),
            'sunday'    => __( 'Sunday' ),
    ];

    foreach ( $days as $en => $translated ) {
        $text = str_ireplace( $en, $translated, $text );
    }

    return $text;
}

function seur_search_pickup_locations_form( array $input, bool $new_search_button, callable $query_data_callback ) { ?>
    <form method="post" name="formulario" width="100%">
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Pick-up locations search', 'seur' ); ?></h1>
            <?php if ( $new_search_button ) { ?>
                <a href="admin.php?page=seur_search_pickup_locations" class="page-title-action">
                    <?php esc_html_e( 'New Search', 'seur' ); ?>
                </a>
            <?php } ?>
            <hr class="wp-header-end">
            <p><?php esc_html_e( 'Search SEUR database for pick-up locations available for your business.', 'seur' ); ?></p>

            <?php $draw_query_input_fields = $query_data_callback(); ?>

        </div>
        <?php if ( $draw_query_input_fields ) { ?>
            <div class="wp-filter">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Postal code', 'seur' ); ?></span>
                    <input type='text' name='codigo_postal' class="wp-filter-search" size="12"
                           placeholder="<?php esc_html_e( 'Postal code', 'seur' ); ?>"
                           value='<?php if ( ! empty( $input['codigo_postal'] ) ) { echo esc_html( $input['codigo_postal'] ); } ?>'
                           required>
                </label>
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'City', 'seur' ); ?></span>
                    <input type='text' name='nombre_poblacion' class="wp-filter-search"
                           placeholder="<?php esc_html_e( 'City', 'seur' ); ?>"
                           value='<?php if ( ! empty( $input['nombre_poblacion'] ) ) { echo esc_html( $input['nombre_poblacion'] ); } ?>'
                           required>
                </label>
                <label>
                    <span class="screen-reader-text"><?php esc_html_e( 'Country', 'seur' ); ?></span>
                    <select class="select country" id="seur_country" title="<?php esc_html_e( 'Select Country', 'seur' ); ?>" name="seur_country" required>
                        <?php seur_search_pickup_locations_form_countries( $input['seur_country'] ?? '', seur_get_countries()); ?>
                    </select>
                </label>
                <label>
                    <input type="submit" name="submit" id="submit" class="button button-primary"
                           value="<?php esc_html_e( 'Search', 'seur' ); ?>">
                </label>
            </div>
        <?php } ?>
        <?php wp_nonce_field( 'pickup_search_seur', 'pickup_search_seur_nonce_field' ); ?>
    </form>
<?php }


function seur_search_pickup_locations_form_countries(string $selected_country, array $countries) { ?>
    <option value=""><?= esc_html__( 'Select', 'seur' ); ?></option>
    <option value="ES" <?= $selected_country === 'ES' ? esc_html('selected' ) : '' ?> >
        <?= esc_html__( 'Spain', 'seur' ); ?>
    </option>
    <option value="PT" <?= $selected_country === 'PT' ? esc_html('selected' ) : '' ?> >
        <?= esc_html__( 'Portugal', 'seur' ); ?>
    </option>
    <option value="AD" <?= $selected_country === 'AD' ? esc_html('selected' ) : '' ?> >
        <?= esc_html__( 'Andorra', 'seur' ); ?>
    </option>
    <?php foreach ( $countries as $country => $value ) { ?>
        <option value="<?= esc_html( $country ); ?>" <?= $selected_country === $country ? esc_html('selected' ) : '' ?> >
            <?= esc_html( $value ); ?>
        </option>
    <?php }
}

function seur_search_pickup_locations_form_error( string $zipcode, string $country, string $city ) { ?>
    <hr><br/>
    <b style="color: #e53920"><?php esc_html_e( 'Postal code and country not found in pick-up locations database.', 'seur' ); ?></b><br/>
    <br/>
    <b style="color: #0074a2"><?php esc_html_e( 'Your data:', 'seur' ); ?></b><br/>
    <b><?php echo esc_html( $zipcode )  . ' - ' . esc_html( $country ) . ' - ' . esc_html( $city ); ?></b><br/>
    <br/>
<?php }

function seur_search_pickup_locations_form_data(array $result ) { ?>
    <ul class="subsubsub">
        <li class="all">
            <?php seur_search_number_message_result( count($result) ); ?>
        </li>
    </ul>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <td class="manage-column"><?php esc_html_e( 'PUDO ID', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Name', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Address', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Schedule', 'seur' ); ?></td>
        </tr>
        </thead>
        <?php foreach ( $result as $item ) {
            seur_search_pickup_locations_form_data_row( $item );
        } ?>
        <tfoot>
        <tr>
            <td class="manage-column"><?php esc_html_e( 'PUDO ID', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Name', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Address', 'seur' ); ?></td>
            <td class="manage-column"><?php esc_html_e( 'Schedule', 'seur' ); ?></td>
        </tr>
        </tfoot>
    </table>
<?php }

function seur_search_pickup_locations_form_data_row(array $item) { ?>
    <tr>
        <td>
            <?= esc_html( $item['pudoId'] ) ?> <?php // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase ?>
        </td>
        <td>
            <?= esc_html( $item['company'] ) ?>
        </td>
        <td>
            <a href="<?= seur_search_pickup_locations_form_data_row_maps_link($item); ?>"
               target="_blank" rel="noopener">
                <?= esc_html( $item['tipovia'] ) ?> <?= esc_html( $item['address'] ) ?> <?= esc_html( $item['numvia'] ) ?><br/>
                <?= esc_html( $item['post_code'] ) ?> <?= esc_html( $item['city'] ) ?>
            </a>
        </td>
        <td>
            <?= wp_kses_post( seur_search_pickup_locations_translate_schedule( $item['timetable'] ) ) ?>
        </td>
    </tr>
<?php }

function seur_search_pickup_locations_form_data_row_maps_link(array $item)
{
    $query = sprintf('%.6f', $item['lat']) . ',' . sprintf('%.6f', $item['lng']);

    return esc_url("https://www.google.com/maps/search/?api=1&query=" . urlencode($query) );
}
