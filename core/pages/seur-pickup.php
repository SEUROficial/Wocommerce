<?php
/**
 * SEUR Pickup
 *
 * @package SEUR
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * SEUR Pickup
 *
 * @param WP_Post $post Post Data.
 */
function seur_pickup($post)
{
	$error_message = ''; // Variable para almacenar el mensaje de error.

	// Procesar solicitudes de recogida o cancelación
	if (isset($_POST['request_normal'])) {
		// Lógica para solicitar recogida normal
		$date = gmdate( 'Y-m-d' );
		$data = array(
			'type' => 'normal',
			'date' => $date,
			'mfrom' => '09:00:00', // Hora de ejemplo
			'mto' => '13:00:00',
			'efrom' => '16:00:00',
			'eto' => '19:00:00',
			'ref' => 'normal_' . seur()->get_option('seur_accountnumber_field') . gmdate('ymdHis'),
		);
		$result = seur_collections($data);
		if (isset($result['errors'])) {
			// Si hay errores, almacenar el mensaje
			$error_message = 'Error: ' . $result['errors'][0]['detail'];
		} else {
			seur()->save_collection($result['data']['collectionRef'], 'normal');
			seur()->save_reference($result['data']['reference'], 'normal');
			seur()->save_date_normal($date);

			// Redirigir para refrescar la página solo si no hay error
            $url = sanitize_url( wp_unslash($_SERVER['REQUEST_URI']??''));
            if ( !empty($url) ) {
                wp_redirect( $url );
                exit;
            }
        }
	}

	if (isset($_POST['request_cold'])) {
		// Lógica para solicitar recogida fría
		$date = gmdate( 'Y-m-d' );
		$data = array(
			'type' => 'cold',
			'date' => $date,
			'mfrom' => '09:00:00', // Hora de ejemplo
			'mto' => '13:00:00',
			'efrom' => '16:00:00',
			'eto' => '19:00:00',
			'ref' => 'cold_' . seur()->get_option('seur_accountnumber_field') . gmdate('ymdHis'),
		);
		$result = seur_collections($data);
		if (isset($result['errors'])) {
			// Si hay errores, almacenar el mensaje
			$error_message = 'Error: ' . $result['errors'][0]['detail'];
		} else {
			seur()->save_collection($result['data']['collectionRef'], 'cold');
			seur()->save_reference($result['data']['reference'], 'cold');
			seur()->save_date_cold($date);

			// Redirigir para refrescar la página solo si no hay error
            $url = sanitize_url( wp_unslash($_SERVER['REQUEST_URI']??''));
            if ( !empty($url) ) {
                wp_redirect( $url );
                exit;
            }
		}
	}

	if (isset($_POST['cancel_normal'])) {
		// Lógica para cancelar recogida normal
		// Obtener la referencia de la recogida normal
		$reference_normal = seur()->get_collection('normal');
		$result = seur_cancel_collection($reference_normal);

		if (isset($result['errors'])) {
			// Si hay errores, almacenar el mensaje
			$error_message = 'Error: ' . $result['errors'][0]['detail'];
		} else {
			seur()->cancel_collection('normal');
			seur()->cancel_reference('normal');
			seur()->cancel_date_normal();

			// Redirigir para refrescar la página
            $url = sanitize_url( wp_unslash($_SERVER['REQUEST_URI']??''));
            if ( !empty($url) ) {
                wp_redirect( $url );
                exit;
            }
		}
	}

	if (isset($_POST['cancel_cold'])) {
		// Lógica para cancelar recogida fría

		$reference_cold = seur()->get_collection('cold');
		$result = seur_cancel_collection($reference_cold);

		if (isset($result['errors'])) {
			// Si hay errores, almacenar el mensaje
			$error_message = 'Error: ' . $result['errors'][0]['detail'];
		} else {
			seur()->cancel_collection('cold');
			seur()->cancel_reference('cold');
			seur()->cancel_date_cold();

			// Redirigir para refrescar la página
            $url = sanitize_url( wp_unslash($_SERVER['REQUEST_URI']??''));
            if ( !empty($url) ) {
                wp_redirect( $url );
                exit;
            }
		}
	}

	?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e('Collection Management', 'seur'); ?></h1>
        <hr class="wp-header-end">
        <form method="post" name="formulario" style="width: 100%;">
			<?php
			wp_nonce_field('seur_pickup_action', 'seur_pickup_nonce_field');

			if (isset($_POST['seur_pickup_nonce_field']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['seur_pickup_nonce_field'])), 'seur_pickup_action')) {
				print 'Sorry, your nonce did not verify.';
				exit;
			}

			// Obtener los datos de las recogidas actuales
			$date = gmdate( 'Y-m-d' );
			$collection_normal = seur()->get_collection('normal');
			$collection_cold = seur()->get_collection('cold');
			$reference_normal = seur()->get_reference('normal');
			$reference_cold = seur()->get_reference('cold');
			$date_normal = seur()->get_date_normal();
			$date_cold = seur()->get_date_cold();

			// Mostrar errores si existen
			if ($error_message) {
				echo "<div style='color: red; font-weight: bold; margin-top: 20px;'>" . esc_html( $error_message ) . "</div>";
			}

			// Paneles para normal y frío
			?>
            <div class="seur-collection-wrapper" style="display: flex; justify-content: space-between; gap: 20px;">
                <!-- Panel para recogida normal -->
                <div class="collection-panel" style="background-color: #f1f1f1; border: 1px solid #ddd; padding: 20px; width: 48%; border-radius: 5px;">
                    <h2 style="font-size: 18px; font-weight: bold; text-align: center;"><?php esc_html_e('Normal Collection', 'seur'); ?></h2>

                    <!-- Mostrar recogida activa -->
					<?php if ($date === $date_normal): ?>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            <p><?php esc_html_e('You have a Normal collection today', 'seur'); ?></p>
                            <p><?php esc_html_e('Reference: ', 'seur'); echo esc_html($reference_normal); ?></p>
                            <p><?php esc_html_e('Collection: ', 'seur'); echo esc_html($collection_normal); ?></p>
                        </div>
					<?php endif; ?>

                    <!-- Botón de solicitar/cancelar recogida -->
					<?php if ($date === $date_normal): ?>
                        <button name="cancel_normal" type="submit" style="width: 100%; background-color: #e53935; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
							<?php esc_html_e('Cancel Normal Collection', 'seur'); ?>
                        </button>
					<?php else: ?>
                        <button name="request_normal" type="submit" style="width: 100%; background-color: #4CAF50; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
							<?php esc_html_e('Request Normal Collection', 'seur'); ?>
                        </button>
					<?php endif; ?>
                </div>

                <!-- Panel para recogida fría -->
                <div class="collection-panel" style="background-color: #f1f1f1; border: 1px solid #ddd; padding: 20px; width: 48%; border-radius: 5px;">
                    <h2 style="font-size: 18px; font-weight: bold; text-align: center;"><?php esc_html_e('Cold Collection', 'seur'); ?></h2>

                    <!-- Mostrar recogida activa -->
					<?php if ($date === $date_cold): ?>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            <p><?php esc_html_e('You have a Cold collection today', 'seur'); ?></p>
                            <p><?php esc_html_e('Reference: ', 'seur'); echo esc_html($reference_cold); ?></p>
                            <p><?php esc_html_e('Collection: ', 'seur'); echo esc_html($collection_cold); ?></p>
                        </div>
					<?php endif; ?>

                    <!-- Botón de solicitar/cancelar recogida -->
					<?php if ($date === $date_cold): ?>
                        <button name="cancel_cold" type="submit" style="width: 100%; background-color: #e53935; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
							<?php esc_html_e('Cancel Cold Collection', 'seur'); ?>
                        </button>
					<?php else: ?>
                        <button name="request_cold" type="submit" style="width: 100%; background-color: #4CAF50; color: #fff; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
							<?php esc_html_e('Request Cold Collection', 'seur'); ?>
                        </button>
					<?php endif; ?>
                </div>
            </div>
        </form>
    </div>
	<?php
}
