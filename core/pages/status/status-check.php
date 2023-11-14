<?php
/**
 * SEUR Status
 *
 * @package SEUR
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_filesystem;

/**
 * SEUR Check Service Names
 *
 * @param string $services Services.
 */
function seur_check_service_names( $services ) {
    $products = seur()->get_products();
    foreach ( $products as $code => $product ) {
        if ($services === $code) {
            return true;
        }
    }
	return false;
}
?>
<div class="updated seur-dashboard-notice">
	<p><?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'seur' ); ?> </p>
	<p class="submit"><a href="#" class="button-primary debug-report"><?php esc_html_e( 'Get system report', 'seur' ); ?></a></p>
	<div id="seur-debug-report">
		<textarea readonly="readonly"></textarea>
		<p class="copy-error"><?php esc_html_e( 'Please press Ctrl/Cmd+C to copy.', 'seur' ); ?></p>
	</div>
</div>
<div id="seur-dashboard" class="wrap seur-status">
	<h1><?php esc_html_e( 'Seur Information', 'seur' ); ?></h1>
	<div class="seur-column-container">
		<div class="seur-column seur-column-double">
			<table class="seur-status-table widefat" cellspacing="0">
				<thead>
					<tr>
						<th colspan="3" data-export-label="WordPress Environment"><?php esc_html_e( 'WordPress Environment', 'seur' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-export-label="Home URL"><?php esc_html_e( 'Home URL:', 'seur' ); ?></td>
						<td><?php echo esc_url( home_url() ); ?></td>
					</tr>
					<tr>
						<td data-export-label="Site URL"><?php esc_html_e( 'Site URL:', 'seur' ); ?></td>
						<td><?php echo esc_url( site_url() ); ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Version"><?php esc_html_e( 'WP Version:', 'seur' ); ?></td>
						<td><?php bloginfo( 'version' ); ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Multisite"><?php esc_html_e( 'WP Multisite:', 'seur' ); ?></td>
						<td>
							<?php if ( is_multisite() ) : ?>
								<span class="yes">&#10004;</span>
							<?php else : ?>
								&ndash;
							<?php endif ?>
						</td>
					</tr>
					<tr>
						<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WP Memory Limit:', 'seur' ); ?></td>
						<td>
							<?php
							$memory = size_format( wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
							echo esc_html( $memory );
							?>
						</td>
					</tr>
					<tr>
						<td data-export-label="FS Accessible"><?php esc_html_e( 'FS Accessible:', 'seur' ); ?></td>
						<td>
						<?php if ( $wp_filesystem || WP_Filesystem() ) : ?>
							<span class="yes">&#10004;</span>
						<?php else : ?>
							<span class="error">No.</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WP Debug Mode:', 'seur' ); ?></td>
					<td>
						<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
							<span class="yes">&#10004;</span>
						<?php else : ?>
							<span class="no">&ndash;</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Language"><?php esc_html_e( 'Language:', 'seur' ); ?></td>
					<td><?php echo esc_html( get_locale() ); ?></td>
				</tr>
			</tbody>
		</table>
		<table class="seur-status-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Server Environment"><?php esc_html_e( 'Server Environment', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Server Info"><?php esc_html_e( 'Server Info:', 'seur' ); ?></td>
					<td><?php echo esc_html( sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version:', 'seur' ); ?></td>
					<td>
						<?php
						if ( function_exists( 'phpversion' ) ) {
							echo esc_html( phpversion() );
						}
						?>
					</td>
				</tr>
				<?php if ( function_exists( 'ini_get' ) ) : ?>
					<tr>
						<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size:', 'seur' ); ?></td>
						<td><?php echo esc_html( size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ) ); ?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit:', 'seur' ); ?></td>
						<td>
							<?php
							$time_limit = ini_get( 'max_execution_time' );
							echo esc_html( $time_limit );
							?>
						</td>
					</tr>
					<tr>
						<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars:', 'seur' ); ?></td>
						<?php
						$registered_navs  = get_nav_menu_locations();
						$menu_items_count = array( '0' => '0' );
						foreach ( $registered_navs as $handle => $registered_nav ) {
							$menur = wp_get_nav_menu_object( $registered_nav );
							if ( $menur ) {
								$menu_items_count[] = $menu->count;
							}
						}
						$max_items           = max( $menu_items_count );
						$required_input_vars = $max_items * 20;
						?>
						<td>
							<?php
							$max_input_vars      = ini_get( 'max_input_vars' );
							$required_input_vars = $required_input_vars + ( 500 + 1000 );
							echo esc_html( $max_input_vars );
							?>
						</td>
					</tr>
					<tr>
						<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN Installed:', 'seur' ); ?></td>
						<td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
					</tr>
					<?php if ( extension_loaded( 'suhosin' ) ) : ?>
						<tr>
							<td data-export-label="Suhosin Post Max Vars"><?php esc_html_e( 'Suhosin Post Max Vars:', 'seur' ); ?></td>
							<?php
							$registered_navs  = get_nav_menu_locations();
							$menu_items_count = array( '0' => '0' );
							foreach ( $registered_navs as $handle => $registered_nav ) {
								$menur = wp_get_nav_menu_object( $registered_nav );
								if ( $menur ) {
									$menu_items_count[] = $menu->count;
								}
							}
							$max_items           = max( $menu_items_count );
							$required_input_vars = $max_items * 20;
							?>
							<td>
								<?php
								$max_input_vars      = ini_get( 'suhosin.post.max_vars' );
								$required_input_vars = $required_input_vars + ( 500 + 1000 );
								echo esc_html( $max_input_vars );
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="Suhosin Request Max Vars"><?php esc_html_e( 'Suhosin Request Max Vars:', 'seur' ); ?></td>
							<?php
							$registered_navs  = get_nav_menu_locations();
							$menu_items_count = array( '0' => '0' );
							foreach ( $registered_navs as $handle => $registered_nav ) {
								$menur = wp_get_nav_menu_object( $registered_nav );
								if ( $menur ) {
									$menu_items_count[] = $menu->count;
								}
							}

							$max_items           = max( $menu_items_count );
							$required_input_vars = $max_items * 20;
							?>
							<td>
								<?php
								$max_input_vars      = ini_get( 'suhosin.request.max_vars' );
								$required_input_vars = $required_input_vars + ( 500 + 1000 );
								echo esc_html( $max_input_vars );
								?>
							</td>
						</tr>
						<tr>
							<td data-export-label="Suhosin Post Max Value Length"><?php esc_html_e( 'Suhosin Post Max Value Length:', 'seur' ); ?></td>
							<td>
								<?php
								$suhosin_max_value_length     = ini_get( 'suhosin.post.max_value_length' );
								$recommended_max_value_length = 2000000;
								echo esc_html( $suhosin_max_value_length );
								?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
				<tr>
					<td data-export-label="ZipArchive"><?php esc_html_e( 'ZipArchive:', 'seur' ); ?></td>
					<td><?php echo class_exists( 'ZipArchive' ) ? '<span class="yes">&#10004;</span>' : '<span class="error">No.</span>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version:', 'seur' ); ?></td>
					<td>
						<?php global $wpdb; ?>
						<?php echo esc_html( $wpdb->db_version() ); ?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size:', 'seur' ); ?></td>
					<td><?php echo esc_html( size_format( wp_max_upload_size() ) ); ?></td>
				</tr>
				<tr>
					<td data-export-label="GD Library"><?php esc_html_e( 'GD Library:', 'seur' ); ?></td>
					<td>
						<?php
						$info = esc_attr__( 'Not Installed', 'seur' );
						if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
							$info    = esc_attr__( 'Installed', 'seur' );
							$gd_info = gd_info();
							if ( isset( $gd_info['GD Version'] ) ) {
								$info = $gd_info['GD Version'];
							}
						}
						echo esc_html( $info );
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="cURL"><?php esc_html_e( 'cURL:', 'seur' ); ?></td>
					<td>
						<?php
						$info = esc_attr__( 'Not Enabled', 'seur' );
						if ( function_exists( 'curl_version' ) ) {
							$curl_info = curl_version();
							$info      = $curl_info['version'];
						}
						echo esc_html( $info );
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="XML"><?php esc_html_e( 'SimpleXML:', 'seur' ); ?></td>
					<td>
						<?php
						if ( function_exists( 'curl_version' ) ) {
							?>
							<span class="yes">&#10004;</span>
							<?php
						} else {
							?>
							<span class="error">No.</span>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="SOAP"><?php esc_html_e( 'SOAP:', 'seur' ); ?></td>
					<td>
						<?php
						if ( function_exists( 'simplexml_load_string' ) ) {
							?>
							<span class="yes">&#10004;</span>
							<?php
						} else {
							?>
							<span class="error">No.</span>
							<?php
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="seur-column seur-column-double">
		<table class="seur-status-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Seur Information"><?php esc_html_e( 'Seur Information', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Current Plugin Version"><?php esc_html_e( 'Current Plugin Version:', 'seur' ); ?></td>
					<td><?php echo esc_html( SEUR_OFFICIAL_VERSION ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Current DB Version"><?php esc_html_e( 'Current DB Version:', 'seur' ); ?></td>
					<td><?php echo esc_html( SEUR_DB_VERSION ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Current Table Version"><?php esc_html_e( 'Current Table Version:', 'seur' ); ?></td>
					<td><?php echo esc_html( SEUR_TABLE_VERSION ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Ajax calls with wp_remote_post"><?php esc_html_e( 'Ajax calls with wp_remote_post:', 'seur' ); ?></td>
					<td>
						<?php
							$ajax_url         = esc_url_raw( admin_url( 'admin-ajax.php' ) );
							$seur_server_code = wp_remote_retrieve_response_code( wp_remote_post( $ajax_url, array( 'decompress' => false ) ) );
						if ( 400 === $seur_server_code ) {
							echo '<span class="yes">&#10004;</span>';
						} else {
							// translators: Ajax URL.
							printf( esc_html__( '<span class="error">No</span><br> Seems that your server is blocking connections to your own site. It may brake theme db update process and lead to style corruption. Please, make sure that remote requests to %s are not blocked.', 'seur' ), esc_url( $ajax_url ) );
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="seur-status-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Seur Tables"><?php esc_html_e( 'Seur Tables', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="WordPress Table Prefix"><?php esc_html_e( 'WordPress Table Prefix:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( $wpdb->prefix ); ?></code></td>
				</tr>
				<tr>
					<?php $table_name = $wpdb->prefix . 'seur_custom_rates'; ?>
					<td data-export-label="Check for <?php echo esc_html( $table_name ); ?>"><?php echo esc_html__( 'Check for', 'seur' ) . ' ' . esc_html( $table_name ); ?></td>
					<?php
					if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %d', $table_name ) ) === $table_name ) {
						echo '<td><span class="yes">&#10004;</span></td>';
					} else {
						echo '<td><span class="error">No.</span></td>';
					}
					?>
				</tr>
			</tbody>
		</table>
		<table class="seur-status-table widefat" cellspacing="0" id="status">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php esc_html_e( 'Active Plugins', 'seur' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$active_plugins = (array) get_option( 'active_plugins', array() );
				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}
				foreach ( $active_plugins as $plugin2 ) {
					$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin2 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					if ( empty( $plugin_data['Name'] ) ) {
						continue;
					}

					// Link the plugin name to the plugin url if available.
					$plugin_name = esc_html( $plugin_data['Name'] );
					if ( ! empty( $plugin_data['PluginURI'] ) ) {
						$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage', 'seur' ) . '">' . $plugin_name . '</a>';
					}
					?>
				<tr>
					<td><?php echo esc_html( $plugin_name ); ?></td>
					<td>
					<?php
					// translators: Name of plugin Author.
					printf( esc_html_x( 'by %s', 'admin status', 'seur' ), esc_html( $plugin_data['Author'] ) );
					echo ' &ndash; ' . esc_html( $plugin_data['Version'] );
					?>
					</td>
				</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="seur-column seur-table-full">
		<table class="seur-status-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Seur Directories and URLs"><?php esc_html_e( 'Seur Directories & URLs', 'seur' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Installation Path"><?php esc_html_e( 'Installation Path:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_PLUGIN_PATH ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Installation Plugin URL"><?php esc_html_e( 'Installation Plugin URL:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_PLUGIN_URL ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="wp-content is writable"><?php esc_html_e( 'wp-content is writable:', 'seur' ); ?></td>
						<?php
						$writable = wp_is_writable( WP_CONTENT_DIR );
						if ( $writable ) {
							?>
							<td><span class="yes"> &#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Path"><?php esc_html_e( 'Seur Upload Path:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_PATH ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Check for Seur Upload Path"><?php esc_html_e( 'Check for Seur Upload Path:', 'seur' ); ?></td>
						<?php
						$path_upload = SEUR_UPLOADS_PATH;
						if ( file_exists( $path_upload ) ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Path is writable"><?php esc_html_e( 'Seur Upload Path is writable:', 'seur' ); ?></td>
						<?php
						$writable = wp_is_writable( esc_html( SEUR_UPLOADS_PATH ) );
						if ( $writable ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload URL"><?php esc_html_e( 'Seur Upload URL:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_URL ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Labels Path"><?php esc_html_e( 'Seur Upload Labels Path:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_LABELS_PATH ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Check for Seur Upload Labels Path"><?php esc_html_e( 'Check for Seur Upload Labels Path:', 'seur' ); ?></td>
						<?php
						$path_upload = SEUR_UPLOADS_LABELS_PATH;
						if ( file_exists( $path_upload ) ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Labels Path is writable"><?php esc_html_e( 'Seur Upload Labels Path is writable:', 'seur' ); ?></td>
						<?php
						$writable = wp_is_writable( SEUR_UPLOADS_LABELS_PATH );
						if ( $writable ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Labels URL"><?php esc_html_e( 'Seur Upload Labels URL:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_LABELS_URL ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Manifest Path"><?php esc_html_e( 'Seur Upload Manifest Path:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_MANIFEST_PATH ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Check for Seur Upload Manifest Path"><?php esc_html_e( 'Check for Seur Upload Manifest Path:', 'seur' ); ?></td>
						<?php
						$path_upload = SEUR_UPLOADS_MANIFEST_PATH;
						if ( file_exists( $path_upload ) ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Manifest Path is writable"><?php esc_html_e( 'Seur Upload Manifest Path is writable:', 'seur' ); ?></td>
						<?php
						$writable = wp_is_writable( SEUR_UPLOADS_MANIFEST_PATH );
						if ( $writable ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Upload Manifest URL"><?php esc_html_e( 'Seur Upload Manifest URL:', 'seur' ); ?></td>
					<td><code><?php echo esc_html( SEUR_UPLOADS_MANIFEST_URL ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Seur Download File URL"><?php esc_html_e( 'Seur Download File URL:', 'seur' ); ?></td>
						<?php
						$url_to_file_down = get_site_option( 'seur_download_file_url' );
						?>
					<td><code><?php echo esc_html( $url_to_file_down ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Seur Download File Path"><?php esc_html_e( 'Seur Download File Path:', 'seur' ); ?></td>
						<?php
						$path_to_file_down = get_site_option( 'seur_download_file_path' );
						?>
					<td><code><?php echo esc_html( $path_to_file_down ); ?></code></td>
				</tr>
				<tr>
					<td data-export-label="Check for Seur Download File"><?php esc_html_e( 'Check for seur Download File:', 'seur' ); ?></td>
						<?php
						$path_to_file_down = get_site_option( 'seur_download_file_path' );
						if ( file_exists( $path_to_file_down ) ) {
							?>
							<td><span class="yes">&#10004;</span></td>
							<?php
						} else {
							?>
							<td><span class="error">No.</span></td>
							<?php
						}
						?>
				</tr>
				<tr>
					<td data-export-label="Seur Download Password"><?php esc_html_e( 'Seur Download Password:', 'seur' ); ?></td>
						<?php
						$pass = get_site_option( 'seur_pass_for_download' );
						?>
					<td><code><?php echo esc_html( $pass ); ?></code></td>
				</tr>
				</tbody>
			</table>
		</div>
			<div class="seur-column seur-column-double">
				<table class="seur-status-table widefat" cellspacing="0">
					<thead>
						<tr>
							<th colspan="3" data-export-label="Seur Services"><?php esc_html_e( 'Seur Services', 'seur' ); ?></th>
						</tr>
					</thead>
					<tbody>
                    <?php $products = seur()->get_products();
                    foreach ( $products as $code => $product ) {
                        echo '<tr>
							<td data-export-label="'.$code.'">'.$code.'</td>';
                        if ( seur_check_service_names($code) ) {
						    echo '<td><span class="yes">&#10004;</span></td>';
						} else {
                            echo '<td><span class="error">No.</span></td>';
						}
						echo '</tr>';
                    } ?>
					</tbody>
				</table>
			</div>
			<div class="seur-column seur-column-double">
				<table class="seur-status-table widefat" cellspacing="0">
					<?php
						$seur_cit_codigo_field      = get_option( 'seur_cit_codigo_field' );
						$seur_cit_usuario_field     = get_option( 'seur_cit_usuario_field' );
						$seur_cit_contra_field      = get_option( 'seur_cit_contra_field' );
						$seur_ccc_field             = get_option( 'seur_ccc_field' );
						$seur_int_ccc_field         = get_option( 'seur_int_ccc_field' );
						$seur_franquicia_field      = get_option( 'seur_franquicia_field' );
						$seur_seurcom_usuario_field = get_option( 'seur_seurcom_usuario_field' );
						$seur_seurcom_contra_field  = get_option( 'seur_seurcom_contra_field' );
					?>
					<thead>
						<tr>
							<th colspan="3" data-export-label="Seur User Settings"><?php esc_html_e( 'Seur User Settings', 'seur' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td data-export-label="CIT code"><?php esc_html_e( 'CIT code:', 'seur' ); ?></td>
							<?php if ( $seur_cit_codigo_field ) { ?>
								<td><?php echo esc_html( $seur_cit_codigo_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="CIT user"><?php esc_html_e( 'CIT user:', 'seur' ); ?></td>
							<?php if ( $seur_cit_usuario_field ) { ?>
								<td><?php echo esc_html( $seur_cit_usuario_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="CIT password"><?php esc_html_e( 'CIT password:', 'seur' ); ?></td>
							<?php if ( $seur_cit_contra_field ) { ?>
								<td><?php echo esc_html( $seur_cit_contra_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="CCC"><?php esc_html_e( 'CCC:', 'seur' ); ?></td>
							<?php if ( $seur_ccc_field ) { ?>
								<td><?php echo esc_html( $seur_ccc_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="International CCC"><?php esc_html_e( 'International CCC:', 'seur' ); ?></td>
							<?php if ( $seur_int_ccc_field ) { ?>
								<td><?php echo esc_html( $seur_int_ccc_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Franchise"><?php esc_html_e( 'Franchise:', 'seur' ); ?></td>
							<?php if ( $seur_franquicia_field ) { ?>
								<td><?php echo esc_html( $seur_franquicia_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="SEUR.com user"><?php esc_html_e( 'SEUR.com user:', 'seur' ); ?></td>
							<?php if ( $seur_seurcom_usuario_field ) { ?>
								<td><?php echo esc_html( $seur_seurcom_usuario_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="SEUR.com password"><?php esc_html_e( 'SEUR.com password:', 'seur' ); ?></td>
							<?php if ( $seur_seurcom_contra_field ) { ?>
								<td><?php echo esc_html( $seur_seurcom_contra_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
					</tbody>
				</table>



				<table class="seur-status-table widefat" cellspacing="0">
					<?php
						$seur_activate_free_shipping_field = get_option( 'seur_activate_free_shipping_field' );
						$seur_activate_local_pickup_field  = get_option( 'seur_activate_local_pickup_field' );
						$seur_google_maps_api_field        = get_option( 'seur_google_maps_api_field' );
						$seur_after_get_label_field        = get_option( 'seur_after_get_label_field' );
						$seur_preaviso_notificar_field     = get_option( 'seur_preaviso_notificar_field' );
						$seur_reparto_notificar_field      = get_option( 'seur_reparto_notificar_field' );
						$seur_tipo_notificacion_field      = get_option( 'seur_tipo_notificacion_field' );
						$seur_tipo_etiqueta_field          = get_option( 'seur_tipo_etiqueta_field' );
						$seur_aduana_origen_field          = get_option( 'seur_aduana_origen_field' );
						$seur_aduana_destino_field         = get_option( 'seur_aduana_destino_field' );
						$seur_tipo_mercancia_field         = get_option( 'seur_tipo_mercancia_field' );
						$seur_id_mercancia_field           = get_option( 'seur_id_mercancia_field' );
                        $seur_descripcion_field            = get_option( 'seur_descripcion_field' );
					?>
					<thead>
						<tr>
							<th colspan="3" data-export-label="Seur Advanced Settings"><?php esc_html_e( 'Seur Advanced Settings', 'seur' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td data-export-label="Show WooCommerce Free Shipping at Checkout"><?php esc_html_e( 'Show WooCommerce Free Shipping at Checkout:', 'seur' ); ?></td>
							<?php if ( $seur_activate_free_shipping_field ) { ?>
								<td><?php echo esc_html( $seur_activate_free_shipping_field ); ?></td>
								<?php
							} else {
								?>
								<td><span><?php esc_html_e( 'No', 'seur' ); ?></span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="What to do after get order label"><?php esc_html_e( 'What to do after get order label:', 'seur' ); ?></td>
							<?php if ( $seur_after_get_label_field ) { ?>
								<td><?php echo esc_html( $seur_after_get_label_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Activate Local Pickup"><?php esc_html_e( 'Activate Local Pickup:', 'seur' ); ?></td>
							<?php if ( '1' === $seur_activate_local_pickup_field ) { ?>
								<td><span><?php esc_html_e( 'Yes', 'seur' ); ?></span></td>
								<?php
							} else {
								?>
								<td><span><?php esc_html_e( 'No', 'seur' ); ?></span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Google Maps API Key"><?php esc_html_e( 'Google Maps API Key:', 'seur' ); ?></td>
							<?php if ( $seur_google_maps_api_field ) { ?>
								<td><?php echo esc_html( $seur_google_maps_api_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Notify collection"><?php esc_html_e( 'Notify collection:', 'seur' ); ?></td>
							<?php if ( '1' === $seur_preaviso_notificar_field ) { ?>
								<td><span><?php esc_html_e( 'Yes', 'seur' ); ?></span></td>
								<?php
							} else {
								?>
								<td><span><?php esc_html_e( 'No', 'seur' ); ?></span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Notify distribution"><?php esc_html_e( 'Notify distribution:', 'seur' ); ?></td>
							<?php if ( '1' === $seur_reparto_notificar_field ) { ?>
								<td><span><?php esc_html_e( 'Yes', 'seur' ); ?></span></td>
								<?php
							} else {
								?>
								<td><span><?php esc_html_e( 'No', 'seur' ); ?></span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Notifications by SMS or Email"><?php esc_html_e( 'Notifications by SMS or Email:', 'seur' ); ?></td>
							<?php if ( $seur_tipo_notificacion_field ) { ?>
								<td><?php echo esc_html( $seur_tipo_notificacion_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Type of label"><?php esc_html_e( 'Type of label:', 'seur' ); ?></td>
							<?php if ( $seur_tipo_etiqueta_field ) { ?>
								<td><?php echo esc_html( $seur_tipo_etiqueta_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Customs of origin"><?php esc_html_e( 'Customs of origin:', 'seur' ); ?></td>
							<?php if ( $seur_aduana_origen_field ) { ?>
								<td><?php echo esc_html( $seur_aduana_origen_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Customs of destination"><?php esc_html_e( 'Customs of destination:', 'seur' ); ?></td>
							<?php if ( $seur_aduana_destino_field ) { ?>
								<td><?php echo esc_html( $seur_aduana_destino_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="Type of goods"><?php esc_html_e( 'Type of goods:', 'seur' ); ?></td>
							<?php if ( $seur_tipo_mercancia_field ) { ?>
								<td><?php echo esc_html( $seur_tipo_mercancia_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="ID of goods"><?php esc_html_e( 'ID of goods:', 'seur' ); ?></td>
							<?php if ( $seur_id_mercancia_field ) { ?>
								<td><?php echo esc_html( $seur_id_mercancia_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
						<tr>
							<td data-export-label="International description"><?php esc_html_e( 'International description:', 'seur' ); ?></td>
							<?php if ( $seur_descripcion_field ) { ?>
								<td><?php echo esc_html( $seur_descripcion_field ); ?></td>
								<?php
							} else {
								?>
								<td><span class="error">No.</span></td>
								<?php } ?>
						</tr>
					</tbody>
				</table>
			</div>
	</div>
</div>
