<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;



DataLayer::init();

class DataLayer{

    private static $instance = null;


    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){

        \add_filter('ric_settings_fields_array', function($fields){

        
            $f=[];
            $f['key'] = 'datalayer_active';
            $f['def'] = 'no';
            $f['type'] = 'checkbox';
            $f['subtype'] = 'none';
            $f['label'] =  __("Allow to fire ricerca's events to GTM datalayer",'ric');
            $f['label_row_after'] = 'For additional info <a target="_blank" href="https://my.myricerca.com/datalayer-integration/">'.__('click here','ric').'</a>';
            $f['ispro'] = true;
            $f['label_row_after_pro'] = sprintf( esc_html__('%s and start tracking behavior data on your site.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
           
            $fields['general'][]=$f;
        

        
            return $fields;
        });
  
       
             

 
    }
 

 
 
}
 


 