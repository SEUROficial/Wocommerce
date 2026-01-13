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
    $valid_data = !(empty($sanitized_data['seur_country']) || empty($sanitized_data['nombre_poblacion']) || empty($sanitized_data['codigo_postal']));

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

/**
 * Sanitiza los datos de entrada del formulario.
 *
 * @return array
 */
function seur_search_pickup_locations_sanitize_input(): array {
    $data   = [];
    $inputs = [
        'nombre_poblacion',
        'codigo_postal',
        'seur_country',
    ];

    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in process_request function.
    if (empty($_POST)) {
        return $data;
    }

    foreach ($inputs as $input) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in process_request function.
        if (isset($_POST[$input])) {
            // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification happens in process_request function.
            $data[$input] = trim(sanitize_text_field(wp_unslash($_POST[$input])));
        }
    }

    return $data;
}

/**
 * Procesa la solicitud de búsqueda de puntos de recogida.
 *
 * @param array $input Datos sanitizados del formulario.
 * @return bool
 */
function seur_search_pickup_locations_process_request(array $input): bool {
    if (!isset($_POST['pickup_search_seur_nonce_field'])) {
        esc_html_e('Sorry, your nonce did not verify.', 'seur');
        return false;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['pickup_search_seur_nonce_field']));
    if (!wp_verify_nonce($nonce, 'pickup_search_seur')) {
        esc_html_e('Sorry, your nonce did not verify.', 'seur');
        return false;
    }

    $result = seur_get_local_pickups($input['seur_country'], $input['nombre_poblacion'], $input['codigo_postal']);
    if (false === $result) {
        seur_search_pickup_locations_form_error($input['codigo_postal'], $input['seur_country'], $input['nombre_poblacion']);
        return false;
    } else {
        seur_search_pickup_locations_form_data($result);
        return true;
    }
}

/**
 * Traduce los días del horario.
 *
 * @param string $text Texto con días en inglés.
 * @return string
 */
function seur_search_pickup_locations_translate_schedule(string $text): string {
    $days = [
        'monday'    => __('Monday', 'seur'),
        'tuesday'   => __('Tuesday', 'seur'),
        'wednesday' => __('Wednesday', 'seur'),
        'thursday'  => __('Thursday', 'seur'),
        'friday'    => __('Friday', 'seur'),
        'saturday'  => __('Saturday', 'seur'),
        'sunday'    => __('Sunday', 'seur'),
    ];

    foreach ($days as $en => $translated) {
        $text = str_ireplace($en, $translated, $text);
    }

    return $text;
}

/**
 * Muestra el formulario de búsqueda.
 *
 * @param array    $input                  Datos del formulario.
 * @param bool     $new_search_button      Si mostrar botón de nueva búsqueda.
 * @param callable $query_data_callback    Callback para procesar datos.
 */
function seur_search_pickup_locations_form(array $input, bool $new_search_button, callable $query_data_callback) {
    ?>
    <form method="post" name="formulario" width="100%">
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Pick-up locations search', 'seur'); ?></h1>
            <?php if ($new_search_button) { ?>
                <a href="admin.php?page=seur_search_pickup_locations" class="page-title-action">
                    <?php esc_html_e('New Search', 'seur'); ?>
                </a>
            <?php } ?>
            <hr class="wp-header-end">
            <p><?php esc_html_e('Search SEUR database for pick-up locations available for your business.', 'seur'); ?></p>

            <?php $draw_query_input_fields = $query_data_callback(); ?>

        </div>
        <?php if ($draw_query_input_fields) { ?>
            <div class="wp-filter">
                <label>
                    <span class="screen-reader-text"><?php esc_html_e('Postal code', 'seur'); ?></span>
                    <input type='text' name='codigo_postal' class="wp-filter-search" size="12"
                           placeholder="<?php esc_html_e('Postal code', 'seur'); ?>"
                           value='<?php echo !empty($input['codigo_postal']) ? esc_attr($input['codigo_postal']) : ''; ?>'
                           required>
                </label>
                <label>
                    <span class="screen-reader-text"><?php esc_html_e('City', 'seur'); ?></span>
                    <input type='text' name='nombre_poblacion' class="wp-filter-search"
                           placeholder="<?php esc_html_e('City', 'seur'); ?>"
                           value='<?php echo !empty($input['nombre_poblacion']) ? esc_attr($input['nombre_poblacion']) : ''; ?>'
                           required>
                </label>
                <label>
                    <span class="screen-reader-text"><?php esc_html_e('Country', 'seur'); ?></span>
                    <select class="select country" id="seur_country" title="<?php esc_html_e('Select Country', 'seur'); ?>" name="seur_country" required>
                        <?php seur_search_pickup_locations_form_countries($input['seur_country'] ?? '', seur_get_countries()); ?>
                    </select>
                </label>
                <label>
                    <input type="submit" name="submit" id="submit" class="button button-primary"
                           value="<?php esc_html_e('Search', 'seur'); ?>">
                </label>
            </div>
        <?php } ?>
        <?php wp_nonce_field('pickup_search_seur', 'pickup_search_seur_nonce_field'); ?>
    </form>
    <?php
}

