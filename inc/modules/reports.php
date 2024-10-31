<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;



ReportsFree::init();

class ReportsFree{

    private static $instance = null;


    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public  function __construct(){



            
        \add_filter('ric_settings_tabs', function($tabs){
            $tabs[]= [
                'name'=>__('Reports','ric'),
                'key'=>'reports'
            ];
            return $tabs;
        });

 
 

            
 
            \add_filter('ric_settings_fields_array', function($fields){

          

                $f=[];
                $f['key'] = 'free_reports_delete';
                $f['def'] = '60';
                $f['type'] = 'select';
                $f['subtype'] = 'none';
                $f['options'] = [
                    '7' =>sprintf( esc_html__('After %d days', 'ric' ), 7),
                    '30' => sprintf( esc_html__('After %d days', 'ric' ), 30),
                    '60' => sprintf( esc_html__('After %d days', 'ric' ), 60),
                    '180' => sprintf( esc_html__('After %d days', 'ric' ), 180),
                    '365' => sprintf( esc_html__('After %d days', 'ric' ), 365)
                ];
                $f['label'] = __('Delete reports','ric');
                $f['label_row_after'] = '';
                $f['ispro'] = true;
                $f['label_row_after_pro'] = sprintf( esc_html__("Wanna get real statistics data on your website's visitors engagement with Ricerc's search results? %s and start anlyzing your site.", 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade here",'ric').'</a>');
              
                $fields['reports'][]=$f;
            
              
            
            
                return $fields;
            });

     

     
            \add_action('admin_menu', ['\Ric\RicPro\Modules\ReportsFree','menu'],20);
 


    }
 
    public static function adminPage(){

        
        $page_type=!empty($_GET['page']) ? wp_kses_post($_GET['page']) :false;
        
             
        if(!$page_type || $page_type!='ric_plugin_admin_menu_reports_free'){
                return false;
        }
    
        $screen = get_current_screen();
        if ( $screen->parent_file != 'ric_plugin_admin_menu_page' )
                return;


         
        
                ?>
                <div class="ric_admin_container ric_admin_container_collection">
                    <h1><?php echo __('Reports','ric'); ?></h1>
        
        
                    <h2 style="margin-bottom:40px;"><?php echo sprintf( esc_html__("Wanna get real statistics data on your website's visitors engagement with Ricerc's search results? %s and start anlyzing your site.", 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade here",'ric').'</a>'); ?></h2>
                    <?php
                 
         
                    echo '<img src="'.\Ric\Func::getAssets('images/r1.png').'" class="ric_col_ad_placeholder" alt="" />';
                    echo '<img src="'.\Ric\Func::getAssets('images/r2.png').'" class="ric_col_ad_placeholder" alt="" />';
                    echo '<img src="'.\Ric\Func::getAssets('images/r3.png').'" class="ric_col_ad_placeholder" alt="" />';
        
        echo '</div>';
                    


    }
    public static function menu(){
        add_submenu_page(
            'ric_plugin_admin_menu_page',
            __('Reports','ric'), 
            __('Reports','ric'), 
            'manage_options',
            'ric_plugin_admin_menu_reports_free',
            ['\Ric\RicPro\Modules\ReportsFree','adminPage']
            ,5
            );
    }
}
 


 