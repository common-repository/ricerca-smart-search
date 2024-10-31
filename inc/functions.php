<?php
namespace Ric;

defined( 'ABSPATH' ) || exit;



class Func{


 
    public static function getModalSync(){
        
        $syncHtml = '<div class="ric_sync_modal">';
            $syncHtml.= '<div class="ric_sync_modal_in">';
                $syncHtml.= '<h2>'.esc_html__('Sync in process, please wait!','ric').'</h2>';
                $syncHtml.= '<h2>'.esc_html__('Please keep this tab open.','ric').'</h2>';
                $syncHtml.= Func::getSyncAdmin();
            $syncHtml.='</div>';
        $syncHtml.='</div>';


        return $syncHtml;
    }
   
    public static function getUpgradeModal(){
        
        $syncHtml = '<div class="ric_upgrade_modal">';
            $syncHtml.= '<div class="ric_upgrade_modal_in">';
            $syncHtml.= '<a class="ric_upgrade_modal_close" href="#"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></a>';
                $syncHtml.= '<h2>'.esc_html__('Upgrade to premium!','ric').'</h2>';
                
            $syncHtml.='</div>';
        $syncHtml.='</div>';


        return $syncHtml;
    }
   


 
    public static function getCronsList(){
        $list=[];

        $cron_jobs = get_option( 'cron' );
        if(!empty($cron_jobs)){
            foreach($cron_jobs as $cron_job1){
 
                if(!empty($cron_job1) && is_array($cron_job1)){
                    foreach($cron_job1 as $k=>$v){
                        $name = substr($k,0,4);
                        if($name=='ric_'){
                            $list[]=$k;
                        }
    
                        
                    }
                } 
            }
        }
 


        return $list;
    }


    /**
    * Save settings
    *
    * @since 1.0.0
    * @param array $data
    * @return array;
    */
    public static function setOption($data){
        \update_option(RIC_SETTINGS_KEY, $data,false);
    }



        
    /**
    * Is woocommerce plugin is active
    *
    * @since 1.0.0
    * @param none
    * @return bool
    */
    public static function isWoocommerceActive(){
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        return \is_plugin_active( 'woocommerce/woocommerce.php' );
    }
    
    /**
    * Get assets url
    *
    * @since 1.0.0
    * @param string $path
    * @return string
    */
    public static function getAssets($path=null){
        return RIC_URL.'assets/'.$path;
    }

    

    
 
    

        
    /**
    * Is plugin active
    *
    * @since 1.0.0
    * @param none
    * @return bool;
    */
    public static function isActive(){
        global $_GET;
        $opt= Func::getOption('general');
        $currentStatus =  $opt['ric_status'];
        /*
        * prev mode
        */
        if(!empty($_GET['ric_prev_mode']) && 
        $_GET['ric_prev_mode']==='true' && $currentStatus=='active_preview'){
            return true;
        }
        
        return $currentStatus=='active';
    }

    

