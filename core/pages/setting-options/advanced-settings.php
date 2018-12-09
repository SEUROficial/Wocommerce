<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_activate_local_pickup_field(){ ?>

     <input type="checkbox" class="js-switch-pickup" title="<?php _e('Activate Local Pickup', 'seur' ); ?>" name="seur_activate_local_pickup_field" value="1" <?php checked(1, get_option('seur_activate_local_pickup_field'), true); ?>/>
    <?php }

function seur_deactivate_free_shipping_field(){ ?>

     <input type="checkbox" class="js-switch-free-shipping" title="<?php _e('Deactivate WooCommerce Free Shipping', 'seur' ); ?>" name="seur_deactivate_free_shipping_field" value="1" <?php checked(1, get_option('seur_deactivate_free_shipping_field'), true); ?>/>
    <?php }

function seur_google_maps_api_field(){ ?>
    <input title="<?php _e('Google Maps API Key', 'seur' ); ?>" type="text" name="seur_google_maps_api_field" value="<?php echo get_option('seur_google_maps_api_field'); ?>" size="40" />
    <?php }

function seur_after_get_label_field(){
   $option = get_option( 'seur_after_get_label_field' );
   ?>
   <select id="notification_type" name="seur_after_get_label_field">
       <option value="shipping" <?php if ( $option == 'shipping') echo ' selected'; ?>><?php _e('Mark as Shipping', 'seur' ); ?></option>
       <option value="complete" <?php if ( $option == 'complete') echo ' selected'; ?>><?php _e('Mark as Complete', 'seur' ); ?></option>
    </select>
<?php   }

function seur_preaviso_notificar_field(){ ?>

     <input type="checkbox" class="js-switch-preavisonotificar" title="<?php _e('SEUR field description', 'seur' ); ?>" name="seur_preaviso_notificar_field" value="1" <?php checked(1, get_option('seur_preaviso_notificar_field'), true); ?>/>
    <?php }

function seur_reparto_notificar_field(){ ?>
    <input type="checkbox" class="js-switch-repartonotificar" title="<?php _e('SEUR field description', 'seur' ); ?>" name="seur_reparto_notificar_field" value="1" <?php checked(1, get_option('seur_reparto_notificar_field'), true); ?>/>
<?php }

function seur_tipo_notificacion_field(){
   $option = get_option( 'seur_tipo_notificacion_field' );
   ?>
   <select id="notification_type" name="seur_tipo_notificacion_field">
       <option value="SMS" <?php if ( $option == 'SMS') echo ' selected'; ?>><?php _e('SMS (this option has an extra cost)', 'seur'); ?></option>
       <option value="EMAIL" <?php if ( $option == 'EMAIL') echo ' selected'; ?>><?php _e('Email', 'seur'); ?></option>
       <option value="both" <?php if ( $option == 'both') echo ' selected'; ?>><?php _e('Both (this option has an extra cost)', 'seur'); ?></option>
    </select>
<?php   }

function seur_tipo_etiqueta_field(){
   $option = get_option( 'seur_tipo_etiqueta_field' );
   ?>
   <select id="label_type" name="seur_tipo_etiqueta_field">
       <option value="PDF" <?php if ( $option == 'PDF') echo ' selected'; ?>>PDF</option>
       <option value="TERMICA" <?php if ( $option == 'TERMICA') echo ' selected'; ?>>TERMICA</option>
    </select>
<?php   }

function seur_aduana_origen_field(){
    $option = get_option( 'seur_aduana_origen_field' );
   ?>
   <select id="seur_aduana_origen_type" name="seur_aduana_origen_field">
       <option value="D" <?php if ( $option == 'D') echo ' selected'; ?>>D</option>
       <option value="F" <?php if ( $option == 'F') echo ' selected'; ?>>F</option>

    </select>
    <?php }

function seur_aduana_destino_field(){
    $option = get_option( 'seur_aduana_destino_field' );
   ?>
   <select id="seur_aduana_destino_type" name="seur_aduana_destino_field">
       <option value="D" <?php if ( $option == 'D') echo ' selected'; ?>>D</option>
       <option value="F" <?php if ( $option == 'F') echo ' selected'; ?>>F</option>

    </select>
    <?php }

