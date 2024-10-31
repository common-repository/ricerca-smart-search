<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;



Status::init();

class Status{

    private static $instance = null;

    public $ver = '1.0.0';
	
    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){


        
        \add_action('admin_menu',function(){    
                \add_submenu_page(
                        'ric_plugin_admin_menu_page',
                        __('Status','ric'), 
                        __('Status','ric'), 
                        'manage_options',
                        'ric_plugin_admin_menu_status',
                        [$this,'adminPage'],

                        100
                        );
            },100);
    




 


        \add_action ( 'wp_ajax_ric_export_settings', function(){
                
       
  
                if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ric_nonce' ) 
                ) {
                    echo json_encode(['error'=>true,'message'=>__('Invailed request','ric'),'html'=>'']);
                    die();
                } 



                $settings = \Ric\Func::getOption();
 

                        echo json_encode(['error'=>false,'data'=>$settings]);
                        die();


        });

 
 
    }
    public  function adminPage(){
 
    
        $page_type=!empty($_GET['page']) ? wp_kses_post($_GET['page']) :false;

         
        if(!$page_type || $page_type!='ric_plugin_admin_menu_status'){
                return false;
        }
        $screen = get_current_screen();
  
        if ( $screen->parent_file != 'ric_plugin_admin_menu_page' )
                return;
     
                $nonce= wp_create_nonce('ric_nonce');
                echo '<div id="ric-tab-status" class="current ric_tab_content_item">';
                        echo '<h3 class="hndle"><span>'.__('Export settings','ric').'</span></h3>';
                        echo '<p class="hndle">'.__('Click here to export all settings.','ric');
                        echo '<br/><button data-nonce="'.$nonce.'" class="button button-primary button-large ric_export_settings">'.__('Export','ric').'</button></p>';

                        echo '<hr/>';

                echo '<div id="ric-tab-status2" class="current ric_tab_content_item">';
                        echo '<h3 class="hndle"><span>'.__('Environment info','ric').'</span></h3>';


                        // WP memory limit.
                        $wp_memory_limit = WP_MEMORY_LIMIT;
                        if ( function_exists( 'memory_get_usage' ) ) {
                                $wp_memory_limit = max( $wp_memory_limit,  @ini_get( 'memory_limit' )  ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
                        }
 
                        $r = [
                        'Version'                  => RIC_VER,
                        'Pro version'                  => defined('RIC_PRO_VER') ? RIC_PRO_VER :  'not installed',
                        'Db version'                  => RIC_DB_VER,
                        'Home url'                  => get_option( 'home' ),
			'Site url'                  => get_option( 'siteurl' ),
			'Woocommerce version'                   => function_exists('WC') ?  WC()->version : 'not installed',
		        'WordPress version'                => get_bloginfo( 'version' ),
			'WordPress multisite'              => is_multisite() ? 'yes' : 'no',
			'WordPress memory limit'           => $wp_memory_limit,
			'Debug mode'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
			'Cronjob disabled'                   => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
			'Language'                  => get_locale(), 
			'Server'               => isset( $_SERVER['SERVER_SOFTWARE'] ) ?  wp_unslash( $_SERVER['SERVER_SOFTWARE'] )  : '',
			'PHP version'               => phpversion(),
                        'PHP Upload max filesize'           => ini_get( 'upload_max_filesize' ),
			'PHP Upload post max size'         => ini_get( 'post_max_size' ) ,
			'PHP max execute time'    => (int) ini_get( 'max_execution_time' ),
			'PHP input vars'        => (int) ini_get( 'max_input_vars' ),
		
			  
			'MySql version'             => \Ric\Db::version(),
			'Timezone'          => date_default_timezone_get(),
			'MBstring'          => extension_loaded( 'mbstring' ) ? 'yes' : 'no'
                        
                        ];

 
                        echo '<textarea style="direction:ltr;width:100%; white-space:pre-wrap;height:300px;" readonly="" >';

                                foreach($r as $k=>$v){
                                        echo $k.': '.$v.PHP_EOL;
                                }
                                


                        echo '</textarea>';


                        echo '<hr/>';

                       
/*


  echo '<h3 class="hndle"><span>'.__('Import settings','ric').'</span></h3>';
                        echo '<p class="hndle">'.__('Click here to import settings. (JSON only)','ric');
                       

                        echo '<br/>';
                    ?>
                       <form class="ric_import_settings">
                            <input type="hidden" name="action" value="ric_import_settings" />

                            <?php
wp_nonce_field( 'ric_nonce', 'ric_nonce' );

?>


<input name="ric_json" type="file" accept="application/JSON" />

<?php

echo '<br/>';

echo '<button  class="button button-primary button-large">'.__('Import','ric').'</button>';
?>
        </form>
                    
                    <?php
                        


                        echo '<hr/>';*/

                echo '</div>';

 
    }
 
}