    /**
    * Return public settings
    *
    * @since 1.0.0
    * @param none
    * @return array;
    */
    public static function getPublicSettings(){
        $ret=[];
        $ret['ajaxUrl']=\admin_url ( 'admin-ajax.php' );

        

        $ret['searchUrl']= \home_url ( '?s=' );
        $ret['nonce']=\wp_create_nonce(RIC_NONCE_KEY);
        $ret['newtab']=false;
        $ret['link_new_tab']=false;
        $ret['active']=Func::isActive();
        $ret['layout']='simple';
   
        $ret['show_user_history']=false;
        $ret['newtab']=false;
        $ret['selector_run']=false;
        $ret['image_fit']=false;
        $ret['show_images']=false;
        $ret['show_categories']=false;
        $ret['show_categories_limit']=5;
         
        $ret['woocommerce_search_product']=false;
        $ret['woocommerce_addtocart']=false;
        $ret['woocommerce_prices']=false;
        $ret['fetch_items_on']=false;
        $ret['woocommerce']=false;
        $ret['front_cache']=60;
      
        $ret['results_to_show']=10;
        $opt= Func::getOption();

      
        if($opt){
  
            $ret['layout']=$opt['general']['layout'];

            $ret['selector_run']=!empty($opt['general']['selector_run']) ? $opt['general']['selector_run'] : false;
            if($ret['selector_run']){
                $ret['layout']='modal';
            }

 
            $ret['front_cache']=$opt['performance']['perf_front_cache_expire'];
            $ret['fetch_items_on']=$opt['performance']['perf_front_fetch_items'];
            $ret['newtab']=$opt['general']['newtab']=='yes';
            $ret['show_user_history']=$opt['general']['show_user_history']=='yes';
            
    
            $ret['redirect_to_search']=$opt['general']['redirect_to_search']=='yes';
            $ret['newtab']=$opt['general']['newtab']=='yes';
 


            $ret['show_images']=$opt['thumbnails']['show_images']=='yes';
            $ret['image_fit']=$opt['thumbnails']['image_fit'];
       
            $ret['results_to_show']=$opt['general']['results_to_show'];

  
            $ret['woocommerce']=$opt['woocommerce']['woocommerce']=='yes';
            $ret['woocommerce_search_product']=$opt['woocommerce']['redirect_to_search_product']=='yes';
            $ret['woocommerce']=$opt['woocommerce']['woocommerce']=='yes';
            $ret['woocommerce_addtocart']=$opt['woocommerce']['addtocart']=='yes';
            $ret['woocommerce_prices']=$opt['woocommerce']['prices']=='yes';
           

            $ret['show_categories']=$opt['general']['search_tax_terms']=='yes';
            $ret['show_categories_limit']=$opt['general']['search_tax_terms_count'];

        }
        
        /* strings */
        $strings=[];
        $strings['msg1'] = esc_html__('Additional results for','ric');
        $strings['msg2'] = esc_html__('Add to cart','ric');
        $strings['msg3'] = esc_html__('Additional info','ric');
        $strings['msg4'] = esc_html__('Popular terms','ric');
        $strings['Results'] = esc_html__('Results:','ric');
        $strings['More info'] = esc_html__('More info','ric');
        $strings['Added to cart'] = esc_html__('Added to cart','ric');
        $strings['Categories results'] = esc_html__('Categories Results','ric');
        $strings['No results found'] = esc_html__('No results found','ric');
        $strings['Loading data please wait'] = esc_html__('Loading data, please wait...','ric');
        $strings['What you are looking for today?']=!empty($opt['miscellaneous']['search_field_label']) ? $opt['miscellaneous']['search_field_label'] : '';

        $strings = \apply_filters('ric_strings', $strings);

        $ret['strings']=$strings;
        
        /*
        * popup layout template
        */
        $popUpHtml='';
        $popUpHtml.='<div role="alert" class="ric_modal">';
            $popUpHtml.='<div class="ric_modal_in">';
                $popUpHtml.='<a role="button" href="#" aria-label="'.esc_html__('Close','ric').'" class="ric_modal_toggle"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></a>';
               
                $popUpHtml.='<div class="ric_modal_body">';
                    $popUpHtml.= '<form class="ric_modal_form" action="'. home_url().'">';

                        $str = !empty($opt['miscellaneous']['search_field_label']) ? $opt['miscellaneous']['search_field_label'] : esc_html__('What you are looking for today?','ric'); 

                        $popUpHtml.= '<label class="" for="ric_s">'.esc_html__($str).'</label>';
                            $popUpHtml.= '<input type="text" name="s" class="col-1-1" id="ric_s" />';
                        $popUpHtml.= '</form>'; 
                        $popUpHtml.='<div class="ric_drop_box_outer">';
                        $popUpHtml.='<div class="ric_notfound_placeholder"></div>';
                       // $popUpHtml.='<div class="ric_drop_box_header"></div>';
                        $popUpHtml.='<div class="ric_drop_box"></div>';
                        $popUpHtml.='<div class="ric_drop_box_footer"></div>';
                        $popUpHtml.='<div class="ric_drop_box_footer2"></div>';
                        $popUpHtml.='</div>';
                $popUpHtml.='</div>';
            $popUpHtml.='</div>';
        $popUpHtml.='</div>';
        
        $ret['modalHtml']=$popUpHtml;



        $popUpHtml='<div role="alert" class="ric_drop_box_outer">';
        $popUpHtml.='<a role="button" href="#" aria-label="'.esc_html__('Close','ric').'" class="ric_modal_toggle"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></a>';
        $popUpHtml.='<div class="ric_notfound_placeholder"></div>';
     //   $popUpHtml.='<div class="ric_drop_box_header"></div>';
        $popUpHtml.='<div class="ric_drop_box"></div>';
        $popUpHtml.='<div class="ric_drop_box_footer"></div>';
        $popUpHtml.='<div class="ric_drop_box_footer2"></div>';
        $popUpHtml.='</div>';
        $ret['simpleHtml']=$popUpHtml;




        if($ret['woocommerce'] && 
        $ret['woocommerce_search_product']
        ){
            $ret['searchUrl']= \home_url ( '?post_type=product&s=' );
        }
        


        $ret = \apply_filters('ric_public_settings', $ret,$opt);


        return $ret;
    }
    


    

