<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;

$hooks = new Hooks();

class Hooks{

    public function __construct(){
        \add_action( 'admin_init',[ $this, 'actions']);
    }

    public function actions(){
        
        add_action( 'untrash_post',[ $this, 'doPostSyncById'] );


        add_action( 'delete_post',[ $this, 'markAsDeleted'] );
        add_action( 'transition_post_status',[ $this, 'itemStatusChanged'], 10,3 );



        
        /*
            * hook sync post after save 
            */
        \add_action('save_post', array( $this, 'admin_save_post_cb' ) );
        
    }

    public function itemStatusChanged($new_status, $old_status, $post ){
        if ( $old_status == $new_status ){
            return;
        }
        if($new_status!='publish'){
            $this->markAsDeleted($post->ID);
        }
    }

    public function markAsDeleted($wpid){
        $pdata = Db::getRowByColumn('items','wp_id',$wpid);
        if(!empty($pdata)){
            Db::deleteById('items',$pdata->id);
        }
    }

 

    public function doPostSyncById($wpid){
        $types=Func::getPostTypesToSync();
        $savedData= \get_post(intval($wpid));

 
        if(!empty($types) && in_array($savedData->post_type, $types)){
            $savedData= \get_post(intval($wpid));
    
            if(!empty($savedData)){
 
                //in case post status changed
                if($savedData->post_status!='publish'){
                    
                    $pdata = Db::getRowByColumn('items','wp_id',$savedData->ID);
                    if(!empty($pdata)){
                        Db::deleteById('items',$pdata->id);
                    }

                }else{
                    Func::syncPost($savedData);
                }

                 
            }
        }


    }

    /**
    * sync post after save post
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function admin_save_post_cb(){
        global $_POST;
        if(!empty($_POST) && !empty($_POST['post_ID']) && !empty($_POST['post_type'])){
            $pid = \absint($_POST['post_ID']);
            $this->doPostSyncById($pid);
        }
    }
 
}
 
