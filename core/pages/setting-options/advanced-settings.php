<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_after_get_label_field(){
   $option = get_option( 'seur_after_get_label_field' );
   ?>
   <select id="notification_type" name="seur_after_get_label_field">
       <option value="shipping" <?php if ( $option == 'shipping') echo ' selected'; ?>><?php _e('Mark as Shipping', 'seur-oficial' ); ?></option>
       <option value="complete" <?php if ( $option == 'complete') echo ' selected'; ?>><?php _e('Mark as Complete', 'seur-oficial' ); ?></option>
    </select>
<?php   }

function seur_preaviso_notificar_field(){ ?>

     <input type="checkbox" class="js-switch-preavisonotificar" title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" name="seur_preaviso_notificar_field" value="1" <?php checked(1, get_option('seur_preaviso_notificar_field'), true); ?>/>
    <?php }

function seur_reparto_notificar_field(){ ?>
    <input type="checkbox" class="js-switch-repartonotificar" title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" name="seur_reparto_notificar_field" value="1" <?php checked(1, get_option('seur_reparto_notificar_field'), true); ?>/>
<?php }

function seur_tipo_notificacion_field(){
   $option = get_option( 'seur_tipo_notificacion_field' );
   ?>
   <select id="notification_type" name="seur_tipo_notificacion_field">
       <option value="SMS" <?php if ( $option == 'SMS') echo ' selected'; ?>><?php _e('SMS (this option has an extra cost)', 'seur-oficial'); ?></option>
       <option value="EMAIL" <?php if ( $option == 'EMAIL') echo ' selected'; ?>><?php _e('Email', 'seur-oficial'); ?></option>
       <option value="both" <?php if ( $option == 'both') echo ' selected'; ?>><?php _e('Both', 'seur-oficial'); ?></option>
    </select>
<?php   }

function seur_manana_desde_field(){
    $option = get_option( 'seur_manana_desde_field' );
   ?>
   <select id="manana_desde_type" name="seur_manana_desde_field">
       <option value="09:00" <?php if ( $option == '09:00') echo ' selected'; ?>>09:00</option>
       <option value="10:00" <?php if ( $option == '10:00') echo ' selected'; ?>>10:00</option>
       <option value="11:00" <?php if ( $option == '11:00') echo ' selected'; ?>>11:00</option>
       <option value="12:00" <?php if ( $option == '12:00') echo ' selected'; ?>>12:00</option>
    </select>
    <?php }

function seur_manana_hasta_field(){
    $option = get_option( 'seur_manana_hasta_field' );
   ?>
   <select id="manana_hasta_type" name="seur_manana_hasta_field">
       <option value="11:00" <?php if ( $option == '11:00') echo ' selected'; ?>>11:00</option>
       <option value="12:00" <?php if ( $option == '12:00') echo ' selected'; ?>>12:00</option>
       <option value="13:00" <?php if ( $option == '13:00') echo ' selected'; ?>>13:00</option>
       <option value="14:00" <?php if ( $option == '14:00') echo ' selected'; ?>>14:00</option>
    </select>
    <?php }

function seur_tarde_desde_field(){
    $option = get_option( 'seur_tarde_desde_field' );
   ?>
   <select id="tarde_desde_type" name="seur_tarde_desde_field">
       <option value="15:00" <?php if ( $option == '15:00') echo ' selected'; ?>>15:00</option>
       <option value="16:00" <?php if ( $option == '16:00') echo ' selected'; ?>>16:00</option>
       <option value="17:00" <?php if ( $option == '17:00') echo ' selected'; ?>>17:00</option>
       <option value="18:00" <?php if ( $option == '18:00') echo ' selected'; ?>>18:00</option>
    </select>
    <?php }

