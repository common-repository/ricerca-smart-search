<?php
namespace Ric;

defined( 'ABSPATH' ) || exit;



class RicPlugin{

    public static function activate(){
        //create db tables
        Db::createTables();

        do_action( 'ric_activate' );
    }
    public static function deactivate(){
        //drop db tables
        
        //Db::dropTables();

        /*
        * disable cron jobs
        */
        wp_clear_scheduled_hook( 'ric_everyfiveminutes_cron_jobs' );
        wp_clear_scheduled_hook( 'ric_hourly_cron_jobs' );
        do_action( 'ric_deactivate' );

    }
    public static function uninstall(){
        //drop db tables
        Db::dropTables();


        /*
        * disable cron jobs
        */
        wp_clear_scheduled_hook( 'ric_everyfiveminutes_cron_jobs' );
        wp_clear_scheduled_hook( 'ric_hourly_cron_jobs' );
        do_action( 'ric_uninstall' );
    }

    public static function init(){
 

        if ( ! version_compare( PHP_VERSION, RIC_MIN_PHP, '>=' ) ) {
            \add_action( 'admin_notices',function(){
                RicPlugin::fail('php');
            });
        } elseif ( ! version_compare( get_bloginfo( 'version' ), RIC_MIN_WP, '>=' ) ) {
            \add_action( 'admin_notices',function(){
                RicPlugin::fail('wp');
            });
        } else {


            require RIC_DIR.'inc/db.php';

         

            // Activation and deactivation hook.
            register_activation_hook( RIC_FILE, 'Ric\RicPlugin::activate');
            register_deactivation_hook( RIC_FILE, 'Ric\RicPlugin::deactivate');
            register_uninstall_hook( RIC_FILE,'Ric\RicPlugin::uninstall');

            require RIC_DIR.'inc/ric_class.php';
            
            require RIC_DIR.'plugins/plugins.php';

            \add_action( 'plugins_loaded', function(){
                RicPlugin::loaded();
                Db::checkDbUpdates();
            } );

        
            \add_filter( 'cron_schedules',function($schedules) {
                $schedules['everyfiveminutes'] = array(
                    'interval' => 300,
                    'display' => esc_html__('Every 5 minutes','ric')
                );
                $schedules['twice_hour'] = array(
                    'interval' => 1800,
                    'display' => esc_html__('Twice at hour','ric')
                );
                return $schedules;
            }); 
            
            \add_action( 'activated_plugin',function($plugin){
                if( $plugin == plugin_basename( __FILE__ ) ) {
                    exit( wp_redirect( admin_url( 'admin.php?page=ric_plugin_admin_menu_sync&firstsync=true' ) ) );
                }
            }); 
 

        }
    }
 
    public static function fail($error) {
        if($error=='php'){
            $message = sprintf( esc_html__( 'Ricerca requires minimum PHP version %s.', 'ric' ), RIC_MIN_PHP );
        }elseif($error=='wp'){
            $message = sprintf( esc_html__( 'Ricerca requires minimum WordPress version %s+.', 'ric' ), RIC_MIN_WP );
        }
        $html_message = \sprintf( '<div class="error">%s</div>', wpautop( $message ) );
        echo $html_message;
    }


    /**
    * Plugin init
    *
    * @since 1.0.0
    * @param none
    * @return null
    */
    public static function loaded() {

        //* Localization Code */
        \load_plugin_textdomain(
            'ric',
            false,
            \dirname(plugin_basename( __FILE__ ))  . '/languages'
        );


            
 
        /*
        * load plugin
        */
        RicMain::get_instance();
    } 
}