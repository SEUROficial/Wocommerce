function seur_create_upload_folder_ajax() {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'seur_regenerate_upload_dir'
        },
        success: function(response) {
            if (response) {
                jQuery('#seur_uploads_dir').html(response);
            }
        }
    });
}