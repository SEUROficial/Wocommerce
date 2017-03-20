<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_after_get_label_field(){
   $option = get_option( 'seur_after_get_label_field' );
   ?>
   <select id="notification_type" name="seur_after_get_label_field">
       <option value="default"> <?php _e( "Select what to do", SEUR_TEXTDOMAIN ); ?> </option>
       <option value="shipping" <?php if ( $option == 'shipping') echo ' selected'; ?>><?php _e('Mark as Shipping', SEUR_TEXTDOMAIN ); ?></option>
       <option value="complete" <?php if ( $option == 'complete') echo ' selected'; ?>><?php _e('Mark as Complete', SEUR_TEXTDOMAIN ); ?></option>
    </select>
<?php   }

function seur_preaviso_notificar_field(){ ?>

     <input type="checkbox" class="js-switch-preavisonotificar" title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" name="seur_preaviso_notificar_field" value="1" <?php checked(1, get_option('seur_preaviso_notificar_field'), true); ?>/>
    <?php }

function seur_reparto_notificar_field(){ ?>
    <input type="checkbox" class="js-switch-repartonotificar" title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" name="seur_reparto_notificar_field" value="1" <?php checked(1, get_option('seur_reparto_notificar_field'), true); ?>/>
<?php }

function seur_tipo_notificacion_field(){
   $option = get_option( 'seur_tipo_notificacion_field' );
   ?>
   <select id="notification_type" name="seur_tipo_notificacion_field">
       <option value="default"> <?php _e( "Select notification Type", SEUR_TEXTDOMAIN ); ?> </option>
       <option value="SMS" <?php if ( $option == 'SMS') echo ' selected'; ?>><?php _e('SMS (this option has an extra cost)', SEUR_TEXTDOMAIN); ?></option>
       <option value="EMAIL" <?php if ( $option == 'EMAIL') echo ' selected'; ?>>Email</option>
    </select>
<?php   }

function seur_manana_desde_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_manana_desde_field" value="<?php echo get_option('seur_manana_desde_field'); ?>" size="40" />
    <?php }

function seur_manana_hasta_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_manana_hasta_field" value="<?php echo get_option('seur_manana_hasta_field'); ?>" size="40" />
    <?php }

function seur_tarde_desde_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_tarde_desde_field" value="<?php echo get_option('seur_tarde_desde_field'); ?>" size="40" />
    <?php }

function seur_tarde_hasta_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_tarde_hasta_field" value="<?php echo get_option('seur_tarde_hasta_field'); ?>" size="40" />
    <?php }

function seur_tipo_etiqueta_field(){
   $option = get_option( 'seur_tipo_etiqueta_field' );
   ?>
   <select id="label_type" name="seur_tipo_etiqueta_field">
       <option value="default"> <?php _e( "Select Label Type", SEUR_TEXTDOMAIN ); ?> </option>
       <option value="PDF" <?php if ( $option == 'PDF') echo ' selected'; ?>>PDF</option>
       <option value="TERMICA" <?php if ( $option == 'TERMICA') echo ' selected'; ?>>TERMICA</option>
    </select>
<?php   }

function seur_aduana_origen_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_aduana_origen_field" value="<?php echo get_option('seur_aduana_origen_field'); ?>" size="40" />
    <?php }

function seur_aduana_destino_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_aduana_destino_field" value="<?php echo get_option('seur_aduana_destino_field'); ?>" size="40" />
    <?php }

function seur_tipo_mercancia_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_tipo_mercancia_field" value="<?php echo get_option('seur_tipo_mercancia_field'); ?>" size="40" />
    <?php }

function seur_valor_declarado_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_valor_declarado_field" value="<?php echo get_option('seur_valor_declarado_field'); ?>" size="40" />
    <?php }

function seur_id_mercancia_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_id_mercancia_field" value="<?php echo get_option('seur_id_mercancia_field'); ?>" size="40" />
    <?php }

function seur_descripcion_field(){ ?>
    <input title="<?php _e('SEUR field description', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_descripcion_field" value="<?php echo get_option('seur_descripcion_field'); ?>" size="40" />
    <?php }

function display_seur_advanced_settings_panel_fields(){

    add_settings_section( 'seur-advanced-settings-section', NULL, NULL, 'seur-advanced-settings-options' );

    add_settings_field( 'seur_after_get_label_field',    __('What to do after get order label',        SEUR_TEXTDOMAIN), 'seur_after_get_label_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_preaviso_notificar_field',    __('Notificar recogida',        SEUR_TEXTDOMAIN), 'seur_preaviso_notificar_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
     add_settings_field( 'seur_reparto_notificar_field',     __('Notificar reparto',         SEUR_TEXTDOMAIN), 'seur_reparto_notificar_field',       'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_notificacion_field',    __('Notifications by SMS or Email',        SEUR_TEXTDOMAIN), 'seur_tipo_notificacion_field',      'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_manana_desde_field',          __('Mañana desde',              SEUR_TEXTDOMAIN), 'seur_manana_desde_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_manana_hasta_field',          __('Mañana hasta',              SEUR_TEXTDOMAIN), 'seur_manana_hasta_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tarde_desde_field',           __('Tarde desde',               SEUR_TEXTDOMAIN), 'seur_tarde_desde_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tarde_hasta_field',           __('Tarde hasta',               SEUR_TEXTDOMAIN), 'seur_tarde_hasta_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_etiqueta_field',         __('Tipo etiqueta',             SEUR_TEXTDOMAIN), 'seur_tipo_etiqueta_field',           'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_origen_field',         __('Aduana origen',             SEUR_TEXTDOMAIN), 'seur_aduana_origen_field',           'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_aduana_destino_field',        __('Aduana destino',            SEUR_TEXTDOMAIN), 'seur_aduana_destino_field',          'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_tipo_mercancia_field',        __('Tipo mercancia',            SEUR_TEXTDOMAIN), 'seur_tipo_mercancia_field',          'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_valor_declarado_field',       __('Valor declarado',           SEUR_TEXTDOMAIN), 'seur_valor_declarado_field',         'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_id_mercancia_field',          __('ID Mercancia',              SEUR_TEXTDOMAIN), 'seur_id_mercancia_field',            'seur-advanced-settings-options', 'seur-advanced-settings-section' );
    add_settings_field( 'seur_descripcion_field',           __('Descripción internacional', SEUR_TEXTDOMAIN), 'seur_descripcion_field',             'seur-advanced-settings-options', 'seur-advanced-settings-section' );

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
    register_setting('seur-advanced-settings-section', 'seur_valor_declarado_field'         );
    register_setting('seur-advanced-settings-section', 'seur_id_mercancia_field'            );
    register_setting('seur-advanced-settings-section', 'seur_descripcion_field'             );

}

add_action('admin_init', 'display_seur_advanced_settings_panel_fields');