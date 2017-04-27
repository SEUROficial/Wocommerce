<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_nif_field(){ ?>
    <input title="<?php _e('NIF- NIF de la empresa', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_nif_field" value="<?php echo get_option('seur_nif_field'); ?>" size="40" />
    <?php }

function seur_empresa_field(){ ?>
    <input title="<?php _e('Empresa', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_empresa_field" value="<?php echo get_option('seur_empresa_field'); ?>" size="40" />
    <?php }

function seur_viatipo_field(){ ?>
    <input title="<?php _e('CL para calle, Av para Avenida, PZA para Plaza', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_viatipo_field" value="<?php echo get_option('seur_viatipo_field'); ?>" size="40" />
    <?php }

function seur_vianombre_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_vianombre_field" value="<?php echo get_option('seur_vianombre_field'); ?>" size="40" />
    <?php }

function seur_vianumero_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_vianumero_field" value="<?php echo get_option('seur_vianumero_field'); ?>" size="40" />
    <?php }

function seur_escalera_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_escalera_field" value="<?php echo get_option('seur_escalera_field'); ?>" size="40" />
    <?php }

function seur_piso_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_piso_field" value="<?php echo get_option('seur_piso_field'); ?>" size="40" />
    <?php }

function seur_puerta_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_puerta_field" value="<?php echo get_option('seur_puerta_field'); ?>" size="40" />
    <?php }

function seur_postal_field(){ ?>
    <input title="<?php _e('Para España 5 dígitos, para Portugal 4 dígitos', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_postal_field" value="<?php echo get_option('seur_postal_field'); ?>" size="40" />
    <?php }

function seur_poblacion_field(){ ?>
    <input title="<?php _e('Población donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_poblacion_field" value="<?php echo get_option('seur_poblacion_field'); ?>" size="40" />
    <?php }

function seur_provincia_field(){ ?>
    <input title="<?php _e('Provincia donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_provincia_field" value="<?php echo get_option('seur_provincia_field'); ?>" size="40" />
    <?php }

function seur_pais_field(){ ?>
    <input title="<?php _e('País donde vamos a recoger', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_pais_field" value="<?php echo get_option('seur_pais_field'); ?>" size="40" />
    <?php }

function seur_telefono_field(){ ?>
    <input title="<?php _e('Teléfono de contacto', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_telefono_field" value="<?php echo get_option('seur_telefono_field'); ?>" size="40" />
    <?php }

function seur_email_field(){ ?>
    <input title="<?php _e('Email de contacto', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_email_field" value="<?php echo get_option('seur_email_field'); ?>" size="40" />
    <?php }

function seur_contacto_nombre_field(){ ?>
    <input title="<?php _e('Persona de contacto', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_contacto_nombre_field" value="<?php echo get_option('seur_contacto_nombre_field'); ?>" size="40" />
    <?php }

function seur_contacto_apellidos_field(){ ?>
    <input title="<?php _e('Persona de contacto', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_contacto_apellidos_field" value="<?php echo get_option('seur_contacto_apellidos_field'); ?>" size="40" />
    <?php }

function seur_cit_codigo_field(){ ?>
    <input title="<?php _e('Código de cliente integrado', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_cit_codigo_field" value="<?php echo get_option('seur_cit_codigo_field'); ?>" size="40" />
    <?php }

function seur_cit_usuario_field(){ ?>
    <input title="<?php _e('Usuario para generar las etiquetas', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_cit_usuario_field" value="<?php echo get_option('seur_cit_usuario_field'); ?>" size="40" />
    <?php }

function seur_cit_contra_field(){ ?>
    <input title="<?php _e('Contraseña para generar las etiquetas', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_cit_contra_field" value="<?php echo get_option('seur_cit_contra_field'); ?>" size="40" />
    <?php }

function seur_ccc_field(){ ?>
    <input title="<?php _e('Código de Cuenta con SEUR', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_ccc_field" value="<?php echo get_option('seur_ccc_field'); ?>" size="40" maxlength="5" />
    <?php }

function seur_franquicia_field(){ ?>
    <input title="<?php _e('Código numérico de dos dígitos', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_franquicia_field" value="<?php echo get_option('seur_franquicia_field'); ?>" size="40" maxlength="2" />
    <?php }

function seur_seurcom_usuario_field(){ ?>
    <input title="<?php _e('Usuario de acceso a seur.com', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_seurcom_usuario_field" value="<?php echo get_option('seur_seurcom_usuario_field'); ?>" size="40" />
    <?php }

function seur_seurcom_contra_field(){ ?>
    <input title="<?php _e('Contraseña de acceso seur.com', SEUR_TEXTDOMAIN ); ?>" type="text" name="seur_seurcom_contra_field" value="<?php echo get_option('seur_seurcom_contra_field'); ?>" size="40" />
    <?php }