function seur_tipo_mercancia_field(){
    $option = get_option( 'seur_tipo_mercancia_field' );
   ?>
   <select id="mercancia_type" name="seur_tipo_mercancia_field">
       <option value="C" <?php if ( $option == 'C') echo ' selected'; ?>><?php _e( 'C: Commercial', 'seur' ); ?></option>
       <option value="D" <?php if ( $option == 'D') echo ' selected'; ?>><?php _e( 'D: Documents', 'seur' ); ?></option>
       <option value="N" <?php if ( $option == 'N') echo ' selected'; ?>><?php _e( 'N: No Commercial', 'seur' ); ?></option>
       <option value="S" <?php if ( $option == 'S') echo ' selected'; ?>><?php _e( 'S: Envelopes', 'seur' ); ?></option>
    </select>
    <?php }

function seur_id_mercancia_field(){ ?>
    <input title="<?php _e('SEUR field description', 'seur' ); ?>" type="text" name="seur_id_mercancia_field" value="<?php echo get_option('seur_id_mercancia_field'); ?>" size="40" />
    <?php }

function seur_descripcion_field(){ ?>
    <input title="<?php _e('SEUR field description', 'seur' ); ?>" type="text" name="seur_descripcion_field" value="<?php echo get_option('seur_descripcion_field'); ?>" size="40" />
    <?php }

function display_seur_advanced_settings_panel_fields(){

    add_settings_section( 'seur-advanced-settings-section', NULL, NULL, 'seur-advanced-settings-options' );

    add_settings_field( 'seur_deactivate_free_shipping_field', __('Disable WooCommerce Free Shipping (by default SEUR disable the Free Shipping)', 'seur'), 'seur_deactivate_free_shipping_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_after_get_label_field', __('What to do after get order label', 'seur'), 'seur_after_get_label_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_activate_local_pickup_field', __('Activate Local Pickup', 'seur'), 'seur_activate_local_pickup_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_google_maps_api_field', __('Google Maps API Key', 'seur'), 'seur_google_maps_api_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_preaviso_notificar_field', __('Notify collection', 'seur'), 'seur_preaviso_notificar_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_reparto_notificar_field', __('Notify distribution', 'seur'), 'seur_reparto_notificar_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_notificacion_field', __('Notifications by SMS or Email', 'seur'), 'seur_tipo_notificacion_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_etiqueta_field', __('Type of label', 'seur'), 'seur_tipo_etiqueta_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_origen_field', __('Customs of origin', 'seur'), 'seur_aduana_origen_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_destino_field', __('Customs of destination', 'seur'), 'seur_aduana_destino_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_mercancia_field', __('Type of goods', 'seur'), 'seur_tipo_mercancia_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_id_mercancia_field', __('ID of goods', 'seur'), 'seur_id_mercancia_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_descripcion_field', __('International description', 'seur'), 'seur_descripcion_field', 'seur-advanced-settings-options', 'seur-advanced-settings-section' );

    // register all setings

    register_setting('seur-advanced-settings-section', 'seur_deactivate_free_shipping_field' );
    register_setting('seur-advanced-settings-section', 'seur_preaviso_notificar_field' );
    register_setting('seur-advanced-settings-section', 'seur_activate_local_pickup_field' );
    register_setting('seur-advanced-settings-section', 'seur_google_maps_api_field' );
    register_setting('seur-advanced-settings-section', 'seur_after_get_label_field' );
    register_setting('seur-advanced-settings-section', 'seur_preaviso_notificar_field' );
    register_setting('seur-advanced-settings-section', 'seur_reparto_notificar_field' );
    register_setting('seur-advanced-settings-section', 'seur_tipo_notificacion_field' );
    register_setting('seur-advanced-settings-section', 'seur_tipo_etiqueta_field' );
    register_setting('seur-advanced-settings-section', 'seur_aduana_origen_field' );
    register_setting('seur-advanced-settings-section', 'seur_aduana_destino_field' );
    register_setting('seur-advanced-settings-section', 'seur_tipo_mercancia_field' );
    register_setting('seur-advanced-settings-section', 'seur_id_mercancia_field' );
    register_setting('seur-advanced-settings-section', 'seur_descripcion_field' );

}

add_action('admin_init', 'display_seur_advanced_settings_panel_fields');