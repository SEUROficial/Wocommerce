<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_nif_field(){ ?>
    <input title="<?php _e('NIF- NIF de la empresa', 'seur' ); ?>" type="text" name="seur_nif_field" value="<?php echo get_option('seur_nif_field'); ?>" size="40" />
    <?php }

function seur_rates_type_field(){
	$option = get_option( 'seur_rates_type_field' );
?>

    <select id="seur_rates_type" name="seur_rates_type_field">
       <option value="price"  <?php if ( $option == 'price') echo ' selected'; ?>><?php _e('By Price', 'seur' ); ?></option>
       <option value="weight"  <?php if ( $option == 'weight') echo ' selected'; ?>><?php _e('By Weight', 'seur' ); ?></option>
    </select>
    <?php }

function seur_empresa_field(){ ?>
    <input title="<?php _e('Empresa', 'seur' ); ?>" type="text" name="seur_empresa_field" value="<?php echo get_option('seur_empresa_field'); ?>" size="40" />
    <?php }

function seur_viatipo_field(){
	$option = get_option( 'seur_viatipo_field' );
?>

    <select id="street_type" name="seur_viatipo_field">
       <option value="AVD"  <?php if ( $option == 'AVD') echo ' selected'; ?>><?php _e('Avenida', 'seur' ); ?></option>
       <option value="PZA"  <?php if ( $option == 'PZA') echo ' selected'; ?>><?php _e('Plaza', 'seur' ); ?></option>
       <option value="CL"  <?php if ( $option == 'CL') echo ' selected'; ?>><?php _e('Calle', 'seur' ); ?></option>
    </select>
    <?php }

function seur_vianombre_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_vianombre_field" value="<?php echo get_option('seur_vianombre_field'); ?>" size="40" />
    <?php }

function seur_vianumero_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_vianumero_field" value="<?php echo get_option('seur_vianumero_field'); ?>" size="40" />
    <?php }

function seur_escalera_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_escalera_field" value="<?php echo get_option('seur_escalera_field'); ?>" size="40" />
    <?php }

function seur_piso_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_piso_field" value="<?php echo get_option('seur_piso_field'); ?>" size="40" />
    <?php }

function seur_puerta_field(){ ?>
    <input title="<?php _e('Dirección donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_puerta_field" value="<?php echo get_option('seur_puerta_field'); ?>" size="40" />
    <?php }

function seur_postal_field(){ ?>
    <input title="<?php _e('Para España 5 dígitos, para Portugal 4 dígitos', 'seur' ); ?>" type="text" name="seur_postal_field" value="<?php echo get_option('seur_postal_field'); ?>" size="40" />
    <?php }

function seur_poblacion_field(){ ?>
    <input title="<?php _e('Población donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_poblacion_field" value="<?php echo get_option('seur_poblacion_field'); ?>" size="40" />
    <?php }

function seur_provincia_field(){ ?>
    <input title="<?php _e('Provincia donde vamos a recoger', 'seur' ); ?>" type="text" name="seur_provincia_field" value="<?php echo get_option('seur_provincia_field'); ?>" size="40" />
    <?php }

function seur_pais_field(){
    $option = get_option( 'seur_pais_field' );
	?>

    <select id="country" name="seur_pais_field">
       <option value="ES"  <?php if ( $option == 'ES') echo ' selected'; ?>><?php _e('Spain', 'seur' ); ?></option>
       <option value="PT"  <?php if ( $option == 'PT') echo ' selected'; ?>><?php _e('Portugal', 'seur' ); ?></option>
       <option value="AD"  <?php if ( $option == 'AD') echo ' selected'; ?>><?php _e('Andorra', 'seur' ); ?></option>
    </select>

    <?php }

function seur_telefono_field(){ ?>
    <input title="<?php _e('Teléfono de contacto', 'seur' ); ?>" type="text" name="seur_telefono_field" value="<?php echo get_option('seur_telefono_field'); ?>" size="40" />
    <?php }

function seur_email_field(){ ?>
    <input title="<?php _e('Email de contacto', 'seur' ); ?>" type="text" name="seur_email_field" value="<?php echo get_option('seur_email_field'); ?>" size="40" />
    <?php }

function seur_contacto_nombre_field(){ ?>
    <input title="<?php _e('Persona de contacto', 'seur' ); ?>" type="text" name="seur_contacto_nombre_field" value="<?php echo get_option('seur_contacto_nombre_field'); ?>" size="40" />
    <?php }

function seur_contacto_apellidos_field(){ ?>
    <input title="<?php _e('Persona de contacto', 'seur' ); ?>" type="text" name="seur_contacto_apellidos_field" value="<?php echo get_option('seur_contacto_apellidos_field'); ?>" size="40" />
    <?php }

