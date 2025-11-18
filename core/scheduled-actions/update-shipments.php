<?php
/**
 * Migración de WP-Cron a Action Scheduler para actualizar envíos SEUR
 *
 * Requisitos: WooCommerce (o Action Scheduler cargado por otro plugin).
 */

define('SEUR_AS_HOOK',  'seur_as_process_update_shipments');
define('SEUR_AS_GROUP', 'seur');

function seur_as_update_shipments_activate() {
    // Establecer valores por defecto si no existen
    if (get_option('seur_activate_cron_update_shipments_field') === false) {
        add_option('seur_activate_cron_update_shipments_field', '0'); // Deshabilitado por defecto
    }
    if (get_option('seur_cron_update_shipments_interval') === false) {
        add_option('seur_cron_update_shipments_interval', 'every_8_hours');
    }

    // (Re)programar en Action Scheduler
    seur_as_reprogram_update_shipments();
}
register_activation_hook(__FILE__, 'seur_as_update_shipments_activate');

function seur_as_update_shipments_deactivate() {
    // Cancelar todas las acciones programadas en AS
    if (function_exists('as_unschedule_all_actions')) {
        as_unschedule_all_actions(SEUR_AS_HOOK, [], SEUR_AS_GROUP);
    }
}
register_deactivation_hook(__FILE__, 'seur_as_update_shipments_deactivate');


function seur_as_interval_to_seconds($key) {
    switch ($key) {
        case 'hourly':
            return HOUR_IN_SECONDS;  // 3600
        case 'every_4_hours':
            return 4 * HOUR_IN_SECONDS;  // 14400
        default:
            return HOUR_IN_SECONDS;
    }
}

function seur_as_reprogram_update_shipments() {
    // Cancelar acciones existentes para evitar duplicados
    if (function_exists('as_unschedule_all_actions')) {
        as_unschedule_all_actions(SEUR_AS_HOOK, [], SEUR_AS_GROUP);
    }

    // ¿Está habilitado desde ajustes?
    $enabled = (bool) get_option('seur_activate_cron_update_shipments_field', '0');
    if (!$enabled) {
        error_log('SEUR AS: Deshabilitado, no se programa la acción recurrente');
        return;
    }

    $interval_key = get_option('seur_cron_update_shipments_interval', 'hourly');
    $interval     = seur_as_interval_to_seconds($interval_key);

    // Evitar doble programación si por alguna razón quedara otra pendiente
    if (function_exists('as_next_scheduled_action')) {
        $next = as_next_scheduled_action(SEUR_AS_HOOK, [], SEUR_AS_GROUP);
        if ($next) {
            error_log('SEUR AS: Ya existe una acción programada, no se duplica');
            return;
        }
    }

    // Programar: empieza en 1 minuto para dar margen tras activar/guardar ajustes
    //$start = time() + 60;
    $start = time();

    // Programar acción recurrente
    if (function_exists('as_schedule_recurring_action')) {
        as_schedule_recurring_action($start, $interval, SEUR_AS_HOOK, [], SEUR_AS_GROUP);
        error_log(sprintf('SEUR AS: Acción programada cada %d segundos (clave: %s)', $interval, $interval_key));
    } else {
        // Fallback si Action Scheduler no está disponible
        error_log('SEUR AS: Action Scheduler no está disponible. Verifica que WooCommerce u otro loader esté activo.');
    }
}

add_action(SEUR_AS_HOOK, 'seur_as_update_shipments_handler');
function seur_as_update_shipments_handler() {
    // Respetar el toggle de activación
    if (get_option('seur_activate_cron_update_shipments_field', '0') != true) {
        return;
    }

    try {
       $labels_ids = seur_get_candidate_ids_for_tracking();

        if (empty($labels_ids)) {
            if ( seur()->log_is_acive() ) {
                seur()->slog('SEUR - AS - Update Shipments: No hay etiquetas para actualizar');
            }
            error_log('SEUR AS: No hay etiquetas para actualizar');
            // Guardar última ejecución aunque no haya trabajo
            update_option('seur_cron_last_run', current_time('mysql'));
            update_option('seur_cron_last_processed', 0);
            return;
        }

        $start_time = microtime(true);

        seur_get_tracking_shipments($labels_ids);

        $execution_time = round(microtime(true) - $start_time, 2);
        $processed      = count($labels_ids);

        error_log("SEUR - AS - Update Shipments: Procesados {$processed} envíos en {$execution_time} segundos");
        if ( seur()->log_is_acive() ) {
            seur()->slog("SEUR - AS - Update Shipments: Procesados {$processed} envíos en {$execution_time} segundos");
        }

        update_option('seur_cron_last_run', current_time('mysql'));
        update_option('seur_cron_last_processed', $processed);

    } catch (Exception $e) {
        // Action Scheduler reintentará en caso de error si lanzas excepción,
        // pero aquí solo registramos y dejamos que el job cuente como fallido.
        error_log('SEUR - AS - Update Shipments Error: ' . $e->getMessage());
        if ( seur()->log_is_acive() ) {
            seur()->slog("SEUR AS - Update Shipments: Procesados {$processed} envíos en {$execution_time} segundos");
        }
        // Si quieres que AS reintente automáticamente, puedes relanzar:
        // throw $e;
    }
}

// Detectar cambios en la opción de activación
add_action('update_option_seur_activate_cron_update_shipments_field', 'seur_detectar_cambio_cron', 10, 2);
function seur_detectar_cambio_cron($old_value, $new_value) {
    // Solo reprogramar si el valor realmente cambió
    if ($old_value !== $new_value) {
        error_log("SEUR Cron: Estado cambió de {$old_value} a {$new_value}");
        seur_as_reprogram_update_shipments();
    }
}
add_action('update_option_seur_cron_update_shipments_interval', 'seur_detectar_cambio_intervalo_cron', 10, 2);
function seur_detectar_cambio_intervalo_cron($old_value, $new_value) {
    // Solo reprogramar si el valor realmente cambió
    if ($old_value !== $new_value) {
        error_log("SEUR Cron: Intervalo cambió de {$old_value} a {$new_value}");
        seur_as_reprogram_update_shipments();
    }
}