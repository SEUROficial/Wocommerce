<?php
if (!defined('ABSPATH')) {
    exit;
}

seur_create_upload_folder_hook();
echo get_option( 'seur_uploads_dir' );