<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;


adminAjax::init();

class adminAjax{


    
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

        \add_action ( 'wp_ajax_ric_plugin_save_settings', [$this,'saveSettings']);
 

        \add_action ( 'wp_ajax_ric_index_act', [$this,'doSingleItemAct']);  
        \add_action ( 'wp_ajax_ric_get_items', [$this,'getItems']);  

        \add_action ( 'wp_ajax_ric_plugin_sync', [$this,'doSync']);  
        \add_action ( 'wp_ajax_ric_do_crons', [$this,'doCrons']);  
 

 
        //admin search items
        \add_action ( 'wp_ajax_ric_search_items', [$this,'ric_search_items']);  
  
    }


    public function doSingleItemAct(){
        global $_POST;
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ric_nonce' ) 
        ) {
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        } 


        $id = \absint($_POST['id']);
        $act = \wp_kses_post($_POST['act']);

        switch($act){
            case 'delete':
                $pdata = Db::getRowByColumn('items','wp_id',$id);
                if(!empty($pdata)){
                    Db::deleteById('items',$pdata->id);
                }
            break;
            case 'sync':
                    
           
                    $savedData= \get_post($id);
                    if(!empty($savedData)){
                        Func::syncPost($savedData);
                    }
                    
               
            break;
        }


        echo \json_encode(['error'=>false,'message'=>'Done']);
            die();

    }
    public function getItems(){
        global $_POST;
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ric_nonce' ) 
        ) {
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        } 


        $items =Func::getItems(false);
    
        $html='<h2>'.__('No Items found in index','ric').'</h2>';
        if(!empty($items)){
            $html='<table id="index_table" data-search="true" data-pagenation="true" class="ric_table_wide">';
                $html.='<thead>';
                    $html.='<tr>';
                        $html.='<th>'.__('ID','ric').'</th>';
                        $html.='<th>'.__('Title','ric').'</th>';
                        $html.='<th>'.__('Post type','ric').'</th>';
                        $html.='<th>'.__('Thumbnail','ric').'</th>';
                        $html.='<th></th>';
                    $html.='</tr>';
                $html.='</thead>';
                $html.='<tbody>';

                $nonce = wp_create_nonce('ric_nonce');

                    foreach($items as $item){
                        $html.='<tr>';
                            $html.='<td>'.$item['id'].'</td>';
                            $html.='<td>'.$item['title'].'</td>';
                            $html.='<td class="ricexcludesearch">'.$item['post_type'].'</td>';
                            $html.='<td class="ricexcludesearch">';
                                if(!empty($item['image'])){
                                    $html.='<div class="ric_lazy" data-src="'.esc_attr($item['image']).'"  data-alt="'.esc_attr($item['title']).'" />';
                                }
                            $html.='</td>';
                            $html.='<td class="ricexcludesearch">';
                                $html.='<div class="items_in_index_buttons">';

                                    $html.='<button class="ric_index_item_act" data-act="sync" data-nonce="'.$nonce.'" title="'.__('Sync','ric').'" data-id="'.$item['id'].'"><span class="dashicons dashicons-update"></span></button>';
                                    $html.='<button class="ric_index_item_act" data-act="delete"  data-nonce="'.$nonce.'" title="'.__('Delete','ric').'" data-id="'.$item['id'].'"><span class="dashicons dashicons-trash"></span></button>';
                                $html.='</div>';
                            
                            $html.='</td>';
                        $html.='</tr>';
                    }
                    
                $html.='</tbody>';



            $html.='</table>';

        }
        echo \json_encode(['error'=>false,'html'=>$html]);
    
        die();

    }
    public function doCrons(){

        $crons = Func::getCronsList();

             /*
         * current offset
         */
        $offset = \absint($_POST['offset']); 

        $nextOffset = $offset+1;

        $isDone=false;


        if(!empty($crons[$offset])){

            $theCron = $crons[$offset];

            do_action($theCron);

 


        }else{
            $isDone=true;
        }

        echo \json_encode(['isdone'=>$isDone,'error'=>false,'percent'=>'0','nextoffset'=>$nextOffset]);
    
        die();




    }



    public function ric_search_items(){

        global $_POST;

        global $_POST;
        if(empty($_POST)){
               echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ric_nonce' ) 
        ) {
            echo \json_encode(['error'=>true,'message'=>'Genral error']);
            die();
        } 

     if (!isset( $_POST['source'] ) ) {
        echo \json_encode(['error'=>true,'message'=>'Genral error']);
        die();
        }
     if (!isset( $_POST['val'] ) ) {
        echo \json_encode(['error'=>true,'message'=>'Genral error']);
        die();
        }


        $items=[];


        $key= wp_kses_post($_POST['key']);
        $val= wp_kses_post($_POST['val']);
        $source= wp_kses_post($_POST['source']);

       
        switch($source){
            case 'items':
                $args=[];
                $args['s'] = $val;
                $args['scolumn'] = 'title'; 
                $args['orderby'] = 'title'; 
                $items= Db::select('items',$args);
            break;
            case 'terms':
                $args=[];
                $args['s'] = $val;
                $args['scolumn'] = 'title'; 
                $args['orderby'] = 'title'; 
                $items= Db::select('taxonomies',$args);
            break;
        }
     
      

        $listHtml='';
        if(!empty($items)){
            foreach($items as $item){

                $title = $item->wp_id;
                if(!empty($item->addition_data)){
                    $addD= unserialize($item->addition_data); 
                    if(!empty($addD['wo_sku'])){
                        $title.= ' | '.$addD['wo_sku'];
                    }
                }
                $title.= ' | '.$item->title;

               
                 $listHtml.='<a data-key="'.$key.'" data-wp_id="'.$item->wp_id.'" href="#">'.$title.'</a>';
            }
        }



        echo \json_encode(['error'=>false,'message'=>'Success','data'=>$listHtml]);
        die();
 


   



    }

    public function doSync(){

        global $_POST;
          
  
        $settings=Func::getOption();
     
        
      
        /*
         * current offset
         */
         $offset = \absint($_POST['offset']); 
        
    
        /*
         * default is not done
         */
        $isDone=false;
        
        
         /* sync taxonomies */
        $typesTax=Func::getOption('sync_tax');
        if($offset===0 && !empty($typesTax) ){
          
    
            //marked tax to sync
            $taxToSync=[];
            foreach($typesTax as $t=>$v){
                if($v=='yes'){
                    $taxToSync[]=$t;
                }
            }
    
            //start sync
            $terms=[];
            if(!empty($taxToSync)){
                foreach($taxToSync as $ts){
                    //find none empty terms
                    $foundTerms = \get_terms( array('taxonomy' => $ts) );
                    if(!empty($foundTerms)){
                        foreach($foundTerms as $foundTerm){
                            $terms[$foundTerm->term_id]=$foundTerm;
                        }
                    }
                } 
            }
  
    
            if(!empty($terms)){
                foreach($terms as $term){
                    Func::syncTerm($term,$settings);
                }
            } 
             
        }
    
    
        
    
    
    
        //post types to sync
        $types=Func::getOption('sync');
        
        /*
         * is there types to sync
         */
         if(empty($types)){
             echo \json_encode(['isdone'=>false,'error'=>'true','message'=>esc_html__("No posts types to sync, plese modify the plugin's settings",'ric')]);
             die();
        }
    
    
    
    
        
    
        
        
        /*
         * posts count per post_type
         */
        $allPostToSyncCount=0;
        $selectes=[];
        foreach ($types as $name=>$issync){
            if($issync==='yes'){
                $selectes[]=$name;
                $count_products= \wp_count_posts($name);
                $allPostToSyncCount+=$count_products->publish;
            }
        }
        
         /*
         * verify is there types to sync
         */
         if(empty($selectes)){
             echo \json_encode(['isdone'=>false,'error'=>'true','message'=>esc_html__("No posts types to sync, plese modify the plugin's settings",'ric')]);
             
             die();
        }
           
        
 
        $items_per_sync=$settings['performance']['items_per_sync'];
   
        
 
    
    
        /*
         * query for selected tyoes
         */
        $args=[];
        $args['offset']=$offset;
        $args['posts_per_page']=$items_per_sync;
        $args['post_type']=$selectes;
        $prs= \get_posts($args);
        if(!empty($prs)){
            foreach ($prs as $pr){
                Func::syncPost($pr,$settings);
            }
            $percent = $offset/intval($allPostToSyncCount)*100;
        }else{



            


             $percent = 100;
               $isDone=true;
        }
        echo \json_encode(['isdone'=>$isDone,'error'=>false,'percent'=>$percent,'nextoffset'=>(\intval($offset)+ \intval($items_per_sync))]);
    
        die();

    }

    public function saveSettings(){
        global $_POST;


        
        if ( ! check_admin_referer( 'ric_nonce', 'ric_nonce' ) ) {
            echo json_encode(['error'=>true,'message'=>__('Invailed request','ric'),'html'=>'']);
            die();
        }

     

        $isThereTermsToExclude=false;
       
       $data=[];
       
       $sections=Fields::getFieldsSettings();
       foreach ($sections as $section=>$fields){
        foreach ($fields as $field){
            $key=$field['key'];
            $def=$field['def'];
            
            
            
            if(!isset($data[$section])){
                $data[$section]=[];
            }
            
           


            if($field['type']=='items_select' ){

                $max = !empty($field['max']) ? $field['max'] : false;
    
               
                if(empty($_POST[$key]) || !is_array($_POST[$key]) ){
                    $valToSave=[];
                }else{
                    $valToSave=[];


         

                    if(!empty($_POST[$key]['id']) &&
                        !empty($_POST[$key]['title'])){
                        foreach($_POST[$key]['id'] as $k=>$v){ 

                            $title = \wp_kses_post($_POST[$key]['title'][$k]);
                            //incase of
                            $title = \str_replace('\\','',$title);
                         
                            
                            if($max!==false && count($valToSave)<$max ){
                                $valToSave[$v] = $title;
                            }else if ($max===false){
                                $valToSave[$v] = $title;
                            }

                            
                        }
                    }
                
                }  


                if(!empty($valToSave) && $key =='advanced_roles_exclude_categories'){
                    $isThereTermsToExclude = $valToSave;
                }
         
             
            }else   if(!empty($_POST[$key])){


              

                $valToSave  = \wp_kses_post($_POST[$key]);
                
            
            }else{
                  $valToSave  = $def;
                
            }
            
            if($field['type']=='number'){
                $valToSave = \absint($valToSave);

                if(!empty($field['max']) && $valToSave>$field['max']){
                    $valToSave= $field['max'];
                }
            }


          



            if($field['type']=='unit'){
             
                $unitKey=$key.'_unit';

                if( !empty($_POST[$unitKey])){
              
                    $valToSaveUnit = \wp_kses_post($_POST[$unitKey]);
                    $data[$section][$unitKey] = $valToSaveUnit;

                     
                }
            }

 

         
 
            $data[$section][$key] = $valToSave;


            if(!empty($field['subfields_condition'])){
                foreach($field['subfields_condition'] as $f2){
                    foreach($f2 as $f3){
                        $sbKey = $f3['key'];
                        $unitKey=$sbKey.'_unit';

                        if(!empty($_POST[$sbKey]) && !empty($_POST[$unitKey])){
                            $valToSave  = \wp_kses_post($_POST[$sbKey]);
                            $valToSaveUnit = \wp_kses_post($_POST[$unitKey]);
                            
                            $data[$section][$sbKey] = $valToSave;
                            $data[$section][$unitKey] = $valToSaveUnit;

                             
                        }
                    }
                }
            }




            
        }
       }

      Func::setOption($data);



      if($isThereTermsToExclude && !empty($isThereTermsToExclude)){

            $ids=array_keys($isThereTermsToExclude);
            $taxQuery=[];
            foreach($ids as $id){
                $term= get_term($id);
                if(!is_wp_error($term)){
                    $taxQuery[]=[
                        'taxonomy'=>$term->taxonomy,
                        'field'=>'id',
                        'terms'=>$id,
                    ];
                }
              
         
            }


            $postsTypes = Func::getPostTypesToSync();
            if(!empty($taxQuery) && !empty($postsTypes)){


                $args['fields'] ='ids';
                $args['post_type'] = $postsTypes;
                $args['tax_query'] =$taxQuery ;
                if(count($taxQuery)>1){
                    $args['tax_query']['relation'] ='OR' ;
                }

                $postsIds = get_posts($args);

                \update_option('ric_exclude_posts_by_category', $postsIds,false);
                \update_option('ric_exclude_categories', $ids,false);
              
             
  
            }

         
 
      }




        $syncHtml = Func::getModalSync();
        
       
      echo \json_encode(['error'=>false,'message'=>esc_html__('Settings saved, Some of the changes will take a while.','ric'),'synchtml'=>$syncHtml]);
     
          die();
    }
}

 
 