function seur_cit_codigo_field(){ ?>
    <input title="<?php _e('Código de cliente integrado (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_cit_codigo_field" value="<?php echo get_option('seur_cit_codigo_field'); ?>" size="40" />
    <?php }

function seur_cit_usuario_field(){ ?>
    <input title="<?php _e('Usuario para generar las etiquetas (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_cit_usuario_field" value="<?php echo get_option('seur_cit_usuario_field'); ?>" size="40" />
    <?php }

function seur_cit_contra_field(){ ?>
    <input title="<?php _e('Contraseña para generar las etiquetas (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_cit_contra_field" value="<?php echo get_option('seur_cit_contra_field'); ?>" size="40" />
    <?php }

function seur_ccc_field(){ ?>
    <input title="<?php _e('Código de Cuenta con SEUR (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_ccc_field" value="<?php echo get_option('seur_ccc_field'); ?>" size="40" maxlength="5" />
    <?php }

function seur_franquicia_field(){ ?>
    <input title="<?php _e('Código numérico de dos dígitos (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_franquicia_field" value="<?php echo get_option('seur_franquicia_field'); ?>" size="40" maxlength="2" />
    <?php }

function seur_seurcom_usuario_field(){ ?>
    <input title="<?php _e('Usuario de acceso a seur.com (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_seurcom_usuario_field" value="<?php echo get_option('seur_seurcom_usuario_field'); ?>" size="40" />
    <?php }

function seur_seurcom_contra_field(){ ?>
    <input title="<?php _e('Contraseña de acceso seur.com (facilitado por SEUR)', 'seur' ); ?>" type="text" name="seur_seurcom_contra_field" value="<?php echo get_option('seur_seurcom_contra_field'); ?>" size="40" />
    <?php }


function display_seur_user_sittings_panel_fields(){

    add_settings_section( 'seur-user-settings-section', NULL, NULL, 'seur-user-settings-options' );
    add_settings_field( 'seur_nif_field',                   __('NIF',                       'seur'), 'seur_nif_field',                     'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_rates_type_field',            __('How to apply rates?',       'seur'), 'seur_rates_type_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_empresa_field',               __('Empresa',                   'seur'), 'seur_empresa_field',                 'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_viatipo_field',               __('Via tipo',                  'seur'), 'seur_viatipo_field',                 'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_vianombre_field',             __('Via nombre',                'seur'), 'seur_vianombre_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_vianumero_field',             __('Via número',                'seur'), 'seur_vianumero_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_escalera_field',              __('Escalera',                  'seur'), 'seur_escalera_field',                'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_piso_field',                  __('Piso',                      'seur'), 'seur_piso_field',                    'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_puerta_field',                __('Puerta',                    'seur'), 'seur_puerta_field',                  'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_postal_field',                __('Códido postal',             'seur'), 'seur_postal_field',                  'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_provincia_field',             __('Provincia',                 'seur'), 'seur_provincia_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_poblacion_field',             __('Población',                 'seur'), 'seur_poblacion_field',               'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_pais_field',                  __('País',                      'seur'), 'seur_pais_field',                    'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_telefono_field',              __('Teléfono',                  'seur'), 'seur_telefono_field',                'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_email_field',                 __('e-mail',                    'seur'), 'seur_email_field',                   'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_contacto_nombre_field',       __('Nombre',                    'seur'), 'seur_contacto_nombre_field',         'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_contacto_apellidos_field',    __('Apellidos',                 'seur'), 'seur_contacto_apellidos_field',      'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_codigo_field',            __('CIT Código<sup>*</sup>',                'seur'), 'seur_cit_codigo_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_usuario_field',           __('CIT Usuario<sup>*</sup>',               'seur'), 'seur_cit_usuario_field',             'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_cit_contra_field',            __('CIT Contraseña<sup>*</sup>',            'seur'), 'seur_cit_contra_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_ccc_field',                   __('CCC<sup>*</sup>',                       'seur'), 'seur_ccc_field',                     'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_franquicia_field',            __('Franquicia<sup>*</sup>',                'seur'), 'seur_franquicia_field',              'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_seurcom_usuario_field',       __('Usuario seur.com<sup>*</sup>',          'seur'), 'seur_seurcom_usuario_field',         'seur-user-settings-options', 'seur-user-settings-section' );
    add_settings_field( 'seur_seurcom_contra_field',        __('Contraseña Seur.com<sup>*</sup>',       'seur'), 'seur_seurcom_contra_field',          'seur-user-settings-options', 'seur-user-settings-section' );


    // register all setings

    register_setting('seur-user-settings-section', 'seur_nif_field'                     );
    register_setting('seur-user-settings-section', 'seur_rates_type_field'              );
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