    public static function getItemHtml($item,$ricConf){

        //const resultItem = ric_Data.find((post) => post.id==obj);

        $isWoo = Func::isWoocommerceActive() && $item['post_type']=='product' && !empty($item['data']) ;



        $price = $isWoo && !empty($item['data']['wo_price_plain']) ? $item['data']['wo_price_plain'] : 0;
        $sku = $isWoo && !empty($item['data']['wo_sku']) ? $item['data']['wo_sku'] : 0;

       // $ricConf = \Ric\Func::getPublicSettings();
    
        $newtab = !empty($ricConf['newtab']) && $ricConf['newtab'];

        $woocommerce = !empty($ricConf['woocommerce']) && $ricConf['woocommerce'];
        $woocommerce_addtocart = !empty($ricConf['woocommerce_addtocart']) && $ricConf['woocommerce_addtocart'];
        $woocommerce_prices = !empty($ricConf['woocommerce_prices']) && $ricConf['woocommerce_prices'];
 
 

        $html='<li data-sku="'.$sku.'" data-price="'.$price.'" class="ric_items_list_li" data-id="'.$item['id'].'" data-type="item" data-title="'.$item['title'].'"   >';
       
    
       
        $html.='<div class=" ric_items_list_content_a"  >';
         
        if($ricConf['show_images'] && $item['image'] ){
            //image_fit


            $html.='<a '.($newtab ? ' target="_blank" ' : '').' href="'.$item['url'].'" class="'.($ricConf['image_fit'] ? 'ric_drop_box_image_'.$ricConf['image_fit'] : '').' rictrack ric_drop_box_image">';
            if( !empty($item['image'])){
                $html.='<img src="'.$item['image'].'" alt="'.$item['title'].'" />';
            }
            $html.='</a>';
        }


        $html.='<div class="ric_drop_box_content">';
        $html.='<a '.($newtab ? ' target="_blank" ' : '').' class="rictrack" href="'.$item['url'].'"><span class="ric_item_title">'.$item['title'].'</span></a>';
                if($isWoo &&  $woocommerce_prices){
                    $html.='<span class="price">'.$item['data']['wo_price'].'</span>';
                    }
                 
                    $html.='<div class="ricclearboth"></div>';

                      if(

                        $isWoo &&$woocommerce_addtocart &&
                        !empty($item['data']['wo_addtocart'])

                    ){


                        //is purchasable product
                        if(
                            !empty($item['data']['wo_addtocart']) && 
                            $item['data']['wo_type'] !='external' && 
                            $item['data']['wo_stock_status'] =='instock'){

                            //is variable
                            if($item['data']['wo_type']=='variable'){
                                $html.='<a '.($newtab ? ' target="_blank" ' : '').' data-id="'.$item['id'].'" data-type="variable" title="'.esc_html__('More info','ric').'"   href="'.$item['url'].'" class="ric_add_to_cart rictrack"   data-type="addtocart" data-title="'.$item['title'].'"  " >'.esc_html__('More info','ric').'</a>';
                            }else{
                                $html.='<a data-id="'.$item['id'].'" data-type="simple" role="button" aria-label="'.esc_html__('Add to cart','ric').'" href="#" role="button"   class="ric_add_to_cart js_ric_add_to_cart"   data-type="addtocart" data-title="'.$item['title'].'" >'.esc_html__('Add to cart','ric').'</a>';
                            }
                        }
               
            }
                
            $html.='<div class="ricclearboth"></div>';
            $html.='</div><div class="ricclearboth"></div>';
                          
            $html.='</div>';

            

            $html.='</li>';


        return $html;
    }



