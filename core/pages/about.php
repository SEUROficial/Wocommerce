<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function seur_about_page(){ ?>
		<div class="wrap">
	<?php
$funcion = seur_create_content_for_download();

echo $funcion;
$content_url    = content_url();
	$random_string  = seur_create_random_string();
	$file_prefix    = 'seur-downloader-';
	$full_name      = $file_prefix . $random_string . '.php';
	$full_url_file  = $content_url . '/' . $full_name;
	$full_path_file = WP_CONTENT_DIR . '/' . $full_name;
	$content_copy   = SEUR_PLUGIN_PATH . 'template/seur-download.txt';

	echo '<br />' . $full_path_file;
	echo '<br />' . $full_url_file;
	echo '<br />' . $content_copy;

echo '<br />' . $content_url;

echo '<br />' . SEUR_PLUGIN_PATH . 'template/seur-download.txt<br />';
echo WP_CONTENT_DIR;
?> </div> <?php
}
