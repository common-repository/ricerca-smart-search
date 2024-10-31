<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;

 
 

Promotions::init();


class Promotions{
    private static $instance = null;

    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){ 
 

        \add_filter('ric_def_options', function($opt){
            if(empty($opt['promotions']['promotions_active'])){
                $opt['promotions']['promotions_active']='no';
            }
            return $opt;
        });
             
 

        if(\is_admin()){

                
            \add_filter('ric_settings_fields_array', function($fields){
            
                $f=[];
                $f['key'] = 'promotions_active';
                $f['def'] = 'no';
                $f['type'] = 'checkbox';
                $f['subtype'] = 'none';
                $f['label'] =  __('Show Promotion items','ric');
                $f['label_row_after'] =  __("In addition to be active, there is need to configure the module",'ric');
                $f['ispro'] = true;
                $f['label_row_after_pro'] = sprintf( esc_html__("%s in order to market and promote products throughout your site that has been engaged with ricerca's search results.", 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
                $fields['promotions'][]=$f;


                return $fields;
            });
            
 
            \add_filter('ric_settings_tabs', function($tabs){
                $tabs[]= [
                    'name'=>__('Promotions','ric'),
                    'key'=>'promotions'
                ];
                return $tabs;
            });
 

            
            
            \add_action ( 'ric_after_settings_form',['\Ric\RicPro\Modules\Promotions','formHtml']);
            \add_action ( 'ric_tab_content_after_button_promotions',['\Ric\RicPro\Modules\Promotions','promotionButton']);
            

         
    
       

            
        } 
         

     

  

         


    }

 
    public static function promotionButton(){
        ?><div class="notfoundcorrection_list">
        
    </div>
        
        
        
        <div class="promotions_promotions">
            
            
            <a href="#promotions" class="ricmodal_open promotions_addpromotion_btn button button-primary button-large"><?php echo  __('Add promotion','ric'); ?></a>
            <div data-nonce="<?php echo wp_create_nonce('ric_nonce'); ?>" class="promotions_promotions_list"></div>
            
            </div><?php
  

    }
 

    

    public static  function formHtml(){

        ?>


        <div id="promotions" class="ricmodal  ">
                    <div class="ricmodalin">
        
                        <a class="ricmodal_close" href="#"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></a>
        
                        <div class="ricmodalin2">
        
                        <div class="promotions_promotion">
  
                            <input type="hidden" name="promotions_promotionid" value="-1" />
                            <label for="popmodal_title">
                                <span><?php echo  __('Promotion title','ric'); ?></span>
                                <input id="popmodal_title" type="text" name="title" />
                            </label>
                            <label for="popmodal_layout">
                                <span><?php echo  __('Layout','ric'); ?></span>
                                <select  name="layout" id="popmodal_layout" >
                                    <option  value="grid"><?php echo  __('Grid','ric'); ?></option>
                                    <!--<option value="carousel"><?php echo  __('Carousel','ric'); ?></option>-->
                                </select>
                            </label>
        
                            <label class="promotions_conditional" data-v="grid"   for="popmodal_items_inrow">
                                <span><?php echo  __('Items in row','ric'); ?></span>
                                <input id="popmodal_items_inrow" value="3" min="2" max="5"  type="number" name="items_inrow" />
                            </label>
        
                            <label class="promotions_conditional" data-v="carousel"  style="display:none;"  for="popmodal_items_inview">
                                <span><?php echo  __('Items in view','ric'); ?></span>
                                <input id="popmodal_items_inview" value="3" min="2" max="5" type="number" name="items_inview" />
                            </label>
        
        
                            <label for="popmodal_insert">
                                <span><?php echo  __('Insertion type','ric'); ?></span>
                                <select  name="insert" id="popmodal_insert" >
                                    <option  value="insertafter"><?php echo  __('Insert after','ric'); ?></option>
                                    <option value="insertinside"><?php echo  __('Insert inside','ric'); ?></option>
                                    <option value="replace_content"><?php echo  __('Replace selector content','ric'); ?></option>
                                </select>
                            </label>
        
                            <label for="popmodal_query">
                                <span><?php echo  __('Promotion Query','ric'); ?></span>
                                <select  name="query" id="popmodal_query" >
                                    <option  value="topclickeditems"><?php echo  __('Top Clicked items','ric'); ?></option>
                                    <option  value="toppurchaseditems"><?php echo  __('Top Purchased','ric'); ?></option>
                                    <option  value="topaddedtocartitems"><?php echo  __('Top Added to cart','ric'); ?></option>
                                </select>
                            </label>
        
        
                            <label class="popmodal_selector"   for="popmodal_selector">
                                <span><?php echo  __('CSS selector','ric'); ?></span>
                                <input id="popmodal_selector" type="text" name="selector" />
                            </label>
        
                            <a target="_blank" href="https://my.myricerca.com/downloads/pro/" class="promotions_free_addpromotion_a button button-primary button-large"><?php echo  __('Upgrade to premium','ric'); ?></a>
        
        
                            <div class="ric_err"></div>
                        </div>
        
                    </div>
                    </div>
                </div>
        <?php
    }

  
 


  

}
   

  


 

 