    public static function get_locale(){

        //get current language
        $lang= \get_locale ();
        $lang=  \substr( $lang, 0, 2 );
       
 

        //if not available and polylang installed then tried to
        if(\function_exists('pll_current_language')){
            $langTMP=  \pll_current_language('locale');
            if(!empty($langTMP)){
                $lang = $langTMP;
            }
        }

        //if still not available get wp default lang
        if(!$lang){
            $lang = \get_site_option( 'WPLANG' );
        }   

       
        return $lang;
    }


    /**
    * Return all synced items
    *
    * @since 1.0.0
    * @param none
    * @return array;
    */
    public static function getItems($locale=true){

        $ricConf = \Ric\Func::getPublicSettings();

        //perf_front_cache_expire


        $lang= $locale ? self::get_locale() : false;

         
        $args=[];
        $args['orderby'] = 'item_order';


        $toExclude = \Ric\Func::getExcludedItems();
        if(!empty($toExclude)){
            $args['exclude'] = $toExclude;
        }

        if($lang){
            $args['lang'] = $lang;
        }

        $args = \apply_filters('ric_getItems_args_query',$args,$ricConf);
 
        //front_cache
        if($ricConf['front_cache']!='none'){
            //caching items for 1 min
            $trnsName = 'ric_temp_items_plain';
            $items =get_transient($trnsName);
            if(!$items){
                $items = Db::select('items',$args);
                set_transient($trnsName,$items,intval($ricConf['front_cache']));
            }
        }else{
            $items = Db::select('items',$args);
        }

      

        $front=[];
        if(!empty($items)){
            foreach ($items as $livepost){
                $r=[];
                $r['keywords'] = $livepost->keywords;
                $r['title'] = $livepost->title;
                $r['ispinned'] = $livepost->ispinned;
                $r['image'] = $livepost->image;
                $r['url'] = $livepost->url;
                $r['lang'] = $livepost->lang;
                $r['id'] = $livepost->wp_id;
                $r['post_type'] = $livepost->wp_type;
                $r['obj_type'] = $livepost->wp_obj_type;
        
                $r['data'] = !empty($livepost->addition_data) ? unserialize($livepost->addition_data) : false;

                $r['html'] = Func::getItemHtml($r,$ricConf);
                $front[]=$r;
            } 
        }
        return $front;
    }


    public static function getSyncAdmin(){
        ob_start();
        ?>
        <form id="ric_sync_data" class="ric_sync_data">
            <h1><?php echo esc_html__('Sync data','ric'); ?></h1>
        
        
            <button class="button button-primary button-large"><?php echo esc_html__('Start a manual sync','ric'); ?></button> 
        
        
            <input type="hidden" name="action" value="ric_plugin_sync" />
            <input type="hidden" name="offset" value="0" />
        </form>
    
        <div class="ric_sync_status">
            <div class="ric_sync_status_bar"></div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();


        return $html;
    }

    /**
    * Return all terms items
    *
    * @since 1.0.0
    * @param none
    * @return array;
    */
    public static function getItemsTerms($locale=true){
 
        $ricConf = \Ric\Func::getPublicSettings();


        //is terms not enabled
        $opt= Func::getOption('general');
        if($opt['search_tax_terms']=='no'){
            return [];
        }

 

        $lang= $locale ? self::get_locale() : false;
        
  



        $args=[];
        $args['orderby'] = 'item_order';
        $args['lang'] = $lang;

        $args = \apply_filters('ric_getItemsTerms_args_query',$args,$ricConf);
 
        
        $liveposts = Db::select('taxonomies',$args);


         
 
        $front=[];
        if(!empty($liveposts)){
            foreach ($liveposts as $livepost){
                $r=[];
                $r['keywords'] = $livepost->keywords;
                $r['title'] = $livepost->title;
                $r['ispinned'] = $livepost->ispinned;
                $r['image'] = $livepost->image;
                $r['url'] = $livepost->url;
                $r['id'] = $livepost->wp_id;
                $r['post_type'] = $livepost->wp_type;
                $r['obj_type'] = $livepost->wp_obj_type;
                $r['data'] = !empty($livepost->addition_data) ? unserialize($livepost->addition_data) : false;
                $front[]=$r;
            } 
        }
        return $front;
    }
    


    






