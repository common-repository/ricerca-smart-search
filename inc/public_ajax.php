<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;
 
publicAjax::init();

class publicAjax{


    
    private static $instance = null;

    public $ver = '1.0.0';
    public $assetUrl = '';

    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }



    public function __construct(){ 

        \add_action ( 'wp_ajax_ric_wc_add_to_cart', [$this,'addToCart']);
        \add_action ( 'wp_ajax_nopriv_ric_wc_add_to_cart',[$this,'addToCart'] );  
 

        \add_action ( 'wp_ajax_ric_load_items', [$this,'loadItems']);
        \add_action ( 'wp_ajax_nopriv_ric_load_items',[$this,'loadItems'] );  
    }


    public function loadItems(){
 
        global $_POST;
        if(empty($_POST)){
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        }
 
           
        /*
        * security verify
        */
        if (!isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'],RIC_NONCE_KEY)) {
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
       }
           

   
        $front=Func::getItems();
      

     
        echo \json_encode(['error'=>false,'items'=>$front,'nonce'=> \wp_create_nonce(RIC_NONCE_KEY) ]);
        die(); 
    }

    public function addToCart(){
 
        global $_POST;
        if(empty($_POST)){
               echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        }
        if(empty($_POST['id'])){
               echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        }
    
        
        
           
        /*
        * security verify
        */
        if (!isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'],RIC_NONCE_KEY)) {
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
       }
           

       
        $id= \absint($_POST['id']);
     
     
        $r= \WC()->cart->add_to_cart( $id, 1 );
    
     
        echo \json_encode(['error'=>false,'message'=>'added']);
       
        die(); 
    }


  
    

}
 
 


 
 

