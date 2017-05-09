<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_settings(){ ?>

    <div class="wrap">
        <h1><?php echo __( 'SEUR Settings', SEUR_TEXTDOMAIN ) ?></h1>
                <?php
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } else {
            $active_tab = 'user_settings';
        }
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="admin.php?page=seur&tab=user_settings" class="nav-tab <?php echo $active_tab == 'user_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'User Settings', SEUR_TEXTDOMAIN ); ?></a>
            <a href="admin.php?page=seur&&tab=advanced_settings" class="nav-tab <?php echo $active_tab == 'advanced_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Advanced Settings', SEUR_TEXTDOMAIN ); ?></a>
        </h2>
        <form method="post" action="options.php">
            <?php
            if( $active_tab == 'user_settings' ) {
                $link = esc_url( admin_url( add_query_arg( array( 'import' => 'seur' ), 'admin.php' ) ) );

            ?>
            <div class="wp-filter">
                <p class="description" id="text-seur-rates"><?php _e('If you have a SEUR settings file, you can upload it through this ', SEUR_TEXTDOMAIN ); ?><a href="<?php echo $link; ?>"><?php  _e( 'Link', SEUR_TEXTDOMAIN ); ?> </a></p>
                </div>
                <?php
                settings_fields( "seur-user-settings-section");
                do_settings_sections( "seur-user-settings-options" );
                _e('(<sup>*</sup>) This data is provided by SEUR', SEUR_TEXTDOMAIN );
            } else {
                settings_fields( "seur-advanced-settings-section");
                do_settings_sections( "seur-advanced-settings-options" );
                }
            submit_button();
            ?>
        </form>
        <script type="text/javascript">

		      var preavisonotificar = document.querySelector('.js-switch-preavisonotificar');
		      if ( preavisonotificar ) {
			      var switchery = new Switchery(preavisonotificar, { size: 'small' });
			      }

		      var repartonotificar = document.querySelector('.js-switch-repartonotificar');
		      if ( repartonotificar ) {
		      	var switchery = new Switchery(repartonotificar, { size: 'small' });
		      	}

		</script>
    </div>
<?php }

function seur_settings_load_css( $hook ){
    global $seurconfig;
    if( $seurconfig != $hook ) {
        return;
    } else {
        wp_register_style(  'seur_switchery_css', SEUR_PLUGIN_URL . '/assets/css/switchery.css', array(), SEUR_OFFICIAL_VERSION  );
        wp_enqueue_style(   'seur_switchery_css');
    }
}
add_action( 'admin_enqueue_scripts', 'seur_settings_load_css' );

//Include all options

include_once( 'setting-options/user-settings.php' );
include_once( 'setting-options/advanced-settings.php' );

?>