        /**
    * Get saved settings
    *
    * @since 1.0.0
    * @param string $name
    * @param string $def
    * @return array;
    */
    public static function getOption($name=false,$def=false){
        $opt=\get_option(RIC_SETTINGS_KEY);
 
  
        $opt = \apply_filters('ric_def_options', $opt);
      
         


        if($opt && $name && isset($opt[$name])){
            return $opt[$name];
        }




        return $opt;
    }

    /**
    * Get Excluded items
    *
    * @since 1.0.0
    * @return array;
    */
    public static function getExcludedItems(){
        $sectionName= 'advanced_roles';
        $fieldName='advanced_roles_exclude_posts';
        $opt=Func::getOption($sectionName);
 
        if(empty($opt) || empty($opt[$fieldName])){
            return [];
        }
 
        $ids=array_keys($opt[$fieldName]);
 
        if(empty($ids)){
            return [];
        }

        return $ids;
    }

    /**
    * Get Excluded terms
    *
    * @since 1.0.0
    * @return array;
    */
    public static function getExcludedTerms(){
        $sectionName= 'advanced_roles';
        $fieldName='advanced_roles_exclude_categories';
        $opt=Func::getOption($sectionName);

        if(empty($opt) || empty($opt[$fieldName])){
            return [];
        }
 
        $ids=array_keys($opt[$fieldName]);
 
        if(empty($ids)){
            return [];
        }

        return $ids;
    }





    /* 
     * taxonomies to sync
     */
    public static function getTaxonomies(){   
        $taxEclude=['product_shipping_class','post_format','sc_coupon_category','product_visibility',
        'wp_theme','link_category','product_type','nav_menu','wp_template_part_area'];
        $ret=[];
        $args = []; 
          $taxonomies = \get_taxonomies( $args); 
        if(!empty($taxonomies)){
            foreach ($taxonomies as $tax){
                if(in_array($tax,$taxEclude)){
                    continue;
                } 
                $ret[]=$tax;
            }
        }
        return $ret;
    }


    public static function getPostTypes(){
        $args = array(
            'public'   => true
        );
        $post_types = \get_post_types( $args );
    
        $ret=[];
        if(!empty($post_types)){
            foreach ($post_types as $post_type){
                if($post_type=='attachment'){
                    continue;
                }
                $ret[]=$post_type;
            }
        }
    
        return $ret;
    }




    /**
    * Start a sync via cron job
    *
    * @since 1.0.0
    * @param none
    * @return null
    */
    public static function doSyncCronPosts(){
        $selectes = Func::getPostTypesToSync();
        if(empty($selectes)){
            return false;
        }
        $offset = \get_option('ric_last_cronjob_offset',0);
        if(!$offset){
            $offset = 0;
        }
        
        $settings=Func::getOption('indexing');
        $performance=Func::getOption('performance');
        $per_page=$performance['items_per_sync'];
 
        /*
        * query for selected tyoes
        */
        $args=[];
        $args['offset']=$offset;
        $args['posts_per_page']=$per_page;
        $args['post_type']=$selectes;
        $prs= \get_posts($args);
        $nextOffset = $offset+$per_page;

    
        if(!empty($prs)){
            foreach ($prs as $pr){
                Func::syncPost($pr);
            }
        }else{
            /*
            * if no results start from the zero
            */
            $nextOffset = 0;
        }
        /*
        * set the new offset
        */
        update_option('ric_last_cronjob_offset',$nextOffset);
    }




        
    /**
    * Get selected post types
    *
    * @since 1.0.0
    * @param none
    * @return null
    */
    public static function getPostTypesToSync(){
        $types=Func::getOption('sync');
        if(empty($types)){
            return [];
        }
        $selectes=[];
        foreach ($types as $name=>$issync){
            if($issync==='yes'){
                $selectes[]=$name;
            }
        }
        return $selectes;
    }
    public static function getTaxonomoesToSync(){
        $types=Func::getOption('sync_tax');
        if(empty($types)){
            return [];
        }
        $selectes=[];
        foreach ($types as $name=>$issync){
            if($issync==='yes'){
                $selectes[]=$name;
            }
        }
        return $selectes;
    }


