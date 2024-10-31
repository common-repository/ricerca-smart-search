<?php
namespace Ric;

use Ric as Ric;
use Ric\RicPro as RicPro;

defined( 'ABSPATH' ) || exit;


Api::init();
class Api{

    private static $instance = null;

    public $ver = '1.0.0';
 
    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    
    public function __construct(){ 
 

        \add_action('ric_after_settings_tabs',function(){
            echo '<a class="ric_tab_toggle" href="#ric-tab-api">'.__('Api','ric').'</a>';
        });



        \add_action('ric_after_settings_tabs_content',function(){
    
            echo '<div id="ric-tab-api" class=" ric_tab_content_item">';
                                
            
            
                echo '<h2 class="hndle"><span>'.__('Api','ric').'</span></h2>';

                \do_action('ric_api_pro');

 
                echo '<p class="hndle">'.__('IMPORTANT: when using one or more of these functions, you must check for its existence before using it, otherwise your site will badly break with a fatal error at the next Plugin update (as WordPress deletes the plugin when updating it).','ric');
               
                echo '<p class="hndle">'.__('Using API can give you the ability to enhancing our features.','ric');
 


                echo '<hr>';

                echo '<h4 class="">'.__('Get top clicked items','ric').'</h3>';
                echo '<div class="ric_api_code">';
                    echo "ricApi()->get_top_clicked()";
                echo '</div>';
                echo '<hr>';

                echo '<h4 class="">'.__('Get top add to cart items','ric').'</h3>';
                echo '<div class="ric_api_code">';
                    echo "ricApi()->get_top_addedtocart()";
                echo '</div>';
               
              
                echo '<hr>';

                echo '<h4 class="">'.__('Get top purchased items','ric').'</h3>';
                echo '<div class="ric_api_code">';
                    echo "ricApi()->get_top_purchaed()";
                echo '</div>';
               
              
                echo '<hr>';

                echo '<h4 class="">'.__('Get promotion by id','ric').'</h3>';
                echo '<div class="ric_api_code">';
                    echo "ricApi()->get_promotion(id)";
                echo '</div>';
               
              


            echo '</div>';
        });


             
        \add_action('ric_api_pro', ['\Ric\Api','pro']);
 

    }



         
    public static function pro(){
        echo '<h3 class="ric_pro_message">'.sprintf( esc_html__('This option availble only to Premium users. %s here.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>').'</h3>';
    }



}

 