function display_seur_user_sittings_panel_fields(){

    add_settings_section( 'seur-user-settings-section', NULL, NULL, 'seur-user-settings-options' );
    add_settings_field( 'seur_nif_field',                   __('NIF',                       SEUR_TEXTDOMAIN), 'seur_nif_field',                     'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_empresa_field',               __('Empresa',                   SEUR_TEXTDOMAIN), 'seur_empresa_field',                 'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_viatipo_field',               __('Via tipo',                  SEUR_TEXTDOMAIN), 'seur_viatipo_field',                 'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_vianombre_field',             __('Via nombre',                SEUR_TEXTDOMAIN), 'seur_vianombre_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_vianumero_field',             __('Via número',                SEUR_TEXTDOMAIN), 'seur_vianumero_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_escalera_field',              __('Escalera',                  SEUR_TEXTDOMAIN), 'seur_escalera_field',                'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_piso_field',                  __('Piso',                      SEUR_TEXTDOMAIN), 'seur_piso_field',                    'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_puerta_field',                __('Puerta',                    SEUR_TEXTDOMAIN), 'seur_puerta_field',                  'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_postal_field',                __('Códido postal',             SEUR_TEXTDOMAIN), 'seur_postal_field',                  'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_provincia_field',             __('Provincia',                 SEUR_TEXTDOMAIN), 'seur_provincia_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_poblacion_field',             __('Población',                 SEUR_TEXTDOMAIN), 'seur_poblacion_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_pais_field',                  __('País',                      SEUR_TEXTDOMAIN), 'seur_pais_field',                    'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_telefono_field',              __('Teléfono',                  SEUR_TEXTDOMAIN), 'seur_telefono_field',                'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_email_field',                 __('e-mail',                    SEUR_TEXTDOMAIN), 'seur_email_field',                   'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_contacto_nombre_field',       __('Nombre',                    SEUR_TEXTDOMAIN), 'seur_contacto_nombre_field',         'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_contacto_apellidos_field',    __('Apellidos',                 SEUR_TEXTDOMAIN), 'seur_contacto_apellidos_field',      'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_codigo_field',            __('CIT Código',                SEUR_TEXTDOMAIN), 'seur_cit_codigo_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_usuario_field',           __('CIT Usuario',               SEUR_TEXTDOMAIN), 'seur_cit_usuario_field',             'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_contra_field',            __('CIT Contraseña',            SEUR_TEXTDOMAIN), 'seur_cit_contra_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_ccc_field',                   __('CCC',                       SEUR_TEXTDOMAIN), 'seur_ccc_field',                     'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_franquicia_field',            __('Franquicia',                SEUR_TEXTDOMAIN), 'seur_franquicia_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_seurcom_usuario_field',       __('Usuario seur.com',          SEUR_TEXTDOMAIN), 'seur_seurcom_usuario_field',         'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_seurcom_contra_field',        __('Contraseña Seur.com',       SEUR_TEXTDOMAIN), 'seur_seurcom_contra_field',          'seur-user-settings-options', 'seur-user-settings-section' );


    // register all setings

    register_setting('seur-user-settings-section', 'seur_nif_field'                     );
    register_setting('seur-user-settings-section', 'seur_empresa_field'                 );
    register_setting('seur-user-settings-section', 'seur_viatipo_field'                 );
    register_setting('seur-user-settings-section', 'seur_vianombre_field'               );
    register_setting('seur-user-settings-section', 'seur_vianumero_field'               );
    register_setting('seur-user-settings-section', 'seur_escalera_field'                );
    register_setting('seur-user-settings-section', 'seur_piso_field'                    );
    register_setting('seur-user-settings-section', 'seur_puerta_field'                  );
    register_setting('seur-user-settings-section', 'seur_postal_field'                  );
    register_setting('seur-user-settings-section', 'seur_poblacion_field'               );
    register_setting('seur-user-settings-section', 'seur_provincia_field'               );
    register_setting('seur-user-settings-section', 'seur_pais_field'                    );
    register_setting('seur-user-settings-section', 'seur_telefono_field'                );
    register_setting('seur-user-settings-section', 'seur_email_field'                   );
    register_setting('seur-user-settings-section', 'seur_contacto_nombre_field'         );
    register_setting('seur-user-settings-section', 'seur_contacto_apellidos_field'      );
    register_setting('seur-user-settings-section', 'seur_cit_codigo_field'              );
    register_setting('seur-user-settings-section', 'seur_cit_usuario_field'             );
    register_setting('seur-user-settings-section', 'seur_cit_contra_field'              );
    register_setting('seur-user-settings-section', 'seur_ccc_field'                     );
    register_setting('seur-user-settings-section', 'seur_franquicia_field'              );
    register_setting('seur-user-settings-section', 'seur_seurcom_usuario_field'         );
    register_setting('seur-user-settings-section', 'seur_seurcom_contra_field'          );

}

add_action('admin_init', 'display_seur_user_sittings_panel_fields');