        /**
    * Start a sync single post
    *
    * @since 1.0.0
    * @param object $post WP_POST
    * @return null
    */
    public static function syncPost($post,$settings=[]){
 

        if(empty($settings)){
            $settings=Func::getOption();
        }


        $indexingOptions = !empty($settings['indexing']) ? $settings['indexing'] : false;
        $wooOptions = !empty($settings['woocommerce']) ? $settings['woocommerce'] : false;


        $words=[];

 


        if($indexingOptions && $indexingOptions['indexing_title']=='yes'){
            $words[]= $post->post_title;
        }

        if($indexingOptions && $indexingOptions['indexing_post_content']=='yes' && !empty($post->post_content)){
            $words[]= \strip_tags($post->post_content);
        }

        if($indexingOptions && $indexingOptions['indexing_post_excerpt']=='yes' && !empty($post->post_excerpt)){
            $words[]= \strip_tags($post->post_excerpt);
        }
    



        //index post meta
    

            


        $imageUrl=false;
        $imageKey = '_thumbnail_id'; 

     
        if($settings && !empty($settings['thumbnails']) && !empty($settings['thumbnails']['meta_image_key'])){
            $imageKey =$settings['thumbnails']['meta_image_key'];
        }


        $imageSizeSlug= 'thumbnail'; 
        if($settings && !empty($settings['thumbnails']) && !empty($settings['thumbnails']['image_size_slug'])){
            $imageSizeSlug =$settings['thumbnails']['image_size_slug'];
        }


        $image= \get_post_meta($post->ID,$imageKey,true);
        if($image){
            $img= \wp_get_attachment_image_src((int)$image,$imageSizeSlug);
            if($img){
                $imageUrl = $img[0];
            }
        }
        
        $url= \get_permalink($post);
    

    
        $words = \apply_filters('ric_settings_words_to_sync',$words,$post);

    

        $additionData=[];


        $hideProduct = 0;

        /* woo support */ 
        if(Func::isWoocommerceActive() && 
            $post->post_type=='product'){

                //sku
            $product = \wc_get_product( $post->ID );
            if(!$product){
                return;
            }
    
 
            if(!empty($wooOptions['sku']) && $wooOptions['sku']=='yes'){
                $sku= $product->get_sku();
                if($sku){
                    $words[]=\strip_tags($sku);
                }
            }
     
           


            $additionData['wo_sku']= $product->get_sku();
            $additionData['wo_price']= $product->get_price_html();
            $additionData['wo_price_plain']= $product->get_price();
            $additionData['wo_type']= $product->get_type();
       
            $additionData['wo_stock_status']= 'instock';
            if($additionData['wo_type']!='external'){
                $additionData['wo_stock_status']= $product->get_stock_status() ;
            }
        

            $hideProduct = 0;

            $hideProduct = \apply_filters('ric_hide_product_on_sync',$hideProduct,$wooOptions,$additionData);
          

            $additionData['wo_variations']=[];
            if($additionData['wo_type']=='variable'){
                    $variations = $product->get_available_variations();
                    if(!empty($variations)){
                        foreach ($variations as $variationd)
                        {
                            $variation = new \WC_Product_Variation($variationd['variation_id']);
                            $variationUrl = $variation->is_purchasable() ? remove_query_arg(
                                'added-to-cart',
                                add_query_arg(
                                        array(
                                                'variation_id' => $variation->get_id(),
                                                'add-to-cart'  => $variation->get_parent_id(),
                                        ),
                                        $product->add_to_cart_url()
                                )
                            ) : $product->add_to_cart_url();
                            if($variationd['is_in_stock']){
                                $additionData['wo_variations'][]=$variationUrl;
                            }
                        }
                    }
                    $additionData['wo_addtocart']= get_permalink($post->ID);
            }else{
                $additionData['wo_addtocart']= $product->add_to_cart_url();
            }
        }


   
        $additionData = \apply_filters('ric_post_sync_addition',$additionData, $post);



        $words= \implode(' ',$words);
        $words= \strtolower($words);


        /*
        * if there any sync data then modify if not insert
        */
 
         
        $args=[];
        $args['multiplekeys'] = [];
        $args['multiplekeys'][] = [
            'key'=>'wp_id',
            'operator'=>'=',
            'value'=>$post->ID,
        ];
        $args['multiplekeys'][] = [
            'key'=>'wp_type',
            'operator'=>'=',
            'value'=>$post->post_type,
        ];
        $liveposts = Db::select('items',$args);



        $lang= self::get_locale();
 
       //polylang
       if(\function_exists('pll_get_post_language')){
            $lang =  \pll_get_post_language( $post->ID, 'locale' );
       }

 


        $additionData = \serialize($additionData);
        if(empty($liveposts)){
            $args=[
                'keywords' => $words, 
                'title' =>$post->post_title,
                'image' => $imageUrl,
                'addition_data' => $additionData,
                'item_order' => 999999,
                'url' => $url,
                'lang' => $lang,
                'ishidestock' => $hideProduct,
                'wp_id' => $post->ID,
                'wp_obj_type' =>'post',
                'wp_type' =>$post->post_type,
            ];
            Db::insert('items',$args);

           
        }else{
            $livepost=$liveposts[0];
            

            $args=[
                'keywords' => $words, 
                'title' =>$post->post_title,
                'addition_data' => $additionData,
                'image' => $imageUrl,
                'lang' => $lang,
                'ishidestock' => $hideProduct,
                'url' => $url
            ];
            Db::updateById('items',$livepost->id,$args);

        }
    }


