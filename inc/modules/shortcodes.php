<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;

 
 

Shortcodes::init();


class Shortcodes{
    private static $instance = null;

    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){ 
 
        
        \add_action('ric_after_settings_tabs',function(){
            echo '<a class="ric_tab_toggle" href="#ric-tab-int">'.__('Short codes','ric').'</a>';
        });



        \add_action('ric_after_settings_tabs_content',function(){
    
            echo '<div id="ric-tab-int" class=" ric_tab_content_item">';
                                
            
                echo '<h3 class="hndle"><span>'.__('Short codes','ric').'</span></h3>';
                echo '<p class="hndle">'.__('Ricerca can work simply on a default WordPress search form.','ric');
                echo '<br/>'.__('If you need some customization here are some codes that can assist.','ric').'</p>';
      
                echo '<div class="ric_shortcodes_pro">';
                    echo '<div class="ric_shortcodes_pro_mask"></div>';
                    echo '<h4 class="hndle"><span>'.__('Form short code','ric').'</span></h4>';
                    echo '<input   name="ricerca_short_code" readonly="true" value="[ricercashortcode]" />';
                echo '</div>'   ;

                \do_action('ric_short_codes_pro1');

                echo '<hr/>';
                echo '<div class="ric_shortcodes_pro">';
                    echo '<div class="ric_shortcodes_pro_mask"></div>';
                    echo '<h4 class="hndle"><span>'.__('Icon button short code','ric').'</span></h4>';
                    echo '<p class="hndle">'.__('This shortcode can work only with Modal layout.','ric').'</p>';
                    echo '<input   name="ricerca_short_code_button" readonly="true" value="'.esc_attr('[ricercashortcode type="button"]').'" >';
                echo '</div>'  ; 
               
                \do_action('ric_short_codes_pro2');


                echo '<hr/>';
                    echo '<h4 class="hndle"><span>'.__('Labeled button short code','ric').'</span></h4>';
                    echo '<p class="hndle">'.__('This shortcode can work only with Modal layout.','ric').'</p>';
                    echo '<input name="ricerca_short_code_buttonl" readonly="true" value="'.esc_attr('[ricercashortcode type="button" icon="search"]').'" >';
             
                


                do_action('ric_short_codes_pro3');


            echo '</div>';
        });


        \add_shortcode('ricercashortcode', ['\Ric\RicPro\Modules\Shortcodes','code']);

        \add_action('ric_short_codes_pro1', ['\Ric\RicPro\Modules\Shortcodes','pro']);
        \add_action('ric_short_codes_pro2', ['\Ric\RicPro\Modules\Shortcodes','pro']);
        
  
    }

 
  
     
    public static function pro(){
        echo '<h3 class="ric_pro_message">'.sprintf( esc_html__('This option availble only to Premium users. %s here.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>').'</h3>';
    }
    public static function code($atts){

        $atts = shortcode_atts( array(
            'type' => 'form' ,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>' ,
        
        ), $atts, 'ricercashortcode' );
    
        ob_start();
        
    
    
        switch($atts['type']){
            case 'button': 
                ?><div class="ricercabuttonshortcode_wrap ricercabuttonshortcode_button">
                <style>
                    .ricercabuttonshortcode_button{
                        display:inline-flex; 
                    }
                    .ricercabuttonshortcode_button button svg{
                        width:20px; height:20px;
                    }
                    .ricercabuttonshortcode_button button{
                        display: block; border:0; background-color:transparent; cursor: pointer;
                        padding:0; margin:0;
                    }
                    </style>
                    <button class="ric_toggle_search"><?php echo $atts['icon'] ;?><button>
                </div>
                <?php
            break; 
        }
    
     
     

     
         do_action('ric_short_codes_types',$atts);


      
        $content =ob_get_contents();
        ob_clean();
        return $content;



    }


  

}
   

  


 

 