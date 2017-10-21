<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_rates_prices( $post ) {
    // Declarando $wpdb global y usarlo para ejecutar una sentencia de consulta SQL
    global $wpdb;
    $rates_type = get_option( 'seur_rates_type_field' );
    $bloquear = '';
    if ( defined('SEUR_DEBUG') && SEUR_DEBUG == true ) {
        echo '============<br />';
        echo __('Debug info', 'seur' ) . '<br />';
        echo '============<br />';

        $usuarioCIT        = get_option( 'seur_cit_usuario_field' );
        $contraCIT         = get_option( 'seur_cit_contra_field' );
        $usuarioseurcom    = get_option( 'seur_seurcom_usuario_field' );
        $contrasenaseurcom = get_option( 'seur_seurcom_contra_field' );
        $ccc               = get_option( 'seur_ccc_field' );
        $franquicia        = get_option( 'seur_franquicia_field' );
        $p_nacional        = get_option( 'seur_nal_producto_field' );
        $s_nacional        = get_option( 'seur_nal_servicio_field' );
        $p_canarias        = get_option( 'seur_canarias_producto_field' );
        $s_canarias        = get_option( 'seur_canarias_servicio_field' );

        if ( get_option( 'seur_aduana_origen_field' ) == 'F')
            $aduanaO = 'P';
        else
            $aduanaO = 'D';

        if ( get_option( 'seur_aduana_destino_field' ) == 'F')
            $aduanaD = 'P';
        else
            $aduanaD = 'D';

        $tipomercancia   = get_option( 'seur_tipo_mercancia_field' );
        $p_internacional = get_option( 'seur_internacional_producto_field' );
        $s_internacional = get_option( 'seur_internacional_servicio_field' );

        echo 'CIT User: ' . $usuarioCIT . '<br />';
        echo 'CIT Password: ' . $contraCIT . '<br />';
        echo 'SEUR User: ' . $usuarioseurcom . '<br />';
        echo 'SEUR Pass: ' . $contrasenaseurcom . '<br />';
        echo 'CCC: ' . $ccc . '<br />';
        echo 'Franchise: ' . $franquicia . '<br />';
        echo 'NAL Product: ' . $p_nacional . '<br />';
        echo 'NAL Service: ' . $s_nacional . '<br />';
        echo 'Canaries Product: ' . $p_canarias . '<br />';
        echo 'Canaries Service: ' . $s_canarias . '<br />';
        echo 'Customs Origin: ' . $aduanaO . '<br />';
        echo 'Custom Destination: ' . $aduanaD . '<br />';
        echo 'Type Merchandise: ' . $tipomercancia . '<br />';
        echo 'International Product: ' . $p_internacional . '<br />';
        echo 'International Service: ' . $s_internacional . '<br />';

        $table_name = $wpdb->prefix . SEUR_PLUGIN_SVPR;
        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
            $seurtableexist = 'No';
        } else {
            $seurtableexist = 'Yes';
        }
        echo 'Table <code>' . $table_name . '</code> exist: ' . $seurtableexist . '<br />';

        echo '===============================================<br />';
        echo '===============================================<br />';
    } ?>

<div class="wrap">
        <h1><?php echo __( 'SEUR Rates', 'seur' ) ?></h1>

        <?php
    if( isset( $_GET[ 'tab' ] ) ) {
        $active_tab = $_GET[ 'tab' ];
    } else {
        $active_tab = 'calculate_rates';
    }
?>
<h2 class="nav-tab-wrapper">
    <a href="?page=seur_rates_prices&tab=calculate_rates" class="nav-tab <?php echo $active_tab == 'calculate_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Calculate Rates', 'seur' ); ?></a>
    <a href="?page=seur_rates_prices&tab=custom_rates" class="nav-tab <?php echo $active_tab == 'custom_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Custom Rates', 'seur' ); ?></a>
    <?php if ( $rates_type == 'weight' ) { ?>
    <a href="?page=seur_rates_prices&tab=limit_price_weight_rates" class="nav-tab <?php echo $active_tab == 'limit_price_weight_rates' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Rate Weight Settings', 'seur' ); ?></a>
    <?php } ?>
</h2>

<?php

if( $active_tab == 'calculate_rates' ) {
        include_once 'rates/seur-rates.php';
    } elseif ( $active_tab == 'custom_rates' ) {
        include_once 'rates/seur-custom-rates.php';
    } elseif ( $active_tab == 'limit_price_weight_rates' ) {
	    include_once 'rates/limit-price-weight-rates.php';
    }
?> </div>
<?php } ?>