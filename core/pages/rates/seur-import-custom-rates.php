<?php
/**
 * SEUR Import Custom Rates
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$rates_type = get_option( 'seur_rates_type_field' );

// Verificar si el formulario fue enviado y si el nonce es válido
if ( isset( $_POST['import_custom_rates'] ) && check_admin_referer( 'seur_import_custom_rates_nonce', 'seur_import_custom_rates_nonce_field' ) ) {
	try {
		// Validar que el archivo fue subido sin errores
		if ( isset( $_FILES['csv_file'] ) && isset( $_FILES['csv_file']['error'] ) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK ) {
			$file = $_FILES['csv_file'];

			// Validar que el archivo es un CSV
			$file_type = wp_check_filetype( $file['name'] );
			if ( $file_type['ext'] !== 'csv' ) {
				echo '<div class="notice notice-error"><p>El archivo subido no es un CSV.</p></div>';
			} else {
				// Inicializar WP_Filesystem
				if ( ! function_exists( 'request_filesystem_credentials' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}

				global $wp_filesystem;

				if ( ! WP_Filesystem( request_filesystem_credentials( '', '', false, false, null ) ) ) {
					echo '<div class="notice notice-error"><p>Error al inicializar WP_Filesystem.</p></div>';
					return;
				}

				// Mover el archivo a una ubicación temporal
				$upload_dir = wp_upload_dir();
				$upload_path = trailingslashit( $upload_dir['basedir'] ) . 'seur_import_custom_rates_csv/';

				if ( ! $wp_filesystem->is_dir( $upload_path ) ) {
					$wp_filesystem->mkdir( $upload_path );
				}

				$uploaded_file = $upload_path . sanitize_file_name( $file['name'] );

				if ( $wp_filesystem->put_contents( $uploaded_file, $wp_filesystem->get_contents( $file['tmp_name'] ), FS_CHMOD_FILE ) ) {
					// Procesar el archivo CSV
					$result = seur_process_csv( $uploaded_file );

					if ( $result['error'] ) {
						if ( is_array( $result['message'] ) ) {
							echo '<div class="notice notice-error">';
							echo '<ul>';
							foreach ( $result['message'] as $message ) {
								echo '<li>' . esc_html( $message ) . '</li>';
							}
							echo '</ul>';
							echo '</div>';
						} else {
							echo '<div class="notice notice-error"><p>' . esc_html( $result['message'] ) . '</p></div>';
						}
					} else {
						echo '<div class="notice notice-success"><p>' . esc_html( $result['message'] ) . '</p></div>';
					}
				} else {
					echo '<div class="notice notice-error"><p>Error al mover el archivo subido.</p></div>';
				}
			}
		} else {
			echo '<div class="notice notice-error"><p>Error al subir el archivo.</p></div>';
		}
	} catch ( Exception $e ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $e->getMessage() ) . '</p></div>';
	}
} else {
	echo '<div class="notice notice-error"><p>Error de seguridad. Por favor, recargue la página e inténtelo de nuevo.</p></div>';
}

if ( isset( $_GET['action'] ) && $_GET['action'] === 'download_seur_rates_csv' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'seur_custom_rates';

	$rates = $wpdb->get_results( "SELECT * FROM {$table_name}", ARRAY_A );

	if ( empty( $rates ) ) {
		wp_die( 'No hay tarifas para exportar.' );
	}

	// Limpiar el buffer de salida para evitar HTML no deseado
	ob_clean();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=seur_tarifas_actuales.csv' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );

	// Abrir salida para CSV
	$output = fopen( 'php://output', 'w' );

	// Eliminar las columnas "created_at" y "updated_at"
	foreach ( $rates as &$row ) {
		unset( $row['created_at'], $row['updated_at'] );
	}
	unset($row); // Para evitar referencias inesperadas

	// Escribir encabezados sin las columnas eliminadas
	fputcsv( $output, array_keys( $rates[0] ) );

	// Escribir filas sin las columnas eliminadas
	foreach ( $rates as $row ) {
		fputcsv( $output, $row );
	}

	// Cerrar salida
	fclose( $output );

	// Detener la ejecución de WordPress
	exit;
}


function seur_process_csv( $file_path ) {
	global $wpdb;
	$registros = seur()->get_products();

	try {
		// Definir las columnas esperadas
		$expected_columns = array( 'ID', 'type', 'country', 'state', 'postcode', 'minprice', 'maxprice', 'minweight', 'maxweight', 'rate', 'rateprice' );

		// Iniciar una transacción
		$wpdb->query( 'START TRANSACTION' );

		// Leer el archivo CSV usando WP_Filesystem
		global $wp_filesystem;
		$file_contents = $wp_filesystem->get_contents( $file_path );

		if ( $file_contents !== false ) {
			$csv_lines = explode( "\n", $file_contents );
			$header = str_getcsv( array_shift( $csv_lines ) ); // Obtener encabezado

			if ( $header !== $expected_columns ) {
				return [
					'error'   => true,
					'message' => 'El archivo CSV no contiene las columnas correctas.',
				];
			}

			$error_messages = [];

			// Definir los estados válidos de España
			$valid_states_es = array( '*', 'AV', 'C', 'AB', 'A', 'AL', 'VI', 'O', 'BA', 'PM', 'B', 'BI', 'BU', 'CC', 'CA', 'CO', 'S', 'CS', 'CE', 'CR', 'CU', 'SS', 'GI', 'GR', 'GU', 'H', 'HU', 'J', 'LO', 'GC', 'LE', 'L', 'LU', 'MA', 'M', 'ML', 'MU', 'NA', 'OR', 'P', 'PO', 'SA', 'TF', 'SG', 'SE', 'SO', 'T', 'TE', 'TO', 'V', 'VA', 'ZA', 'Z' );

			// Leer el resto del archivo línea por línea
			foreach ( $csv_lines as $line ) {
				$data   = str_getcsv( $line );
				$record = array_combine( $header, $data );

				// Validar el campo type si no está vacío
				if ( !empty($record['type']) && $record['type'] !== 'price' && $record['type'] !== 'weight' ) {
					$error_messages[] = "El campo type debe ser 'price' o 'weight'.";
				}

				// Validar el campo rate si no está vacío
				if ( !empty($record['rate']) && !array_key_exists($record['rate'], $registros) ) {
					$error_messages[] = "El valor de rate '{$record['rate']}' no es válido.";
				}

				// Validar el campo country si no está vacío
				if ( !empty($record['country']) && !empty($record['rate']) && $record['country'] !== '*' ) {
					if ( ! in_array( $record['country'], $registros[ $record['rate'] ]['pais'], true ) && ! in_array( 'INTERNATIONAL', $registros[ $record['rate'] ]['pais'], true ) ) {
						$error_messages[] = "El valor de country '{$record['country']}' no es válido para el rate '{$record['rate']}'.";
					}
				}

				// Validar el campo state si no está vacío
				if ( !empty($record['state']) ) {
					if ( $record['country'] === 'ES' && $record['state'] !== '*' && !in_array( $record['state'], $valid_states_es, true ) ) {
						$error_messages[] = "El valor de state '{$record['state']}' no es válido para el país 'ES'.";
					} else if ( $record['country'] !== 'ES' && $record['state'] !== '*' ) {
						$error_messages[] = "El valor de state '{$record['state']}' no es válido.";
					}
				}

				// Validar los campos numéricos si no están vacíos
				$numeric_fields = ['minprice', 'maxprice', 'minweight', 'maxweight', 'rateprice'];
				foreach ( $numeric_fields as $field ) {
					if ( !empty($record[$field]) && !is_numeric($record[$field]) ) {
						$error_messages[] = "El valor de {$field} '{$record[$field]}' no es un número válido.";
					}
				}

				// Verificar si el ID existe
				if ( !empty( $record['ID'] ) ) {
					$existing_record = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}seur_custom_rates WHERE ID = %d", $record['ID'] ) );

					if ( $existing_record ) {
						// Preparar los datos para la actualización
						$update_data = array();
						foreach ( $expected_columns as $column ) {
							if ( $column !== 'ID' && !empty( $record[$column] ) ) {
								$update_data[$column] = $record[$column];
							}
						}
						if ( !empty( $update_data ) ) {
							$wpdb->update(
								"{$wpdb->prefix}seur_custom_rates",
								$update_data,
								array( 'ID' => $record['ID'] )
							);
						}
					} else {
						$error_messages[] = "El ID {$record['ID']} no existe en la base de datos.";
					}
				} else {
					// Validar que todos los campos requeridos estén presentes para una inserción
					foreach ( $expected_columns as $column ) {
						if ( $column !== 'ID' && (!isset($record[$column]) || $record[$column] === '') ) {
							$error_messages[] = "El campo {$column} es obligatorio para una nueva inserción y está vacío.";
						}
					}

					// Insertar un nuevo registro
					$wpdb->insert( "{$wpdb->prefix}seur_custom_rates", array_filter( $record ) );
				}
			}

			// Comprobar si hay errores antes de confirmar o revertir la transacción
			if ( !empty($error_messages) ) {
				$wpdb->query('ROLLBACK'); // Revertir la transacción
				return [
					'error' => true,
					'message' => $error_messages
				];
			}

			// Confirmar la transacción si no hay errores
			$wpdb->query('COMMIT');
			return [
				'error' => false,
				'message' => 'Archivo CSV procesado correctamente.'
			];
		} else {
			$wpdb->query('ROLLBACK'); // Revertir la transacción
			return [
				'error' => true,
				'message' => 'No se pudo abrir el archivo CSV.'
			];
		}
	} catch ( Exception $e ) {
		$wpdb->query('ROLLBACK'); // Revertir la transacción en caso de excepción
		return [
			'error' => true,
			'message' => $e->getMessage()
		];
	}
}
?>

<div class="container">
    <br />
    <h1>Importación de Tarifas</h1>
    <p>Para importar o actualizar las tarifas personalizadas de SEUR, siga estos pasos:</p>
    <ol>
        <li>
            Descargue el <a href="<?php echo esc_url( plugins_url( '../../../data/import_rates.csv', __FILE__ ) ); ?>" download>archivo CSV de ejemplo</a>.
            Los ejemplos deben ser eliminados antes de la importación final.
            <br>
            O bien descargue las tarifas actuales aquíí
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=seur_rates_prices&tab=import_custom_rates&action=download_seur_rates_csv' ) ); ?>">
                Descargar Tarifas Actuales en CSV
            </a>
        </li>
        <li>Edite el archivo CSV utilizando cualquier editor de texto o hoja de cálculo compatible. Elimine los ejemplos proporcionados y complete los detalles según sus necesidades.</li>
        <li>Suba el archivo CSV editado utilizando la función de importación a continuación:</li>
    </ol>

    <h2>Manual de Uso: Importación de Tarifas SEUR</h2>

    <h4>Campos Permitidos en el Archivo CSV</h4>
    <ul>
        <li><strong>ID</strong> (Obligatorio para actualización): Identificador único de la tarifa. Utilizado para actualizar tarifas existentes.</li>
        <li><strong>type</strong>: Tipo de tarifa, puede ser "price" (precio) o "weight" (peso).</li>
        <li><strong>country</strong>: País al que se aplica la tarifa. Use "*" para aplicar a todos los países.</li>
        <li><strong>state</strong>: Estado o provincia al que se aplica la tarifa. Use "*" para aplicar a todos los estados.</li>
        <li><strong>postcode</strong>: Código postal al que se aplica la tarifa.</li>
        <li><strong>minprice</strong>: Precio mínimo para aplicar la tarifa.</li>
        <li><strong>maxprice</strong>: Precio máximo para aplicar la tarifa.</li>
        <li><strong>minweight</strong>: Peso mínimo para aplicar la tarifa.</li>
        <li><strong>maxweight</strong>: Peso máximo para aplicar la tarifa.</li>
        <li><strong>rate</strong>: Nombre del tipo de tarifa SEUR.</li>
        <li><strong>rateprice</strong>: Precio de la tarifa.</li>
    </ul>

    <h3>Casos de Inserción y Actualización</h3>
    <p>Para <strong>insertar nuevas tarifas</strong>, todos los campos son obligatorios. Para <strong>actualizar una tarifa existente</strong>, solo proporcione el ID correspondiente y los campos que desea modificar.</p>
    <hr>
    <div class="content-loader">
        <!-- Formulario para subir un archivo CSV -->
        <form method="post" action="admin.php?page=seur_rates_prices&tab=import_custom_rates" enctype="multipart/form-data">
			<?php wp_nonce_field( 'seur_import_custom_rates_nonce', 'seur_import_custom_rates_nonce_field' ); ?>
            <input type="hidden" name="import_custom_rates" value="true" >
            <label for="csv_file"><?php echo esc_html__( 'Seleccione el archivo CSV', 'seur' ); ?>:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            <br><br>
            <input type="submit" value="Subir CSV" class="button button-primary">
        </form>
    </div>
</div>