    public static function compress($buffer) {
                /* remove comments */
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        return $buffer;
        }



    /**
    * Start a sync term
    *
    * @since 1.0.0
    * @param object $term WP_POST
    * @return null
    */
    public static function syncTerm($term,$settings=[]){
   

        if(empty($settings)){
            $settings=Func::getOption();
        }
        
    

        $indexingOptions = !empty($settings['indexing_tax']) ? $settings['indexing_tax'] : false;

        $words=[]; 



        //index name
        if($indexingOptions && $indexingOptions['indexing_term_title']=='yes'){
            $words[]= $term->name;
        }

        //index description
        if($indexingOptions && $indexingOptions['indexing_term_description']=='yes'){
            $words[]= $term->description;
        }


        //nothing to index
        if(empty($words)){
            return;
        }
        

        $imageUrl='';

        $additionData=[];

        $url =\get_term_link($term);

        $words= \implode(' ',$words);
        $words= \strtolower($words);
        

        /*
        * if there any sync data then modify if not insert
        */



        

        $lang= self::get_locale();
 
       //polylang
       if(\function_exists('pll_get_term_language')){
            $lang =  \pll_get_term_language($term->term_id, 'locale' );
       }


   
     

        $args=[];
        $args['multiplekeys'] = [];
        $args['multiplekeys'][] = [
            'key'=>'wp_id',
            'operator'=>'=',
            'value'=>$term->term_id
        ];
        $args['multiplekeys'][] = [
            'key'=>'wp_type',
            'operator'=>'=',
            'value'=>$term->taxonomy
        ];
        $liveposts = Db::select('taxonomies',$args);

        
         

        $additionData = \serialize($additionData);
        if(empty($liveposts)){
            $args=[
                'keywords' => $words, 
                'title' =>$term->name,
                'image' => $imageUrl,
                'addition_data' => $additionData,
                'item_order' => 999999,
                'url' => $url,
                'lang' => $lang,
                'wp_id' => $term->term_id,
                'wp_obj_type' =>'term',
                'wp_type' =>$term->taxonomy,
            ];
            Db::insert('taxonomies',$args);

           
        }else{
            $livepost=$liveposts[0];
            

            $args=[
                'keywords' => $words, 
                'title' =>$term->name,
                'addition_data' => $additionData,
                'image' => $imageUrl,
                'lang' => $lang,
                'url' => $url
            ];
            Db::updateById('taxonomies',$livepost->id,$args);

        }

 
    }
    
    


}

  

 


 

 