/**
 * Muestra las opciones de países en el select.
 *
 * @param string $selected_country País seleccionado.
 * @param array  $countries        Lista de países.
 */
function seur_search_pickup_locations_form_countries(string $selected_country, array $countries) {
    ?>
    <option value=""><?php echo esc_html__('Select', 'seur'); ?></option>
    <option value="ES" <?php echo 'ES' === $selected_country ? 'selected' : ''; ?>>
        <?php echo esc_html__('Spain', 'seur'); ?>
    </option>
    <option value="PT" <?php echo 'PT' === $selected_country ? 'selected' : ''; ?>>
        <?php echo esc_html__('Portugal', 'seur'); ?>
    </option>
    <option value="AD" <?php echo 'AD' === $selected_country ? 'selected' : ''; ?>>
        <?php echo esc_html__('Andorra', 'seur'); ?>
    </option>
    <?php foreach ($countries as $country => $value) { ?>
        <option value="<?php echo esc_attr($country); ?>" <?php echo $selected_country === $country ? 'selected' : ''; ?>>
            <?php echo esc_html($value); ?>
        </option>
    <?php }
}

/**
 * Muestra mensaje de error cuando no se encuentran resultados.
 *
 * @param string $zipcode Código postal.
 * @param string $country País.
 * @param string $city    Ciudad.
 */
function seur_search_pickup_locations_form_error(string $zipcode, string $country, string $city) {
    ?>
    <hr><br/>
    <b style="color: #e53920"><?php esc_html_e('Postal code and country not found in pick-up locations database.', 'seur'); ?></b><br/>
    <br/>
    <b style="color: #0074a2"><?php esc_html_e('Your data:', 'seur'); ?></b><br/>
    <b><?php echo esc_html($zipcode) . ' - ' . esc_html($country) . ' - ' . esc_html($city); ?></b><br/>
    <br/>
    <?php
}

/**
 * Muestra la tabla con los resultados de búsqueda.
 *
 * @param array $result Resultados de la búsqueda.
 */
function seur_search_pickup_locations_form_data(array $result) {
    ?>
    <ul class="subsubsub">
        <li class="all">
            <?php seur_search_number_message_result(count($result)); ?>
        </li>
    </ul>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <td class="manage-column"><?php esc_html_e('PUDO ID', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Name', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Address', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Schedule', 'seur'); ?></td>
        </tr>
        </thead>
        <?php
        foreach ($result as $item) {
            seur_search_pickup_locations_form_data_row($item);
        }
        ?>
        <tfoot>
        <tr>
            <td class="manage-column"><?php esc_html_e('PUDO ID', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Name', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Address', 'seur'); ?></td>
            <td class="manage-column"><?php esc_html_e('Schedule', 'seur'); ?></td>
        </tr>
        </tfoot>
    </table>
    <?php
}

/**
 * Muestra una fila de la tabla de resultados.
 *
 * @param array $item Datos del punto de recogida.
 */
function seur_search_pickup_locations_form_data_row(array $item) {
    ?>
    <tr>
        <td>
            <?php
            // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- External API response.
            echo esc_html($item['pudoId']);
            ?>
        </td>
        <td>
            <?php echo esc_html($item['company']); ?>
        </td>
        <td>
            <a href="<?php echo esc_url(seur_search_pickup_locations_form_data_row_maps_link($item)); ?>"
               target="_blank" rel="noopener">
                <?php echo esc_html($item['tipovia']) . ' ' . esc_html($item['address']) . ' ' . esc_html($item['numvia']); ?><br/>
                <?php echo esc_html($item['post_code']) . ' ' . esc_html($item['city']); ?>
            </a>
        </td>
        <td>
            <?php echo wp_kses_post(seur_search_pickup_locations_translate_schedule($item['timetable'])); ?>
        </td>
    </tr>
    <?php
}

/**
 * Genera el enlace a Google Maps para un punto de recogida.
 *
 * @param array $item Datos del punto de recogida.
 * @return string URL del enlace a Google Maps.
 */
function seur_search_pickup_locations_form_data_row_maps_link(array $item) {
    $query = sprintf('%.6f', $item['lat']) . ',' . sprintf('%.6f', $item['lng']);

    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($query);
}