function seur_tarde_hasta_field(){
    $option = get_option( 'seur_tarde_hasta_field' );
   ?>
   <select id="tarde_hasta_type" name="seur_tarde_hasta_field">
       <option value="17:00" <?php if ( $option == '17:00') echo ' selected'; ?>>17:00</option>
       <option value="18:00" <?php if ( $option == '18:00') echo ' selected'; ?>>18:00</option>
       <option value="19:00" <?php if ( $option == '19:00') echo ' selected'; ?>>19:00</option>
       <option value="20:00" <?php if ( $option == '20:00') echo ' selected'; ?>>20:00</option>
    </select>
    <?php }

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
       <option value="C" <?php if ( $option == 'C') echo ' selected'; ?>><?php _e( 'C: Commercial', 'seur-oficial' ); ?></option>
       <option value="D" <?php if ( $option == 'D') echo ' selected'; ?>><?php _e( 'D: Documents', 'seur-oficial' ); ?></option>
       <option value="N" <?php if ( $option == 'N') echo ' selected'; ?>><?php _e( 'N: No Commercial', 'seur-oficial' ); ?></option>
       <option value="S" <?php if ( $option == 'S') echo ' selected'; ?>><?php _e( 'S: Envelopes', 'seur-oficial' ); ?></option>
    </select>
    <?php }

function seur_id_mercancia_field(){ ?>
    <input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type="text" name="seur_id_mercancia_field" value="<?php echo get_option('seur_id_mercancia_field'); ?>" size="40" />
    <?php }

function seur_descripcion_field(){ ?>
    <input title="<?php _e('SEUR field description', 'seur-oficial' ); ?>" type="text" name="seur_descripcion_field" value="<?php echo get_option('seur_descripcion_field'); ?>" size="40" />
    <?php }

function display_seur_advanced_settings_panel_fields(){

    add_settings_section( 'seur-advanced-settings-section', NULL, NULL, 'seur-advanced-settings-options' );

    add_settings_field( 'seur_after_get_label_field',    __('What to do after get order label',        'seur-oficial'), 'seur_after_get_label_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_preaviso_notificar_field',    __('Notificar recogida',        'seur-oficial'), 'seur_preaviso_notificar_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
     add_settings_field( 'seur_reparto_notificar_field',     __('Notificar reparto',         'seur-oficial'), 'seur_reparto_notificar_field',       'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_notificacion_field',    __('Notifications by SMS or Email',        'seur-oficial'), 'seur_tipo_notificacion_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_manana_desde_field',          __('Mañana desde',              'seur-oficial'), 'seur_manana_desde_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_manana_hasta_field',          __('Mañana hasta',              'seur-oficial'), 'seur_manana_hasta_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tarde_desde_field',           __('Tarde desde',               'seur-oficial'), 'seur_tarde_desde_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tarde_hasta_field',           __('Tarde hasta',               'seur-oficial'), 'seur_tarde_hasta_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_etiqueta_field',         __('Tipo etiqueta',             'seur-oficial'), 'seur_tipo_etiqueta_field',           'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_origen_field',         __('Aduana origen',             'seur-oficial'), 'seur_aduana_origen_field',           'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_destino_field',        __('Aduana destino',            'seur-oficial'), 'seur_aduana_destino_field',          'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_mercancia_field',        __('Tipo mercancia',            'seur-oficial'), 'seur_tipo_mercancia_field',          'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_id_mercancia_field',          __('ID Mercancia',              'seur-oficial'), 'seur_id_mercancia_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_descripcion_field',           __('Descripción internacional', 'seur-oficial'), 'seur_descripcion_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );

    // register all setings

    register_setting('seur-advanced-settings-section', 'seur_after_get_label_field'         );
    register_setting('seur-advanced-settings-section', 'seur_preaviso_notificar_field'      );
    register_setting('seur-advanced-settings-section', 'seur_reparto_notificar_field'       );
    register_setting('seur-advanced-settings-section', 'seur_tipo_notificacion_field'       );
    register_setting('seur-advanced-settings-section', 'seur_manana_desde_field'            );
    register_setting('seur-advanced-settings-section', 'seur_manana_hasta_field'            );
    register_setting('seur-advanced-settings-section', 'seur_tarde_desde_field'             );
    register_setting('seur-advanced-settings-section', 'seur_tarde_hasta_field'             );
    register_setting('seur-advanced-settings-section', 'seur_tipo_etiqueta_field'           );
    register_setting('seur-advanced-settings-section', 'seur_aduana_origen_field'           );
    register_setting('seur-advanced-settings-section', 'seur_aduana_destino_field'          );
    register_setting('seur-advanced-settings-section', 'seur_tipo_mercancia_field'          );
    register_setting('seur-advanced-settings-section', 'seur_id_mercancia_field'            );
    register_setting('seur-advanced-settings-section', 'seur_descripcion_field'             );

}

add_action('admin_init', 'display_seur_advanced_settings_panel_fields');